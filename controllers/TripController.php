<?php

require_once 'Validator.php';
require_once 'models/Trip.php';

class TripController
{
    protected $dbconn;
    protected $trip;

    public function __construct($dbconn)
    {
        $this->dbconn = $dbconn;
        $this->trip = new Trip($dbconn);
    }

    public function getAll()
    {
        $country = null;
        $available_seats = null;

        if (isset($_GET['country']) && isset($_GET['available_seats']))
        {
            $country = Validator::sanitize($_GET['country']);
            $available_seats = Validator::sanitize($_GET['available_seats']);
        } else if (isset($_GET['country']) && !isset($_GET['available_seats'])) {
            $country = Validator::sanitize($_GET['country']);
        } else if (!isset($_GET['country']) && isset($_GET['available_seats'])) {
            $available_seats = Validator::sanitize($_GET['available_seats']);
        }

        try {
            Validator::setData(null);
            Validator::string($country);   
            Validator::int($available_seats);    
            $trips = $this->trip->getAll($country, $available_seats);
            if(!$trips) {
                http_response(204, "No content");
                return;
            }
            http_response(200, "OK", $trips);
        } catch (Exception $e) {
            http_response(400, "Bad request: " . $e->getMessage());
        }
    }

    public function get($id)
    {
        $trip = $this->trip->get($id);
        if (!$trip) {
            http_response(204, "No content");
            return;
        }
        http_response(200, "OK", $trip);
    }

    public function post()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if(!$data) {
            http_response(400, "Bad request: data needs");
            return;
        }

        try {
            Validator::setData($data);
            Validator::int('id_country');      
            Validator::string('region');       
            Validator::date('departure_date');
            Validator::date('return_date');
            Validator::int('available_seats');
            Validator::float('price');
            if(!$this->trip->post($data)) {
                http_response(500, "Server error occurred");
                return;
            }
            http_response(201, "Created");
        } catch (Exception $e) {
            http_response(400, "Bad request: " . $e->getMessage());
        }
    }

    public function put($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if(!$data) {
            http_response(400, "Bad request: data needs");
            return;
        }

        try {
            Validator::int($id);
            Validator::setData($data);
            Validator::int('id_country');
            Validator::string('region');
            Validator::date('departure_date');
            Validator::date('return_date');
            Validator::int('available_seats');
            Validator::float('price');
            if($this->trip->put($id, $data) == 0) {
                http_response(500, "No rows affected");
                return;
            }
            http_response(200, "OK");
        } catch (Exception $e) {
            http_response(400, "Bad request: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            Validator::int($id);
            if($this->trip->delete($id) == 0) {
                http_response(500, "Content already deleted or not exists");
                return;
            }
        } catch (Exception $e) {
            http_response(400, "Bad request: " . $e->getMessage());
        }

        http_response(200, "OK");
    }
}

?>
