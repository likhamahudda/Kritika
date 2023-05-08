<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;


    public function getCreatedAtAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }   


    protected $table = 'feedbacks';

}
