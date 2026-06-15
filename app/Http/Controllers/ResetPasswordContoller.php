<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Registration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;


class ResetPasswordContoller extends Controller
{
    public function show()
    {
        return view('reset_password');
    }

    public function updatepassword(Request $request)
    {
        try {
            // Validation 
            $request->validate([
                'email' => 'required|email',
                'password' => [
                    'required',
                    Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
                    'confirmed'
                ],
            ]);

            // Databse updation
            Registration::where('email', $request->email)
                ->update([
                    'password' => Hash::make($request->password),
                    'updated_at' => now()
                ]);

            return redirect('/login')
                ->with('success', 'Your password has been reset successfully!');

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