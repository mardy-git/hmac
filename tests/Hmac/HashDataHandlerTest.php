<?php

use Mardy\Hmac\HashDataHandler;

class HashDataHandlerTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @var \Mardy\Hmac\HashDataHandler
     */
    protected $hashDataHandler;

    public function setup()
    {
        $this->hashDataHandler = new HashDataHandler;
    }

    /**
     * Testing all the getters and setters in the entity class
     */
    public function testGettersAndSetters()
    {
        $array = $this->getDummyHmacData();

        $this->hashDataHandler->setData($array['data']);
        $this->hashDataHandler->setKey($array['key']);
        $this->hashDataHandler->setTime($array['time']);
        $this->hashDataHandler->setHmac($array['hmac']);

        $this->assertTrue($array['data'] === $this->hashDataHandler->getData());
        $this->assertTrue($array['key'] === $this->hashDataHandler->getKey());
        $this->assertTrue($array['time'] === $this->hashDataHandler->getTime());
        $this->assertTrue($array['hmac'] === $this->hashDataHandler->getHmac());
    }

    /**
     * Testing the isEncodable method
     */
    public function testIsEncodable()
    {
        $array = $this->getDummyHmacData();

        $this->assertFalse($this->hashDataHandler->isEncodable());

        $this->hashDataHandler->setData($array['data']);
        $this->hashDataHandler->setKey($array['key']);
        $this->hashDataHandler->setTime($array['time']);

        $this->assertTrue($this->hashDataHandler->isEncodable());
    }

    /**
     * Testing the isEncoded method
     */
    public function testIsEncoded()
    {
        $array = $this->getDummyHmacData();

        $this->assertFalse($this->hashDataHandler->isEncoded());

        $this->hashDataHandler->setHmac($array['hmac']);

        $this->assertTrue($this->hashDataHandler->isEncoded());
    }

    /**
     * Testing the toArray method
     */
    public function testToArray()
    {
        $array = $this->getDummyHmacData();

        $this->assertFalse($this->hashDataHandler->toArray());

        $this->hashDataHandler->setData($array['data']);
        $this->hashDataHandler->setHmac($array['hmac']);
        $this->hashDataHandler->setTime($array['time']);

        unset($array['key']);

        $this->assertSame($array, $this->hashDataHandler->toArray());
    }

    /**
     * Gets a dummy HMAC array
     *
     * @return array
     */
    protected function getDummyHmacData()
    {
        $array = [];
        $array['data'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30);
        $array['key'] = hash('sha512', rand(1000, 100000));
        $array['time'] = time();

        //doesn't really matter about it only being a random string, this is only a test!
        $array['hmac'] = hash('sha512', rand(1000, 100000));

        return $array;
    }
}