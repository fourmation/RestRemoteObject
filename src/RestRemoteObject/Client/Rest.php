<?php

namespace RestRemoteObject\Client;

use RestRemoteObject\Client\Rest\ResourceDescriptor;
use RestRemoteObject\Client\Rest\ResponseHandler;
use RestRemoteObject\Client\Rest\Authentication\AuthenticationStrategyInterface;
use RestRemoteObject\Client\Rest\Format\FormatStrategyInterface;
use RestRemoteObject\Client\Rest\Versioning\VersioningStrategyInterface;
use RestRemoteObject\Client\Rest\ResponseHandler\ResponseHandlerInterface;
use RestRemoteObject\Client\Rest\ResponseHandler\DefaultResponseHandler;
use RestRemoteObject\Client\Rest\Feature\FeatureInterface;
use RestRemoteObject\Client\Rest\Exception\MissingResourceDescriptionException;

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
     * @var AuthenticationStrategyInterface $formatStrategy
     */
    protected $formatStrategy;

    /**
     * @var AuthenticationStrategyInterface $authenticationStrategy
     */
    protected $authenticationStrategy;

    /**
     * @var VersioningStrategyInterface $versioningStrategy
     */
    protected $versioningStrategy;

    /**
     * @var ResponseHandlerInterface $responseHandler
     */
    protected $responseHandler;

    /**
     * @var FeatureInterface[] $features
     */
    protected $features = array();

    /**
     * @param string $uri
     * @param FormatStrategyInterface $formatStrategy
     */
    public function __construct($uri, FormatStrategyInterface $formatStrategy)
    {
        $this->uri = trim($uri, '\/');
        $this->formatStrategy = $formatStrategy;
    }

    /**
     * {@inheritDoc}
     */
    public function call($method, $params = array())
    {
        $descriptor = new ResourceDescriptor($method, $params);
        if (!$descriptor->isValid()) {
            throw new MissingResourceDescriptionException(sprintf('Method %s docblock must defined a @rest\http tag which provide the HTTP method to use ann a @rest\uri tag', $this->method));
        }

        return $this->callResource($descriptor);
    }

    /**
     * @param ResourceDescriptor $descriptor
     * @return array
     */
    public function callResource(ResourceDescriptor $descriptor)
    {
        $params = $descriptor->getParams();
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

        $request = $client->getRequest();

        $authenticationStrategy = $this->getAuthenticationStrategy();
        if ($authenticationStrategy) {
            $authenticationStrategy->authenticate($request);
        }

        $versioningStrategy = $this->getVersioningStrategy();
        if ($versioningStrategy) {
            $versioningStrategy->version($request);
        }

        $this->formatStrategy->format($request);

        foreach ($this->features as $feature) {
            $feature->apply($request);
        }
        $response = $client->send();

        $responseHandler = $this->getResponseHandler();
        return $responseHandler->buildResponse($this->formatStrategy, $descriptor, $response);
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

    /**
     * Get the versioning strategy
     * @return VersioningStrategyInterface
     */
    public function getVersioningStrategy()
    {
        return $this->versioningStrategy;
    }

    /**
     * Set the versioning strategy
     * @param VersioningStrategyInterface $versioningStrategy
     * @return $this
     */
    public function setVersioningStrategy(VersioningStrategyInterface $versioningStrategy)
    {
        $this->versioningStrategy = $versioningStrategy;

        return $this;
    }

    /**
     * Get the response handler
     * @return ResponseHandlerInterface
     */
    public function getResponseHandler()
    {
        if (null === $this->responseHandler) {
            $this->setResponseHandler(new DefaultResponseHandler());
        }

        return $this->responseHandler;
    }

    /**
     * Set the response handler
     * @param ResponseHandlerInterface $responseHandler
     * @return $this
     */
    public function setResponseHandler(ResponseHandlerInterface $responseHandler)
    {
        $this->responseHandler = $responseHandler;

        return $this;
    }

    /**
     * Add feature
     * @param FeatureInterface $feature
     * @return $this
     */
    public function addFeature(FeatureInterface $feature)
    {
        $this->features[] = $feature;

        return $this;
    }
}
