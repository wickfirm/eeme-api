<?php


namespace App\ActiveCampaign;


use GuzzleHttp\Client;

class ActiveCampaign
{
    public function connect(){
        $ac = new \ActiveCampaign(config('activecampaign.activecampaign_url'),config('activecampaign.activecampaign_api_key'));
        if ((int)$ac->credentials_test()){

            $ac->api('contact/list');
            return $ac;
        }else{
            return false;
        }
    }

    public function get_form( $id ){

        $url = config('activecampaign.activecampaign_url');
        $data['app_token'] = config('activecampaign.activecampaign_api_key');
        $form_data = json_encode($data);
        $guzzle  = new Client();
        $response    =
        $guzzle->request('GET', 'https://omneeyat.api-us1.com/api/3/forms/' .$id , [
            \GuzzleHttp\RequestOptions::HEADERS      => array(
                'Api-Token'        => config('activecampaign.activecampaign_api_key'),
            ),
        ]);

       return $response_body = json_decode($response->getBody()->getContents());
    }

    public function get_form_list($response_body){

        $list_id = $response_body->form->actiondata->actions[0]->list;
        return $list_id;

    }

    public function add_contact($contact,$ac,$automation,$email )
    {
        $contact_sync = $ac->api("contact/sync", $contact);
        if ((int)$contact_sync->success) {
            $contact_id = (int)$contact_sync->subscriber_id;
        }

    }
}
