<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Server Key
    |--------------------------------------------------------------------------
    |
    | Server key digunakan untuk backend API calls ke Midtrans.
    | Dapatkan di dashboard Midtrans: Settings → Access Keys
    |
    */
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Client Key
    |--------------------------------------------------------------------------
    |
    | Client key digunakan untuk frontend (JavaScript SDK) atau Snap.
    |
    */
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Merchant ID
    |--------------------------------------------------------------------------
    |
    | ID merchant yang terdaftar di Midtrans.
    |
    */
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Environment Mode
    |--------------------------------------------------------------------------
    |
    | true  = Production (real transaction)
    | false = Sandbox (testing)
    |
    */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Sanitization
    |--------------------------------------------------------------------------
    |
    | true  = Midtrans akan membersihkan input data
    | false = tidak
    |
    */
    'is_sanitized' => env('MIDTRANS_SANITIZATION', true),

    /*
    |--------------------------------------------------------------------------
    | 3DS (3D Secure)
    |--------------------------------------------------------------------------
    |
    | true  = Aktifkan verifikasi 3DS
    | false = Nonaktifkan (tidak disarankan)
    |
    */
    'is_3ds' => env('MIDTRANS_3DS', true),
];