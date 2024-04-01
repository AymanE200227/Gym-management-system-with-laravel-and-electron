<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAdmin extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'password', 'super_admin_id'];

}
