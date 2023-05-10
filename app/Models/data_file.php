<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_file extends Model
{
    use HasFactory;

    protected $table = 'data_file';

    protected $fillable = [
        'name',
        'message_digest',
        'signature'
    ];
}
