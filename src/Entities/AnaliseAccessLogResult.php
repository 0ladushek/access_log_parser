<?php

declare(strict_types=1);

namespace App\Entities;


final class AnaliseAccessLogResult
{
    /**
     * @param int $views
     * @param int $urls
     * @param int $traffic
     * @param array $crawlers
     * @param array $statusCodes
     */
    public function __construct(
        public int $views = 0,
        public int $urls = 0,
        public int $traffic = 0,
        public array $crawlers = [],
        public array $statusCodes = [],
    )
    {

    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'views' => $this->views,
            'urls' => $this->urls,
            'traffic' => $this->traffic,
            'crawlers' => $this->crawlers,
            'statusCodes' => $this->statusCodes,
        ];
    }
}