<?php
namespace Acelaya\ExpressiveErrorHandler\Log;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface LogMessageBuilderInterface
{
    /**
     * Builds a message to be logged based on the error handler params
     *
     * @param Request $request
     * @param Response $response
     * @param null $err
     * @return string
     */
    public function buildMessage(Request $request, Response $response, $err = null);
}
