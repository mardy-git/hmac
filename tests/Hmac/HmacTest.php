<?php

use Mardy\Hmac\Hmac;
use Mardy\Hmac\Headers\Values as HeaderValues;

class HmacTest
    extends PHPUnit_Framework_Testcase
{
    protected $hmac;

    protected $config;

    protected $storage;

    protected $generatedHmac;

    protected $when;

    protected $uri;

    protected $algorithm;

    public function setup()
    {
        $this->config = $this->getMockBuilder('Mardy\Hmac\Config\Config')
                             ->setMethods(['getValidFor', 'getKey'])
                             ->getMock();

        $this->config->expects($this->any())
                     ->method('getKey')
                     ->will($this->returnValue("wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb"));

        $this->storage = $this->getMockBuilder('Mardy\Hmac\Storage\NonPersistent')
                              ->setMethods(['getHmac', 'getUri', 'getTimestamp'])
                              ->getMock();

        $this->hmac = new Hmac($this->config, $this->storage, new HeaderValues);

        $this->algorithm = 'sha512';
    }

    public function testCheckExceptionNoHmac()
    {
        $this->assertFalse($this->hmac->check());

        $this->assertSame(
            'An attempt to assign a null HMAC key was detected',
            $this->hmac->getError()
        );
    }

    public function testCheckExceptionNoUri()
    {
        $this->storage->expects($this->any())
                      ->method('getHmac')
                      ->will($this->returnValue("12345678-1234-1234-1234-123456789012"));

        $this->config = $this->getMockBuilder('Mardy\Hmac\Config\Config')
                             ->setMethods(['getValidFor', 'getKey'])
                             ->getMock();

        $this->assertFalse($this->hmac->check());

        $this->assertSame(
            'No URI was set when an HMAC check was attempted',
            $this->hmac->getError()
        );
    }

    public function testCheckExceptionNoTimestamp()
    {
        $this->storage->expects($this->any())
                      ->method('getHmac')
                      ->will($this->returnValue("12345678-1234-1234-1234-123456789012"));

        $this->storage->expects($this->any())
                      ->method('getUri')
                      ->will($this->returnValue("user/1/role/1"));

        $this->assertFalse($this->hmac->check());

        $this->assertSame(
            'No TimeStamp was set when an HMAC check was attempted',
            $this->hmac->getError()
        );
    }

    public function testCheckExceptionOverdueTimestamp()
    {
        $this->storage->expects($this->any())
                      ->method('getHmac')
                      ->will($this->returnValue("12345678-1234-1234-1234-123456789012"));

        $this->storage->expects($this->any())
                      ->method('getUri')
                      ->will($this->returnValue("user/1/role/1"));

        $this->storage->expects($this->any())
                      ->method('getTimestamp')
                      ->will($this->returnValue(time() - 3000));

        $this->assertFalse($this->hmac->check());

        $this->assertSame(
            'The request has taken to long',
            $this->hmac->getError()
        );
    }

    public function testCheckHmacFail()
    {
        $this->setup();
        $this->generateFailedHmac();

        $this->hmac->check();

        $this->assertSame(
            'HMAC is invalid',
            $this->hmac->getError()
        );
    }

    public function testCheckHmacPass()
    {
        $this->generatePassedHmac();

        $this->assertTrue($this->hmac->check());
    }

    public function testCreateNoUriFail()
    {
        $this->assertFalse($this->hmac->create());

        $this->assertSame(
            'No URI was set when an HMAC check was attempted',
            $this->hmac->getError()
        );

    }

    public function testCreateNoTimestampFail()
    {
        $this->storage->expects($this->any())
                      ->method('getUri')
                      ->will($this->returnValue("user/1/role/1"));

        $this->assertFalse($this->hmac->create());

        $this->assertSame(
            'No TimeStamp was set when an HMAC check was attempted',
            $this->hmac->getError()
        );
    }

    public function testCreateTooLongFail()
    {
        //the uri that will be used in the HMAC
        $this->uri = 'user/1/role/1';

        //the timestamp that is used to secure the HMAC - 1 hour so it fails
        $this->when = time() - 3600;

        //the private key, this is used at both the client and server sides
        $key = 'wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb';

        //the first has contains the URI and timestamps that have been set
        $firsthash = hash("sha512", $this->uri . "@" . $this->when);

        //the second has the private key
        $secondhash = hash("sha512", $key);

        //returned is an hash of both the previous hashes
        $this->generatedHmac = hash("sha512", $firsthash . "-" . $secondhash);

        $this->storage->expects($this->any())
                      ->method('getHmac')
                      ->will($this->returnValue($this->generatedHmac));

        $this->storage->expects($this->any())
                      ->method('getUri')
                      ->will($this->returnValue($this->uri));

        $this->storage->expects($this->any())
                      ->method('getTimestamp')
                      ->will($this->returnValue($this->when));

        $this->config->expects($this->any())
                     ->method('getKey')
                     ->will($this->returnValue($key));

        $this->assertFalse($this->hmac->create());

        $this->assertSame(
            'The request has taken to long',
            $this->hmac->getError()
        );
    }

    public function testCreatePass()
    {
        $this->generatePassedHmac();

        (new HeaderValues)->setKey($this->generatedHmac)
                          ->setUri($this->uri)
                          ->setWhen($this->when);

        $hmac = $this->hmac->create();

        $sample = [
            'KEY: ' . $this->generatedHmac,
            'WHEN: ' . $this->when,
            'URI: ' . $this->uri,
        ];

        $this->assertSame($hmac->toArray(), $sample);
    }

    public function testNoKeySet()
    {
        $this->config = $this->getMockBuilder('Mardy\Hmac\Config\Config')
                             ->setMethods(['getValidFor', 'getKey'])
                             ->getMock();

        $this->config->expects($this->any())
                     ->method('getKey')
                     ->will($this->returnValue(null));

        $this->storage = $this->getMockBuilder('Mardy\Hmac\Storage\NonPersistent')
                              ->setMethods(['getHmac', 'getUri', 'getTimestamp'])
                              ->getMock();

        $this->hmac = new Hmac($this->config, $this->storage, new HeaderValues);

        $this->assertFalse($this->hmac->create());

        $this->assertSame(
            'No private key has been set',
            $this->hmac->getError()
        );
    }

    protected function generateFailedHmac()
    {
        //the uri that will be used in the HMAC
        $this->uri = 'user/1/role/1';

        //the timestamp that is used to secure the HMAC
        $this->when = time();

        //the private key, this is used at both the client and server sides
        $key = 'abc1234';

        $hash = $this->encode($key);

        //returned is an hash of both the previous hashes
        $this->generatedHmac = $hash;

        //changed to produce the invalid hmac
        $uri = 'user/1/role/2';

        $this->storage->expects($this->any())
                      ->method('getHmac')
                      ->will($this->returnValue($this->generatedHmac));

        $this->storage->expects($this->any())
                      ->method('getUri')
                      ->will($this->returnValue($this->uri));

        $this->storage->expects($this->any())
                      ->method('getTimestamp')
                      ->will($this->returnValue($this->when));

        $this->config->expects($this->any())
                     ->method('getKey')
                     ->will($this->returnValue($key));

        $this->hmac = new Hmac($this->config, $this->storage, new HeaderValues);
    }

    protected function generatePassedHmac()
    {
        //the uri that will be used in the HMAC
        $this->uri = 'user/1/role/1';

        //the timestamp that is used to secure the HMAC
        $this->when = time();

        //the private key, this is used at both the client and server sides
        $key = 'wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb';

        $hash = $this->encode($key);

        //returned is an hash of both the previous hashes
        $this->generatedHmac = $hash;

        $this->storage->expects($this->any())
                      ->method('getHmac')
                      ->will($this->returnValue($this->generatedHmac));

        $this->storage->expects($this->any())
                      ->method('getUri')
                      ->will($this->returnValue($this->uri));

        $this->storage->expects($this->any())
                      ->method('getTimestamp')
                      ->will($this->returnValue($this->when));

        $this->config->expects($this->any())
                     ->method('getKey')
                     ->will($this->returnValue($key));

        $this->hmac = new Hmac($this->config, $this->storage, new HeaderValues);
    }

    protected function encode ($key)
    {
        //the first has contains the URI and timestamps that have been set
        $firsthash = hash(
            $this->algorithm,
            $this->uri . "@" . $this->when
        );

        //loop to make it hard to crack this hash
        for ($i = 0; $i < 10; $i++) {
            $firsthash = hash(
                $this->algorithm,
                $firsthash
            );
        }

        //the second has the private key
        $secondhash = hash(
            $this->algorithm,
            $key
        );

        //loop to make it hard to crack this hash
        for ($i = 0; $i < 10; $i++) {
            $secondhash = hash(
                $this->algorithm,
                $secondhash
            );
        }

        //returned is an hash of both the previous hashes
        $finalhash = hash(
            $this->algorithm,
            $firsthash . "-" . $secondhash
        );

        //loop to further encode the HMAC key, this will make it harder to crack
        for ($i = 0; $i < 100; $i++) {
            $finalhash = hash(
                $this->algorithm,
                $finalhash
            );
        }

        return $finalhash;
    }
}
