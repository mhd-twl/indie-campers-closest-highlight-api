<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoutePath;
use App\Point;
use Session;

//use  App\Algorithems\Graph;
use  App\Algorithems\Graph\GraphDefault;



class RoutePathController extends Controller
{
    protected $points  , $routes ;         
    protected $adges ;
    protected $visited_list ,$result_arr = [] ; 

    public function create_routs()
    {
        $this->routes = collect([]);
        $this->points = collect([
            new Point(1,1), new Point(1,2),
            new Point(2,3), new Point(2,6),
            new Point(3,1), new Point(3,4), new Point(3,5),
            new Point(4,2), new Point(4,5),
            new Point(5,3), new Point(5,4),
            new Point(6,1), new Point(6,2), new Point(6,5)
        ]);
        
        for ($i=0; $i < 100; $i++) { 
            $start = $this->points->random();
            $end = $this->points->random();

            if( ! $start->is_exact($end) )
                $this->routes->push( new RoutePath($start , $end) )  ; 
       }
    }
    public function test_breadthFirstSearch($source, $origin, $destination) {

        // mark all nodes as unvisited
        foreach ($this->graph as $vertex => $adj) {
          $this->visited[$vertex] = false;
        }
       

        // create an empty queue
        $q = new \SplQueue();
        
        $q->enqueue($source);
        $this->visited[$source]  = true  ;
        
        // enqueue the origin vertex and mark as visited
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
            //dd($this->graph[$t]);
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

        return $this->go_print($path , $origin, $destination );
      }

      public function go_print($path ,$origin,  $destination )
      {
        if (isset($path[$destination])) {
          echo "$origin to $destination in ", 
            count($path[$destination]) - 1,
            " hops<br>";
          $sep = '';
          foreach ($path[$destination] as $vertex) {
            echo $sep, $vertex;
            $sep = '->';
          }
          echo "<br>";
        }
        else {
          echo "No route from $origin to $destination<br>";
        }
      }

    public function go_dfs( $graph, $visited_list, $src , $new_src,  $cost, $dst ,$lvl ) 
    {
        $contain_list = $graph[$new_src];
        if($new_src == $dst){
            dd( $graph, $visited_list, $src , $new_src,  $cost, $dst ,$lvl );
            $this->visited_list[$src][$lvl][$new_src] = $contain_list[$dst];
            return $this->visited_list;
        }
        else if(count($contain_list) == 0 ){
            $this->visited_list[$src][$lvl][$new_src] = null;
            return $this->visited_list ;
        }
        else if( isset($contain_list[$dst]) ){
            $this->visited_list[$src][$lvl][$new_src] = $contain_list[$dst];
            return $this->visited_list;
        }else{
            //dd($visited_list);
            foreach ($contain_list as $new_new_src => $new_cost) {
                $this->visited_list[$src][$lvl][$new_src] = $new_cost;
                //dd($this->visited_list[$src][$lvl][$new_src] );
                return  $this->go_dfs($graph, $visited_list, $new_src,  $new_new_src, $new_cost 
                                                                    , $dst, $lvl );
            }
        }
    }
    public function go_all($main_graph , $src , $dst )
    {
        $needed_graph =  $main_graph[$src]; $i=0;
        //$visited_list[$src] = 0 ; 
        foreach ($needed_graph as $new_src => $cost) {
            unset($main_graph[$src]) ; 
            $this->graph = $main_graph ;
            $array[$i] = $this->test_breadthFirstSearch( $src, $new_src  , $dst );
            $i++;
        }
    }
  
   
    public function print_current_routes()
    {
        // $c = $this->graph_dsf();
         //dd("ASD");
        //$this->dfs_srch();
        //$this->create_routs();
      
        $graph = array( 'A' => array('B' => 9, 'D' => 14, 'F' => 7),
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
        // $graph = array('A' => array('B', 'F'),
        //     'B' => array('A', 'D', 'E'),
        //     'C' => array('F'),
        //     'D' => array('B', 'E'),
        //     'E' => array('B', 'D', 'F'),
        //     'F' => array('A', 'E', 'C'),
        //     );
        $res = $this->go_all($graph, 'B', 'C');
        //dd($res);

        //===================
        $this->adges = [] ;
        $this->visited_list = [] ;
        //$this->check_rec_nodes($graph,'B' ,'C' );
        //dd($this->adges );
        //$this->get_all_highlights( $graph ,'B' ,'C'  );//'J' ,'E' 
        //$connect_comp = new Graph\ConnectedComponent($graph);
        //$components = $connect_comp->findConnectedComponents(); 
        //dd($components);
        
        //dd($path);
        // $g = new Graph($arr2); 
        // $graph_res = 
        // dd($graph_res);
        // dd($this->routes);
    }
    public function get_list($lat_srt , $lang_srt , $lat_end , $lang_end  ){
        $start =  new Point($lat_srt,$lang_srt) ; 
        $end   =  new Point($lat_end,$lang_end) ;  
        //$this->distance($start->lat , $start->long , $end->lat , $end->long );
        //check_valid_start($start) && check_valid_end($end);
    }

    
}

