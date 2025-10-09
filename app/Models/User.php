<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang digunakan model ini.
     */
    protected $table = 'user'; 

    protected $primaryKey = 'id_user'; 

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    
    protected $casts = [
        'password' => 'hashed',
    ];
}