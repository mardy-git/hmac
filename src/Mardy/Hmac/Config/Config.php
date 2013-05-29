<?php

namespace Mardy\Hmac\Config;

use Mardy\Hmac\Exception\HmacValueMissingException;
use Mardy\Hmac\Exception\HmacHashAlgorithmException;

/**
 * Config Class
 *
 * Stores the config options for the HMAC class
 *
 * @package        mardy-dev
 * @subpackage     Authentication
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
     * Example Key:
     * wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb
     *
     * @var string
     */
    protected $key = null;

    /**
     * Holds the number of seconds until the request is valid for
     *
     * @var number
     */
    protected $validFor = 120;

    /**
     * Holds the hashing algorithm used to hash the hmac
     *
     * View this page to view a list of the hashing algorithms or to
     * check which ones are available run hash_algos() to list them
     * http://uk3.php.net/manual/en/function.hash-algos.php
     *
     * @var string
     */
    protected $algorithm = 'sha512';

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
     * Setter method to set the key
     *
     * @param type $key
     * @return \Mardy\Hmac\Config\Config
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
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
     * @return \Mardy\Hmac\Config\Config
     */
    public function setValidityPeriod($validFor)
    {
        //the validFor value cannot be set to null and it must be a number
        //if it fails these check we just return the ConfigValues object
        if (! is_int($validFor)) {
            throw new HmacValueMissingException(
                "You must supply a numerical value when setting the valid for time"
            );
        }

        //the validFor is not null and contains a number
        $this->validFor = $validFor;

        return $this;
    }

    /**
     * Getter method to get the selected algorithm
     *
     * @return string containing the algorithm selected
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * Setter method to set the algorithm used, this will also check to make sure it exists
     * before allowing the setter to change the value.
     *
     * Default is `sha512`
     *
     * Example:
     * sha512
     * sha256
     * md5
     * crc32
     * ... ...
     *
     * @param string $algorithm
     * @return \Mardy\Hmac\Config\Config
     * @throws Mardy\Hmac\Exception\HmacHashAlgorithmException
     */
    public function setAlgorithm($algorithm)
    {
        //show exception if the algorithm is not allowed
        if (! $this->isAlgorithmAllowed($algorithm)) {
            throw new HmacHashAlgorithmException("You have selected an invalid algorithm");
        }

        //the algorithm is allowed so it can be assigned to it var
        $this->algorithm = (string) $algorithm;

        return $this;
    }

    /**
     * Checks to see if the algorithm selected is allowed to be used by the server
     *
     * @param string $algorithm
     * @return boolean
     */
    protected function isAlgorithmAllowed($algorithm)
    {
        //get the hash algorithms that are valid for the current system
        $hashes = hash_algos();

        //return false if the algorithm is not allowed
        if (! in_array((string) $algorithm, $hashes)) {
            return false;
        }

        //else return true
        return true;
    }
}
