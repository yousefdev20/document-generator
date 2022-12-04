<?php

namespace Yousef\GenerateDoc\Helpers;

use Yousef\GenerateDoc\Exceptions\NoTypeDetectedException;

class FileTypeDetector
{
    /**
     * @param  $filePath
     * @param  string|null  $type
     * @return string|null
     *
     * @throws NoTypeDetectedException
     */
    public static function detect($filePath, ?string $type = null)
    {
        if (null !== $type) {
            return $type;
        }

        $pathInfo  = pathinfo($filePath);
        $extension = $pathInfo['extension'] ?? '';

        if (trim($extension) === '') {
            throw new NoTypeDetectedException();
        }

        return config('excel.extension_detector.' . strtolower($extension));
    }

    /**
     * @param  string  $filePath
     * @param  string|null  $type
     * @return string
     *
     * @throws NoTypeDetectedException
     */
    public static function detectStrict(string $filePath, string $type = null): string
    {
        $type = static::detect($filePath, $type);

        if (!$type) {
            throw new NoTypeDetectedException();
        }

        return $type;
    }
}
