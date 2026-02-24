<?php

namespace App\Http\Controllers\Onboarding;

use App\ActiveCampaign\ActiveCampaign;
use App\Helpers\Gru;
use App\Helpers\Minion;
use App\Helpers\Talent\TalentHelper;
use App\Http\Controllers\Controller;
use App\Models\Addons\Addon;
use App\Models\Misc\Category;
use App\Models\Misc\PublicAddons;
use App\Models\Misc\PublicEnrollmentTalents;
use App\Models\Package\Package;
use App\Models\Page;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentPackage;
use App\Models\Talent\TalentPackageRequest;
use App\Models\Talent\TalentPackageResponse;
use App\Models\Talent\TalentVideo;
use App\Models\User\User;
use App\Notifications\AdminOnboardingRegistrationNotification;
use App\Notifications\NewOrderNotification;
use App\Notifications\OnboardingRegistrationNotification;
use App\Notifications\PublicTalentEnrollmentNotification;
use App\Payment\Stripe;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Notification;

class OnboardingController extends Controller {
    public function index(Request $request) {
        $page_a = TalentHelper::getPage( Gru::ONBOARDING_ID , null );

        [ $status , $agency , $url ] = TalentHelper::checkSubdomain( $request );
        $_agency = (bool)$agency;
        $artist = Talent::find( 24 );

        $packages = Package::where( 'user_type' , Gru::TALENT_USER_TYPE )->get();


        $talents   = Talent::where( 'is_published' , 1 )
            ->where( 'id' , '<>' , 1 )
            ->where( 'id' , '<>' , 71 )
            ->where( 'is_available' , 1 )
            ->where( 'is_influencer' , 0 )
            ->where('is_verified',1)
            ->doesntHave( 'agency' )
            ->inRandomOrder()
            ->get()
            ->take( 6 );

        $no_footer = true;
        $is_artist = true;


        return response ()->json ([
            'data' => [
                'page_a'           => $page_a,
                'talents'          => $talents,
                '_agency'          => $_agency,
                'packages'         => $packages,
                'url'              => $url
            ]
        ]);
    }



    public function store( Request $request ) {
        $validator = Validator::make( $request->all() , [
            'name'             => 'required' ,
            'email'            => 'required' ,
            'phone_number'     => 'required' ,
            'ig_username'      => 'required' ,
            'other_social'     => 'required' ,
            'profile_link'     => 'required' ,
            'followers'        => 'required' ,
            'package_id'          => 'required' ,
        ] );

        if ( $validator->fails() ) {
            return redirect()->back()->withInput()->with( 'errors' , $validator->errors() );
        }
        [$status_domain, $agency, $url] = TalentHelper::checkSubdomain ($request);
        $public_enrollment_talent = new PublicEnrollmentTalents();
        $public_enrollment_talent->setTranslation( 'name' , 'en' , $request->get( 'name' ) );
        $public_enrollment_talent->setTranslation( 'name' , 'ar' , $request->get( 'name_ar' ) );
        $public_enrollment_talent->email            = $request->get( 'email' );
        $public_enrollment_talent->ig_username      = $request->get( 'ig_username' );
        $public_enrollment_talent->youtube_username = $request->get( 'youtube_username' );
        $public_enrollment_talent->other_username   = $request->get( 'other_social' );
        $public_enrollment_talent->profile_link     = $request->get( 'profile_link' );
        $public_enrollment_talent->followers        = $request->get( 'followers' );
        $public_enrollment_talent->number           = $request->get( 'phone_number' );
        $public_enrollment_talent->package          = $request->get( 'package_id' );
        $public_enrollment_talent->private_code     = $request->get( 'private_code' );
        $public_enrollment_talent->package_id       = $request->get('package_id');
        $public_enrollment_talent->user_type        = Gru::TALENT_USER_TYPE;
        $public_enrollment_talent->save();

        $package = Package::find(   $public_enrollment_talent->package_id);

        if ( $package->id != Gru::DEFAULT_PACKAGE ) {

            $payment = new Stripe();
            $stripe = $payment->setUpSubscriptionRequest( $request , $package );

            Session::forget( 'stripe' );
            Session::put( 'stripe' , $stripe->id );

            Session::forget( 'enroll' );
            Session::put( 'enroll' , $public_enrollment_talent->id );

            return [
                'data' => [
                    'url'                  => $stripe->url,
                    'public_enrollment_id' => $public_enrollment_talent->id,
                    'stripe_session_id'    => $stripe->id
                ]
            ];

        }else{
            [$status_domain, $agency, $url] = TalentHelper::checkSubdomain ($request);
            $_agency = (bool)$agency;

            return [
                'data' => [
                    'url'                  => $url .'/subscription/approved',
                    'public_enrollment_id' => $public_enrollment_talent->id,
                    'stripe_session_id'    => false

                ]
            ];

//            Notification::route('mail', 'hello@omneeyat.com')->notify(new AdminOnboardingRegistrationNotification($public_enrollment_talent));
//            Notification::route('mail',   $public_enrollment_talent->email)->notify(new OnboardingRegistrationNotification($public_enrollment_talent , $package));




        }


//        $ac =new ActiveCampaign();
//        $auth = $ac->connect();
//
//        if($auth != false) {
//            $response = $ac->get_form( 1 );
//            $list_id  = $ac->get_form_list( $response );
//
//            $contact = [
//                "email"                    => $public_enrollment_talent->email ,
//                "first_name"               => $public_enrollment_talent->name ,
//                "p[" . $list_id . "]"      => $list_id ,
//                "form"                     => 1 ,
//                "status[" . $list_id . "]" => 1 ,
//
//            ];
//
//            $automation = 2;
//            $email      = $public_enrollment_talent->email;
//
//            $ac->add_contact( $contact , $auth , $automation , $email );
//        }
//        Notification::route('mail', 'effat.ammar@omneeyat.com')->notify(new PublicTalentEnrollmentNotification($public_enrollment_talent));
//        Notification::route('mail', 'hello@omneeyat.com')->notify(new PublicTalentEnrollmentNotification($public_enrollment_talent));



    }


}
