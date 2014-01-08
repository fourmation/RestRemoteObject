<?php

namespace RestRemoteObjectTest\Client\Rest\ResponseHandler\Guardian;

use Zend\Http\Headers;
use Zend\Http\Response;

use PHPUnit_Framework_TestCase;

use RestRemoteObject\Client\Rest\ResponseHandler\Guardian\HeadersGuardian;

class HeadersGuardianTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        set_error_handler(function($level, $message, $file, $line) { throw new \RestRemoteObject\Client\Rest\Exception\ResponseErrorException($message); }, E_USER_WARNING);
    }

    public function tearDown()
    {
        restore_error_handler();
    }

    public function testCanGuardCleanResponse()
    {
        $response = new Response();

        $guardian = new HeadersGuardian(array('Api-Error'));

        $headers = Headers::fromString("XApiError: error#1;\r\nWarning: warning#1;");
        $response->setHeaders($headers);

        $guardian->guard($response);

        $this->assertCount(2, $headers);
        $this->assertCount(2, $response->getHeaders());
    }

    public function testCanGuardErrorByHeaders()
    {
        $response = new Response();

        $guardian = new HeadersGuardian(array('XApiError'), array('XApiWarning'));

        $headers = Headers::fromString("XApiError: error#1;\r\nXApiWarning: warning#1;");
        $response->setHeaders($headers);

        $this->setExpectedException('RestRemoteObject\Client\Rest\Exception\ResponseErrorException', 'error#1');
        $guardian->guard($response);

        $this->assertCount(2, $headers);
        $this->assertCount(2, $response->getHeaders());
    }

    public function testCanGuardWarningByHeaders()
    {
        $response = new Response();

        $guardian = new HeadersGuardian(array('XApiError'), array('XApiWarning'));

        $headers = Headers::fromString("XApiWarning: warning#1;");
        $response->setHeaders($headers);

        $this->setExpectedException('RestRemoteObject\Client\Rest\Exception\ResponseErrorException', 'warning#1');
        $guardian->guard($response);

        $this->assertCount(2, $headers);
        $this->assertCount(2, $response->getHeaders());
    }
}
