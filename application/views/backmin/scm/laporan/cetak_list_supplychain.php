<?php
	require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
	header('Content-Type: application/vnd.ms-excel'); //mime type
	header('Content-Disposition: attachment;filename="liststok_supplychain"'); //tell browser what's the file name
	header('Cache-Control: max-age=0'); //no cache
	$this->excel->setActiveSheetIndex(0);
	$this->excel->getActiveSheet()->setTitle("Laporan Supply Chain");
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
	$this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
    $this->excel->getActiveSheet()->mergeCells('A1:L1');
    $this->excel->getActiveSheet()->setCellValue('A1', 'Laporan Stok Supply Chain');
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->SetSize(16);
	$this->excel->getActiveSheet()->getStyle('A1')->getFont()->SetBold(True);

    //header
    $this->excel->getActiveSheet()->setCellValue('A3','No');
    $this->excel->getActiveSheet()->setCellValue('B3','Judul Buku');
    $this->excel->getActiveSheet()->setCellValue('C3','Kelas');
    $this->excel->getActiveSheet()->setCellValue('D3','Stok Fisik');
    $this->excel->getActiveSheet()->setCellValue('E3','Diambil IP');
    $this->excel->getActiveSheet()->setCellValue('F3','Kirim');
    $this->excel->getActiveSheet()->setCellValue('G3','Total Produksi');
    $this->excel->getActiveSheet()->setCellValue('H3','Tunggu Konfirmasi SC');
    $this->excel->getActiveSheet()->setCellValue('I3','Booking');
    $this->excel->getActiveSheet()->setCellValue('J3','Belum Kirim');
    $this->excel->getActiveSheet()->setCellValue('K3','Total Pesanan');
    $this->excel->getActiveSheet()->setCellValue('L3','Available');
    $this->excel->getActiveSheet()->getStyle('A3:L3')->getFont()->SetBold(True);
    
	//body
	$rows = 4; 
    $nomor = 1;
    foreach($liststok_supplychain as $row)
    {
		$this->excel->getActiveSheet()->setCellValue('A'.$rows, $nomor);
		$this->excel->getActiveSheet()->setCellValue('B'.$rows, $row->judul_buku);
		$this->excel->getActiveSheet()->setCellValue('C'.$rows, $row->kelas);
		$this->excel->getActiveSheet()->setCellValue('D'.$rows, $row->stok_fisik);
		$this->excel->getActiveSheet()->setCellValue('E'.$rows, $row->stok_ip);
		$this->excel->getActiveSheet()->setCellValue('F'.$rows, $row->stok_kirim);
		$this->excel->getActiveSheet()->setCellValue('G'.$rows, $row->total_produksi);
		$this->excel->getActiveSheet()->setCellValue('H'.$rows, $row->stok_konfirmasi);
		$this->excel->getActiveSheet()->setCellValue('I'.$rows, $row->stok_booking);
		$this->excel->getActiveSheet()->setCellValue('J'.$rows, $row->stok_belum_kirim);
		$this->excel->getActiveSheet()->setCellValue('K'.$rows, $row->total_pesanan);
		$this->excel->getActiveSheet()->setCellValue('L'.$rows, $row->stok_available);

        $nomor++;
		$rows++;
	}

    $this->excel->getActiveSheet()->mergeCells('A'.$rows.':C'.$rows);
    $this->excel->getActiveSheet()->setCellValue('A'.$rows, 'Total');
    $this->excel->getActiveSheet()->setCellValue('D'.$rows, '=SUM(D4:D'.($rows-1).')');
    $this->excel->getActiveSheet()->setCellValue('E'.$rows, '=SUM(E4:E'.($rows-1).')');
	$this->excel->getActiveSheet()->setCellValue('F'.$rows, '=SUM(F4:F'.($rows-1).')');
	$this->excel->getActiveSheet()->setCellValue('G'.$rows, '=SUM(G4:G'.($rows-1).')');
	$this->excel->getActiveSheet()->setCellValue('H'.$rows, '=SUM(H4:H'.($rows-1).')');
	$this->excel->getActiveSheet()->setCellValue('I'.$rows, '=SUM(I4:I'.($rows-1).')');
	$this->excel->getActiveSheet()->setCellValue('J'.$rows, '=SUM(J4:J'.($rows-1).')');
	$this->excel->getActiveSheet()->setCellValue('K'.$rows, '=SUM(K4:K'.($rows-1).')');
	$this->excel->getActiveSheet()->setCellValue('L'.$rows, '=SUM(L4:L'.($rows-1).')');

    $this->excel->getActiveSheet()->getStyle('A'.$rows)->getFont()->SetBold(True);
	$this->excel->getActiveSheet()->getStyle('A'.$rows)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>