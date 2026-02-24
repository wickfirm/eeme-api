<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;

class TalentFeatured extends Model
{
    protected $table = 'talents_featured';

    public function talent() {
        return $this->belongsTo( Talent::class );
    }

    public function deleteTalentFeatured() {
        $this->delete();
    }
}
