<?php

namespace App\Helpers\Talent;

use App\Helpers\ApiHelper;
use App\Helpers\Gru;
use App\Helpers\Payment\PaymentHelper;
use App\Helpers\PromoCode\PromoCodeHelper;
use App\Models\Addons\Addon;
use App\Models\Addons\OrderDuoAddons;
use App\Models\Campaign\Campaign;
use App\Models\Order\OrderRequest;
use App\Models\Order\OrderResponse;
use App\Models\Order\PromoCode;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentAddons;
use App\Models\Talent\TalentCampaign;
use App\Models\Talent\TalentOrder;
use App\Notifications\CustomerCampaignNotification;
use App\Notifications\CustomerOrderNotification;
use App\Notifications\TalentOrderNotification;
use App\Notifications\WesternUnionOrderNotification;
use App\Payment\Telr;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class TalentBookHelper {
    private $order_request_id;

    public function __construct( $order_request_id ) {
        $this->order_request_id = $order_request_id;
    }
    public static function bookVideoOrder( $talent , $request ) {
        $validator      = Validator::make( $request->all() , [
            'video_target_option' => 'required' ,
            'occasion_id'            => 'required' ,
            'message'             => 'required' ,
            'email'               => 'required' ,
            'phone_number'        => 'required'
        ] );
        if ( $validator->fails() ) {
            return ApiHelper::return_error( Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors(), 'Please enter the required fields' );
        }

        $other_recipient = $request->get( 'other_recipient' );
        $payment_method  = $request->get( 'payment_method' );

        $video_target_option = $request->get('video_target_option'); //if: 2 => other_recipient


        if ( $request->has( 'other_sender' ) ) {
            $talent = Talent::find($talent);

            if ( $talent ) {
                $order_request            = new OrderRequest();
                $order_request->talent_id = $talent->id;
                $order_request->type      = $request->get( 'video_target_option' );
                $order_request->sender    = $request->get( 'other_sender' );
                if ( $video_target_option == 2 ) {
                    $order_request->recipient = $request->get( 'other_recipient' );
                }
                $addons = implode(',', $request->get( 'addons' ));
                $order_request->occasion_id        = $request->get( 'occasion_id' );
                $order_request->message            = $request->get( 'message' );
                $order_request->price              = $talent->price->where( 'talent_price_type_id' , 1 )->first()->price;
                $order_request->email              = $request->get( 'email' );
                $order_request->is_public          = $request->get( 'is_public' );
                $order_request->payment_method     = $payment_method;
                $order_request->addons_ids         = json_encode( $addons );
                $order_request->total_addons_price = $request->get( 'price' );
                $order_request->phone_number       = $request->get( 'phone_number' );
                $order_request->order_type         = 1;

                $promo_code                        = PromoCode::where( 'code' , $request->promo_code )->where( 'is_active' , 1 )->first();

               $order_request = self::checkPromoCodeValidity( $promo_code , $talent , $order_request );

                self::handleOrderAddons( $order_request , $request , $talent );

                return PaymentHelper::paymentProcessing($request, $order_request, $order_request->payment_method);

            }

        }
    }

    public static function bookBusinessOrder( $talent , $request ) {

        $validator = Validator::make( $request->all() , [
            'other_sender'  => 'required' ,
            'email'        => 'required' ,
            'phone_number' => 'required'
        ] );


        if ( $validator->fails() ) {

            return ApiHelper::return_error( Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors(), 'Please enter the required fields' );

        }
        $sender = $request->get( 'other_sender' );
        $payment_method  = $request->get( 'payment_method' );


        if ( $request->has( 'other_sender' ) ) {

            $talent = talent::find( $talent );

            if ( $talent && $talent->is_available == 1 ) {
                $addons = implode(',', $request->get( 'addons' ));
                $order_request                = new OrderRequest();
                $order_request->talent_id = $talent->id;
                $order_request->type          = 1;
                $order_request->sender        = $sender;


                $order_request->message            = $request->get( 'message' );
                $order_request->price              = 0;
                $order_request->email              = $request->get( 'email' );
                $order_request->payment_method     = $payment_method;
                $order_request->addons_ids         = json_encode( $addons );
                $order_request->total_addons_price = $request->get( 'price' );
                $order_request->phone_number       = $request->get( 'phone_number' );
                $order_request->order_type         = Gru::BUSINESS_ORDER_TYPE;
                $order_request->save();

                $promo_code = PromoCode::where( 'code' , $request->get( 'promo_code' ) )->where( 'is_active' , 1 )->first();

                self::checkPromoCodeValidity( $promo_code , $talent , $order_request );
                self::handleOrderAddons( $order_request , $request , $talent );

                return PaymentHelper::paymentProcessing( $request,$order_request , $payment_method );
            }
            else {
                abort( 404 );
            }
        }
    }

    public static function bookCampaignOrder( $talent , $request ) {

        $validator = Validator::make( $request->all() , [
            'name'          => 'required' ,
            'campaign_name' => 'required' ,
            'brand_name'    => 'required' ,
            'usage_rights'  => 'required' ,
            'delivery_date' => 'required' ,
            'message'         => 'required' ,
            'email'         => 'required' ,
            'custom_fee'           => 'required' ,
        ] );

        if ( $validator->fails() ) {

            return redirect()->back()->withInput()->with( 'errors' , $validator->errors() );
        }

        $talent = Talent::find( $talent );
        if ( $talent && $talent->is_available ) {

            $delivery_date = Carbon::make( $request->get( 'delivery_date' ) )->toDateTimeString();

            $campaign = new Campaign();

            $campaign->setTranslation( 'client_name' , 'en' , $request->get( 'name' ) );
            $campaign->setTranslation( 'name' , 'en' , $request->get( 'campaign_name' ) );
            $campaign->setTranslation( 'brand_name' , 'en' , $request->get( 'brand_name' ) );

            $campaign->brief         = $request->get( 'message' );
            $campaign->email         = $request->get( 'email' );
            $campaign->usage_rights  = $request->get( 'usage_rights' );
            $campaign->delivery_date = $delivery_date;

            $campaign->type = Gru::TALENT_USER_TYPE;

            if ( $request->filled( 'extra_settings_1' ) && $request->filled( 'extra_settings_2' ) ) {
                $campaign->extra_settings = 3;
            }
            elseif ( $request->filled( 'extra_settings_1' ) ) {
                $campaign->extra_settings = 1;
            }
            elseif ( $request->filled( 'extra_settings_2' ) ) {
                $campaign->extra_settings = 2;
            }

            $campaign->save();
        }
        if ( $request->get( 'custom_number' ) ) {
            $campaign->custom_usage_right_number = $request->get( 'custom_number' );
            $campaign->save();
        }

        TalentCampaign::create( [
            'talent_id'   => $talent->id ,
            'campaign_id' => $campaign->id ,
            'fee'         => $request->get( 'custom_fee' ) ,
            'status'      => Gru::PENDING_CAMPAIGN
        ] );

        return
            [
                'data' => [
                    'status'              =>  1 ,
                ]
            ];
//        Notification::route( 'mail' , $campaign->email )->notify( new CustomerCampaignNotification() );
    }

    public static function checkPromoCodeValidity( $promo_code , $talent , $order_request ) {
        if ( $promo_code ) {
            if ( $talent->id == $promo_code->talent_id || $promo_code->talent_id == null ) {
                if ( Carbon::now()->gte( $promo_code->start_date ) && Carbon::now()->lte( $promo_code->expiry_date ) ) {
                    if ( $promo_code->count > 0 || $promo_code->count == - 1 ) {
                        $order_request->promo_code_id = $promo_code->id;
                    }
                }
            }
        }
        $order_request->save();

        return $order_request;
    }

    public static function handleOrderAddons( $order_request , $request , $talent ) {
        if ( $request->get( 'addons' ) != null ) {

            $total_addons = 0;

            $talent_duo_price_first = 0;

            $talent_duo_price_second = 0;

            $matches = explode( "," , $order_request->addons_ids );
            $ai      = [];
            foreach ( $matches as $value ) {
                $n     = str_replace( '"' , '' , $value );
                $ai [] = $n;
            }

            if ( count( $ai ) >= 2 ) {

                $a = TalentAddons::where( 'talent_id' , $talent->id )->whereIn( 'addons_id' , $ai )->get();

                foreach ( $a as $aa ) {

                    if ( $aa->addons->talent_duo_id != null ) {

                        $talent_duo_price_first  = $talent_duo_price_first + ( $aa->price * .60 );
                        $talent_duo_price_second = $talent_duo_price_second + ( $aa->price * .40 );

                        $addons_duo                   = new OrderDuoAddons ();
                        $addons_duo->order_request_id = $order_request->id;
                        $addons_duo->addons_id        = $aa->addons_id;
                        $addons_duo->talent_duo_id    = $aa->addons->talent_duo_id;
                        $addons_duo->talent_duo_price = $aa->price * .40;
                        $addons_duo->save();
                    }
                    else {
                        $total_addons = $total_addons + $aa->price;
                    }
                }
            }
            else {

                $a = TalentAddons::where( 'addons_id' , $ai )->where( 'talent_id' , $talent->id )->first();

                $aa = Addon::where( 'id' , $ai )->first();

                if ( $a->talent_duo_id != null ) {

                    $talent_duo_price_first  = $talent_duo_price_first + ( $aa->price * .60 );
                    $talent_duo_price_second = $talent_duo_price_second + ( $aa->price * .40 );

                    $addons_duo                   = new OrderDuoAddons ();
                    $addons_duo->order_request_id = $order_request->id;
                    $addons_duo->addons_id        = $a->addons_id;
                    $addons_duo->talent_duo_id    = $aa->talent_duo_id;
                    $addons_duo->talent_duo_price = $a->price * .40;
                    $addons_duo->save();
                }
                else {
                    $total_addons = $total_addons + $a->price;
                }
            }

            OrderRequest::where( 'id' , $order_request->id )->update( [
                'total_addons_price'      => $total_addons ,
                'talent_duo_price_first'  => $talent_duo_price_first ,
                'talent_duo_price_second' => $talent_duo_price_second
            ] );
        }
    }

}
