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
     * @var int
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
     * @return int
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
        return (string) $this->hmac;
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
     * @param int $time
     * @return \Mardy\Hmac\Item
     */
    public function setTime($time)
    {
        $this->time = (int) $time;

        return $this;
    }

    /**
     * Sets the data
     *
     * @param string $data
     * @return \Mardy\Hmac\Item
     */
    public function setData($data)
    {
        $this->data = (string) $data;

        return $this;
    }

    /**
     * Sets the HMAC
     *
     * @param string $hmac
     * @return \Mardy\Hmac\Item
     */
    public function setHmac($hmac)
    {
        $this->hmac = (string) $hmac;

        return $this;
    }

    /**
     * Sets the key
     *
     * @param string $key
     * @return \Mardy\Hmac\Item
     */
    public function setKey($key)
    {
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
        if (empty($this->time) || empty($this->data) || empty($this->key)) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the HMAC has been generated
     *
     * @return boolean
     */
    public function isEncoded()
    {
        if (empty($this->hmac)) {
            return false;
        }

        return true;
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