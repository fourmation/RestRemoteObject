<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Builder;
use RestRemoteObject\Client\Rest\ResourceDescriptor;

interface BuilderInterface
{
    /**
     * Build response
     *
     * @param $data
     * @return array
     */
    public function build(array $data, ResourceDescriptor $descriptor);
}