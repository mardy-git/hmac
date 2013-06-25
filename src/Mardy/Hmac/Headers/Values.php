<?php

namespace Mardy\Hmac\Headers;

class Values
{
    /**
     * @var string
     */
    protected $prefix = "HTTP_";

    /**
     * @var boolean
     */
    protected $displayPrefix = false;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var int
     */
    protected $when;

    /**
     * @var string
     */
    protected $uri;

    /**
     * Converts the header objects into the a usable array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            ($this->displayPrefix === true? $this->getPrefix(): '') . 'KEY' => $this->getKey(),
            ($this->displayPrefix === true? $this->getPrefix(): '') . 'WHEN' => (int) $this->getWhen(),
            ($this->displayPrefix === true? $this->getPrefix(): '') . 'URI' => $this->getUri()
        ];
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return \Mardy\Hmac\Headers\HeaderValues
     */
    public function setPrefix($prefix)
    {
        $this->prefix = (string) $prefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayPrefix()
    {
        return $this->displayPrefix;
    }

    /**
     * @param boolean $displayPrefix
     * @return \Mardy\Hmac\Headers\HeaderValues
     */
    public function setDisplayPrefix($displayPrefix)
    {
        $this->displayPrefix = (string) $displayPrefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return \Mardy\Hmac\Headers\HeaderValues
     */
    public function setKey($key)
    {
        $this->key = (string) $key;
        return $this;
    }

    /**
     * @return int
     */
    public function getWhen()
    {
        return $this->when;
    }

    /**
     * @param int $when
     * @return \Mardy\Hmac\Headers\HeaderValues
     */
    public function setWhen($when)
    {
        $this->when = (int) $when;
        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return \Mardy\Hmac\Headers\HeaderValues
     */
    public function setUri($uri)
    {
        $this->uri = (string) $uri;
        return $this;
    }
}
