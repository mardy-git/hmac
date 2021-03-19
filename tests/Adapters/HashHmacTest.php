<?php

namespace MardyHmacTest\Adapters;

use Mardy\Hmac\Adapters\HashHmac;

class HashHmacTest extends BaseAdapterTest
{
    public function setup(): void
    {
        $this->adapter = new HashHmac;
    }

    public function dataValidEncodeDetails()
    {
        return [
            [
                self::KEY,
                'sample-data',
                1440880317.8075,
                '7306566b5d685d3b789a9571c18e58088b0b89757d08e9e9b3c17f4e8ec9d2161f5810d6e7263f664c1b8b2f25cfc43f75a230d0ff8f19c4865f67bdc41e0bf1'
            ],
            [
                self::KEY,
                'more-sample-data',
                1440880330.3181,
                'bff94a2f09dfa5893711a280e420918e1f0cb867190f1a41e09458d4225e1dffc86aca14a6ea86dbf87330de50193eb23c5d3c2786a20f3be6ff132ab48c5c47'
            ],
            [
                self::KEY,
                'even-more-sample-data',
                1440880340.3593,
                '1ec5fa20950902248364096a52b54a04eb23e8c27431469f0d190a85acc707302dcdde75368e6999b839bc0aca31c3f16d7eabee635ba8cea267f4ac7cb20185'
            ],
        ];
    }
}
