<?php

private function getFlightRecursive( $cityTreeId, $arr ,$cityTofrom_id , $LastArrival , $var , $i ,$isFin )
    {
        
        $res = $this->getFlightValidConditionCities( $cityTofrom_id  , $LastArrival ,  $var ) ;
        
        
        //{echo "string " . $cityTofrom_id  ." , ". $LastArrival ." , ".  $var;
        //dd($arr);}
        //if($cityTofrom_id == 3 ) dd(count($res);
        if( count($res) > 0 ){
            foreach ($res  as  $fff ) {  

            $cityNewfrom_id = $fff->cityto_id ;
            $LastArrival = $fff->arrival ; 
            $arr[$cityTreeId][$cityTofrom_id ."_".$cityNewfrom_id ][$cityNewfrom_id."_".$fff->id] = $fff ;
            $bool_chk = ( end($res) == $fff ) ;
            if( $cityTreeId == 13 && $cityTofrom_id == 6  ) {

                $tt = $this->getFlightRecursive($cityTofrom_id ,
                  $arr ,$cityNewfrom_id , $LastArrival ,  $var , $i+1 , end($res) == $fff );
                echo "$cityTofrom_id , $cityNewfrom_id , " ;
                echo " $LastArrival ,  $var , $i+1 , $bool_chk " ;
                dd( $tt  );
            }
            // if(end($res) == $fff)
            // dd($arr);
            //if($cityTreeId == 1 && $cityTofrom_id  == 3 ) dd( $arr  ) ;
            return $this->getFlightRecursive($cityTofrom_id ,
                  $arr ,$cityNewfrom_id , $LastArrival ,  $var , $i+1 , end($res) == $fff );
            }
        }
        else{
            if ( $isFin )
            {
                if($i > 1 )
                    $arr[$cityTreeId]["stops"] = $i;
              
                return $arr ;
            }
        }
    }
    public function getEachFlightList($type)  
    {
     
        if( $type =='price')
                $var = 'base_price' ;
            else if( $type =='time')
                 $var = 'sec_duration' ;
            else  
                return [] ;
        $main = [];
            $flights = $this->getFlightSeatAvailableAllCity()->get(); 
            $cityToIdsAll = [];  
            foreach ($flights as $flight) {
                $list = $this->getFlightValidConditionFromCity($flight->cityfrom_id , $var);  
                if(count($list) > 0 )
                $fltrd_main[$flight->cityfrom_id ] =  $list;
            }
            $results = [];
            $results_lst = []; $r = [];
        ///------------- For --------
        //dd($fltrd_main);
        foreach ( $fltrd_main as $cityfromId => $arrival_list ) {
            foreach ($arrival_list as $flyt ) { 


                //$bool = 
                //$results[$cityfromId][$flyt->cityto_id."__".$flyt->id]  = $flyt;
                $results[$cityfromId][$flyt->cityto_id."_".$flyt->id]
                = $this->getFlightRecursive( $cityfromId , $results , 
                    $flyt->cityto_id , $flyt->arrival ,  $var  , 1 , false ) ;   //  $flyt->cityto_id
            }
           
        }
        echo " ++++ ";
        dd($results);
        dd($results_lst);
        foreach ($results as  $flyt) {
            $fs = new FlightSuggestion;
            $fs->cityfrom_id = $cityfromId ;
            $fs->cityto_id = $flyt->cityto_id ;
            // dd($res);
            $all_time = $res->sec_duration +  $flyt->sec_duration + $res->diff ;
            $fs->duration = gmdate("H:i:s", $all_time);
            $fs->num_stops = 1 ; // $fs->total_cost = 
            $fs->save();
            // // ======== 
            $fsd = new FlightSuggestionDetail ; 
            $fsd->flight1st_id = $flyt ->id ;
            $fsd->flight2nd_id = $res->id ; 
            $fsd->waiting_time = gmdate("H:i:s", $res[$diff]);
            $fsd->flight_suggestion_id = $fs->id;
            $fsd->save();
        }

        // echo "<pre>";
        // print_r($results);
        dd("----------------");
        //return $fltrd_main 

    }


      public function getEachFlightListDJKS($type) // time , price
    {
        if( $type =='price')
            $var = 'base_price' ;
        else if( $type =='time')
             $var = 'sec_duration' ;
        else // not price  --> time
            return [] ;
        $main = [];
        $flights = $this->getFlightSeatAvailableAllCity()->get(); 
        // getFlightSeatAvailableDepartureCityOnly()->get();
        $cityToIdsAll = []; //."-". $fly->id ;
        foreach ($flights as $flight) {
            $list = $this->getFlightValidConditionFromCity($flight->cityfrom_id , $var);  
            $fl=[]; $cityToIds = []; //$datalist = [];
            foreach ($list as  $fly) {
                if( ! in_array( $fly->cityto_id, $cityToIds ))
                {
                    $b_name = $fly->cityto_id  ; //."_city"; 
                    $fl[$b_name] =  $fly->$var;
                    
                    array_push($cityToIdsAll, [ 'cityto' => $b_name , 
                        'cityfrom' => $flight->cityfrom_id ,'diff' => $fly->$var ]);
                }
            }
            $main[$flight->cityfrom_id ] =  $fl  ; // ."_city" ]
        }
        $fltrd_main =  array_filter($main);
        /// Here $main has from array city list. dd($main) ;
        ///// To fill data for reversed from array 
        //// it will be needed to active dijkstra garph
        $toVisted = []; 
        $toVistedcity = [];
        $fromVistedcity = [];
        foreach ($cityToIdsAll as $to) {
            $flgt = [] ;
            $flgt[ $to['cityfrom'] ] = $to['diff'];
            if(  ! in_array( $to['cityto'] , $toVistedcity )){
                $toVisted[ $to['cityto'] ] = $flgt; 
                //$main[ $to['cityto'] ] = $flgt; 
                array_push( $toVistedcity    , $to['cityto'] ) ;
                array_push( $fromVistedcity    , $to['cityfrom'] ) ;
                
            } else{
                if(  ! in_array( $to['cityfrom'] , $fromVistedcity ) ){
                    $toVisted[ $to['cityto'] ][ $to['cityfrom'] ] = $to['diff'];
                    //$main[ $to['cityto'] ][ $to['cityfrom'] ] =  $to['diff'];
                }
            }
        }
        
        $all = $toVisted;
        foreach ($fltrd_main as $key => $value) {
             $all[$key] = $value;
        }
        
        foreach ($all as $key => $value) {
            $subKeys = array_keys($value);
            foreach ($subKeys as  $subKy) {
                if( ! isset($all[ $subKy] [ $key] ) ) {
                    $all[ $subKy] [ $key]  = $all [ $key] [ $subKy];
                }

            }
        }
        //dd($all);
        return $all;
    }
       