<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipRegistration extends Model
{
    use HasFactory;
    protected $fillable = ['trainer_id', 'membership_plan_id', 'sub_admin_id'];

}
