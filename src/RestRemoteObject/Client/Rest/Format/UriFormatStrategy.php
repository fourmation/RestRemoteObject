<?php

namespace RestRemoteObject\Client\Rest\Format;

use RestRemoteObject\Client\Rest\Context;

class UriFormatStrategy extends AbstractFormatStrategy
{
    /**
     * @var string $baseUrl
     */
    protected $baseUrl;

    /**
     * @param Format $format
     * @param null|string $baseUrl
     */
    public function __construct(Format $format = null, $baseUrl = null)
    {
        parent::__construct($format);
        if ($baseUrl) {
            $this->baseUrl = '/' . trim($baseUrl, '\/');
        }
    }

    /**
     * Format apply
     * @param Context $context
     */
    public function format(Context $context)
    {
        $uri = $context->getRequest()->getUri();

        if ($this->baseUrl) {
            $path = $uri->getPath();
            $path = str_replace($this->baseUrl, $this->baseUrl . '/' . $this->getFormat()->toString(), $path);
            $uri->setPath($path);
        } else {
            $uri->setPath('/' . $this->getFormat()->toString() . $uri->getPath());
        }
    }
}
