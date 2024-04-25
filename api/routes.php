<?php

$routes = [
    'countries' => 'models/countries.php',
    'itineraries' => 'models/itineraries.php',
    'travellers' => 'models/travellers.php',
    'trips' => 'models/trips.php'
];

function matchRoute($uri)
{
    global $routes;

    foreach($routes as $route => $destinationRoute)
    {
        if($uri == $route)
        {
            return $destinationRoute;
        }
    }
    return null;
}

?>
