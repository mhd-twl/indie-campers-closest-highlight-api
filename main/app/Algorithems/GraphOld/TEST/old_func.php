<? class graph { 
    private function get_all_highlights( $graph ,$point1,$point2)
    {
        $excluding_arr = [];
        $highlights = collect([]);
        $dijkstra = new GraphDefault\Dijkstra($graph); 
        $short_path_points = $dijkstra->shortestPath($point1,$point2); 
        dd($short_path_points);
        //$short_path_points = $dijkstra->shortestPaths($point1,$point2,  $excluding_arr); 
        // $short_path_points2 = $dijkstra->getAllPaths($point1,$point2, $excluding_arr ); 
        // dd($short_path_points2);
        $j = 0 ; 
        foreach ( $short_path_points as $arr ) {
            $highlights[$j] = $arr;
            if(count($arr) > 3 ){
                for ($i=0; $i <= count($arr)-1 ; $i++) { $j++;
                    if(isset($arr[$i+1]))
                    { $excluding_arr[$i] = $arr[$i+1];
                     $filtered_points = $dijkstra
                                ->shortestPaths($point1,$point2,  $excluding_arr); 
                    if(count ($filtered_points) > 1 )
                    {   for ($i=0; $i <  count ($filtered_points); $i++) { 
                            $highlights[$j] = array_flatten($filtered_points[$i]);
                            $j++;
                        }
                    }else
                        $highlights[$j] =  $filtered_points;
                    }
                }
            } 
        }
        dd($highlights);
    }
  
    public function check_rec_nodes($array, $src , $dst)
    {
        if( ! in_array(   $src , $this->visited_list  )){
            if($src == $dst || count( $array) == 0){
                return null ;
            }else{
                foreach ($array as $key => $value) {
                    $this->visited_list[] = $key;
                    $this->adges[$key] = [$array[$key] , $value]   ;
                    print_r(   $this->visited_list );
                    return $this->check_rec_nodes($array, $key  , $dst);
                }
               
            }
        }
    }
    public function dfs_OLD(Node $node, $path = '', $visited = array())
    {
        $visited[] = $node->name;
        $not_visited = $node->not_visited_nodes($visited);
        if (empty($not_visited)) {
            echo 'path : ' . $path . '->' . $node->name . PHP_EOL . '<br/>'; 
             $result_arr[count($result_arr)] = $node->name;
            return;
        }
        foreach ($not_visited as $n) $this->dfs($n, $path . '->' . $node->name, $visited);
    }
    public function graph_dsf()
    {
        $this->result_arr = [] ; 
        /* Building Graph */
        $root = new Node('root');
        foreach (range(1, 6) as $v) {
                $name = "node{$v}";
                $$name = new Node($name);
        }
        $root->link_to($node1)->link_to($node2);
        $node1->link_to($node3)->link_to($node4);
        $node2->link_to($node5)->link_to($node6);
        $node4->link_to($node5);
        /* Searching Path */
       
        $this->dfs($root);
        return $result_arr ;
    }
    public function rec_path($graph , $s , $d)
    {
        if(is_array($graph))
        foreach($graph as $key => $value) {
            if( ! in_array(   $key , $this->visited_list  )){
                $this->visited_list[] = $key;
                $this->adges[$key] = [$graph[$key] , $value]   ;
                $this->rec_path($graph[$key] , $key , $d) ;
            } 
        }
    }


  public function test_bfgsS(){


    }




   //  private $api_url = "https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data";

   //  public function cancel()
   //  {
   //      Session()->forget('cur_customer');
   //      return redirect('/');
   //  }
   //  public function continue( Request $request )
   //  {
   //      $customer = Session::get('cur_customer');
   //      $step = 1;
   //      if($customer)
   //      { 
   //          $step = $customer->last_step  ;
   //          $step = (int) $step + 1 ; 
   //          return view('registration.step' . $step , compact('customer'));
   //      }else{
   //          return  $this->register(1, $request );
   //      }
   //  }
   //  public function register($step = 1 , Request $request )
   //  {
        
   //      $customer = null ;
   //      switch ($step) {
   //          case '1':
   //               Session::forget('cur_customer'); 
   //              break;
   //          case '2':
   //              if($request->firstname)
   //              {
   //                 $customer = new Customer;
   //                 $customer->status = 0;
   //                 $customer->firstname = $request->firstname ;
   //                 $customer->lastname = $request->lastname ;
   //                 $customer->telephone = $request->telephone ;
   //                 $customer->last_step = $request->last_step ;
   //                 Session::put('cur_customer', $customer);
   //              }
   //              break;

   //          case '3':   
   //              if($request->address)
   //              {
   //                  $customer = Session::get('cur_customer');
   //                  $customer->city  = $request->city  ;
   //                  $customer->address  = $request->address  ;
   //                  $customer->street  = $request->street  ;
   //                  $customer->house_no  = $request->house_no  ;
   //                  $customer->zip_code  = $request->zip_code  ;
   //                  $customer->last_step = $request->last_step ;
   //                  Session::put('cur_customer', $customer);
   //              }
   //              break;

   //          case '4':
   //              if($request->account_owner)
   //              {
   //                  $account_owner  = $request->account_owner  ;
   //                  $iban  = $request->iban  ;
   //                  //----------
   //                  $customer = Session::get('cur_customer');
   //                  $customer->account_owner  = $account_owner  ;
   //                  $customer->iban  = $iban  ;
   //                  $customer->last_step = $request->last_step ;
   //                  $customer->save();

   //                  $customer->paymentDataId = 
   //                      $this->get_payment_id( $customer ,$iban  ,$account_owner );

   //                  if($customer->paymentDataId == 0)
   //                  {
   //                      Session::flash('message', ' Error while returning Payment id'); 
   //                      Session::flash('alert-class', 'alert-danger');
   //                      return view('registration.step' . 3 , compact('customer'));
   //                  }else{
   //                      $customer->status = 1; 
   //                      $customer->save();
   //                  }
   //                  Session::put('cur_customer', $customer);
   //              }
   //              break;
   //      }
        // return view('registration.step' . $step , compact('customer'));
   //  }
 
   //  private  function call_payment_api($method, $url, $data = false)
   //  {
   //      $curl = curl_init();

   //      switch ($method)
   //      {
   //          case "POST":
   //              curl_setopt($curl, CURLOPT_POST, 1);

   //              if ($data)
   //                  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
   //              break;
   //          case "PUT":
   //              curl_setopt($curl, CURLOPT_PUT, 1);
   //              break;
   //          default:
   //              if ($data)
   //                  $url = sprintf("%s?%s", $url, http_build_query($data));
   //      }

   //      curl_setopt($curl, CURLOPT_URL, $url);
   //      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

   //      $result = curl_exec($curl);

   //      curl_close($curl);

   //      return $result;
   //  }

   //  private function get_payment_id( $customer  ,$iban  ,$account_owner )
   //  {
   //      $res = $this->call_payment_api('post',$this->api_url,[
   //                  'customerId' =>  $customer->id,
   //                  'iban' => $iban  ,
   //                  'owner' =>   $account_owner
   //              ]);
   //      $result = json_decode($res); 
   //      $status = $result[0]['status'];
   //      if ($status === '200' || $status === true) {
   //        return $result[ "paymentDataId"];
   //      }
   //      return 0 ; 
   //  }
   

   //  // not used 

   //  private function find_customer($telephone)
   //  {
   //      // $customer = Customer::where('telephone',$telephone)->get();
   //      // if($customer){
   //      //     $this->register($step = $customer->last_step  ,  $customer );
   //      // }
   //  }
   //  private function save_to_draft($step, $customer)
   //  {
   //      // $response = file_get_contents('http://example.com/path/to/api/call?param1=5');
   //      // $response = json_decode($response);
   //      Session::flash('message', 
   //              'Your data in step '.$prevStep.
   //              ' has been saved in draft!'); 
   //      Session::flash('alert-class', 'alert-info'); 
   //      dd($customer);
   //  }
   }