<?php

namespace App\Http\Controllers;

use ActiveCampaign;
use App\Helpers\ApiHelper;
use App\Helpers\Gru;
use App\Helpers\Minion;
use App\Helpers\Talent\TalentHelper;
use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Misc\Category;
use App\Models\Order\OrderRequest;
use App\Models\Talent\Talent;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller {


    public function index(Request $request){


        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        if ( $status == Gru::AGENCY_SUBDOMAIN || $status == Gru::MAIN_DOMIAN ) {
            $is_home = true ;


            $page_a           = TalentHelper::getPage( Gru::HOME_PAGE_ID , $agency );
            $talents_priority = TalentHelper::getTalents( 6 , $page_a  , 0 , true);
        }
        elseif ( $status == Gru::NOT_DEFINED_DOMAIN ) {
            ApiHelper::notFoundError ();
        }
        $_agency = (bool)$agency;


        return response ()->json ([

            'page_a'             => $page_a,
            'talents'            => $talents_priority,
            '_agency'            => $_agency,
            'url'                => $url,

        ]);



    }

    public function getMoreData(Request $request){
        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $page_a           = TalentHelper::getPage( Gru::HOME_PAGE_ID , $agency );


        $talents_priority = TalentHelper::getTalents( 18 , $page_a , true , 6);

        return response ()->json ([

            'talents'            => $talents_priority,


        ]);

    }
}
