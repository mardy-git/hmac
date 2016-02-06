<?php

namespace Mardy\Hmac\Strategy;

use Mardy\Hmac\Exceptions\HmacInvalidAlgorithmException;
use Mardy\Hmac\Exceptions\HmacMissingDependencyException;

/**
 * Bcrypt Strategy
 *
 * @package Mardy\Hmac\Strategy
 * @author Michael Bardsley @mic_bardsley
 */
class Bcrypt extends AbstractStrategy
{
    /**
     * The algorithm that will be used by the hash function, this is validated using the
     *
     * @var string
     */
    protected $algorithm = PASSWORD_DEFAULT;

    /**
     * {@inherited}
     */
    protected $noFinalHashIterations = 10;

    /**
     * Bcrypt constructor.
     */
    public function __construct()
    {
        if (!function_exists('password_hash')) {
            throw new HmacMissingDependencyException('The password_hash function is unavailable on this server');
        }
    }

    /**
     * hash the data using the password_hash function with multiple iterations
     *
     * @param string $data the string of data that will be hashed
     * @param string $salt
     * @param int $cost the number of iterations required
     * @return string
     */
    protected function hash($data, $salt = '', $cost = 10)
    {
        return password_hash($data . $salt, $this->algorithm, ['cost' => $cost, 'salt' => md5($salt)]);
    }

    /**
     * Sets the algorithm that will be used by the encoding process
     *
     * @param string $algorithm
     * @return Bcrypt
     * @throws HmacInvalidAlgorithmException
     */
    protected function setAlgorithm($algorithm)
    {
        if ($algorithm !== PASSWORD_DEFAULT && $algorithm !== PASSWORD_BCRYPT) {
            throw new HmacInvalidAlgorithmException('The algorithm selected is not available');
        }
        $this->algorithm = $algorithm;

        return $this;
    }
}
