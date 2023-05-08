<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public function getUpdatedAtAttribute($value)
        {
            return date('j F Y', strtotime($value));
        }

    protected $table = 'subscription_plan';

    protected $fillable = [
        'plan_name',
        'monthly_price',
        'plan_yearly_but_montly_price',
        'subscription_type',
    ];
}
