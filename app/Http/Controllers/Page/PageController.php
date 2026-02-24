<?php

namespace App\Http\Controllers\Page;

use App\Helpers\Gru;
use App\Helpers\Talent\TalentHelper;
use App\Http\Controllers\Controller;
use App\Models\Agency\AgencyPage;
use App\Models\Misc\Category;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller {
    public function about( Request $request ) {
        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;

        $page_a = TalentHelper::getPage(Gru::ABOUT_PAGE_ID , $agency );

        return response ()->json ([
            'page_a'           => $page_a,
            '_agency'          => $_agency,

            'url'              => $url
        ]);

    }

    public function terms(Request $request ) {
        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;
        $page_a = TalentHelper::getPage(Gru::TERMS_PAGE_ID , $agency );

        return response ()->json ([
            'page_a'           => $page_a,
            '_agency'          => $_agency,
            'url'              => $url
        ]);    }

    public function privacy_policy(Request $request ) {
        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;
        $page_a = TalentHelper::getPage(Gru::PRIVACY_PAGE_ID , $agency );

        return response ()->json ([
            'page_a'           => $page_a,
            '_agency'          => $_agency,
            'url'              => $url
        ]);    }
    public function contact( Request $request ) {
        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $page_a = TalentHelper::getPage(Gru::CONTACT_PAGE_ID , $agency );
        $_agency = (bool)$agency;

        return response ()->json ([
            'page_a'           => $page_a,
            '_agency'          => $_agency,
            'url'              => $url
        ]);    }
}
