<?php

use Mardy\Hmac\Adapters\Bcrypt;

class BcryptTest extends BaseAdapterTest
{
    public function setup()
    {
        $this->adapter = new Bcrypt;
    }

    public function testSetAlgorithmWithInvalidAlgorithmExpectsHmacInvalidAlgorithmException()
    {
        $this->setExpectedException(
            'Mardy\Hmac\Exceptions\HmacInvalidAlgorithmException',
            'The algorithm selected is not available'
        );

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
        return [
            [
                self::KEY,
                'sample-data',
                1440879450.5018,
                '$2y$10$2370a73b8d20ab0d40c65O2ShzeDBOC/KOFRcEs6d4QWqsBZHW0HC'
            ],
            [
                self::KEY,
                'more-sample-data',
                1440879580.054,
                '$2y$10$2370a73b8d20ab0d40c65OC30aTM8Q9V.sRafXn.Kgpjlc897mOyu'
            ],
            [
                self::KEY,
                'even-more-sample-data',
                1440879612.5178,
                '$2y$10$2370a73b8d20ab0d40c65OM89nzAYhpmtevg.TSe9M7u1xTzPhp9O'
            ],
        ];
    }
}
