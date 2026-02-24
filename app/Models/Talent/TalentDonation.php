<?php

namespace App\Models\Talent;

use App\Models\Donation\DonationRequest;
use Illuminate\Database\Eloquent\Model;

class TalentDonation extends Model
{
    protected $table = 'talents_donations';

    public function talent() {
        return $this->belongsTo( Talent::class );
    }
    public function donation_request() {
        return $this->belongsTo( DonationRequest::class );
    }
}
