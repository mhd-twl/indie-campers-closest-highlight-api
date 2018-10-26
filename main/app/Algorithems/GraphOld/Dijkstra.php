<?php
namespace App\Algorithems\Graph;

class Dijkstra
{
  protected $graph;

  public function __construct($graph) {
    $this->graph = $graph;
  }

  public function shortestPath($source, $target , $flight ,$cost_type) {
    // array of best estimates of shortest path to each
    // vertex
    $d = array();
    // array of predecessors for each vertex
    $pi = array();
    // queue of all unoptimized vertices
    $Q = new \SplPriorityQueue();

    foreach ($this->graph as $v => $adj) {
      $d[$v] = INF; // set initial distance to "infinity"
      $pi[$v] = null; // no known predecessors yet
      foreach ($adj as $w => $cost) {
        // use the edge cost as the priority
        //dd($this->graph);
        //dd($v ." - ". $w );
        //dd($v );
        //if( $flight->isGraphSourceValidateFlight( $v , $w  , $cost  , $cost_type ) )
        {
          //dd($v );
          $Q->insert($w, $cost);
        }
      }
    }
    //dd($source);
    // initial distance at source is 0
    $d[$source] = 0;

    while (!$Q->isEmpty()) {

      // extract min cost
      $u = $Q->extract();
      
      if (!empty($this->graph[$u])) {
        // "relax" each adjacent vertex
        foreach ($this->graph[$u] as $v => $cost) {
          // alternate route length to adjacent neighbor
          //if( isset( $d[$v]) ) {
            $alt = $d[$u] + $cost;
            // if alternate route is shorter
            if (  $alt < $d[$v]) { //isset( $d[$v]) && 
              $d[$v] = $alt; // update minimum length to vertex
              $pi[$v] = $u;  // add neighbor to predecessors
                             //  for vertex
          //  }
          }
        }
      }
    }

    // we can now find the shortest path using reverse
    // iteration
    $S = new \SplStack(); // shortest path with a stack
    $u = $target;
    $dist = 0;
    // traverse from target to source
    while (isset($pi[$u]) && $pi[$u]) {
      $S->push($u);
      $dist += $this->graph[$u][$pi[$u]]; // add distance to predecessor
      $u = $pi[$u];
    }

    // stack will be empty if there is no route back
    if ($S->isEmpty()) {
      //echo "No route from $source to $target";
      return [] ; 
    }
    else {
      // add the source node and print the path in reverse
      // (LIFO) order
      $S->push($source);
      $res_array[0] = $dist ;
      $points = [] ;
      //array_push(    , 
      //echo "$dist:";
      $sep = '';
      foreach ($S as $v) {
        array_push($points, $v);
        // echo $sep, $v;         $sep = '->';
      }
      $res_array[1] =$points ; 
      return  $res_array ;
      //echo "n";
    }
  }
}