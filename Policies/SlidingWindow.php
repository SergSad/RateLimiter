<?php declare(strict_types=1);

namespace Limiter\Policies;

use Limiter\Exception\RateLimitExceededException;
use Carbon\Carbon;
use Exception;

/**
 * Политика Скользящее окно
 */
class SlidingWindow implements PoliciesInterface
{
    /** @var string Ключ хранилища */
    private $key;
    /** @var int Максимальное кол-во запросов */
    private $limit;
    /** @var int Интервал проверки запросов */
    private $interval;

    /**
     * @param string $key
     * @param int $limit
     * @param int $interval
     */
    public function __construct(string $key, int $limit, int $interval)
    {
        $this->key = 'LimiterSlidingWindow:' . $key;
        $this->limit = $limit;
        $this->interval = $interval;
    }

    /**
     * @throws Exception
     */
    public function add(): void
    {
        $now = (new Carbon())->getTimestamp();
        $end = $now - $this->interval;

        // Удаляем значения который вышли за рамки интервала
        Redis::zRemRangeByScore(__METHOD__, $this->key, 0, $end);

        if (Redis::zCount(__METHOD__, $this->key, 0, $end) >= $this->limit) {
            throw new RateLimitExceededException('You are not allowed this action.');
        }

        Redis::zAdd(__METHOD__, $this->key, $now, $now);
        Redis::expire(__METHOD__, $this->key, $this->interval);
    }
}
