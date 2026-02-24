<?php

namespace App\Models\Talent;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TalentMeta extends Model
{
    use HasTranslations;

    public $translatable = ['description', 'title'];

    protected $table = 'talents_meta';
}
