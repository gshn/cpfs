<?php
class ExcelHelper
{
    public function contact()
    {
        $li = new Contact();
        $title = '접촉기록';

        $cols = $li->getColumn();
        $cols[] = '비콘 시리얼';
        $cols[] = '비콘 지점명';
        $cols[] = 'GPS 지점명';
        $cols[] = 'GPS 색인';
        $cols[] = 'GPS 위도';
        $cols[] = 'GPS 경도';
        $list = $li->getList(null, '', PDO::FETCH_NUM);

        if (count($list) < 1) {
            swal('자료없음', '출력할 자료가 없습니다.', 'info');
        }

        $this->excelout($title, $cols, $list);
    }

    public function beacon()
    {
        $li = new Beacon();
        $title = '비콘';

        $cols = $li->getColumn();
        $list = $li->getList(null, '', PDO::FETCH_NUM);

        if (count($list) < 1) {
            swal('자료없음', '출력할 자료가 없습니다.', 'info');
        }

        $this->excelout($title, $cols, $list);
    }

    public function gps()
    {
        $li = new Gps();
        $title = 'GPS';

        $cols = $li->getColumn();
        $list = $li->getList(null, '', PDO::FETCH_NUM);

        if (count($list) < 1) {
            swal('자료없음', '출력할 자료가 없습니다.', 'info');
        }

        $this->excelout($title, $cols, $list);
    }

    public function admin()
    {
        $li = new Admin();
        $title = '담당자';

        $cols = $li->getColumn();
        $list = $li->getList(null, '', PDO::FETCH_NUM);

        if (count($list) < 1) {
            swal('자료없음', '출력할 자료가 없습니다.', 'info');
        }

        $this->excelout($title, $cols, $list);
    }

    public function excelout($title, $cols, $list)
    {
        require HELPER.'/excel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()
            ->setCreator('KEPCO')
            ->setLastModifiedBy('KEPCO')
            ->setTitle($title)
            ->setSubject('KEPCO '.$title.' 데이터')
            ->setDescription('KEPCO '.$title.' 데이터')
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
