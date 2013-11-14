<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler;

use RestRemoteObject\Client\Rest\MethodDescriptor;
use RestRemoteObject\Client\Rest\Format\FormatStrategyInterface;

use Zend\Http\Response;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class DefaultResponseHandler implements ResponseHandlerInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @param FormatStrategyInterface $format
     * @param MethodDescriptor $descriptor
     * @param Response $response
     * @return array
     */
    public function buildResponse(FormatStrategyInterface $format, MethodDescriptor $descriptor, Response $response)
    {
        $content = $response->getBody();
        if ($format->isJson()) {
            $content = json_decode($content);
        } else if ($format->isXml()) {
            // TODO
        } else {
            // error
        }

        $key = $this->getKey();
        if ($key) {
            $content = $content[$key];
        }

        $returnType = $descriptor->getReturnType();
        if (!$returnType) {
            return;
        }
        $hydrator   = new ClassMethodsHydrator();

        if (count($content) > 1) {
            $list       = array();
            foreach ($content as $data) {
                $object = $this->createInstance($returnType);
                $hydrator->hydrate((array)$data, $object);
                $list[] = $object;
            }

            return $list;
        }

        $object = $this->createInstance($returnType);
        $hydrator->hydrate((array)$content[0], $object);
        return $object;
    }

    /**
     * Create instance
     * @param $returnType
     * @return object
     */
    protected function createInstance($returnType)
    {
        return new $returnType();
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }
}
