<?php

namespace RestRemoteObject\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException;

use RestRemoteObject\Client\Rest\Context;

class HeaderAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var string
     */
    protected $header;

    /**
     * @var string
     */
    protected $value;

    public function __construct($header = null, $value = null)
    {
        if ($header) {
            $this->setHeader($header);
        }
        if ($value) {
            $this->setValue($value);
        }
    }

    /**
     * Authenticate the request
     * @param  Context $context
     * @return void
     */
    public function authenticate(Context $context)
    {
        $header = $this->getHeader();
        $value = $this->getValue();

        $context->getRequest()->getHeaders()->addHeaderLine($header .':' . $value);
    }

    /**
     * Get the header name
     * @return string
     * @throws \RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException
     */
    public function getHeader()
    {
        if (null === $this->header) {
            throw new MissingAuthenticationParameterException('Header name must be defined');
        }

        return $this->header;
    }

    /**
     * Set the header name
     * @param $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get the header value
     * @return string
     * @throws \RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException
     */
    public function getValue()
    {
        if (null === $this->value) {
            throw new MissingAuthenticationParameterException('Header value must be defined');
        }

        return $this->value;
    }

    /**
     * Set the header value
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
