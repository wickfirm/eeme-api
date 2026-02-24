<?php

namespace App\Models\Addons;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CategoryAddons extends Model {
    use HasTranslations;

    protected $connection = 'mysql';

    protected $guarded = [];

    public $translatable = [ 'name' ];

    protected $table = 'categories_addons';
}
