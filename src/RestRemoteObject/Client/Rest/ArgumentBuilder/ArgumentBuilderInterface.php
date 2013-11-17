<?php

namespace RestRemoteObject\Client\Rest\ArgumentBuilder;

use RestRemoteObject\Client\Rest\Context;

interface ArgumentBuilderInterface
{
    /**
     * @param Context $context
     */
    public function build(Context $context);
}
