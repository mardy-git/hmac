<?php

namespace Mardy\Hmac\Storage;

interface HmacStorageInterface
{
    /**
     * Getter method to return the hmac key
     *
     * @return string
     */
    public function getHmac();

    /**
     * Setter method to set the $hmac value
     *
     * @param string $hmac
     * @throws HmacException
     * @return NonPersistent
     */
    public function setHmac($hmac);

    /**
     * Getter method to return the $uri value
     *
     * @return string
     */
    public function getUri();

    /**
     * Setter method to set the $uri value
     *
     * @param string $uri
     * @throws HmacException
     * @return NonPersistent
     */
    public function setUri($uri);

    /**
     * Getter method to return the timestamp
     *
     * @return number
     */
    public function getTimestamp();

    /**
     * Setter method to set the $timestamp value
     *
     * @param number $ts
     * @throws HmacException
     * @return NonPersistent
     */
    public function setTimestamp($ts);
}
