<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService){
        $this->paymentService = $paymentService;
    }

    public function createPayment(Request $request) 
    {
        try {
            $request->validate([
                'amount' => 'required|integer',
                'type' => 'required|string',
                'description' => 'nullable|string',
            ]);

            $result = $this->paymentService->createTransaction($request->all());
            return $this->responseOk($result, 'Transaction created successfully');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function getPayment(Request $request)
    {
        try {
            $result = $this->paymentService->getPayment();
            return $this->responseOk($result, 'Success');
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage, $th->getCode ?: 500);
        }
    }

}
