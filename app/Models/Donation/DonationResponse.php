<?php

namespace App\Models\Donation;

use Illuminate\Database\Eloquent\Model;

class DonationResponse extends Model
{
    protected $table = 'donations_responses';


    protected $dates = [ 'created_at', 'updated_at' ];

    public function donation_request() {
        return $this->belongsTo( DonationRequest::class );
    }


}
