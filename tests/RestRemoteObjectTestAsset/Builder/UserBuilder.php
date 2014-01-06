<?php

namespace RestRemoteObjectTestAsset\Builder;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Builder\AbstractBuilder;

class UserBuilder extends AbstractBuilder
{
    protected $relatedClass = 'RestRemoteObjectTestAsset\Services\UserServiceInterface';

    public function get(Context $context)
    {
        $binder = $context->getResourceBinder();
        $params = $binder->getParams();
        $params[0] = 2; // do what you want with params
        $binder->setParams($params);
    }
}
