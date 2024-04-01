<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipRenewal extends Model
{
    use HasFactory;
    protected $fillable = ['trainer_id', 'membership_plan_id'];
}
