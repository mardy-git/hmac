<?php

use Mardy\Hmac\Strategy\HashHmacStrategy;

class HashHmacStrategyTest extends BaseStrategyTest
{
    public function setup()
    {
        if (!function_exists('hash_hmac')) {
            $this->markTestSkipped(
                'The hash_hmac extension is not available.'
            );
        }

        $this->adapter = new HashHmacStrategy;
    }

    public function dataValidEncodeDetails()
    {
        return [
            [
                self::KEY,
                'sample-data',
                1440880317.8075,
                'c342dfbd714c5363a551c58f45ed2256c74af672df18511e1c3a0884efe543bb24f41ac277a270e9bbe8027e090a0f7edb42813f3a716ac759f91a7b658c5379'
            ],
            [
                self::KEY,
                'more-sample-data',
                1440880330.3181,
                '7a72406ee800aed2511f20d2c1283503c6583d967c52c179a5c2a5b878878177967367c276915a1953fffa3753df1a57a80cdc213b57bf3ea7e32861a6a6c767'
            ],
            [
                self::KEY,
                'even-more-sample-data',
                1440880340.3593,
                '2665e29680d25ee2ec98889f4f8f6bfb5ada290d0b64cf256c1234387a310f143623bab0fccc4b26db8866a808fd887a0fc71e3b6efbd487b21af53359f78f2b'
            ],
        ];
    }
}
