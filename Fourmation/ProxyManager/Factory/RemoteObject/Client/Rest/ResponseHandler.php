<?php

namespace Fourmation\ProxyManager\Factory\RemoteObject\Client\Rest;

use Zend\Http\Response;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class ResponseHandler
{
    /**
     * @var MethodDescriptor
     */
    protected $descriptor;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param MethodDescriptor $descriptor
     * @param Response $response
     */
    public function __construct(MethodDescriptor $descriptor, Response $response)
    {
        $this->descriptor = $descriptor;
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        $content = $this->response->getBody();
        $content = sample(); // to delete
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
















function sample()
{
    static $i = 0;
    $i++;
    if($i == 2) {
        $location1 = new \Location();
        $location1->setAddress('George Street');

        $location2 = new \Location();
        $location2->setAddress('Pitt Street');

        return json_decode(json_encode(array(
            array(
                'address' => $location1->getAddress(),
            ),
            array(
                'address' => $location2->getAddress(),
            ),
        )));
    } else {
        $location = new \Location();
        $location->setAddress('Pitt Street');

        return json_decode(json_encode(array(
            array(
                'address' => $location->getAddress(),
            ),
        )));
    }
}