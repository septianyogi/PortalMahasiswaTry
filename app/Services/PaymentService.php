<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

/**
 * Class PaymentService
 * @package App\Services
 */
class PaymentService
{
    public function createTransaction(array $data) {
        $user = Auth::user();

        $student = Student::where('user_id', $user->id)->first();

        if(!$student){
            throw new \Exception('Student Nto Found', 404);
        }

        $orderId = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid()) . '-' . $student->id;

        $newTransaction = Payment::create([
            'order_id' => $orderId,
            'student_id' => $student->id,
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'],
            'status' => 'pending',
            
        ]);

        return $newTransaction;

    }
}
