<?php

namespace Mardy\Hmac\Adapters;

use Mardy\Hmac\Entity;

/**
 * Class AbstractAdapter
 *
 * @package Mardy\Hmac\Adapters
 * @author Michael Bardsley @mic_bardsley
 */
abstract class AbstractAdapter implements AdapterInterface
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
     * @var Entity
     */
    protected $entity;

    /**
     * The number of times the first hash will be iterated
     *
     * @var int
     */
    protected $noFirstHashIterations = 10;

    /**
     * The number of times the second hash will be iterated
     *
     * @var int
     */
    protected $noSecondHashIterations = 10;

    /**
     * The number of times the final hash will be iterated
     *
     * @var int
     */
    protected $noFinalHashIterations = 100;

    /**
     * Sets the data that will be used in the hash
     *
     * @param \Mardy\Hmac\Entity $entity
     * @return AbstractAdapter
     */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Sets the adapter config options
     *
     * @param array $config
     * @return AbstractAdapter
     */
    public function setConfig(array $config)
    {
        if (isset($config['algorithm'])) {
            $this->setAlgorithm($config['algorithm']);
        }

        if (isset($config['num-first-iterations'])) {
            $this->noFirstHashIterations = $config['num-first-iterations'];
        }

        if (isset($config['num-second-iterations'])) {
            $this->noSecondHashIterations = $config['num-second-iterations'];
        }

        if (isset($config['num-final-iterations'])) {
            $this->noFinalHashIterations = $config['num-final-iterations'];
        }

        return $this;
    }

    /**
     * Encodes the HMAC based on the values that have been entered using the hash() function
     *
     * @throws \InvalidArgumentException
     * @return AbstractAdapter
     */
    public function encode()
    {
        if (! $this->entity->isEncodable()) {
            throw new \InvalidArgumentException(
                'The item is not encodable, make sure the key, time and data are set'
            );
        }

        $firstHash = $this->hash($this->entity->getData(), $this->entity->getTime(), $this->noFirstHashIterations);
        $secondHash = $this->hash($this->entity->getKey(), '', $this->noSecondHashIterations);
        $this->entity->setHmac($this->hash($firstHash, $secondHash, $this->noFinalHashIterations));

        return $this;
    }

    /**
     * @param $data
     * @param string $salt
     * @param int $iterations
     * @return mixed
     */
    abstract protected function hash($data, $salt = '', $iterations = 10);

    /**
     * Set the algorithm
     *
     * @param string $algorithm
     * @return AbstractAdapter
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
     * @throws \InvalidArgumentException
     */
    protected function validateHashAlgorithm($algorithm)
    {
        $algorithm = strtolower($algorithm);
        if (!in_array($algorithm, hash_algos())) {
            throw new \InvalidArgumentException(sprintf(self::ERROR_INVALID_ALGORITHM, $algorithm));
        }
    }
}
