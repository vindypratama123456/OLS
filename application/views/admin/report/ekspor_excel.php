<?php
	require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
	header('Content-Type: application/vnd.ms-excel'); //mime type
	header('Content-Disposition: attachment;filename="'.$file_name.'"'); //tell browser what's the file name
	header('Cache-Control: max-age=0'); //no cache

	$this->excel->setActiveSheetIndex(0);
	$this->excel->getActiveSheet()->setTitle($sheet_name);
	$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
	$this->excel->getActiveSheet()->getPageSetup()->setFitToPage(true);
	$this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
	$this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
	$this->excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(36, 36);
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
	$this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
	$this->excel->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
    //header
    $this->excel->getActiveSheet()->setCellValue('A1','No');
    $this->excel->getActiveSheet()->setCellValue('B1','Kode Pesanan');
    $this->excel->getActiveSheet()->setCellValue('C1','Tgl Pesan');
    $this->excel->getActiveSheet()->setCellValue('D1','NPSN');
    $this->excel->getActiveSheet()->setCellValue('E1','Nama Sekolah');
    $this->excel->getActiveSheet()->setCellValue('F1','Alamat');
    $this->excel->getActiveSheet()->setCellValue('G1','Kecamatan');
    $this->excel->getActiveSheet()->setCellValue('H1','Kab/Kota');
    $this->excel->getActiveSheet()->setCellValue('I1','Propinsi');
    $this->excel->getActiveSheet()->setCellValue('J1','Kode Buku');
    $this->excel->getActiveSheet()->setCellValue('K1','Judul Buku');
    $this->excel->getActiveSheet()->setCellValue('L1','Jenjang');
    $this->excel->getActiveSheet()->setCellValue('M1','Kelas');
    $this->excel->getActiveSheet()->setCellValue('N1','Jumlah');
    $this->excel->getActiveSheet()->setCellValue('O1','Harga');
    $this->excel->getActiveSheet()->setCellValue('P1','Zona');
    $this->excel->getActiveSheet()->setCellValue('Q1','Kodepos');
    $this->excel->getActiveSheet()->setCellValue('R1','Telpon');
    $this->excel->getActiveSheet()->setCellValue('S1','Tgl Konfirmasi');
    $this->excel->getActiveSheet()->setCellValue('T1','Tgl Logistik');
    $this->excel->getActiveSheet()->setCellValue('U1','Waktu Pelaksanaan');
    $this->excel->getActiveSheet()->setCellValue('V1','Tgl Kirim');
    $this->excel->getActiveSheet()->setCellValue('W1','Tgl Sampai');
    $this->excel->getActiveSheet()->setCellValue('X1','Nama Penerima');
    $this->excel->getActiveSheet()->setCellValue('Y1','Tgl Terima');
    $this->excel->getActiveSheet()->setCellValue('Z1','Nomor BAST');
    $this->excel->getActiveSheet()->setCellValue('AA1','Tanggal BAST');
    $this->excel->getActiveSheet()->setCellValue('AB1','Tgl Bayar');
    $this->excel->getActiveSheet()->setCellValue('AC1','Jumlah Bayar');
    $this->excel->getActiveSheet()->setCellValue('AD1','Total Harga');
    $this->excel->getActiveSheet()->setCellValue('AE1','Status');
    $this->excel->getActiveSheet()->setCellValue('AF1','Logistik');
    $this->excel->getActiveSheet()->setCellValue('AG1','Nama Korwil');
    $this->excel->getActiveSheet()->setCellValue('AH1','Sales');
    $this->excel->getActiveSheet()->setCellValue('AI1','Is Offline');
    $this->excel->getActiveSheet()->setCellValue('AJ1','Realisasi');
    
	//body
	$rows = 2;
    $nomor = 1;
    $total_omset = 0;
    foreach($r_list_order as $row) 
    {
		$this->excel->getActiveSheet()->setCellValue('A'.$rows, $nomor);
		$this->excel->getActiveSheet()->setCellValue('B'.$rows, $row['kode_pesanan']);
		$this->excel->getActiveSheet()->setCellValue('C'.$rows, $row['p_tgl_pesan']);
		$this->excel->getActiveSheet()->setCellValue('D'.$rows, $row['npsn']);
		$this->excel->getActiveSheet()->setCellValue('E'.$rows, $row['nama_sekolah']);
		$this->excel->getActiveSheet()->setCellValue('F'.$rows, $row['alamat']);
		$this->excel->getActiveSheet()->setCellValue('G'.$rows, $row['kecamatan']);
		$this->excel->getActiveSheet()->setCellValue('H'.$rows, $row['kab_kota']);
		$this->excel->getActiveSheet()->setCellValue('I'.$rows, $row['prop']);
		$this->excel->getActiveSheet()->setCellValue('J'.$rows, $row['p_kode_buku']);
		$this->excel->getActiveSheet()->setCellValue('K'.$rows, $row['p_judul_buku']);
		$this->excel->getActiveSheet()->setCellValue('L'.$rows, $row['bentuk']);
		$this->excel->getActiveSheet()->setCellValue('M'.$rows, $row['kelas']);
		$this->excel->getActiveSheet()->setCellValue('N'.$rows, (int)$row['p_jml_buku']);
		$this->excel->getActiveSheet()->setCellValue('O'.$rows, (int)$row['p_harga_konfirm']);
		$this->excel->getActiveSheet()->setCellValue('P'.$rows, $row['zona']);
		$this->excel->getActiveSheet()->setCellValue('Q'.$rows, $row['kodepos']);
		$this->excel->getActiveSheet()->setCellValue('R'.$rows, (string)$row['phone']);
		$this->excel->getActiveSheet()->setCellValue('S'.$rows, $row['p_tanggal_konfirmasi']);
		$this->excel->getActiveSheet()->setCellValue('T'.$rows, $row['p_tanggal_logistik']);
		$this->excel->getActiveSheet()->setCellValue('U'.$rows, $row['p_waktu_pelaksanaan']);
		$this->excel->getActiveSheet()->setCellValue('V'.$rows, $row['k_tgl_kirim']);
		$this->excel->getActiveSheet()->setCellValue('W'.$rows, $row['s_tgl_sampai']);
		$this->excel->getActiveSheet()->setCellValue('X'.$rows, $row['s_nama_penerima']);
		$this->excel->getActiveSheet()->setCellValue('Y'.$rows, $row['t_tgl_terima']);
		$this->excel->getActiveSheet()->setCellValue('Z'.$rows, $row['t_nomor_surat']);
		$this->excel->getActiveSheet()->setCellValue('AA'.$rows, $row['t_tanggal_bast']);
		$this->excel->getActiveSheet()->setCellValue('AB'.$rows, $row['b_tgl_bayar']);
		$this->excel->getActiveSheet()->setCellValue('AC'.$rows, (int)$row['b_jml_bayar']);
		$this->excel->getActiveSheet()->setCellValue('AD'.$rows, (int)$row['p_total_harga']);
		$this->excel->getActiveSheet()->setCellValue('AE'.$rows, $row['status']);
		$this->excel->getActiveSheet()->setCellValue('AF'.$rows, $row['logistik']);
		$this->excel->getActiveSheet()->setCellValue('AG'.$rows, $row['korwil_name']);
		$this->excel->getActiveSheet()->setCellValue('AH'.$rows, $row['sales_referer']);
		$this->excel->getActiveSheet()->setCellValue('AI'.$rows, $row['is_offline']);
		$this->excel->getActiveSheet()->setCellValue('AJ'.$rows, $row['realisasi']);

        // $total_omset = $total_jumlah+$row['p_total_harga'];
        $nomor++;
		$rows++;
	}

    // $this->excel->getActiveSheet()->mergeCells('A'.$rows.':AC'.$rows);
    // $this->excel->getActiveSheet()->setCellValue('A'.$rows, 'Total Omset');
    // $this->excel->getActiveSheet()->setCellValue('AD'.$rows, $total_omset);
	
	$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>