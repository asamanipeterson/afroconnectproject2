<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::withCount(['followers', 'reports'])
            ->where('id', '!=', Auth::id())
            ->get();

        return view('Admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->loadCount(['followers', 'reports']);
        return view('Admin.users.show', compact('user'));
    }

    public function block(User $user)
    {
        if ($user->reports_count >= 3) {
            $user->update(['is_blocked' => true]);
            return redirect()->route('admin.users.index')->with('success', 'User blocked successfully.');
        }
        return redirect()->route('admin.users.index')->with('error', 'User has insufficient reports to be blocked.');
    }

    public function unblock(User $user)
    {
        $user->update(['is_blocked' => false]);
        return redirect()->route('admin.users.index')->with('success', 'User unblocked successfully.');
    }

    public function verify(User $user)
    {
        $user->loadCount('followers'); // Reload to ensure fresh count
        if ($user->followers_count >= 100) {
            $user->update(['is_verified' => true]);
            return redirect()->route('admin.users.index')->with('success', 'User verified successfully.');
        }
        return redirect()->route('admin.dashboard')->with('error', 'User has ' . $user->followers_count . ' followers, needs at least 1 for verification.');
    }

    public function suspend(User $user)
    {
        if ($user->reports_count >= 3) {
            $user->update(['is_suspended' => true]);
            return redirect()->route('admin.users.index')->with('success', 'User suspended successfully.');
        }
        return redirect()->route('admin.dashboard')->with('error', 'User has insufficient reports to be suspended.');
    }

    public function unsuspend(User $user)
    {
        $user->update(['is_suspended' => false]);
        return redirect()->route('admin.dashboard')->with('success', 'User unsuspended successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }
}
