<?php

namespace App\Models\Donation;

use Illuminate\Database\Eloquent\Model;

class DonationRequest extends Model
{
    protected $table = 'donations_requests';


    public function donation_response() {
        return $this->hasOne( DonationResponse::class );
    }
}
