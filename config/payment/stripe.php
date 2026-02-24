<?php

return [

    'secret_key' => env( 'STRIPE_SECRET_KEY', '' ),


    'base_url' => 'https://api.stripe.com',

    'currency' => 'usd',

    'return_auth' => 'auto:' . env( 'WEBSITE_PORTAL_URL' ) . '/payment/approved',
    'return_decl' => 'auto:' . env( 'WEBSITE_PORTAL_URL' ) . '/payment/declined',
    'return_can'  => 'auto:' . env( 'WEBSITE_PORTAL_URL' ) . '/payment/cancelled',
];
