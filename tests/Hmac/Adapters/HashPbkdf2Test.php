<?php

use Mardy\Hmac\Adapters\HashPbkdf2;

class HashPbkdf2Test extends BaseAdapterTest
{
    public function setup()
    {
        $this->adapter = new HashPbkdf2;
    }

    public function dataValidEncodeDetails()
    {
        return [
            [
                self::KEY,
                'sample-data',
                1440880779.1754,
                '76d0dcc8975b43208354a2f3239ba57423f13bce1c8bbbfa199f7c7a65f4ef63eb9dd83e434347c797cf3412df6c0e931fcdd39c28a1f46fd46e1341fc120813'
            ],
            [
                self::KEY,
                'more-sample-data',
                1440880788.8208,
                '6ded0ba9862079c9b824ca0b8d8830e9a22248ef81eed37ce34745b718098067748de08c314cd0f7e445972291617d62a5dad615fcae9abc4a7b55d1cdd5f216'
            ],
            [
                self::KEY,
                'even-more-sample-data',
                1440880795.3669,
                'fcfd620591d65498ab344aede163a7345a6b127decf51c5f14ff1d534f0026c9ff645946ca64444ca5322953d7f4a78f630e5d635799a990f3c84eac83d56055'
            ],
        ];
    }
}
