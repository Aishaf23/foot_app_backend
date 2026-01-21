<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    // 3.1 Get My Profile
    public function getProfile(Request $req)
    {
        $user = $req->user();

        return response()->json([
            'success' => true,
            'message' => 'Profile fetched successfully',
            'user_data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
                'profile_image' => $user->profile_image ? asset('storage/'.$user->profile_image) : null,
                'bio' => $user->bio,
                'address' => $user->address,
                'profession' => $user->profession,
                'vehicle_type' => $user->vehicle_type,
                'vehicle_number' => $user->vehicle_number,
                'is_verified' => $user->is_verified,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    // 3.2 Update Profile (POST method now)
   public function updateProfile(Request $req)
{
    $authUser = $req->user(); // Step 0️⃣: Get logged-in user
    Log::info("Step 0️⃣: Starting profile update for user: {$authUser->id}");

    // Step 1️⃣: Validate input
    $validator = Validator::make($req->all(), [
        'name' => 'sometimes|string|max:255',
        'phone' => 'sometimes|string|max:20',
        'bio' => 'sometimes|string|max:500',
        'address' => 'sometimes|string|max:255',
        'profession' => 'sometimes|string|max:100',
        'profile_image' => 'sometimes|file|mimes:jpeg,jpg,png|max:5120', // allow common image types
        'vehicle_type' => 'sometimes|string|max:50',
        'vehicle_number' => 'sometimes|string|max:50',
    ]);

    if ($validator->fails()) {
        Log::error("Step 1️⃣: Validation failed: " . json_encode($validator->errors()));
        return response()->json([
            'success' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422);
    }
    Log::info("Step 1️⃣: Validation passed");

    // Step 2️⃣: Update profile image if uploaded
    if ($req->hasFile('profile_image')) {
        Log::info("Step 2️⃣: Profile image uploaded");

        // Delete old image if exists
        if ($authUser->profile_image) {
            Storage::disk('public')->delete($authUser->profile_image);
            Log::info("Step 2️⃣: Old profile image deleted: {$authUser->profile_image}");
        }

        // Store new image
        $path = $req->file('profile_image')->store('profile_images', 'public');
        $authUser->profile_image = $path;
        Log::info("Step 2️⃣: New profile image stored at: {$path}");
    } else {
        Log::info("Step 2️⃣: No profile image uploaded");
    }

    // Step 3️⃣: Update other fields (even if null)
    $fields = ['name','phone','bio','address','profession','vehicle_type','vehicle_number'];
    foreach ($fields as $field) {
        if ($req->exists($field)) {
            $authUser->$field = $req->$field; // update field
            Log::info("Step 3️⃣: Field updated: {$field} = " . json_encode($req->$field));
        } else {
            Log::info("Step 3️⃣: Field not present in request: {$field}");
        }
    }

    // Step 4️⃣: Save user
    $saved = $authUser->save();
    Log::info("Step 4️⃣: User saved: " . ($saved ? 'success' : 'failed'));

    // Step 5️⃣: Return response
    Log::info("Step 5️⃣: Returning updated user profile");
    return response()->json([
        'success' => true,
        'message' => 'Profile updated successfully',
        'user_data' => [
            'id' => $authUser->id,
            'name' => $authUser->name,
            'email' => $authUser->email,
            'role' => $authUser->role,
            'phone' => $authUser->phone,
            'profile_image' => $authUser->profile_image ? asset('storage/'.$authUser->profile_image) : null,
            'bio' => $authUser->bio,
            'address' => $authUser->address,
            'profession' => $authUser->profession,
            'vehicle_type' => $authUser->vehicle_type,
            'vehicle_number' => $authUser->vehicle_number,
            'is_verified' => $authUser->is_verified,
            'created_at' => $authUser->created_at,
            'updated_at' => $authUser->updated_at,
        ]
    ], 200);
}

}
