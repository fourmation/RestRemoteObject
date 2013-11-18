<?php

namespace RestRemoteObject\Client\Rest\Format;

use RestRemoteObject\Client\Rest\Context;

class ExtensionFormatStrategy extends AbstractFormatStrategy
{
    /**
     * Format apply
     * @param Context $context
     */
    public function format(Context $context)
    {
        $uri = $context->getRequest()->getUri();
        $uri->setPath($uri->getPath() . '.' . $this->getFormat()->toString());
    }
}
