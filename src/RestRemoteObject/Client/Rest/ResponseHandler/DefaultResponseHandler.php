<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\ResponseHandler\Parser\JsonParser;
use RestRemoteObject\Client\Rest\ResponseHandler\Parser\ParserInterface;
use RestRemoteObject\Client\Rest\ResponseHandler\Builder\BuilderInterface;
use RestRemoteObject\Client\Rest\ResponseHandler\Builder\DefaultBuilder;

use RestRemoteObject\Client\Rest\ResponseHandler\Parser\XmlParser;
use Zend\Http\Response;

class DefaultResponseHandler implements ResponseHandlerInterface
{
    /**
     * @var ParserInterface $parser
     */
    protected $parser;

    /**
     * @var BuilderInterface $builder
     */
    protected $builder;

    /**
     * @param  Context           $context
     * @param  Response          $response
     * @return array
     * @throws \RuntimeException
     */
    public function buildResponse(Context $context, Response $response)
    {
        $content = $response->getBody();
        $responseParser = $this->getResponseParser();
        if (null === $responseParser) {
            $format = $context->getFormat();
            if ($format->isJson()) {
                $responseParser = new JsonParser();
            } else {
                if ($format->isXml()) {
                    $responseParser = new XmlParser();
                } else {
                    throw new \RuntimeException('You have to specify a response parser');
                }
            }
        }

        $content = (array) $responseParser->parse($content, $context);

        $builder = $this->getResponseBuilder();

        return $builder->build($content, $context);
    }

    /**
     * Get response parser
     *
     * @return ParserInterface
     */
    public function getResponseParser()
    {
        return $this->parser;
    }

    /**
     * Set response parser
     *
     * @param ParserInterface $parser
     */
    public function setResponseParser(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Get response builder
     *
     * @return BuilderInterface
     */
    public function getResponseBuilder()
    {
        if (null === $this->builder) {
            $this->setResponseBuilder(new DefaultBuilder());
        }

        return $this->builder;
    }

    /**
     * Set response builder
     *
     * @param BuilderInterface $builder
     */
    public function setResponseBuilder($builder)
    {
        $this->builder = $builder;
    }
}
