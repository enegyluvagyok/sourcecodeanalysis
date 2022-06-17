<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secrets extends Model
{
    use HasFactory;

    protected $fillable = [
        'secret',
        'hash',
        'ttl',
        'viewsnum'
    ];
}
