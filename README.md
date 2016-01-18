Mardy-Git HMAC
==============
[![Build Status](https://travis-ci.org/mardy-git/hmac.png?branch=master)](https://travis-ci.org/mardy-git/hmac)

A simple lightweight HMAC generator and checker.

Currently this is used to authenticate applications to other applications.

NOTE
----
At the moment the Bcrypt adapter is broken on PHP 7. This is due to the changes in the password_hash function. 
I will be working on a fix for this in v3 which will come out soon.

Installation
--------------

To install this use composer by adding

    "mardy-git/hmac": "2.*"

to your composer.json file

Usage Example
--------------------

Generating the HMAC
-------------------
```php
use Mardy\Hmac\Manager;
use Mardy\Hmac\Adapters\Hash;
use Mardy\Hmac\Exceptions\HmacInvalidAlgorithmException;
use Mardy\Hmac\Exceptions\HmacInvalidArgumentException;

//there are several adapters available 'Bcrypt', 'Hash', 'HashHmac', 'HashPbkdf2'
//you can inject any of them into the manager, they all share the same interface
//With the Bcrypt adapter the num of iteration config is applied to the cost
$manager = new Manager(new Hash);

//you can use any of the Hash algorithms that are available on your environment
$config = [
    'algorithm' => 'sha256',
    'num-first-iterations' => 10,
    'num-second-iterations' => 10,
    'num-final-iterations' => 100,
];

//the private key used in both applications to ensure the hash is the same
$key = 'wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb';

try {
    $manager->config($config);
} catch (HmacInvalidAlgorithmException $e) {
    //an HmacInvalidAlgorithmException can be caught here
    //"The algorithm ({$algorithm}) selected is not available"
}

//the secure private key that will be stored locally and not sent in the http headers
$manager->key($key);

//the data to be encoded with the hmac, you could use the URI for this
$manager->data('test');

//the current timestamp, this will be compared in the other API to ensure
$manager->time(microtime(true)); //use time() or micortime(true)

//encodes the hmac if all the requirements have been met
try {
    $manager->encode();
} catch (HmacInvalidArgumentException $e) {
    //an HmacInvalidArgumentException can be caught here
    //'The item is not encodable, make sure the key, time and data are set'
}

$hmac = $manager->toArray();

//these values need to be sent in the http headers of the request so they can
//be received by the api and used to authenticated the request
//$hmac = [
//    'data' => 'test-data', //perhaps the uri or other unique string related to the transaction
//    'time' => 1396901689,
//    'hmac' => 'f22081d5fcdc64e3ee78e79d235f67b2d1a54ba24be6da4ac537976d313e07cf119731e76585b9b22f789c6043efe1df133497483f559899db7d2f4398084b08',
//];
```

Validating the HMAC
-------------------
```php
use Mardy\Hmac\Manager;
use Mardy\Hmac\Adapters\Hash;
use Mardy\Hmac\Exceptions\HmacInvalidAlgorithmException;

//there are several adapters available 'Bcrypt', 'Hash', 'HashHmac', 'HashPbkdf2'
//you can inject any of them into the manager, they all share the same interface
//With the Bcrypt adapter the num of iteration config is applied to the cost
$manager = new Manager(new Hash);

//you can use any of the Hash algorithms that are available on your environment
$config = [
    'algorithm' => 'sha256',
    'num-first-iterations' => 10,
    'num-second-iterations' => 10,
    'num-final-iterations' => 100,
];

//the private key used in both applications to ensure the hash is the same
$key = 'wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb';
$ttl = 2;

try {
    $manager->config($config);
} catch (HmacInvalidAlgorithmException $e) {
    //an HmacInvalidAlgorithmException can be caught here
    //"The algorithm ({$algorithm}) selected is not available"
}

//time to live, when checking if the hmac isValid this will ensure
//that the time with have to be with this number of seconds
$manager->ttl($ttl);

//the secure private key that will be stored locally and not sent in the http headers
$manager->key($key);

//get the HMAC values from the $_SERVER/request headers (and make sure you sanitise the values)
$hmac['data'] = filter_var($_SERVER['data'], FILTER_SANITIZE_STRING);
$hmac['time'] = filter_var($_SERVER['time'], FILTER_SANITIZE_STRING);
$hmac['hmac'] = filter_var($_SERVER['hmac'], FILTER_SANITIZE_STRING);

//the data to be encoded with the hmac, you could use the URI for this
$manager->data($hmac['data']);

//the current timestamp, this will be compared in the other API to ensure
$manager->time($hmac['time']);

//to check if the hmac is valid you need to run the isValid() method
//this needs to be executed after the encode method has been ran
if (! $manager->isValid($hmac['hmac'])) {
    http_response_code(401);
    echo 'Invalid credentials';
}
```

Using with Guzzle
-----------------

Guzzle is a PHP HTTP client that makes it easy to send HTTP requests and trivial to integrate with web services.
https://github.com/guzzle/guzzle

There is now a plugin that will allow integration with guzzle 4+

```php
use GuzzleHttp\Client;
use GuzzleHttp\Event\BeforeEvent;
use Mardy\Hmac\Plugin\HmacHeadersGuzzleEvent;
use Mardy\Hmac\Adapters\Hash;

//Using the HmacHeadersGuzzleEvent class you can automatically inject some headers 
//directly into the guzzle request. This is far more convenient for those of us 
//using dependency injection containers and means we don't have to do it manually 
//each time \o/

$client = new Client;

$client->getEmitter()->on('before', function (BeforeEvent $event) {
    (new HmacHeadersGuzzleEvent(
        new Hash, 
        'wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb', 
        'test-data', 
        microtime(true)
    ))->onBefore($event);
});

$request = $client->createRequest('GET', 'http://www.google.com');
$client->send($request);
```
