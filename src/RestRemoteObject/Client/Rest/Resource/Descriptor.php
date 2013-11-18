<?php

namespace RestRemoteObject\Client\Rest\Resource;

use RestRemoteObject\Client\Rest\RestParametersAware;

use Zend\Code\Reflection\MethodReflection;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class Descriptor
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var MethodReflection
     */
    protected $reflection;

    /**
     * @var string
     */
    protected $httpMethod;

    /**
     * @var string
     */
    protected $apiResource;

    /**
     * @var string
     */
    protected $returnType;

    /**
     * @var bool
     */
    protected $returnAsArray = false;

    /**
     * @param string $method
     */
    public function __construct($method)
    {
        $this->method = $method;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->getHttpMethod() && $this->getApiResource();
    }

    /**
     * Get the method reflection
     * @return MethodReflection
     */
    protected function getReflection()
    {
        if (null === $this->reflection) {
            list($serviceName, $methodName) = explode('.', $this->method);
            $this->reflection = new MethodReflection($serviceName, $methodName);
        }

        return $this->reflection;
    }

    /**
     * Get the HTTP method from the REST webservice
     * @return string
     */
    public function getHttpMethod()
    {
        if (null === $this->httpMethod) {
            $docBlock   = $this->getReflection()->getDocBlock();
            if (!$docBlock) {
                return null;
            }
            $http = $docBlock->getTag('rest\http');
            if (!$http) {
                return null;
            }
            $this->httpMethod  = $docBlock->getTag('rest\http')->getContent();
        }

        return $this->httpMethod;
    }

    /**
     * Get the URI resource from the REST webservice
     * @return string
     */
    public function getApiResource()
    {
        if (null === $this->apiResource) {
            $reflection = $this->getReflection();
            $docBlock   = $reflection->getDocBlock();
            if (!$docBlock) {
                return null;
            }
            $uri = $docBlock->getTag('rest\uri');
            if (!$uri) {
                return null;
            }
            $this->apiResource  = $uri->getContent();
        }

        return $this->apiResource;
    }

    /**
     * Get the return type from the REST webservice
     * @return string
     */
    public function getReturnType()
    {
        if (null === $this->returnType) {
            $reflection = $this->getReflection();
            $docBlock   = $reflection->getDocBlock();

            /** @var \Zend\Code\Reflection\DocBlock\Tag\ReturnTag $return */
            $return = $docBlock->getTag('return');

            if (!$return) {
                return null;
            }

            // add tests with basic type, class with namespace, without namespace
            // for basic type, add cast
            $types = $return->getTypes();
            $type = $types[0];
            $this->returnType = $reflection->getNamespaceName() . $type;
        }

        if (preg_match('#\[\]$#', $this->returnType)) {
            return substr($this->returnType, 0, -2);
        }

        return $this->returnType;
    }

    /**
     * @return bool
     */
    public function isReturnAsArray()
    {
        $this->getReturnType();
        return preg_match('#\[\]$#', $this->returnType);
    }

    /**
     * Get the service method name, eg MyInterface.myMethod
     *
     * @return string
     */
    public function getMethodName()
    {
        return $this->method;
    }

    /**
     * @param Binder $binder
     */
    public function bind(Binder $binder)
    {
        $apiResource = $this->getApiResource();
        $reflection = $this->getReflection();
        $httpMethod = $this->getHttpMethod();

        $object = $binder->getObject();
        $params = $binder->getParams();

        if ($params) {
            $parametersList = array();

            if ($httpMethod === 'GET') {
                $parameters = $reflection->getParameters();
                foreach($parameters as $parameter) {
                    $value = $params[$parameter->getPosition()];
                    if (is_object($value)) {
                        if ($value instanceof RestParametersAware) {
                            $newParametersList = $value->getRestParameters();
                        } else {
                            $hydrator   = new ClassMethodsHydrator();
                            $newParametersList = $hydrator->extract($value);
                        }
                        $parametersList = array_merge($parametersList, $newParametersList);
                    } else {
                        $parametersList[$parameter->getName()] = $params[$parameter->getPosition()];
                    }
                }
            }
            $apiResource = @preg_replace('/%(\w+)/e', '$parametersList["$1"]', $apiResource);
        }

        if ($object) {
            $apiResource = @preg_replace('/:(\w+)/e', '$bject->$1()', $apiResource);
        }

        $this->apiResource = $apiResource;
    }
}