<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\Gru;
use App\Helpers\Notification\NotificationHelper;
use App\Helpers\Payment\PaymentHelper;
use App\Helpers\PromoCode\PromoCodeHelper;
use App\Helpers\Talent\TalentHelper;
use App\Http\Controllers\Controller;
use App\Models\Misc\Category;
use App\Models\Order\OrderRequest;
use App\Models\Order\OrderResponse;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Product;
use Stripe\Stripe;

class PaymentController extends Controller {
    public function index( $status , $order , Request $request ) {

        [ $status_domain , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;
        $order_request = OrderRequest::find( $order );


        if ( $order_request ) {

            $talent = $order_request->talent;

            if ( $order_request ) {
             if ($order_request->payment_method == Gru::PAYMENT_METHOD_CC ) {

                    [$payment , $response ] = PaymentHelper::handleStripeResponse( $request , $order_request );
                    $order_response = OrderResponse::where('order_request_id',$order_request->id)->first();
                    $order_response->response = json_encode($response);
                    $order_response->save();
//
                    if( $payment == Gru::SUCCESS_PAYMENT){
                        PromoCodeHelper::promoCount( $order_request );
                        $page = Page::find( Gru::SUCCESS_PAGE_ID );
//
////                        NotificationHelper::sendSuccessNotification( $order_request , $payment );
//
//
                        if ($order_request->order_type == Gru::VIDEO_ORDER_TYPE) {

                            PaymentHelper::informTalent ($order_request->order_response);
                        }
//
                        return [
                            'data' => [
                                '_agency'       => $_agency,
                                'url'           => $url,
                                'page'          => $page,
                                'order_type'    => $order_request->order_type,
                                'status'        => Gru::SUCCESS_PAYMENT,
                                'order_request' => $order_request,
                                'talent'        => $talent,
                                'status_text' => 'approved'
                            ]
                        ];
                    } else {
                        $page = Page::find (Gru::DECLINED_PAGE_ID);

                        return [
                            'data' => [
                                '_agency'       => $_agency,
                                'url'           => $url,
                                'page'          => $page,
                                'order_type'    => $order_request->order_type,
                                'status'        => Gru::CANCELLED_PAYMENT,
                                'order_request' => $order_request,
                                'talent'        => $talent,
                                'status_text'   => 'cancelled'
                            ]
                        ];
                    }


                }


                elseif ( $order_request->payment_method == Gru::PAYMENT_METHOD_PAYPAL ) {

                    $payment = PaymentHelper::handlePaypalResponse( $request , $order_request );

                    $private_domain = TalentHelper::checkTalentPackage( $talent );

                    $page = Page::find( Gru::SUCCESS_PAGE_ID );
//                    NotificationHelper::sendSuccessNotification( $order_request , $payment );

                    if ( $payment == 1 ) {

                        return [
                            'data' => [
                                '_agency'       => $_agency,
                                'url'           => $url,
                                'page'          => $page,
                                'order_type'    => $order_request->order_type,
                                'status'        => Gru::SUCCESS_PAYMENT,
                                'order_request' => $order_request,
                                'talent'        => $talent,
                                'status_text'  => 'approved'
                            ]
                        ];


                    }
                    else {
                        return [
                            'data' => [
                                '_agency'       => $_agency,
                                'url'           => $url,
                                'page'          => $page,
                                'order_type'    => $order_request->order_type,
                                'status'        => Gru::CANCELLED_PAYMENT,
                                'order_request' => $order_request,
                                'talent'        => $talent,

                            ]
                        ];
                    }
                }
                elseif ( $order_request->payment_method == Gru::PAYMENT_METHOD_WU ) {
                    $page = Page::find( Gru::PENDING_PAGE_ID );
                    //                    NotificationHelper::sendSuccessNotification( $order_request );
                    return [
                        'data' => [
                            '_agency'       => $_agency,
                            'url'           => $url,
                            'page'          => $page,
                            'order_type'    => $order_request->order_type,
                            'status'        => Gru::PENDING_PAYMENT,
                            'order_request' => $order_request,
                            'talent'        => $talent,

                        ]
                    ];



                }elseif( $order_request->payment_method == Gru::PAYMENT_METHOD_WHATS_APP){

//                 NotificationHelper::sendSuccessNotification( $order_request )

                }
            }

        }
    }


}
