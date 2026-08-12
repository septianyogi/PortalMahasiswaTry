<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'student_id',
        'type',
        'description',
        'amount',
        'status',
        'snap_token',
        'payment_type',
        'midtrans_response',
        'paid_at',
    ];

   

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
