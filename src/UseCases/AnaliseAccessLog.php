<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Entities\AnaliseAccessLogResult;
use App\Services\FileReader;


final class AnaliseAccessLog
{
    /**
     * @var FileReader
     */
    private FileReader $fileReaderService;
    /**
     * @var array
     */
    private array $urls = [];

    /**
     * @param string $logFilePath
     */
    public function __construct(
        private string $logFilePath
    )
    {
        $this->fileReaderService = new FileReader($this->logFilePath);
    }

    /**
     * @return AnaliseAccessLogResult
     */
    public function execute(): AnaliseAccessLogResult
    {
        $views = 0;
        $urls = 0;
        $traffic = 0;
        $crawlers = [
            'Google' => 0,
            'Bing' => 0,
            'Baidu' => 0,
            'Yandex' => 0
        ];
        $statusCodes = [];

        foreach ($this->fileReaderService->fetch() as $line) {
            $accessLog = (new \App\Services\AccessLogParser($line))->parse();
            $views++;
            if ($this->isUniqueUrl($accessLog->path)) {
                $urls++;
            }

            if ($this->isOkStatus($accessLog->status)) {
                $traffic += $accessLog->bytes;
            }

            if ($this->isGoogleBotVisit($accessLog->agent)) {
                $crawlers['Google']++;
            }
            if ($this->isBingBotVisit($accessLog->agent)) {
                $crawlers['Bing']++;
            }
            if ($this->isBaiduBotVisit($accessLog->agent)) {
                $crawlers['Baidu']++;
            }
            if ($this->isYandexBotVisit($accessLog->agent)) {
                $crawlers['Yandex']++;
            }
            if (! array_key_exists($accessLog->status, $statusCodes)) {
                $statusCodes[$accessLog->status] = 1;
            } else {
                $statusCodes[$accessLog->status]++;
            }

        }

        return new AnaliseAccessLogResult(
            views: $views,
            urls: $urls,
            traffic: $traffic,
            crawlers: $crawlers,
            statusCodes: $statusCodes,
        );
    }

    /**
     * @param string $url
     * @return bool
     */
    private function isUniqueUrl(string $url): bool
    {
        if (in_array($url, $this->urls)) {
            return false;
        }

        $this->urls[] = $url;
        return true;
    }

    /**
     * @param string $agent
     * @return bool
     */
    private function isGoogleBotVisit(string $agent): bool
    {
        return stripos($agent, 'Googlebot') !== false;
    }

    /**
     * @param string $agent
     * @return bool
     */
    private function isBingBotVisit(string $agent): bool
    {
        return stripos($agent, 'bingbot') !== false;
    }

    /**
     * @param string $agent
     * @return bool
     */
    private function isBaiduBotVisit(string $agent): bool
    {
        return stripos($agent, 'Baiduspider') !== false;
    }

    /**
     * @param string $agent
     * @return bool
     */
    private function isYandexBotVisit(string $agent): bool
    {
        return stripos($agent, 'yandex') !== false;
    }

    /**
     * @param int $status
     * @return bool
     */
    private function isOkStatus(int $status): bool
    {
        return $status === 200;
    }
}