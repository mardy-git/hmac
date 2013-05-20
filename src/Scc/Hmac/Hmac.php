<?php

namespace Scc\Hmac;

use Scc\Hmac\Exception\HmacValueMissingException;
use Scc\Hmac\Exception\HmacTimeoutException;
use Scc\Hmac\Config\Config;
use Scc\Hmac\Storage\NonPersistent;

/**
 * Hmac Class
 *
 * Manages all the HMAC checking for the application
 *
 * @package        Symphony Common Components
 * @subpackage     SCC
 * @category       HMAC
 * @author         Michael Bardsley
 */
class Hmac
{
    /**
     * Hold the instantiated ConfigValues class
     *
     * @var ConfigValues object
     */
    protected $config;

    /**
     * Holds the instantiated NonPersistent class
     *
     * @var NonPersistent
     */
    protected $storage;

    /**
     * Hold the error reason
     *
     * @var string
     */
    protected $error = '';

    /**
     * Constructor
     *
     * @param Config $config
     */
    public function __construct(Config $config, NonPersistent $storage)
    {
        $this->setConfig($config);
        $this->setStorage($storage);
    }

    /**
     * Checks the HMAC key to make sure it is valid
     *
     * @throws HmacException
     * @return bool true if it ok and false if it fails
     */
    public function check()
    {
        //intial checks - checking the HMAC as well
        if (! $this->checkInput(true)) {
            return false;
        }

        //work out how long the request has taken by subtracting the $ts from the current time
        $taken = time() - $this->getStorage()->getTimestamp();

        //check to make sure the request was sent within the last 2 mins, if not show an exception
        if ($taken > $this->getConfig()->getValidityPeriod()) {
            $this->setError("The request has taken to long");
            return false;
        }

        //all the values have been correctly set, now we need to encode the HMAC to see if it matches
        //to the one that was sent in the request
        $hmac = $this->encode();

        //if the stored hmac and encoded HMCA don't match up return false
        if ($hmac != $this->getStorage()->getHmac()) {
            $this->setError("HMAC is invalid");
            return false;
        }

        //return true because the HMAC matches
        return true;
    }

    /**
     * Creates an HMAC key using the supplied parameters
     *
     * @return boolean|string contains false or an array with the HMAC details
     */
    public function create()
    {
        //intial checks - without checking the HMAC key
        if (! $this->checkInput(false)) {
            return false;
        }

        //work out how long the request has taken by subtracting the $ts from the current time
        $taken = time() - $this->getStorage()->getTimestamp();

        //check to make sure the request was sent within the last 2 mins, if return false and produce an error
        if ($taken > $this->getConfig()->getValidityPeriod()) {
            $this->setError("The request has taken to long");
            return false;
        }

        //all the values have been correctly set, now we need to encode the HMAC to see if it matches
        //to the one that was sent in the request
        $hmac = $this->encode();

        //build the HMAC array that will contain all the details needed to regenerate
        //the HMAC on the other application
        $return = [
            'key' => $hmac,
            'when' => $this->getStorage()->getTimestamp(),
            'uri' => $this->getStorage()->getUri(),
        ];

        //return true because the HMAC matches
        return $return;
    }

    /**
     * Check the inputs that have been supplied
     * HMAC
     * URI
     * Timestamp
     *
     * @param boolean false if the HMAC needs to the checked or true if not
     * @return boolean true if all the values have been sent false if not
     */
    protected function checkInput($checkHmac = true)
    {
        //throw an exception if the $hmac is null
        if ($checkHmac === true && is_null($this->getStorage()->getHmac())) {
            $this->setError("An attempt to assign a null HMAC key was detected");
            return false;
        }
        //throw an exception if the $uri is null
        if (is_null($this->getStorage()->getUri())) {
            $this->setError("No URI was set when an HMAC check was attempted");
            return false;
        }

        //throw an exception if the ts has not been set
        if (is_null($this->getStorage()->getTimestamp())) {
            $this->setError("No TimeStamp was set when an HMAC check was attempted");
            return false;
        }

        return true;
    }

    /**
     * Setter method to set the config object
     *
     * @param ConfigValues $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Getter method to return the config object
     *
     * @return ConfigValues
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Setter method to set the storage object
     *
     * @param NonPersistent $storage
     * @return Hmac
     */
    public function setStorage(NonPersistent $storage)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * Getter method to return the storage object
     *
     * @return NonPersistent
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Setter method to set the error message
     *
     * @param string $error
     * @return Hmac
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Getter method to return the error message
     *
     * @return string $error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Encodes the HMAC based on the values that have been entered
     *
     * @return string
     */
    protected function encode()
    {
        //the first has contains the URI and timestamps that have been set
        $firsthash = hash("sha512", $this->getStorage()->getUri() . "@" . $this->getStorage()->getTimestamp());

        //the second has the private key
        $secondhash = hash("sha512", $this->getConfig()->getKey());

        //returned is an hash of both the previous hashes
        return hash("sha512", $firsthash . "-" . $secondhash);

    }
}
