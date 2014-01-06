<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Builder;

use RestRemoteObject\Client\Rest\Context;

interface BuilderInterface
{
    /**
     * Build API response
     *
     * @param  array   $data
     * @param  Context $context
     * @return array
     */
    public function build(array $data, Context $context);
}
