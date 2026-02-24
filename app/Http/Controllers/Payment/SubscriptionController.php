<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\Gru;
use App\Helpers\Notification\NotificationHelper;
use App\Helpers\Payment\PaymentHelper;
use App\Helpers\PromoCode\PromoCodeHelper;
use App\Helpers\Talent\TalentHelper;
use App\Http\Controllers\Controller;
use App\Models\Misc\Category;
use App\Models\Misc\PublicEnrollmentTalents;
use App\Models\Order\OrderRequest;
use App\Models\Order\OrderResponse;
use App\Models\Package\Package;
use App\Models\Page;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentPackage;
use App\Models\Talent\TalentPackageRequest;
use App\Models\Talent\TalentPackageResponse;
use App\Models\User\PasswordReset;
use App\Models\User\User;
use App\Notifications\AdminOnboardingRegistrationNotification;
use App\Notifications\MailNewTalentNotification;
use App\Notifications\OnboardingRegistrationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Stripe\Product;
use Stripe\Stripe;

class SubscriptionController extends Controller {
    public function index ($status , $public_enrollment_id , $current_stripe_session, Request $request) {

        [$status_domain, $agency, $url] = TalentHelper::checkSubdomain ($request);
        $_agency = (bool)$agency;


        $enroll = PublicEnrollmentTalents::find ($public_enrollment_id);


        if($current_stripe_session != 'false'){

            $payment = new \App\Payment\Stripe();

            [$payment_status, $response] = $payment->validateSubscription ($current_stripe_session, $enroll);
        }else{

            $payment_status = Gru::SUCCESS_PAYMENT ;
            $response = 'Free Package';
        }


        if ($payment_status == Gru::SUCCESS_PAYMENT) {

            $enroll->response = json_encode ($response);
            $enroll->save();

            if($enroll->is_notified == 0 ){
                $enroll->is_notified = 1 ;
                $enroll->save();

                $package = Package::find($enroll->package_id);

                try{
//                    Notification::route('mail', 'effat.ammar@thewickfirm.com')->notify(new AdminOnboardingRegistrationNotification($enroll));
//                    Notification::route('mail', 'hello@omneeyat.com')->notify(new AdminOnboardingRegistrationNotification($enroll));
//                    Notification::route('mail',   $enroll->email)->notify(new OnboardingRegistrationNotification($enroll , $package));

                }catch(\Exception $ex){

                }

            }

            return [
                'data' => [
                    '_agency'                  => $_agency,
                    'status'                   => Gru::SUCCESS_PAYMENT,
                    'public_talent_enrollment' => $enroll,


                ]
            ];

        }else{
            return [
                'data' => [
                '_agency'                  => $_agency,

                'url'                      => $url,
                'status'                   => Gru::CANCELLED_PAYMENT,
                ]

            ];

        }
    }
}
