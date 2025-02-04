<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 *
 * API endpoints for user authentication, including registration, login, and logout.
 */
class UserAuthController extends Controller
{
    /**
     * Register a new user
     *
     * @bodyParam name string required The user's full name. Example: John Doe
     * @bodyParam email string required The user's email address. Example: johndoe@example.com
     * @bodyParam password string required Must be at least 8 characters long. Example: secret123
     *
     * @response 201 {
     *    "message": "User Created"
     * }
     */
    public function register(Request $request)
    {
        $registerUserData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
        ]);

        return response()->json([
            'message' => 'User Created',
        ], 201);
    }

    /**
     * Log in a user
     *
     * @bodyParam email string required The user's email address. Example: johndoe@example.com
     * @bodyParam password string required Must be at least 8 characters long. Example: secret123
     *
     * @response 200 {
     *    "access_token": "your-access-token"
     * }
     * @response 401 {
     *    "message": "Invalid Credentials"
     * }
     */
    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $loginUserData['email'])->first();

        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ], 200);
    }

    /**
     * Log out the authenticated user
     *
     * @authenticated
     *
     * @response 200 {
     *    "message": "logged out"
     * }
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ], 200);
    }
}
