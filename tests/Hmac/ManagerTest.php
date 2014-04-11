<?php

use Mardy\Hmac\Manager;
use Mardy\Hmac\Adapters\Hash;

class ManagerTest extends PHPUnit_Framework_Testcase
{
    protected $manager;

    public function setup()
    {
        $this->manager = new Manager(new Hash);
    }

    public function testEncodeAndIsValid()
    {
        $this->manager->ttl(0)
                      ->data('test')
                      ->time(1396901689)
                      ->key('1234');

        $this->assertTrue($this->manager->encode()->isValid('f22081d5fcdc64e3ee78e79d235f67b2d1a54ba24be6da4ac537976d313e07cf119731e76585b9b22f789c6043efe1df133497483f559899db7d2f4398084b08'));
    }

    public function testEncodeAndNotValid()
    {
        $this->manager->ttl(0)
                      ->data('test')
                      ->time(1396901689)
                      ->key('1234');

        $this->assertFalse($this->manager->encode()->isValid('f22081d5fcdf64e3ee78e79d235f67b2d1a54ba24be6da4ac554976d313e07cf119731e76585b9b22f789c6043efe1ff133497483f559899db7d2f43258276b08'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEncodeWithNoParametersThrowException()
    {
        $this->manager->encode();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAlgorithmException()
    {
        $this->manager->config(['algorithm' => 'invalid-algorithm']);
    }

    public function testToArrayWithoutEncodeFalse()
    {
        $this->assertFalse($this->manager->toArray());
    }

    public function testToArrayEncodedArray()
    {
        $this->manager->ttl(0)
                      ->data('test')
                      ->time(1396901689)
                      ->key('1234')
                      ->encode();

        $hmac = $this->manager->toArray();

        $this->assertTrue(isset($hmac['data'], $hmac['hmac'], $hmac['time']));

        $this->assertSame($hmac['data'], 'test');
        $this->assertSame($hmac['hmac'], 'f22081d5fcdc64e3ee78e79d235f67b2d1a54ba24be6da4ac537976d313e07cf119731e76585b9b22f789c6043efe1df133497483f559899db7d2f4398084b08');
        $this->assertSame($hmac['time'], 1396901689);

    }
}
