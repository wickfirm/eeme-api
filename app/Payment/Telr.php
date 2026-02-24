<?php


namespace App\Payment;


use App\Helpers\Gru;
use App\Helpers\Minion;
use App\Helpers\PromoCode\PromoCodeHelper;
use App\Helpers\Talent\TalentHelper;
use App\Models\Addons\Addon;
use App\Models\Donation\DonationRequest;
use App\Models\Order\MasterClass\MasterClassRequest;
use App\Models\Order\OrderRequest;
use App\Models\Order\PromoCode;
use App\Models\Talent\Talent;
use App\Models\Talent\TalentAddons;
use App\Models\Talent\TalentDomains;
use App\Models\Talent\TalentPackage;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;

class Telr {
    private $ivpStore;
    private $authKey;
    private $ivpCart;
    private $ivpAmount;
    private $ivpCurrency;
    private $ivpTest;
    private $ivp_framed;
    private $ivpTimestamp;
    private $ivpDesc;
    private $ivpLang;
    private $returnAuth;
    private $returnDecl;
    private $returnCan;
    private $orderRef;
    private $paymentUrl;
    private $responseData;

    public function setUpRequest( $request , OrderRequest $order_request ) {
        [ $status_domain , $agency , $web_url ] = TalentHelper::checkSubdomain( $request );
        $this->ivpStore    = config( 'payment.telr.ivp_store' );
        $this->authKey     = config( 'payment.telr.secret_key' );
        $this->ivpCart     = $order_request->talent->id . '-' . ( config( 'omneeyat.telr_order_start_index' ) + ( $order_request->id ) );
        $this->ivpCurrency = config( 'payment.telr.ivp_currency' );
        $this->ivp_framed  = 2;
        // $this->ivpAmount   = $order_request->talent->price->where('talent_price_type_id', 1)->first()->price;

        $total = PromoCodeHelper::getDiscountedPrice( $order_request );

        $this->ivpAmount = $total;

        if ( $order_request->email == 'marwasaad.cs@gmail.com' ) {
            $this->ivpTest = 1;
        }
        else {
            $this->ivpTest = 0;
        }
        $this->ivpTimestamp = Carbon::now()->timestamp;
        $this->ivpDesc      = 'Omneeyat\'s order' . ' - ' . $order_request->id;
        $this->ivpLang      = App::getLocale();

        $private_domain = TalentDomains::where( 'talent_id' , $order_request->talent_id )->get();

        $url                = parse_url( \URL::current() )[ 'host' ];
        $url                = str_replace( 'www.' , '' , $url );
        $talent_domains     = TalentDomains::where( 'domain_name' , $url )->get();
        $on_external_domain = false;
        if ( ! $talent_domains->isEmpty() ) {
            $on_external_domain = true;
        }

        if ( ! $private_domain->isEmpty() && $on_external_domain ) {
            $domain_name = $private_domain->first()->domain_name;

            $this->returnAuth = 'http://' . $domain_name . '/payment/approved';
            $this->returnDecl = 'http://' . $domain_name . '/payment/declined';
            $this->returnCan  = 'http://' . $domain_name . '/payment/cancelled';
        }
        else {

            $this->returnAuth = $web_url . '/payment/approved';
            $this->returnDecl = $web_url . '/payment/declined';
            $this->returnCan  = $web_url . '/payment/cancelled';
        }
    }

    public function setUpBusinessOrderRequest( MasterClassRequest $order_request ) {
        $this->ivpStore    = config( 'payment.telr.ivp_store' );
        $this->authKey     = config( 'payment.telr.secret_key' );
        $this->ivpCart     = $order_request->talent_id . '-' . ( config( 'omneeyat.telr_order_start_index' ) + ( $order_request->id ) );
        $this->ivpCurrency = config( 'payment.telr.ivp_currency' );
        $this->ivpAmount   = $order_request->price;

        if ( $order_request->email == 'marwasaad.cs@gmail.com' ) {
            $this->ivpTest = 1;
        }
        else {
            $this->ivpTest = 0;
        }
        $this->ivpTimestamp = Carbon::now()->timestamp;
        $this->ivpDesc      = 'Master Class order' . ' - ' . $order_request->id;
        $this->ivpLang      = App::getLocale();
        $this->returnAuth   = config( 'payment.telr.return__auth' );
        $this->returnDecl   = config( 'payment.telr.return__decl' );
        $this->returnCan    = config( 'payment.telr.return__can' );
    }

    public function setupDonationRequest( DonationRequest $donation_request ) {
        $this->ivpStore    = config( 'payment.telr.ivp_store' );
        $this->authKey     = config( 'payment.telr.secret_key' );
        $this->ivpCart     = $donation_request->id . '-' . ( config( 'omneeyat.telr_order_start_index' ) );
        $this->ivpCurrency = config( 'payment.telr.ivp_currency' );

        $this->ivpAmount = $donation_request->amount;

        if ( $donation_request->email == 'haya.inc@gmail.com' ) {
            $this->ivpTest = 1;
        }
        else {
            $this->ivpTest = 0;
        }
        $this->ivpTimestamp = Carbon::now()->timestamp;
        $this->ivpDesc      = 'Omneeyat\'s Donation' . ' - ' . $donation_request->id;
        $this->ivpLang      = App::getLocale();
        $this->returnAuth   = config( 'payment.telr.return_donation_auth' );
        $this->returnDecl   = config( 'payment.telr.return_donation_dec1' );
        $this->returnCan    = config( 'payment.telr.return_donation_can' );
    }

    public function issuePayment() {
        $post_data = $this->buildRequestPostData();

        $guzzle      = new Client();
        $response    = $guzzle->request( 'POST' , config( 'payment.telr.endpoint' ) , [
            'form_params' => $post_data
        ] );
        $status_code = $response->getStatusCode();

        $response_body = json_decode( $response->getBody()->getContents() );

        if ( isset( $response_body->order ) ) {

            $this->orderRef = $response_body->order->ref;

            $this->setResponseData( $response_body );
            $this->setOrderRef( $response_body->order->ref );

            if ( $status_code ) {

                $this->setPaymentUrl( $response_body->order->url );
            }
        }
        else {
            return Minion::return_error( 500 , $response_body , 'Something went wrong. Please contact us at hello@omneeyat.com' );
        }

        return $status_code;
    }

    private function buildRequestPostData() {
        return [
            'ivp_method'   => 'create' ,
            'ivp_store'    => $this->ivpStore ,
            'ivp_authkey'  => $this->authKey ,
            'ivp_amount'   => $this->ivpAmount ,
            'ivp_currency' => $this->ivpCurrency ,
            'ivp_test'     => $this->ivpTest ,
            'ivp_framed'   => $this->ivp_framed ,
            'ivp_cart'     => $this->ivpCart ,
            'ivp_desc'     => $this->ivpDesc ,
            'ivp_lang'     => $this->ivpLang ,
            'return_auth'  => $this->returnAuth ,
            'return_decl'  => $this->returnDecl ,
            'return_can'   => $this->returnCan ,
        ];
    }

    private function buildResponsePostData( $order_reference ) {
        return [
            'ivp_method'  => 'check' ,
            'ivp_store'   => config( 'payment.telr.ivp_store' ) ,
            'ivp_authkey' => config( 'payment.telr.secret_key' ) ,
            'order_ref'   => $order_reference
        ];
    }

    public function validate_order( $order_reference ) {
        $post_data = $this->buildResponsePostData( $order_reference );

        $guzzle   = new Client();
        $response = $guzzle->request( 'POST' , config( 'payment.telr.endpoint' ) , [
            'form_params' => $post_data
        ] );

        $status_code = $response->getStatusCode();

        $this->setResponseData( json_decode( $response->getBody()->getContents() ) );

        if ( $status_code ) {
            $this->setOrderRef( $this->responseData->order->ref );
        }

        return $status_code;
    }

    /**
     * @return mixed
     */
    public function getOrderRef() {
        return $this->orderRef;
    }

    /**
     * @param mixed $orderRef
     */
    public function setOrderRef( $orderRef )
    : void {
        $this->orderRef = $orderRef;
    }

    /**
     * @return mixed
     */
    public function getPaymentUrl() {
        return $this->paymentUrl;
    }

    /**
     * @param mixed $paymentUrl
     */
    public function setPaymentUrl( $paymentUrl )
    : void {
        $this->paymentUrl = $paymentUrl;
    }

    /**
     * @return mixed
     */
    public function getResponseData() {
        return $this->responseData;
    }

    /**
     * @param mixed $responseData
     */
    public function setResponseData( $responseData )
    : void {
        $this->responseData = $responseData;
    }


}
