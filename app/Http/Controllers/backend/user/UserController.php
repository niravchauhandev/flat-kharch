<?php

namespace App\Http\Controllers\backend\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display all Flat Members
     */
    public function index(Request $request)
    {
        $data['title'] = 'Flat Members || ' . get_project_name();
        $data['header'] = [
            'title' => 'Flat Members Management',
            'subtitle' => 'Manage Flatmates, Roles and Access Credentials',
            'breadcrumb' => [
                'Home' => route('dashboard'),
                'Members' => '',
            ]
        ];
        $data['js'] = ['comman_function.js', 'user.js'];
        $data['funinit'] = ['User.init()'];

        $query = User::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $data['users'] = $query->latest()->paginate(10)->withQueryString();

        return view('backend.pages.user.list', $data);
    }

    /**
     * Show form for adding a new Flat Member
     */
    public function create()
    {
        $data['title'] = 'Add New Member || ' . get_project_name();
        $data['header'] = [
            'title' => 'Add New Flatmate',
            'subtitle' => 'Create credentials for your flatmate to access Flat-Kharch',
            'breadcrumb' => [
                'Home' => route('dashboard'),
                'Members' => route('user'),
                'Add Member' => '',
            ]
        ];
        $data['js'] = ['comman_function.js', 'user.js'];
        $data['funinit'] = ['User.initAdd()'];

        return view('backend.pages.user.add', $data);
    }

    /**
     * Store a newly created Flat Member
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'role'     => 'required|in:1,2',
            'password' => 'required|string|min:6',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'   => 'required|in:1,2',
        ], [
            'email.unique' => 'This email is already registered with another member.',
            'phone.unique' => 'This phone number is already registered with another member.',
        ]);

        $avatarName = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $uploadPath = public_path('backend/upload/userprofile');
            
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            $avatarName = 'user_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $avatarName);
        }

        $roleId = (int)$request->input('role');
        $user = User::create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'phone'     => $request->input('phone'),
            'role'      => $roleId,
            'password'  => Hash::make($request->input('password')),
            'avatar'    => $avatarName ? 'backend/upload/userprofile/' . $avatarName : null,
            'is_active' => (int)$request->input('status'),
            'status'    => (int)$request->input('status'),
        ]);

        // Assign Spatie Role (Admin or Member)
        $roleName = ($roleId === 1) ? 'Admin' : 'Member';
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $user->assignRole($role);

        if ($request->ajax()) {
            return response()->json([
                'status'   => 'success',
                'message'  => 'Member "' . $user->name . '" added successfully!',
                'redirect' => route('user'),
            ]);
        }

        return redirect()->route('user')->with('success', 'Member "' . $user->name . '" added successfully!');
    }

    /**
     * Show form for editing an existing Flat Member
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $data['title'] = 'Edit Member || ' . get_project_name();
        $data['header'] = [
            'title' => 'Edit Flatmate Details',
            'subtitle' => 'Update ' . $user->name . '\'s details and credentials',
            'breadcrumb' => [
                'Home' => route('dashboard'),
                'Members' => route('user'),
                'Edit Member' => '',
            ]
        ];
        $data['user'] = $user;
        $data['js'] = ['comman_function.js', 'user.js'];
        $data['funinit'] = ['User.initEdit()'];

        return view('backend.pages.user.edit', $data);
    }

    /**
     * Update an existing Flat Member
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email,' . $id,
            'phone'    => 'required|string|max:20|unique:users,phone,' . $id,
            'role'     => 'required|in:1,2',
            'password' => 'nullable|string|min:6',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'   => 'required|in:1,2',
        ]);

        // Prevent self-demotion or self-deactivation by the logged-in admin
        if (Auth::id() === $user->id) {
            if ((int)$request->input('role') !== 1 || (int)$request->input('status') !== 1) {
                if ($request->ajax()) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'You cannot change your own admin role or deactivate yourself.',
                    ], 422);
                }
                return back()->with('error', 'You cannot change your own admin role or deactivate yourself.');
            }
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $uploadPath = public_path('backend/upload/userprofile');

            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            // Remove old avatar if exists
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }

            $avatarName = 'user_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $avatarName);
            $user->avatar = 'backend/upload/userprofile/' . $avatarName;
        }

        $roleId = (int)$request->input('role');
        $user->name      = $request->input('name');
        $user->email     = $request->input('email');
        $user->phone     = $request->input('phone');
        $user->role      = $roleId;
        $user->status    = (int)$request->input('status');
        $user->is_active = (int)$request->input('status');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        // Sync Spatie Role
        $roleName = ($roleId === 1) ? 'Admin' : 'Member';
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $user->syncRoles([$role]);

        if ($request->ajax()) {
            return response()->json([
                'status'   => 'success',
                'message'  => 'Member updated successfully!',
                'redirect' => route('user'),
            ]);
        }

        return redirect()->route('user')->with('success', 'Member updated successfully!');
    }

    /**
     * Delete a Flat Member
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            if ($request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You cannot delete your own account.',
                ], 422);
            }
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Delete avatar file
        if ($user->avatar && File::exists(public_path($user->avatar))) {
            File::delete(public_path($user->avatar));
        }

        $userName = $user->name;
        $user->delete();

        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Member "' . $userName . '" has been removed.',
            ]);
        }

        return redirect()->route('user')->with('success', 'Member "' . $userName . '" has been removed.');
    }

    /**
     * Toggle User Status (Active / Inactive) via AJAX
     */
    public function changeStatus(Request $request)
    {
        $userId = $request->input('id');
        $user = User::findOrFail($userId);

        if (Auth::id() === $user->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You cannot deactivate your own account.',
            ], 422);
        }

        $newStatus = ((int)$user->status === 1) ? 2 : 1;
        $user->status = $newStatus;
        $user->is_active = $newStatus;
        $user->save();

        return response()->json([
            'status'     => 'success',
            'message'    => 'Status updated to ' . ($newStatus === 1 ? 'Active' : 'Inactive'),
            'new_status' => $newStatus,
        ]);
    }
}
