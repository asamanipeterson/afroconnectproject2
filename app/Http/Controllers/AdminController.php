<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use App\Models\PostReport;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * -------------------------
     * NEW / ADDED METHOD
     * -------------------------
     * Display all users in the admin panel
     */
    public function users()
    {
        // Fetch all users including soft-deleted ones, with followers and reports count
        // ADDED: 'withTrashed' in case some users are soft-deleted
        $users = User::withTrashed()
            ->withCount(['followers', 'reports']) // ADDED: followers_count & reports_count
            ->get();

        // Return view with $users variable for Blade
        return view('Admin.users.index', compact('users')); // ADDED: ensure Blade has $users
    }

    /**
     * Promote a regular user to an admin.
     */
    public function promoteUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->user_type === 'admin') {
            return response()->json(['success' => false, 'message' => 'User is already an admin.'], 409);
        }

        $user->user_type = 'admin';
        $user->save();

        return response()->json(['success' => true, 'message' => 'User promoted to admin successfully.']);
    }

    /**
     * Demote an admin back to a regular user.
     */
    public function demoteUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if (auth()->id() === $user->id) {
            return response()->json(['success' => false, 'message' => 'You cannot demote yourself.'], 403);
        }

        if ($user->user_type === 'user') {
            return response()->json(['success' => false, 'message' => 'User is not an admin.'], 409);
        }

        $user->user_type = 'user';
        $user->save();

        return response()->json(['success' => true, 'message' => 'User demoted successfully.']);
    }

    /**
     * -------------------------
     * OPTIONAL: Soft-delete / block user
     * -------------------------
     * Could add methods to block/unblock, suspend/unsuspend if needed
     */
}
