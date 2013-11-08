<?php

namespace RestRemoteObject\Client\Rest\Feature;

use Zend\Http\Request;

class TimestampFeature implements FeatureInterface
{
    /**
     * @param Request $request
     */
    public function apply(Request $request)
    {
        $uri = $request->getUri();
        $query = $uri->getQueryAsArray();

        $query['t'] = time();
        $uri->setQuery($query);
    }
}
