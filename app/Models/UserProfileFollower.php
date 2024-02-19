<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfileFollower extends Model
{
    use HasFactory;

    protected $tables = 'user_profile_followers'; 
}
