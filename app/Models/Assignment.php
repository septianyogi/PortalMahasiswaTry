<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
    'class_id',
    'title',
    'description',
    'due_date',
    'score',
];

}
