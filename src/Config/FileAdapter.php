<?php

declare(strict_types=1);

namespace tr33m4n\Di\Config;

use tr33m4n\Di\Exception\AdapterException;

final class FileAdapter implements FileAdapterInterface
{
    /**
     * @inheritDoc
     */
    public function read(string $filePath): array
    {
        if (!$this->validate($filePath)) {
            throw new AdapterException(sprintf('File name is invalid %s', $filePath));
        }

        $fileContents = include $filePath;
        if (!$fileContents) {
            throw new AdapterException(sprintf('Unable to include file %s', $filePath));
        }

        return $fileContents;
    }

    /**
     * @inheritDoc
     */
    public function validate(string $filePath): bool
    {
        return file_exists($filePath) && pathinfo($filePath, PATHINFO_BASENAME) === self::FILE_NAME;
    }
}
