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
    public function set($key = null, $timestamp = null, $uri = null)
    {
        //checks to ensure that all the values are not null
        if (is_null($key)) {
            throw new HmacValueMissingException("The HMAC value is null");
        }

        if (is_null($timestamp)) {
            throw new HmacValueMissingException("The timestamp value is null");
        }

        if (is_null($uri)) {
            throw new HmacValueMissingException("The URI value is null");
        }

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
        $return = [];

        //if the HTTP_KEY var is not set then throw an exception
        if (! isset($_SERVER['HTTP_KEY'])) {
            throw new HmacValueMissingException("No HMAC key is set in the header");
        }

        //get the generated hmac key
        $return['key'] = filter_var($_SERVER['HTTP_KEY'], FILTER_SANITIZE_STRING);

        //if the HTTP_WHEN var is not set then throw an exception
        if (! isset($_SERVER['HTTP_WHEN'])) {
            throw new HmacValueMissingException("No timestamp has been set in the header");
        }

        //get the timestamp
        $return['when'] = filter_var($_SERVER['HTTP_WHEN'], FILTER_SANITIZE_STRING);

        //if the HTTP_URI var is not set then throw an exception
        if (! isset($_SERVER['HTTP_URI'])) {
            throw new HmacValueMissingException("No URI has been set in the header");
        }

        //get the URI
        $return['uri'] = filter_var($_SERVER['HTTP_URI'], FILTER_SANITIZE_STRING);

        return $return;
    }
}
