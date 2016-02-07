<?php

use Mardy\Hmac\Strategy\HashPbkdf2Strategy;

class HashPbkdf2StrategyTest extends BaseStrategyTest
{
    public function setup()
    {
        if (!function_exists('hash_pbkdf2')) {
            $this->markTestSkipped(
                'The hash_pbkdf2 extension is not available.'
            );
        }

        $this->adapter = new HashPbkdf2Strategy;
    }

    public function dataValidEncodeDetails()
    {
        return [
            [
                self::KEY,
                'sample-data',
                1440880779.1754,
                '768cfba38e249af5f38a7de4fcec80b84f7e32a6828a65ff14d829b8efbe32dae2f7e6f50d5f0176d75e60ac382661daa5139f436a9c4b471ae2c5b5e4664921'
            ],
            [
                self::KEY,
                'more-sample-data',
                1440880788.8208,
                'd3386b1c4128df4b36563657df41575cf6c23987819d7f6efc3b48df23c8ffbfbcf64cf3ba77d3fd51b09a4c1919b62ff1e36993684e54a0078efebc4d9eaf5e'
            ],
            [
                self::KEY,
                'even-more-sample-data',
                1440880795.3669,
                'feda7bb8d4c50339debc393834bc7ed19c008cfc6aacd685b8f729ebb210588383ecb23de05b1d2a8831f00e1fd9736b0e882035b1bc49b1e02742e703e5bd7f'
            ],
        ];
    }
}
