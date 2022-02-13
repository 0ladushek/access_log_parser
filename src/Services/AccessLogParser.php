<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\AccessLog;

/**
 *
 */
class AccessLogParser
{
    /**
     * @param string $line
     */
    public function __construct(
        private string $line
    )
    {

    }

    /**
     * @return AccessLog
     */
    public function parse(): AccessLog
    {
        preg_match(
            '/^(\S+) (\S+) (\S+) \[([^:]+):(\d+:\d+:\d+) ([^\]]+)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) (\".*?\") (\".*?\")$/',
            $this->line,
            $matches
        );

        if (count($matches) < 13) {
            throw new \DomainException('Error: Invalid format');
        }

        return new AccessLog(
            ip: $matches[1],
            identity: $matches[2],
            user: $matches[2],
            date_time: \DateTime::createFromFormat('d/M/Y H:i:s', "$matches[4] $matches[5]")
                        ->setTimezone((new \DateTimeZone($matches[6]))),
            method: $matches[7],
            path: $matches[8],
            protocol: $matches[9],
            status: (int) $matches[10],
            bytes: (int) $matches[11],
            referer: $matches[12],
            agent: $matches[13],
        );
    }
}