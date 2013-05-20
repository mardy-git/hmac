<?php

namespace Scc\Hmac\Config;

use Scc\Hmac\Exception\HmacValueMissingException;

/**
 * Config Class
 *
 * Stores the config options for the HMAC class
 *
 * @package        Symphony Common Components
 * @subpackage     SCC
 * @category       HMAC
 * @author         Michael Bardsley
 */
class Config
{
    /**
     * Hold the key that will be used to check the HMAC sent by the client in the headers
     * This key is generated randomly and will need to remain the same across all application
     * that the HMAC is being checked.
     * This can be changed but must be applied to all the other apps that are using this library
     * /[a-zA-Z0-9]/
     *
     * @var string
     */
    protected $key = "wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb";

    /**
     * Holds the number of seconds until the request is valid for
     *
     * @var number
     */
    protected $validFor = 120;

    /**
     * Getter method to get the HMAC key that has been stored
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Getter method to return the validFor value
     *
     * @return number
     */
    public function getValidityPeriod()
    {
        return $this->validFor;
    }

    /**
     * Setter method to set the validFor value.
     * validFor must be set to a number or it will not be changed
     *
     * @param number $validFor
     * @return ConfigValues
     */
    public function setValidityPeriod($validFor)
    {
        //the validFor value cannot be set to null and it must be a number
        //if it fails these check we just return the ConfigValues object
        if ( !is_int($validFor)) {
            throw new HmacValueMissingException("You must supply a numerical value when setting the valid for time");
        }

        //the validFor is not null and contains a number
        $this->validFor = $validFor;

        return $this;
    }
}
