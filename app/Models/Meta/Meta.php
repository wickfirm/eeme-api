<?php

namespace App\Models\Meta;

use App\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Meta extends Model
{
    use  HasTranslations;

    protected $table = 'meta';

    public $translatable = [ 'title', 'description' ];


    public function page() {
        return $this->belongsTo( Page::class);
    }
}
