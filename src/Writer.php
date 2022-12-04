<?php

namespace Yousef\GenerateDoc;

use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterAbstract;
use Yousef\GenerateDoc\Concerns\FromQuery;
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
     * @var temporaryFile
     */
    protected TemporaryFile $temporaryFile;

    protected array $config = [];

    /**
     * @param  TemporaryFile  $temporaryFile
     */
    public function __construct(TemporaryFile $temporaryFile, array $config = [])
    {
        $this->temporaryFile = $temporaryFile;
        $this->config = $config;
    }

    /**
     * @param $export
     * @param string $writerType
     * @return TemporaryFile
     * @throws UnsupportedTypeException
     */
    public function export($export, string $fileName, string $writerType): static
    {
        $this->exportable = $export;

        $this->sheet = WriterEntityFactory::createWriter($fileName);

        $this->open($fileName);

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
    public function open(string $filePath): static
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
     * @return void
     */
    public function download(): void
    {
        $this->sheet->openToBrowser();
    }

    public function __destruct()
    {
        $this->sheet->close();
    }
}
