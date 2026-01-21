<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;



class AuthController extends Controller
{

public function userSignup(Request $req)
{
    // Check if email already exists
    if (User::where('email', $req->email)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Email already exists',
        ], 409);
    }

    // Validate request
    $validator = Validator::make($req->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        // 'role' => 'required|in:user,buddy'
        'role' => 'nullable|in:user,buddy,admin' 
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422);
    }

    // Create user
    $user = User::create([
        'name' => $req->name,
        'email' => $req->email,
        'password' => bcrypt($req->password),
        // 'role' => $req->role, 
        'role' => $req->role ?? 'user',

    ]);

    // Create token
    $token = $user->createToken('MyAuthApp')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'User created successfully.',
        'token' => $token,
        'user_data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role, 
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]
    ], 201);
}

public function buddySignup(Request $req)
{
    // Check if email already exists
    if (User::where('email', $req->email)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Email already exists',
        ], 409);
    }

    // Validate request
    $validator = Validator::make($req->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone' => 'required|string|max:20',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'bio' => 'nullable|string|max:500',
        'address' => 'nullable|string|max:255',
        'profession' => 'nullable|string|max:100',
        'vehicle_type' => 'required|string|max:50',
        'vehicle_number' => 'required|string|max:50',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422);
    }

    // Upload profile image if provided
    $imagePath = null;
    if ($req->hasFile('profile_image')) {
        $imagePath = $req->file('profile_image')->store('profile_images', 'public');
    }

    // Create buddy
    $buddy = User::create([
        'name' => $req->name,
        'email' => $req->email,
        'password' => bcrypt($req->password),
        'role' => 'buddy', // force role to buddy
        'phone' => $req->phone,
        'profile_image' => $imagePath,
        'bio' => $req->bio,
        'address' => $req->address,
        'profession' => $req->profession,
        'vehicle_type' => $req->vehicle_type,
        'vehicle_number' => $req->vehicle_number,
        'is_verified' => false, // default false
    ]);

    // Create token
    $token = $buddy->createToken('MyAuthApp')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Buddy registered successfully.',
        'token' => $token,
        'buddy_data' => [
            'id' => $buddy->id,
            'name' => $buddy->name,
            'email' => $buddy->email,
            'role' => $buddy->role,
            'phone' => $buddy->phone,
            'profile_image' => $buddy->profile_image,
            'bio' => $buddy->bio,
            'address' => $buddy->address,
            'profession' => $buddy->profession,
            'vehicle_type' => $buddy->vehicle_type,
            'vehicle_number' => $buddy->vehicle_number,
            'is_verified' => $buddy->is_verified,
            'created_at' => $buddy->created_at,
            'updated_at' => $buddy->updated_at,
        ]
    ], 201);
}


    public function login(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $req->email)->first();

        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        // Create Sanctum token
        $token = $user->createToken(
            'MyAuthApp',
            [],
            Carbon::now()->addDays(2) // <-- Token expires in 2 days
            )->plainTextToken;

        return response()->json([
    'success' => true,
    'message' => 'Login successful',
    'token' => $token,
    'user_data' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'password' => $user->password,
        'role' => $user->role,
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,
    ]
], 200);

    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
