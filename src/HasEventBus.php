<?php

namespace Yousef\GenerateDoc;

use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

trait HasEventBus
{

    public function raise($rows): void
    {
        foreach ($rows as $row) {
            $this->listener($this->exportable->toArray($row));
        }
    }

    public function specialRaise()
    {
        $this->listener($this->exportable->headings(), false);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function listener(array $row, bool $defaultStyle = true): void
    {
        if (!$defaultStyle) {

            $style = (new StyleBuilder())
                ->setFontBold()
                ->setFontSize(15)
                ->setFontColor(Color::BLUE)
                ->setShouldWrapText()
                ->setCellAlignment(CellAlignment::CENTER)
                ->setBackgroundColor(Color::YELLOW)
                ->build();
        }

        foreach ($row as $value) {
            $cells[] = WriterEntityFactory::createCell($value, $style);
        }

        $this->write(WriterEntityFactory::createRow($cells ?? []));
    }
}
