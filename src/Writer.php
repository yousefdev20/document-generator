<?php

namespace Yousef\GenerateDoc;

use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Yousef\GenerateDoc\Concerns\FromQuery;
use Yousef\GenerateDoc\Concerns\WithHeadings;
use Yousef\GenerateDoc\Files\TemporaryFile;

class Writer
{
    use HasEventBus;

    protected $sheet;

    /**
     * @var object
     */
    protected $exportable;

    /**
     * @var string
     */
    protected string $fileName;

    /**
     * @var temporaryFile
     */
    protected ?TemporaryFile $temporaryFile;

    protected array $config = [];

    /**
     * @param TemporaryFile|null $temporaryFile
     * @param array $config
     */
    public function __construct(?TemporaryFile $temporaryFile, array $config = [])
    {
        $this->temporaryFile = $temporaryFile;
        $this->config = $config;
    }

    /**
     * @param $export
     * @param string $writerType
     * @throws UnsupportedTypeException
     */
    public function export($export, string $fileName, string $writerType)
    {
        $this->exportable = $export;

        $this->fileName = $fileName;

        $this->sheet = WriterEntityFactory::createWriter($writerType);

        $this->open($fileName);

        if ($export instanceof WithHeadings) {
            $this->specialRaise();
        }

        if ($export instanceof FromQuery) {
            $export->query()->chunk($this->config['chunk_size'], function($rows) {
                $this->raise($rows);
            });
        }

        return $this;
    }

    /**
     * @param string $filePath
     * @return $this
     */
    public function open(string $filePath)
    {
        $this->sheet->openToFile($filePath);
        return $this;
    }

    /**
     * @param Row $row
     * @return void
     */
    private function write(Row $row): void
    {
        $this->sheet->addRow($row);
    }

    /**
     * @return mixed
     */
    public function download()
    {
        setcookie('progress', 'downloaded', time() + 60);
        return $this->sheet->openToBrowser($this->fileName);
    }

    public function __destruct()
    {
        $this->sheet->close();
    }
}
