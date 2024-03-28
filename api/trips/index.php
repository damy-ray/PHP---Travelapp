<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../http_status_code.php';
require_once 'trips_crud.php';

Database::connect();
$db = Database::getConnection();
$tripsCRUD = new TripsCRUD($db);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);


switch ($method)
{
    case 'GET':
        $tripsCRUD->get($data);
        break;
    case 'POST':
        $tripsCRUD->post($data);
        break;
    case 'PUT':
        $tripsCRUD->put($data);
        break;
    case 'DELETE':
        $tripsCRUD->delete($data);
        break;
    default:
        http_code(405, "Method not allowed");
        break;
}

Database::disconnect();

?>
