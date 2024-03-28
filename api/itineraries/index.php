<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../http_status_code.php';
require_once 'itineraries_crud.php';

Database::connect();
$db = Database::getConnection();
$itinerariesCRUD = new ItinerariesCRUD($db);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);


switch ($method)
{
    case 'GET':
        $itinerariesCRUD->get($data);
        break;
    case 'POST':
        $itinerariesCRUD->post($data);
        break;
    case 'PUT':
        $itinerariesCRUD->put($data);
        break;
    case 'DELETE':
        $itinerariesCRUD->delete($data);
        break;
    default:
        http_code(405, "Method not allowed");
        break;
}

Database::disconnect();

?>
