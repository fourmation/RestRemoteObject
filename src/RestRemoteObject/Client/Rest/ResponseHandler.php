<?php

namespace RestRemoteObject\Client\Rest;

use Zend\Http\Response;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ResponseHandler
{
    CONST JSON_RESPONSE = 'json_response';
    CONST XML_RESPONSE = 'xml_response';

    /**
     * @var string $format
     */
    protected $format;

    /**
     * @var MethodDescriptor
     */
    protected $descriptor;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param string $format
     * @param MethodDescriptor $descriptor
     * @param Response $response
     */
    public function __construct($format, MethodDescriptor $descriptor, Response $response)
    {
        $this->format = $format;
        $this->descriptor = $descriptor;
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        $content = $this->response->getBody();
        if ($this->format == self::JSON_RESPONSE) {
            $content = json_decode($content);
        } else {
            // TODO
        }

        $returnType = $this->descriptor->getReturnType();
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
}
