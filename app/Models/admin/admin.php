<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    protected $fillable = [
 'id_admin',
 'nama',
 'username',
 'email',
 'password',
 'role',
 'status',
 ];

}
