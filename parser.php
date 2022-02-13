<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

try {
    if (php_sapi_name() !== 'cli') {
        throw new \DomainException("Error: This script must be run with command line");
    }

    if (empty($argv[1])) {
        throw new \DomainException("Error: Path to access_log not passed");
    }

    $logFilePath = $argv[1];
    $analiseLogResult = (new \App\UseCases\AnaliseAccessLog($logFilePath))->execute();
    echo json_encode($analiseLogResult, JSON_PRETTY_PRINT);

} catch (\Exception $e) {
    echo $e->getMessage();
    exit(1);
}

