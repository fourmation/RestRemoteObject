<?php

namespace RestRemoteObject\Client\Rest;

use RestRemoteObject\Client\Rest\RestParametersAware;
use RestRemoteObject\Client\Rest\Exception\MissingResourceDescriptionException;
use Zend\Code\Reflection\MethodReflection;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ResourceDescriptor
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $params;

    /**
     * @var object
     */
    protected $object;

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
     * @param array $params
     */
    public function __construct($method, $params = array())
    {
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->getHttpMethod() && $this->getApiResource();
    }

    /**
     * @param object $object
     */
    public function bind($object)
    {
        $this->object = $object;
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

            $httpMethod = $this->getHttpMethod();
            if ($httpMethod === 'GET') {
                $parametersList = array();
                $parameters = $reflection->getParameters();
                foreach($parameters as $parameter) {
                    $value = $this->params[$parameter->getPosition()];
                    if (is_object($value)) {
                        if ($value instanceof RestParametersAware) {
                            $newParametersList = $value->getRestParameters();
                        } else {
                            $hydrator   = new ClassMethodsHydrator();
                            $newParametersList = $hydrator->extract($value);
                        }
                        $parametersList = array_merge($parametersList, $newParametersList);
                    } else {
                        $parametersList[$parameter->getName()] = $this->params[$parameter->getPosition()];
                    }
                }
            }
        }

        $this->apiResource = @preg_replace('/%(\w+)/e', '$parametersList["$1"]', $this->apiResource);
        if ($this->object) {
            $this->apiResource = @preg_replace('/:(\w+)/e', '$this->object->$1()', $this->apiResource);
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
            $return = $docBlock->getTag('return');

            if (!$return) {
                return null;
            }

            // add tests with basic type, class with namespace, without namespace
            // for bqsic type, add cast
            $this->returnType = $reflection->getNamespaceName() . $return->getType();
            if (preg_match('#\[\]$#', $this->returnType)) {
                $this->returnAsArray = true;
                $this->returnType = substr($this->returnType, 0, -2);
            }
        }

        return $this->returnType;
    }

    /**
     * @return bool
     */
    public function isReturnAsArray()
    {
        $this->getReturnType();
        return $this->returnAsArray;
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
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}