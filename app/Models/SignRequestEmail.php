<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SignRequestEmail extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'emails';

    public function getUpdatedAtAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

}
