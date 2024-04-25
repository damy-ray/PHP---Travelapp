<?php

class Trips
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

        // GET /api/trips/{id}
        if(isset($data["id"]) and $data["id"] != "")
        {
            $id = $data["id"];

            $stmt = $this->db->prepare("SELECT t.*, c.name AS country FROM trips t, countries c
                                   WHERE t.id_country = c.id
                                   AND t.id = :id");
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
                "country" => $row["country"],
                "region" => $row["region"],
                "departure_date" => $row["departure_date"],
                "return_date" => $row["return_date"],
                "available_seats" => $row["available_seats"],
                "price" => $row["price"]
            );

            return_response(200, "OK", $content);
        }
        // GET /api/trips/ (ALL)
        else
        {
            $q = $this->db->query("SELECT t.*, c.name AS country FROM trips t, countries c
                                   WHERE t.id_country = c.id");

            if($q->rowCount() == 0)
            {
                return_response(204, "No content");
                return;
            }

            $content = array();
            while($row = $q->fetch())
            {
                $element = array(
                    "id" => $row["id"],
                    "country" => $row["country"],
                    "region" => $row["region"],
                    "departure_date" => $row["departure_date"],
                    "return_date" => $row["return_date"],
                    "available_seats" => $row["available_seats"],
                    "price" => $row["price"]
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

        if(!isset($data["id_country"]) OR !isset($data["region"]) OR !isset($data["departure_date"])
           OR !isset($data["return_date"]) OR !isset($data["available_seats"]) OR !isset($data["price"])
           OR $data["id_country"] == "" OR $data["region"] == "" OR $data["departure_date"] == ""
           OR $data["return_date"] == "" OR $data["available_seats"] == "" OR $data["price"] == "")
        {
            return_response(400, "Bad request: all params required");
            return;
        }

        $id_country = $data["id_country"];
        $region = $data["region"];
        $departure_date = $data["departure_date"];
        $return_date = $data["return_date"];
        $available_seats = $data["available_seats"];
        $price = $data["price"];

        $stmt = $this->db->prepare("INSERT INTO trips
                                    (id_country, region, departure_date, return_date, available_seats, price)
                                    VALUES (:id_country, :region, :departure_date, :return_date, :available_seats, :price)");
        $stmt->bindParam(':id_country', $id_country);
        $stmt->bindParam(':region', $region);
        $stmt->bindParam(':departure_date', $departure_date);
        $stmt->bindParam(':return_date', $return_date);
        $stmt->bindParam(':available_seats', $available_seats);
        $stmt->bindParam(':price', $price);

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

        $check_stmt = $this->db->prepare("SELECT * FROM trips WHERE id = :id");
        $check_stmt->bindParam(':id', $id);
        $check_stmt->execute();

        if($check_stmt->rowCount() == 0)
        {
            return_response(204, "No content: id not valid");
            return;
        }

        $query_set = "";
        if(isset($data["id_country"]) and $data["id_country"] != ""){ $id_country = $data["id_country"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "id_country = :id_country"; }
        if(isset($data["region"]) and $data["region"] != ""){ $region = $data["region"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "region = :region"; }
        if(isset($data["departure_date"]) and $data["departure_date"] != ""){ $departure_date = $data["departure_date"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "departure_date = :departure_date"; }
        if(isset($data["return_date"]) and $data["return_date"] != ""){ $return_date = $data["return_date"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "return_date = :return_date"; }
        if(isset($data["available_seats"]) and $data["available_seats"] != ""){ $available_seats = $data["available_seats"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "available_seats = :available_seats"; }
        if(isset($data["price"]) and $data["price"] != ""){ $price = $data["price"]; if($query_set!=""){ $query_set .= ", "; } $query_set .= "price = :price"; }

        $stmt = $this->db->prepare("UPDATE trips SET $query_set WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if(isset($id_country)){ $stmt->bindParam(':id_country', $id_country); }
        if(isset($region)){ $stmt->bindParam(':region', $region); }
        if(isset($departure_date)){ $stmt->bindParam(':departure_date', $departure_date); }
        if(isset($return_date)){ $stmt->bindParam(':return_date', $return_date); }
        if(isset($available_seats)){ $stmt->bindParam(':available_seats', $available_seats); }
        if(isset($price)){ $stmt->bindParam(':price', $price); }

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

        $check_stmt = $this->db->prepare("SELECT * FROM trips WHERE id = :id");
        $check_stmt->bindParam(':id', $id);
        $check_stmt->execute();

        if($check_stmt->rowCount() == 0)
        {
            return_response(204, "No content: id not valid");
            return;
        }

        $stmt = $this->db->prepare("DELETE FROM trips WHERE id = :id");
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
