<?php

namespace App\Http\Controllers\Talent;

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


class TalentArticleController extends Controller {


    public function index($lang ,$talent , $title , Request $request){

        [$status, $agency, $url] = TalentHelper::checkSubdomain ($request);
        $_agency = (bool)$agency;

        if ( $status == Gru::MAIN_DOMIAN || $status == Gru::AGENCY_SUBDOMAIN ) {
            $page_a = TalentHelper::getPage( Gru::ARTICLE_PAGE_ID , $agency );

            $talent = TalentHelper::getTalent( $talent , 1,$lang , $agency );

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
                    return ApiHelper::notFoundError ();
                }
            }
            else {
                [$status, $url ] = TalentHelper::redirectToCorrectURL($talent, false ,$title);

                if( $status == true ){
                    redirect()->to( $url )->send();
                }else{
                     return ApiHelper::notFoundError ();
                }

            }
        }




    }

    public function search(Request $request){

        if ( $request->get( 'search' ) != null ) {

            $search =  $request->get( 'search' );


            $talents = Talent::where( 'is_published' , 1 )
                ->where( 'id' , '<>' , '1' )
                ->where( 'id' , '<>' , 71 )
                ->doesntHave( 'agency' )
                ->wherehas ('user', function ($query) use ($search) {
                    $query->where('name->en','like', "%{$search}%")
                        ->orWhere('name->ar','like' , "%{$search}%");
                })  ->paginate(100);

            return $talents;


        }

    }


    public function getMoreArticles($lang ,$talent , Request $request){

        if ( $lang == 'en' ) {

            $talent = Talent::where( 'slug->en' , $talent )
                ->where( 'is_published' , 1 )
                ->doesntHave( 'agency' )
                ->with('talent_social.talent_social_info','categories', 'charities')
                ->first();
        }
        else {
            $talent = Talent::where( 'slug->ar' , $talent )
                ->where( 'is_published' , 1 )
                ->doesntHave( 'agency' )
                ->with('talent_social.talent_social_info' , 'categories', 'charities')
                ->first();
        }


        $articles = Article::whereHas( 'talents' , function ( $query ) use ( $talent ) {
            $query->where( 'talent_id' , $talent->id);
        } )->where( 'is_published' , 1 )->orderby( 'created_at' , 'DESC' )->paginate(6);


        return $articles;

    }
}
