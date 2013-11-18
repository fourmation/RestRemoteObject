<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Builder;

use RestRemoteObject\Client\Rest;
use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Resource\Descriptor;
use RestRemoteObject\Client\Rest\Resource\Binder;

use Zend\Code\Reflection\ClassReflection;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

use ProxyManager\Factory\LazyLoadingGhostFactory;

class GhostObjectBuilder implements BuilderInterface
{
    /**
     * @var Rest
     */
    protected $client;

    /**
     * @param Rest $client
     */
    public function __construct(Rest $client)
    {
        $this->client = $client;
    }

    /**
     * Build response
     *
     * @param array $data
     * @param Context $context
     * @return mixed|array
     */
    public function build(array $data, Context $context)
    {
        $descriptor = $context->getResourceDescriptor();
        $returnType = $descriptor->getReturnType();
        if (!$returnType) {
            return;
        }
        $hydrator   = new ClassMethodsHydrator();

        if ($descriptor->isReturnAsArray()) {
            $list       = array();
            foreach ($data as $row) {
                $object = $this->createInstance($returnType);
                $hydrator->hydrate((array)$row, $object);
                $list[] = $object;
            }

            return $list;
        }

        $object = $this->createInstance($returnType);
        $hydrator->hydrate((array)$data, $object);
        return $object;
    }

    /**
     * Create instance
     * @param $returnType
     * @return object
     */
    protected function createInstance($returnType)
    {
        $reflection = new ClassReflection($returnType);
        $methods = $reflection->getMethods();

        $remoteMethods = array();
        foreach ($methods as $method) {
            $descriptor = new Descriptor($returnType . '.' . $method->getName());
            if ($descriptor->isValid()) {
                $remoteMethods[$returnType . '.' . $method->getName()] = $descriptor;
            }
        }

        $client = $this->client;

        $factory = new LazyLoadingGhostFactory();
        $proxy = $factory->createProxy(
            $returnType,
            function($proxy, $method, $parameters, & $initializer) use ($returnType, $client, $remoteMethods) {
                $fullName = $returnType . '.' . $method;
                if (isset($remoteMethods[$fullName])) {
                    /** @var Descriptor $resource */
                    $resource = $remoteMethods[$fullName];
                    $binder = new Binder($proxy);
                    $resource->bind($binder);
                    $result = $client->doResourceRequest($resource);
                    $mapping = $resource->getMappingResult();
                    if ($mapping) {
                        $proxy->$mapping($result);
                    }
                }

                return true;
            }
        );

        return $proxy;
    }
}