<?php

namespace Mardy\Hmac;

use Mardy\Hmac\Adapters\AdapterInterface;
use Mardy\Hmac\Entity;

/**
 * Manager Class
 *
 * Manages all the HMAC checking for the application
 *
 * @package        mardy-git
 * @subpackage     Authentication
 * @category       HMAC
 * @author         Michael Bardsley <me@mic-b.co.uk>
 */
class Manager
{
    /**
     * @var \Mardy\Hmac\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Mardy\Hmac\Entity
     */
    protected $entity;

    /**
     * Number of seconds the key will remain active for
     *
     * @var int
     */
    protected $ttl = 2;

    /**
     * Constructor
     *
     * @param \Mardy\Hmac\Adapters\AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->entity = new Entity;
        $this->adapter->setEntity($this->entity);
    }

    /**
     * Sets the private key in the item
     *
     * @param string $key
     * @return \Mardy\Hmac\Manager
     */
    public function key($key)
    {
        $this->entity->setKey($key);

        return $this;
    }

    /**
     * Sets the time in the item
     *
     * @param int $time
     * @return \Mardy\Hmac\Manager
     */
    public function time($time)
    {
        $this->entity->setTime($time);

        return $this;
    }

    /**
     * Sets the data in the item
     *
     * @param string $data
     * @return \Mardy\Hmac\Manager
     */
    public function data($data)
    {
        $this->entity->setData($data);

        return $this;
    }

    /**
     * Sets the adapter config
     *
     * @param array $config
     * @return \Mardy\Hmac\Manager
     */
    public function config(array $config)
    {
        $this->adapter->setConfig($config);

        return $this;
    }

    /**
     * Checks the HMAC key to make sure it is valid
     *
     * @return boolean
     */
    public function isValid($hmac)
    {
        $this->adapter->encode();

        if (((time() - $this->entity->getTime()) >= $this->ttl && $this->ttl != 0) ||
            ($hmac != $this->entity->getHmac())
        ) {
            return false;
        }

        return true;
    }

    /**
     * Encodes the HMAC and returns an array
     *
     * @return array
     */
    public function encode()
    {
        $this->adapter->encode();

        return $this;
    }

    /**
     * Returns the entity array or false if the hmac doesn't exist
     *
     * @return type
     */
    public function toArray()
    {
        return $this->entity->toArray();
    }

    /**
     * Sets the Time To Live
     *
     * @param int $ttl
     * @return \Mardy\Hmac\Manager
     */
    public function ttl($ttl)
    {
        $this->ttl = (int) $ttl;

        return $this;
    }
}
