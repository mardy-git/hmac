<?php

namespace Mardy\Hmac\Adapters;

use Mardy\Hmac\Adapters\AdapterInterface;
use Mardy\Hmac\Entity;

abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * The data that will be used in the hash, this will need to be sent with the HTTP request
     *
     * @var \Mardy\Hmac\Item
     */
    protected $entity;

    /**
     * Sets the data that will be used in the hash
     *
     * @param \Mardy\Hmac\Entity $entity
     * @return \Mardy\Hmac\Adapters\Hash
     */
    public function setEntity(Entity &$entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     *
     */
    public function setConfig(array $config)
    {

    }

    /**
     *
     */
    public function encode()
    {

    }
}
