<?php

namespace Fourmation\ProxyManager\Factory\RemoteObject\Adapter;

use ProxyManager\Factory\RemoteObject\Adapter\BaseAdapter;

class Rest extends BaseAdapter
{
    /**
     * {@inheritDoc}
     */
    protected function getServiceName($wrappedClass, $method)
    {
        return $wrappedClass . '.' . $method;
    }
}