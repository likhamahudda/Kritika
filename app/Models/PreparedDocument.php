<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreparedDocument extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'prepared_documents';

    public function getUpdatedAtAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

}
