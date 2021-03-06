<?php

class Util2
{

    public static function read($stream, $offset, $numberOfBytes)
    {
        if ($numberOfBytes == 0) {
            return '';
        }
        if (fseek($stream, $offset) == 0) {
            $value = fread($stream, $numberOfBytes);

            // We check that the number of bytes read is equal to the number
            // asked for. We use ftell as getting the length of $value is
            // much slower.
            if (ftell($stream) - $offset === $numberOfBytes) {
                return $value;
            }
        }
        throw new InvalidDatabaseException2(
            "The MaxMind DB file contains bad data"
        );
    }

    public static function stringLength($string)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($string, '8bit');
        }

        return strlen($string);
    }
}
