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
     * The cost that will be applied to the hashing algorithm
     *
     * @var int
     */
    protected $cost = 10;

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

        if (isset($config['cost'])) {
            $this->cost = $config['cost'];
        }

        return $this;
    }

    /**
     * Encodes the HMAC based on the values that have been entered using the hash() function
     *
     * @throws HmacInvalidArgumentException
     * @return AbstractAdapter
     */
    public function encode()
    {
        if (!$this->entity->isEncodable()) {
            throw new HmacInvalidArgumentException(
                'The HMAC is not encodable, make sure the key, time and data are set'
            );
        }

        $this->entity->setHmac(
            $this->hash(
                $this->hash($this->entity->getData(), $this->entity->getTime(), $this->cost),
                $this->hash($this->entity->getKey(), '', $this->cost),
                $this->cost
            )
        );

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
