<?php

namespace RestRemoteObject\Client\Rest\Feature;

use RestRemoteObject\Client\Rest\Context;

interface FeatureInterface
{
    /**
     * @param Context $context
     */
    public function apply(Context $context);
}
