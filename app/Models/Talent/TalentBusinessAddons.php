<?php

namespace App\Models\Talent;

use App\Models\Addons\Addon;
use App\Models\Addons\BusinessAddon;
use Illuminate\Database\Eloquent\Model;

class TalentBusinessAddons extends Model
{
    protected $table = 'talent_business_addons';
    protected $guarded = [];



    public function business_addons(){
        return $this->belongsTo(BusinessAddon::class);
    }

    public function talent(){
        return $this->belongsTo(Addon::class);
    }
}
