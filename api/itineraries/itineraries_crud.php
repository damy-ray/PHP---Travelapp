<?php

require_once '../../class/Database.class.php';

class ItinerariesCRUD
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

            $stmt = $this->db->prepare("SELECT i.*, t.region AS trip
                                        FROM itineraries i, trips t
                                        WHERE i.id_trip = t.id
                                        AND i.id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $row = $stmt->fetch();

                $content = array(
                   "id" => $row["id"],
                   "trip" => $row["trip"],
                   "day" => $row["day"],
                   "time" => $row["time"],
                   "place" => $row["place"],
                   "details" => $row["details"]
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
            $q = $this->db->query("SELECT i.*, t.region AS trip
                                   FROM itineraries i, trips t
                                   WHERE i.id_trip = t.id");

            if($q->rowCount() > 0)
            {
                $content = array();
                while($row = $q->fetch())
                {
                    $element = array(
                        "id" => $row["id"],
                        "trip" => $row["trip"],
                        "day" => $row["day"],
                        "time" => $row["time"],
                        "place" => $row["place"],
                        "details" => $row["details"]
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
        if(isset($data["id_trip"]) and isset($data["day"]) and isset($data["time"])
           and isset($data["place"]) and $data["id_trip"] != "" and $data["day"] != ""
           and $data["time"] != "" and $data["place"] != "")
        {
            $id_trip = $data["id_trip"];
            $day = $data["day"];
            $time = $data["time"];
            $place = $data["place"];
            $details = $data["details"];

            $stmt = $this->db->prepare("INSERT INTO itineraries
                                        (id_trip, day, time, place, details)
                                        VALUES (:id_trip, :day, :time, :place, :details)");
            $stmt->bindParam(':id_trip', $id_trip);
            $stmt->bindParam(':day', $day);
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':place', $place);
            $stmt->bindParam(':details', $details);

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

            $check_stmt = $this->db->prepare("SELECT * FROM itineraries WHERE id = :id");
            $check_stmt->bindParam(':id', $id);
            $check_stmt->execute();

            if($check_stmt->rowCount() > 0)
            {
                $query_set = "";
                if(isset($data["id_trip"]) and $data["id_trip"] != ""){ $id_trip = $data["id_trip"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "id_trip = :id_trip"; }
                if(isset($data["day"]) and $data["day"] != ""){ $day = $data["day"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "day = :day"; }
                if(isset($data["time"]) and $data["time"] != ""){ $time = $data["time"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "time = :time"; }
                if(isset($data["place"]) and $data["place"] != ""){ $place = $data["place"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "place = :place"; }
                if(isset($data["details"]) and $data["details"] != ""){ $details = $data["details"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "details = :details"; }

                $stmt = $this->db->prepare("UPDATE itineraries SET $query_set WHERE id = :id");
                $stmt->bindParam(':id', $id);

                if(isset($id_trip)){ $stmt->bindParam(':id_trip', $id_trip); }
                if(isset($day)){ $stmt->bindParam(':day', $day); }
                if(isset($time)){ $stmt->bindParam(':time', $time); }
                if(isset($place)){ $stmt->bindParam(':gender', $place); }
                if(isset($details)){ $stmt->bindParam(':details', $details); }

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

            $check_stmt = $this->db->prepare("SELECT * FROM itineraries WHERE id = :id");
            $check_stmt->bindParam(':id', $id);
            $check_stmt->execute();

            if($check_stmt->rowCount() > 0)
            {
                $stmt = $this->db->prepare("DELETE FROM itineraries WHERE id = :id");
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
