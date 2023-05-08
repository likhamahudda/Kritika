<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFeatures extends Model
{
    use HasFactory;

    protected $table = 'subscription_plan_feature';

    protected $fillable = [
        'subscription_plan_id',
        'count',
        'type',
        'description',
    ];
}
