<?php

namespace Mardy\Hmac\Strategy;

use Mardy\Hmac\HashDataHandler;
use Mardy\Hmac\Exceptions\HmacInvalidAlgorithmException;
use Mardy\Hmac\Exceptions\HmacInvalidArgumentException;

/**
 * Class AbstractStrategy
 *
 * @package Mardy\Hmac\Strategy
 * @author Michael Bardsley @mic_bardsley
 */
abstract class AbstractStrategy implements StrategyInterface
{
    const ERROR_INVALID_ALGORITHM = 'The algorithm (%s) selected is not available';

    /**
     * The algorithm that will be used by the hash function, this is validated using the
     *
     * @var string
     */
    protected $algorithm = 'sha512';

    /**
     * The data that will be used in the hash, this will need to be sent with the HTTP request
     *
     * @var HashDataHandler
     */
    protected $hashDataHandler;

    /**
     * The cost that will be applied to the hashing algorithm
     *
     * @var int
     */
    protected $cost = 10;

    /**
     * Sets the data that will be used in the hash
     *
     * @param \Mardy\Hmac\HashDataHandler $hashDataHandler
     * @return AbstractStrategy
     */
    public function setHashDataHandler(HashDataHandler $hashDataHandler)
    {
        $this->hashDataHandler = $hashDataHandler;

        return $this;
    }

    /**
     * Gets the cost that is used when calculating the hash
     *
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Sets the Strategy config options
     *
     * @param array $config
     * @return AbstractStrategy
     */
    public function setConfig(array $config)
    {
        if (isset($config['algorithm'])) {
            $this->setAlgorithm($config['algorithm']);
        }

        if (isset($config['cost'])) {
            $this->cost = $config['cost'];
        }

        return $this;
    }

    /**
     * Encodes the HMAC based on the values that have been entered using the hash() function
     *
     * @throws HmacInvalidArgumentException
     * @return AbstractStrategy
     */
    public function encode()
    {
        if (!$this->hashDataHandler->isEncodable()) {
            throw new HmacInvalidArgumentException(
                'The HMAC is not encodable, make sure the key, time and data are set'
            );
        }

        $this->hashDataHandler->setHmac(
            $this->hash(
                $this->hash($this->hashDataHandler->getData(), $this->hashDataHandler->getTime()),
                $this->hash($this->hashDataHandler->getKey(), '')
            )
        );

        return $this;
    }

    /**
     * @param $data
     * @param string $salt
     * @return mixed
     */
    abstract public function hash($data, $salt = '');

    /**
     * Set the algorithm
     *
     * @param string $algorithm
     * @return AbstractStrategy
     */
    protected function setAlgorithm($algorithm)
    {
        $this->validateHashAlgorithm($algorithm);
        $this->algorithm = (string) $algorithm;

        return $this;
    }

    /**
     * Validates the algorithm against the hash_algos() function
     *
     * @param string $algorithm
     * @throws HmacInvalidAlgorithmException
     */
    protected function validateHashAlgorithm($algorithm)
    {
        $algorithm = strtolower($algorithm);
        if (!in_array($algorithm, hash_algos())) {
            throw new HmacInvalidAlgorithmException(sprintf(self::ERROR_INVALID_ALGORITHM, $algorithm));
        }
    }
}
