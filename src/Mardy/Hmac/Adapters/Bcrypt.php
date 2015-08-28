<?php

namespace Mardy\Hmac\Adapters;

/**
 * Bcrypt Adapter
 *
 * @package Mardy\Hmac\Adapters
 * @author Michael Bardsley @mic_bardsley
 */
class Bcrypt extends AbstractAdapter
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
     * hash the data using the password_hash function with multiple iterations
     *
     * @param string $data the string of data that will be hashed
     * @param string $salt
     * @param int $iterations the number of iterations required
     * @return string
     */
    protected function hash($data, $salt = '', $iterations = 10)
    {
        return password_hash($data . $salt, $this->algorithm, ['cost' => $iterations, 'salt' => md5($salt)]);
    }

    /**
     * Sets the algorithm that will be used by the encoding process
     *
     * @param string $algorithm
     * @return Bcrypt
     * @throws \InvalidArgumentException
     */
    protected function setAlgorithm($algorithm)
    {
        if ($algorithm !== PASSWORD_DEFAULT && $algorithm !== PASSWORD_BCRYPT) {
            throw new \InvalidArgumentException("The algorithm selected is not available");
        }
        $this->algorithm = $algorithm;

        return $this;
    }
}
