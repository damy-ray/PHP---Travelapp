<?php

require_once '../../class/Database.class.php';

class CountriesCRUD
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function get($data)
    {
        if(isset($data["id"]) and $data["id"] != "")
        {
            $id = $data["id"];

            $stmt = $this->db->prepare("SELECT * FROM countries WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $row = $stmt->fetch();

                $content = array(
                   "id" => $row["id"],
                   "name" => $row["name"]
                );

                http_code(200, "OK", $content);
            }
            else
            {
                http_code(204, "No content: id not valid");
            }
        }
        else
        {
            $q = $this->db->query("SELECT * FROM countries");

            if($q->rowCount() > 0)
            {
                $content = array();
                while($row = $q->fetch())
                {
                    $element = array(
                        "id" => $row["id"],
                        "name" => $row["name"]
                    );

                    array_push($content, $element);
                }

                http_code(200, "OK", $content);
            }
            else
            {
                http_code(204, "No content");
            }
        }
    }


    function post($data)
    {
        if(isset($data["name"]) and $data["name"] != "")
        {
            $name = $data["name"];

            $stmt = $this->db->prepare("INSERT INTO countries (name) VALUES (:name)");
            $stmt->bindParam(':name', $name);

            if($stmt->execute())
            {
                http_code(201, "Created");
            } else {
                http_code(500, "Internal server error");
            }
        }
        else
        {
            http_code(400, "Bad request: name missing");
        }
    }


    function put($data)
    {
        if(isset($data["id"]) and isset($data["name"]) and $data["id"] != "" and $data["name"] != "")
        {
            $id = $data["id"];
            $name = $data["name"];

            $check_stmt = $this->db->prepare("SELECT * FROM countries WHERE id = :id");
            $check_stmt->bindParam(':id', $id);
            $check_stmt->execute();

            if($check_stmt->rowCount() > 0)
            {
                $stmt = $this->db->prepare("UPDATE countries SET name = :name WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':name', $name);

                if($stmt->execute())
                {
                    http_code(200, "OK");
                } else {
                    http_code(500, "Internal server error");
                }
            } else {
                http_code(204, "No content: id not valid");
            }
        }
        else
        {
            http_code(400, "Bad request: id, name missing");
        }
    }


    function delete($data)
    {
        if(isset($data["id"]) and $data["id"] != "")
        {
            $id = $data["id"];

            $check_stmt = $this->db->prepare("SELECT * FROM countries WHERE id = :id");
            $check_stmt->bindParam(':id', $id);
            $check_stmt->execute();

            if($check_stmt->rowCount() > 0)
            {
                $stmt = $this->db->prepare("DELETE FROM countries WHERE id = :id");
                $stmt->bindParam(':id', $id);

                if($stmt->execute())
                {
                    http_code(200, "OK");
                } else {
                    http_code(500, "Internal server error");
                }
            }
            else
            {
                http_code(204, "No content: id not valid");
            }
        }
        else
        {
            http_code(400, "Bad request: id missing");
        }
    }
}

?>
