<?php

namespace App\Models\Misc;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BusinessOrderType extends Model
{
    use Sluggable, HasTranslations;

    public $translatable = [ 'name','title','description' ];

    protected $table = 'business_orders_types';

    public function sluggable(): array {
        return [
            'source' => 'name'
        ];
    }
}
