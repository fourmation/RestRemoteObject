<?php

namespace RestRemoteObject\Client\Rest;

use Zend\Http\Request;
use RestRemoteObject\Client\Rest\ResourceDescriptor;
use RestRemoteObject\Client\Rest\Format\Format;

class Context
{
    /**
     * REST request
     * @var Request $request
     */
    protected $request;

    /**
     * @var ResourceDescriptor $resourceDescriptor
     */
    protected $resourceDescriptor;

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
     * @return ResourceDescriptor
     */
    public function getResourceDescriptor()
    {
        return $this->resourceDescriptor;
    }

    /**
     * Set the resource descriptor
     *
     * @param $resourceDescriptor
     * @return $this
     */
    public function setResourceDescriptor(ResourceDescriptor $resourceDescriptor)
    {
        $this->resourceDescriptor = $resourceDescriptor;

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