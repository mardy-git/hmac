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

    /**
     * Sets the algorithm that will be used by the encoding process
     *
     * @param string $algorithm
     * @return \Mardy\Hmac\Adapters\HashHmac
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
