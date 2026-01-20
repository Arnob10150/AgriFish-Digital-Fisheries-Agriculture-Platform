<?php

class TOON {
    public static function encode($data) {
        if (is_array($data)) {
            if (self::isAssociativeArray($data)) {
                return self::encodeObject($data);
            } else {
                if (!empty($data) && is_array($data[0]) && self::isAssociativeArray($data[0])) {
                    return self::encodeArrayOfObjects($data);
                } else {
                    return self::encodeSimpleArray($data);
                }
            }
        } elseif (is_object($data)) {
            return self::encodeObject((array) $data);
        } elseif (is_scalar($data)) {
            return (string) $data;
        } else {
            return json_encode($data);
        }
    }

    public static function decode($toonString) {
        $toonString = trim($toonString);

        if (empty($toonString)) {
            return null;
        }

        if (strpos($toonString, "\n") !== false) {
            $lines = explode("\n", $toonString);
            if (count($lines) > 1 && strpos($lines[0], ',') !== false) {
                return self::decodeArrayOfObjects($lines);
            }
        }

        if (strpos($toonString, ':') !== false) {
            return self::decodeObject($toonString);
        }

        return $toonString;
    }

    private static function isAssociativeArray($array) {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    private static function encodeObject($object) {
        $lines = [];
        foreach ($object as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
            $lines[] = $key . ': ' . $value;
        }
        return implode("\n", $lines);
    }

    private static function encodeArrayOfObjects($array) {
        if (empty($array)) return '';

        $headers = array_keys($array[0]);
        $lines = [implode(',', $headers)];

        foreach ($array as $item) {
            $values = [];
            foreach ($headers as $header) {
                $value = $item[$header] ?? '';
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value);
                }
                $values[] = $value;
            }
            $lines[] = implode(',', $values);
        }

        return implode("\n", $lines);
    }

    private static function encodeSimpleArray($array) {
        return implode(',', $array);
    }

    private static function decodeObject($string) {
        $object = [];
        $lines = explode("\n", $string);

        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $key = trim($key);
                $value = trim($value);

                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $object[$key] = $decoded;
                } else {
                    $object[$key] = $value;
                }
            }
        }

        return $object;
    }

    private static function decodeArrayOfObjects($lines) {
        $headers = explode(',', array_shift($lines));
        $array = [];

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            $values = explode(',', $line);
            $object = [];

            foreach ($headers as $index => $header) {
                $value = $values[$index] ?? '';

                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $object[trim($header)] = $decoded;
                } else {
                    $object[trim($header)] = trim($value);
                }
            }

            $array[] = $object;
        }

        return $array;
    }
}

function toon_encode($data) {
    return TOON::encode($data);
}

function toon_decode($string) {
    return TOON::decode($string);
}
?>