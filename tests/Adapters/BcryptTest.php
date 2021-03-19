<?php

namespace MardyHmacTest\Adapters;

use Mardy\Hmac\Adapters\Bcrypt;
use Mardy\Hmac\Exceptions\HmacInvalidAlgorithmException;

class BcryptTest extends BaseAdapterTest
{
    public function setup(): void
    {
        $this->adapter = new Bcrypt;
    }

    public function testSetAlgorithmWithInvalidAlgorithmExpectsHmacInvalidAlgorithmException()
    {
        $this->expectException(HmacInvalidAlgorithmException::class);
        $this->expectExceptionMessage('The algorithm selected is not available');

        $config = ['algorithm' => 'invalid-algorithm'];

        $this->adapter->setConfig($config);
    }

    public function dataSetAdapterConfig()
    {
        return [
            [['algorithm' => PASSWORD_DEFAULT]],
            [['num-first-iterations' => 10]],
            [['num-second-iterations' => 20]],
            [['num-final-iterations' => 30]],
        ];
    }

    public function dataValidEncodeDetails()
    {
        return [];
    }
}
