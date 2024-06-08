<?php

function route($req, $path, $controllerInstance, $method)
{
    $request_method = $_SERVER["REQUEST_METHOD"];
    $path_info = explode('?', $_SERVER['REQUEST_URI'])[0];
    //$path_info = str_replace('/Damiano/', '/', $path_info);
    $path = str_replace('{id}', '([^/]+)', $path);
    if ($request_method == $req && preg_match("@^$path$@", $path_info, $matches))
    {
        $id = isset($matches[1]) ? $matches[1] : null;
        if ($id) {
            return $controllerInstance->$method($id);
        }
        return $controllerInstance->$method();
    }
}

function http_response($http_code, $http_response, $data=null)
{
    header("HTTP/1.0 $http_code");
    http_response_code($http_code);
    echo json_encode(["http_code" => $http_code, "http_response" => $http_response, "data" => $data]);
}

function processInsertData($data)
{
    if(!$data) {
        return null;
    }
    
    $columns = array_map(function($col)
    {
        return "`" . $col . "`";
    }, array_keys($data));
    $columnsList = implode(", ", $columns);

    $values = [];
    $params = [];
    $index = 1;
    foreach ($data as $key => $value)
    {
        $values[] = ":valore" . $index;
        $params[":valore" . $index] = $value;
        $index++;
    }
    $valuesList = implode(", ", $values);

    return [$columnsList, $valuesList, $params];
}

function processUpdateData($data)
{
    if (!$data) {
        return null;
    }

    $updates = [];
    $params = [];
    $index = 1;
    foreach ($data as $key => $value) {
        $updates[] = "`" . $key . "` = :valore" . $index;
        $params[":valore" . $index] = $value;
        $index++;
    }
    $updatesList = implode(", ", $updates);

    return [$updatesList, $params];
}

function existAndNotEmpty($param)
{
    if($param != '' && $param !== null)
        return True;
    else
        return False;
}

?>
