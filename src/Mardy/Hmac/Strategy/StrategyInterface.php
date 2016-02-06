<?php

namespace Mardy\Hmac\Strategy;

use Mardy\Hmac\HashDataHandler;

/**
 * Strategy Interface
 *
 * @package Mardy\Hmac\Strategy
 * @author Michael Bardsley @mic_bardsley
 */
interface StrategyInterface
{
    /**
     * Sets the entity object
     *
     * @param \Mardy\Hmac\HashDataHandler $hashDataHandler
     * @return Hash
     */
    public function setHashDataHandler(HashDataHandler $hashDataHandler);

    /**
     * Sets the Strategy config options
     *
     * @param array $config
     */
    public function setConfig(array $config);

    /**
     * Encodes the HMAC based on the chosen Strategy
     *
     * @return StrategyInterface
     */
    public function encode();
}
