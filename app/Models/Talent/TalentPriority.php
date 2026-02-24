<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;

class TalentPriority extends Model
{
    protected $table = 'talents_priority';

    public function talent_article() {
        return $this->belongsTo( TalentArticle::class );
    }
    public function talent() {
        return $this->belongsTo( Talent::class );
    }
    public function deleteTalentPriority( $priority ) {
        $this->where( 'priority', $priority)->delete();
    }

}
