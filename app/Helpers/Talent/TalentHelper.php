<?php

namespace App\Helpers\Talent;

use App\Helpers\Gru;
use App\Models\Agency\Agency;
use App\Models\Agency\AgencyDomain;
use App\Models\Agency\AgencyPage;
use App\Models\Article\Article;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentDomains;
use App\Models\Talent\TalentFeatured;
use App\Models\Talent\TalentFilmographyCast;
use App\Models\Talent\TalentPackage;
use App\Models\Talent\TalentPackageRequest;
use App\Models\Talent\TalentPackageResponse;
use App\Models\Talent\TalentPlatformLink;
use App\Models\Talent\TalentSocialInfo;
use App\Models\Talent\TalentSocialPost;
use App\Models\Talent\TalentVideo;
use App\Models\User\PasswordReset;
use App\Models\User\User;
use App\Models\User\UserFollow;
use App\Notifications\MailNewTalentNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TalentHelper {
    public static function actionFollow( Talent $talent , $user ) {
        $follow_exist = UserFollow::where( 'user_id' , $user->id )->where( 'talent_id' , $talent->id )->first();

        if ( $follow_exist ) {
            $follow_exist->delete();

            return false;
        }
        else {
            $user_follow            = new UserFollow();
            $user_follow->talent_id = $talent->id;
            $user_follow->user_id   = $user->id;
            $user_follow->save();

            return true;
        }
    }

    public static function checkFollowing( $talent ) {
        if ( Auth::check() ) {
            Auth::user()->is_following( $talent->id );
        }
    }

    public static function customNumberFormat( $number ) {
        if ( $number >= 1E9 ) {
            return round( $number / 1E9 , 0 ) . 'B';
        }
        elseif ( $number >= 1E6 ) {
            return round( $number / 1E6 , 0 ) . 'M';
        }
        elseif ( $number >= 1E3 ) {
            return round( $number / 1E3 , 0 ) . 'K';
        }
        return $number;
    }

    public static function checkSubdomain( $request ) {
        $url  = $request->get('host');
        $host = explode( '.' , $url );
        if ( count( $host ) == 3 ) {
            $agency_domain = AgencyDomain::where( 'domain_name' , $url )->first();
            if ( isset( $agency_domain ) ) {
                $agency = Agency::find( $agency_domain->agency_id );
                $url    = env( 'HTTPS_REQUEST' ) . $url;
                return [ Gru::AGENCY_SUBDOMAIN , $agency , $url ];//agency subdomain
            }
            elseif ( str_replace( 'https://' , '' , config( 'eeme.website_portal_url' ) ) ) {
                $url = env( 'HTTPS_REQUEST' ) . $url;
                return [ Gru::MAIN_DOMIAN , null , $url ];
            }
            elseif ( isset( $host[1] ) && $host[1] == 'vercel' && isset( $host[2] ) && $host[2] == 'app' ) {
                $url = env( 'HTTPS_REQUEST' ) . $url;
                return [ Gru::MAIN_DOMIAN , null , $url ]; // vercel staging domain
            }
            else {
                return [ Gru::NOT_DEFINED_DOMAIN , null , null ]; //no agency for the requested url
            }
        }
        elseif ( count( $host ) == 2 && $host[ 0 ] == 'eeme' ||  $host[ 0 ] == 'staging' ||  $host[ 0 ] == 'localhost:3011'||  $host[ 0 ] == 'localhost:3000') {
            $url = env( 'HTTPS_REQUEST' ) . $url;
            return [ Gru::MAIN_DOMIAN , null , $url ]; //main domain
        }
    }

    public static function getTalents( $count , $page, $is_home = false ,  $skip , $agency = null) {

        if ( isset( $agency ) ) {
            $talents = Talent::where( 'id' , '<>' , 1 )->where('is_verified',1)->where( 'id' , '<>' , 71 )->where( 'is_available' , 1 )->whereHas( 'agency' , function ( $q ) use ( $agency ) {
                return $q->where( 'agencies.id' , $agency->id );
            } )->with('categories' )->inRandomOrder()->paginate( $count );
        }
        else {

            if( $page->agency_id == null && $is_home == true && $page->page_id == Gru::HOME_PAGE_ID &&
                $page->page->is_published_talents_control == true ){

                $talents = Talent::wherehas('featured')
                    ->where('is_verified',1)
                    ->where('is_available',1)
                    ->where('is_published',1)
                    ->with('categories' )
                    ->wherehas('talentLatestArticle.article')
                    ->with('talentLatestArticle.article')
                    ->get()
                    ->skip($skip)
                    ->take( $count );
            }else{
                $talents = Talent::where( 'is_available' , 1 )
                    ->where( 'is_published' , 1 )
                    ->where('is_verified',1)
                    ->where( 'id' , '!=' , 1 )
                    ->where( 'id' , '!=' , 71 )
                    ->where( 'is_available' , 1 )
                    ->doesntHave( 'agency' )
                    ->with('categories' )
                    ->wherehas('talentLatestArticle.article')
                    ->with('talentLatestArticle.article')
                    ->get()
                    ->skip($skip)
                    ->take( $count );

            }
        }

        return $talents;
    }

    public static function getTalentsCount( $agency = null ) {
        if ( isset( $agency ) ) {
            $count = Talent::whereHas( 'agency' , function ( $q ) use ( $agency ) {
                return $q->where( 'agencies.id' , $agency->id );
            } )->get()->count();
        }
        else {
            $count = Talent::where( 'is_published' , '=' , 1 )->where( 'id' , '!=' , 1 )->where( 'id' , '!=' , 71 )->doesntHave( 'agency' )->get()->count();
        }

        return $count;
    }

    public static function getTalentsVideos( $agency = null ) {

        if ( isset( $agency ) ) {
            $talent_videos = TalentVideo::where( 'is_published' , 1 )->where( 'talent_id' , '!=' , 1 )->where( 'talent_id' , '!=' , 1 )->whereHas( 'talent' , function ( $query ) use ( $agency ) {
                return $query->whereHas( 'agency' , function ( $q ) use ( $agency ) {
                    return $q->where( 'agency_id' , $agency->id );
                } );
            } )->orderby( 'created_at' , 'DESC' )->whereHas( 'talent_order.order_response.order_request' , function ( $query ) {
                $query->where( 'is_public' , 1 );
            } )->take( 6 )->get();
        }
        else {
            $talent_videos = TalentVideo::where( 'is_published' , 1 )->where( 'talent_id' , '!=' , 1 )->orderby( 'created_at' , 'DESC' )->whereHas( 'talent' , function ( $query ) {
                return $query->doesntHave( 'agency' );
            } )->whereHas( 'talent_order.order_response.order_request' , function ( $query ) {
                $query->where( 'is_public' , 1 );
            } )->take( 6 )->get();
        }

        return $talent_videos;
    }

    public static function getArticles( $count , $agency = null ) {
        if ( isset( $agency ) ) {
            $articles = Article::orderBy( 'created_at' , 'DESC' )->where( 'is_published' , '=' , 1 )
                ->where( 'type' , 1 )
                ->whereHas( 'talents' , function ( $query ) use ( $agency ) {
                    return $query->whereHas( 'talent' , function ( $query ) use ( $agency ) {
                        $query->where( 'is_published' , 1 );
                        return $query->whereHas( 'agency' , function ( $q ) use ( $agency ) {
                            $q->where( 'is_published' , 1 );
                            return $q->where( 'agency_id' , $agency->id );
                        } );
                    } );
                } )->paginate( $count );
        }
        else {
            $articles = Article::orderBy( 'created_at' , 'DESC' )
                ->where( 'is_published' , '=' , 1 )
                ->where( 'type' , 1 )
//                ->whereHas( 'talents' , function ( $query ) {
//                return $query->whereHas( 'talent' , function ( $query ) {
//                    $query->where( 'is_published' , 1 );
//                    return $query->doesntHave( 'agency' );
//                } );
//            } )
                ->paginate( $count );
        }
        return $articles;
    }

    public static function getLatestArticles( $skip , $count , $agency = null ) {
        if ( isset( $agency ) ) {
            $articles = Article::orderBy( 'created_at' , 'DESC' )->where( 'is_published' , '=' , 1 )->where( 'type' , 1 )->whereHas( 'talents' , function ( $query ) use ( $agency ) {
                return $query->whereHas( 'talent' , function ( $query ) use ( $agency ) {
                    $query->where( 'is_published' , 1 );
                    return $query->whereHas( 'agency' , function ( $q ) use ( $agency ) {
                        return $q->where( 'agency_id' , $agency->id );
                    } );
                } );
            } )->skip( $skip )->take( $count )->get();
        }
        else {
            $articles = Article::orderBy( 'created_at' , 'DESC' )->where( 'is_published' , '=' , 1 )->where( 'type' , 1 )->whereHas( 'talents' , function ( $query ) {
                return $query->whereHas( 'talent' , function ( $query ) {
                    $query->where( 'is_published' , 1 );
                    return $query->doesntHave( 'agency' );
                } );
            } )->skip( 6 )->take( $count )->get();
        }
        return $articles;
    }

    public static function getAllTalents( $agency = null ) {
        if ( isset ( $agency ) ) {
            $talents = Talent::whereHas( 'agency' , function ( $q ) use ( $agency ) {
                return $q->where( 'agencies.id' , $agency->id );
            } )->inRandomOrder()->get();
        }
        else {
            $talents = Talent::where( 'is_published' , '=' , 1 )->where( 'id' , '!=' , 1 )->where( 'id' , '!=' , 71 )->doesntHave( 'agency' )->get();
        }
        return $talents;
    }

    public static function getTalent( $slug , $is_published , $lang , $agency = null ) {

        if ( isset( $agency ) ) {
            if ( $lang == 'en' ) {
                $talent = Talent::where( 'slug->en' , $slug )
                    ->where( 'is_published' , $is_published )
                    ->whereHas( 'agency' , function ( $query ) use ( $agency ) {
                        $query->where( 'agency_id' , $agency->id );
                    } )->with('categories','charities')->first();
            }
            else {
                $talent = Talent::where( 'slug->ar' , $slug )->where( 'is_published' , $is_published )->whereHas( 'agency' , function ( $query ) use ( $agency ) {
                    $query->where( 'agency_id' , $agency->id );
                } )->with('categories','charities')->first();
            }
        }
        else {

            if ( $lang == 'en' ) {

                $talent = Talent::where( 'slug->en' , $slug )->where( 'is_published' , $is_published )->doesntHave( 'agency' )->with('categories','charities')->first();
            }
            else {
                $talent = Talent::where( 'slug->ar' , $slug )->where( 'is_published' , $is_published )->doesntHave( 'agency' )->with('categories','charities')->first();
            }
        }
        return $talent;
    }

    public static function getTalentVideos( $talent , $count , $agency = null ) {

        if ( $count == 1 ) {
            if ( isset ( $agency ) ) {
                $talent_videos = TalentVideo::where( 'is_published' , 1 )->where( 'talent_id' , $talent->id )->orderby( 'created_at' , 'DESC' )->whereHas( 'talent' , function ( $query ) use ( $agency , $count ) {
                    return $query->whereHas( 'agency' , function ( $q ) use ( $agency , $count ) {
                        return $q->where( 'agency_id' , $agency->id );
                    } );
                } )->whereHas( 'talent_order.order_response.order_request' , function ( $query ) {
                    $query->where( 'is_public' , 1 );
                } )->with('video')->first();
            }
            else {
                $talent_videos = TalentVideo::where( 'is_published' , 1 )->where( 'talent_id' , $talent->id )->orderby( 'created_at' , 'DESC' )->whereHas( 'talent_order.order_response.order_request' , function ( $query ) {
                    $query->where( 'is_public' , 1 );
                } )->with('video')->first();
            }
        }
        else {
            if ( isset ( $agency ) ) {
                $talent_videos = TalentVideo::where( 'is_published' , 1 )->where( 'talent_id' , $talent->id )->orderby( 'created_at' , 'DESC' )->whereHas( 'talent' , function ( $query ) use ( $agency , $count ) {
                    return $query->whereHas( 'agency' , function ( $q ) use ( $agency , $count ) {
                        return $q->where( 'agency_id' , $agency->id );
                    } );
                } )->whereHas( 'talent_order.order_response.order_request' , function ( $query ) {
                    $query->where( 'is_public' , 1 );
                } )->take( $count )->with('video')->get();
            }
            else {
                $talent_videos = TalentVideo::where( 'is_published' , 1 )->where( 'talent_id' , $talent->id )->orderby( 'created_at' , 'DESC' )->whereHas( 'talent_order.order_response.order_request' , function ( $query ) {
                    $query->where( 'is_public' , 1 );
                } )->take( $count )->with('video')->get();
            }
        }

        return $talent_videos;
    }

    public static function checkTalentPackage( $talent ) {

        $talent_package = $talent->talent_package->where( 'is_active' , 1 )->first();
        if ( $talent_package ) {
            $private_domain = ! $talent->talent_package->where( 'is_active' , 1 )->first()->package->services->where( 'id' , Gru::PRIVATE_DOMAIN_NAME )->isEmpty();

            if ( $private_domain ) {
                $private_domain = ! TalentDomains::where( 'talent_id' , $talent->id )->get()->isEmpty();
            }
        }
        else {
            $private_domain = false;
        }

        return $private_domain;
    }

    public static function getExternalDomain( $talent , $book , $payment ) {
        if ( $book == true && $payment == false ) {
            $on_external_domain      = false;
            $on_external_domain_name = null;
            $talent_domain           = null;
            if ( $talent == null ) {
                $url                     = parse_url( \URL::current() )[ 'host' ];
                $url                     = str_replace( 'www.' , '' , $url );
                $talent_domain           = TalentDomains::where( 'domain_name' , $url )->first()->talent->slug;
                $on_external_domain      = true;
                $on_external_domain_name = 'talent/' . $talent_domain . '/book';
            }
        }
        elseif ( $book == false && $payment == false ) {
            $on_external_domain      = false;
            $on_external_domain_name = null;
            $talent_domain           = null;

            if ( $talent == null ) {
                $url                     = parse_url( \URL::current() )[ 'host' ];
                $url                     = str_replace( 'www.' , '' , $url );
                $talent_domain           = TalentDomains::where( 'domain_name' , $url )->first()->talent->slug;
                $on_external_domain      = true;
                $on_external_domain_name = 'talent/' . $talent_domain;
            }
        }
        elseif ( $book == false && $payment == true ) {
            $url           = parse_url( \URL::current() )[ 'host' ];
            $url           = str_replace( 'www.' , '' , $url );
            $talent_domain = TalentDomains::where( 'domain_name' , $url )->first();

            $on_external_domain      = false;
            $on_external_domain_name = null;
        }

        return [ $on_external_domain , $on_external_domain_name , $talent_domain ];
    }

    public static function getTalentArticles( $talent , $agency = null ) {
        if ( isset( $agency ) ) {
            $articles = Article::whereHas( 'talents' , function ( $query ) use ( $talent ) {
                $query->where( 'talent_id' , $talent->id );
            } )->where( 'is_published' , 1 )->orderby( 'created_at' , 'DESC' )->where( 'type' , 1 )->whereHas( 'talents' , function ( $query ) use ( $agency ) {
                return $query->whereHas( 'talent' , function ( $query ) use ( $agency ) {
                    return $query->whereHas( 'agency' , function ( $q ) use ( $agency ) {
                        return $q->where( 'agency_id' , $agency->id );
                    } );
                } );
            } )->paginate(6);
        }
        else {
            $articles = Article::whereHas( 'talents' , function ( $query ) use ( $talent ) {
                $query->where( 'talent_id' , $talent->id );
            } )->where( 'is_published' , 1 )->orderby( 'created_at' , 'DESC' )->paginate(6);
        }
        return $articles;
    }

    public static function getLatestArticle( $talent , $agency = null ) {
        if ( isset( $agency ) ) {
            $articles = Article::whereHas( 'talents' , function ( $query ) use ( $talent ) {
                $query->where( 'talent_id' , $talent->id );
            } )->where( 'is_published' , 1 )->orderby( 'created_at' , 'DESC' )->where( 'type' , 1 )->whereHas( 'talents' , function ( $query ) use ( $agency ) {
                return $query->whereHas( 'talent' , function ( $query ) use ( $agency ) {
                    return $query->whereHas( 'agency' , function ( $q ) use ( $agency ) {
                        return $q->where( 'agency_id' , $agency->id );
                    } );
                } );
            } )->first();
        }
        else {
            $articles = Article::whereHas( 'talents' , function ( $query ) use ( $talent ) {
                $query->where( 'talent_id' , $talent->id );
            } )->where( 'is_published' , 1 )->orderby( 'created_at' , 'DESC' )->first();
        }
        return $articles;
    }

    public static function getTalentDetails( $talent , $agency = null ) {



        $slug = $talent;


        $talent_social_posts = TalentSocialPost::whereHas( 'talent_social.talent' , function ( $query ) use ( $talent ) {
            $query->where( 'talent_id' , $talent->id );
        } )->first();

        $talent_social_info = TalentSocialInfo::whereHas( 'talent_social.talent' , function ( $query ) use (
            $talent
        ) {
            $query->where( 'talent_id' , $talent->id );
        } )->first();
        return [ $slug , $talent_social_posts  , $talent_social_info ];
    }

    public static function getFirstInthespotItem( $talent  , $agency = null ) {

        if (isset($talent->platforms) && $talent->platforms->where( 'type' , Gru::IN_THE_SPOT )->where( 'is_published' , 1 )->count() > 0 ) {
            return [
                Gru::IN_THE_SPOT ,
                TalentPlatformLink::where('talent_id',$talent->id)->where( 'type' , Gru::IN_THE_SPOT )->where( 'is_published' , 1 )->paginate(10)

            ];
        }
        elseif ( TalentFilmographyCast::where( 'talent_id' , $talent->id )->whereHas( 'filmography' , function ( $query ) {
                $query->where( 'is_published' , 1 );})->count() > 0 ) {

            return [ Gru::FILMOGRAPHY ,TalentFilmographyCast::where( 'talent_id' , $talent->id )->whereHas( 'filmography' , function ( $query ) {
                $query->where( 'is_published' , 1 );})->paginate(30) ];
        }
        elseif ( isset($talent->platforms) && $talent->platforms->where( 'type' , Gru::IN_THE_MEDIA )->where( 'is_published' , 1 )->count() > 0 ) {
            return [
                Gru::IN_THE_MEDIA ,
                TalentPlatformLink::where('talent_id',$talent->id)->where( 'type' , Gru::IN_THE_MEDIA )->where( 'is_published' , 1 )->paginate(10)
            ];
        }
        elseif (TalentSocialPost::whereHas ('talent_social.talent', function ($query) use ($talent) {
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

            $items = [$talent_ig_social_posts  , $talent_tiktok_social_posts, $talent_youtube_social_posts] ;
            return [ Gru::TALENT_SOCIAL , $items ];
        }
        elseif (TalentVideo::where ('is_published', 1)->where ('talent_id', $talent->id)->orderby ('created_at', 'DESC')->whereHas ('talent_order.order_response.order_request', function ($query) {
                $query->where ('is_public', 1);
            })->with ('video')->first () !== null) {
            $talent_videos = self::getTalentVideos( $talent , 1 , $agency );
            return [ Gru::TALENT_VIDEOS , $talent_videos ];
        }
        elseif (TalentSocialInfo::whereHas ('talent_social.talent', function ($query) use ($talent) {$query->where ('talent_id', $talent->id);})->first () !== null)  {
            $talent_social_info = TalentSocialInfo::whereHas( 'talent_social.talent' , function ( $query ) use (
                $talent
            ) {
                $query->where( 'talent_id' , $talent->id );
            } )->where( 'user_profile' , '<>' , null )->first();
            return [ Gru::INSIGHTS , $talent_social_info ];
        }
        elseif ( isset( $talent->collaborations ) && $talent->collaborations->count() > 0 ) {
            return [ Gru::COLLABS , $talent->collaborations ];
        }
        else {
            return [ 0 , null ];
        }
    }

    public static function getMetaDetails( $article , $lang , $title_count , $description_count ) {

        $description = str_replace( '&nbsp;' , ' ' , strip_tags( $article->getTranslation( 'description' , $lang ) ) );
        $description = Str::substr( $description , '0' , $description_count );

        $title_article = str_replace( '&nbsp;' , ' ' , strip_tags( $article->getTranslation( 'title' , $lang ) ) );
        $title_article = Str::substr( $title_article , '0' , $title_count );

        return [ $description , $title_article ];
    }

    public static function getDateArticle( $article ) {
        $lang = App::getLocale();
        if ( $lang == 'en' ) {
            $date = $article->created_at->format( 'F j, Y' );
        }
        else {
            $date = Carbon::parse( $article->created_at )->locale( $lang );

            $date = $date->isoFormat( 'MMMM DD, Y' );
        }
        return $date;
    }

    public static function getMainArticle( $talent , $title ,$lang ) {

        if ( $lang == 'en' ) {
            $latest_article = Article::where( 'is_published' , 1 )->where( 'slug->en' , $title )
                ->whereHas( 'talents' , function ( $query ) use ( $talent ) {
                    $query->where( 'talent_id' , $talent->id );
                } )->first();
        }
        elseif ( $lang == 'ar' ) {
            $latest_article = Article::where( 'is_published' , 1 )->where( 'slug->ar' , $title )->whereHas( 'talents' , function ( $query ) use ( $talent ) {
                $query->where( 'talent_id' , $talent->id );
            } )->first();
        }
        return $latest_article;
    }

    public static function getPage( $page_id , $agency ) {

        if ( isset ( $agency ) ) {
            $page = AgencyPage::where( 'page_id' , $page_id )->where( 'agency_id' , $agency->id )->first();
        }
        else {
            $page = AgencyPage::where( 'page_id' , $page_id )->where( 'agency_id' , null )->first();
        }
        return $page;
    }

    public static function redirectToCorrectURL( $slug , $is_book , $title = null ) {

        if ( isset( $title ) ) {
            if ( App::getLocale() == 'en' ) {
                $talent = Talent::where( 'slug->en' , $slug )->where( 'is_published' , 1 )->first();
            }
            else {
                $talent = Talent::where( 'slug->ar' , $title )->where( 'is_published' , 1 )->first();
            }
            if ( isset( $talent ) ) {
                if ( isset( $talent->agency ) && $talent->agency->first() ) {
                    $domain = $talent->agency->first()->domain;
                    if ( isset( $domain ) ) {
                        $domain = $domain->domain_name;
                        return [true , env( 'HTTPS_REQUEST' ) . $domain . '/' . App::getLocale() . '/talent/' . $slug . '/' . $title];
                    }
                    else {
                        return [false , null];
                    }
                }
                else {
                    return [true , config( 'omneeyat.website_portal_url' ) . '/' . App::getLocale() . '/talent/' . $slug . '/' . $title ];
                }
            }
        }
        else {

            if ( App::getLocale() == 'en' ) {
                $talent = Talent::where( 'slug->en' , $slug )->where( 'is_published' , 1 )->first();
            }
            else {
                $talent = Talent::where( 'slug->ar' , $slug )->where( 'is_published' , 1 )->first();
            }

            if(isset($talent)){
                if ( isset( $talent->agency ) && $talent->agency->first() ) {
                    $domain = $talent->agency->first()->domain;
                    if ( isset( $domain ) ) {
                        $domain = $domain->domain_name;
                        if ( isset( $domain ) ) {
                            if($is_book == true ){
                                return [ true , env( 'HTTPS_REQUEST' ) . $domain . '/' . App::getLocale() . '/talent/' .
                                         $slug .'/book'];

                            }else{
                                return [ true , env( 'HTTPS_REQUEST' ) . $domain . '/' . App::getLocale() . '/talent/' .
                                         $slug ];
                            }
                        }
                        else {
                            return [ false , null ];
                        }
                    }
                    else {
                        return [ false , null ];
                    }
                }
                else {
                    if($is_book == true ){
                        return [ true , config( 'omneeyat.website_portal_url' ) . '/' . App::getLocale() . '/talent/' .
                                 $slug .'/book'];

                    }else{
                        return [ true , config( 'omneeyat.website_portal_url' ) . '/' . App::getLocale() . '/talent/' . $slug ];
                    }
                }
            }else{
                return [ false , null ];
            }

        }
    }

    public static function registerTalent($public_enrollment_talent,$package, $json , $payment_status){
        $user = User::where ('email', $public_enrollment_talent->email)->first ();
        if (!$user) {
            $user = User::create ([
                'name'     => $public_enrollment_talent->name, 'email' => $public_enrollment_talent->email,
                'password' => '0Mn##Y@tp@$sW0Rd'
            ]);
        }
        $talent = Talent::where ('user_id', $user->id)->first ();

        if (!$talent) {
            $talent = new Talent();
            $talent->user_id = $user->id;
            $talent->save();

            $user->assignRole( 'talent' );
        }

        $talent_package = TalentPackage::create( [
            'talent_id'  => $talent->id ,
            'package_id' => $package ,
            'is_active'  => 0
        ] );

        $payment_request = TalentPackageRequest::create( [
            'talent_package_id' => $talent_package->id ,
            'type'              => 0 ,
            'expired_at'        =>  Carbon::now ()->addMonths (1)->toDateTimeString (),
            'payment_method'    => Gru::PAYMENT_METHOD_CC
        ] );

        $payment_response = TalentPackageResponse::create( [
            'talent_package_request_id' => $payment_request->id ,
            'payment_status'            => $payment_status ,
            'response'                  => $json
        ] );

        self::passwordResetEmail ($user);

        return $talent_package;

    }


    public static function passwordResetEmail($user){
        $token = Str::random( 60 );

        $old_password_resets = PasswordReset::where( 'email' , $user->email )->get();
        foreach ( $old_password_resets as $old_password_reset ) {
            $old_password_reset->delete();
        }

        $password_reset             = new PasswordReset();
        $password_reset->email      = $user->email;
        $password_reset->token      = Hash::make( $token );
        $password_reset->created_at = Carbon::now();
        $password_reset->save();

        $user->notify( new MailNewTalentNotification( $user , $token ) );
    }





}
