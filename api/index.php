<?php

// File che verrà richiamato dalle regole .htaccess del web server Apache

// Definisco il contenuto del file come JSON
header('Content-Type: application/json; charset=utf-8');

// Richiamo i file necessari
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';
require_once 'routes.php';

// Credo un'istanza al database passando $config (ottenuto da config.php)
$dbInstance = new Database($config);
$db = $dbInstance->getConnection();

// Salvo il metodo, l'URI (endpoint risorsa) ed eventuali dati nel body
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$data = json_decode(file_get_contents('php://input'), true);

// Dal path URI cerco la risorsa chiamata e verifico l'esistenza della route
// Esempio: /api/trips/{id} -> cerca la route: ./models/trips.php
$resource = getResource($requestUri);
$route = matchRoute($resource);

// Se la route non viene trovata nel file routes.php ritorna un errore 404
if($route === null)
{
    return_response(404, "Resource not found");
    exit();
}

// Se il codice continua, aggiungo il file route da models
require_once $route;

// Salvo in resourceClass la classe appartenente alla risorsa: trips -> Trips
// resourceIstance è l'oggetto collegato alla classe della risorsa
// E' dinamico, dunque in base alla classe, istanzia oggetti diversi (di classe diverse) 
$resourceClass = ucfirst($resource);
$resourceIstance = new $resourceClass($db);

// Cerco il metodo richiesto e vado a chiamare un metodo della classe di appartenenza
// Esempio: GET /api/trips/2 -> andrà in: $resourceIstance->get($data)
switch($method)
{
    case 'GET':
        $resourceIstance->get($data);
        break;
    case 'POST':
        $resourceIstance->post($data);
        break;
    case 'PUT':
        $resourceIstance->put($data);
        break;
    case 'DELETE':
        $resourceIstance->delete($data);
        break;
    default:
        return_response(405, "Method not allowed");
        break;
}

// Per sicurezza, infine, disconnetto la connessione al database
$dbInstance->disconnect();

?>
