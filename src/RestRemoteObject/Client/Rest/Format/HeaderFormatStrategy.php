<?php

namespace RestRemoteObject\Client\Rest\Format;

use RestRemoteObject\Client\Rest\Context;

class HeaderFormatStrategy extends AbstractFormatStrategy
{
    /**
     * Format apply
     * @param Context $context
     */
    public function format(Context $context)
    {
        $context->getRequest()->getHeaders()->addHeaderLine('Content-type: application/' . $this->getFormat()->toString());
    }
}
