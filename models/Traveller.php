<?php

class Traveller
{
    protected $dbconn;

    public function __construct(PDO $dbconn)
    {
        $this->dbconn = $dbconn;
    }

    public function getAll()
    {
        $stmt = $this->dbconn->query("SELECT ts.*, tp.region AS trip
                                  FROM travellers ts, trips tp
                                  WHERE ts.id_trip = tp.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function get($id)
    {
        $stmt = $this->dbconn->prepare("SELECT ts.*, tp.region AS trip
                                    FROM travellers ts, trips tp
                                    WHERE ts.id_trip = tp.id
                                    AND ts.id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function post($data)
    {
        [$columnsList, $valuesList, $params] = processInsertData($data);
        $stmt = $this->dbconn->prepare("INSERT INTO travellers ($columnsList) VALUES ($valuesList)");
        foreach ($params as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }
        return $stmt->execute();
    }

    public function put($id, $data)
    {
        [$updatesList, $params] = processUpdateData($data);
        $params[':id'] = $id; 
        $stmt = $this->dbconn->prepare("UPDATE travellers SET $updatesList WHERE id = :id");
        foreach ($params as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->dbconn->prepare("DELETE FROM travellers WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}

?>
