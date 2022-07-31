<?php

declare(strict_types=1);

namespace tr33m4n\Di\Config;

interface FileAdapterInterface
{
    /**
     * Read file
     *
     * @throws \tr33m4n\Di\Exception\AdapterException
     * @return array<string, mixed>
     */
    public function read(string $filePath): array;

    /**
     * Validate file
     */
    public function validate(string $filePath): bool;

    /**
     * Get adapter file extension
     */
    public static function getFileExtension(): string;
}
