<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PublicEnrollmentTalents extends Model
{
    use HasTranslations;

    public $translatable = [ 'description','name' ];

    protected $table = 'public_enrollment_talents';

    protected $casts = [
        'categories' => 'array',
        'addons'     => 'array',
        'platforms'  => 'array'
    ];
}
