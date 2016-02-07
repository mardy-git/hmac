<?php

namespace Mardy\Hmac\Strategy;

use Mardy\Hmac\Exceptions\HmacMissingDependencyException;

/**
 * HashHmac Strategy
 *
 * @package Mardy\Hmac\Strategy
 * @author Michael Bardsley @mic_bardsley
 */
class HashHmacStrategy extends AbstractStrategy
{
    /**
     * HashHmac constructor.
     */
    public function __construct()
    {
        if (!function_exists('hash_hmac')) {
            throw new HmacMissingDependencyException('The hash_hmac function is unavailable on this server');
        }
    }

    /**
     * Iterate and hash the data multiple times
     *
     * @param string $data the string of data that will be hashed
     * @param string $salt
     * @return string
     */
    public function hash($data, $salt = '')
    {
        $hash = $data;
        foreach (range(1, $this->cost) as $i) {
            $hash = hash_hmac($this->algorithm, $hash, $salt . md5($i));
        }

        return $hash;
    }
}
