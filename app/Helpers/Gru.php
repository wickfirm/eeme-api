<?php

namespace App\Helpers;

class Gru {

    const TALENT_BOOK_TYPE_INDIVIDUAL = 1;
    const TALENT_BOOK_TYPE_Other = 2;
    const YOUTUBE_PLATFORM_EMBED_ID = 2;
    const YOUTUBE_PLATFORM_ID = 5;
    const PRIVATE_DOMAIN_NAME = 88;

    const TALENT_USER_TYPE = 1;
    const INFLUENCER_USER_TYPE = 2;

    const DEFAULT_PACKAGE = 11;

    const PENDING_CAMPAIGN = 0;
    const ACCEPT_CAMPAIGN = 1;
    const REJECT_CAMPAIGN = 2;
    const DONE_CAMPAIGN = 3;
    const COMPLETE_CAMPAIGN = 4;
    const DIS_APPROVE_CAMPAIGN = 5;
    const TEMP_STATUS_CAMPAIGN = 6;

    const ABOUT_PAGE_ID = 1;
    const TERMS_PAGE_ID = 2;
    const PRIVACY_PAGE_ID = 3;
    const CONTACT_PAGE_ID = 4;
    const HOME_PAGE_ID = 5;
    const SINGLE_PAGE_ID = 6;
    const ARTICLE_PAGE_ID = 7;
    const BOOK_PAGE_ID = 8;
    const DONATE_PAGE_ID = 9;
    const VIDEO_PAGE_ID = 14;
    const TALENT_TEMP_PAGE_ID = 15;
    const FILMOGRAPHY_PAGE_ID = 16;
    const PENDING_PAGE_ID = 17;
    const CANCELLED_PAGE_ID = 18;
    const DECLINED_PAGE_ID = 19;
    const SUCCESS_PAGE_ID = 20;
    const NEWS_PAGE_ID = 33;
    const ONBOARDING_ID = 42;
    const TALENT_INDEX_PAGE_ID = 76;

    const PAYMENT_METHOD_WU = 0;
    const PAYMENT_METHOD_CC = 1;
    const PAYMENT_METHOD_PAYPAL = 2;
    const PAYMENT_METHOD_FREE = 3;
    const PAYMENT_METHOD_WHATS_APP = 4;
    const PAYMENT_METHOD_CASH = 5;

    const VIDEO_ORDER_TYPE = 1;
    const BUSINESS_ORDER_TYPE = 2;
    const CAMPAIGN_ORDER_TYPE = 3;

    const CANCELLED_PAYMENT = -2;
    const SUCCESS_PAYMENT = 3;
    const PENDING_PAYMENT = 1 ;


    const PAYMENT_METHODS = [
        0 => 'Western Union' ,
        1 => 'Credit Card' ,
        2 => 'PayPal' ,
        3 => 'Free' ,
        4 => 'Via WhatsAPP' ,
    ];
    const Special_Characters = [
        ':' ,
        '_' ,
        '@' ,
        '*' ,
        '؟' ,
        '-' ,
        '"' ,
        '/' ,
        '،' ,
        '#' ,
        '$' ,
        '!' ,
        '?' ,
        ';' ,
        '|' ,
        '<' ,
        '>' ,
        '~' ,
        '.' ,
        '`' ,
        '=' ,
        '%' ,
        '^' ,
        '&' ,
        '(' ,
        ')' ,
        '{' ,
        '}' ,
        '[' ,
        ']' ,
        '+' ,
        '\'' ,
        '\\' ,
        ',' ,
        '’' ,
        'ـ'
    ];

    const ACTING_WORLD = [ '9' , '17' , '18' , '19' , '24' , '42' , '58' ];
    const Comedy_World = [ '3' ];
    const MUSIC_WORLD = [ '4' , '7' , '11' , '16' , '22' , '23' , '24' , '25' , '14' , '15' , '71' , '78' ];
    const ON_OF_A_KIND_WORLD = [ '1' , '2' , '6' , '8' , '13' ];
    const BROADCASTING_WORLD = [ '5' , '10' , '12' , '21' ];

    const SUBSCRIBE = [
        'Keep me up-to-date on Omneeyat™ exclusives.' => 'Keep me up-to-date on Omneeyat™ exclusives.' ,
        'Send talent updates and new releases'        => 'Send talent updates and new releases' ,
    ];
    const IN_THE_SPOT = 1;
    const IN_THE_MEDIA = 2;
    const FILMOGRAPHY = 3;
    const TALENT_SOCIAL = 4;
    const TALENT_VIDEOS = 5;
    const INSIGHTS = 6 ;
    const COLLABS = 7 ;

    const FILMOGRAPHY_TYPES = [
        0 => 'Series' ,
        1 => 'Movie'
    ];

    const INSTAGRAM_SOCIAL_MEDIA_ID = 3;
    const YOUTUBE_SOCIAL_MEDIA_ID = 6;
    const TIKTOK_SOCIAL_MEDIA_ID = 5;

    const POST_SOCIAL_TYPE = 1;
    const VIDEO_SOCIAL_TYPE = 2;

    const NOT_DEFINED_DOMAIN = 0;
    const AGENCY_SUBDOMAIN = 1;
    const MAIN_DOMIAN = 2;
    const STAGING_DOMAIN = 3;

    const COLORS = [
        '#3BBAB1' ,
        '#8461A6' ,
        '#3F3C3E' ,
        '#ff455f' ,
        '#775dd0' ,
        '#0077B5' ,
        '#c9cbcf' ,
        '#2ccdc9' ,
        '#80effe' ,
        '#00E396' ,
        '#0057ff' ,
        '#5e72e4' ,
        '#008FFB' ,
        '#feb019' ,
    ];

    const PACKAGES = [
        '1' => 'FREE' ,
        '2' => 'BOOKING ENGINE ' ,
        '3' => 'TALENTS’ INSIGHTS '
    ];


    const MONTHS = [ 'Jan' , 'Feb' , 'Mar' , 'April' , 'May' , 'Jun' , 'July' , 'Aug' , 'Sep' , 'Oct' , 'Nov' , 'Dec' ];

}

