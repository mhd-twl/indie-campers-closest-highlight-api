<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoutePath;
use App\Point;

use  App\Algorithems\Graph\Dijkstra;
use  App\Http\Resources\RoutePathResource;

class RoutePathController extends Controller
{
    protected $graph , $map_points = [] ; 

    /* =================== Initiolization Methods =================== */
    public function __construct(){ 
        /* The Graph routes definition with sample path cost */
        $this->graph 
            = array('A' => array('B' => 9, 'D' => 14, 'F' => 7),
                    'B' => array('A' => 9, 'C' => 11, 'D' => 2, 'F' => 10),
                    'C' => array('B' => 11, 'E' => 6, 'F' => 15),
                    'D' => array('A' => 14, 'B' => 2, 'E' => 9),
                    'E' => array('C' => 6, 'D' => 9),
                    'F' => array('A' => 7, 'B' => 10, 'C' => 15),
                    'G' => array(),
                    'H' => array('E' => 7, 'C' => 2),
                    'I' => array('H' => 7, 'C' => 3 , 'B' => 15),
                    'J' => array('F' => 7, 'I' => 12),
                );
        $this->create_point_list();
        $this->create_route_paths();}
    /* filling $this->map_points up as public variable */
    private function create_point_list(){
        $this->map_points['A'] = new Point( 'A', 44.968  ,  -94.420 );
        $this->map_points['B'] = new Point( 'B', 43.333  ,  -89.132 );
        $this->map_points['C'] = new Point( 'C', 33.755  ,  -116.359 );
        $this->map_points['D'] = new Point( 'D', 33.844  ,  -116.549 );
        $this->map_points['E'] = new Point( 'E', 44.920  ,  -93.447 );
        $this->map_points['F'] = new Point( 'F', 44.240  ,  -91.493 );
        $this->map_points['G'] = new Point( 'G', 44.968  ,  -94.419 );
        $this->map_points['H'] = new Point( 'H', 44.333  ,  -89.132 );
        $this->map_points['I'] = new Point( 'I', 33.755  ,  -116.360 );
        $this->map_points['J'] = new Point( 'J', 33.844  ,  -117.549 );}
    /* re-filling $this->graph with assigned path-cost */
    private function create_route_paths() { 
        /* Creating Route Path between nodes */     
        foreach ($this->graph as $key => $routes) {
            foreach ($routes as $single_key => $single_route ) { 
                $route = new RoutePath($this->map_points[$key],$this->map_points[$single_key] 
                );
                $this->graph[$key][$single_key] = $route->distance ; 
            }
        }}

    /* =================== Searching Methods =================== */
    /* return the node label given lat & long */
    private function find_point_label($lat , $long){
        foreach ($this->map_points as $key => $node) {
            if($node->lat ==  $lat && $node->long ==  $long ) 
                return  $key;
        }
        return null;}
    ///------------ All Highlights ----------------
    private function get_all_hightlights($src = "B" , $dst = "C" ){
        $main_graph = $this->graph ; 
        $needed_graph =  $main_graph[$src]; $i=0; $array=[];
        foreach ($needed_graph as $new_src => $cost) {
            unset($main_graph[$src]) ; 
            $this->graph = $main_graph ;
            $array[$i] = $this->bfs_least_hops( $src, $new_src  , $dst );
            $i++;
        }
        $destination = $dst ; $origin = $src ;
        $reaching_list = []; $i=0;
        foreach ($array as  $path) {
            $vrtx_list = [] ;
             if (isset($path[$destination])) {
                array_push($vrtx_list , $src) ; 
              foreach ($path[$destination] as $vertex) {
                array_push($vrtx_list , $vertex) ; 
              }
              $reaching_list[$i] = $vrtx_list ; $i++;
            }
            else {
                $reaching_list[$i]  =  null  ; 
            }
        }
        return $reaching_list;}
    private function bfs_least_hops($source, $origin, $destination) {
        // mark all nodes as unvisited
        foreach ($this->graph as $vertex => $adj) {
          $this->visited[$vertex] = false;
        }
        // create an empty queue
        $q = new \SplQueue();
        // enqueue the origin vertex and mark as visited       
        $q->enqueue($source);
        $this->visited[$source]  = true  ;
        $q->enqueue($origin);
        $this->visited[$origin] = true;

        // this is used to track the path back from each node
        $path = array();

        $path[$source] = new \SplDoublyLinkedList();
        $path[$source]->setIteratorMode(
          \SplDoublyLinkedList::IT_MODE_FIFO|\SplDoublyLinkedList::IT_MODE_KEEP
        );

        $path[$origin] = new \SplDoublyLinkedList();
        $path[$origin]->setIteratorMode(
          \SplDoublyLinkedList::IT_MODE_FIFO|\SplDoublyLinkedList::IT_MODE_KEEP
        );

        $path[$source]->push($source);
        $path[$origin]->push($origin);

        $found = false;
        // while queue is not empty and destination not found
        while (!$q->isEmpty() && $q->bottom() != $destination) {
          $t = $q->dequeue();

          if (!empty($this->graph[$t]) && is_array($this->graph[$t])) {  
            // for each adjacent neighbor
            foreach ($this->graph[$t] as  $vertex => $cost ) {
              if (!$this->visited[$vertex]) {
                // if not yet visited, enqueue vertex and mark
                // as visited
                $q->enqueue($vertex);
                $this->visited[$vertex] = true;  
                // add vertex to current path
                $path[$vertex] = clone $path[$t];
                $path[$vertex]->push($vertex);
              }
            }
          }
        }
        return  $path  ;
      }
    ///------------ Shortest Path ----------------
    private function dijkstra_shortest_path($source = "J" , $destination  = "C") {
       $dj = new Dijkstra($this->graph);
       $result = $dj->shortestPaths($source, $destination , []);
       return $result;} 

    /* =================== API Methods =================== */

    public function show_highlights($point1 , $point2)
    {
        if($point1== $point2) return $this->apiResponse([],"Start is the end point!" ,200);  
        if(isset($this->graph[$point1]) && isset($this->graph[$point2]) ) {
            $result= $this->get_all_hightlights($point1 , $point2);
            return $this->apiResponse(  $result, null ,200);
        }else{
            return $this->apiResponse([],"Points not found.." ,404);    
        }
    }
    public function shortest_route($point1 , $lat2 , $long2)
    {
        $point2 = $this->find_point_label($lat2 , $long2);
        if(isset($this->graph[$point2])){
            if(isset($this->graph[$point1]) ) {
                $result= $this->dijkstra_shortest_path($point1 , $point2);
                return $this->apiResponse(  $result, null ,200);
            }else{
                return $this->apiResponse([]," Your point is not found.." ,404);    
            }
        }else{
            return $this->apiResponse([],"lat or long is not defind in graph" ,404);   
        }
    }
    public function show_geo_diff($lat1 , $long1, $lat2 , $long2)
    {
        $routepath = new RoutePath;
        $point1 = $this->find_point_label($lat1 , $long1);
        $point2 = $this->find_point_label($lat2 , $long2);
        if(isset($point1) && isset($point1))
        {
                if($point1== $point2) return $this->apiResponse([],"Start is the end point!" ,200);  
                if(isset($this->graph[$point1]) && isset($this->graph[$point2]) ) {
                    $result= [
                        $point1 => [ "Lat: "  $this->graph[$point1]->lat ,
                                     "Long: " $this->graph[$point1]->long ],
                        $point2 => [ "Lat: "  $this->graph[$point2]->lat ,
                                     "Long: " $this->graph[$point2]->long ],
                        "Distance in KM" => $routepath->calc_distance(
                                                $lat1 , $long1, $lat2 , $long2) ];
        
                    return $this->apiResponse(  $result, null ,200);
                }else{
                    return $this->apiResponse([],"Points not found.." ,404);    
                }
        }else{
            $result= [
                        "point1" => [ "Lat: "  $lat1 ,
                                     "Long: " $long1 ],
                        "point2" => [ "Lat: "  $lat2 ,
                                     "Long: " $long2 ],
                        "Distance in KM" => $routepath->calc_distance(
                                                $lat1 , $long1, $lat2 , $long2) ];
            return $this->apiResponse(  $result, null ,200);
        }
    }
    ///------------ api helpers
    private function successCode() {return [200, 201, 202]; }
    private function apiResponse($data = null  , $error  = null , $code = 200 ){
        $array = [
            'data' => $data ,
            'status' => in_array(  $code , $this->successCode() )   ? true : false,
            'error' => $error
            
        ];
        return response( $array , $code) ;}
    
}

 