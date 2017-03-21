<?php
namespace Acelaya\ExpressiveErrorHandler\ErrorHandler;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

interface ErrorResponseGeneratorInterface
{
    /**
     * Final handler for an application.
     *
     * @param \Throwable|\Exception $e
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke($e, Request $request, Response $response);
}
