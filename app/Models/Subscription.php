<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public function users(){
        return $this->belongsToMany(User::class,'user_subscriptions','user_id','subscription_id');
    }
 
    protected $fillable = [
        'user_id',
        'plan_name',
        'price',
        'start_date',
        'close_date',
        'active',
    ];
}
