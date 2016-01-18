<?php

namespace Mardy\Hmac;

use Mardy\Hmac\Exceptions\HmacInvalidHashException;
use Mardy\Hmac\Exceptions\HmacRequestTimeoutException;

/**
 * HashManager Class
 *
 * Manages all the HMAC checking for the application
 *
 * @package Mardy\Hmac
 * @author Michael Bardsley @mic_bardsley
 */
class HashManager
{
    /**
     * @var \Mardy\Hmac\Adapters\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Mardy\Hmac\HashDataHandler
     */
    protected $hashDataHandler;

    /**
     * Number of seconds the key will remain active for
     *
     * @var int|float
     */
    protected $ttl = 2;

    /**
     * Constructor
     *
     * @param \Mardy\Hmac\Adapters\AdapterInterface $adapter
     */
    public function __construct(Adapters\AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->hashDataHandler = new HashDataHandler;
        $this->adapter->setHashDataHandler($this->hashDataHandler);
    }

    /**
     * Sets the private key in the item
     *
     * @param string $key
     * @return HashManager
     */
    public function key($key)
    {
        $this->hashDataHandler->setKey($key);

        return $this;
    }

    /**
     * Sets the time in the item
     *
     * @param int $time
     * @return HashManager
     */
    public function time($time)
    {
        $this->hashDataHandler->setTime($time);

        return $this;
    }

    /**
     * Sets the data in the item
     *
     * @param string $data
     * @return HashManager
     */
    public function data($data)
    {
        $this->hashDataHandler->setData($data);

        return $this;
    }

    /**
     * Sets the adapter config
     *
     * @param array $config
     * @return HashManager
     */
    public function config(array $config)
    {
        $this->adapter->setConfig($config);

        return $this;
    }

    /**
     * Checks the HMAC key to make sure it is valid
     *
     * @param string $hmac
     * @throws HmacRequestTimeoutException - when the request has timed out
     * @throws HmacInvalidHashException - when the hashes do not match
     */
    public function valid($hmac)
    {
        $this->encode();

        if (time() - $this->hashDataHandler->getTime() >= $this->ttl && $this->ttl != 0) {
            throw new HmacRequestTimeoutException('The request has timed out');
        }

        if ($hmac != $this->hashDataHandler->getHmac()) {
            throw new HmacInvalidHashException('The generated hashes does not match');
        }
    }

    /**
     * Encodes the HMAC and returns an array
     *
     * @return HashManager
     */
    public function encode()
    {
        $this->adapter->encode();

        return $this;
    }

    /**
     * Gets the HMAC entity object
     *
     * @return HashDataHandler
     */
    public function getHmac()
    {
        return $this->hashDataHandler;
    }

    /**
     * Returns the entity array or false if the hmac doesn't exist
     *
     * @return array
     */
    public function toArray()
    {
        return $this->hashDataHandler->toArray();
    }

    /**
     * Sets the Time To Live
     *
     * @param int|float $ttl
     * @return HashManager
     */
    public function ttl($ttl)
    {
        $this->ttl = is_int($ttl) ? (int) $ttl : (float) $ttl;

        return $this;
    }
}
