<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Countries\Package\Countries;

class AuthController extends Controller
{
    public function registerPage()
    {
        $countries = Countries::all()
            ->pluck('name.common', 'name.common')
            ->toArray();



        return view('auth.register', compact('countries'));
    }

    public function registerLogic(AuthRequest $request)
    {
        $data = $request->validated();
        User::create($data);
        return redirect('login')->with('success', 'Registration successful. Please log in.');
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function loginLogic(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect(route('welcome'));
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect(route('login'))->with('success', 'You have been logged out successfully.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'profile_picture' => ['nullable', 'image', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:255'],
            'cover_picture' => ['nullable', 'image', 'max:2048'],
            'website' => ['nullable', 'url', 'max:255'],
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        if ($request->hasFile('cover_picture')) {
            if ($user->cover_picture) {
                Storage::disk('public')->delete($user->cover_picture);
            }
            $path = $request->file('cover_picture')->store('cover_pictures', 'public');
            $validated['cover_picture'] = $path;
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function toggleFollow(User $user)
    {
        if (Auth::id() === $user->id) {
            return response()->json([
                'message' => 'You cannot follow yourself'
            ], 403);
        }

        $isFollowing = Auth::user()->isFollowing($user);
        Auth::user()->toggleFollow($user);

        return response()->json([
            'following' => !$isFollowing,
            'message' => $isFollowing ? 'Unfollowed successfully' : 'Followed successfully'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->query('q');

        if (!$query) {
            return response()->json(['users' => []]);
        }

        $users = User::where('username', 'LIKE', "%{$query}%")
            ->orWhere('location', 'LIKE', "%{$query}%")
            ->select('id', 'username', 'location', 'profile_picture')
            ->take(10)
            ->get();

        return response()->json(['users' => $users]);
    }

    public function show(User $user)
    {
        return view('profile.index', compact(
            'user',
            'groupedPosts',
            'postsCount',
            'followersCount',
            'followingCount',
            'isFollowing'
        ));
    }
}
