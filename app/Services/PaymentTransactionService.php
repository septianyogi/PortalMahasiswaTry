<?php

namespace App\Services;

use App\Models\PaymentTransaction;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

/**
 * Class PaymentTransactionService
 * @package App\Services
 */
class PaymentTransactionService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createTransaction(array $data) {
        $user = Auth::user();

        $student = Student::where('user_id', $user->id)->first();

        if(!$student){
            throw new \Exception('Student Nto Found', 404);
        }

        $orderId = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid()) . '-' . $student->id;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $data['amount'],
            ],
            'customer_details' => [
                'first_name' => $student->name,
                'email' => $student->email,
                'phone' => $data['phone'] ?? '-',
            ],
            'item_details' => [
                [
                    'id' => $data['type'],
                    'price' => $data['amount'],
                    'quantity' => 1,
                    'name' => $data['description'] ?? $data['type'],
                ]
            ],
        ];

        try {
            $transaction = Snap::createTransaction($params);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: '.$e->getMessage(), 500);
        }

        $newTransaction = PaymentTransaction::create([
            'order_id' => $orderId,
            'student_id' => $student->id,
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'],
            'status' => 'pending',
            'snap_token' => $transaction->token,
        ]);

        return [
            'transaction' => $newTransaction,
            'snap_token' => $transaction->token,
            'redirect_url' => $transaction->redirect_url
        ];

    }

    public function handleNotification(array $payload): bool
    {
        if (!isset($payload['order_id'])) {
            throw new \Exception('order_id is missing');
        }

        if (!isset($payload['transaction_status'])) {
            throw new \Exception('transaction_status is missing');
        }

        $transaction = PaymentTransaction::where(
            'order_id',
            $payload['order_id']
        )->first();

        if (!$transaction) {
            throw new \Exception('Transaction not found');
        }

        switch ($payload['transaction_status']) {
            case 'capture':
            case 'settlement':
                $transaction->status = 'success';
                $transaction->paid_at = now();
                break;
            case 'pending':
                $transaction->status = 'pending';
                break;
            case 'cancel':
            case 'deny':
                $transaction->status = 'canceled';
                break;
            case 'expire':
                $transaction->status = 'expired';
                break;
            default:
                $transaction->status = $payload['transaction_status'];
                break;
        }

        $transaction->payment_type = $payload['payment_type'] ?? null;

        $transaction->midtrans_response = $payload;

        $transaction->save();

        return true;
    }

    public function checkStatus($orderId)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();


        if (!$student) {
            throw new \Exception('Student not found', 404);
        }

        $transaction = PaymentTransaction::where('order_id', $orderId)
            ->where('student_id', $student->id)
            ->first();

        if (!$transaction) {
            throw new \Exception('Transaction not found', 404);
        }

        return $transaction;
    }

    public function history()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            throw new \Exception('Student not found', 404);
        }

        return PaymentTransaction::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }




}
