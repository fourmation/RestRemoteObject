<?php

namespace RestRemoteObject\Client\Rest\Format;

use RestRemoteObject\Client\Rest\Context;

class ParameterFormatStrategy extends AbstractFormatStrategy
{
    /**
     * Format apply
     * @param Context $context
     */
    public function format(Context $context)
    {
        $uri = $context->getRequest()->getUri();
        $query = $uri->getQueryAsArray();

        $query['f'] = $this->getFormat()->toString();
        $uri->setQuery($query);
    }
}
