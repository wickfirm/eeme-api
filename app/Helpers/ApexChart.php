<?php

namespace App\Helpers;

use Illuminate\Support\Facades\View;

class ApexChart {
    /*
        |--------------------------------------------------------------------------
        | Chart
        |--------------------------------------------------------------------------
        |
        | This class build the chart by passing setters to the object, it will
        | use the method container and scripts to generate a JSON
        | in blade views, it works also with Vue JS components
        |
        */

    public    $id;
    protected $series;
    protected $chart;
    protected $legend;
    protected $dataLabels;
    protected $fill;
    protected $stroke;
    protected $xAxis;
    protected $yAxis;
    protected $states;
    protected $tooltip;
    protected $grid;
    protected $markers;


    protected $title;
    protected $type         = 'donut';
    protected $labels;
    protected $dataset;
    protected $height       = 350;
    protected $colors;
    protected $horizontal;
    private   $chartLetters = 'abcdefghijklmnopqrstuvwxyz';

    public function __construct() {
        $this->id     = substr( str_shuffle( str_repeat( $x = $this->chartLetters , ceil( 25 / strlen( $x ) ) ) ) , 1 , 25 );
        $this->colors = json_encode( [
            '#008FFB' ,
            '#00E396' ,
            '#feb019' ,
            '#ff455f' ,
            '#775dd0' ,
            '#80effe' ,
            '#0077B5' ,
            '#ff6384' ,
            '#c9cbcf' ,
            '#0057ff' ,
            '00a9f4' ,
            '#2ccdc9' ,
            '#5e72e4'
        ] );

        return $this;
    }

    public function setChart( $chart ) {
        $this->chart = $chart;

        return $this;
    }

    public function showLegend( $legend = true ) {
        $this->legend = [ 'show' => $legend ];

        return $this;
    }

    public function showDataLabels( $data_labels = true ) {
        $this->legend = [ 'enabled' => $data_labels ];

        return $this;
    }

    public function setFill( $fill ) {
        $this->fill = $fill;

        return $this;
    }

    public function setStates( $states ) {
        $this->states = $states;
    }

    public function setTooltip( $tooltip ) {
        $this->tooltip = $tooltip;
    }

    public function setType( $type = null ) {
        $this->type = $type;

        return $this;
    }

    public function setDataset( $dataset ) {
        $this->dataset = json_encode( $dataset );

        return $this;
    }

    public function setHeight( int $height ) {
        $this->height = $height;

        return $this;
    }

    public function setMarkers( $markers ) {
        $this->markers = $markers;
    }

    public function setColors( array $colors ) {
        $this->colors = json_encode( $colors );

        return $this;
    }

    public function setHorizontal( bool $horizontal ) {
        $this->horizontal = json_encode( [ 'horizontal' => $horizontal ] );

        return $this;
    }

    public function setLabels( array $labels ) {
        $this->labels = $this->transformLabels( $labels );

        return $this;
    }

    public function setXAxis( $xAxis ) {
        //array $categories
//		$this->xAxis = json_encode( $categories );
//		return $this;

        $this->xAxis = $xAxis;

        return $this;
    }

    public function setYAxis( $yAxis ) {
        //array $categories
//		$this->xAxis = json_encode( $categories );
//		return $this;

        $this->yAxis = $yAxis;

        return $this;
    }

    public function setGrid( $grid ) {
        $this->grid = $grid;

        return $this;
    }

    public function setStroke( int $width , array $colors = [ '#fff' ] ) {
        $this->stroke = json_encode( [
            'curve'  => 'smooth' ,
            'show'   => true ,
            'width'  => $width ,
            'colors' => $colors
        ] );

        return $this;
    }

    public function transformLabels( array $array ) {
        $stringArray = array_filter( $array , function ( $string ) {
            return "{$string}";
        } );

        return '"' . implode( '","' , $stringArray ) . '"';
    }

    public function container() {
//		return View::make( 'larapex-charts::chart.container', [ 'id' => $this->id() ] );
    }

    public function script() {
        if ( $this->stroke ) {
//			return View::make( 'larapex-charts::chart.script-with-stroke', [ 'chart' => $this ] );
        }
//		return View::make( 'larapex-charts::chart.script', [ 'chart' => $this ] );
    }

    public function cdn() {
//		return 'https://cdn.jsdelivr.net/npm/apexcharts';
    }

    /**
     * @return false|string
     */
    public function id() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function type() {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function labels() {
        return $this->labels;
    }

    /**
     * @return mixed
     */
    public function dataset() {
        return $this->dataset;
    }

    /**
     * @return int
     */
    public function height() {
        return $this->height;
    }

    /**
     * @return false|string
     */
    public function colors() {
        return $this->colors;
    }

    /**
     * @return false|string
     */
    public function horizontal() {
        return $this->horizontal;
    }

    /**
     * @return mixed
     */
    public function xAxis() {
        return $this->xAxis;
    }

    /**
     * @return false|string
     */
    public function grid() {
        return $this->grid;
    }

    /**
     * @return mixed
     */
    public function stroke() {
        return $this->stroke;
    }

    public static function create_general_chart( $chart_type , $chart_title , $apex_chart_div_name , $data , $height = 350 , $others = true , $limit = 4 ) {

        $apex_chart = new ApexChart();

        $labels = [];
        $values = [];

        [ $new_data , $labels , $values ] = self::aggregate_result( $data , $labels , $values , $others , $limit );

        $apex_chart_js = view( 'apex-chart.general_chart' , compact( 'chart_type' , 'chart_title' , 'height' , 'apex_chart_div_name' , 'labels' , 'values' ) )->render();

        return [
            'id'    => $apex_chart->id ,
            'chart' => $apex_chart_js
        ];
    }

    public static function create_bar_chart_old( $per_solution_monetized , $per_solution_received ) {

        $apex_chart = new ApexChart();

        $monetized_label = explode( "," , $per_solution_monetized[ 'labels' ] );
        $received_label  = explode( "," , $per_solution_received[ 'labels' ] );

        $monetized_values = explode( "," , $per_solution_monetized[ 'values' ] );
        $received_values  = explode( "," , $per_solution_received[ 'values' ] );

        $label_merge = array_values( array_unique( array_merge( $monetized_label , $received_label ) ) );

        $monetized_new_values = [];
        $received_new_values  = [];

        foreach ( $label_merge as $label ) {
            $monetized_search = array_search( $label , $monetized_label );

            array_push( $monetized_new_values , $monetized_values[ $monetized_search ] );

            $received_search = array_search( $label , $received_label );

            array_push( $received_new_values , $received_values[ $received_search ] );
        }

        $apex_chart_js = View::make( 'apex-chart.column' , [
            'chart'      => $apex_chart ,
            'labels'     => $label_merge ,
            'column_one' => $monetized_new_values ,
            'column_two' => $received_new_values
        ] )->render();

        return [
            'id'    => $apex_chart->id ,
            'chart' => $apex_chart_js
        ];
    }

    public static function create_single_bar_chart( $labels , $values ) {
        $apex_chart = new ApexChart();

        $apex_chart_js = View::make( 'apex-chart.bar' , [
            'chart'  => $apex_chart ,
            'labels' => $labels ,
            'values' => $values
        ] )->render();

        return [
            'id'    => $apex_chart->id ,
            'chart' => $apex_chart_js
        ];
    }

    public static function create_pie_chart( $title , $data , $others = true , $limit = 4 ) {
        $apex_chart = new ApexChart();

        if ( empty ( $data ) ) {
            return [ 'id' => $apex_chart->id ];
        }
        else {
            $labels = [];
            $values = [];

            $apex_chart_id = '#' . $apex_chart->id;

            [ $new_data , $labels , $values ] = self::aggregate_result( $data , $labels , $values , $others , $limit );

            $colors = Gru::COLORS;

//            $apex_chart_js = view( 'apex-chart.pie' , compact( 'title' , 'colors' , 'apex_chart_id' , 'labels' , 'values' ) )->render();
            return ['colors' => $colors , 'title' => $title , 'labels' => $labels , 'values' => $values, 'apex_chart_id' => $apex_chart_id] ;


//            return [
//                'id'    => $apex_chart->id ,
//                'chart' => $apex_chart_js
//            ];
        }
    }

    public static function create_bar_chart( $title , $data , $others = true , $limit = 4 ) {

        $apex_chart = new ApexChart();

        $labels        = [];
        $values        = [];
        $colors        = Gru::COLORS;
        $apex_chart_id = '#' . $apex_chart->id;

        [ $new_data , $labels , $values ] = self::aggregate_result( $data , $labels , $values , $others , $limit );

//        $apex_chart_js = view( 'apex-chart.bar' , compact( 'colors' , 'title' , 'apex_chart_id' , 'labels' , 'values' ) )->render();

        return ['colors' => $colors , 'title' => $title , 'labels' => $labels , 'values' => $values, 'apex_chart_id' => $apex_chart_id] ;

//        return [
//            'id'    => $apex_chart->id ,
//            'chart' => $apex_chart_js
//        ];
    }
    public static function create_bar_vertical_chart( $title , $data , $others = true , $limit = 4 ) {

        $apex_chart = new ApexChart();

        $labels        = [];
        $values        = [];
        $colors        = Gru::COLORS;
        $apex_chart_id = '#' . $apex_chart->id;

        [ $new_data , $labels , $values ] = self::aggregate_result( $data , $labels , $values , $others , $limit );

//        $apex_chart_js = view( 'apex-chart.bar-vertical' , compact( 'colors' , 'title' , 'apex_chart_id' , 'labels' ,
//            'values' ) )->render();
        return ['colors' => $colors , 'title' => $title , 'labels' => $labels , 'values' => $values, 'apex_chart_id' => $apex_chart_id] ;

//        return [
//            'id'    => $apex_chart->id ,
//            'chart' => $apex_chart_js
//        ];
    }

    public static function create_area_chart( $title , $data , $categories = Gru::MONTHS ) {
        $apex_chart    = new ApexChart();
        $apex_chart_id = '#' . $apex_chart->id;

        $labels = [];
        $values = [];

        $colors = Gru::COLORS;

        foreach ( $data as $key => $datum ) {
            array_push( $labels , $key );
            array_push( $values , $datum );
        }


        return ['colors' => $colors , 'categories' => $categories , 'title' => $title , 'labels' => $labels , 'values' => $values, 'apex_chart_id' => $apex_chart_id] ;

    }

    public static function create_chart( $name , ...$data ) {
        if ( count( $data ) > 0 ) {
            $apex_chart = new ApexChart();

            $apex_chart_js = View::make( 'apex-chart.line' , [
                'chart' => $apex_chart ,
                'data'  => $data[ 0 ] ,
                'name'  => $name
            ] )->render();

            return [
                'id'    => $apex_chart->id ,
                'chart' => $apex_chart_js
            ];
        }

        return [];
    }

    public static function create_single_bar_vertical_chart( $data ) {
        $apex_chart = new ApexChart();

        $labels = [];
        $values = [];

        $new_data = self::aggregate_result( $data , $labels , $values );

//		$apex_chart_js = View::make( 'apex-chart.bar_vertical', [ 'chart' => $apex_chart, 'labels' => $labels, 'values' => $values ] )->render();

        return [
            'id'    => $apex_chart->id ,
            'chart' => $new_data
        ];
    }

    public static function create_single_column_chart( $data ) {
        $apex_chart = new ApexChart();
        $labels     = explode( "," , $data[ 'labels' ] );
        $values     = explode( "," , $data[ 'values' ] );
//
//        $new_values = [];
//
//        foreach ( array_values($labels) as $label ) {
//
//            $search = array_search( $label, $labels );
//            array_push( $new_values, $values[ $search ] );
//        }
//
//        $apex_chart_js = View::make( 'apex-chart.column', [ 'chart' => $apex_chart, 'labels' => array_values($labels), 'column_one' => $new_values,'column_two' => []] )->render();

        $new_data = [];
        for ( $i = 0 ; $i < count( $labels ) ; $i ++ ) {
            $new_data[ $labels[ $i ] ] = $values[ $i ];
        }

        return [
            'id'    => $apex_chart->id ,
            'chart' => $new_data
        ];
    }

    public static function create_chart_demo( ...$data ) {
        if ( count( $data ) > 0 ) {
            $apex_chart = new ApexChart();

            $categories = $data[ 0 ][ 'data' ][ 'labels' ];

            $series = [];

            foreach ( $data as $datum ) {
                if ( $datum[ 'data' ][ 'values' ] != '' ) {
                    $temp_arr = [
                        "name" => $datum[ 'name' ] ,
                        "data" => explode( "," , $datum[ 'data' ][ 'values' ] )
                    ];
                    array_push( $series , $temp_arr );
                }
            }
            $series = json_encode( $series );
            $series = str_replace( "\"name\"" , "name" , $series );
            $series = str_replace( "\"data\"" , "data" , $series );

            $apex_chart_js = View::make( 'apex-chart.line-multiple' , [
                'chart'      => $apex_chart ,
                'series'     => $series ,
                'categories' => $categories
            ] )->render();

            return [
                'id'    => $apex_chart->id ,
                'chart' => $apex_chart_js
            ];
        }

        return [];
    }

    public static function aggregate_result( $data , &$labels , &$values , $others = true , $limit = 4 ) {
        $count = 0;
        $other = 0;

//        if (!$others) {
//            $limit = count($data);
//        }

        foreach ( $data as $key => $datum ) {
            if ( $count < $limit ) {
                array_push( $labels , $key );
                array_push( $values , $datum );
                $count ++;
            }
            else {
                if ( ! $others ) {
                    break;
                }

                $other += $datum;
            }
        }

        if ( $others ) {
            if ( $other > 0 ) {
                array_push( $labels , 'Others' );
                array_push( $values , $other );
            }
        }

        $new_data = [];

        for ( $i = 0 ; $i < count( $labels ) ; $i ++ ) {
            $new_data[ $labels[ $i ] ] = $values[ $i ];
        }

        return [ $new_data , $labels , $values ];
    }

    public static function createMonthlyChart( $data ) {
//		$labels     = [];
//		$success    = [];
//		$error      = [];
//		$pending    = [];
//		$authorized = [];
//
//		$months = self::monthsArray();
//
//		foreach ( $months as $month ) {
//			array_push( $labels, $month );
//
//			array_push( $success, $data['success'] );
//			array_push( $error, $data['error'] );
//			array_push( $pending, $data['pending'] );
//			array_push( $authorized, $data['authorized'] );
//		}
//
//		$success_chart_data = [
//			'name' => 'Success',
//			'data' => [
//				'labels' => $labels,
//				'values' => implode( ",", $success )
//			]
//		];
//
//		$error_chart_data      = [];
//		$pending_chart_data    = [];
//		$authorized_chart_data = [];
//
//
//		$chart = ApexChart::create_chart_demo( $success_chart_data, $error_chart_data, $pending_chart_data, $authorized_chart_data );

//		foreach ( $per_date as $date => $values ) {
//			array_push( $labels, $date );
//
//			array_push( $impressions, $values[ CampaignMap::IMPRESSIONS_KEY ] );
//			array_push( $clicks, $values[ CampaignMap::CLICKS_KEY ] );
//
//			if ( $is_video ) {
//				array_push( $views, $values[ CampaignMap::V0_KEY ] );
//			}
//		}
//
//		$impressions_chart_data = [
//			'name' => 'Impressions',
//			'data' => [
//				'labels' => $labels,
//				'values' => implode( ",", $impressions )
//			]
//		];
//		$clicks_chart_data      = [
//			'name' => 'Clicks',
//			'data' => [
//				'labels' => $labels,
//				'values' => implode( ",", $clicks )
//			]
//		];
//		$views_chart_data       = [
//			'name' => 'Views',
//			'data' => [
//				'labels' => $labels,
//				'values' => implode( ",", $views )
//			]
//		];
//
//		$labels                                 = implode( ",", $labels );
//		$result['performance_chart']            = ApexChart::create_chart_demo( $impressions_chart_data, $clicks_chart_data, $views_chart_data );
//		$result[ CampaignMap::IMPRESSIONS_KEY ] = ApexChart::create_chart( 'Impressions', [
//			'labels' => $labels,
//			'values' => implode( ",", $impressions )
//		] );
//		$result[ CampaignMap::CLICKS_KEY ]      = ApexChart::create_chart( 'Clicks', [
//			'labels' => $labels,
//			'values' => implode( ",", $clicks )
//		] );
//		$result[ CampaignMap::VIEWS_KEY ]       = ApexChart::create_chart( 'Views', [
//			'labels' => $labels,
//			'values' => implode( ",", $views )
//		] );
//
//		return $result;
    }
}
