<?php

namespace Helpers\Sanitizer;

class SanitizeArray
{
    // Recursive function to sanitize array or object values
    public static function sanitizeObject($object)
    {
        if (is_object($object)) {
            foreach ($object as $key => &$value) {
                if (is_array($value) || is_object($value)) {
                    $value = SanitizeArray::sanitizeObject($value);
                } else {
                    $object->$key = esc($value);
                }
            }
        }

        return $object;
    }
}
