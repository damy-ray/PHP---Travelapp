<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../http_status_code.php';
require_once 'countries_crud.php';

Database::connect();
$db = Database::getConnection();
$countriesCRUD = new CountriesCRUD($db);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);


switch ($method)
{
    case 'GET':
        $countriesCRUD->get($data);
        break;
    case 'POST':
        $countriesCRUD->post($data);
        break;
    case 'PUT':
        $countriesCRUD->put($data);
        break;
    case 'DELETE':
        $countriesCRUD->delete($data);
        break;
    default:
        http_code(405, "Method not allowed");
        break;
}

Database::disconnect();

?>
