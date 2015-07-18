<?php

namespace Mardy\Hmac\Adapters;

use Mardy\Hmac\Adapters\AdapterInterface;
use Mardy\Hmac\Entity;

/**
 * Class AbstractAdapter
 *
 * @package Mardy\Hmac\Adapters
 * @author Michael Bardsley @mic_bardsley
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * The algorithm that will be used by the hash function, this is validated using the
     *
     * @var string
     */
    protected $algorithm = 'sha512';

    /**
     * The data that will be used in the hash, this will need to be sent with the HTTP request
     *
     * @var \Mardy\Hmac\Entity
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
     * @return $this
     */
    public function setEntity(Entity &$entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Sets the adapter config options
     *
     * @param array $config
     * @return $this
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
     * @return string
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

    abstract protected function hash($data, $salt = '', $iterations = 10);
}
