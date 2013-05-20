<?php

namespace Mardy\Hmac\Storage;

use Mardy\Hmac\Exception\HmacValueMissingException;
use Mardy\Hmac\Storage\HmacStorageInterface;

/**
 * NonPersistent Class
 *
 * Stores the values used to check the HMAC
 *
 * @package        mardy-dev
 * @subpackage     Authentication
 * @category       HMAC
 * @author         Michael Bardsley
 */
class NonPersistent
    implements HmacStorageInterface
{
    /**
     * @var string containing the HMAC key from the remote application
     */
    protected $hmac;

    /**
     * contains the URI that the requesting remote application is sending
     * the request from
     *
     * @var string
     */
    protected $uri;

    /**
     * @var Number contains the timestamp that the request was sent
     */
    protected $timestamp;

    /**
     * (non-PHPdoc)
     * @see \Scc\Hmac\Storage\HmacStorageInterface::getHmac()
     */
    public function getHmac()
    {
        return $this->hmac;
    }

    /**
     * (non-PHPdoc)
     * @see \Scc\Hmac\Storage\HmacStorageInterface::setHmac()
     */
    public function setHmac($hmac)
    {
        $this->hmac = $hmac;

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \Scc\Hmac\Storage\HmacStorageInterface::getUri()
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * (non-PHPdoc)
     * @see \Scc\Hmac\Storage\HmacStorageInterface::setUri()
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \Scc\Hmac\Storage\HmacStorageInterface::getTimestamp()
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * (non-PHPdoc)
     * @see \Scc\Hmac\Storage\HmacStorageInterface::setTimestamp()
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
