<?php

namespace App\Http\Controllers\Category;

use App\Helpers\ApiHelper;
use App\Helpers\Gru;
use App\Helpers\Minion;
use App\Helpers\Talent\TalentHelper;
use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Misc\Category;
use App\Models\Page;
use App\Models\Talent\Talent;
use Illuminate\Http\Request;
use Whoops\Util\Misc;

class CategoryController extends Controller {

    public function index(Request  $request){
        $_parent_categories = Category::where( 'parent_id' , null )->where('page_id','!=',null)->get();
        return response ()->json ([

            'data' => [
              'categories' => $_parent_categories
            ]

        ]);

    }
    public function show ($category, Request $request) {

        [$status, $agency, $url] = TalentHelper::checkSubdomain ($request);

        $_agency = (bool)$agency;

        $category = Category::where ('slug', $category)->first ();

        if($category){

            $page_a = TalentHelper::getPage ($category->page_id, $agency);

            if ($status == Gru::AGENCY_SUBDOMAIN) {
                $talents = Talent::whereHas ('categories', function ($query) use ($category) {
                    $query->where ('parent_id', '=', $category->id);
                })->whereHas ('agency', function ($q) use ($agency) {
                    return $q->where ('agencies.id', $agency->id);
                })->where ('is_published', 1)
                    ->where ('is_available', 1)
                    ->where ('id', '!=', 1)
                    ->where ('id', '!=', 71)
                    ->where ('is_verified', 1)
                    ->with ('categories', 'talentLatestArticle.article')
                    ->paginate (24);
            } elseif ($status == Gru::MAIN_DOMIAN) {
                $talents = Talent::whereHas ('categories', function ($query) use ($category) {
                    $query->where ('parent_id', '=', $category->id);
                })->doesntHave ('agency')
                    ->where ('is_published', 1)
                    ->where ('is_available', 1)
                    ->where ('id', '!=', 1)
                    ->where ('id', '!=', 71)
                    ->where ('is_verified', 1)
                    ->wherehas ('talentLatestArticle.article')
                    ->with ('categories', 'talentLatestArticle.article')
                    ->paginate (24);


            }

            if ($request->get ('page')) {

                return $talents;

            } else {
                return response ()->json ([

                    'category'   => $category,
                    'page_a'     => $page_a,
                    'talents'    => $talents,
                    '_agency'    => $_agency,
                    'url'        => $url
                ]);
            }
        }else{
            return ApiHelper::notFoundError ();
        }



    }

}
