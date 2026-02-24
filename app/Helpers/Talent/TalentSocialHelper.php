<?php

namespace App\Helpers\Talent;

use App\Helpers\ApexChart;
use App\Helpers\Gru;

class TalentSocialHelper {
    public static function socialUserProfileCharts( $talent_social ) {
        if ( isset( $talent_social->talent_social_info->user_profile ) ) {
            $stat = json_decode( $talent_social->talent_social_info->user_profile )->stat_history;
            if ( isset( $stat ) ) {
                $followers_array[ 'Followers' ] = [];
                $following_array[ 'Following' ] = [];
                $likes_array[ 'Likes' ]         = [];
                $views_array['Views']           = [];
                $categories                     = [];
                foreach ( $stat as $f ) {
                    array_push( $categories , substr( $f->month , 5 ) );
                    array_push( $followers_array[ 'Followers' ] , $f->followers );
                    if ( isset( $f->following ) ) {
                        array_push( $following_array[ 'Following' ] , $f->following );
                    }
                    if(isset($f->avg_views)){
                        array_push( $views_array[ 'Views' ] , $f->avg_views );

                    }
                    array_push( $likes_array[ 'Likes' ] , $f->avg_likes );
                }

                $followers_stat = ApexChart::create_area_chart( 'Followers ' , $followers_array , $categories );
                $following_stat = ApexChart::create_area_chart( 'Following ' , $following_array , $categories );
                $likes_stat     = ApexChart::create_area_chart( 'Likes ' , $likes_array , $categories );
                $views_stat     = ApexChart::create_area_chart( 'Views ' , $views_array , $categories );

            }
            else {
                $followers_stat = null;
                $following_stat = null;
                $likes_stat     = null;
                $views_stat     = null;
            }
        }
        else {
            $followers_stat = null;
            $following_stat = null;
            $likes_stat     = null;
            $views_stat     = null;

        }
        return [ $followers_stat , $following_stat , $likes_stat , $views_stat];
    }

    public static function socialAudienceFollowersCharts( $talent_social ) {
        if ( isset( $talent_social->talent_social_info->audience_followers )  && json_decode( $talent_social->talent_social_info->audience_followers )->success == true && isset(json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_genders)) {
            $gender_split = json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_genders;
            if ( isset( $gender_split ) ) {
                $gender_array = [];
                foreach ( $gender_split as $object ) {
                    $gender_array[ $object->code ] = $object->weight;
                }
                $gender_stat = ApexChart::create_pie_chart( 'Gender Split' , $gender_array , true , 2 );
            }
            else {
                $gender_stat = null;
            }

            if ( isset( json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_reachability ) ) {
                $reachability = json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_reachability;
                if ( isset( $reachability ) ) {
                    $reachability_array = [];
                    foreach ( $reachability as $object ) {
                        $reachability_array[ $object->code ] = number_format( $object->weight * 100 , 2 );
                    }
                    $reachability_stat = ApexChart::create_bar_vertical_chart( 'Audience Reachability' , $reachability_array , false , 5 );
                }
                else {
                    $reachability_stat = null;
                }
            }
            else {
                $reachability_stat = null;
            }

            if(isset(json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_genders_per_age)){
                $audience_genders_per_age = json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_genders_per_age;
                if ( isset( $audience_genders_per_age ) ) {
                    $male_array   = [];
                    $female_array = [];
                    foreach ( $audience_genders_per_age as $object ) {
                        $male_array[ $object->code ]   = number_format( $object->male * 100 , 2 );
                        $female_array[ $object->code ] = number_format( $object->female * 100 , 2 );
                    }

                    $male_stat_age   = ApexChart::create_bar_vertical_chart( 'Audience Male per age' , $male_array , false , 5 );
                    $female_stat_age = ApexChart::create_bar_vertical_chart( 'Audience Female per age' , $female_array , false , 5 );
                }
                else {
                    $male_stat_age   = null;
                    $female_stat_age = null;
                }
            }else{
                $male_stat_age   = null;
                $female_stat_age = null;
            }


            if ( isset( json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_geo->cities ) ) {

                $audience_location_city = json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_geo->cities;
                if ( isset( $audience_location_city ) ) {
                    $city_array = [];
                    foreach ( $audience_location_city as $city ) {
                        $city_array[ $city->name ] = number_format( $city->weight * 100 , 2 );
                    }
                    $city_stat = ApexChart::create_bar_vertical_chart( 'Location by City' , $city_array , false , 5 );
                }
                else {
                    $city_stat = null;
                }
            }
            else {
                $city_stat = null;
            }
            if(isset(json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_geo->countries)){
                $audience_location_country = json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_geo->countries;
                if ( isset( $audience_location_country ) ) {
                    $country_array = [];
                    foreach ( $audience_location_country as $country ) {
                        $country_array[ $country->name ] = number_format( $country->weight * 100 , 2 );
                    }
                    $country_stat = ApexChart::create_bar_chart( 'Location by Country' , $country_array , false , 5 );
                }
                else {
                    $country_stat = null;
                }
            }else{
                $country_stat = null;

            }

            if ( isset( json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_ethnicities ) ) {

                $audience_ethnicities = json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_ethnicities;
                if ( isset( $audience_ethnicities ) ) {
                    $ethnicity_array = [];
                    foreach ( $audience_ethnicities as $ethnicity ) {
                        $ethnicity_array[ $ethnicity->name ] = number_format( $ethnicity->weight * 100 , 2 );
                    }
                    $ethnicity_stat = ApexChart::create_bar_chart( 'Ethnicity' , $ethnicity_array , false , 5 );
                }
                else {
                    $ethnicity_stat = null;
                }
            }
            else {
                $ethnicity_stat = null;
            }
            if(isset(json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_languages)){
                $audience_languages = json_decode( $talent_social->talent_social_info->audience_followers )->data->audience_languages;
                if ( isset( $audience_languages ) ) {
                    $audience_languages_array = [];
                    foreach ( $audience_languages as $language ) {
                        if ( isset( $language->name ) ) {
                            $audience_languages_array[ $language->name ] = number_format( $language->weight * 100 , 2 );
                        }
                    }
                    $languages_stat = ApexChart::create_bar_chart( 'Language' , $audience_languages_array , false , 5 );
                }
                else {
                    $languages_stat = null;
                }
            }else{
                $languages_stat = null;
            }

        }
        else {
            $languages_stat    = null;
            $ethnicity_stat    = null;
            $country_stat      = null;
            $city_stat         = null;
            $male_stat_age     = null;
            $female_stat_age   = null;
            $reachability_stat = null;
            $gender_stat       = null;
        }
        return [
            $languages_stat ,
            $ethnicity_stat ,
            $country_stat ,
            $city_stat ,
            $male_stat_age ,
            $female_stat_age ,
            $reachability_stat ,
            $gender_stat
        ];
    }

    public static function socialAudienceLikersCharts( $talent_social ) {
        if ( isset( $talent_social->talent_social_info->audience_likers ) && json_decode( $talent_social->talent_social_info->audience_likers )->success == true ) {
            $gender_aud_split = json_decode( $talent_social->talent_social_info->audience_likers )->data->audience_genders;
            if ( isset( $gender_aud_split ) ) {
                $gender_aud_array = [];
                foreach ( $gender_aud_split as $object ) {
                    $gender_aud_array[ $object->code ] = $object->weight;
                }
                $gender_aud_stat = ApexChart::create_pie_chart( 'Gender Split' , $gender_aud_array , true , 2 );
            }
            else {
                $gender_aud_stat = null;
            }
            $reachability_aud = json_decode( $talent_social->talent_social_info->audience_likers )->data->audience_reachability;
            if ( isset( $reachability_aud ) ) {
                $reachability_aud_array = [];
                foreach ( $reachability_aud as $object ) {
                    $reachability_aud_array[ $object->code ] = number_format( $object->weight * 100 , 2 );
                }
                $reachability_aud_stat = ApexChart::create_bar_vertical_chart( 'Audience Reachability' , $reachability_aud_array , false , 5 );
            }
            else {
                $reachability_aud_stat = null;
            }
            $audience_likes_genders_per_age = json_decode( $talent_social->talent_social_info->audience_likers )->data->audience_genders_per_age;
            if ( isset( $audience_likes_genders_per_age ) ) {
                $male_aud_array   = [];
                $female_aud_array = [];
                foreach ( $audience_likes_genders_per_age as $object ) {
                    $male_aud_array[ $object->code ]   = number_format( $object->male * 100 , 2 );
                    $female_aud_array[ $object->code ] = number_format( $object->female * 100 , 2 );
                }
                $male_aud_stat_age   = ApexChart::create_bar_vertical_chart( 'Audience Male per age' , $male_aud_array , false , 5 );
                $female_aud_stat_age = ApexChart::create_bar_vertical_chart( 'Audience Female per age' , $female_aud_array , false , 5 );
            }
            else {
                $male_aud_stat_age   = null;
                $female_aud_stat_age = null;
            }
            $audience_likes_location_city = json_decode( $talent_social->talent_social_info->audience_likers )->data->audience_geo->cities;
            if ( isset( $audience_likes_location_city ) ) {
                $city_aud_array = [];
                foreach ( $audience_likes_location_city as $city ) {
                    $city_aud_array[ $city->name ] = number_format( $city->weight * 100 , 2 );
                }
                $city_aud_stat = ApexChart::create_bar_vertical_chart( 'Location by City' , $city_aud_array , false , 5 );
            }
            else {
                $city_aud_stat = null;
            }
            $audience_likes_location_country = json_decode( $talent_social->talent_social_info->audience_likers )->data->audience_geo->countries;
            if ( isset( $audience_likes_location_country ) ) {
                $country_aud_array = [];
                foreach ( $audience_likes_location_country as $country ) {
                    $country_aud_array[ $country->name ] = number_format( $country->weight * 100 , 2 );
                }
                $country_aud_stat = ApexChart::create_bar_chart( 'Location by Country' , $country_aud_array , false , 5 );
            }
            else {
                $country_aud_stat = null;
            }
            $audience_likes_ethnicities = json_decode( $talent_social->talent_social_info->audience_likers )->data->audience_ethnicities;
            if ( isset( $audience_likes_ethnicities ) ) {
                $ethnicity_aud_array = [];
                foreach ( $audience_likes_ethnicities as $ethnicity ) {
                    $ethnicity_aud_array[ $ethnicity->name ] = number_format( $ethnicity->weight * 100 , 2 );
                }
                $ethnicity_aud_stat = ApexChart::create_bar_chart( 'Ethnicity' , $ethnicity_aud_array , false , 5 );
            }
            else {
                $ethnicity_aud_stat = null;
            }
            $audience_likes_languages = json_decode( $talent_social->talent_social_info->audience_likers )->data->audience_languages;
            if ( isset( $audience_likes_languages ) ) {
                $audience_likes_languages_array = [];
                foreach ( $audience_likes_languages as $language ) {
                    if ( isset( $language->name ) ) {
                        $audience_likes_languages_array[ $language->name ] = number_format( $language->weight * 100 , 2 );
                    }
                }
                $languages_aud_stat = ApexChart::create_bar_chart( 'Language' , $audience_likes_languages_array , false , 5 );
            }
            else {
                $languages_aud_stat = null;
            }
        }
        else {
            $languages_aud_stat    = null;
            $ethnicity_aud_stat    = null;
            $country_aud_stat      = null;
            $city_aud_stat         = null;
            $male_aud_stat_age     = null;
            $female_aud_stat_age   = null;
            $reachability_aud_stat = null;
            $gender_aud_stat       = null;
        }
        return [
            $languages_aud_stat ,
            $ethnicity_aud_stat ,
            $country_aud_stat ,
            $city_aud_stat ,
            $male_aud_stat_age ,
            $female_aud_stat_age ,
            $reachability_aud_stat ,
            $gender_aud_stat
        ];
    }

    public static function socialAudienceCommentersCharts( $talent_social ) {

        if ( isset( $talent_social->talent_social_info->audience_commenters ) && json_decode(
                                                                                     $talent_social->talent_social_info->audience_commenters )->success == true ) {
            $gender_aud_split = json_decode( $talent_social->talent_social_info->audience_commenters )->data->audience_genders;

            if ( isset( $gender_aud_split ) ) {
                $gender_aud_array = [];
                foreach ( $gender_aud_split as $object ) {
                    $gender_aud_array[ $object->code ] = $object->weight;
                }
                $gender_comment_stat = ApexChart::create_pie_chart( 'Gender Split' , $gender_aud_array , true , 2 );

            }
            else {
                $gender_comment_stat = null;
            }

            $audience_comment_genders_per_age = json_decode( $talent_social->talent_social_info->audience_commenters )->data->audience_genders_per_age;
            if ( isset( $audience_comment_genders_per_age ) ) {
                $male_aud_array   = [];
                $female_aud_array = [];
                foreach ( $audience_comment_genders_per_age as $object ) {
                    $male_aud_array[ $object->code ]   = number_format( $object->male * 100 , 2 );
                    $female_aud_array[ $object->code ] = number_format( $object->female * 100 , 2 );
                }
                $male_comment_stat_age   = ApexChart::create_bar_vertical_chart( 'Audience Male per age' , $male_aud_array , false , 5 );
                $female_comment_stat_age = ApexChart::create_bar_vertical_chart( 'Audience Female per age' , $female_aud_array , false , 5 );
            }
            else {
                $male_comment_stat_age   = null;
                $female_comment_stat_age = null;
            }
            $audience_comment_languages = json_decode( $talent_social->talent_social_info->audience_commenters )
                ->data->audience_languages;
            if ( isset( $audience_comment_languages ) ) {
                $audience_likes_languages_array = [];
                foreach ( $audience_comment_languages as $language ) {
                    if ( isset( $language->name ) ) {
                        $audience_likes_languages_array[ $language->name ] = number_format( $language->weight * 100 , 2 );
                    }
                }
                $languages_comment_stat = ApexChart::create_bar_chart( 'Language' , $audience_likes_languages_array ,
                    false , 5 );
            }
            else {
                $languages_comment_stat = null;
            }
        }else {
            $gender_comment_stat = null;
            $languages_comment_stat = null;
            $female_comment_stat_age = null ;
            $male_comment_stat_age   = null;

        }
        return [ $gender_comment_stat , $languages_comment_stat, $female_comment_stat_age , $male_comment_stat_age  ];
    }

}
