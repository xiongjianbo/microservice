<?php
/**
 * Created by PhpStorm.
 * User: i
 * Date: 2019/2/20
 * Time: 17:28
 */

namespace excel;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use think\Exception;

class Excel
{
    public static $instance;
    public static $excel;

    /** 获取类实例
     * @return Excel
     */
    public static function getInstance()
    {
        return self::$instance ?? self::$instance = new self;
    }

    /** 获取EXCEL实例
     * @return Spreadsheet
     */
    public static function initExcel()
    {
        if (isset(self::$instance)) {
            self::getInstance();
            self::header();
        }
        return self::$excel ?? self::$excel = new Spreadsheet();
    }

    /** 设置header
     * @return mixed
     */
    public static function header()
    {
        self::$excel->getProperties()->setCreator('Cgland')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');
        return self::$excel;
    }

    /** 写入图片到EXCEL
     * @param string $path
     * @param string $pos
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function picture($path = '', $pos = 'A1')
    {
        $drawing = new Drawing();

        $drawing->setName('Paid');

        $drawing->setDescription('Paid');

        $drawing->setPath($path);

        $drawing->setCoordinates($pos);

        $drawing->setOffsetX(5);

        $drawing->setRotation(25);

        $drawing->getShadow()->setVisible(true);

        $drawing->getShadow()->setDirection(45);

        $drawing->setWorksheet(self::initExcel()->getActiveSheet());
        return self::getInstance();
    }

    /** 设置某个单元格文字加粗
     * @param string $pos
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function bold($pos = 'A1')
    {
        self::initExcel()
            ->getActiveSheet()
            ->getStyle($pos)
            ->getFont()
            ->setBold(true);
        return self::getInstance();
    }

    /** 设置某个单元格文字大小
     * @param string $pos
     * @param int $size
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function size($pos = 'A1', $size = 20)
    {
        self::initExcel()
            ->getActiveSheet()
            ->getStyle($pos)
            ->getFont()
            ->setSize($size);
        return self::getInstance();
    }

    /** 设置文字颜色
     * @param string $pos
     * @param string $color
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function color($pos = 'A1', $color = 'FFFFFF')
    {
        self::initExcel()
            ->getActiveSheet()
            ->getStyle($pos)
            ->getFont()
            ->getColor()
            ->setARGB('FF' . $color);
        return self::getInstance();
    }

    /** 设置某个区域之间的背景颜色
     * @param string $posRange
     * @param string $color
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function bgColor($posRange = 'A1:A2', $color = 'CA1B22')
    {
        self::initExcel()
            ->getActiveSheet()
            ->getStyle($posRange)
            ->getFill()
            ->setFillType('solid');

        self::initExcel()
            ->getActiveSheet()
            ->getStyle($posRange)
            ->getFill()
            ->getStartColor()
            ->setARGB('FF' . $color);
        return self::getInstance();
    }

    /** 合并单元格
     * @param string $posRange
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function merge($posRange = 'A1:A2')
    {
        self::initExcel()
            ->getActiveSheet()
            ->mergeCells($posRange);
        return self::getInstance();
    }

    /** 垂直对齐方式
     * @param string $pos
     * @param string $method
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function vAlign($pos = 'A1', $method = 'center')
    {
        self::initExcel()
            ->getActiveSheet()
            ->getStyle($pos
            )->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
        self::initExcel()
            ->getActiveSheet()
            ->getStyle($pos)
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);
        return self::getInstance();
    }

    /** 水平对齐方式
     * @param string $pos
     * @param string $method
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function align($pos = 'A1', $method = 'center')
    {
        self::initExcel()
            ->getActiveSheet()
            ->getStyle($pos)
            ->getAlignment()
            ->setHorizontal($method);
        return self::getInstance();
    }

    /** 设置某一行高度
     * @param int $row
     * @param int $height
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function height($row = 1, $height = 30)
    {
        self::initExcel()
            ->getActiveSheet()
            ->getRowDimension($row)
            ->setRowHeight((int)$height);
        return self::getInstance();
    }

    /** 设置默认行高
     * @param int $height
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function defaultHeight($height = 30)
    {
        self::initExcel()
            ->getActiveSheet()
            ->getDefaultRowDimension()
            ->setRowHeight($height);
        return self::getInstance();
    }

    /** 设置默认行宽
     * @param int $width
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function defaultWidth($width = 10)
    {
        self::initExcel()
            ->getActiveSheet()
            ->getDefaultColumnDimension()
            ->setWidth($width);
        return self::getInstance();
    }

    /** 设置某一列行宽
     * @param string $col
     * @param int $width
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function width($col = 'A', $width = 10)
    {
        self::initExcel()
            ->getActiveSheet()
            ->getColumnDimension($col)
            ->setWidth($width);
        return self::getInstance();
    }

    /** 根据某一列内容自动计算计算单元格宽度
     * @param string $col
     * @param int $width
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function autoWidth($col = 'A')
    {
        //自动计算列宽
        self::initExcel()
            ->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
        return self::getInstance();
    }

    /** 边框
     * @param string $range
     * @param string $style
     * @param string $color
     * @return Excel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function border($range = 'D13:E13', $style = 'thin', $color = '993300')
    {
        //style列表
        /*        const BORDER_NONE = 'none';
                const BORDER_DASHDOT = 'dashDot';
                const BORDER_DASHDOTDOT = 'dashDotDot';
                const BORDER_DASHED = 'dashed';
                const BORDER_DOTTED = 'dotted';
                const BORDER_DOUBLE = 'double';
                const BORDER_HAIR = 'hair';
                const BORDER_MEDIUM = 'medium';
                const BORDER_MEDIUMDASHDOT = 'mediumDashDot';
                const BORDER_MEDIUMDASHDOTDOT = 'mediumDashDotDot';
                const BORDER_MEDIUMDASHED = 'mediumDashed';
                const BORDER_SLANTDASHDOT = 'slantDashDot';
                const BORDER_THICK = 'thick';
                const BORDER_THIN = 'thin';*/

        //自动计算列宽
        $styleThickBrownBorderOutline = [
            'borders' => [
                'outline' => [
                    'borderStyle' => $style,
                    'color' => ['argb' => 'FF' . $color],
                ],
            ],
        ];
        self::initExcel()
            ->getActiveSheet()
            ->getStyle($range)
            ->applyFromArray($styleThickBrownBorderOutline);
        return self::getInstance();
    }

    /**
     * 数字转字母
     * @param $index
     * @param int $start
     * @return string
     */
    public static function intToChr($index, $start = 65)
    {
        $str = '';
        if (floor($index / 26) > 0) {
            $str .= self::intToChr(floor($index / 26) - 1);
        }
        return $str . chr($index % 26 + $start);
    }

    public static function data($data = [])
    {

        $spreadsheet = self::initExcel();

        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $col) {
                $rowCode = self::intToChr($colIndex);
                $colCode = $rowCode . ($rowIndex + 1);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue($colCode, $col);
            }
        }
        return self::getInstance();
    }

    /** 上传EXCEL文件并解析为数组
     * @param int $read_sheet
     * @param int $maxColumn
     * @param int $maxRow
     * @return array
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function uploadExcel($read_sheet = 0, $maxColumn = 10, $maxRow = '')
    {
        $temp_file = current($_FILES);
        $temp = '';
        if (isset($temp_file['name']) && is_array($temp_file['name'])) {
            $temp = current($temp_file['tmp_name']);
        } elseif (isset($temp_file['name'])) {
            $temp = $temp_file['tmp_name'];
        }

        //临时文件为空 抛出异常，转到异常接管文件处理
        if (empty($temp)) {
            throw new Exception('Parse Excel Failed.');
        }

        //兼容XLS和XLSX
        $reader = IOFactory::createReader('Xls');
        if (!$reader->canRead($temp)) {
            $reader = IOFactory::createReader('Xlsx');
        }
        $PHPExcel = $reader->load($temp);
        $sheet = $PHPExcel->getSheet($read_sheet);

        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数

        $result = [];
        $highestRow = empty($maxRow) ? $highestRow : $maxRow;
        $highestColumn = empty($maxColumn) ? $highestColumn : self::intToChr($maxColumn);
        for ($row = 1; $row <= $highestRow; $row++) {
            $range = 'A' . $row . ':' . $highestColumn . $row;
            $result[] = $sheet->rangeToArray($range, null, true, false);
        }
        return $result;
    }

    public static function save($fileName = 'test')
    {
        $spreadsheet = self::initExcel();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}