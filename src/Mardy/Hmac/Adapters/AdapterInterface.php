<?php

namespace Mardy\Hmac\Adapters;

use Mardy\Hmac\Entity;

interface AdapterInterface
{
    /**
     * Sets the entity object
     *
     * @param \Mardy\Hmac\Entity $entity
     * @return \Mardy\Hmac\Adapters\Hash
     */
    public function setEntity(Entity &$entity);

    /**
     * Sets the adapter config options
     *
     * @param array $config
     */
    public function setConfig(array $config);

    /**
     * Encodes the HMAC based on the values that have been entered using the hash() function
     *
     * http://php.net/manual/en/function.hash.php
     *
     * @return string
     */
    public function encode();
}
