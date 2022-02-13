<?php

declare(strict_types=1);

namespace App\Entities;

final class AccessLog
{
    /**
     * @param string $ip
     * @param string $identity
     * @param string $user
     * @param \DateTime $date_time
     * @param string $method
     * @param string $path
     * @param string $protocol
     * @param int $status
     * @param int $bytes
     * @param string $referer
     * @param string $agent
     */
    public function __construct(
        public string $ip,
        public string $identity,
        public string $user,
        public \DateTime $date_time,
        public string $method,
        public string $path,
        public string $protocol,
        public int $status,
        public int $bytes,
        public string $referer,
        public string $agent,
    )
    {

    }
}