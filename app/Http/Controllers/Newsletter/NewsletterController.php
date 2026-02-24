<?php

namespace App\Http\Controllers\Newsletter;

use ActiveCampaign;
use App\Helpers\Minion;
use App\Http\Controllers\Controller;
use App\Models\Order\OrderRequest;
use App\Models\Talent\Talent;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class NewsletterController extends Controller {

    public function store( Request $request ) {
        $validator = Validator::make( $request->all() , [
            'email'   => 'required' ,

        ] );

        if ( $validator->fails() ) {

            return Minion::return_error( 400 , '' , 'Invalid Email' );
        }

//        $email   = $request->get( 'email' );
//        $form_id = $request->get( 'form_id' );
//
//        $ac   = new \App\ActiveCampaign\ActiveCampaign();
//        $auth = $ac->connect();
//
//        if ( $auth != false ) {
//            $response = $ac->get_form( $form_id );
//            $list_id  = $ac->get_form_list( $response );
//
//            if ( $request->has( 'keep_up_to_date' ) ) {
//                $contact = [
//                    "email"                    => $email ,
//                    "p[" . $list_id . "]"      => $list_id ,
//                    "form"                     => $form_id ,
//                    "status[" . $list_id . "]" => $list_id ,
//                    'field[3,0][0]'            => 'Keep me up-to-date on Omneeyat™ exclusives.' ,
//                    'field[3,0][1]'            => 'Send talent updates and new releases' ,
//
//                ];
//            }
//            else {
//                $contact = [
//                    "email"                    => $email ,
//                    "p[" . $list_id . "]"      => $list_id ,
//                    "form"                     => $form_id ,
//                    "status[" . $list_id . "]" => $list_id ,
//                ];
//            }
//            $automation = 3;
//
//            $ac->add_contact( $contact , $auth , $automation , $email );
//        }
        // successful request

        return true;

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
}
