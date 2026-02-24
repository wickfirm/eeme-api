<?php

namespace App\Models\Talent;

use App\Models\Misc\SocialMedia;
use Illuminate\Database\Eloquent\Model;

class TalentSocialInfo extends Model
{
    protected $table = 'talents_social_info';

    public function social() {
        return $this->belongsTo( SocialMedia::class, 'social_media_id' );
    }
    public function talent_social() {
        return $this->belongsTo( TalentSocial::class  );
    }
}
