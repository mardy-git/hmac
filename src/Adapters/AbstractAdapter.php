<?php

namespace Mardy\Hmac\Adapters;

use Mardy\Hmac\Entity;
use Mardy\Hmac\Exceptions\HmacInvalidAlgorithmException;
use Mardy\Hmac\Exceptions\HmacInvalidArgumentException;

/**
 * Class AbstractAdapter
 *
 * @package Mardy\Hmac\Adapters
 * @author Michael Bardsley @mic_bardsley
 */
abstract class AbstractAdapter implements AdapterInterface
{
    public const ERROR_INVALID_ALGORITHM = 'The algorithm (%s) selected is not available';

    /**
     * The algorithm that will be used by the hash function, this is validated using the
     *
     * @var string
     */
    protected string $algorithm = 'sha512';

    /**
     * The data that will be used in the hash, this will need to be sent with the HTTP request
     *
     * @var Entity
     */
    protected Entity $entity;

    /**
     * The number of times the first hash will be iterated
     *
     * @var int
     */
    protected int $noFirstHashIterations = 10;

    /**
     * The number of times the second hash will be iterated
     *
     * @var int
     */
    protected int $noSecondHashIterations = 10;

    /**
     * The number of times the final hash will be iterated
     *
     * @var int
     */
    protected int $noFinalHashIterations = 100;

    /**
     * Sets the data that will be used in the hash
     *
     * @param \Mardy\Hmac\Entity $entity
     * @return AbstractAdapter
     */
    public function setEntity(Entity $entity): self
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
    public function setConfig(array $config): self
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
     * @throws HmacInvalidArgumentException
     * @return AbstractAdapter
     */
    public function encode(): self
    {
        if (!$this->entity->isEncodable()) {
            throw new HmacInvalidArgumentException(
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
     * @return string
     */
    abstract protected function hash(string $data, string $salt = '', int $iterations = 10): string;

    /**
     * Set the algorithm
     *
     * @param string $algorithm
     * @return AbstractAdapter
     */
    protected function setAlgorithm($algorithm): self
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
    protected function validateHashAlgorithm(string $algorithm): void
    {
        $algorithm = strtolower($algorithm);
        if (!in_array($algorithm, hash_algos())) {
            throw new HmacInvalidAlgorithmException(sprintf(self::ERROR_INVALID_ALGORITHM, $algorithm));
        }
    }
}
