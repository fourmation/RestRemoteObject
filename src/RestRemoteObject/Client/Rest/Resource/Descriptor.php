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
    protected $identifier;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var string
     */
    protected $className;

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
     * @var string
     */
    protected $mapping;

    /**
     * @param string $identifier
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
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
            $service = explode('.', $this->identifier);
            $this->className = ltrim($service[0], '\\');
            $this->methodName = $service[1];
            $this->reflection = new MethodReflection($this->className, $this->methodName);
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
            $this->httpMethod  = $http->getContent();
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
            $type = $reflection->getNamespaceName() . $type;
            $this->returnType = ltrim($type, '\\');
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
        return (bool)preg_match('#\[\]$#', $this->returnType);
    }

    /**
     * @return string
     */
    public function getMappingResult()
    {
        if (null !== $this->mapping) {
            return $this->mapping;
        }

        $reflection = $this->getReflection();
        $docBlock   = $reflection->getDocBlock();
        if (!$docBlock) {
            return;
        }

        $mapping    = $docBlock->getTag('rest\mapping');
        if (!$mapping) {
            return;
        }

        $this->mapping = $mapping->getContent();
        return $this->mapping;
    }

    /**
     * Get the service identifier name, eg MyInterface.myMethod
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Get the service method name, eg MyInterface
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Get the service method name, eg myMethod
     *
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
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
            $apiResource = @preg_replace('/%(\w+)/e', '$parametersList["$1"]', $apiResource);
        }

        if ($object) {
            $apiResource = @preg_replace('/:(\w+)/e', '$object->$1()', $apiResource);
        }

        $this->apiResource = $apiResource;
    }
}