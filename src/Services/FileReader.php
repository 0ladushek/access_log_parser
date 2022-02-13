<?php

declare(strict_types=1);

namespace App\Services;

/**
 *
 */
class FileReader
{
    /**
     * @param string $filePath
     */
    public function __construct(
        private string $filePath
    )
    {
        if (! file_exists($this->filePath)) {
            throw new \DomainException("Error: File not found");
        }
    }

    /**
     * @return \Generator
     */
    public function fetch(): \Generator
    {
        $handle = fopen($this->filePath, 'r');

        while (!feof($handle)) {
            $str = fgets($handle, 4096);
            yield trim($str);
        }
        fclose($handle);
    }
}