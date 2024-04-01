<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountOffer extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'discount_percentage', 'start_date', 'end_date'];
}
