<?php

namespace App\Models\Brand;

use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Brand extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'slug','description','title'];

    protected $table = 'brands';

    public function video() {
        return $this->belongsTo( Video::class );
    }
}
