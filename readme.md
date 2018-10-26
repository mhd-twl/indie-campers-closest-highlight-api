# indie-campers-closest-highlight-api

Is a Laravel web app contains GET 2 APIs as JSON Response.

1. {APP_API_URL}/show_highlights/{point1}/{point2}
2. {APP_API_URL}/shortest_route/{point1}/{lat1}/{long2}

I defined them as Label in a GIVEN GRAPH in the constructor function.
The routes between points is calculated using their lat & long which is given and hardcoded as well ( in App\Http\Controllers\RoutePathController ). 

The data added as dummy 

#### The Points  
	Point( 'A', 44.968  ,  -94.420 )
	Point( 'B', 43.333  ,  -89.132 )
	Point( 'C', 33.755  ,  -116.359 )
	Point( 'D', 33.844  ,  -116.549 )
	Point( 'E', 44.920  ,  -93.447 )
	Point( 'F', 44.240  ,  -91.493 )
	Point( 'G', 44.968  ,  -94.419 )
	Point( 'H', 44.333  ,  -89.132 )
	Point( 'I', 33.755  ,  -116.360 )
	Point( 'J', 33.844  ,  -117.549 )
#### The Points' Routes 
	Ex. RoutePath( A , B , 9 ): 
	The edge in the map start with "A" and costs 9 to reach "B".

	'A' => array('B' => 9, 'D' => 14, 'F' => 7),
	'B' => array('A' => 9, 'C' => 11, 'D' => 2, 'F' => 10),
	'C' => array('B' => 11, 'E' => 6, 'F' => 15),
	'D' => array('A' => 14, 'B' => 2, 'E' => 9),
	'E' => array('C' => 6, 'D' => 9),
	'F' => array('A' => 7, 'B' => 10, 'C' => 15),
	'G' => array(),
	'H' => array('E' => 7, 'C' => 2),
	'I' => array('H' => 7, 'C' => 3 , 'B' => 15),
	'J' => array('F' => 7, 'I' => 12),

### 1st API 
Using this API by calling show_highlights in url will return list of routes between the given 2 points.


### 2nd API
Using this API by calling shortest_route in url will return shortest routes
between the given 2 points.
- The destination point here allowed to be used by lat and long.
- The shortest path is found by Dijkstra Algorithem.

* Note that APIs do not show the cost, it's easy to change it as required in the api functions  * 


___ 


<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications.


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
