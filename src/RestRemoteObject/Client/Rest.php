<?php

namespace RestRemoteObject\Client;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Resource\Binder;
use RestRemoteObject\Client\Rest\Resource\Descriptor;
use RestRemoteObject\Client\Rest\ResponseHandler;
use RestRemoteObject\Client\Rest\ArgumentBuilder\ArgumentBuilderInterface;
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
     * @var HttpClient $client
     */
    protected $client;

    /**
     * @var ArgumentBuilderInterface $argumentBuilder
     */
    protected $argumentBuilder;

    /**
     * @var FormatStrategyInterface $formatStrategy
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
     */
    public function __construct($uri)
    {
        $this->uri = trim($uri, '\/');
    }

    /**
     * {@inheritDoc}
     */
    public function call($method, $params = array())
    {
        $descriptor = new Descriptor($method);
        $binder = new Binder($params);
        if (!$descriptor->isValid()) {
            throw new MissingResourceDescriptionException(sprintf('Method %s docblock must defined a @rest\http tag which provide the HTTP method to use ann a @rest\uri tag', $method));
        }

        return $this->doResourceRequest($descriptor, $binder);
    }

    /**
     * @param Descriptor $descriptor
     * @param Binder $binder
     * @return array
     */
    public function doResourceRequest(Descriptor $descriptor, Binder $binder = null)
    {
        if (null === $binder) {
            $binder = new Binder();
        }

        $client = $this->getHttpClient();
        $client->setUri($this->uri . $descriptor->getApiResource());

        $request = $client->getRequest();

        $context = new Context();
        $context->setRequest($request);
        $context->setResourceDescriptor($descriptor);
        $context->setResourceBinder($binder);

        $formatStrategy = $this->getFormatStrategy();
        if ($formatStrategy) {
            $context->setFormat($formatStrategy->getFormat());
        }

        $httpMethod = $descriptor->getHttpMethod();
        $client->setMethod($httpMethod);

        $params = $binder->getParams();

        switch($httpMethod) {
            case 'DELETE':
            case 'GET' :
                break; // params already in the URI
            case 'PUT' :
            case 'POST' :
                $argumentBuilder = $this->getArgumentBuilder();
                if ($argumentBuilder) {
                    $params = $argumentBuilder->build($context);
                }
                $client->setParameterPost($params);
                break;
        }

        $versioningStrategy = $this->getVersioningStrategy();
        if ($versioningStrategy) {
            $versioningStrategy->version($context);
        }

        $formatStrategy = $this->getFormatStrategy();
        if ($formatStrategy) {
            $formatStrategy->format($context);
        }

        foreach ($this->features as $feature) {
            $feature->apply($context);
        }

        $authenticationStrategy = $this->getAuthenticationStrategy();
        if ($authenticationStrategy) {
            $authenticationStrategy->authenticate($context);
            $client->setUri($request->getUri()); // bugfix -- refresh auth params
        }

        $response = $client->send();

        $responseHandler = $this->getResponseHandler();
        return $responseHandler->buildResponse($context, $response);
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
     * Get the format strategy
     *
     * @return FormatStrategyInterface
     */
    public function getFormatStrategy()
    {
        return $this->formatStrategy;
    }

    /**
     * Get the arguments builder
     *
     * @return ArgumentBuilderInterface
     */
    public function getArgumentBuilder()
    {
        return $this->argumentBuilder;
    }

    /**
     * Set the arguments builder
     *
     * @param ArgumentBuilderInterface $argumentBuilder
     * @return $this
     */
    public function setArgumentBuilder(ArgumentBuilderInterface $argumentBuilder)
    {
        $this->argumentBuilder = $argumentBuilder;

        return $this;
    }

    /**
     * Set the format strategy
     *
     * @param FormatStrategyInterface $formatStrategy
     * @return FormatStrategyInterface
     */
    public function setFormatStrategy(FormatStrategyInterface $formatStrategy)
    {
        $this->formatStrategy = $formatStrategy;

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
