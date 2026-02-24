<?php

namespace App\Models\Talent;

use App\Models\Misc\BusinessOrderType;
use Illuminate\Database\Eloquent\Model;

class TalentOrderType extends Model
{
    protected $table = 'talents_orders_types';

    public function deleteTalentOrderType( $talent ) {
        $this->where( 'talent_id', $talent->id )->delete();
    }
    public function talent() {
        return $this->belongsTo( Talent::class );
    }
    public function business_order_type() {
        return $this->belongsTo( BusinessOrderType::class );
    }

}
