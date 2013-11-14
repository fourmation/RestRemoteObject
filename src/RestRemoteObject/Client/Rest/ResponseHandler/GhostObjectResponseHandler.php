<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler;

use RestRemoteObject\Client\Rest;
use RestRemoteObject\Client\Rest\MethodDescriptor;

use Zend\Code\Reflection\ClassReflection;
use Zend\Http\Response;

use ProxyManager\Factory\LazyLoadingGhostFactory;

class GhostObjectResponseHandler extends DefaultResponseHandler
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
     * @param $returnType
     * @return object
     */
    protected function createInstance($returnType)
    {
        $reflection = new ClassReflection($returnType);
        $methods = $reflection->getMethods();

        $remoteMethods = array();
        foreach ($methods as $method) {
            $descriptor = new MethodDescriptor($returnType . '.' . $method->getName());
            if ($descriptor->isValid()) {
                $remoteMethods[$returnType . '.' . $method->getName()] = array(
                    'http'   => $descriptor->getHttpMethod(),
                    'uri'    => $descriptor->getApiResource(),
                    'return' => $descriptor->getReturnType(),
                );
            }
        }

        $client = $this->client ;

        $factory = new LazyLoadingGhostFactory();
        $proxy = $factory->createProxy(
            substr($returnType, 1),
            function($proxy, $method, $parameters, & $initializer) use ($returnType, $client, $remoteMethods) {
                if (isset($remoteMethods[$returnType . '.' . $method])) {
                    //$parameters = array_merge($parameters, array('getId' => $proxy->getId()));
                    $result = $client->call($returnType . '.' . $method, $parameters);
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
