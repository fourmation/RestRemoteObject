<?php

namespace RestRemoteObject\Client\Rest\Feature;

use Zend\Http\Request;

interface FeatureInterface
{
    /**
     * @param Request $request
     */
    public function apply(Request $request);
}
