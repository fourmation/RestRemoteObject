<?php

namespace RestRemoteObject\Client;

use RestRemoteObject\Client\Rest\MethodDescriptor;
use RestRemoteObject\Client\Rest\ResponseHandler;
use RestRemoteObject\Client\Rest\Authentication\AuthenticationStrategyInterface;

use Zend\Http\Client as HttpClient;
use Zend\Server\Client as ClientInterface;

class Rest implements ClientInterface
{
    /**
     * @var string $format
     */
    protected $format;

    /**
     * @var HttpClient $client
     */
    protected $client;

    /**
     * @var AuthenticationStrategyInterface $authenticationStrategy
     */
    protected $authenticationStrategy;

    /**
     * @param string $uri
     * @param string $format
     */
    public function __construct($uri, $format = ResponseHandler::JSON_RESPONSE)
    {
        $this->uri = $uri;
        $this->format = $format;
    }

    /**
     * {@inheritDoc}
     */
    public function call($method, $params = array())
    {
        $descriptor = new MethodDescriptor($method, $params);

        $client = $this->getHttpClient();
        $client->setUri($this->uri . $descriptor->getApiResource());

        $httpMethod = $descriptor->getHttpMethod();
        $client->setMethod($httpMethod);

        switch($httpMethod) {
            case 'GET' : break; // params already in the URI
            case 'POST';
                $client->setParameterPost($params);
                break;
            case 'DELETE'; break;
            case 'PUT';
                $client->setParameterPost($params);
                break;
        }

        $response = $client->send();

        $response = new ResponseHandler($this->format, $descriptor, $response);
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

    /**
     * Get the authentication strategy
     * @return AuthenticationStrategyInterface
     */
    public function getAuthenticationStrategy()
    {
        return $this->authenticationStrategy;
    }

    /**
     * Set the authentication strategy
     * @param AuthenticationStrategyInterface $authenticationStrategy
     * @return $this
     */
    public function setAuthenticationStrategy(AuthenticationStrategyInterface $authenticationStrategy)
    {
        $this->authenticationStrategy = $authenticationStrategy;

        return $this;
    }
}
