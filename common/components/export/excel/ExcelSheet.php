<?php
namespace common\components\export\excel;

use Yii;

/**
 * Excel File
 */
class ExcelSheet extends \codemix\excelexport\ExcelSheet
{
    public $heading;
    public $header = [];
    public $footer = [];
    public $overwriteTitles = false;

    public function renderHeader()
    {
        // First row
        // $this->renderHeading();

        foreach ($this->header as $cell => $value) {
            if ($this->isRange($cell)) $this->_sheet->mergeCells($cell);
            $firstCell = $this->getFirstCellInRange($cell);
            $this->_sheet->setCellValue($firstCell, $value);
            $this->_sheet->getStyle($firstCell)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ],
            ]);
        }
    }

    public function renderCells($cells, $styles = null)
    {
        foreach ($cells as $cell => $value) {
            if ($this->isRange($cell)) $this->_sheet->mergeCells($cell);
            $firstCell = $this->getFirstCellInRange($cell);
            $this->_sheet->setCellValue($firstCell, $value);
            if ($styles) {
                $this->_sheet->getStyle($firstCell)->applyFromArray($styles);
            }
        }
    }

    protected function renderTitle()
    {
        if ($this->overwriteTitles) { 
            return;
        }
        parent::renderTitle();
    }

    public function renderHeading()
    {
        $columns = array_keys($this->getTitles());
        $lastColumn = end($columns);
        $titleRange = sprintf("A1:%s1", $lastColumn);
        $this->_sheet->mergeCells($titleRange);
        $this->_sheet->setCellValue("A1", $this->heading);
        $this->_sheet->getStyle("A1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 15,
            ],
            'alignment' => [
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ],
        ]);
    }

    public function renderFooter()
    {
        foreach ($this->footer as $cell => $value) {
            if ($this->isRange($cell)) $this->_sheet->mergeCells($cell);
            $firstCell = $this->getFirstCellInRange($cell);
            $this->_sheet->setCellValue($firstCell, $value);
            $this->_sheet->getStyle($firstCell)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ],
            ]);
        }
    }

    public function isRange($cell)
    {
        $parts = explode(":", $cell);
        return count($parts) == 2;
    }

    public function getFirstCellInRange($range)
    {
        $parts = explode(":", $range);
        return reset($parts);
    }
}