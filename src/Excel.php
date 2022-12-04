<?php

namespace Yousef\GenerateDoc;

use Yousef\GenerateDoc\Exceptions\NoTypeDetectedException;
use Yousef\GenerateDoc\Files\LocalTemporaryFile;
use Yousef\GenerateDoc\Helpers\FileTypeDetector;
use Yousef\GenerateDoc\Files\TemporaryFile;

class Excel
{
    const XLSX     = 'Xlsx';

    const CSV      = 'Csv';

    const TSV      = 'Csv';

    const ODS      = 'Ods';

    const XLS      = 'Xls';

    protected static Writer $writer;
    protected static array $config = [];

    /**
     * @param $export
     * @param string $fileName
     * @param string|null $writerType
     * @return TemporaryFile|Writer
     * @throws NoTypeDetectedException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     */
    public static function export($export, string $fileName, ?string $writerType = null)
    {
        $writerType = FileTypeDetector::detectStrict($fileName, $writerType);

        self::$writer = new Writer(new LocalTemporaryFile(self::$config['filePath']), self::$config);
        return self::$writer->export($export, $fileName, $writerType);
    }

    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }
}
