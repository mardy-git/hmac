<?php

namespace MardyHmacTest\Adapters;

use Mardy\Hmac\Adapters\Hash;

class HashTest extends BaseAdapterTest
{
    public function setup(): void
    {
        $this->adapter = new Hash;
    }

    public function dataValidEncodeDetails()
    {
        return [
            [
                self::KEY,
                'sample-data',
                1440879889.6871,
                '3ccd13b7fd3cfab8845891e5fe06c886256a22fd851648793845b96ad1b9281a6d49f4dc5c01bc4f8ee452b755a3bf8e9dc97494b7cc49f5659200f95c316294'
            ],
            [
                self::KEY,
                'more-sample-data',
                1440879915.8978,
                '0a18569617e04f1232f12686a62de31d523652e5d991133a0ed9305546b55a4370a77dfd3126922d76c5a4351f7a6fe99c34eb3833b2b6e71fbc4275baf936f5'
            ],
            [
                self::KEY,
                'even-more-sample-data',
                1440879928.5044,
                '9914e449bf149120b50cf3dd3820eefff3a2f70d31be0316f0465637a574e28a5a69a34a5ebe058fc884f46f53ab683773a5b2d546ba7427978fc220328a5786'
            ],
        ];
    }
}
