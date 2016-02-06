<?php

use Mardy\Hmac\Strategy\Hash;

class HashTest extends BaseStrategyTest
{
    public function setup()
    {
        if (!function_exists('hash')) {
            $this->markTestSkipped(
                'The hash extension is not available.'
            );
        }

        $this->adapter = new Hash;
    }

    public function dataValidEncodeDetails()
    {
        return [
            [
                self::KEY,
                'sample-data',
                1440879889.6871,
                '78f3fa1819a71d6bbdee60580e5bea5d41bee9b11952b94023b5e2281107fc5b24b15e820d5f951c469f3f139f77b327a3f6921ca1bef4f3673bd305b7645bda'
            ],
            [
                self::KEY,
                'more-sample-data',
                1440879915.8978,
                'cc8b1e4447db2fb8a9defbef771ecd6292128631fe6ce3b7fbbecd798cb27624dba59802222fdd60a72181769b52e096bd81f1dccc109beacb900a13986a2c71'
            ],
            [
                self::KEY,
                'even-more-sample-data',
                1440879928.5044,
                'fedff85f530da49732315ff135e94457f0645d015638de937674c72c70ab4782e78ec194e0ca19a98cc19e9769d381009a32d06506b4397a0a297dd7421d63f3'
            ],
        ];
    }
}
