<?php

use Scc\Hmac\Storage\NonPersistent;

class NonPersistentTest
    extends PHPUnit_Framework_Testcase
{
    protected $storage;

    public function setup()
    {
        $this->storage = new NonPersistent;
    }

    public function testSetHmacInstanceOf()
    {
        $storage = clone $this->storage;

        $this->assertInstanceOf('Scc\Hmac\Storage\NonPersistent', $storage->setHmac('qwertyuiop'));
    }

    public function testGetHmacSame()
    {
        $storage = clone $this->storage;

        $storage->setHmac('qwertyuiop');

        $this->assertSame('qwertyuiop', $storage->getHmac());
    }

    public function testSetUriInstanceOf()
    {
        $storage = clone $this->storage;

        $this->assertInstanceOf('Scc\Hmac\Storage\NonPersistent', $storage->setUri("api/user/1/role/1"));
    }

    public function testGetUriSame()
    {
        $storage = clone $this->storage;

        $storage->setUri("api/user/1/role/1");

        $this->assertSame("api/user/1/role/1", $storage->getUri());
    }

    public function testSetTimeoutInstanceOf()
    {
        $storage = clone $this->storage;

        $this->assertInstanceOf('Scc\Hmac\Storage\NonPersistent', $storage->setTimestamp(time()));
    }

    public function testGetTimeoutSame()
    {
        $storage = clone $this->storage;

        $time = time();

        $storage->setTimestamp($time);

        $this->assertSame($time, $storage->getTimestamp());
    }
}