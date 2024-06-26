<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }


    public function organizations()
    {
        return $this->hasMany(Organization::class ,'user_id');
    }

    public function subscriptions(){
        return $this->belongsToMany(Subscription::class,'user_subscriptions','user_id','subscription_id')->withPivot('payment_id');
    }

    public function following()
    {
        return $this->belongsToMany(Organization::class, 'user_profile_followers', 'user_id', 'profile_id')->withPivot('status');
    }

    public function notifications()
    {
        return $this->hasMany(AdminActivity::class, 'sender_id', 'receiver_id');
    }

    public function sentNotifications()
    {
        return $this->hasMany(AdminActivity::class, 'sender_id', 'id');
    }

    public function receivedNotifications()
    {
        return $this->hasMany(AdminActivity::class, 'receiver_id', 'id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified',
        'password',
        'role',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
