<?php

namespace Mardy\Hmac;

use Mardy\Hmac\Adapters\AdapterInterface;

/**
 * Manager Class
 *
 * Manages all the HMAC checking for the application
 *
 * @package Mardy\Hmac
 * @author Michael Bardsley @mic_bardsley
 */
class Manager
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Mardy\Hmac\Entity
     */
    protected $entity;

    /**
     * Number of seconds the key will remain active for
     *
     * @var int|float
     */
    protected $ttl = 2;

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter
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
     * @return Manager
     */
    public function key(string $key): self
    {
        $this->entity->setKey($key);

        return $this;
    }

    /**
     * Sets the time in the item
     *
     * @param int $time
     * @return Manager
     */
    public function time(int $time): self
    {
        $this->entity->setTime($time);

        return $this;
    }

    /**
     * Sets the data in the item
     *
     * @param string $data
     * @return Manager
     */
    public function data(string $data): self
    {
        $this->entity->setData($data);

        return $this;
    }

    /**
     * Sets the adapter config
     *
     * @param array $config
     * @return Manager
     */
    public function config(array $config): self
    {
        $this->adapter->setConfig($config);

        return $this;
    }

    /**
     * Checks the HMAC key to make sure it is valid
     *
     * @param string $hmac
     * @return bool
     */
    public function isValid($hmac): bool
    {
        $this->adapter->encode();

        return !(
            (time() - $this->entity->getTime() >= $this->ttl && $this->ttl != 0)
            || $hmac != $this->entity->getHmac()
        );
    }

    /**
     * Encodes the HMAC and returns an array
     *
     * @return Manager
     */
    public function encode(): self
    {
        $this->adapter->encode();

        return $this;
    }

    /**
     * Gets the HMAC entity object
     *
     * @return Entity
     */
    public function getHmac(): Entity
    {
        return $this->entity;
    }

    /**
     * Returns the entity array or false if the hmac doesn't exist
     *
     * @return array|false
     */
    public function toArray()
    {
        return $this->entity->toArray();
    }

    /**
     * Sets the Time To Live
     *
     * @param int|float $ttl
     * @return Manager
     */
    public function ttl($ttl): self
    {
        $this->ttl = is_int($ttl) ? (int) $ttl : (float) $ttl;

        return $this;
    }
}
