<?php declare(strict_types=1);

namespace Limiter;

/**
 * Интерфейс для лимитеров!
 */
interface LimiterInterface
{
    /**
     * @param callable|null $callback
     */
    public function try(callable $callback = null): array;
}
