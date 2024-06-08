<?php

require_once 'Validator.php';
require_once 'models/Itinerary.php';

class ItineraryController
{
    protected $dbconn;
    protected $itinerary;

    public function __construct($dbconn)
    {
        $this->dbconn = $dbconn;
        $this->itinerary = new Itinerary($dbconn);
    }

    public function getAll()
    {
        $itineraries = $this->itinerary->getAll();
        http_response(200, "OK", $itineraries);
    }

    public function get($id)
    {
        $itinerary = $this->itinerary->get($id);
        if (!$itinerary) {
            http_response(204, "No content");
            return;
        }
        http_response(200, "OK", $itinerary);
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
            Validator::int('id_trip');
            Validator::date('day');
            Validator::time('time');
            Validator::string('place');
            Validator::string('details');
            if(!$this->itinerary->post($data)) {
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
            Validator::int('id_trip');
            Validator::date('day');
            Validator::time('time');
            Validator::string('place');
            Validator::string('details');
            if($this->itinerary->put($id, $data) == 0) {
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
            if($this->itinerary->delete($id) == 0) {
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
