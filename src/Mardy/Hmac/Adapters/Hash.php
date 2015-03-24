<?php

namespace Mardy\Hmac\Adapters;

/**
 * Hash Adapter
 *
 * @package Mardy\Hmac\Adapters
 * @author Michael Bardsley @mic_bardsley
 */
class Hash extends AbstractAdapter
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
            $hash = hash($this->algorithm, $hash . md5($i) . $salt);
        }

        return $hash;
    }
}
