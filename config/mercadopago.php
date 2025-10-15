<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mercado Pago Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for Mercado Pago
    | payment gateway integration. Make sure to set your credentials
    | in the .env file.
    |
    */

    'access_token' => env('MERCADO_PAGO_ACCESS_TOKEN'),
    'public_key' => env('MERCADO_PAGO_PUBLIC_KEY'),
    'client_id' => env('MERCADO_PAGO_CLIENT_ID'),
    'client_secret' => env('MERCADO_PAGO_CLIENT_SECRET'),
    'sandbox' => env('MERCADO_PAGO_SANDBOX', true),

    /*
    |--------------------------------------------------------------------------
    | PIX Configuration
    |--------------------------------------------------------------------------
    |
    | PIX payment method specific settings for Brazil
    |
    */

    'pix' => [
        'enabled' => true,
        'expiration_minutes' => 30, // PIX QR code expiration time
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configure webhook URLs for payment notifications
    |
    */

    'webhook' => [
        'url' => env('APP_URL') . '/webhook/mercadopago',
        'events' => [
            'payment',
            'merchant_order'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    */

    'currency' => 'BRL',
    'country' => 'BR',
    'locale' => 'pt-BR',

];