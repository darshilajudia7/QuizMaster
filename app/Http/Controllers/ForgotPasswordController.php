<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Mail\Sendlink;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function view()
    {
        return view('forgot_password');
    }

    public function sendlink(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'email' => 'required|email|exists:registration,email',
            ]);

            // Fetch name 
            $email = $request->email;
            $user = DB::table('registration')->where('email', $email)->first();
            $name = $user->name ?? 'User';

            // Session
            Session::put('email', $email);
            Session::put('username', $name);

            // Send mail 
            $verificationLink = url('/reset_password?email=' . urlencode($email));
            Mail::to($email)->send(new Sendlink($name, $verificationLink));

            return redirect('/login')
                ->with('success', 'Password reset link sent successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validation Failed. Please check the form.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again later.');
        }
    }
}