<?php

namespace Mardy\Hmac\Strategy;

use Mardy\Hmac\Exceptions\HmacMissingDependencyException;

/**
 * HashPbkdf2 Strategy
 *
 * @package Mardy\Hmac\Strategy
 * @author Michael Bardsley @mic_bardsley
 */
class HashPbkdf2 extends AbstractStrategy
{
    /**
     * HashPbkdf2 constructor.
     */
    public function __construct()
    {
        if (!function_exists('hash_pbkdf2')) {
            throw new HmacMissingDependencyException('The hash_pbkdf2 function is unavailable on this server');
        }
    }

    /**
     * hash the data using the hash_pbkdf2 function with multiple iterations
     *
     * @param string $data the string of data that will be hashed
     * @param string $salt
     * @param int $cost the number of iterations required
     * @return string
     */
    protected function hash($data, $salt = '', $cost = 10)
    {
        return hash_pbkdf2($this->algorithm, $data, $salt, $cost);
    }
}
