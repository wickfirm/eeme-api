<?php

namespace App\Http\Controllers\Video;

use App\Helpers\Gru;
use App\Helpers\Talent\TalentHelper;
use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Misc\Category;
use App\Models\Order\OrderRequest;
use App\Models\Order\OrderResponse;
use App\Models\Page;
use App\Models\Talent\TalentArticle;
use App\Models\Talent\TalentOrder;
use App\Models\Talent\TalentVideo;
use App\Models\Video\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request ,$code){
        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;
        $order_request = OrderRequest::where('code','=',$code)->with('order_response')->first();


        if($order_request->id != 2391 ){

            $page_a = TalentHelper::getPage(Gru::VIDEO_PAGE_ID , $agency );
            $talent = $order_request->talent;


            $articles = Article::whereHas('talents',function ($query) use ($talent){
                $query->where('talent_id',$talent->id);
            })->where('is_published',1)->orderby('created_at','DESC')->paginate(6);

            return [
                'data' => [
                    '_agency'       => $_agency,
                    'url'           => $url,
                    'order_request' => $order_request,
                    'talent'        => $talent,
                    'articles'      => $articles
                ]
            ];


        }else{
            abort(404);
        }

    }

}
