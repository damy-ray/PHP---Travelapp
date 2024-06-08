<?php

require_once './controllers/CountryController.php';
require_once './controllers/ItineraryController.php';
require_once './controllers/TravellerController.php';
require_once './controllers/TripController.php';

$countryController = new CountryController($dbconn);
$itineraryController = new ItineraryController($dbconn);
$travellerController = new TravellerController($dbconn);
$tripController = new TripController($dbconn);


// Countris routes
route('GET', '/countries', $countryController, 'getAll');
route('GET', '/countries/{id}', $countryController, 'get');
route('POST', '/countries', $countryController, 'post');
route('PUT', '/countries/{id}', $countryController, 'put');
route('DELETE', '/countries/{id}', $countryController, 'delete');

// Itineraries routes
route('GET', '/itineraries', $itineraryController, 'getAll');
route('GET','/itineraries/{id}', $itineraryController, 'get');
route('POST', '/itineraries', $itineraryController, 'post');
route('PUT', '/itineraries/{id}', $itineraryController, 'put');
route('DELETE', '/itineraries/{id}', $itineraryController, 'delete');

// Trips routes
route('GET', '/trips', $tripController, 'getAll');
route('GET','/trips/{id}', $tripController, 'get');
route('POST', '/trips', $tripController, 'post');
route('PUT', '/trips/{id}', $tripController, 'put');
route('DELETE', '/trips/{id}', $tripController, 'delete');

// Travellers routes
route('GET', '/travellers', $travellerController, 'getAll');
route('GET','/travellers/{id}', $travellerController, 'get');
route('POST', '/travellers', $travellerController, 'post');
route('PUT', '/travellers/{id}', $travellerController, 'put');
route('DELETE', '/travellers/{id}', $travellerController, 'delete');

?>