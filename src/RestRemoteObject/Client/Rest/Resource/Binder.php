<?php

namespace RestRemoteObject\Client\Rest\Resource;

use Zend\Code\Reflection\MethodReflection;

class Binder
{
    /**
     * @var array
     */
    protected $params;

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
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}