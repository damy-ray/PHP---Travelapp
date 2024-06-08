<?php

require_once 'Validator.php';
require_once 'models/Traveller.php';

class TravellerController
{
    protected $dbconn;
    protected $traveller;

    public function __construct($dbconn)
    {
        $this->dbconn = $dbconn;
        $this->traveller = new Traveller($dbconn);
    }

    public function getAll()
    {
        $travellers = $this->traveller->getAll();
        http_response(200, "OK", $travellers);
    }

    public function get($id)
    {
        $traveller = $this->traveller->get($id);
        if (!$traveller) {
            http_response(204, "No content");
            return;
        }
        http_response(200, "OK", $traveller);
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
            Validator::string('name');
            Validator::date('birth_date');
            Validator::gender('gender');
            Validator::yesno('payed');
            if(!$this->traveller->post($data)) {
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
            Validator::string('name');
            Validator::date('birth_date');
            Validator::gender('gender');
            Validator::yesno('payed');
            if($this->traveller->put($id, $data) == 0) {
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
            if($this->traveller->delete($id) == 0) {
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
