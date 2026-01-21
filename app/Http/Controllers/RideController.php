<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Log;
// use App\Models\Ride; 
// use App\Models\User;

// class RideController extends Controller
// {
//     // Create Ride Request
// public function createRideRequest(Request $req)
// {
//   
//     $authUser = $req->user();
//     Log::error(' Authenticated user ID from token: '.($authUser->id ?? 'null'));
//     
//     $user = User::find($authUser->id ?? 0);
//     if (!$user) {
//         Log::warning('Step 0.1: User does not exist in database.');
//         return response()->json([
//             'success' => false,
//             'message' => 'User not found'
//         ], 404);
//     }
//     
//     $validator = Validator::make($req->all(), [
//         'pickup_location' => 'required|string|max:255',
//         'drop_location' => 'required|string|max:255',
//         'ride_type' => 'required|in:walking,outing,hangout',
//         'scheduled_at' => 'nullable|date'
//     ]);

//     if ($validator->fails()) {
//         Log::error('Step 2: Validation failed: '.json_encode($validator->errors()));
//         return response()->json([
//             'success' => false,
//             'message' => 'Validation Error',
//             'errors' => $validator->errors()
//         ], 422);
//     }
//    
//     $ride = Ride::create([
//         'user_id' => $user->id,
//         'pickup_location' => $req->pickup_location,
//         'drop_location' => $req->drop_location,
//         'ride_type' => $req->ride_type,
//         'scheduled_at' => $req->scheduled_at,
//         'status' => 'pending'
//     ]);

//   
//     return response()->json([
//         'success' => true,
//         'message' => 'Ride request created successfully',
//         'ride' => $ride
//     ]);
// }


