<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Minion {
//    public static function upload_image( UploadedFile $image, $location ) {
//        $new_name = Str::random( 25 ) . '.' . $image->getClientOriginalExtension();
//        $image    = Storage::putFileAs( $location, $image, $new_name );
//
//        return '/storage/' . $image;
//    }
//
//    public static function upload_file( UploadedFile $file, $location ) {
//        $new_name = $file->getClientOriginalName();
//        $image    = Storage::putFileAs( $location, $file, $new_name );
//
//        return '/storage/' . $image;
//    }
    public static function upload_image(UploadedFile $image, $location)
    {
        $new_name = Str::random(25) . '.' . $image->getClientOriginalExtension();
        $image    = Storage::putFileAs($location, $image, $new_name);

        return 'https://storage.googleapis.com/omneeyat_gcs/' . $image;
    }

    public static function upload_file(UploadedFile $uploaded_file, $location, $name)
    {
        $name = $name . '.' . $uploaded_file->getClientOriginalExtension();
        return Storage::disk('omneeyat')->putFileAs($location, $uploaded_file, $name);
    }


    public static function return_error( $code, $debugger = null, $message = null ) {
        $error_data["error"]             = array();
        $error_data["error"]["message"]  = $message;
        $error_data["error"]["debugger"] = $debugger;

        return response( $error_data, $code );
    }

    public static function create_slug( $variable, $class ) {
        $slug  = Str::slug( $variable );
        $exist = $class::where( 'slug', 'LIKE', "%$slug%" )->get();

        if ( count( $exist ) > 0 ) {
            $slug .= '-' . ( count( $exist ) + 1 );
        }

        return $slug;
    }

    public static function human_date_format( $date, $difference = 3, $format = 'F d, Y' ) {
        $date = Carbon::createFromFormat( 'Y-m-d H:i:s', $date );

        $now = Carbon::now();

        if ( $date->diffInDays( $now ) > $difference ) {
            return $date->format( $format );
        } else {
            return $date->diffForHumans();
        }
    }
    public static function paginate($items, $perPage = 18 , $page = null, $options = [])
    {

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
