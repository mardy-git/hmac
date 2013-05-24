Mardy-Dev HMAC
==============

A simple lightweight HMAC generator and checker.

Currently this is used to authenticate applications to other applications.

Usage Example
--------------------

    use \Mardy\Hmac\Config\Config as HmacConfig;
    use \Mardy\Hmac\Storage\NonPersistent as HmacStorage;
    use \Mardy\Hmac\Hmac;

    $hmac = new Hmac(new HmacConfig, new HmacStorage);

    $key = "wul4RekRPOMw4a2A6frifPqnOxDqMXdtRQMt6v6lsCjxEeF9KgdwDCMpcwROTqyPxvs1ftw5qAHjL4Lb";

    $hmac = "NVvMExOSctuSidto3VB5crz5qsRhnTivFuUSqpgKWkZfruE0D5NEjwlsqWiN495vEzgt9P1IEhIJMmpI";
    $timestamp = 1369084726;
    $uri = "/test/foo/bar";

    //sets the private key, this needs to be loaded from your config
    $hmac->getConfig()->setKey($key);

    //Sets the HMAC, timestamp and URI
    $hmac->getStorage()
               ->setHmac($hmac)
               ->setTimestamp($timestamp)
               ->setUri($uri);

    //returns true or false based on if the HMAC key is valid;
    $hmac->check(); 

If an error occurs when the check() method runs and error message can be
view by calling the getError() method.

    echo $hmac->getError();
