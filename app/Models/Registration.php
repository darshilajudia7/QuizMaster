<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Registration extends Authenticatable
{
    protected $table = 'registration';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'google_token',
    ];

    protected $hidden = [
        'password',
    ];
}