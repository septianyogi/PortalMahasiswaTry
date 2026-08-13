<?php

namespace App\Http\Controllers;

use App\Services\PaymentTransactionService;
use Illuminate\Http\Request;

class PaymentTransactionController extends Controller
{

    protected PaymentTransactionService $paymentService;

    public function __construct(PaymentTransactionService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    
    public function createTransaction(int $orderId)
    {
        try {
            $result = $this->paymentService->createTransaction($orderId);
            return $this->responseOk($result, 'Transaction created successfully');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function checkStatus($orderId)
    {
        try {
            $transaction = $this->paymentService->checkStatus($orderId);
            return $this->responseOk($transaction, 'Status retrieved');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function history()
    {
        try {
            $transactions = $this->paymentService->history();
            return $this->responseOk($transactions, 'History retrieved');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function webhook(Request $request)
    {
        try {

            $this->paymentService->handleNotification(
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully'
            ], 200);

        } catch (\Throwable $e) {

            dd($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);

        }
    }
}