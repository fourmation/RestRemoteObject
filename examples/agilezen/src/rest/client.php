<?php

use RestRemoteObject\Client\Rest;
use RestRemoteObject\Client\Rest\Versioning\UriVersioningStrategy;
use RestRemoteObject\Client\Rest\Authentication\HeaderAuthenticationStrategy;

require 'http.php';

$client = new Rest('https://agilezen.com/api');

$client->setVersioningStrategy(
    new UriVersioningStrategy($config['version'], '/api')
);
$client->setAuthenticationStrategy(
    new HeaderAuthenticationStrategy('X-Zen-ApiKey', $config['token'])
);

$client->setHttpClient(new HttpClient());

/** @var \RestRemoteObject\Client\Rest\ResponseHandler\DefaultResponseHandler $responseHandler */
$responseHandler = $client->getResponseHandler();
$parser = new Rest\ResponseHandler\Parser\JsonParser();
$parser->setKey('items');
$responseHandler->setResponseParser($parser);

return $client;