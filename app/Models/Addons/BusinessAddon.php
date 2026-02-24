<?php

namespace App\Models\Addons;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BusinessAddon extends Model
{
    use HasTranslations;


    protected $guarded = [] ;

    public $translatable = [ 'name', 'description' ];


    protected $table = 'business_addons';

    public function talents(){
        return $this->belongsToMany('App\Models\Talent\Talent', 'talent_addons', 'addons_id', 'talent_id');
    }



}
