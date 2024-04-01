<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'membership_period', 'price', 'gym_sessions', 'swim_period', 'swim_sessions'];

}
