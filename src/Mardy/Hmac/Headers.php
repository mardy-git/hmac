<?php

namespace Mardy\Hmac;

use Mardy\Hmac\Exception\HmacValueMissingException;

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
     * Getter method to get the required HMAC headers
     *
     * @return array contains the required details from the header
     * @throws HmacValueMissingException
     */
    public function get()
    {
        if (! isset($_SERVER['HTTP_KEY'], $_SERVER['HTTP_WHEN'], $_SERVER['HTTP_URI'])) {
            throw new HmacValueMissingException("Missing headers!");
        }

        return [
            'key'   => filter_var($_SERVER['HTTP_KEY'], FILTER_SANITIZE_STRING),
            'when'  => filter_var($_SERVER['HTTP_WHEN'], FILTER_SANITIZE_STRING),
            'uri'   => filter_var($_SERVER['HTTP_URI'], FILTER_SANITIZE_STRING)
        ];
    }
}
