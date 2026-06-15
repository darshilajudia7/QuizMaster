<?php
namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;


use Exception;
class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if Google ID exists
            $user = Registration::where('google_id', $googleUser->id)->first();

            if ($user) {
                // Update token
                $user->update(['google_token' => $googleUser->token]);
            } else {
                // Check if email exists
                $user = Registration::where('email', $googleUser->email)->first();

                if ($user) {
                    // Update query if user exists but hasn't linked Google yet
                    $user->update([
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                    ]);
                } else {
                    // Insert query for a brand-new user
                    // NOTE: Defaulting role to 'student' here, adjust if needed
                    $user = Registration::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                        'role' => 'student',
                    ]);
                }
            }

            // 1. Log the user into Laravel's native Auth guard
            Auth::login($user);

            // 2. CRITICAL FIX: Set the session data your middleware looks for
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_role', $user->role);

            // 3. FIX: Redirect dynamically based on the role, matching LoginController logic
            if ($user->role === 'teacher') {
                return redirect('/teacher')->with('success', 'Welcome Teacher');
            }

            if ($user->role === 'student') {
                return redirect('/student')->with('success', 'Welcome Student');
            }

            // Fallback redirect just in case
            return redirect('/');

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}