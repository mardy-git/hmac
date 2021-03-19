<?php

namespace MardyHmacTest\Adapters;

use Mardy\Hmac\Entity;
use Mardy\Hmac\Exceptions\HmacInvalidAlgorithmException;
use Mardy\Hmac\Exceptions\HmacInvalidArgumentException;
use PHPUnit\Framework\TestCase;

abstract class BaseAdapterTest extends TestCase
{
    const KEY = 'wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb';

    protected $adapter;

    protected function mockEntity()
    {
        $entity = new Entity;

        return $entity;
    }

    public function testSetEntity()
    {
        $this->assertInstanceOf(get_class($this->adapter), $this->adapter->setEntity($this->mockEntity()));
    }

    public function testSetAlgorithmWithInvalidAlgorithmExpectsHmacInvalidAlgorithmException()
    {
        $this->expectException(HmacInvalidAlgorithmException::class);
        $this->expectExceptionMessage('The algorithm (invalid-algorithm) selected is not available');

        $config = ['algorithm' => 'invalid-algorithm'];

        $this->adapter->setConfig($config);
    }

    /**
     * @dataProvider dataSetAdapterConfig
     */
    public function testSetAdapterConfig($config)
    {
        $this->assertInstanceOf(get_class($this->adapter), $this->adapter->setConfig($config));
    }

    public function dataSetAdapterConfig()
    {
        return [
            [['algorithm' => 'sha512']],
            [['num-first-iterations' => 10]],
            [['num-second-iterations' => 20]],
            [['num-final-iterations' => 30]],
        ];
    }

    public function testEncodeWithNonEncodableEntityExpectsHmacInvalidArgumentException()
    {
        $this->expectException(HmacInvalidArgumentException::class);
        $this->expectExceptionMessage('The item is not encodable, make sure the key, time and data are set');

        $entity = $this->mockEntity();

        $this->adapter->setEntity($entity);
        $this->adapter->encode();
    }

    /**
     * @dataProvider dataValidEncodeDetails
     */
    public function testEncode($key, $data, $time, $hmac)
    {
        $entity = $this->mockEntity();

        $entity->setKey($key);
        $entity->setData($data);
        $entity->setTime($time);

        $this->adapter->setEntity($entity);

        $response = $this->adapter->encode();

        $this->assertInstanceOf(get_class($this->adapter), $response);
        $this->assertTrue($entity->isEncoded());
        $this->assertSame($hmac, $entity->getHmac());
    }
}
