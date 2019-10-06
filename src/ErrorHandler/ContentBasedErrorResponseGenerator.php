<?php

declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler\ErrorHandler;

use Acelaya\ExpressiveErrorHandler\Exception\InvalidArgumentException;
use Acelaya\ExpressiveErrorHandler\Log\LogMessageBuilderInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Throwable;

use function explode;
use function implode;
use function sprintf;

class ContentBasedErrorResponseGenerator implements ErrorResponseGeneratorInterface
{
    private const DEFAULT_CONTENT = 'text/html';

    /** @var ErrorResponseGeneratorManagerInterface */
    private $errorHandlerManager;
    /** @var LoggerInterface */
    private $logger;
    /** @var LogMessageBuilderInterface */
    private $logMessageBuilder;
    /** @var string */
    private $defaultContentType;

    /**
     * ContentBasedErrorResponseGenerator constructor.
     * @param ErrorResponseGeneratorManagerInterface $errorHandlerManager
     * @param LoggerInterface $logger
     * @param LogMessageBuilderInterface $logMessageBuilder
     * @param string $defaultContentType
     */
    public function __construct(
        ErrorResponseGeneratorManagerInterface $errorHandlerManager,
        LoggerInterface $logger,
        LogMessageBuilderInterface $logMessageBuilder,
        string $defaultContentType = self::DEFAULT_CONTENT
    ) {
        $this->errorHandlerManager = $errorHandlerManager;
        $this->logger = $logger;
        $this->logMessageBuilder = $logMessageBuilder;
        $this->defaultContentType = $defaultContentType;
    }

    /**
     * Final handler for an application.
     *
     * @param \Throwable|null $e
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws InvalidArgumentException
     */
    public function __invoke(?Throwable $e, Request $request, Response $response): Response
    {
        // Try to get an error handler for provided request accepted type
        $errorHandler = $this->resolveErrorHandlerFromAcceptHeader($request);
        $this->logger->error($this->logMessageBuilder->buildMessage($request, $response, $e));
        return $errorHandler($e, $request, $response);
    }

    /**
     * Tries to resolve an error response generator based on request's accept header
     *
     * @param Request $request
     * @return callable
     * @throws InvalidArgumentException
     */
    private function resolveErrorHandlerFromAcceptHeader(Request $request): callable
    {
        // Try to find an error handler for one of the accepted content types
        $accepts = $request->hasHeader('Accept') ? $request->getHeaderLine('Accept') : $this->defaultContentType;
        /** @var array $accepts */
        $accepts = explode(',', $accepts);
        foreach ($accepts as $accept) {
            if (! $this->errorHandlerManager->has($accept)) {
                continue;
            }

            return $this->errorHandlerManager->get($accept);
        }

        // If it wasn't possible to find an error handler for accepted content type, use default one if registered
        if ($this->errorHandlerManager->has($this->defaultContentType)) {
            return $this->errorHandlerManager->get($this->defaultContentType);
        }

        // It wasn't possible to find an error handler
        throw new InvalidArgumentException(sprintf(
            'It wasn\'t possible to find an error handler for ["%s"] content types. '
            . 'Make sure you have registered at least the default "%s" content type',
            implode('", "', $accepts),
            $this->defaultContentType
        ));
    }
}
