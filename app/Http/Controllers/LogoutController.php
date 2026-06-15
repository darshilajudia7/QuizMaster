<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Session::forget(['user_id', 'user_name', 'user_role']);

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

