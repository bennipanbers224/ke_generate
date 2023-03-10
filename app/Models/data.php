<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data extends Model
{
    use HasFactory;

    protected $table = 'data';

    protected $fillable = [
        'name',
        'major',
        'title',
        'predicate',
        'graduation_date',
        'start_study',
        'nim',
        'certificate_number',
        'image',
        'private_key',
        'public_key'
    ];
}
