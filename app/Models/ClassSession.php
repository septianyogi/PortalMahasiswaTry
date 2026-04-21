<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $fillable = [
    'class_id',
    'week',
    'date',
    'qr_token',
    'expired_at',
    'is_active',
];
}
