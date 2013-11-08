<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler;

use RestRemoteObject\Client\Rest\MethodDescriptor;

use Zend\Http\Response;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class DefaultResponseHandler implements ResponseHandlerInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @param $format
     * @param MethodDescriptor $descriptor
     * @param Response $response
     * @return array
     */
    public function buildResponse($format, MethodDescriptor $descriptor, Response $response)
    {
        $content = $response->getBody();
        if ($format == ResponseHandlerInterface::JSON_RESPONSE) {
            $content = json_decode($content);
        } else {
            // TODO
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
                $object = new $returnType();
                $hydrator->hydrate((array)$data, $object);
                $list[] = $object;
            }

            return $list;
        }

        $object = new $returnType();
        $hydrator->hydrate((array)$content[0], $object);
        return $object;
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
