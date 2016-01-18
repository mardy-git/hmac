<?php

use Mardy\Hmac\HashDataHandler;

abstract class BaseAdapterTest extends \PHPUnit_Framework_Testcase
{
    const KEY = 'wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb';

    protected $adapter;

    /**
     * @return HashDataHandler
     */
    protected function mockHashDataHandler()
    {
        return new HashDataHandler;
    }

    public function testSetEntity()
    {
        $this->assertInstanceOf(get_class($this->adapter), $this->adapter->setHashDataHandler($this->mockHashDataHandler()));
    }

    public function testSetAlgorithmWithInvalidAlgorithmExpectsHmacInvalidAlgorithmException()
    {
        $this->setExpectedException(
            'Mardy\Hmac\Exceptions\HmacInvalidAlgorithmException',
            'The algorithm (invalid-algorithm) selected is not available'
        );

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
        $this->setExpectedException(
            'Mardy\Hmac\Exceptions\HmacInvalidArgumentException',
            'The item is not encodable, make sure the key, time and data are set'
        );

        $entity = $this->mockHashDataHandler();

        $this->adapter->setHashDataHandler($entity);
        $this->adapter->encode();
    }

    /**
     * @dataProvider dataValidEncodeDetails
     */
    public function testEncode($key, $data, $time, $hmac)
    {
        $hashDataHandler = $this->mockHashDataHandler();

        $hashDataHandler->setKey($key);
        $hashDataHandler->setData($data);
        $hashDataHandler->setTime($time);

        $this->adapter->setHashDataHandler($hashDataHandler);

        $response = $this->adapter->encode();

        $this->assertInstanceOf(get_class($this->adapter), $response);
        $this->assertTrue($hashDataHandler->isEncoded());
        $this->assertSame($hmac, $hashDataHandler->getHmac());
    }
}
