<?php

namespace Mardy\Hmac\Adapters;

use Mardy\Hmac\Adapters\AbstractAdapter;

/**
 * Hash Adapter
 *
 * @author Michael Bardsley <me@mic-b.co.uk>
 */
class Hash extends AbstractAdapter
{
    /**
     * The algorithm that will be used by the hash function, this is validated using the
     *
     * @var type
     */
    protected $algorithm = 'sha512';

    /**
     * Sets the adapter config options
     *
     * @param array $config
     */
    public function setConfig(array $config)
    {
        if (isset($config['algorithm'])) {
            $this->setAlgorithm($config['algorithm']);
        }

        return $this;
    }

    /**
     * Encodes the HMAC based on the values that have been entered using the hash() function
     *
     * http://php.net/manual/en/function.hash.php
     *
     * @return string
     */
    public function encode()
    {
        if (! $this->entity->isEncodable()) {
            throw new \InvalidArgumentException(
                'The item is not encodable, make sure the key, time and data are set'
            );
        }

        $firsthash  = $this->iterateAndHash($this->entity->getData() . '@' . $this->entity->getTime());
        $secondhash = $this->iterateAndHash($this->entity->getKey());
        $this->entity->setHmac($this->iterateAndHash($firsthash . '-' . $secondhash, 100));

        return $this;
    }

    /**
     * Iterate and hash the data multiple times
     *
     * @param string $data the string of data that will be hashed
     * @param int $iterations the number of iterations required
     * @return string
     */
    protected function iterateAndHash($data, $iterations = 10)
    {
        $hash = $data;
        foreach (range(1, $iterations) as $i) {
            $hash = hash($this->algorithm, $hash);
        }

        return $hash;
    }

    /**
     * Sets the algorithm that will be used by the encoding process
     *
     * @param string $algorithm
     * @return \Mardy\Hmac\Adapters\Hash
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
