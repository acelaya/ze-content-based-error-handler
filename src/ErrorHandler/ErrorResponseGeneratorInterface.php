<?php
declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler\ErrorHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface ErrorResponseGeneratorInterface
{
    /**
     * Final handler for an application.
     *
     * @param \Throwable $e
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke($e, Request $request, Response $response);
}
