<?php

namespace App\Models\Order\MasterClass;

use Illuminate\Database\Eloquent\Model;

class MasterClassRequest extends Model
{
    protected $table = 'master_class_requests';

    public function master_class_response() {
        return $this->hasOne( MasterClassResponse::class );
    }
}
