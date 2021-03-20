<?php

namespace Mardy\Hmac\Adapters;

use Mardy\Hmac\Entity;

/**
 * Adapter Interface
 *
 * @package Mardy\Hmac\Adapters
 * @author Michael Bardsley @mic_bardsley
 */
interface AdapterInterface
{
    /**
     * Sets the entity object
     *
     * @param Entity $entity
     * @return Hash
     */
    public function setEntity(Entity $entity): self;

    /**
     * Sets the adapter config options
     *
     * @param array $config
     */
    public function setConfig(array $config);

    /**
     * Encodes the HMAC based on the chosen adapter
     *
     * @return AdapterInterface
     */
    public function encode(): AdapterInterface;
}
