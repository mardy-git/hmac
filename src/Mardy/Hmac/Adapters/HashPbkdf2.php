<?php

namespace Mardy\Hmac\Adapters;

/**
 * HashPbkdf2 Adapter
 *
 * @package Mardy\Hmac\Adapters
 * @author Michael Bardsley @mic_bardsley
 */
class HashPbkdf2 extends AbstractAdapter
{
    /**
     * hash the data using the hash_pbkdf2 function with multiple iterations
     *
     * @param string $data the string of data that will be hashed
     * @param string $salt
     * @param int $iterations the number of iterations required
     * @return string
     */
    protected function hash($data, $salt = '', $iterations = 10)
    {
        return hash_pbkdf2($this->algorithm, $data, $salt, $iterations);
    }

    /**
     * Sets the algorithm that will be used by the encoding process
     *
     * @param string $algorithm
     * @return \Mardy\Hmac\Adapters\HashPbkdf2
     * @throws \InvalidArgumentException
     */
    protected function setAlgorithm($algorithm)
    {
        $algorithm = strtolower($algorithm);
        if (! in_array($algorithm, hash_algos())) {
            throw new \InvalidArgumentException("The algorithm ({$algorithm}) selected is not available");
        }
        $this->algorithm = $algorithm;

        return $this;
    }
}
