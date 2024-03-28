<?php

function http_code($code, $status, $content="")
{
    http_response_code($code);
    header("HTTP/1.0 $code $status");

    if($content == "")
    {
        echo json_encode(["http_code" => "$code", "status" => "$status"]);
    } else {
        echo json_encode($content);
    }
}

?>
