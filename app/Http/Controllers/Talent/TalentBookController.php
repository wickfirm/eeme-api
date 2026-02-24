<?php

namespace App\Http\Controllers\Talent;

use App\Helpers\ApiHelper;
use App\Helpers\Gru;
use App\Helpers\Talent\TalentBookHelper;
use App\Helpers\Talent\TalentHelper;
use App\Http\Controllers\Controller;
use App\Models\Addons\Addon;
use App\Models\Misc\Category;
use App\Models\Misc\Occasion;
use App\Models\Order\PromoCode;
use App\Models\Talent\TalentAddons;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TalentBookController extends Controller {


    public function index ($lang , $talent , Request $request){

        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;

        $_occasions = Occasion::get();
        if ( $status == Gru::MAIN_DOMIAN || $status == Gru::AGENCY_SUBDOMAIN ) {
            $page_a = TalentHelper::getPage (Gru::BOOK_PAGE_ID, $agency);
            $talent = TalentHelper::getTalent( $talent , 1 , $lang , $agency );

            if ( $talent && $talent->is_available && $talent->talent_order_types->count() > 0 && $talent->is_verified == 1) {

                $business_addons = Addon::whereHas( 'talents' , function ( $query ) use ( $talent ) {
                    $query->where( 'talent_addons.is_active' , 1 )
                           ->where( 'talent_id' , $talent->id );
                } )->where( 'order_type' , 2 )
                    ->with(['talent_addon' => function($query) use ($talent){
                        $query->where('talent_id',$talent->id);
                    }])
                    ->get()->groupBy('category_addon_id');


                $personal_addons = TalentAddons::whereHas( 'addons' , function ( $query ) {
                    $query->where( 'order_type' , 1 );
                } )->where( 'talent_id' , $talent->id )->where( 'is_active' , 1 )->with('addons')->get();

                $type = "" ;
                if ($talent->talent_order_types[0]->order_type === 1){
                    $type = 'personalized';
                }else  if ($talent->talent_order_types[0]->order_type === null){
                    $type = 'business';
                }else  if ($talent->talent_order_types[0]->order_type === 3){
                    $type = 'request-brief';
                }

                  return response ()->json ([
                      'data' => [
                          '_agency'             => $_agency,
                          'url'                 => $url,
                          'page_a'              => $page_a,
                          'occasion'            => $_occasions,
                          'talent'              => $talent->fresh ('user', 'talent_order_types.business_order_type', 'price'),
                          'business_addons'     => $business_addons->toArray (),
                          'personalized_addons' => $personal_addons,
                          'type'                => $type
                      ]

                ]);

            }else{
                return ApiHelper::notFoundError ();
            }

        }
    }

    public function store( Request $request , $talent ) {
        $validator = Validator::make( $request->all() , [
            'order_type' => 'required' ,
        ] );
        if ( $validator->fails() ) {
            return ApiHelper::return_error( Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors(), 'Please enter the required fields' );
        }





        $type = $request->get( 'order_type' );

        if ( $type == Gru::VIDEO_ORDER_TYPE ) {

            return TalentBookHelper::bookVideoOrder( $talent , $request );
        }
        elseif ( $type == Gru::BUSINESS_ORDER_TYPE ) {
            return TalentBookHelper::bookBusinessOrder( $talent , $request );

        }elseif ($type == Gru::CAMPAIGN_ORDER_TYPE){

            return TalentBookHelper::bookCampaignOrder( $talent , $request );
        }
        else {
            return ApiHelper::notFoundError ();
        }
    }


    public function promoCodeValidity( $talent , $promo_code) {

        $promo_code = PromoCode::where( 'code' , $promo_code )->where( 'is_active' , 1 )->first();

        if ( $promo_code ) {
            if ( $talent == $promo_code->talent_id || $promo_code->talent_id == null ) {
                if ( Carbon::now()->gte( $promo_code->start_date ) && Carbon::now()->lte( $promo_code->expiry_date ) ) {
                    if ( $promo_code->count > 0 || $promo_code->count == - 1 ) {

                        return response ()->json ([
                            'data' => [
                                'is_valid'          => true,
                                'promo_code_number' => $promo_code->number,
                                'promo_code_type'   => $promo_code->type,
                                'promo_type'        => $promo_code->promo_type,
                                'addons'            => $promo_code->addons->pluck ('id')->toArray (),
                            ]

                        ]);

                    }
                }
            }
        }

        return response ()->json ([
            'data' => [
                'is_valid'          => false,
                'promo_code_number' => null,
                'promo_code_type'   => null,
                'promo_type'        => null,
                'addons'            => null,

            ]

        ]);

    }

    public function promo_code_validity_null() {
        return [ 0 , null , null ];
    }
}
