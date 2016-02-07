<?php

use Mardy\Hmac\HashEncoder;
use Mardy\Hmac\Strategy\HashStrategy;

class HashEncoderTest extends \PHPUnit_Framework_Testcase
{
    /**
     * @var \Mardy\Hmac\HashEncoder
     */
    protected $manager;

    public function setup()
    {
        $this->manager = new HashEncoder(new HashStrategy);
    }

    public function testEncodeAndIsValid()
    {

        $this->manager->ttl(0)
                      ->data('test')
                      ->time(1396901689)
                      ->key('1234');

        $this->assertTrue($this->manager->encode()->valid('b72ef195af1901de1d52ecbc02104dc53642d579345a9899d9535385a3791de237c323110342d124dedc83a63b596ed43bbcd7eddf5c5f4fe0626bd7021516b5') == null);

    }

    /**
     * @expectedException Mardy\Hmac\Exceptions\HmacRequestTimeoutException
     */
    public function testEncodeAndNotValidTimedOut()
    {
        $this->manager->ttl(1)
            ->data('test')
            ->time(1396901689)
            ->key('1234');

        $this->manager->encode()->valid('f22081d5fcdf64e3ee78e79d235f67b2d1a54ba24be6da4ac554976d313e07cf119731e76585b9b22f789c6043efe1ff133497483f559899db7d2f43258276b08');
    }

    /**
     * @expectedException Mardy\Hmac\Exceptions\HmacInvalidHashException
     */
    public function testEncodeAndNotValidNonMatchingHashes()
    {
        $this->manager->ttl(0)
                      ->data('test')
                      ->time(1396901689)
                      ->key('1234');

        $this->manager->encode()->valid('f22081d5fcdf64e3ee78e79d235f67b2d1a54ba24be6da4ac554976d313e07cf119731e76585b9b22f789c6043efe1ff133497483f559899db7d2f43258276b08');
    }

    /**
     * @expectedException Mardy\Hmac\Exceptions\HmacInvalidArgumentException
     */
    public function testEncodeWithNoParametersThrowException()
    {
        $this->manager->encode();
    }

    /**
     * @expectedException Mardy\Hmac\Exceptions\HmacInvalidAlgorithmException
     */
    public function testInvalidAlgorithmException()
    {
        $this->manager->config(['algorithm' => 'invalid-algorithm']);
    }

    public function testSetValidConfig()
    {
        $this->assertInstanceOf(
            'Mardy\Hmac\HashEncoder',
            $this->manager->config([
                'algorithm' => 'sha512',
                'cost' => 1,
            ])
        );
    }

    public function testToArrayWithoutEncodeFalse()
    {
        $this->assertFalse($this->manager->toArray());
    }

    public function testToArrayEncodedArray()
    {
        $this->manager->ttl(0)
                      ->data('test')
                      ->time(1396901689)
                      ->key('1234')
                      ->encode();

        $this->assertInstanceOf('Mardy\Hmac\HashDataHandler', $this->manager->getHashDataHandler());
        $hmac = $this->manager->toArray();

        $this->assertTrue(isset($hmac['data'], $hmac['hmac'], $hmac['time']));

        $this->assertSame('test', $hmac['data']);
        $this->assertSame('b72ef195af1901de1d52ecbc02104dc53642d579345a9899d9535385a3791de237c323110342d124dedc83a63b596ed43bbcd7eddf5c5f4fe0626bd7021516b5', $hmac['hmac']);
        $this->assertSame(1396901689, $hmac['time']);
    }
}
