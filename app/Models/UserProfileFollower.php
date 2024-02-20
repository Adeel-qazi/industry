<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfileFollower extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_id',
    ];
    public $table = 'user_profile_followers';

    
}
