<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentRoles extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'prepared_documents_roles';

    public function getUpdatedAtAttribute($value)
    {
        return date('j F Y', strtotime($value));
    }

}
