<?php declare(strict_types=1);

namespace Limiter\Exception;

use Exception;

/**
 * Ошибка превышение лимита
 */
class RateLimitExceededException extends Exception
{
}
