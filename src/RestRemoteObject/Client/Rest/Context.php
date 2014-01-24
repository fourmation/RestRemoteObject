<?php

namespace RestRemoteObject\Client\Rest;

use RestRemoteObject\Client\Rest\Resource\Descriptor;
use RestRemoteObject\Client\Rest\Resource\Binder;
use RestRemoteObject\Client\Rest\Format\Format;

use Zend\Http\Request;

class Context
{
    /**
     * REST request
     * @var Request $request
     */
    protected $request;

    /**
     * @var Descriptor $resourceDescriptor
     */
    protected $resourceDescriptor;

    /**
     * @var Binder $resourceBinder
     */
    protected $resourceBinder;

    /**
     * @var Format $formatStrategy
     */
    protected $format;

    /**
     * Get the REST request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set the REST request
     *
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get the resource descriptor
     *
     * @return Descriptor
     */
    public function getResourceDescriptor()
    {
        return $this->resourceDescriptor;
    }

    /**
     * Set the resource descriptor
     *
     * @param Descriptor $resourceDescriptor
     * @return $this
     */
    public function setResourceDescriptor(Descriptor $resourceDescriptor)
    {
        $this->resourceDescriptor = $resourceDescriptor;

        return $this;
    }

    /**
     * Get the resource binder
     *
     * @return Binder
     */
    public function getResourceBinder()
    {
        return $this->resourceBinder;
    }

    /**
     * Set the resource binder
     *
     * @param Binder $resourceBinder
     * @return $this
     */
    public function setResourceBinder(Binder $resourceBinder)
    {
        $this->resourceBinder = $resourceBinder;

        return $this;
    }

    /**
     * Get the format
     *
     * @return Format
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the format
     *
     * @param Format $format
     * @return $this
     */
    public function setFormat(Format $format)
    {
        $this->format = $format;

        return $this;
    }
}
