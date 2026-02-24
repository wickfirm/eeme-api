<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TalentFilmography extends Model
{

    use HasTranslations;

    public $translatable = [
        'title',
        'description',
        'slug',
        'role' ];

    protected $table = 'talent_filmography';


    public function talent_filmography_cast()
    {
        return $this->hasMany(TalentFilmographyCast::class,'talent_filmography_id','id')->inRandomOrder();
    }

    public function talent () {
        return $this->belongsTo(Talent::class);
    }



}
