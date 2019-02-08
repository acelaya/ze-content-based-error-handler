<?php
declare(strict_types=1);

namespace Acelaya\ExpressiveErrorHandler\Exception;

use InvalidArgumentException as SplInvalidArgumentException;

class InvalidArgumentException extends SplInvalidArgumentException implements ExceptionInterface
{
}
