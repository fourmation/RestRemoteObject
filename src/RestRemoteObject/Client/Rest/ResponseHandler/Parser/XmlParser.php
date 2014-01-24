<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Parser;

use RestRemoteObject\Client\Rest\Context;

class XmlParser implements ParserInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * Parse response content
     *
     * @param $content
     * @param  Context $context
     * @return array
     */
    public function parse($content, Context $context)
    {
        $content = json_decode(json_encode(simplexml_load_string($content)), true);
        $key = $this->getKey();
        if ($key) {
            $content = $content[$key];
        }

        return $content;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
    }
}
