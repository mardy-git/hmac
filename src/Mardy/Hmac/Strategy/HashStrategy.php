<?php

namespace Mardy\Hmac\Strategy;

use Mardy\Hmac\Exceptions\HmacMissingDependencyException;

/**
 * Hash Strategy
 *
 * @package Mardy\Hmac\Strategy
 * @author Michael Bardsley @mic_bardsley
 */
class HashStrategy extends AbstractStrategy
{
    /**
     * Hash constructor.
     */
    public function __construct()
    {
        if (!function_exists('hash')) {
            throw new HmacMissingDependencyException('The hash function is unavailable on this server');
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
            $hash = hash($this->algorithm, $hash . md5($i) . $salt);
        }

        return $hash;
    }
}
