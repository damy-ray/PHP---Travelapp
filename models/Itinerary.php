<?php

class Itinerary
{
    protected $dbconn;

    function __construct(PDO $dbconn)
    {
        $this->dbconn = $dbconn;
    }

    public function getAll()
    {
        $stmt = $this->dbconn->query("SELECT i.*, t.region AS trip
                                      FROM itineraries i, trips t
                                      WHERE i.id_trip = t.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function get($id)
    {
        $stmt = $this->dbconn->prepare("SELECT i.*, t.region AS trip
                                    FROM itineraries i, trips t
                                    WHERE i.id_trip = t.id
                                    AND i.id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function post($data)
    {
        [$columnsList, $valuesList, $params] = processInsertData($data);
        $stmt = $this->dbconn->prepare("INSERT INTO itineraries ($columnsList) VALUES ($valuesList)");
        foreach ($params as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }
        return $stmt->execute();
    }

    public function put($id, $data)
    {
        [$updatesList, $params] = processUpdateData($data);
        $params[':id'] = $id; 
        $stmt = $this->dbconn->prepare("UPDATE itineraries SET $updatesList WHERE id = :id");
        foreach ($params as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->dbconn->prepare("DELETE FROM itineraries WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}

?>
