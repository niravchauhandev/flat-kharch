<?php

namespace App\Http\Controllers\backend\authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display Login Page
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $data['title'] = 'Login || ' . get_project_name();
        $data['header'] = [
            'title' => 'Sign In',
        ];
        $data['js'] = ['comman_function.js', 'login.js'];
        $data['funinit'] = ['Login.init()'];

        return view('backend.pages.authentication.login.list', $data);
    }

    /**
     * Handle Login Authentication Request (Supports Email OR Phone Number)
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $request->input('email');
        $password = $request->input('password');
        $remember = $request->has('remember');

        // Check user by Email OR Phone
        $user = User::where('email', $loginInput)
            ->orWhere('phone', $loginInput)
            ->first();

        if (!$user) {
            if ($request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid email/phone or password.',
                ], 422);
            }
            return back()->with('error', 'Invalid email/phone or password.')->withInput();
        }

        // Check if user is active
        if ((int)$user->status !== 1 || (int)$user->is_active !== 1) {
            if ($request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Your account is deactivated. Please contact Flat Admin.',
                ], 403);
            }
            return back()->with('error', 'Your account is deactivated. Please contact Flat Admin.');
        }

        // Attempt login
        if (Auth::attempt(['id' => $user->id, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            if ($request->ajax()) {
                return response()->json([
                    'status'   => 'success',
                    'message'  => 'Login successful. Redirecting...',
                    'redirect' => route('dashboard'),
                ]);
            }
            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
        }

        if ($request->ajax()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Incorrect password. Please try again.',
            ], 422);
        }

        return back()->with('error', 'Incorrect password. Please try again.')->withInput();
    }

    /**
     * Handle User Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
