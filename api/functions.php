<?php

function getResource($url)
{
    $parts = explode('/', $url);
    $index = array_search('api', $parts);

    if($index !== false && isset($parts[$index + 1]))
    {
        return $parts[$index + 1];
    } else {
        return null;
    }
}

function return_response($code, $status, $content="")
{
    http_response_code($code);
    header("HTTP/1.0 $code");

    if($content == "")
    {
        echo json_encode(["http_code" => "$code", "status" => "$status"]);
    } else {
        echo json_encode($content);
    }
}

?>
