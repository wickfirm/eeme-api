<?php


namespace App\Helpers\Notification;

use App\Helpers\Gru;
use App\Helpers\Order\OrderHelper;
use App\Models\Order\OrderRequest;
use App\Models\Talent\TalentOrder;
use App\Notifications\BusinessTalentOrderNotification;
use App\Notifications\CustomerOrderNotification;
use App\Notifications\FailedTelrTransactionNotification;
use App\Notifications\NewOrderNotification;
use App\Notifications\SuccessTelrTransactionNotification;
use App\Notifications\TalentOrderNotification;
use App\Notifications\WesternUnionBusinessOrderNotification;
use App\Notifications\WesternUnionOrderNotification;
use Illuminate\Support\Facades\Notification;

class NotificationHelper {
    public static function sendSuccessNotification( OrderRequest $order_request , $payment = NULL ) {

        $talent_order = TalentOrder::where( 'order_response_id' , $order_request->order_response->id )->first();



//        if ( isset ( $talent_order ) ) {
//
//            if ( $order_request->order_type == Gru::VIDEO_ORDER_TYPE ) {
//
//                if ( $order_request->client_notified == 0 ) {
//
//                    if ( $order_request->payment_method == Gru:: PAYMENT_METHOD_CC && $order_request->order_response->status == 3 ) {
////                        Notification::route( 'mail' , $order_request->email )->notify( new SuccessTelrTransactionNotification( $payment->getResponseData() ) );
//                        Notification::route( 'mail' , $order_request->email )->notify( new CustomerOrderNotification( $order_request ) );
//
//                    }
//                    else if ( $order_request->payment_method == Gru:: PAYMENT_METHOD_PAYPAL && $order_request->order_response->status == 3 ) {
//                        Notification::route( 'mail' , $order_request->email )->notify( new CustomerOrderNotification( $order_request ) );
//                    }
//                    else if ( $order_request->payment_method == Gru:: PAYMENT_METHOD_WU || $order_request->payment_method == Gru:: PAYMENT_METHOD_WHATS_APP ) {
//
//                        Notification::route( 'mail' , $order_request->email )->notify( new WesternUnionOrderNotification( $order_request ) );
//                    }
//                    $order_request->client_notified = 1;
//                    $order_request->save();
//                }
//                if ( $order_request->talent_notified == 0 && $order_request->order_response->status == 3 ) {
//                    Notification::route( 'mail' , $order_request->talent->user->email )->notify( new TalentOrderNotification( $order_request , $talent_order ) );
//
//                    $order_request->talent_notified = 1;
//                    $order_request->save();
//                }
//                if ( $order_request->admin_notified == 0 && $order_request->order_response->status == 3 ) {
////                     Notification::route( 'mail' , 'hello@omneeyat.com' )->notify( new NewOrderNotification( $order_request , $talent_order ) );
//                    $order_request->admin_notified = 1;
//                    $order_request->save();
//                }
//            }
//        }
//        else {
//            if ( $order_request->order_type == Gru::BUSINESS_ORDER_TYPE ) {
//
//                if ( $order_request->client_notified == 0 ) {
//                    if ( $order_request->payment_method == Gru:: PAYMENT_METHOD_WU || $order_request->payment_method == Gru:: PAYMENT_METHOD_WHATS_APP ) {
//                        Notification::route( 'mail' , $order_request->email )->notify( new WesternUnionBusinessOrderNotification( $order_request ) );
//                    }
//                    else if ( ( $order_request->payment_method == Gru:: PAYMENT_METHOD_CC || $order_request->payment_method == Gru:: PAYMENT_METHOD_PAYPAL ) && $order_request->order_response->status == 3 ) {
//                        Notification::route( 'mail' , $order_request->email )->notify( new CustomerOrderNotification( $order_request ) );
//                        if($order_request->talent_id == 510 && $order_request->talent_notified == 0 ){
//
//                            $addons = OrderHelper::addonsAppliedToOrder ($order_request);
//                            $addon_name = '';
//                            if($addons->count() > 1 ){
//                                foreach ($addons as $addon){
//                                    $addon_name  = $addon_name . $addon->name .', ' ;
//
//                                }
//                            }else{
//                                $addon_name = $addons->first()->name;
//                            }
//
//                            Notification::route ('mail',$order_request->talent->user->email)->notify(new BusinessTalentOrderNotification($order_request ,$addon_name));
//                        }
//                        $order_request->talent_notified = 1;
//                    }
//                    $order_request->client_notified = 1;
//
//                    $order_request->save();
//                }
//            }
//        }
    }

    public static function sendFailedOrderNotification( OrderRequest $order_request , $payment ) {
//        if ( $order_request->client_notified == 0 ) {
//            if ( $order_request->order_type == Gru::VIDEO_ORDER_TYPE ) {
//                Notification::route( 'mail' , $order_request->email )->notify( new FailedTelrTransactionNotification( $payment->getResponseData() ) );
//            }
//            else if ( $order_request->order_type == Gru::BUSINESS_ORDER_TYPE ) {
//                Notification::route( 'mail' , $order_request->email )->notify( new FailedTelrTransactionNotification( $payment->getResponseData() ) );
//            }
//            $order_request->client_notified = 1;
//            $order_request->save();
//        }
    }

}
