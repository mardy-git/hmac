Mardy-Git HMAC
==============

A simple lightweight HMAC generator and checker.

Currently this is used to authenticate applications to other applications.

Usage Example
--------------------

    use Mardy\Hmac\Hmac;
    use Mardy\Hmac\Headers;
    use Mardy\Hmac\Config\Config as HmacConfig;
    use Mardy\Hmac\Storage\NonPersistent as HmacStorage;

    $hmac = new Hmac(new HmacConfig, new HmacStorage);

    //used to get the headers
    $headers = new Headers;

    //the private key used in both applications to ensure the hash is the same
    $key = "wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb";

    //sets the private key, this needs to be loaded from your config
    $hmac->getConfig()->setKey($key);

    //optional, to set the hash algorithm
    $hmac->getConfig()->setAlgorithm("sha256");

    //get the information contained in the headers
    $values = $headers->get();

    //Sets the HMAC, timestamp and URI
    $hmac->getStorage()
         ->setHmac($values['key'])
         ->setTimestamp($values['when'])
         ->setUri($values['uri']);

    //returns true or false based on if the HMAC key is valid;
    if(! $hmac->check())
    {
        echo $hmac->getError();
    }

    //else process the script as required

If an error occurs when the check() method runs and error message can be
view by calling the getError() method.

    echo $hmac->getError();
