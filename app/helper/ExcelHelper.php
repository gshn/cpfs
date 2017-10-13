<?php
require HELPER.'/excel/PHPExcel.php';
class ExcelHelper
{
    public function __construct()
    {
    }

    public function user()
    {
        $li = new User();
        $title = '사용자';

        $cols = $li->getColumn();
        $list = $li->getList(null, '', PDO::FETCH_NUM);

        if (count($list) < 1) {
            swal('자료없음', '출력할 자료가 없습니다.', 'info');
        }

        $this->excelout($title, $cols, $list);
    }

    public function product()
    {
        $li = new Product();
        $title = '상품';

        $cols = $li->getColumn();
        $cols[] = '아이디';
        $cols[] = '이메일';
        $cols[] = '닉네임';
        $list = $li->getList(null, '', PDO::FETCH_NUM);

        if (count($list) < 1) {
            swal('자료없음', '출력할 자료가 없습니다.', 'info');
        }

        $this->excelout($title, $cols, $list);
    }

    public function excelout($title, $cols, $list)
    {
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()
            ->setCreator('동고물')
            ->setLastModifiedBy('동고물')
            ->setTitle($title)
            ->setSubject('동고물 '.$title.' 데이터')
            ->setDescription('동고물 '.$title.' 데이터')
            ->setKeywords($title)
            ->setCategory($title);

        $objPHPExcel->getActiveSheet()->setTitle($title);
        $objPHPExcel->setActiveSheetIndex(0);
        $filename = iconv('UTF-8', 'EUC-KR', $title.' '.YMDHIS);


        $char = 'A';
        foreach ($cols as $col) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($char.'1', $col);
            $char++;
        }

        $i = 2;
        foreach ($list as $row) {
            $char = 'A';
            foreach ($row as $column) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($char.$i, $column);
                $char++;
            }
            $i += 1;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}
