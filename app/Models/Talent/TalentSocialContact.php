<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;

class TalentSocialContact extends Model
{
    protected $table = 'talents_social_contacts';

    public function talent_social() {
        return $this->hasOne( TalentSocial::class  );
    }

}
