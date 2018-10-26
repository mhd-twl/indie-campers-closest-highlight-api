<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Point;

class RoutePath extends Model
{

    protected  $startPoint ,  $endPoint ;

    function __construct($start = null ,  $end = null )
    {
    	$this->startPoint = $start ; 
    	$this->endPoint = $end ; 
        $this->distance = 
                $this->calc_distance($start->lat , $start->long , $end->lat , $end->long )  ;
    }
    public function calc_distance($lat1, $lon1, $lat2, $lon2) {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        //echo '<br/>'.$km;
        return $km;
    }

    public function check_valid_start($startPoint)
    {
        return $this->startPoint->is_exact($startPoint);
    }
    public function check_valid_end($endPoint)
    {
        return $this->endPoint->is_exact($endPoint);
    }

    public function getStartPointAttribute()
    {
    	return $this->startPoint;
    }
    public function getEndPointAttribute()
    {
    	return $this->endPoint;
    }
}













