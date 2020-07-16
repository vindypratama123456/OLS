<?php
	require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
	header('Content-Type: application/vnd.ms-excel'); //mime type
	header('Content-Disposition: attachment;filename="list_transaksi.xls"'); //tell browser what's the file name
	header('Cache-Control: max-age=0'); //no cache
	$this->excel->setActiveSheetIndex(0);
	$this->excel->getActiveSheet()->setTitle($nama_gudang);
	$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
	$this->excel->getActiveSheet()->getPageSetup()->setFitToPage(true);
	$this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
	$this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
	$this->excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(5, 5);
	$this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $this->excel->getActiveSheet()->mergeCells('A1:F1');
    $this->excel->getActiveSheet()->setCellValue('A1', 'Daftar Transaksi Siap Kirim - '.$nama_gudang);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->SetSize(16);
	$this->excel->getActiveSheet()->getStyle('A1')->getFont()->SetBold(True);

    //header
    $this->excel->getActiveSheet()->setCellValue('A3','No');
    $this->excel->getActiveSheet()->setCellValue('B3','Kode');
    $this->excel->getActiveSheet()->setCellValue('C3','Tujuan');
    $this->excel->getActiveSheet()->setCellValue('D3','Alamat');
	$this->excel->getActiveSheet()->setCellValue('E3','Jumlah');
	$this->excel->getActiveSheet()->setCellValue('F3','Berat (Kg)');
    $this->excel->getActiveSheet()->getStyle('A3:F3')->getFont()->SetBold(True);
    
	//body
	$rows = 4; 
    $nomor = 1;
    $total_berat = 0;
    $total_jumlah = 0;
    foreach($list_transaksi as $row)
    {
		$this->excel->getActiveSheet()->setCellValue('A'.$rows, $nomor);
		$this->excel->getActiveSheet()->setCellValue('B'.$rows, '#'.$row->kode);
		$this->excel->getActiveSheet()->setCellValue('C'.$rows, $row->tujuan);
		$this->excel->getActiveSheet()->setCellValue('D'.$rows, $row->alamat);
		$this->excel->getActiveSheet()->setCellValue('E'.$rows, $row->total_jumlah);
		$this->excel->getActiveSheet()->setCellValue('F'.$rows, $row->total_berat);

        $total_berat = $total_berat + $row->total_berat;
        $total_jumlah = $total_jumlah + $row->total_jumlah;

        $nomor++;
		$rows++;
	}

    $this->excel->getActiveSheet()->mergeCells('A'.$rows.':D'.$rows);
    $this->excel->getActiveSheet()->setCellValue('A'.$rows, 'Total');
    $this->excel->getActiveSheet()->setCellValue('E'.$rows, $total_jumlah);
    $this->excel->getActiveSheet()->setCellValue('F'.$rows, $total_berat);

    $this->excel->getActiveSheet()->getStyle('A'.$rows)->getFont()->SetBold(True);
	$this->excel->getActiveSheet()->getStyle('A'.$rows)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>