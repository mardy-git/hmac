<?php

namespace Mardy\Hmac\Plugin;;

use Mardy\Hmac\Manager;
use Mardy\Hmac\Adapters\AdapterInterface;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\SubscriberInterface;

/**
 * HmacHeadersGuzzleEvent Class
 *
 * @package Mardy\Hmac\Plugin
 * @author Michael Bardsley @mic_bardsley
 */
class HmacHeadersGuzzleEvent implements SubscriberInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $data;

    /**
     * @var int|float
     */
    private $time;

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter
     * @param string $key
     * @param string $data
     * @param null|int|float $time
     */
    public function __construct(AdapterInterface $adapter, $key, $data, $time = null)
    {
        !$time && $time = microtime(true);

        $this->adapter = $adapter;
        $this->key = (string) $key;
        $this->data = (string) $data;
        $this->time = is_float($time) ? (float) $time : (int) $time;
    }

    /**
     * {@inherit}
     *
     * @return array
     */
    public function getEvents()
    {
        return ['before' => ['onBefore', 100]];
    }

    /**
     * The onBefore event that will add the hmac errors to the request headers
     *
     * @param BeforeEvent $event
     */
    public function onBefore(BeforeEvent $event)
    {
        if ($event !== null && $event->getRequest() !== null) {
            $hmac = (new Manager($this->adapter))
                ->key($this->key)
                ->data($this->data)
                ->time($this->time)
                ->encode()
                ->getHmac();

            $event->getRequest()->setHeader('hmac', $hmac->getHmac());
            $event->getRequest()->setHeader('data', $hmac->getData());
            $event->getRequest()->setHeader('time', $hmac->getTime());
        }
    }
}