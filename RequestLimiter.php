<?php declare(strict_types=1);

namespace Limiter;

use Limiter\Exception\LogicalException;
use Limiter\Policies\PoliciesInterface;
use Limiter\Policies\SlidingWindow;
use Exception;

/**
 * Rate Limiter
 */
class RequestLimiter implements LimiterInterface
{
    /** @var string Политика Скользящее окно */
    public const POLICY_SLIDING_WINDOW = SlidingWindow::class;

    /** @var string[] Возможные политики */
    public const POLICIES_LIST = [
        self::POLICY_SLIDING_WINDOW,
    ];

    /** @var string Политика ограничителя */
    private $policy;
    /** @var int Максимальное кол-во запросов */
    private $limit;
    /** @var int Интервал проверки запросов */
    private $interval;
    /** @var string Ключ Лимитера */
    private $key;

    /**
     * @param string $policy
     *
     * @return $this
     *
     * @throws Exception
     */
    public function setPolicy(string $policy): self
    {
        if (!in_array($policy, static::POLICIES_LIST)) {
            throw new LogicalException("Policy $policy not found");
        }

        $this->policy = $policy;

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $interval
     *
     * @return $this
     */
    public function setInterval(int $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param callable|null $callback
     */
    public function try(callable $callback = null): array
    {
        /** @var PoliciesInterface $policy */
        $policy = new $this->policy($this->key, $this->limit, $this->interval);
        $policy->add();

        if ($callback !== null) {
            return $callback();
        }

        return [];
    }
}
