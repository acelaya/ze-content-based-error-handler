<?php
declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler\ErrorHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

interface ErrorResponseGeneratorInterface
{
    /**
     * Final handler for an application.
     *
     * @param \Throwable|null $e
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(?Throwable $e, Request $request, Response $response);
}
