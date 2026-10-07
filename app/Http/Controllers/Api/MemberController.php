<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MemberController extends Controller
{
    /**
     * Display a listing of flat members
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $members = $query->latest()->get()->map(function ($user) {
            return [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role,
                'is_admin'   => $user->isAdmin(),
                'status'     => $user->status,
                'is_active'  => $user->is_active,
                'avatar_url' => $user->avatar_url,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $members,
        ]);
    }

    /**
     * Store a newly created flat member
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'role'     => 'required|in:1,2',
            'password' => 'required|string|min:6',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'   => 'required|in:1,2',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $uploadPath = public_path('backend/upload/userprofile');

            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            $avatarName = 'user_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $avatarName);
            $avatarPath = 'backend/upload/userprofile/' . $avatarName;
        }

        $roleId = (int)$request->input('role');
        $user = User::create([
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'phone'     => $request->input('phone'),
            'role'      => $roleId,
            'password'  => Hash::make($request->input('password')),
            'avatar'    => $avatarPath,
            'is_active' => (int)$request->input('status'),
            'status'    => (int)$request->input('status'),
        ]);

        // Assign Spatie Role
        $roleName = ($roleId === 1) ? 'Admin' : 'Member';
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $user->assignRole($role);

        return response()->json([
            'success' => true,
            'message' => 'Member added successfully.',
            'data'    => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role,
                'status'     => $user->status,
                'avatar_url' => $user->avatar_url,
            ],
        ], 201);
    }

    /**
     * Show single member details
     */
    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role,
                'status'     => $user->status,
                'avatar_url' => $user->avatar_url,
            ],
        ]);
    }

    /**
     * Update an existing member
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email,' . $user->id,
            'phone'    => 'required|string|max:20|unique:users,phone,' . $user->id,
            'role'     => 'required|in:1,2',
            'password' => 'nullable|string|min:6',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'   => 'required|in:1,2',
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $uploadPath = public_path('backend/upload/userprofile');

            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }

            $avatarName = 'user_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $avatarName);
            $user->avatar = 'backend/upload/userprofile/' . $avatarName;
        }

        $user->name      = $request->input('name');
        $user->email     = $request->input('email');
        $user->phone     = $request->input('phone');
        $user->role      = (int)$request->input('role');
        $user->status    = (int)$request->input('status');
        $user->is_active = (int)$request->input('status');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Member details updated successfully.',
            'data'    => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role,
                'status'     => $user->status,
                'avatar_url' => $user->avatar_url,
            ],
        ]);
    }

    /**
     * Delete a member
     */
    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->avatar && File::exists(public_path($user->avatar))) {
            File::delete(public_path($user->avatar));
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully.',
        ]);
    }

    /**
     * Toggle status (Active / Inactive)
     */
    public function toggleStatus(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $newStatus = ($user->status == 1) ? 2 : 1;
        $user->status = $newStatus;
        $user->is_active = $newStatus;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Member status updated.',
            'status'  => $newStatus,
        ]);
    }
}
