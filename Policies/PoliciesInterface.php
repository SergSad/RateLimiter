<?php declare(strict_types=1);

namespace Limiter\Policies;

/**
 * Интерфейс для политик
 */
interface PoliciesInterface
{
    /**
     * Добавить запись в лимитер
     */
    public function add(): void;
}
