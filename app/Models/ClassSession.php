<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $fillable = [
    'class_id',
    'week',
    'code_duration',
    'qr_token',
    'expired_at',
    'is_active',
];
}
