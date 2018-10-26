<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected  $lbl, $lat ,  $long ;

    function __construct($lbl = null, $lat = null,  $long= null )
    {
        $this->lbl = $lbl;
    	$this->lat = $lat ; 
    	$this->long = $long ; 
    }

    public function is_exact($point2)
    {
    	if( $this->lat == $point2->lat && $this->lang == $point2->lang) 
    		return true;
    	return false;
    }

    public function getLblAttribute()
    {
        return $this->lbl;
    }
    public function getLatAttribute()
    {
    	return $this->lat;
    }
    public function getLongAttribute()
    {
    	return $this->long;
    }
}













