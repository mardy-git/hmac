<?php

use Mardy\Hmac\Entity;

class EntityTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var \Mardy\Hmac\Entity
     */
    protected $entity;

    public function setup()
    {
        $this->entity = new Entity;
    }

    /**
     * Testing all the getters and setters in the entity class
     */
    public function testGettersAndSetters()
    {
        $array = $this->getDummyHmacData();

        $this->entity->setData($array['data']);
        $this->entity->setKey($array['key']);
        $this->entity->setTime($array['time']);
        $this->entity->setHmac($array['hmac']);

        $this->assertTrue($array['data'] === $this->entity->getData());
        $this->assertTrue($array['key'] === $this->entity->getKey());
        $this->assertTrue($array['time'] === $this->entity->getTime());
        $this->assertTrue($array['hmac'] === $this->entity->getHmac());
    }

    /**
     * Testing the isEncodable method
     */
    public function testIsEncodable()
    {
        $array = $this->getDummyHmacData();

        $this->assertFalse($this->entity->isEncodable());

        $this->entity->setData($array['data']);
        $this->entity->setKey($array['key']);
        $this->entity->setTime($array['time']);

        $this->assertTrue($this->entity->isEncodable());
    }

    /**
     * Testing the isEncoded method
     */
    public function testIsEncoded()
    {
        $array = $this->getDummyHmacData();

        $this->assertFalse($this->entity->isEncoded());

        $this->entity->setHmac($array['hmac']);

        $this->assertTrue($this->entity->isEncoded());
    }

    /**
     * Testing the toArray method
     */
    public function testToArray()
    {
        $array = $this->getDummyHmacData();

        $this->assertFalse($this->entity->toArray());

        $this->entity->setData($array['data']);
        $this->entity->setHmac($array['hmac']);
        $this->entity->setTime($array['time']);

        unset($array['key']);

        $this->assertSame($array, $this->entity->toArray());
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