<?php


namespace common\services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;


/**
 * Class ImportService
 * @package common\services
 */
class ImportService
{
    /** @var string */
    public const READ_MODE_FILE = 'file';
    public const READ_MODE_REMOTE = 'remote';

    /**
     * @param $source
     * @param string $destination
     * @param string $filename
     * @return string
     */
    public function saveSpreadsheetToFile($source, $destination = '.', $filename = 'import.xlsx')
    {
        try {
            $data = file_get_contents($source);
        } catch (\Exception $exception) {
            throw new PhpSpreadsheetException($exception->getMessage());
        }

        try {
            file_put_contents($destination . DIRECTORY_SEPARATOR . $filename, $data);
        } catch (\Exception $exception) {
            throw new PhpSpreadsheetException($exception->getMessage());
        }

        return $destination . DIRECTORY_SEPARATOR  . $filename;
    }

    /**
     * @param $file
     * @param string $mode
     */
    public function readSpreadsheet($file, $mode = 'file')
    {
        if ($mode == self::READ_MODE_REMOTE) {
            $file = $this->saveSpreadsheetToFile("https://docs.google.com/spreadsheets/d/e/2PACX-1v{$file}/pub?output=xlsx", \Yii::getAlias('@console') . '/runtime');
        }

        return IOFactory::load($file);
    }

    /**
     * @param $file
     * @param string $mode
     * @return \Generator
     * @throws PhpSpreadsheetException
     */
    public function readSpreadsheetAsArray($file, $mode = 'file')
    {
        try {
            $PHPExcel = $this->readSpreadsheet($file, $mode);
        } catch (\Exception $exception) {
            throw new PhpSpreadsheetException($exception->getMessage());
        }

        for ($sn = 0; $sn < $PHPExcel->getSheetCount(); $sn++)
        {
            $PHPExcel->setActiveSheetIndex($sn);
            $sheet = $PHPExcel->getActiveSheet();
            $headers = [];

            foreach ($sheet->getRowIterator() as $i => $row)
            {
                $item = [];

                if ($i == 1) {

                    foreach ($row->getCellIterator() as $x => $cell)
                    {
                        $value = trim($cell->getValue());

                        if (empty($value)) {
                            break;
                        }

                        $headers[$x] = $value;
                    }

                    continue;
                }

                foreach ($row->getCellIterator() as $x => $cell)
                {
                    if (!array_key_exists($x, $headers)) {
                        break;
                    }

                    $value = in_array($x, []) ? floatval($cell->getValue()) : trim($cell->getValue());

                    $item[$headers[$x]] = $value;
                }

                if (empty($item[$headers['A']])) {
                    break;
                }

                yield $item;
            }
        }
    }
}