<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Builder;

use RestRemoteObject\Client\Rest\Context;

use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class DefaultBuilder implements BuilderInterface
{
    /**
     * Build response
     *
     * @param  array       $data
     * @param  Context     $context
     * @return mixed|array
     */
    public function build(array $data, Context $context)
    {
        $descriptor = $context->getResourceDescriptor();
        $returnType = $descriptor->getReturnType();
        if (!$returnType) {
            return;
        }
        $hydrator = new ClassMethodsHydrator();

        if ($descriptor->isReturnAsArray()) {
            $list = array();
            foreach ($data as $row) {
                $object = $this->createInstance($context);
                $hydrator->hydrate((array) $row, $object);
                $list[] = $object;
            }

            return $list;
        }

        $object = $this->createInstance($context);
        $hydrator->hydrate((array) $data, $object);

        return $object;
    }

    /**
     * Create instance
     * @param  Context $context
     * @return object
     */
    protected function createInstance(Context $context)
    {
        $returnType = $context->getResourceDescriptor()->getReturnType();

        return new $returnType();
    }
}
