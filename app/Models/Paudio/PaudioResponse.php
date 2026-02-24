<?php

namespace App\Models\Paudio;

use App\Models\Talent\TalentArticle;
use Illuminate\Database\Eloquent\Model;

class PaudioResponse extends Model
{
    protected $table = 'paudio';

    public function talent_articles() {
        return $this->belongsTo( TalentArticle::class );
    }
}
