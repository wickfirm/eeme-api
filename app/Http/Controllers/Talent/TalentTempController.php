<?php

namespace App\Http\Controllers\Talent;

use App;
use App\Helpers\ApiHelper;
use App\Helpers\Gru;
use App\Helpers\Talent\TalentHelper;
use App\Http\Controllers\Controller;
use App\Models\Addons\Addon;
use App\Models\Article\Article;
use App\Models\Misc\BusinessOrderType;
use App\Models\Misc\Category;
use App\Models\Misc\Occasion;
use App\Models\Page;
use App\Models\Paudio\PaudioResponse;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentAddons;
use App\Models\Talent\TalentArticle;
use App\Models\Talent\TalentOrderType;
use App\Models\Talent\TalentSocialPost;
use App\Models\Talent\TalentVideo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TalentTempController extends Controller {
    public function index($lang , $talent , $title , Request $request){



    [$status, $agency, $url] = TalentHelper::checkSubdomain ($request);
    $_agency = (bool)$agency;

    if ( $status == Gru::MAIN_DOMIAN || $status == Gru::AGENCY_SUBDOMAIN ) {
        $page_a = TalentHelper::getPage( Gru::ARTICLE_PAGE_ID , $agency );

        $talent = TalentHelper::getTalent( $talent , 0 , $lang , $agency );


        if ( $talent ) {

            $article = TalentHelper::getMainArticle( $talent , $title ,$lang);

            if ( $talent && $article ) {

                $talent_video = TalentHelper::getTalentVideos( $talent , 1 , $agency );

                $articles = Article::whereHas( 'talents' , function ( $query ) use ( $talent ) {
                    $query->where( 'talent_id' , $talent->id );
                } )->where( 'id' , '!=' , $article->id )->where( 'is_published' , 1 )->orderby( 'created_at' , 'DESC' )->paginate(6);

                if($request->get('page')){
                    return  Article::whereHas( 'talents' , function ( $query ) use ( $talent ) {
                        $query->where( 'talent_id' , $talent->id );
                    } )->where( 'id' , '!=' , $article->id )->where( 'is_published' , 1 )->orderby( 'created_at' , 'DESC' )->paginate(6) ;
                }

                return response ()->json ([
                    'talent'     => $talent,
                    'article'    => $article,
                    'articles'   => $articles,
                    'video'      => $talent_video,
                    'page_a'     => $page_a,
                    '_agency'    => $_agency,
                    'url'        => $url
                ]);

            }
            else {
                abort( 404 );
            }
        }
        else {
            [$status, $url ] = TalentHelper::redirectToCorrectURL($talent, false ,$title);

            if( $status == true ){
//                    redirect()->to( $url )->send();
            }else{
//                    abort(404);
            }

        }
    }




}

    public function show( Request $request  , $lang, $talent = null ) {

        $slug = $talent;

        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;
        if ( $status == Gru::MAIN_DOMIAN || $status == Gru::AGENCY_SUBDOMAIN ) {
//            $page_a = TalentHelper::getPage( Gru::SINGLE_PAGE_ID , $agency );

//            TalentHelper::checkFollowing( $talent );
            if ( isset( $agency ) ) {
                if ( $lang == 'en' ) {
                    $talent = Talent::where( 'slug->en' , $talent )
                        ->where( 'is_published' ,0 )
                        ->whereHas( 'agency' , function ( $query ) use ( $agency ) {
                            $query->where( 'agency_id' , $agency->id );
                        } )->first();
                }
                else {
                    $talent = Talent::where( 'slug->ar' , $talent )->where( 'is_published' ,1 )->whereHas( 'agency' , function ( $query ) use ( $agency ) {
                        $query->where( 'agency_id' , $agency->id );
                    } )->first();
                }
            }
            else {

                if ( $lang == 'en' ) {

                    $talent = Talent::where( 'slug->en' , $talent )
                        ->where( 'is_published' , 0 )
                        ->doesntHave( 'agency' )
                        ->with('talent_social.talent_social_info','categories', 'charities')
                        ->first();
                }
                else {
                    $talent = Talent::where( 'slug->ar' , $talent )
                        ->where( 'is_published' , 0 )
                        ->doesntHave( 'agency' )
                        ->with('talent_social.talent_social_info' , 'categories', 'charities')
                        ->first();
                }
            }
            if ( $talent ) {


                $articles = Article::whereHas( 'talents' , function ( $query ) use ( $talent ) {
                    $query->where( 'talent_id' , $talent->id );
                } )->where( 'is_published' , 1 )->orderby( 'created_at' , 'DESC' )->paginate(6);

                $talent_main_video = TalentVideo::where('talent_id',$talent->id)->where('is_main' ,1 ) -> first();
                return response ()->json ([

                    'data' => [
                        'talent'            => $talent,
                        'talent_main_video' => $talent_main_video,
                        '_agency'           => $_agency,
                        'url'               => $url,
                        'articles'          => $articles,
                    ]

                ]);
            }
            elseif( $talent == null ) {

                [$status, $url ] = TalentHelper::redirectToCorrectURL($slug , false );

                if( $status == true ){
                    redirect()->to( $url )->send();
                }else{

                    abort(404);
                }

            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }

    public function book ($lang , $talent , Request $request){

        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;
        $_occasions = Occasion::get();
        if ( $status == Gru::MAIN_DOMIAN || $status == Gru::AGENCY_SUBDOMAIN ) {
            $page_a = TalentHelper::getPage (Gru::BOOK_PAGE_ID, $agency);
            $talent = TalentHelper::getTalent( $talent , 0 , $lang , $agency );

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
}
