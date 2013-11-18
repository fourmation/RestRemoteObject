<?php

namespace RestRemoteObject\Client\Rest\Builder;

abstract class AbstractBuilder implements  BuilderInterface
{
    /**
     * @var string
     */
    protected $relatedClass;

    /**
     * @return string
     * @throws \Exception
     */
    public function getRelatedClass()
    {
        if (null === $this->relatedClass) {
            // error to custom
            throw new \Exception('You have to specify a related class');
        }

        return $this->relatedClass;
    }
}
