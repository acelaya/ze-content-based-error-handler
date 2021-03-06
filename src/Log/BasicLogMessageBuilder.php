<?php

declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler\Log;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

use function sprintf;

use const PHP_EOL;

class BasicLogMessageBuilder implements LogMessageBuilderInterface
{
    /**
     * Builds a message to be logged based on the error handler params
     *
     * @param Request $request
     * @param Response $response
     * @param \Throwable|null $err
     * @return string
     */
    public function buildMessage(Request $request, Response $response, ?Throwable $err = null): string
    {
        $base = 'Error occurred while dispatching request';
        if ($err === null) {
            return $base;
        }

        return sprintf('%s:%s%s', $base, PHP_EOL, $err);
    }
}
