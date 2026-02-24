<?php

return [

    'secret_key' => env( 'TELR_PAYMENT_SECRET_KEY', '' ),

    'ivp_store' => 22904,

    'ivp_currency' => 'USD',

    'ivp_test' => env('TELR_PAYMENT_TEST_MODE', 0),

    'endpoint' => 'https://secure.telr.com/gateway/order.json',

    'return_auth' => 'auto:' . env( 'APP_URL' ) . '/payment/approved',
    'return_decl' => 'auto:' . env( 'APP_URL' ) . '/payment/declined',
    'return_can'  => 'auto:' . env( 'APP_URL' ) . '/payment/cancelled',
    'return_donation_auth' => 'auto:' . env( 'APP_URL' ) . '/donate/approved',
    'return_donation_dec1' => 'auto:' . env( 'APP_URL' ) . '/donate/declined',
    'return_donation_can'  => 'auto:' . env( 'APP_URL' ) . '/donate/cancelled',
    'return__auth' => 'auto:' . env( 'APP_URL' ) . '/class/approved',
    'return__decl' => 'auto:' . env( 'APP_URL' ) . '/class/declined',
    'return__can'  => 'auto:' . env( 'APP_URL' ) . '/class/cancelled',
];
