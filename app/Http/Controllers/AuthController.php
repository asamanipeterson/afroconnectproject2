<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Countries\Package\Countries;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        // Hash the password before creation (assuming this is done via mutator or explicitly here)
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect('login')->with('success', 'Registration successful. Please log in.');
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function loginLogic(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // 💡 NEW: Capture the 'remember' state and store it in the session
        $remember = $request->filled('remember');

        if ($user && Hash::check($request->password, $user->password)) {
            $user->generateOtp();
            session([
                'otp_user_id' => $user->id,
                'remember_login' => $remember // Store the remember me state
            ]);
            return redirect()->route('otp.verify.page')->with('success', 'A verification code has been sent to your email for security verification.');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function showOtpVerificationPage()
    {
        // Check for both session keys now
        if (!session('otp_user_id') || !session()->has('remember_login')) {
            return redirect()->route('login')->withErrors(['error' => 'Your session has expired or you need to log in first.']);
        }
        return view('auth.otp-verify');
    }

    public function verifyOtpLogic(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = session('otp_user_id');
        // 💡 NEW: Retrieve the 'remember' state from the session
        $remember = session('remember_login', false);

        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Your session has expired. Please log in again.']);
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Invalid user. Please log in again.']);
        }

        $otpRecord = DB::table('one_time_pass_codes')
            ->where('user_id', $user->id)
            ->where('code', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otpRecord) {
            // 💡 NEW: Pass the $remember boolean as the second argument to Auth::login()
            Auth::login($user, $remember);

            DB::table('one_time_pass_codes')->where('id', $otpRecord->id)->delete();

            // Clean up both session keys
            $request->session()->forget(['otp_user_id', 'remember_login']);
            $request->session()->regenerate();

            if ($user->user_type === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('welcome');
            }
        }

        return back()->withErrors(['otp' => 'The provided OTP is invalid or has expired.']);
    }

    public function resendOtpLogic(Request $request)
    {
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Your session has expired. Please log in again.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Invalid user. Please log in again.']);
        }

        $user->generateOtp();

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        // Since you are using a redirect with a named route, make sure the redirect returns the response.
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

        $users = User::where('user_type', '!=', 'admin')
            ->where(function ($q) use ($query) {
                $q->where('username', 'LIKE', "%{$query}%")
                    ->orWhere('location', 'LIKE', "%{$query}%");
            })
            ->select('id', 'username', 'location', 'profile_picture')
            ->take(10)
            ->get();

        return response()->json(['users' => $users]);
    }

    public function show(User $user)
    {
        return view('profile.index', compact(
            'user',
        ));
    }
}
