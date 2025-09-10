<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Promote a regular user to an admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function promoteUser(Request $request)
    {
        // Validate the request to ensure a user_id is provided
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if the user is already an admin to prevent unnecessary updates
        if ($user->user_type === 'admin') {
            return response()->json(['success' => false, 'message' => 'User is already an admin.'], 409);
        }

        // Update the user_type to 'admin'
        $user->user_type = 'admin';
        $user->save();

        return response()->json(['success' => true, 'message' => 'User promoted to admin successfully.']);
    }

    /**
     * Demote an admin back to a regular user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function demoteUser(Request $request)
    {
        // Validate the request to ensure a user_id is provided
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Prevent a user from demoting themselves
        if (auth()->id() === $user->id) {
            return response()->json(['success' => false, 'message' => 'You cannot demote yourself.'], 403);
        }

        // Check if the user is a regular user to prevent unnecessary updates
        if ($user->user_type === 'user') {
            return response()->json(['success' => false, 'message' => 'User is not an admin.'], 409);
        }

        // Update the user_type to 'user'
        $user->user_type = 'user';
        $user->save();

        return response()->json(['success' => true, 'message' => 'User demoted successfully.']);
    }
}
