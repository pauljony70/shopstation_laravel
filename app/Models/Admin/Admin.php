<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Admin extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;

    protected $table = 'admin_login';
    protected $guard = 'admin';

    protected $fillable = [
        'email',
        'password',
    ];
}