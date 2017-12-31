<?php
declare(strict_types=1);

namespace AcelayaTest\ExpressiveErrorHandler\Log;

use Acelaya\ExpressiveErrorHandler\Log\BasicLogMessageBuilder;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class BasicLogMessageBuilderTest extends TestCase
{
    /**
     * @var BasicLogMessageBuilder
     */
    protected $messageBuilder;

    public function setUp()
    {
        $this->messageBuilder = new BasicLogMessageBuilder();
    }

    /**
     * @test
     */
    public function onlyBaseIsProvidedWithNoError()
    {
        $message = $this->messageBuilder->buildMessage(ServerRequestFactory::fromGlobals(), new Response());
        $this->assertEquals('Error occurred while dispatching request', $message);
    }

    /**
     * @test
     */
    public function errorIsIncludedWhenProvided()
    {
        $err = 'A super critical error';
        $message = $this->messageBuilder->buildMessage(ServerRequestFactory::fromGlobals(), new Response(), $err);
        $this->assertEquals('Error occurred while dispatching request: ' . PHP_EOL . $err, $message);
    }
}
