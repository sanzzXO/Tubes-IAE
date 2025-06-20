<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    //Register API
    public function register(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Return a response
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully', 
            'user' => $user
        ]);
    }

    //Login API
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if(!empty($user)) {
            // Check if the password is correct
            if (Hash::check($request->password, $user->password)) {
                // Generate a JWT token
                $token = $user->createToken('auth-token')->plainTextToken;

                return response()->json([
                    'status' => true,
                    'message' => 'Logged in successfully',
                    'token' => $token,
                    'user' => $user
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Password is incorrect'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User does not exist'
            ]);
        }
    }

    //Logout API
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    //Get User Details API
    public function profile(Request $request){
        $userdata = auth()->user();
        // Return the authenticated user
        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully',
            'user' => $userdata
        ]);
    }

    //Get All Users API
    public function users()
    {
        $users = \App\Models\User::all();
        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }

    //Get Single User API
    public function getUser($id)
    {
        $user = \App\Models\User::find($id);
        if ($user) {
            return response()->json([
                'status' => true,
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }
    }
}
