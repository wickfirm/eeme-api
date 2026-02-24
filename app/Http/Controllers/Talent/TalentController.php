<?php

namespace App\Http\Controllers\Talent;

use ActiveCampaign;
use App\Helpers\ApiHelper;
use App\Helpers\Gru;
use App\Helpers\Minion;
use App\Helpers\Talent\TalentHelper;
use App\Helpers\Talent\TalentSocialHelper;
use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Misc\Category;
use App\Models\Order\OrderRequest;
use App\Models\Recipe\RecipeCategory;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentArticle;
use App\Models\Talent\TalentFilmographyCast;
use App\Models\Talent\TalentNotify;
use App\Models\Talent\TalentPlatform;
use App\Models\Talent\TalentPlatformLink;
use App\Models\Talent\TalentSocial;
use App\Models\Talent\TalentSocialInfo;
use App\Models\Talent\TalentSocialPost;
use App\Models\Talent\TalentVerifyContact;
use App\Models\Talent\TalentVideo;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class TalentController extends Controller {


    public function index(Request $request){
        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;
        $page_a = TalentHelper::getPage( Gru::TALENT_INDEX_PAGE_ID , $agency );
        $articles         = TalentHelper::getArticles( 6 , $agency );
        $both_types=[];
        $talents =[] ;
        $talents_priority =[];
        if ( $status == Gru::MAIN_DOMIAN ) {
            $both_types = Talent::orderBy( 'created_at' , 'DESC' )
                ->where( 'is_published' , 1 )->where( 'is_available' , 1 )
                ->where( 'id' , '<>' , 1 )
                ->where('is_verified',1)
                ->where( 'id' , '<>' , 71 )
                ->doesntHave( 'agency' )
                ->whereHas( 'talent_order_types' , function ( $query ) {
                if ( ( $query->where( 'order_type' , null )->orWhere( 'order_type' , 3 ) ) ) {
                    return $query;
                }
            } )  ->with('talentLatestArticle.article','categories')->paginate( 12 );

            $talents = Talent::orderBy( 'created_at' , 'DESC' )
                ->where( 'id' , '<>' , 1 )
                ->where( 'id' , '<>' , 71 )
                ->where( 'is_published' , 1 )
                ->where('is_verified',1)
                ->where( 'is_available' , 1 )
                ->doesntHave( 'agency' ) ->with('categories')->paginate( 12) ;

            $talents_priority = Talent::where( 'is_published' , 1 )
                ->where( 'id' , '<>' , 1 )
                ->where( 'id' , '<>' , 71 )
                ->where( 'is_available' , 1 )
                ->where('is_verified',1)
                ->doesntHave( 'agency' )
                ->with('talentLatestArticle.article','categories')
                ->paginate( 12);
        }
        elseif ( $status == Gru::AGENCY_SUBDOMAIN ) {

            $both_types = Talent::orderBy( 'created_at' , 'DESC' )->where( 'id' , '<>' , 1 )->where( 'id' , '<>' , 71 )->where( 'is_published' , 1 )->where( 'is_available' , 1 )->where('is_verified',1)->whereHas( 'agency' , function ( $q ) use ( $agency ) {
                return $q->where( 'agencies.id' , $agency->id );
            } )->whereHas( 'talent_order_types' , function ( $query ) {
                if ( ( $query->where( 'order_type' , null )->orWhere( 'order_type' , 3 ) ) ) {
                    return $query;
                }
            } ) ->with('categories')->paginate( 12 );

            $talents = Talent::orderBy( 'created_at' , 'DESC' )->where( 'id' , '<>' , 1 )->where( 'id' , '<>' , 71 )->where( 'is_published' , 1 )->where( 'is_available' , 1 )->where('is_verified',1)->whereHas( 'agency' , function ( $q ) use ( $agency ) {
                return $q->where( 'agencies.id' , $agency->id );
            } ) ->with('categories')->paginate( 12 );

            $talents_priority = Talent::where( 'is_published' , 1 )->where( 'id' , '<>' , 1 )->where( 'id' , '<>' , 71 )->where( 'is_available' , 1 )->where('is_verified',1)->whereHas( 'agency' , function ( $q ) use ( $agency ) {
                return $q->where( 'agencies.id' , $agency->id );
            } ) ->with('categories')->paginate( 12 );
        }

        if($request->get('page') != null ){
            if($request->get('type') != null ){
                if($request->get('type') == 1 ){
                    $talents_priority = Talent::where( 'is_published' , 1 )
                        ->where( 'id' , '<>' , 1 )
                        ->where( 'id' , '<>' , 71 )
                        ->where( 'is_available' , 1 )
                        ->where('is_verified',1)
                        ->doesntHave( 'agency' )
                        ->with('talentLatestArticle.article','categories')
                        ->paginate( 12);

                    return $talents_priority;
              }  elseif($request->get('type') == 2 ) {
                    $both_types = Talent::orderBy( 'created_at' , 'DESC' )
                        ->where( 'is_published' , 1 )
                        ->where( 'is_available' , 1 )
                        ->where( 'id' , '<>' , 1 )
                        ->where('is_verified',1)
                        ->where( 'id' , '<>' , 71 )
                        ->doesntHave( 'agency' )
                        ->whereHas( 'talent_order_types' , function ( $query ) {
                        if ( ( $query->where( 'order_type' , null )
                            ->orWhere( 'order_type' , 3 ) ) ) {
                            return $query;
                        }
                    } )  ->with('talentLatestArticle.article','categories')
                        ->paginate( 12 );

                    return $both_types;
                }
            }
        }else{
            return response ()->json ([
                'articles'         => $articles,
                'both_types'       => $both_types,
                'page_a'           => $page_a,
                'talents'          => $talents,
                'talents_priority' => $talents_priority,
                '_agency'          => $_agency,
                'url'              => $url
            ]);
        }


    }

    public function show( Request $request  , $lang, $talent = null ) {

        $slug = $talent;

        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;

        if ( $status == Gru::MAIN_DOMIAN || $status == Gru::AGENCY_SUBDOMAIN ) {
            $page_a = TalentHelper::getPage( Gru::SINGLE_PAGE_ID , $agency );

            if ( isset( $agency ) ) {
                if ( $lang == 'en' ) {
                    $talent = Talent::where( 'slug->en' , $talent )
                        ->where( 'is_published' ,1 )
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
                        ->where( 'is_published' , 1 )
                        ->doesntHave( 'agency' )
                        ->with('categories', 'charities')
                        ->first();
                }
                else {
                    $talent = Talent::where( 'slug->ar' , $talent )
                        ->where( 'is_published' , 1 )
                        ->doesntHave( 'agency' )
                        ->with( 'categories', 'charities')
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
            } elseif( $talent == null ) {

                [$status, $url ] = TalentHelper::redirectToCorrectURL($slug , false );

                if( $status == true ){
                    redirect()->to( $url )->send();
                }else{
                    return ApiHelper::notFoundError ();
//                    abort(404);
                }

            }else{
                return ApiHelper::notFoundError ();
            }
        }else{
            return ApiHelper::notFoundError ();
        }
    }

    public function notify(   $talent , Request $request ) {

        $talent = Talent::find($talent);


        if ( $talent ) {
            $notify_exist = TalentNotify::where( 'talent_id' , $talent->id )->where( 'email' , $request->get( 'email' ) )->first();

            if ( ! $notify_exist ) {
                $notify            = new TalentNotify();
                $notify->talent_id = $talent->id;
                $notify->email     = $request->get( 'email' );
                $notify->save();
            }

            return response ()->json ([

                'data' => [
                    'success' => 1
                ]]);
        }
        else {
            abort( 404 );
        }
    }

    public function getSpotFirstItem($lang , $talent, $is_published){


        if ( $lang == 'en' ) {

            $talent = Talent::where( 'slug->en' , $talent )
                ->where( 'is_published' , (int)$is_published )
                ->with('talent_social.talent_social_info' )
                ->first();
        }
        else {
            $talent = Talent::where( 'slug->ar' , $talent )
                ->where( 'is_published' , (int)$is_published )
                ->with('talent_social.talent_social_info' )
                ->first();
        }


        [
            $first_item ,
            $items
        ] = TalentHelper:: getFirstInthespotItem( $talent  );

        $nav_items = [] ;

        if ( isset($talent->platforms) && $talent->platforms->where( 'type' , Gru::IN_THE_SPOT )->where( 'is_published' , 1 )->count() > 0 ) {
            array_push ($nav_items, Gru::IN_THE_SPOT);
        }
        if ( TalentFilmographyCast::where( 'talent_id' , $talent->id )->whereHas( 'filmography' , function ( $query ) {
                $query->where( 'is_published' , 1 );})->count() > 0 ) {
            array_push ($nav_items, Gru::FILMOGRAPHY);

        }
        if ( isset($talent->platforms) && $talent->platforms->where( 'type' , Gru::IN_THE_MEDIA )->where( 'is_published' , 1 )->count() > 0 ) {
            array_push ($nav_items,  Gru::IN_THE_MEDIA);
        }
        if (TalentSocialPost::whereHas ('talent_social.talent', function ($query) use ($talent) {
                $query->where ('talent_id', $talent->id);
            })->first () !== null ) {
            array_push ($nav_items,   Gru::TALENT_SOCIAL);
        }
       if (TalentVideo::where ('is_published', 1)->where ('talent_id', $talent->id)->orderby ('created_at', 'DESC')->whereHas ('talent_order.order_response.order_request', function ($query) {
                $query->where ('is_public', 1);
            })->with ('video')->first () !== null) {
            array_push ($nav_items,   Gru::TALENT_VIDEOS );

        }
       if (TalentSocialInfo::whereHas ('talent_social.talent', function ($query) use ($talent) {$query->where ('talent_id', $talent->id);})->first () !== null)  {
            array_push ($nav_items,   Gru::INSIGHTS );

        }
        if ( isset( $talent->collaborations ) && $talent->collaborations->count() > 0 ) {
            array_push ($nav_items,Gru::COLLABS);

        }


        return response ()->json ([

            'data' => [
                'nav_items'  => $nav_items,
                'first_item' => $first_item,
                'items'      => $items,
                'talent' => $talent
            ]
        ]);

    }

    public function getSpotData($lang , $talent , $type){
        $first_item = null;
        $followers_stat = null;
        $following_stat = null;
        $likes_stat = null;
        $views_stat = null;

        $languages_stat = null;
        $ethnicity_stat = null;
        $country_stat = null;
        $city_stat = null;
        $male_stat_age = null;
        $female_stat_age = null;
        $reachability_stat = null;
        $gender_stat = null;

        $languages_aud_stat = null;
        $ethnicity_aud_stat = null;
        $country_aud_stat = null;
        $city_aud_stat = null;
        $male_aud_stat_age = null;
        $female_aud_stat_age = null;
        $reachability_aud_stat = null;
        $gender_aud_stat = null;

        $gender_comment_stat = null;
        $languages_comment_stat = null;
        $female_comment_stat_age = null;
        $male_comment_stat_age = null;
        $social_nav_items = [];
        $items = [] ;
        if ( $lang == 'en' ) {

            $talent = Talent::where( 'slug->en' , $talent )
                ->first();
        }
        else {
            $talent = Talent::where( 'slug->ar' , $talent )
                ->first();
        }
        if ($type ==  Gru::IN_THE_SPOT &&  isset($talent->platforms) && $talent->platforms->where( 'type' , Gru::IN_THE_SPOT )->where( 'is_published' , 1 )->count() > 0 ) {

             $type =   Gru::IN_THE_SPOT ;
              $items =  TalentPlatformLink::where('talent_id',$talent->id)->where( 'type' , Gru::IN_THE_SPOT )->where( 'is_published' , 1 )->paginate(10);


        }
        elseif ($type ==  Gru::FILMOGRAPHY &&   TalentFilmographyCast::where( 'talent_id' , $talent->id )->whereHas( 'filmography' , function ( $query ) {
                $query->where( 'is_published' , 1 );})->count() > 0 ) {
            $type =  Gru::FILMOGRAPHY ;
            $items =  TalentFilmographyCast::where( 'talent_id' , $talent->id )->whereHas( 'filmography' , function ( $query ) {
                $query->where( 'is_published' , 1 );})->paginate(30) ;


        }
        elseif ($type ==  Gru::IN_THE_MEDIA &&  isset($talent->platforms) && $talent->platforms->where( 'type' , Gru::IN_THE_MEDIA )->where( 'is_published' , 1 )->count() > 0 ) {
            $type = Gru::IN_THE_MEDIA ;
            $items = TalentPlatformLink::where('talent_id',$talent->id)->where( 'type' , Gru::IN_THE_MEDIA )->where( 'is_published' , 1 )->paginate(10);

        }
        elseif ($type ==  Gru::TALENT_SOCIAL &&  TalentSocialPost::whereHas ('talent_social.talent', function ($query) use ($talent) {
                $query->where ('talent_id', $talent->id);
            })->first () !== null ) {
            $talent_ig_social_posts = TalentSocialPost::whereHas( 'talent_social' , function ( $query ) use ( $talent ) {
                $query->where( 'social_media_id' , Gru::INSTAGRAM_SOCIAL_MEDIA_ID)->wherehas('talent', function ($query)  use($talent) {
                    $query->where('talent_id',$talent->id);
                });
            } )->orderBy('created_at','DESC')->paginate(3);

            $talent_youtube_social_posts = TalentSocialPost::whereHas( 'talent_social' , function ( $query ) use ( $talent ) {
                $query->where( 'social_media_id' , Gru::YOUTUBE_SOCIAL_MEDIA_ID)->wherehas('talent', function ($query)  use($talent) {
                    $query->where('talent_id',$talent->id);
                });
            } )->orderBy('created_at','DESC')->paginate(3);

            $talent_tiktok_social_posts = TalentSocialPost::whereHas( 'talent_social' , function ( $query ) use ( $talent ) {
                $query->where( 'social_media_id' , Gru::TIKTOK_SOCIAL_MEDIA_ID)->wherehas('talent', function ($query)  use($talent) {
                    $query->where('talent_id',$talent->id);
                });
            } )->orderBy('created_at','DESC')->with('talent_social')->paginate(3);
             $type = Gru::TALENT_SOCIAL ;
             $items = [$talent_ig_social_posts  , $talent_tiktok_social_posts, $talent_youtube_social_posts] ;
        }
        elseif ($type ==  Gru::TALENT_VIDEOS && TalentVideo::where ('is_published', 1)->where ('talent_id', $talent->id)->orderby ('created_at', 'DESC')->whereHas ('talent_order.order_response.order_request', function ($query) {
                $query->where ('is_public', 1);
            })->with ('video')->first () !== null) {
            $type = Gru::TALENT_VIDEOS ;
            $items = TalentVideo::where( 'is_published' , 1 )->where( 'talent_id' , $talent->id )->orderby( 'created_at' , 'DESC' )->whereHas( 'talent_order.order_response.order_request' , function ( $query ) {
                $query->where( 'is_public' , 1 );
            } )->with('video')->paginate(6);

        }
        elseif ($type ==  Gru::INSIGHTS && TalentSocialInfo::whereHas ('talent_social.talent', function ($query) use ($talent) {$query->where ('talent_id', $talent->id);})->first () !== null)  {

            if($talent->talent_social->where('social_media_id',Gru::INSTAGRAM_SOCIAL_MEDIA_ID)->count() > 0  && isset($talent->talent_social->where('social_media_id',Gru::INSTAGRAM_SOCIAL_MEDIA_ID)->first()->talent_social_info)){
                $social_nav_items[] = Gru::INSTAGRAM_SOCIAL_MEDIA_ID;
            }
            if($talent->talent_social->where('social_media_id',Gru::YOUTUBE_SOCIAL_MEDIA_ID)->count() > 0 && isset($talent->talent_social->where('social_media_id',Gru::YOUTUBE_SOCIAL_MEDIA_ID)->first()->talent_social_info) &&  $talent->talent_social->where('social_media_id',Gru::YOUTUBE_SOCIAL_MEDIA_ID)->first()->talent_social_info->user_profile != null) {
                $social_nav_items[] = Gru::YOUTUBE_SOCIAL_MEDIA_ID;
            }

            if($talent->talent_social->where('social_media_id',Gru::TIKTOK_SOCIAL_MEDIA_ID)->count() > 0 && isset($talent->talent_social->where('social_media_id',Gru::TIKTOK_SOCIAL_MEDIA_ID)->first()->talent_social_info) && $talent->talent_social->where('social_media_id',Gru::TIKTOK_SOCIAL_MEDIA_ID)->first()->talent_social_info->user_profile != null) {
                $social_nav_items[] = Gru::TIKTOK_SOCIAL_MEDIA_ID;
            }

              $talent_social_info = TalentSocialInfo::whereHas( 'talent_social.talent' , function ( $query ) use (
                $talent ) {
                $query->where( 'talent_id' , $talent->id );
            } )->where('user_profile','<>',null)->whereHas('talent_social.social' , function($query) {
                $query->where('social_media_id' , Gru::INSTAGRAM_SOCIAL_MEDIA_ID);
            })->with('talent_social')->first();

            $first_item = Gru::INSTAGRAM_SOCIAL_MEDIA_ID;

            if( !isset( $talent_social_info)) {
                $talent_social_info = TalentSocialInfo::whereHas( 'talent_social.talent' , function ( $query ) use (
                    $talent ) {
                    $query->where( 'talent_id' , $talent->id );
                } )->where('user_profile','<>',null)->whereHas('talent_social.social' , function($query) {
                    $query->where('social_media_id' , Gru::YOUTUBE_SOCIAL_MEDIA_ID);
                })->with('talent_social')->first();
                $first_item = Gru::YOUTUBE_SOCIAL_MEDIA_ID;

            }elseif (!isset( $talent_social_info)){
                $talent_social_info = TalentSocialInfo::whereHas( 'talent_social.talent' , function ( $query ) use (
                    $talent ) {
                    $query->where( 'talent_id' , $talent->id );
                } )->where('user_profile','<>',null)->whereHas('talent_social.social' , function($query) {
                    $query->where('social_media_id' , Gru::TIKTOK_SOCIAL_MEDIA_ID);
                })->with('talent_social')->first();

                $first_item = Gru::TIKTOK_SOCIAL_MEDIA_ID;
            }

            $first_insight_items = $talent_social_info->first()->talent_social;

            [
                $followers_stat ,
                $following_stat ,
                $likes_stat ,
                $views_stat
            ] = TalentSocialHelper::socialUserProfileCharts( $first_insight_items );


            [
                $languages_stat ,
                $ethnicity_stat ,
                $country_stat ,
                $city_stat ,
                $male_stat_age ,
                $female_stat_age ,
                $reachability_stat ,
                $gender_stat
            ] = TalentSocialHelper::socialAudienceFollowersCharts( $first_insight_items );
            [
                $languages_aud_stat ,
                $ethnicity_aud_stat ,
                $country_aud_stat ,
                $city_aud_stat ,
                $male_aud_stat_age ,
                $female_aud_stat_age ,
                $reachability_aud_stat ,
                $gender_aud_stat
            ] = TalentSocialHelper::socialAudienceLikersCharts( $first_insight_items );
            [
                $gender_comment_stat ,
                $languages_comment_stat ,
                $female_comment_stat_age ,
                $male_comment_stat_age
            ] = TalentSocialHelper::socialAudienceCommentersCharts( $first_insight_items );

            $type = Gru::INSIGHTS ;
            $items = $talent_social_info ;
        }
        elseif ($type ==  Gru::COLLABS &&  isset( $talent->collaborations ) && $talent->collaborations->count() > 0 ) {
            $type = Gru::COLLABS ;
            $items = $talent->collaborations ;
        }



        return response ()->json ([

            'data' => [
                'type'             => $type,
                'social_nav_items' => $social_nav_items,
                'first_item'       => $first_item,
                'items'            => $items,
                'charts' => [
                    'followers_stat'          => $followers_stat,
                    'following_stat'          => $following_stat,
                    'likes_stat'              => $likes_stat,
                    'views_stat'              => $views_stat,
                    'languages_stat'          => $languages_stat,
                    'ethnicity_stat'          => $ethnicity_stat,
                    'country_stat'            => $country_stat,
                    'city_stat'               => $city_stat,
                    'male_stat_age'           => $male_stat_age,
                    'female_stat_age'         => $female_stat_age,
                    'reachability_stat'       => $reachability_stat,
                    'gender_stat'             => $gender_stat,
                    'languages_aud_stat'      => $languages_aud_stat,
                    'ethnicity_aud_stat'      => $ethnicity_aud_stat,
                    'country_aud_stat'        => $country_aud_stat,
                    'city_aud_stat'           => $city_aud_stat,
                    'male_aud_stat_age'       => $male_aud_stat_age,
                    'female_aud_stat_age'     => $female_aud_stat_age,
                    'reachability_aud_stat'   => $reachability_aud_stat,
                    'gender_aud_stat'         => $gender_aud_stat,
                    'gender_comment_stat'     => $gender_comment_stat,
                    'languages_comment_stat'  => $languages_comment_stat,
                    'female_comment_stat_age' => $female_comment_stat_age,
                    'male_comment_stat_age'   => $male_comment_stat_age,
                ]
            ]
        ]);

    }

    public function getInsightData ( $lang , $talent , $type ){
        $talent = Talent::find($talent);

        if( $type == Gru::INSTAGRAM_SOCIAL_MEDIA_ID){
            $talent_social_info = TalentSocialInfo::whereHas( 'talent_social.talent' , function ( $query ) use (
                $talent ) {
                $query->where( 'talent_id' , $talent->id );
            } )->where('user_profile','<>',null)->whereHas('talent_social.social' , function($query) {
                $query->where('social_media_id' , Gru::INSTAGRAM_SOCIAL_MEDIA_ID);
            })->with('talent_social')->first();
        }elseif( $type == Gru::TIKTOK_SOCIAL_MEDIA_ID){

            $talent_social_info = TalentSocialInfo::whereHas( 'talent_social.talent' , function ( $query ) use (
                $talent ) {
                $query->where( 'talent_id' , $talent->id );
            } )->where('user_profile','<>',null)->whereHas('talent_social.social' , function($query) {
                $query->where('social_media_id' , Gru::TIKTOK_SOCIAL_MEDIA_ID);
            })->with('talent_social')->first();


        }elseif( $type == Gru::YOUTUBE_SOCIAL_MEDIA_ID) {
            $talent_social_info = TalentSocialInfo::whereHas( 'talent_social.talent' , function ( $query ) use (
                $talent ) {
                $query->where( 'talent_id' , $talent->id );
            } )->where('user_profile','<>',null)->whereHas('talent_social.social' , function($query) {
                $query->where('social_media_id' , Gru::YOUTUBE_SOCIAL_MEDIA_ID);
            })->with('talent_social')->first();


        }

        $talent_social = TalentSocial::where('talent_id',$talent->id)->where('social_media_id' , $type)->first();

        $items = $talent_social_info ;

        [
            $followers_stat ,
            $following_stat ,
            $likes_stat ,
            $views_stat
        ] = TalentSocialHelper::socialUserProfileCharts( $talent_social );
        [
            $languages_stat ,
            $ethnicity_stat ,
            $country_stat ,
            $city_stat ,
            $male_stat_age ,
            $female_stat_age ,
            $reachability_stat ,
            $gender_stat
        ] = TalentSocialHelper::socialAudienceFollowersCharts( $talent_social );
        [
            $languages_aud_stat ,
            $ethnicity_aud_stat ,
            $country_aud_stat ,
            $city_aud_stat ,
            $male_aud_stat_age ,
            $female_aud_stat_age ,
            $reachability_aud_stat ,
            $gender_aud_stat
        ] = TalentSocialHelper::socialAudienceLikersCharts( $talent_social );
        [
            $gender_comment_stat ,
            $languages_comment_stat ,
            $female_comment_stat_age ,
            $male_comment_stat_age
        ] = TalentSocialHelper::socialAudienceCommentersCharts( $talent_social );

        return response ()->json ([

            'data' => [
                'type'             => $type,
                'items'            => $items,
                'charts' => [
                    'followers_stat'          => $followers_stat,
                    'following_stat'          => $following_stat,
                    'likes_stat'              => $likes_stat,
                    'views_stat'              => $views_stat,
                    'languages_stat'          => $languages_stat,
                    'ethnicity_stat'          => $ethnicity_stat,
                    'country_stat'            => $country_stat,
                    'city_stat'               => $city_stat,
                    'male_stat_age'           => $male_stat_age,
                    'female_stat_age'         => $female_stat_age,
                    'reachability_stat'       => $reachability_stat,
                    'gender_stat'             => $gender_stat,
                    'languages_aud_stat'      => $languages_aud_stat,
                    'ethnicity_aud_stat'      => $ethnicity_aud_stat,
                    'country_aud_stat'        => $country_aud_stat,
                    'city_aud_stat'           => $city_aud_stat,
                    'male_aud_stat_age'       => $male_aud_stat_age,
                    'female_aud_stat_age'     => $female_aud_stat_age,
                    'reachability_aud_stat'   => $reachability_aud_stat,
                    'gender_aud_stat'         => $gender_aud_stat,
                    'gender_comment_stat'     => $gender_comment_stat,
                    'languages_comment_stat'  => $languages_comment_stat,
                    'female_comment_stat_age' => $female_comment_stat_age,
                    'male_comment_stat_age'   => $male_comment_stat_age,
                ]
            ]
        ]);
    }

    public function search( Request $request){

        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $search = strtolower($request->get('search'));
        $talents = [] ;
        if ( $status == Gru::AGENCY_SUBDOMAIN ) {
            $talents = Talent::whereHas( 'agency' , function ( $q ) use ( $agency ) {
                return $q->where( 'agencies.id' , $agency->id );
            } )
                ->where('id', '<>' , [1,71])
                ->where('is_published',1)
                ->whereHas('user',function($query) use ($search){
                    $query->whereRaw('LOWER(name->"$.en") LIKE ?', '%' . $search . '%')
                        ->orWhereRaw('LOWER(name->"$.ar") LIKE ?', '%' . $search . '%');

                })->get();
        }
        elseif ( $status == Gru::MAIN_DOMIAN ) {
            $talents = Talent::doesntHave( 'agency' )
                ->where('id', '<>' , [1,71])
                ->where('is_published',1)
                ->whereHas('user',function($query) use ($search){
                    $query->whereRaw('LOWER(name->"$.en") LIKE ?', '%' . $search . '%')
                        ->orWhereRaw('LOWER(name->"$.ar") LIKE ?', '%' . $search . '%');

                })->get();
        }

        return [
            'data' => [
                'talents'                  =>  $talents,
            ]
        ];
    }

    public function verifyIdentity ($talent , Request $request){

        TalentVerifyContact::create(['talent_id' => $talent , 'email' => $request->get('email') , 'number' => $request->get('phone_number')]);

        $talent = Talent::find($request->get('talent_id'));
//        Notification::route ('mail','mohamed@thewickfirm.com')->notify (new TalentVerifyIdentityNotification($talent));
//        Notification::route ('mail','effat.ammar@omneeyat.com')->notify (new TalentVerifyIdentityNotification($talent));
//        Notification::route ('mail','hello@omneeyat.com')->notify (new TalentVerifyIdentityNotification($talent));

        return [
            'data' => [
                'status'                  =>  1,
            ]
        ];
    }

    public function getLatestArticles () {
        return TalentArticle::whereRaw ('id IN (select MAX(id) FROM talent_articles GROUP BY talent_id)')->where ('is_published', '=', 1)->orderBy ('created_at', 'DESC')->where ('talent_id', '!=', 1)->whereHas ('talent', function ($query) {
            $query->where ('is_published', 1)->where ('is_available', 1);
        })->with ('talent')->get ()->take (20);
    }
}
