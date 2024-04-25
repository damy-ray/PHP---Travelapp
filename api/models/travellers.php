<?php

class Travellers
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

        // GET /api/travellers/{id}
        if(isset($data["id"]) AND $data["id"] != "")
        {
            $id = $data["id"];

            $stmt = $this->db->prepare("SELECT ts.*, tp.region AS trip
                                   FROM travellers ts, trips tp
                                   WHERE ts.id_trip = tp.id
                                   AND ts.id = :id");
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
                "trip" => $row["trip"],
                "name" => $row["name"],
                "birth_date" => $row["birth_date"],
                "gender" => $row["gender"],
                "payed" => $row["payed"]
            );

            return_response(200, "OK", $content);
        }
        else
        // GET /api/travellers/ (ALL)
        {
            $q = $this->db->query("SELECT ts.*, tp.region AS trip
                                   FROM travellers ts, trips tp
                                   WHERE ts.id_trip = tp.id");

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
                    "trip" => $row["trip"],
                    "name" => $row["name"],
                    "birth_date" => $row["birth_date"],
                    "gender" => $row["gender"],
                    "payed" => $row["payed"]
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

        if(!isset($data["id_trip"]) OR !isset($data["name"]) OR !isset($data["birth_date"])
        OR !isset($data["gender"]) OR !isset($data["payed"])
           OR $data["id_trip"] == "" OR $data["name"] == "" OR $data["birth_date"] == ""
           OR $data["gender"] == "" OR $data["payed"] == "")
        {
            return_response(400, "Bad request: all params required");
            return;
        }

        $id_trip = $data["id_trip"];
        $name = $data["name"];
        $birth_date = $data["birth_date"];
        $gender = $data["gender"];
        $payed = $data["payed"];

        $stmt = $this->db->prepare("INSERT INTO travellers
                                    (id_trip, name, birth_date, gender, payed)
                                    VALUES (:id_trip, :name, :birth_date, :gender, :payed)");
        $stmt->bindParam(':id_trip', $id_trip);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':birth_date', $birth_date);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':payed', $payed);

        if(!$stmt->execute())
        {
            return_response(500, "Internal server error");
            return;
        }

        return_response(201, "Created");
    }


    function put($data)
    {
        if(!isset($data["id"]) OR $data["id"] == "")
        {
            return_response(400, "Bad request: id missing");
            return;
        }

        $id = $data["id"];

        $check_stmt = $this->db->prepare("SELECT * FROM travellers WHERE id = :id");
        $check_stmt->bindParam(':id', $id);
        $check_stmt->execute();

        if($check_stmt->rowCount() == 0)
        {
            return_response(400, "No content: id not valid");
            return;
        }

        $query_set = "";
        if(isset($data["id_trip"]) and $data["id_trip"] != ""){ $id_trip = $data["id_trip"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "id_trip = :id_trip"; }
        if(isset($data["name"]) and $data["name"] != ""){ $name = $data["name"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "name = :name"; }
        if(isset($data["birth_date"]) and $data["birth_date"] != ""){ $birth_date = $data["birth_date"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "birth_date = :birth_date"; }
        if(isset($data["gender"]) and $data["gender"] != ""){ $gender = $data["gender"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "gender = :gender"; }
        if(isset($data["payed"]) and $data["payed"] != ""){ $payed = $data["payed"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "payed = :payed"; }

        $stmt = $this->db->prepare("UPDATE travellers SET $query_set WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if(isset($id_trip)){ $stmt->bindParam(':id_trip', $id_trip); }
        if(isset($name)){ $stmt->bindParam(':name', $name); }
        if(isset($birth_date)){ $stmt->bindParam(':birth_date', $birth_date); }
        if(isset($gender)){ $stmt->bindParam(':gender', $gender); }
        if(isset($payed)){ $stmt->bindParam(':payed', $payed); }

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

        $check_stmt = $this->db->prepare("SELECT * FROM travellers WHERE id = :id");
        $check_stmt->bindParam(':id', $id);
        $check_stmt->execute();

        if($check_stmt->rowCount() == 0)
        {
            return_response(400, "No content: id not valid");
            return;
        }

        $stmt = $this->db->prepare("DELETE FROM travellers WHERE id = :id");
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
