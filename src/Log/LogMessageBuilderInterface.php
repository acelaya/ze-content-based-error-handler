<?php
declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler\Log;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

interface LogMessageBuilderInterface
{
    /**
     * Builds a message to be logged based on the error handler params
     *
     * @param Request $request
     * @param Response $response
     * @param \Throwable|null $err
     * @return string
     */
    public function buildMessage(Request $request, Response $response, Throwable $err = null): string;
}
