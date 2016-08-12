<?php
namespace Acelaya\ExpressiveErrorHandler\ErrorHandler;

use Acelaya\ExpressiveErrorHandler\Exception\InvalidArgumentException;
use Acelaya\ExpressiveErrorHandler\Log\LogMessageBuilderInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class ContentBasedErrorHandler implements ErrorHandlerInterface
{
    const DEFAULT_CONTENT = 'text/html';

    /**
     * @var ErrorHandlerManagerInterface
     */
    private $errorHandlerManager;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var LogMessageBuilderInterface
     */
    private $logMessageBuilder;

    /**
     * ContentBasedErrorHandler constructor.
     * @param ErrorHandlerManagerInterface|ErrorHandlerManager $errorHandlerManager
     * @param LogMessageBuilderInterface $logMessageBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        ErrorHandlerManagerInterface $errorHandlerManager,
        LoggerInterface $logger,
        LogMessageBuilderInterface $logMessageBuilder
    ) {
        $this->errorHandlerManager = $errorHandlerManager;
        $this->logger = $logger;
        $this->logMessageBuilder = $logMessageBuilder;
    }

    /**
     * Final handler for an application.
     *
     * @param Request $request
     * @param Response $response
     * @param null|mixed $err
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $err = null)
    {
        // Try to get an error handler for provided request accepted type
        $errorHandler = $this->resolveErrorHandlerFromAcceptHeader($request);
        $this->logger->error($this->logMessageBuilder->buildMessage($request, $response, $err));
        return $errorHandler($request, $response, $err);
    }

    /**
     * Tries to resolve
     *
     * @param Request $request
     * @return callable
     */
    protected function resolveErrorHandlerFromAcceptHeader(Request $request)
    {
        // Try to find an error handler for one of the accepted content types
        $accepts = $request->hasHeader('Accept') ? $request->getHeaderLine('Accept') : self::DEFAULT_CONTENT;
        $accepts = explode(',', $accepts);
        foreach ($accepts as $accept) {
            if (! $this->errorHandlerManager->has($accept)) {
                continue;
            }

            return $this->errorHandlerManager->get($accept);
        }

        // If it wasn't possible to find an error handler for accepted content type, use default one if registered
        if ($this->errorHandlerManager->has(self::DEFAULT_CONTENT)) {
            return $this->errorHandlerManager->get(self::DEFAULT_CONTENT);
        }

        // It wasn't possible to find an error handler
        throw new InvalidArgumentException(sprintf(
            'It wasn\'t possible to find an error handler for ["%s"] content types. '
            . 'Make sure you have registered at least the default "%s" content type',
            implode('", "', $accepts),
            self::DEFAULT_CONTENT
        ));
    }
}
