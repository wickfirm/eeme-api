<?php


namespace App\Payment;


use App\Helpers\Payment\PaymentHelper;
use App\Helpers\Talent\TalentHelper;
use App\Models\Order\OrderRequest;
use Carbon\Carbon;
use Srmklive\PayPal\Services\ExpressCheckout;

class Paypal {
    private $paymentUrl;

    public function setUpRequest( OrderRequest $order_request ,  $request) {
        [ $status_domain , $agency , $web_url ] = TalentHelper::checkSubdomain( $request );

        $total = PaymentHelper::totalPrice( $order_request );

        $data[ 'items' ]               = [
            [
                'name'  => PaymentHelper::productName( $order_request , $total ) ,
                'price' => $total ,
                'desc'  => 'eeMe\'s order' . ' - ' . $order_request->id ,
                'qty'   => 1
            ] ,
        ];
        $data[ 'total' ]               = $total;
        $data[ 'invoice_id' ]          = uniqid();
        $data[ 'invoice_description' ] = PaymentHelper::productName( $order_request , $total );
        $data[ 'return_url' ]          = $web_url . '/payment/approved' ;
        $data[ 'cancel_url' ]          = $web_url . '/payment/cancelled' ;

        return $data;
    }

    public function issuePayment( $data ) {

        $provider = new ExpressCheckout;
        return $provider->setExpressCheckout( $data );
    }

}
