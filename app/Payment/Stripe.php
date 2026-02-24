<?php

namespace App\Payment;

use App\Helpers\Gru;
use App\Helpers\PromoCode\PromoCodeHelper;
use App\Helpers\Talent\TalentHelper;
use App\Models\Order\OrderRequest;
use App\Models\User\User;
use GuzzleHttp\Client;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\Product;

class Stripe {
    public function setUpRequest( $request , OrderRequest $order_request ) {

        [ $status_domain , $agency , $web_url ] = TalentHelper::checkSubdomain( $request );

        $product_name = $order_request->talent->user->name . '-' . $order_request->id;
        $total        = PromoCodeHelper::getDiscountedPrice( $order_request ) * 100 ;

        \Stripe\Stripe::setApiKey( config( 'payment.stripe.secret_key' ) );

        $product          = Product::create( [
            'name' => $product_name ,
        ] );
        $price            = Price::create( [
            'product'     => $product->id ,
            'unit_amount' => $total ,
            'currency'    => config( 'payment.stripe.currency' )
        ] );
        $checkout_session = Session::create( [
            'line_items'           => [
                [
                    'price'    => $price->id ,
                    'quantity' => 1 ,
                    'description' => ' Order' .  ' - '  . $order_request->id,
                ]
            ] ,
            'payment_method_types' => [
                'card' ,
            ] ,
            'mode'                 => 'payment' ,

            'success_url'          => $web_url . '/en/payment/approved' ,
            'cancel_url'           => $web_url . '/en/payment/cancelled' ,

        ] );

        return $checkout_session;

    }

    public function validate_order( $order_reference , $order_request ) {

        \Stripe\Stripe::setApiKey( config( 'payment.stripe.secret_key' ) );

        $guzzle   = new Client();
        $response = $guzzle->request( 'GET' , config( 'payment.stripe.base_url' ) . '/v1/checkout/sessions/' .
                                            $order_reference , [
            'headers' => [
                'Authorization'=> 'Bearer ' . config( 'payment.stripe.secret_key' ),
                'content-type' => 'application/json',
                'accept'       =>'application/json',
            ],

        ] );

        $response_body = json_decode($response->getBody()->getContents());


        $payment_status = $response_body->payment_status;

        if( $payment_status == 'paid'){
            $order_response = $order_request->order_response;
            $order_response->status =Gru::SUCCESS_PAYMENT ;
            $order_response->save();
            return [Gru::SUCCESS_PAYMENT , $response_body ] ;
        }else{
            $order_response = $order_request->order_response;
            $order_response->status = Gru::CANCELLED_PAYMENT ;
            $order_response->save();
            return [Gru::CANCELLED_PAYMENT , $response_body ] ;

        }
    }

    public function setUpSubscriptionRequest($request , $package ){
        [ $status_domain , $agency , $web_url ] = TalentHelper::checkSubdomain( $request );

        \Stripe\Stripe::setApiKey( config( 'payment.stripe.secret_key' ) );

        $product          = Product::create( [
            'name' => $package->name ,
        ] );
        $price            = Price::create( [
            'product'     => $product->id ,
            'unit_amount' => $package->number * 100,
            'currency'    => config( 'payment.stripe.currency' )
        ] );
        $checkout_session = Session::create( [
            'line_items'           => [
                [
                    'price'    => $price->id ,
                    'quantity' => 1 ,
                    'description' => ' Subscription for 1 month ' .  ' - '  . $package->name,
                ]
            ] ,
            'payment_method_types' => [
                'card' ,
            ] ,
            'mode'                 => 'payment' ,

            'success_url'          => $web_url . '/subscription/approved' ,
            'cancel_url'           => $web_url . '/subscription/cancelled' ,

        ] );

        return $checkout_session;
    }


    public function validateSubscription( $order_reference , $enroll ) {

        \Stripe\Stripe::setApiKey( config( 'payment.stripe.secret_key' ) );

        $guzzle   = new Client();
        $response = $guzzle->request( 'GET' , config( 'payment.stripe.base_url' ) . '/v1/checkout/sessions/' .
            $order_reference , [
            'headers' => [
                'Authorization'=> 'Bearer ' . config( 'payment.stripe.secret_key' ),
                'content-type' => 'application/json',
                'accept'       =>'application/json',
            ],

        ] );

        $response_body = json_decode($response->getBody()->getContents());


        $payment_status = $response_body->payment_status;

        if( $payment_status == 'paid'){
            return [Gru::SUCCESS_PAYMENT , $response_body ] ;
        }else{
            return [Gru::CANCELLED_PAYMENT , $response_body ] ;

        }
    }


}
