<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class otp_verification extends Model
{
    protected $filename = 'otp_verifications';

    protected $fillable = [
        'email',
        'otp',
        'expires_at',
    ];



}
