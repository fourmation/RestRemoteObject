<?php

namespace RestRemoteObject\Client\Rest\Format;

use Zend\Http\Request;

class ExtensionFormatStrategy extends  AbstractFormatStrategy
{
    /**
     * Format apply
     * @param Request $request
     */
    public function format(Request $request)
    {
        $uri = $request->getUri();
        $uri->setPath($uri->getPath() . '.' . $this->format);
    }
}
