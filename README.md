Mardy-Dev HMAC
==============

A simple lightweight HMAC generator and checker.

Currently this is used to authenticate applications to other applications.

Usage Example

use \Mardy\Hmac\Config\Config as HmacConfig;
use \Mardy\Hmac\Storage\NonPersistent as HmacStorage;
use \Mardy\Hmac\Hmac;

$hmac = new Hmac(new HmacConfig, new HmacStorage);

$hmac->getStorage()
     ->setHmac($key)
     ->setTimestamp($ts)
     ->setUri($uri);

$hmac->check(); //returns true or false based on if the HMAC key is valid;

If an error occurs when the check() method runs and error message can be
view by calling the getError() method.

echo $hmac->getError();



