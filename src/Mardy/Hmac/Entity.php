<?php

namespace Mardy\Hmac;

use Mardy\Hmac\Exceptions\HmacInvalidArgumentException;

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
    protected $data = '';

    /**
     * @var string
     */
    protected $hmac = '';

    /**
     * @var string
     */
    protected $key;

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
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get generated HMAC
     *
     * @return string
     */
    public function getHmac()
    {
        return $this->hmac;
    }

    /**
     * Get the key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the time
     *
     * @param int|float $time - use time() or microtime(true)
     * @return Entity
     */
    public function setTime($time)
    {
        if (!filter_var($time, FILTER_VALIDATE_INT) && !filter_var($time, FILTER_VALIDATE_FLOAT)) {
            throw new HmacInvalidArgumentException('The time is not a valid int or float');
        }

        $this->time = is_float($time) ? (float) $time : (int) $time;

        return $this;
    }

    /**
     * Sets the data
     *
     * @param string $data
     * @return Entity
     */
    public function setData($data)
    {
        if (!is_string($data)) {
            throw new HmacInvalidArgumentException('The data is not a valid string');
        }

        $this->data = (string) $data;

        return $this;
    }

    /**
     * Sets the HMAC
     *
     * @param string $hmac
     * @return Entity
     */
    public function setHmac($hmac)
    {
        if (!is_string($hmac)) {
            throw new HmacInvalidArgumentException('The HMAC is not a valid string');
        }

        $this->hmac = (string) $hmac;

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
        if (!is_string($key)) {
            throw new HmacInvalidArgumentException('The key is not a valid string');
        }

        $this->key = (string) $key;

        return $this;
    }

    /**
     * Checks if the time, data and key have been set so the HMAC can be generated
     *
     * @return boolean
     */
    public function isEncodable()
    {
        return $this->time && $this->data && $this->key;
    }

    /**
     * Checks if the HMAC has been generated
     *
     * @return boolean
     */
    public function isEncoded()
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
