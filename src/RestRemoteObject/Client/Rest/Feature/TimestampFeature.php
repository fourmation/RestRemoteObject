<?php

namespace RestRemoteObject\Client\Rest\Feature;

use RestRemoteObject\Client\Rest\Context;

class TimestampFeature implements FeatureInterface
{
    /**
     * @param Context $context
     */
    public function apply(Context $context)
    {
        $uri = $context->getRequest()->getUri();
        $query = $uri->getQueryAsArray();

        $query['t'] = time();
        $uri->setQuery($query);
    }
}
