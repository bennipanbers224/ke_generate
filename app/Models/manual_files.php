<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class manual_files extends Model
{
    use HasFactory;

    protected $table = 'manual_files';

    protected $fillable = [
        'file_name',
        'status',
        'user_id'
    ];
}
