<?php

namespace Mardy\Hmac;

/**
 * Entity Class
 *
 * @package Mardy\Hmac
 * @author Michael Bardsley @mic_bardsley
 */
class Entity
{
    /**
     * @var int|float
     */
    protected $time = 0;

    /**
     * @var string
     */
    protected string $data = '';

    /**
     * @var string
     */
    protected string $hmac = '';

    /**
     * @var string
     */
    protected string $key;

    /**
     * Gets the time
     *
     * @return int|float
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Gets the data
     *
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Get generated HMAC
     *
     * @return string
     */
    public function getHmac(): string
    {
        return $this->hmac;
    }

    /**
     * Get the key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Sets the time
     *
     * @param int|float $time - use time() or microtime(true)
     * @return Entity
     */
    public function setTime($time): self
    {
        $this->time = is_float($time) ? (float) $time : (int) $time;

        return $this;
    }

    /**
     * Sets the data
     *
     * @param string $data
     * @return Entity
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Sets the HMAC
     *
     * @param string $hmac
     * @return Entity
     */
    public function setHmac(string $hmac): self
    {
        $this->hmac = $hmac;

        return $this;
    }

    /**
     * Sets the key
     *
     * @param string $key
     * @return Entity
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Checks if the time, data and key have been set so the HMAC can be generated
     *
     * @return bool
     */
    public function isEncodable(): bool
    {
        return $this->time && $this->data && $this->key;
    }

    /**
     * Checks if the HMAC has been generated
     *
     * @return bool
     */
    public function isEncoded(): bool
    {
        return (bool) $this->hmac;
    }

    /**
     * Returns false if the HMAC doesn't exist or an array containing the data, time and HMAC
     *
     * @return boolean|array
     */
    public function toArray()
    {
        if ($this->isEncoded()) {
            return [
                'data' => $this->getData(),
                'time' => $this->getTime(),
                'hmac' => $this->getHmac(),
            ];
        }

        return false;
    }
}
