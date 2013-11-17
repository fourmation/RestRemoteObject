<?php

namespace RestRemoteObject\Client\Rest\ArgumentBuilder;

use RestRemoteObject\Client\Rest\Context;

class JsonArgumentBuilder implements ArgumentBuilderInterface
{
    /**
     * @param Context $context
     * @return string
     */
    public function build(Context $context)
    {
        $params = $context->getResourceDescriptor()->getParams();
        return json_encode($params);
    }
}
