<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Builder;

use RestRemoteObject\Client\Rest;
use RestRemoteObject\Client\Rest\ResourceDescriptor;

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
     * @var
     */
    protected $lazyLoadingMap;

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
     * @param ResourceDescriptor $descriptor
     * @return mixed|array
     */
    public function build(array $data, ResourceDescriptor $descriptor)
    {
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
            $descriptor = new ResourceDescriptor($returnType . '.' . $method->getName());
            if ($descriptor->isValid()) {
                $remoteMethods[$returnType . '.' . $method->getName()] = $descriptor;
            }
        }

        $client = $this->client;

        $factory = new LazyLoadingGhostFactory();
        $proxy = $factory->createProxy(
            substr($returnType, 1),
            function($proxy, $method, $parameters, & $initializer) use ($returnType, $client, $remoteMethods) {
                $fullName = $returnType . '.' . $method;
                if (isset($remoteMethods[$fullName])) {
                    /** @var ResourceDescriptor $resource */
                    $resource = $remoteMethods[$fullName];
                    $resource->bind($proxy);
                    $result = $client->callResource($resource);
                    $setter = preg_replace('#^get#', 'set', $method);
                    $proxy->$setter($result);
                }

                return true;
            }
        );

        return $proxy;
    }

    public function setLazyLoadingMap(array $lazyLoadingMap)
    {
        $this->lazyLoadingMap = $lazyLoadingMap;
    }

    public function getLazyLoadingMap()
    {
        return $this->lazyLoadingMap;
    }
}