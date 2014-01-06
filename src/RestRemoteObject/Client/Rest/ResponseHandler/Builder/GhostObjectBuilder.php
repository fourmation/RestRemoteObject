<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Builder;

use RestRemoteObject\Client\Rest;
use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Resource\Descriptor;
use RestRemoteObject\Client\Rest\Resource\Binder;

use Zend\Code\Reflection\ClassReflection;

use ProxyManager\Factory\LazyLoadingGhostFactory;

class GhostObjectBuilder extends DefaultBuilder
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
     * Create instance
     * @param  Context $context
     * @return object
     */
    protected function createInstance(Context $context)
    {
        $returnType = $context->getResourceDescriptor()->getReturnType();

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
            function ($proxy, $method, $parameters, & $initializer) use ($returnType, $client, &$remoteMethods) {
                $fullName = $returnType . '.' . $method;
                if (isset($remoteMethods[$fullName])) {
                    /** @var Descriptor $resource */
                    $resource = $remoteMethods[$fullName];

                    $binder = new Binder();
                    $binder->setObject($proxy);
                    $binder->setParams($parameters);

                    $result = $client->doResourceRequest($resource, $binder);

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
