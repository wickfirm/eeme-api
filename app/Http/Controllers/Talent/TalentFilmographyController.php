<?php

namespace App\Http\Controllers\Talent;

use App\Helpers\ApiHelper;
use App\Helpers\Gru;
use App\Helpers\Talent\TalentHelper;
use App\Http\Controllers\Controller;
use App\Models\Misc\Category;
use App\Models\Page;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentDomains;
use App\Models\Talent\TalentFilmography;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use function GuzzleHttp\Psr7\get_message_body_summary;

class TalentFilmographyController extends Controller {
    public function show( Request $request , $title ) {
        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;

       $film = TalentFilmography::where('slug->en' , $title)->orWhere('slug->ar',$title)->first();

        if ( $film ) {
            $page_a = TalentHelper::getPage(Gru::FILMOGRAPHY_PAGE_ID , $agency );
            $casting = $film->talent_filmography_cast;
            $meta_description = '';
           if($casting->count() > 0 ) {
               foreach ($casting as $actor) {
                   $meta_description .= $actor->name . " ";
               }
           }

           return [
               'data' => [
                   'filmography'      => $film,
                   'meta_description' => $meta_description,
                   'casting'          => $casting
               ]
           ];

        }else{
            return ApiHelper::notFoundError ();
        }
    }
}
