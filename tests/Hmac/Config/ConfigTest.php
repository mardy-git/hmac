<?php

use Mardy\Hmac\Config\Config;

class ConfigTest extends PHPUnit_Framework_Testcase
{
    protected $config;

    public function setup()
    {
        $this->config = new Config;

        $this->config->setKey('wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb');
    }

    public function testGetKey()
    {
        $config = clone $this->config;

        //if the key changes in the config.php file this will also need to be changed so the test will pass
        $this->assertSame("wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb", $config->getKey());
    }

    public function testGetValidFor()
    {
        $config = clone $this->config;

        //will fail
        $this->assertFalse((20 == $config->getValidityPeriod()));

        //will pass this time
        $this->assertSame(120, $config->getValidityPeriod());
    }

    public function testSetKey()
    {
        $config = clone $this->config;

        $this->assertInstanceOf("Mardy\Hmac\Config\Config", $config->setKey('1234567890'));
        $this->assertSame('1234567890', $config->getKey());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetValidityPeriodException()
    {
        $config = clone $this->config;

        $config->setValidityPeriod('abc1234');
    }

    public function testSetValidityPeriodInstanceOf()
    {
        $config = clone $this->config;

        //set a key so we know what to check for
        $this->assertInstanceOf('Mardy\Hmac\Config\Config', $config->setValidityPeriod(150));
    }

    public function testGetValidityPeriodSame()
    {
        $config = clone $this->config;

        //set a key so we know what to check for
        $config->setValidityPeriod(520);

        //will pass this time
        $this->assertSame(520, $config->getValidityPeriod());
    }

    public function testGetAlgorithmSame()
    {
        $config = clone $this->config;

        $this->assertSame('sha512', $config->getAlgorithm());
    }

    public function testGetAlgorithmNotSame()
    {
        $config = clone $this->config;

        $this->assertNotSame('md5', $config->getAlgorithm());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetAlgorithmException()
    {
        $config = clone $this->config;

        $config->setAlgorithm('mike1');
    }

    public function testSetAlgorithmReturn()
    {
        $config = clone $this->config;

        $this->assertInstanceOf("Mardy\Hmac\Config\Config", $config->setAlgorithm('sha256'));
        $this->assertSame('sha256', $config->getAlgorithm());
    }
}
