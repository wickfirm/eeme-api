<?php


namespace App\Helpers\Payment;


use App\Helpers\Gru;
use App\Helpers\Order\OrderHelper;
use App\Helpers\PromoCode\PromoCodeHelper;
use App\Helpers\Talent\TalentHelper;
use App\Models\App\Order;
use App\Models\Order\OrderRequest;
use App\Models\Order\OrderResponse;
use App\Models\Page;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentDomains;
use App\Models\Talent\TalentOrder;
use App\Notifications\CustomerOrderNotification;
use App\Notifications\NewOrderNotification;
use App\Notifications\TalentOrderNotification;
use App\Notifications\WesternUnionOrderNotification;
use App\Payment\Paypal;
use App\Payment\Stripe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Payment\Telr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaymentHelper {
    public static function verifyReferer( Request $request ) {
        if ( $request->hasHeader( 'referer' ) ) {

            $referer = $request->header( 'referer' );
            $referer = parse_url( $referer );

            if ( $referer['host'] == 'secure.telr.com' ) {
                if ( $referer['path'] == '/gateway/process.html' ) {
                    return explode( '=', $referer['query'] );
                }
            }
        }

        return null;
    }

    public static function informTalent( $order_response ) {
        $order = self::createOrderObject( $order_response );

        $talent_order = TalentOrder::where( 'talent_id', $order->request->talent->id )
            ->where( 'order_response_id', $order_response->id )
            ->first();

        if ( !$talent_order ) {
            $talent_order                    = new TalentOrder();
            $talent_order->talent_id         = $order->talent->id;
            $talent_order->order_response_id = $order->response->id;
            $talent_order->save();
        }
        return $talent_order;
    }

    private static function createOrderObject( $order_response ) {
        $order           = new Order();
        $order->talent   = $order_response->order_request->talent;
        $order->response = $order_response;
        $order->request  = $order_response->order_request;

        return $order;
    }

    public static function paymentProcessing($request, $order_request , $payment_method ) {
        if ( $payment_method == Gru::PAYMENT_METHOD_CC ) {

            $order_response                   = new OrderResponse();
            $order_response->order_request_id = $order_request->id;
            $order_response->status           = 0;
            $order_response->response         = json_encode( [ '' ] );
            $order_response->save();

            $payment                       = new Stripe();
            $order_request->payment_method = Gru::PAYMENT_METHOD_CC;
            $order_request->save();

            $stripe = $payment->setUpRequest( $request , $order_request );

            $order_request->order_ref = $stripe ->id;
            $order_request->save();

            Session::forget( 'omneeyat_or' );

            Session::put( 'omneeyat_or' , $order_request->id );



            if ( $order_request->order_type == Gru::VIDEO_ORDER_TYPE ) {
                $talent_order                    = new TalentOrder();
                $talent_order->talent_id         = $order_request->talent->id;
                $talent_order->order_response_id = $order_response->id;
                $date                            = date( "Y-m-d H:i" );
                $talent_order->first_reminder    = date( 'Y-m-d H:i' , strtotime( $date . '+4 day' ) );
                $talent_order->due_date          = date( 'Y-m-d H:i' , strtotime( $date . '+' . $order_request->talent->response_time . 'day' ) );
                $talent_order->save();
            }

            return [
                'data' => [
                    'url'              => $stripe->url,
                    'order_request_id' => $order_request->id
                ]
            ];


        }


        elseif ( $payment_method == Gru::PAYMENT_METHOD_PAYPAL ) {
            return self::paymentViaPaypal( $order_request , $request );
        }
        elseif ($payment_method == Gru::PAYMENT_METHOD_WHATS_APP ) {
            return self::paymentViaWhatsApp( $order_request );
        }
        elseif ( $payment_method == Gru::PAYMENT_METHOD_WU ) {
            return self::paymentViaWU( $order_request , $request);
        }
        elseif($payment_method == Gru::PAYMENT_METHOD_FREE){
          return self::freeOrder($order_request);
        }
    }

    public static function paymentViaPaypal( $order_request , $request) {

        $payment  = new Paypal();
        $data     = $payment->setUpRequest( $order_request , $request);

        $provider = new ExpressCheckout;

        $response = $provider->setExpressCheckout( $data );



        return [
            'data' => [
                'url'              => $response['paypal_link'],
                'order_request_id' => $order_request->id
            ]
        ];
    }


    public static function paymentViaWU( $order_request , Request $request) {

        [ $status_domain , $agency , $web_url ] = TalentHelper::checkSubdomain( $request );

        $order_response                   = new OrderResponse();
        $order_response->order_request_id = $order_request->id;
        $order_response->status           = 4;
        $order_response->response         = json_encode( [ '' ] );
        $order_response->save();

        if ( $order_request->order_type == Gru::VIDEO_ORDER_TYPE ) {
            $talent_order                    = new TalentOrder();
            $talent_order->talent_id     = $order_request->talent->id;
            $talent_order->order_response_id = $order_response->id;
            $talent_order->save();
        }
        Session::forget('omneeyat_or');
        Session::put( 'omneeyat_or' , $order_request->id );

        return
            [
                'data' => [
                    'url'              =>  $web_url . '/en/payment/pending',
                    'order_request_id' => $order_request->id
                ]
        ];

    }

    public static function paymentViaWhatsApp( $order_request ) {

        $order_response                   = new OrderResponse();
        $order_response->order_request_id = $order_request->id;
        $order_response->status           = 4;
        $order_response->response         = json_encode( [ '' ] );
        $order_response->save();

        if( $order_request->order_type == Gru::VIDEO_ORDER_TYPE ){
            $talent_order                    = new TalentOrder();
            $talent_order->talent_id         = $order_request->talent->id;
            $talent_order->order_response_id = $order_response->id;
            $talent_order->save();

        }

        Session::forget('omneeyat_or');
        Session::put( 'omneeyat_or' , $order_request->id );

        return response()->json( [ $order_request , $order_request->id , url( 'payment/pending' ) ] );
    }

    public static function freeOrder( $order_request ) {
        // FREE ORDER
        $order_response                   = new OrderResponse();
        $order_response->order_request_id = $order_request->id;
        $order_response->status           = 0;
        $order_response->response         = json_encode( [ '' ] );
        $order_response->save();

        $total = PromoCodeHelper::getDiscountedPrice( $order_request );
        if ( $total <= 0 ) {
            // CHECKING IF ORDER IS ACTUALLY FREE OR NOT
            $order_response->status = 3;
            $order_response->save();

            PromoCodeHelper::promoCount( $order_request );
            if ( $order_request->order_type == Gru::VIDEO_ORDER_TYPE ) {
                $talent_order = PaymentHelper::informTalent( $order_response );
//                Notification::route( 'mail' , $order_request->email )->notify( new CustomerOrderNotification( $order_request ) );
//                Notification::route( 'mail' , $order_request->talent->user->email )->notify( new TalentOrderNotification( $order_request , $talent_order ) );

            }
            return url( 'promo' , [ 'status' => 1 ] );
        }
        else {
            return url( 'promo' , [ 'status' => 0 ] );
        }
    }

    public static function totalPrice( OrderRequest $order_request ) {

        $addon_price = ( $order_request->total_addons_price != NULL ? $order_request->total_addons_price : 0 ) + (
            $order_request->talent_duo_price_first != NULL ? $order_request->talent_duo_price_first : 0 ) + (
                $order_request->talent_duo_price_second != NULL ? $order_request->talent_duo_price_second : 0 );

        if ( self::checkPromoCode( $order_request ) ) {
            $total = ( $order_request->price != NULL ? $order_request->price : 0 );

            if ( $order_request->promo_code->type == 0 ) {
                $total = $total - $order_request->promo_code->number;

                if ( $total < 0 ) {
                    $total = 0;
                }

                $total = $total + $addon_price;
            }
            else {
                $total = $total * ( 100 - $order_request->promo_code->number ) / 100;
                $total = $total + $addon_price;
            }
        }
        else if ( $order_request->order_type == 2 ) {
            $total = $order_request->total_addons_price;
        }
        else {
            $total = ( $order_request->price != NULL ? $order_request->price : 0 ) + $addon_price;
        }

        return $total;
    }

    private static function checkPromoCode( OrderRequest $order_request ) {
        if ( $order_request->promo_code && $order_request->order_type == 1 ) {

            if (
                $order_request->promo_code->is_active == 1 && Carbon::now()->gte( $order_request->promo_code->start_date ) && Carbon::now()->lte( $order_request->promo_code->expiry_date )
            ) {

                return true;
            }
            else {

                return false;
            }
        }
        else {

            return false;
        }
    }

    public static function productName( OrderRequest $order_request , $total ) {
        if ( $order_request->order_type == Gru::VIDEO_ORDER_TYPE ) {

            $product_name = $order_request->talent->user->getTranslation( 'name' , 'en' ) . ' - ' .
                            $order_request->occasion->name;
        }
        else if ( $order_request->order_type == Gru::BUSINESS_ORDER_TYPE ) {

            $product_name = $order_request->talent->user->getTranslation( 'name' , 'en' ) . ' - ' . $order_request->id .
                            '  (USD ' . $total . ')';
        }

        if ( $order_request->total_addons_price > 0 ) {
            $addons_price = ( $order_request->total_addons_price != NULL ? $order_request->total_addons_price : 0 ) +
                            ( $order_request->talent_duo_price_first != NULL ?
                                $order_request->talent_duo_price_first : 0 ) + (
                                    $order_request->talent_duo_price_second != NULL ?
                                        $order_request->talent_duo_price_second : 0 );

            $product_name = $product_name . ' (USD ' . $order_request->price . ') + Add-ons (USD ' . $addons_price . ')';
        }

        if ( self::checkPromoCode( $order_request ) ) {

            $product_name = $product_name . '+ Promo Code (';

            if ( $order_request->promo_code->type == 0 ) {
                $product_name = $product_name . 'USD ' . $order_request->promo_code->number;
            }
            else {

                $product_name = $product_name . '% ' . $order_request->promo_code->number;
            }
            $product_name = $product_name . ')';
        }
        else if ( $order_request->order_type == Gru::BUSINESS_ORDER_TYPE ) {

            $product_name = $order_request->talent->user->getTranslation( 'name' , 'en' ) . ' - ' . $order_request->id .
                            '  (USD ' . $total . ')';
        }

        return $product_name;
    }

    public static function handleTelrResponse( $order_request ) {

        $payment = new Telr();
        $payment->validate_order( $order_request->order_ref );

        $order_status = $payment->getResponseData()->order->status->code;

        $order_response = OrderResponse::where( 'order_request_id' , $order_request->id )->first();

        if ( $order_request->order_type == Gru::VIDEO_ORDER_TYPE ) {
            PaymentHelper::informTalent( $order_response  );
        }

        if ( ! $order_response ) {
            OrderHelper::createOrderResponse( $order_request , $order_status );
        }
        else {
            $order_response->status   = $order_status;
            $order_response->response = json_encode( $payment->getResponseData() );
            $order_response->save();
        }
        return $payment;

    }

    public static function handleStripeResponse( $request , $order_request ) {
        $payment        = new Stripe();

        [$payment_status , $response ] = $payment->validate_order( $order_request->order_ref , $order_request );

        $order_response = OrderResponse::where('order_request_id',$order_request->id)->first();
        $order_response->response = json_encode( $response );
        $order_response->save();

        return [$payment_status , $response ] ;
    }

    public static function handlePaypalResponse( $request , $order_request ) {

        $token   = $request->get( 'token' );
        $payerId = $request->get( 'PayerID' );

        $payment  = new Paypal();
        $provider = new ExpressCheckout();

        $data     = $payment->setUpRequest( $order_request , $request);

        $response = $provider->doExpressCheckoutPayment( $data , $token , $payerId );

        $order_response = OrderResponse::where( 'order_request_id' , '=' , $order_request->id )->first();

        if ( ! $order_response ) {
            $order_response                   = new OrderResponse();
            $order_response->order_request_id = $order_request->id;
            $order_response->status           = 4;
            $order_response->response         = json_encode( $response );
            $order_response->save();
        }
        if ( $order_request->order_type == Gru::VIDEO_ORDER_TYPE ) {
            $talent_order = TalentOrder::where( 'talent_id' , $order_request->talent_id )->where( 'order_response_id' , $order_response->id )->first();

            if ( ! $talent_order ) {
                $talent_order                    = new TalentOrder();
                $talent_order->talent_id         = $order_request->talent_id;
                $talent_order->order_response_id = $order_response->id;
                $date                            = date( "Y-m-d H:i" );
                $talent_order->first_reminder    = date( 'Y-m-d H:i' , strtotime( $date . '+4 day' ) );
                $talent_order->due_date          = date( 'Y-m-d H:i' , strtotime( $date . '+' . $order_request->talent->response_time . 'day' ) );
                $talent_order->save();
            }
        }

        if ( $response[ 'ACK' ] == "Success" || $response[ 'ACK' ] == "SuccessWithWarning" ) {


            $order_response->status = 3;
            $order_response->save();

            PromoCodeHelper::promoCount( $order_request );

            if ( $order_request->order_type == Gru::VIDEO_ORDER_TYPE ) {
                PaymentHelper::informTalent( $order_response  );
            }

            return 1;
        } else {
            $order_request          = OrderRequest::find( $order_request->id );
            $order_response         = OrderResponse::where( 'order_request_id' , $order_request->id )->first();
            $order_response->status = - 3;
            $order_response->save();

            if ( ! $order_response ) {
                $order_request->delete();
            }

            return 0;
        }
    }


}
