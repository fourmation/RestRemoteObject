<?php

namespace RestRemoteObject\Client\Rest\Format;

use Zend\Http\Request;

class HeaderFormatStrategy extends AbstractFormatStrategy
{
    /**
     * Format apply
     * @param Request $request
     */
    public function format(Request $request)
    {
        $request->getHeaders()->addHeaderLine('Content-type: application/' . $this->format);
    }
}
