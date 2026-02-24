<?php

namespace App\Models\Order\MasterClass;

use Illuminate\Database\Eloquent\Model;

class MasterClassResponse extends Model
{
    protected $table = 'master_class_responses';
    protected $dates = [ 'created_at', 'updated_at' ];

    public function master_class_request() {
        return $this->belongsTo( MasterClassRequest::class );
    }
}
