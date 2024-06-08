<?php

class Trip
{
    protected $dbconn;

    public function __construct(PDO $dbconn)
    {
        $this->dbconn = $dbconn;
    }

    public function getAll($country=null, $available_seats=null)
    {
        $query = "SELECT c.name AS country, t.* 
                  FROM trips t, countries c
                  WHERE t.id_country = c.id ";

        if ($country !== null) {
            $query .= "AND c.name = '$country'";
        }
        if ($available_seats !== null) {
            $query .= "AND t.available_seats = '$available_seats'";
        }

        $stmt = $this->dbconn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id)
    {
        $stmt = $this->dbconn->prepare("SELECT c.name AS country, t.*
                                        FROM trips t, countries c
                                        WHERE t.id_country = c.id
                                        AND t.id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function post($data)
    {
        [$columnsList, $valuesList, $params] = processInsertData($data);
        $stmt = $this->dbconn->prepare("INSERT INTO trips ($columnsList) VALUES ($valuesList)");
        foreach ($params as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }
        return $stmt->execute();
    }

    public function put($id, $data)
    {
        [$updatesList, $params] = processUpdateData($data);
        $params[':id'] = $id; 
        $stmt = $this->dbconn->prepare("UPDATE trips SET $updatesList WHERE id = :id");
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
