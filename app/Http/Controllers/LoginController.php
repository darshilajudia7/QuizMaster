<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function view()
    {
        return view('login_page');
    }

    public function login(Request $request)
    {
        // Validataion 
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = Registration::where('email', $request->email)->first();

        // Match email , password
        if ($user && Hash::check($request->password, $user->password)) {

            Auth::login($user);

            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_role', $user->role);

            // Role Wise Redirect

            if ($user->role === 'teacher') {
                return redirect('/teacher')
                    ->with('success', 'Welcome Teacher');
            }

            if ($user->role === 'student') {
                return redirect('/student')
                    ->with('success', 'Welcome Student');
            }
        }

        return back()->with('error', 'Invalid Email or Password');
    }
}