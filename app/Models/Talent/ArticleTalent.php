<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;

class ArticleTalent extends Model
{
    protected $table = 'talent_articles';
    public function talent() {
        return $this->belongsTo( Talent::class );
    }
    public function article() {
        return $this->belongsTo( TalentArticle::class );
    }
}
