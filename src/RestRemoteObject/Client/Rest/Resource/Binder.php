<?php

namespace RestRemoteObject\Client\Rest\Resource;

use Zend\Code\Reflection\MethodReflection;

class Binder
{
    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var object
     */
    protected $object;

    /**
     * @param object|array $data
     */
    public function __construct($data = null)
    {
        if (is_object($data)) {
            $this->object = $data;
        } else if(is_array($data)) {
            $this->params = $data;
        }
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }
}