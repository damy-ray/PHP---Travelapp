<?php

class Validator
{
    public static $data;

    public static function setData($data)
    {
        self::$data = $data;
    }

    public static function int($attr)   
    {
        $param = null;
        if (self::$data === null) {
            $param = $attr;
        } else if (isset(self::$data[$attr])) {
            $param = self::$data[$attr];
        }

        if (isset($param)) {
            if (!is_numeric($param) || intval($param) <= 0) {
                throw new Exception('Value is not numeric');
            }
        }
    }

    public static function float($attr)
    {
        $param = null;
        if (self::$data === null) {
            $param = $attr;
        } else if (isset(self::$data[$attr])) {
            $param = self::$data[$attr];
        }

        if (isset($param)) {
            if (!is_numeric($param) || floatval($param) <= 0 || strval(floatval($param)) !== strval($param)) {
                throw new Exception('Float number not valid');
            }
        }
    }

    public static function string($attr)
    {
        $param = null;
        if (self::$data === null) {
            $param = $attr;
        } else if (isset(self::$data[$attr])) {
            $param = self::$data[$attr];
        }

        if (isset($param)) {
            if (!is_string($param) || trim($param) === '') {
                throw new Exception('String is not valid');
            }
        }
    }

    public static function date($attr, $format = 'Y-m-d')
    {
        $param = null;
        if (self::$data === null) {
            $param = $attr;
        } else if (isset(self::$data[$attr])) {
            $param = self::$data[$attr];
        }

        if (isset($param)) {
            $date = DateTime::createFromFormat($format, $param);
            if ($date->format($format) !== $param) {
                throw new Exception('Date is not a valid format. Follow: Y-m-d');
            }
        }
    }

    public static function time($attr)
    {
        $param = null;
        if (self::$data === null) {
            $param = $attr;
        } else if (isset(self::$data[$attr])) {
            $param = self::$data[$attr];
        }

        if (isset($param)) {
            if (!is_string($t) || !preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $t)) {
                throw new Exception('Time is not a valid format. Follow: HH:MM');
            }
        }
    }

    public static function gender($attr)
    {
        $param = null;
        if (self::$data === null) {
            $param = $attr;
        } else if (isset(self::$data[$attr])) {
            $param = self::$data[$attr];
        }

        if (isset($param)) {
            if (isset($param) !== 'm' && isset($param) !== 'f') {
                throw new Exception('Gender not valid');
            }
        }
    }

    public static function yesno($attr)
    {
        $param = null;
        if (self::$data === null) {
            $param = $attr;
        } else if (isset(self::$data[$attr])) {
            $param = self::$data[$attr];
        }
        
        if (isset($param)) {
            if ($param !== 'yes' && $param !== 'no') {
                throw new Exception('Response yes/no not valid');
            }
        }
    }

    public static function sanitize($param)
    {
        $param = htmlspecialchars($param);
        $param = htmlentities($param, ENT_QUOTES);
        echo json_encode($param);
        return $param;
    }
}

?>
