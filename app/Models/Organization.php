<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_nation',
        'treaty',
        'tribal_council',
        'website',
        'mailing_address',
        'town',
        'province',
        'postal_code',
        'phone',
        'physical_location',
        'language',
        'on_reserve_membership',
        'off_reserve_membership',
        'chief',
        'councillor',
        'term',
        'election_date',
        'economic_development_corp',
        'ec_dev_corp_website',
        'existing_company',
        'equity_investment',
        'partner',
        'news',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_profile_followers', 'profile_id', 'user_id');

    }
}
