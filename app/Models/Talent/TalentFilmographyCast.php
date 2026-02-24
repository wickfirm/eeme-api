<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TalentFilmographyCast extends Model
{
    use HasTranslations;

    public $translatable = [
        'name','role'
    ];
    protected $table = 'talent_filmography_cast';


    public function deleteTalentFilmographyCast( $talent_filmography ) {

        $this->where( 'talent_filmography_id', $talent_filmography )->where('talent_id','<>',null)->delete();
    }

    public function talent() {
        return $this->belongsTo( Talent::class );
    }
    public function filmography(){
        return $this->belongsTo(TalentFilmography::class, "talent_filmography_id", "id");
    }

    protected static function booted () {
        static::addGlobalScope ('talent_filmography_cast', function (Builder $builder) {
            $builder->with (['filmography' , 'talent']);
        });
    }


}
