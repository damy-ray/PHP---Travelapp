<?php

require_once 'Validator.php';
require_once 'models/Country.php';

class CountryController
{
    protected $dbconn;
    protected $country;

    public function __construct($dbconn)
    {
        $this->dbconn = $dbconn;
        $this->country = new Country($dbconn);
    }

    public function getAll()
    {
        $countries = $this->country->getAll();
        http_response(200, "OK", $countries);
    }

    public function get($id)
    {
        $country = $this->country->get($id);
        if (!$country) {
            http_response(204, "No content");
            return;
        }
        http_response(200, "OK", $country);
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
            Validator::string('name');
            if(!$this->country->post($data)) {
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
            Validator::string('name');
            if($this->country->put($id, $data) == 0) {
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
            if($this->country->delete($id) == 0) {
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
