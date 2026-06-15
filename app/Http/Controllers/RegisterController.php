<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Registration;
use App\Models\otp_verification;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Carbon\Carbon;

use App\Mail\SendOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function view()
    {
        return view('registration_page');
    }

    public function reg(Request $request)
    {
        try {
            // Validation 
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:registration,email',
                'password' => [
                    'required',
                    Password::min(8)->letters()->mixedCase()->numbers()->symbols()
                ]
            ]);

            // Random otp generate
            $otp = rand(100000, 999999);

            Session::put('register_name', $request->name);
            Session::put('register_email', $request->email);
            Session::put('register_password', Hash::make($request->password));
            Session::put('register_otp', $otp);

            $mailSent = $this->SendOtp($request->email, $otp, $request->name);

            if (!$mailSent) {
                throw new \Exception("Mail delivery failed.");
            }

            return redirect('/otp')->with('success', 'An OTP has been sent to your email.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validation Failed. Please check the form.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function SendOtp($email, $otp, $name)
    {
        // If otp send then then store its otp data 
        try {
            if (Mail::to($email)->send(new SendOtp($name, $otp))) {
                otp_verification::create([
                    'email' => $email,
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(1)
                ]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function VerifyOTP(Request $request)
    {
        try {
            // Validation 
            $request->validate([
                'otp' => 'required|array|min:6|max:6',
            ]);

            // Convert array into string
            $enteredOtp = implode('', $request->otp);
            $email = Session::get('register_email');

            // Session exists
            if (!$email) {
                return redirect('/register')->with('error', 'Session expired. Please register again.');
            }

            // Find OTP 
            $otpRecord = otp_verification::where('email', $email)
                ->where('otp', $enteredOtp)
                ->first();

            // Check OTP 
            if (!$otpRecord) {
                return back()->withErrors(['otp' => 'Invalid OTP']);
            }

            $expiresAt = \Carbon\Carbon::parse($otpRecord->expires_at);

            // Check time 
            if (now()->gt($expiresAt)) {
                $otpRecord->delete();
                return back()->withErrors(['otp' => 'OTP expired']);
            }

            // Insert query
            Registration::create([
                'name' => Session::get('register_name'),
                'email' => $email,
                'password' => Session::get('register_password'),
                'role' => 'student',
            ]);

            // OTP delete 
            $otpRecord->delete();

            // Session destroy
            Session::forget(['register_name', 'register_email', 'register_password', 'register_otp']);
            return redirect('/login')->with('success', 'Account created successfully');

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return back()->with('error', 'Password not match.');
        }
    }


    public function resendOTP()
    {
        try {
            $email = Session::get('register_email');
            $name = Session::get('register_name');

            if (!$email) {
                return redirect('/register')->with('error', 'Session expired. Please register again.');
            }

            otp_verification::where('email', $email)->delete();

            $newOtp = rand(100000, 999999);

            Session::put('register_otp', $newOtp);

            $mailSent = $this->SendOtp($email, $newOtp, $name ?? 'User');

            if (!$mailSent) {
                throw new \Exception("Mail delivery failed during OTP resend.");
            }

            return back()->with('success', 'A new verification code has been sent to your email!');

        } catch (\Exception $e) {
            \Log::error('Resend OTP Error: ' . $e->getMessage());
            return back()->with('error', 'Could not resend code. Please try again.');
        }
    }
}
