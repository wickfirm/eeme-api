<?php
/**
 * Created by PhpStorm.
 * User: aymanbitar
 * Date: 2019-01-27
 * Time: 21:18
 */

namespace App\Models\Traits;

use App\Helpers\Minion;

trait Sluggable {

    abstract public function sluggable(): array;

    public static function bootSluggable() {
        static::creating( function ( $model ) {
            $settings    = $model->sluggable();
            $variable    = $settings['source'];
            $slug        = Minion::create_slug( $model->$variable, get_class( $model ) );
            $model->slug = $slug;
        } );
    }

}
