<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Builder;
use RestRemoteObject\Client\Rest\ResourceDescriptor;

use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class DefaultBuilder implements BuilderInterface
{
    /**
     * Build response
     *
     * @param array $data
     * @param ResourceDescriptor $descriptor
     * @return mixed|array
     */
    public function build(array $data, ResourceDescriptor $descriptor)
    {
        $returnType = $descriptor->getReturnType();
        if (!$returnType) {
            return;
        }
        $hydrator   = new ClassMethodsHydrator();

        if ($descriptor->isReturnAsArray()) {
            $list       = array();
            foreach ($data as $row) {
                $object = new $returnType;
                $hydrator->hydrate((array)$row, $object);
                $list[] = $object;
            }

            return $list;
        }

        $object = new $returnType;
        $hydrator->hydrate((array)$data, $object);
        return $object;
    }
}