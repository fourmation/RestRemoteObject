<?php

namespace RestRemoteObject\Client;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Resource\Binder;
use RestRemoteObject\Client\Rest\Resource\Descriptor;
use RestRemoteObject\Client\Rest\ResponseHandler;
use RestRemoteObject\Client\Rest\Builder\BuilderInterface;
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
     * @var BuilderInterface[] $builders
     */
    protected $builders = array();

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
        $request = $client->getRequest();

        // create context
        $context = new Context();
        $context->setRequest($request);
        $context->setResourceDescriptor($descriptor);
        $context->setResourceBinder($binder);

        // add format to the context
        $formatStrategy = $this->getFormatStrategy();
        if ($formatStrategy) {
            $context->setFormat($formatStrategy->getFormat());
        }

        // build arguments
        $className = $descriptor->getClassName();
        $builder = $this->getBuilder($className);
        $methodName = $descriptor->getMethodName();
        if ($builder && method_exists($builder, $methodName)) {
            $builder->$methodName($context);
        }

        // bind and get the uri resource
        $descriptor->bind($binder);
        $client->setUri($this->uri . $descriptor->getApiResource());

        // set the http method
        $httpMethod = $descriptor->getHttpMethod();
        $client->setMethod($httpMethod);

        // set the request params
        switch($httpMethod) {
            case 'DELETE':
            case 'GET' :
                break; // params already in the URI
            case 'PUT' :
                $params = $binder->getParams();
                $request->setContent(implode('&', $params));
                break;
            case 'POST' :
                $params = $binder->getParams();
                $client->setParameterPost($params);
                break;
        }

        // apply versioning strategy
        $versioningStrategy = $this->getVersioningStrategy();
        if ($versioningStrategy) {
            $versioningStrategy->version($context);
        }

        // apply format strategy
        $formatStrategy = $this->getFormatStrategy();
        if ($formatStrategy) {
            $formatStrategy->format($context);
        }

        // apply features
        foreach ($this->features as $feature) {
            $feature->apply($context);
        }

        // apply auth strategy
        $authenticationStrategy = $this->getAuthenticationStrategy();
        if ($authenticationStrategy) {
            $authenticationStrategy->authenticate($context);
            $client->setUri($request->getUri()); // bugfix -- refresh auth params
        }

        $response = $client->send();

        $responseHandler = $this->getResponseHandler();
        $response = $responseHandler->buildResponse($context, $response);

        // don't forget to reset client
        $client->reset();

        return $response;
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
     * Get a builder
     *
     * @param string $className
     * @return BuilderInterface
     */
    public function getBuilder($className)
    {
        if (!isset($this->builders[$className])) {
            return null;
        }
        return $this->builders[$className];
    }

    /**
     * Get the builders
     *
     * @return BuilderInterface[]
     */
    public function getBuilders()
    {
        return $this->builders;
    }

    /**
     * Add a builder
     *
     * @param BuilderInterface $builder
     * @return $this
     */
    public function addBuilder(BuilderInterface $builder)
    {
        $this->builders[$builder->getRelatedClass()] = $builder;

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
