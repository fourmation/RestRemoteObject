<?php

use ProxyManager\Factory\RemoteObjectFactory;
use RestRemoteObject\Adapter\Rest as RestAdapter;

/** @var \RestRemoteObject\Client\Rest $client */
$client = include __DIR__ . '/../rest/client.php';

$factory = new RemoteObjectFactory(
    new RestAdapter($client)
);

require __DIR__ . '/interfaces/search.php';
require __DIR__ . '/../entities/issue.php';

return $factory->createProxy('SearchInterface');