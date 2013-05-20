<?php

use Scc\Hmac\Config\Config;

class ConfigValuesTest
    extends PHPUnit_Framework_Testcase
{
    protected $config;

    public function setup()
    {
        $this->config = new Config;
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
        $this->assertInstanceOf('Scc\Hmac\Config\Config', $config->setValidityPeriod(150));
    }

    public function testGetValidityPeriodSame()
    {
        $config = clone $this->config;

        //set a key so we know what to check for
        $config->setValidityPeriod(520);

        //will pass this time
        $this->assertSame(520, $config->getValidityPeriod());
    }
}