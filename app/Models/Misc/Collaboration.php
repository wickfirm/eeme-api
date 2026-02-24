<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Collaboration extends Model
{
    use HasTranslations;

    public $translatable = [ 'name' ];

    protected $table = 'collaborations';

}
