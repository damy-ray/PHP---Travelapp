<?php

class Countries
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function get($data)
    {
        if(!$data)
        {
            $data = $_GET;
        }

        // GET api/countries/{id}
        if(isset($data["id"]) AND $data["id"] != "")
        {
            $id = $data["id"];

            $stmt = $this->db->prepare("SELECT * FROM countries WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if($stmt->rowCount() == 0)
            {
                return_response(400, "No content: id not valid");
                return;
            }

            $row = $stmt->fetch();
            $content = array(
                "id" => $row["id"],
                "name" => $row["name"]
            );

            return_response(200, "OK", $content);
        }
        // GET api/countries/ (ALL)
        else
        {
            $q = $this->db->query("SELECT * FROM countries");

            if($q->rowCount() == 0)
            {
                return_response(400, "No content");
                return;
            }

            $content = array();
            while($row = $q->fetch())
            {
                $element = array(
                    "id" => $row["id"],
                    "name" => $row["name"]
                );

                array_push($content, $element);
            }

            return_response(200, "OK", $content);
        }
    }


    function post($data)
    {
        if(!$data)
        {
            $data = $_POST;
        }

        if(!isset($data["name"]) OR $data["name"] == "")
        {
            return_response(400, "Bad request: name missing");
            return;
        }

        $name = $data["name"];

        $stmt = $this->db->prepare("INSERT INTO countries (name) VALUES (:name)");
        $stmt->bindParam(':name', $name);

        if(!$stmt->execute())
        {
            return_response(500, "Internal server error");
            return;
        }

        return_response(201, "Created");
    }


    function put($data)
    {
        if(!isset($data["id"]) OR !isset($data["name"]) OR $data["id"] == "" OR $data["name"] == "")
        {
            return_response(400, "Bad request: id, name missing");
            return;
        }

        $id = $data["id"];
        $name = $data["name"];

        $check_stmt = $this->db->prepare("SELECT * FROM countries WHERE id = :id");
        $check_stmt->bindParam(':id', $id);
        $check_stmt->execute();

        if($check_stmt->rowCount() == 0)
        {
            return_response(400, "No content: id not valid");
            return;
        }

        $stmt = $this->db->prepare("UPDATE countries SET name = :name WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);

        if(!$stmt->execute())
        {
            return_response(500, "Internal server error");
            return;
        }

        return_response(200, "OK");
    }


    function delete($data)
    {
        if(!$data)
        {
            $data = $_GET;
        }

        if(!isset($data["id"]) OR $data["id"] == "")
        {
            return_response(400, "Bad request: id missing");
            return;
        }

        $id = $data["id"];

        $check_stmt = $this->db->prepare("SELECT * FROM countries WHERE id = :id");
        $check_stmt->bindParam(':id', $id);
        $check_stmt->execute();

        if($check_stmt->rowCount() == 0)
        {
            return_response(400, "No content: id not valid");
            return;
        }

        $stmt = $this->db->prepare("DELETE FROM countries WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if(!$stmt->execute())
        {
            return_response(500, "Internal server error");
            return;
        }

        return_response(200, "OK");
    }
}

?>
