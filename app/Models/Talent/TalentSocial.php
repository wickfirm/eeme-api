<?php

namespace App\Models\Talent;

use App\Models\Misc\SocialMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TalentSocial extends Model
{
    protected $table = 'talents_social';
    public function social() {
        return $this->belongsTo( SocialMedia::class, 'social_media_id' );
    }
    public function talent_social_info(){
        return $this->hasOne(TalentSocialInfo::class);
    }
    public function talent_social_contact(){
        return $this->hasMany(TalentSocialContact::class);
    }
    public function talent_social_post(){
        return $this->hasMany(TalentSocialPost::class);
    }
    public function talent(){
        return $this->belongsTo( Talent::class);
    }
    protected static function booted () {
        static::addGlobalScope ('talent_social', function (Builder $builder) {
            $builder->with (['social']);
        });
    }

}
