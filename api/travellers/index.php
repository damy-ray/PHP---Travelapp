<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../http_status_code.php';
require_once 'travellers_crud.php';

Database::connect();
$db = Database::getConnection();
$tavellersCRUD = new TravellersCRUD($db);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);


switch ($method)
{
    case 'GET':
        $tavellersCRUD->get($data);
        break;
    case 'POST':
        $tavellersCRUD->post($data);
        break;
    case 'PUT':
        $tavellersCRUD->put($data);
        break;
    case 'DELETE':
        $tavellersCRUD->delete($data);
        break;
    default:
        http_code(405, "Method not allowed");
        break;
}

Database::disconnect();

?>
