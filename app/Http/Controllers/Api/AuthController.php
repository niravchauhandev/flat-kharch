<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Authenticate user and issue Sanctum token (supports Email OR Phone)
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login'    => 'required|string', // Email or Phone
            'password' => 'required|string',
        ]);

        $loginInput = $request->input('login');
        $password = $request->input('password');

        $user = User::where('email', $loginInput)
            ->orWhere('phone', $loginInput)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email/phone or password.',
            ], 401);
        }

        if ((int)$user->status !== 1 || (int)$user->is_active !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is deactivated. Please contact Flat Admin.',
            ], 403);
        }

        // Revoke previous tokens optionally or keep multiple devices
        // $user->tokens()->delete();

        $token = $user->createToken('Flat_Kharch')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data'    => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user'  => [
                    'id'          => $user->id,
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'phone'       => $user->phone,
                    'role'        => $user->role,
                    'is_admin'    => $user->isAdmin(),
                    'avatar_url'  => $user->avatar_url,
                    'roles'       => $user->getRoleNames(),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                ],
            ],
        ]);
    }

    /**
     * Get currently authenticated user details
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'phone'       => $user->phone,
                'role'        => $user->role,
                'is_admin'    => $user->isAdmin(),
                'avatar_url'  => $user->avatar_url,
                'roles'       => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ],
        ]);
    }

    /**
     * Logout and revoke current token
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }
}
