<?php

require_once '../../class/Database.class.php';

class TravellersCRUD
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

            $stmt = $this->db->prepare("SELECT ts.*, tp.region AS trip
                                   FROM travellers ts, trips tp
                                   WHERE ts.id_trip = tp.id
                                   AND ts.id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $row = $stmt->fetch();

                $content = array(
                   "id" => $row["id"],
                   "trip" => $row["trip"],
                   "name" => $row["name"],
                   "birth_date" => $row["birth_date"],
                   "gender" => $row["gender"],
                   "payed" => $row["payed"]
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
            $q = $this->db->query("SELECT ts.*, tp.region AS trip
                                   FROM travellers ts, trips tp
                                   WHERE ts.id_trip = tp.id");

            if($q->rowCount() > 0)
            {
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
        if(isset($data["id_trip"]) and isset($data["name"]) and isset($data["birth_date"])
           and isset($data["gender"]) and isset($data["payed"])
           and $data["id_trip"] != "" and $data["name"] != "" and $data["birth_date"] != ""
           and $data["gender"] != "" and $data["payed"] != "")
        {
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

            if($stmt->execute())
            {
                http_code(201, "Created");
            } else {
                http_code(500, "Internal server error");
            }
        }
        else
        {
            http_code(400, "Bad request: all params required");
        }
    }


    function put($data)
    {
        if(isset($data["id"]) and $data["id"] != "")
        {
            $id = $data["id"];

            $check_stmt = $this->db->prepare("SELECT * FROM travellers WHERE id = :id");
            $check_stmt->bindParam(':id', $id);
            $check_stmt->execute();

            if($check_stmt->rowCount() > 0)
            {
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


    function delete($data)
    {
        if(isset($data["id"]) and $data["id"] != "")
        {
            $id = $data["id"];

            $check_stmt = $this->db->prepare("SELECT * FROM travellers WHERE id = :id");
            $check_stmt->bindParam(':id', $id);
            $check_stmt->execute();

            if($check_stmt->rowCount() > 0)
            {
                $stmt = $this->db->prepare("DELETE FROM travellers WHERE id = :id");
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
