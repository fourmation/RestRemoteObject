<?php

namespace RestRemoteObject\Client\Rest\Format;

use Zend\Http\Request;

class ParameterFormatStrategy extends  AbstractFormatStrategy
{
    /**
     * Format apply
     * @param Request $request
     */
    public function format(Request $request)
    {
        $uri = $request->getUri();
        $query = $uri->getQueryAsArray();

        $query['f'] = $this->format;
        $uri->setQuery($query);
    }
}
