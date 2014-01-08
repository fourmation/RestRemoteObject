<?php

use RestRemoteObject\Client\Rest;
use RestRemoteObject\Client\Rest\Format\Format;
use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;
use RestRemoteObject\Client\Rest\Versioning\UriVersioningStrategy;
use RestRemoteObject\Client\Rest\Authentication\HttpAuthenticationStrategy;

$client = new Rest($config['host'] . '/rest/api');
$client->setFormatStrategy(
    new HeaderFormatStrategy(new Format(Format::JSON))
);

$client->setVersioningStrategy(
    new UriVersioningStrategy($config['version'], '/api')
);
$client->setAuthenticationStrategy(
    new HttpAuthenticationStrategy($config['user'], $config['password'])
);

use RestRemoteObject\Client\Rest\Debug\Debug;
use RestRemoteObject\Client\Rest\Debug\Verbosity\Verbosity;
use RestRemoteObject\Client\Rest\Debug\Writer\Stdout;

/** @var \RestRemoteObject\Client\Rest\ResponseHandler\DefaultResponseHandler $responseHandler */
$responseHandler = $client->getResponseHandler();
$parser = new Rest\ResponseHandler\Parser\JsonParser();
$parser->setKey('issues');
$responseHandler->setResponseParser($parser);

return $client;