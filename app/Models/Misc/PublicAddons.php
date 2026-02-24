<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PublicAddons extends Model
{
    use HasTranslations;
    protected $table = 'public_addons';
    public $translatable = [ 'name' ];

    public function addons(){
        return $this->belongsTo(PublicEnrollmentTalents::class);
    }
}
