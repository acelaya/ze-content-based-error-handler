<?php
namespace AcelayaTest\ExpressiveErrorHandler\ErrorHandler;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ContentBasedErrorHandler;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorHandlerManager;
use Acelaya\ExpressiveErrorHandler\Log\BasicLogMessageBuilder;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Log\NullLogger;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\ServiceManager\ServiceManager;

class ContentBasedErrorHandlerTest extends TestCase
{
    /**
     * @var ContentBasedErrorHandler
     */
    protected $errorHandler;

    public function setUp()
    {
        $this->errorHandler = new ContentBasedErrorHandler(new ErrorHandlerManager(new ServiceManager(), [
            'factories' => [
                'text/html' => [$this, 'factory'],
                'application/json' => [$this, 'factory'],
            ],
        ]), new NullLogger(), new BasicLogMessageBuilder());
    }

    public function factory($container, $name)
    {
        return function () use ($name) {
            return $name;
        };
    }

    /**
     * @test
     */
    public function correctAcceptHeaderValueInvokesErrorHandler()
    {
        $request = ServerRequestFactory::fromGlobals()->withHeader('Accept', 'foo/bar,application/json');
        $result = $this->errorHandler->__invoke($request, new Response());
        $this->assertEquals('application/json', $result);
    }

    /**
     * @test
     */
    public function defaultContentTypeIsUsedWhenNoAcceptHeaderisPresent()
    {
        $request = ServerRequestFactory::fromGlobals();
        $result = $this->errorHandler->__invoke($request, new Response());
        $this->assertEquals('text/html', $result);
    }

    /**
     * @test
     */
    public function defaultContentTypeIsUsedWhenAcceptedContentIsNotSupported()
    {
        $request = ServerRequestFactory::fromGlobals()->withHeader('Accept', 'foo/bar,text/xml');
        $result = $this->errorHandler->__invoke($request, new Response());
        $this->assertEquals('text/html', $result);
    }

    /**
     * @test
     * @expectedException \Acelaya\ExpressiveErrorHandler\Exception\InvalidArgumentException
     */
    public function ifNoErrorHandlerIsFoundAnExceptionIsThrown()
    {
        $this->errorHandler = new ContentBasedErrorHandler(
            new ErrorHandlerManager(new ServiceManager(), []),
            new NullLogger(),
            new BasicLogMessageBuilder()
        );
        $request = ServerRequestFactory::fromGlobals()->withHeader('Accept', 'foo/bar,text/xml');
        $this->errorHandler->__invoke($request, new Response());
    }
}
