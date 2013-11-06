<?php

namespace Fourmation\ProxyManager\Factory\RemoteObject\Client;

use Fourmation\ProxyManager\Factory\RemoteObject\Client\Rest\MethodDescriptor;
use Fourmation\ProxyManager\Factory\RemoteObject\Client\Rest\ResponseHandler;
use Zend\Http\Client as HttpClient;
use Zend\Server\Client as ClientInterface;

class Rest implements ClientInterface
{
    /**
     * @var HttpClient $client
     */
    protected $client;

    /**
     * {@inheritDoc}
     */
    public function call($method, $params = array())
    {
        $descriptor = new MethodDescriptor($method, $params);

        $client = $this->getHttpClient();
        $client->setUri($descriptor->getApiResource());
        $client->setMethod($descriptor->getHttpMethod());

        $response = $client->send();

        $response = new ResponseHandler($descriptor, $response);
        return $response->getResult();


    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (null === $this->client) {
            $this->setHttpClient(new HttpClient());
        }

        return $this->client;
    }

    /**
     * @param HttpClient $client
     * @return $this
     */
    public function setHttpClient(HttpClient $client)
    {
        $this->client = $client;

        return $this;
    }
}
