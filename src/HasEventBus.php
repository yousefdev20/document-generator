<?php

namespace Yousef\GenerateDoc;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

trait HasEventBus
{

    public function raise($rows): void
    {
        foreach ($rows as $row) {
            $this->listener($this->exportable->toArray($row));
        }
    }

    public function listener(array $row): void
    {
        foreach ($row as $value) {
            $cells[] = WriterEntityFactory::createCell($value);
        }

        $this->write(WriterEntityFactory::createRow($cells ?? []));
    }
}
