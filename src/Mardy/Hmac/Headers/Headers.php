<?php

namespace Mardy\Hmac\Headers;

/**
 * Headers Class
 *
 * Gets and Sets the headers for the HMAC
 *
 * @package        mardy-dev
 * @subpackage     Authentication
 * @category       HMAC-Headers
 * @author         Michael Bardsley
 */
class Headers
{
    /**
     * Setter method to set the header values used by the HMAC class
     *
     * @param string $key contains the HMAC key
     * @param int $timestamp contains the timestamp from when the request was made
     * @param string $uri contains the URI that was used in the request
     */
    public function set($key, $timestamp, $uri)
    {
        header('HTTP_KEY: ' . $key);
        header('HTTP_WHEN: ' . $timestamp);
        header('HTTP_URI: ' . $uri);

        return $this;
    }

    /**
     * Getter method to get the required HMAC headers, returns null values if the header values cannot be found
     *
     * @return array contains the required details from the header
     */
    public function get()
    {
        return [
            'key'   => isset($_SERVER['HTTP_KEY']) ? filter_var($_SERVER['HTTP_KEY'], FILTER_SANITIZE_STRING) : null,
            'when'  => isset($_SERVER['HTTP_WHEN']) ? filter_var($_SERVER['HTTP_WHEN'], FILTER_SANITIZE_STRING) : null,
            'uri'   => isset($_SERVER['HTTP_URI']) ? filter_var($_SERVER['HTTP_URI'], FILTER_SANITIZE_STRING) : null
        ];
    }
}
