<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signature extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'user_signatures';

    public function getUpdatedAtAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

}
