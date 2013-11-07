<?php

namespace RestRemoteObject\Adapter;

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