<?php

namespace Mardy\Hmac\Adapters;

/**
 * HashHmac Adapter
 *
 * @package Mardy\Hmac\Adapters
 * @author Michael Bardsley @mic_bardsley
 */
class HashHmac extends AbstractAdapter
{
    /**
     * Iterate and hash the data multiple times
     *
     * @param string $data the string of data that will be hashed
     * @param string $salt
     * @param int $iterations the number of iterations required
     * @return string
     */
    protected function hash($data, $salt = '', $iterations = 10)
    {
        $hash = $data;
        foreach (range(1, $iterations) as $i) {
            $hash = hash_hmac($this->algorithm, $hash, $salt . md5($i));
        }

        return $hash;
    }
}
