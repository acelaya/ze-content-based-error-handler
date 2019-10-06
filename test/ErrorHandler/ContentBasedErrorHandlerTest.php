<?php

declare(strict_types=1);

namespace AcelayaTest\ExpressiveErrorHandler\ErrorHandler;

use Acelaya\ExpressiveErrorHandler\ErrorHandler\ContentBasedErrorResponseGenerator;
use Acelaya\ExpressiveErrorHandler\ErrorHandler\ErrorResponseGeneratorManager;
use Acelaya\ExpressiveErrorHandler\Exception\InvalidArgumentException;
use Acelaya\ExpressiveErrorHandler\Log\BasicLogMessageBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\ServiceManager\ServiceManager;

class ContentBasedErrorHandlerTest extends TestCase
{
    /** @var ContentBasedErrorResponseGenerator */
    protected $errorHandler;

    public function setUp(): void
    {
        $this->errorHandler = new ContentBasedErrorResponseGenerator(
            new ErrorResponseGeneratorManager(new ServiceManager(), [
                'factories' => [
                    'text/html' => [$this, 'factory'],
                    'application/json' => [$this, 'factory'],
                ],
            ]),
            new NullLogger(),
            new BasicLogMessageBuilder()
        );
    }

    public function factory($container, $name): callable
    {
        return function () use ($name) {
            return (new Response())->withHeader('Content-type', $name);
        };
    }

    /**
     * @test
     */
    public function correctAcceptHeaderValueInvokesErrorHandler(): void
    {
        $request = ServerRequestFactory::fromGlobals()->withHeader('Accept', 'foo/bar,application/json');
        $result = $this->errorHandler->__invoke(null, $request, new Response());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-type'));
    }

    /**
     * @test
     */
    public function defaultContentTypeIsUsedWhenNoAcceptHeaderIsPresent(): void
    {
        $request = ServerRequestFactory::fromGlobals();
        $result = $this->errorHandler->__invoke(null, $request, new Response());
        $this->assertEquals('text/html', $result->getHeaderLine('Content-type'));
    }

    /**
     * @test
     */
    public function defaultContentTypeIsUsedWhenAcceptedContentIsNotSupported(): void
    {
        $request = ServerRequestFactory::fromGlobals()->withHeader('Accept', 'foo/bar,text/xml');
        $result = $this->errorHandler->__invoke(null, $request, new Response());
        $this->assertEquals('text/html', $result->getHeaderLine('Content-type'));
    }

    /**
     * @test
     */
    public function ifNoErrorHandlerIsFoundAnExceptionIsThrown(): void
    {
        $this->errorHandler = new ContentBasedErrorResponseGenerator(
            new ErrorResponseGeneratorManager(new ServiceManager(), []),
            new NullLogger(),
            new BasicLogMessageBuilder()
        );
        $request = ServerRequestFactory::fromGlobals()->withHeader('Accept', 'foo/bar,text/xml');

        $this->expectException(InvalidArgumentException::class);
        $this->errorHandler->__invoke(null, $request, new Response());
    }

    /**
     * @test
     */
    public function providedDefaultContentTypeIsUsed(): void
    {
        $this->errorHandler = new ContentBasedErrorResponseGenerator(
            new ErrorResponseGeneratorManager(new ServiceManager(), [
                'factories' => [
                    'text/html' => [$this, 'factory'],
                    'application/json' => [$this, 'factory'],
                ],
            ]),
            new NullLogger(),
            new BasicLogMessageBuilder(),
            'application/json'
        );
        $request = ServerRequestFactory::fromGlobals()->withHeader('Accept', 'foo/bar,text/xml');
        $result = $this->errorHandler->__invoke(null, $request, new Response());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-type'));
    }
}
