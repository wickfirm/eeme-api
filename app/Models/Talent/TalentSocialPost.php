<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TalentSocialPost extends Model
{
    protected $table = 'talents_social_posts';
    public function talent_social() {
        return $this->belongsTo( TalentSocial::class  );
    }

    protected static function booted () {
        static::addGlobalScope ('talent_social_post', function (Builder $builder) {
            $builder->with (['talent_social']);
        });
    }
}
