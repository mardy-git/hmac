<?php

use Mardy\Hmac\Manager;
use Mardy\Hmac\Adapters\Hash;

class ManagerTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var \Mardy\Hmac\Manager
     */
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

        $this->assertTrue($this->manager->encode()->isValid('367aa96189a95b92dfd0ec8adb6f84cd37eb58e38745551361e561814c085f8197b54612a481eb9b25f2c3de23a0c836298623348b3e005d52facaaeaff3eb7d'));
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

    public function testSetValidConfig()
    {
        $this->assertInstanceOf(
            "Mardy\Hmac\Manager",
            $this->manager->config([
                'algorithm' => 'sha512',
                'num-first-iterations' => 1,
                'num-second-iterations' => 1,
                'num-final-iterations' => 1,
            ])
        );
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

        $this->assertSame('test', $hmac['data']);
        $this->assertSame('367aa96189a95b92dfd0ec8adb6f84cd37eb58e38745551361e561814c085f8197b54612a481eb9b25f2c3de23a0c836298623348b3e005d52facaaeaff3eb7d', $hmac['hmac']);
        $this->assertSame(1396901689, $hmac['time']);
    }
}
