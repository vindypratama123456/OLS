<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'third_party/PhpExportExcel.php';
require_once APPPATH . 'third_party/xlsxwriter.class.php';

/**
 * @property Mod_general $mod_general
 * @property Mod_report $mod_report
 */
class Report extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        $arrAdmin = array_merge($this->backoffice_admin_area, [3, 8]);
        if ( ! in_array($this->adm_level, $arrAdmin)) {
            redirect(ADMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->load->model('mod_report');
        $this->load->helper('download');
        $this->table = 'orders';
        $this->_output = [];
    }

    public function index()
    {
        $this->isAdmin();
        $listWilayah = (!in_array($this->adm_level, $this->backoffice_admin_area)) ? $this->mod_report->getWilayahKoordinator($this->session->userdata('adm_id')) : $this->mod_report->getListWilayah();
        $data['listwilayah'] = $listWilayah;
        $this->_output['content'] = $this->load->view('admin/report/list', $data, true);
        $this->_output['script_css'] = $this->load->view('admin/report/css', '', true);
        $this->_output['script_js'] = $this->load->view('admin/report/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    private function isAdmin()
    {
        if (!in_array($this->adm_level, $this->backoffice_superadmin_area)) {
            redirect(ADMIN_PATH);
        }
        return true;
    }

    public function kirimLogistikIP()
    {
        $this->isAdmin();
        $this->_output['content'] = $this->load->view('admin/report/list_logistik', '', true);
        $this->_output['script_css'] = $this->load->view('admin/report/css', '', true);
        $this->_output['script_js'] = $this->load->view('admin/report/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function search()
    {
        $this->isAdmin();
        try {
            ini_set('memory_limit', '-1');
            set_time_limit(0);
            $tglMulai = trim($this->input->post('tgl_mulai', true)) . ' 00:00:00';
            $tglAkhir = trim($this->input->post('tgl_akhir', true)) . ' 23:59:59';
            $tipe = $this->input->post('tipe');
            $kabupaten = $this->input->post('kabupaten') ? trim($this->input->post('kabupaten')) : false;
            $wilayah = ('all' != $kabupaten) ? $kabupaten : false;
            $getListOrder = [];
            $getTotalOmset = [];
            switch ($tipe) {
                case 1:
                    $getListOrder = $this->mod_report->getListReport(['perpage' => 10, 'offset' => 0], $tglMulai,
                        $tglAkhir, 'asc', $wilayah);
                    $getTotalOmset = $this->mod_report->getTotalSummary($tglMulai, $tglAkhir, $wilayah);
                    break;
                case 2:
                    $getListOrder = $this->mod_report->getListReport(['perpage' => 10, 'offset' => 0], $tglMulai,
                        $tglAkhir, 'asc', false, true);
                    $getTotalOmset = $this->mod_report->getTotalSummary($tglMulai, $tglAkhir, false, true);
                    break;
            }
            $data['listdata'] = $getListOrder;
            $data['total'] = $getTotalOmset[0];
            $data['tipe'] = $tipe;
            $data['tgl_mulai'] = substr($tglMulai, 0, 10);
            $data['tgl_akhir'] = substr($tglAkhir, 0, 10);
            $data['wilayah'] = $wilayah;
            $callBack = [
                'success' => 'true',
                'content' => $this->load->view('admin/report/ajax_append', $data, true),
	            'csrf_token' => $this->security->get_csrf_hash()
            ];
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage(),
	            'csrf_token' => $this->security->get_csrf_hash()
            ];
            echo json_encode($callBack, true);
        }
    }

    public function exportExcel($rTglMulai, $rTglAkhir, $rWilayah = false)
    {
        $this->isAdmin();
        try {
            // ini_set('memory_limit', '1024');
            // set_time_limit(0);
            $awal = strtotime($rTglMulai);
            $akhir = strtotime($rTglAkhir);
            $selisih = 1 + (date("Y", $akhir) - date("Y", $awal)) * 12;
            $selisih += date("m", $akhir) - date("m", $awal);
            if ($selisih > 3) {
                return false;
            } else {
                $tm = $rTglMulai;
                $ta = $rTglAkhir;
                $rWilayah = ! empty($rWilayah) ? $rWilayah : false;
                $wil = ! empty($rWilayah) ? '_' . strtolower(str_replace(' ', '_', $rWilayah)) : '';
                $filename = 'laporan_omset_' . $tm . '_' . $ta . $wil . '.xlsx';
                $folder = 'uploads/omset/';
                $startDate = $tm . ' 00:00:00';
                $finishDate = $ta . ' 23:59:59';
                $rListOrder = $this->mod_report->getListExcel($startDate, $finishDate, 'asc', $rWilayah);
                $header = [
                    'Kode Pesanan' => 'string',
                    'Tgl Pesan' => 'datetime',
                    'NPSN' => 'string',
                    'Nama Sekolah' => 'string',
                    'Alamat' => 'string',
                    'Kecamatan' => 'string',
                    'Kab/Kota' => 'string',
                    'Propinsi' => 'string',
                    'Kode Buku' => 'string',
                    'Judul Buku' => 'string',
                    'Jenjang' => 'string',
                    'Kelas' => 'string',
                    'Jumlah' => 'integer',
                    'Harga' => 'price',
                    'Zona' => 'integer',
                    'Kodepos' => 'string',
                    'Telpon' => 'string',
                    'Tgl Konfirmasi' => 'datetime',
                    'Tgl Logistik' => 'datetime',
                    'Waktu Pelaksanaan' => 'integer',
                    'Tgl Kirim' => 'date',
                    'Tgl Sampai' => 'date',
                    'Nama Penerima' => 'string',
                    'Tgl Terima' => 'date',
                    'Nomor BAST' => 'string',
                    'Tanggal BAST' => 'date',
                    'Tgl Bayar' => 'date',
                    'Jumlah Bayar' => 'price',
                    'Total Harga' => 'price',
                    'Status' => 'integer',
                    'Logistik' => 'integer',
                    'Nama Korwil' => 'string',
                    'Sales' => 'string',
                    'Is Offline' => 'integer',
                    'Realisasi' => 'integer'
                ];
                $writer = new XLSXWriter();
                $writer->writeSheetHeader('Sheet1', $header);
                foreach ($rListOrder as $row) {
                    $value = [
                        $row['kode_pesanan'],
                        $row['p_tgl_pesan'],
                        (int)$row['npsn'],
                        $row['nama_sekolah'],
                        $row['alamat'],
                        $row['kecamatan'],
                        $row['kab_kota'],
                        $row['prop'],
                        $row['p_kode_buku'],
                        $row['p_judul_buku'],
                        $row['bentuk'],
                        $row['kelas'],
                        (int)$row['p_jml_buku'],
                        (int)$row['p_harga_konfirm'],
                        $row['zona'],
                        $row['kodepos'],
                        $row['phone'],
                        $row['p_tanggal_konfirmasi'],
                        $row['p_tanggal_logistik'],
                        $row['p_waktu_pelaksanaan'],
                        $row['k_tgl_kirim'],
                        $row['s_tgl_sampai'],
                        $row['s_nama_penerima'],
                        $row['t_tgl_terima'],
                        $row['t_nomor_surat'],
                        $row['t_tanggal_bast'],
                        $row['b_tgl_bayar'],
                        (int)$row['b_jml_bayar'],
                        (int)$row['p_total_harga'],
                        (int)$row['status'],
                        (int)$row['logistik'],
                        $row['korwil_name'],
                        $row['sales_referer'],
                        (int)$row['is_offline'],
                        (int)$row['realisasi']
                    ];
                    $writer->writeSheetRow('Sheet1', $value);
                }
                $writer->writeToFile(FCPATH . $folder . $filename);
                chmod($folder . $filename, 0777);
                force_download($folder . $filename, null);
            }
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage()
            ];
            echo json_encode($callBack, true);
        }
    }

    public function exportExcelLogistik($rTglMulai, $rTglAkhir)
    {
        $this->isAdmin();
        try {
            $tm = $rTglMulai;
            $ta = $rTglAkhir;
            $filename = 'laporan_logistik_ip_' . $tm . '_' . $ta . '.xlsx';
            ini_set('memory_limit', '-1');
            set_time_limit(0);
            $style = (new StyleBuilder())->setShouldWrapText(false)->build();
            $writer = WriterFactory::create(Type::XLSX);
            $writer->setShouldCreateNewSheetsAutomatically(true);
            $writer->setShouldUseInlineStrings(true);
            $writer->openToBrowser($filename);
            $sheet = $writer->getCurrentSheet();
            $sheet->setName('IPLog_' . $tm . '_' . $ta);
            $startDate = $tm . ' 00:00:00';
            $finishDate = $ta . ' 23:59:59';
            $rTotal = $this->mod_report->getTotalReport($startDate, $finishDate, false);
            $rListOrder = $this->mod_report->getListReport(['perpage' => $rTotal, 'offset' => 0], $startDate,
                $finishDate, 'asc', false, true);
            $writer->addRowWithStyle([
                'Kode Pesanan',
                'Tgl Pesan',
                'NPSN',
                'Nama Sekolah',
                'Alamat',
                'Kecamatan',
                'Kab/Kota',
                'Propinsi',
                'Kode Buku',
                'Judul Buku',
                'Jenjang',
                'Kelas',
                'Jumlah',
                'Harga',
                'Zona',
                'Kodepos',
                'Telpon',
                'Tgl Konfirmasi',
                'Tgl Logistik',
                'Waktu Pelaksanaan',
                'Tgl Kirim',
                'Tgl Sampai',
                'Nama Penerima',
                'Tgl Terima',
                'Nomor BAST',
                'Tanggal BAST',
                'Tgl Bayar',
                'Jumlah Bayar',
                'Total Harga',
                'Status',
                'Logistik',
                'Nama Korwil',
                'Is Offline',
                'Realisasi'
            ], $style);
            // Loop through the result set
            foreach ($rListOrder as $row) {
                $writer->addRowWithStyle([
                    $row['kode_pesanan'],
                    $row['p_tgl_pesan'],
                    $row['npsn'],
                    $row['nama_sekolah'],
                    $row['alamat'],
                    $row['kecamatan'],
                    $row['kab_kota'],
                    $row['prop'],
                    $row['p_kode_buku'],
                    $row['p_judul_buku'],
                    $row['bentuk'],
                    $row['kelas'],
                    $row['p_jml_buku'],
                    $row['p_harga_konfirm'],
                    $row['zona'],
                    $row['kodepos'],
                    $row['phone'],
                    $row['p_tanggal_konfirmasi'],
                    $row['p_tanggal_logistik'],
                    $row['p_waktu_pelaksanaan'],
                    $row['k_tgl_kirim'],
                    $row['s_tgl_sampai'],
                    $row['s_nama_penerima'],
                    $row['t_tgl_terima'],
                    $row['t_nomor_surat'],
                    $row['t_tanggal_bast'],
                    $row['b_tgl_bayar'],
                    $row['b_jml_bayar'],
                    $row['p_total_harga'],
                    $row['status'],
                    $row['logistik'],
                    $row['korwil'],
                    $row['is_offline'],
                    $row['realisasi']
                ], $style);
            }
            $writer->close();
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage()
            ];
            echo json_encode($callBack, true);
        }
    }

    public function korwil()
    {
        $this->_output['content'] = $this->load->view('admin/report/list_korwil', '', true);
        $this->_output['script_css'] = $this->load->view('admin/report/css', '', true);
        $this->_output['script_js'] = $this->load->view('admin/report/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function searchKorwil()
    {
        try {
            $tglMulai = trim($this->input->post('tgl_mulai', true)) . ' 00:00:00';
            $tglAkhir = trim($this->input->post('tgl_akhir', true)) . ' 23:59:59';
            if ($this->adm_level == 3) {
                $korwil = $this->session->userdata('adm_uname');
                $getListOrder = $this->mod_report->getListReportKorwil(['perpage' => 10, 'offset' => 0], $tglMulai,
                    $tglAkhir, 'asc', $korwil);
                $getTotalOmset = $this->mod_report->getTotalSummaryKorwil($tglMulai, $tglAkhir, $korwil);
            } else {
                $getListOrder = $this->mod_report->getListReportKorwil(['perpage' => 10, 'offset' => 0], $tglMulai,
                    $tglAkhir, 'asc', false, $this->adm_id);
                $getTotalOmset = $this->mod_report->getTotalSummaryKorwil($tglMulai, $tglAkhir, false, $this->adm_id);
            }
            $data['listdata'] = $getListOrder;
            $data['total'] = $getTotalOmset[0];
            $data['tipe'] = 3;
            $data['tgl_mulai'] = substr($tglMulai, 0, 10);
            $data['tgl_akhir'] = substr($tglAkhir, 0, 10);
            $callBack = [
                'success' => 'true',
                'content' => $this->load->view('admin/report/ajax_append', $data, true)
            ];
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage()
            ];
            echo json_encode($callBack, true);
        }
    }

    public function exportExcelKorwil($rTglMulai, $rTglAkhir)
    {
        try {
            $tm = $rTglMulai;
            $ta = $rTglAkhir;
            $folder = 'uploads/omset/';
            $filename = 'laporan_omset_korwil_' . $tm . '_' . $ta . '.xlsx';
            ini_set('memory_limit', '2048M');
            set_time_limit(0);
            $startDate = $tm . ' 00:00:00';
            $finishDate = $ta . ' 23:59:59';
            if (8 == $this->adm_level) {
                $idEmployee = $this->adm_id;
                $rListOrder = $this->mod_report->getListExcel($startDate, $finishDate, 'asc', false, false, false,
                    $idEmployee);
            } else {
                $korwil = $this->session->userdata('adm_uname');
                $rListOrder = $this->mod_report->getListExcel($startDate, $finishDate, 'asc', false, false, $korwil);
            }
            $header = [
                'Kode Pesanan' => 'string',
                'Tgl Pesan' => 'datetime',
                'NPSN' => 'string',
                'Nama Sekolah' => 'string',
                'Alamat' => 'string',
                'Kecamatan' => 'string',
                'Kab/Kota' => 'string',
                'Propinsi' => 'string',
                'Kode Buku' => 'string',
                'Judul Buku' => 'string',
                'Jenjang' => 'string',
                'Kelas' => 'string',
                'Jumlah' => 'integer',
                'Harga' => 'price',
                'Zona' => 'integer',
                'Kodepos' => 'string',
                'Telpon' => 'string',
                'Tgl Konfirmasi' => 'datetime',
                'Tgl Logistik' => 'datetime',
                'Waktu Pelaksanaan' => 'integer',
                'Tgl Kirim' => 'date',
                'Tgl Sampai' => 'date',
                'Nama Penerima' => 'string',
                'Tgl Terima' => 'date',
                'Nomor BAST' => 'string',
                'Tanggal BAST' => 'date',
                'Tgl Bayar' => 'date',
                'Jumlah Bayar' => 'price',
                'Total Harga' => 'price',
                'Status' => 'integer',
                'Logistik' => 'integer',
                'Nama Korwil' => 'string',
                'Sales' => 'string',
                'Is Offline' => 'integer',
                'Realisasi' => 'integer',
            ];

            $writer = new XLSXWriter();
            $writer->writeSheetHeader('Sheet1', $header);
            foreach ($rListOrder as $row) {
                $value = [
                    $row['kode_pesanan'],
                    $row['p_tgl_pesan'],
                    (int)$row['npsn'],
                    $row['nama_sekolah'],
                    $row['alamat'],
                    $row['kecamatan'],
                    $row['kab_kota'],
                    $row['prop'],
                    $row['p_kode_buku'],
                    $row['p_judul_buku'],
                    $row['bentuk'],
                    $row['kelas'],
                    (int)$row['p_jml_buku'],
                    (int)$row['p_harga_konfirm'],
                    $row['zona'],
                    $row['kodepos'],
                    $row['phone'],
                    $row['p_tanggal_konfirmasi'],
                    $row['p_tanggal_logistik'],
                    $row['p_waktu_pelaksanaan'],
                    $row['k_tgl_kirim'],
                    $row['s_tgl_sampai'],
                    $row['s_nama_penerima'],
                    $row['t_tgl_terima'],
                    $row['t_nomor_surat'],
                    $row['t_tanggal_bast'],
                    $row['b_tgl_bayar'],
                    (int)$row['b_jml_bayar'],
                    (int)$row['p_total_harga'],
                    (int)$row['status'],
                    (int)$row['logistik'],
                    $row['korwil_name'],
                    $row['sales_referer'],
                    (int)$row['is_offline'],
                    (int)$row['realisasi']
                ];
                $writer->writeSheetRow('Sheet1', $value);
            }
            $writer->writeToFile(FCPATH . $folder . $filename);

            chmod($folder . $filename, 0777);
            force_download($folder . $filename, null);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage()
            ];
            echo json_encode($callBack, true);
        }
    }

    //////////////////////// UNTUK REKAPITULASI ////////////////////////
    public function rekapitulasiDataSekolah()
    {
        // ---------------- DAPODIK ----------------
        // 1) Semester 1 Periode awal transaksi s.d. 28 Februari 2017
        $rekapitulasiData['dapodik_1'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 1, $is_offline = 0,
            $date_end = "2017-02-28");
        // 2) Semester 1 Periode 1 Maret 2017 s.d. 26 Mei 2017
        $rekapitulasiData['dapodik_2'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 1, $is_offline = 0,
            $date_end = "2017-05-26", $date_start = "2017-03-01");
        // 3) Semester 2 Periode awal transaksi s.d. 26 Mei 2017
        $rekapitulasiData['dapodik_3'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 1, $is_offline = 0,
            $date_end = "2017-05-26");
        // ---------------- NON DAPODIK ----------------
        // 1) Semester 1 Periode awal transaksi s.d. 28 Februari 2017
        $rekapitulasiData['non_dapodik_1'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 1, $is_offline
            = 1, $date_end = "2017-02-28");
        // 2) Semester 1 Periode 1 Maret 2017 s.d. 26 Mei 2017
        $rekapitulasiData['non_dapodik_2'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 1, $is_offline
            = 1, $date_end = "2017-05-26", $date_start = "2017-03-01");
        // 3) Semester 2 Periode awal transaksi s.d. 26 Mei 2017
        $rekapitulasiData['non_dapodik_3'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 1, $is_offline
            = 1, $date_end = "2017-05-26");
        foreach ($rekapitulasiData as $dapodik => $values) {
            $data[$dapodik]['sd']['pesan'] = 0;
            $data[$dapodik]['sd']['kirim'] = 0;
            $data[$dapodik]['sd']['bayar'] = 0;
            $data[$dapodik]['smp']['pesan'] = 0;
            $data[$dapodik]['smp']['kirim'] = 0;
            $data[$dapodik]['smp']['bayar'] = 0;
            $data[$dapodik]['sma']['pesan'] = 0;
            $data[$dapodik]['sma']['kirim'] = 0;
            $data[$dapodik]['sma']['bayar'] = 0;
            $data[$dapodik]['smk']['pesan'] = 0;
            $data[$dapodik]['smk']['kirim'] = 0;
            $data[$dapodik]['smk']['bayar'] = 0;
            foreach ($values as $rows) {
                if ($rows->jenjang == "1-6") {
                    $data[$dapodik]['sd']['pesan'] += $rows->pesan;
                    $data[$dapodik]['sd']['kirim'] += $rows->kirim;
                    $data[$dapodik]['sd']['bayar'] += $rows->bayar;
                }
                if ($rows->jenjang == "7-9") {
                    $data[$dapodik]['smp']['pesan'] += $rows->pesan;
                    $data[$dapodik]['smp']['kirim'] += $rows->kirim;
                    $data[$dapodik]['smp']['bayar'] += $rows->bayar;
                }
                if ($rows->jenjang == "10-12" && $rows->bentuk != "SMK") {
                    $data[$dapodik]['sma']['pesan'] += $rows->pesan;
                    $data[$dapodik]['sma']['kirim'] += $rows->kirim;
                    $data[$dapodik]['sma']['bayar'] += $rows->bayar;
                }
                if ($rows->jenjang == "10-12" && $rows->bentuk == "SMK") {
                    $data[$dapodik]['smk']['pesan'] += $rows->pesan;
                    $data[$dapodik]['smk']['kirim'] += $rows->kirim;
                    $data[$dapodik]['smk']['bayar'] += $rows->bayar;
                }
            }
        }
        $data['page_title'] = 'Laporan - Rekapitulasi Data Jumlah Sekolah';
        $data['content'] = $this->load->view('admin/report/rekapitulasi_sekolah', $data, true);
        $this->load->view('admin/template', $data);
        return false;
    }

    public function rekapitulasiDataBuku()
    {
        // ---------------- DAPODIK ----------------
        // 1) Semester 1 Periode awal transaksi s.d. 28 Februari 2017
        $rekapitulasiData['dapodik_1'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 0, $is_offline = 0,
            $date_end = "2017-02-28");
        // 2) Semester 1 Periode 1 Maret 2017 s.d. 26 Mei 2017
        $rekapitulasiData['dapodik_2'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 0, $is_offline = 0,
            $date_end = "2017-05-26", $date_start = "2017-03-01");
        // 3) Semester 2 Periode awal transaksi s.d. 26 Mei 2017
        $rekapitulasiData['dapodik_3'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 0, $is_offline = 0,
            $date_end = "2017-05-26");
        // ---------------- NON DAPODIK ----------------
        // 1) Semester 1 Periode awal transaksi s.d. 28 Februari 2017
        $rekapitulasiData['non_dapodik_1'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 0, $is_offline
            = 1, $date_end = "2017-02-28");
        // 2) Semester 1 Periode 1 Maret 2017 s.d. 26 Mei 2017
        $rekapitulasiData['non_dapodik_2'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 0, $is_offline
            = 1, $date_end = "2017-05-26", $date_start = "2017-03-01");
        // 3) Semester 2 Periode awal transaksi s.d. 26 Mei 2017
        $rekapitulasiData['non_dapodik_3'] = $this->mod_report->getRekapitulasiDataSekolah($rekapitulasi = 0, $is_offline
            = 1, $date_end = "2017-05-26");
        foreach ($rekapitulasiData as $dapodik => $value) {
            $data[$dapodik]['sd']['pesan_buku'] = 0;
            $data[$dapodik]['sd']['pesan_harga'] = 0;
            $data[$dapodik]['sd']['kirim_buku'] = 0;
            $data[$dapodik]['sd']['kirim_harga'] = 0;
            $data[$dapodik]['sd']['bast'] = 0;
            $data[$dapodik]['sd']['bayar_tagihan'] = 0;
            $data[$dapodik]['sd']['bayar_terbayar'] = 0;
            $data[$dapodik]['sd']['bayar_sisa'] = 0;
            $data[$dapodik]['smp']['pesan_buku'] = 0;
            $data[$dapodik]['smp']['pesan_harga'] = 0;
            $data[$dapodik]['smp']['kirim_buku'] = 0;
            $data[$dapodik]['smp']['kirim_harga'] = 0;
            $data[$dapodik]['smp']['bast'] = 0;
            $data[$dapodik]['smp']['bayar_tagihan'] = 0;
            $data[$dapodik]['smp']['bayar_terbayar'] = 0;
            $data[$dapodik]['smp']['bayar_sisa'] = 0;
            $data[$dapodik]['sma']['pesan_buku'] = 0;
            $data[$dapodik]['sma']['pesan_harga'] = 0;
            $data[$dapodik]['sma']['kirim_buku'] = 0;
            $data[$dapodik]['sma']['kirim_harga'] = 0;
            $data[$dapodik]['sma']['bast'] = 0;
            $data[$dapodik]['sma']['bayar_tagihan'] = 0;
            $data[$dapodik]['sma']['bayar_terbayar'] = 0;
            $data[$dapodik]['sma']['bayar_sisa'] = 0;
            $data[$dapodik]['smk']['pesan_buku'] = 0;
            $data[$dapodik]['smk']['pesan_harga'] = 0;
            $data[$dapodik]['smk']['kirim_buku'] = 0;
            $data[$dapodik]['smk']['kirim_harga'] = 0;
            $data[$dapodik]['smk']['bast'] = 0;
            $data[$dapodik]['smk']['bayar_tagihan'] = 0;
            $data[$dapodik]['smk']['bayar_terbayar'] = 0;
            $data[$dapodik]['smk']['bayar_sisa'] = 0;
            foreach ($value as $row) {
                if ($row->jenjang == "1-6") {
                    $data[$dapodik]['sd']['pesan_buku'] += $row->pesan_buku;
                    $data[$dapodik]['sd']['pesan_harga'] += $row->pesan_harga;
                    $data[$dapodik]['sd']['kirim_buku'] += $row->kirim_buku;
                    $data[$dapodik]['sd']['kirim_harga'] += $row->kirim_harga;
                    $data[$dapodik]['sd']['bast'] += $row->bast;
                    $data[$dapodik]['sd']['bayar_tagihan'] += $row->bayar_tagihan;
                    $data[$dapodik]['sd']['bayar_terbayar'] += $row->bayar_terbayar;
                    $data[$dapodik]['sd']['bayar_sisa'] += $row->bayar_tagihan - $row->bayar_terbayar;
                }
                if ($row->jenjang == "7-9") {
                    $data[$dapodik]['smp']['pesan_buku'] += $row->pesan_buku;
                    $data[$dapodik]['smp']['pesan_harga'] += $row->pesan_harga;
                    $data[$dapodik]['smp']['kirim_buku'] += $row->kirim_buku;
                    $data[$dapodik]['smp']['kirim_harga'] += $row->kirim_harga;
                    $data[$dapodik]['smp']['bast'] += $row->bast;
                    $data[$dapodik]['smp']['bayar_tagihan'] += $row->bayar_tagihan;
                    $data[$dapodik]['smp']['bayar_terbayar'] += $row->bayar_terbayar;
                    $data[$dapodik]['smp']['bayar_sisa'] += $row->bayar_tagihan - $row->bayar_terbayar;
                }
                if ($row->jenjang == "10-12" && $row->bentuk != "SMK") {
                    $data[$dapodik]['sma']['pesan_buku'] += $row->pesan_buku;
                    $data[$dapodik]['sma']['pesan_harga'] += $row->pesan_harga;
                    $data[$dapodik]['sma']['kirim_buku'] += $row->kirim_buku;
                    $data[$dapodik]['sma']['kirim_harga'] += $row->kirim_harga;
                    $data[$dapodik]['sma']['bast'] += $row->bast;
                    $data[$dapodik]['sma']['bayar_tagihan'] += $row->bayar_tagihan;
                    $data[$dapodik]['sma']['bayar_terbayar'] += $row->bayar_terbayar;
                    $data[$dapodik]['sma']['bayar_sisa'] += $row->bayar_tagihan - $row->bayar_terbayar;
                }
                if ($row->jenjang == "10-12" && $row->bentuk == "SMK") {
                    $data[$dapodik]['smk']['pesan_buku'] += $row->pesan_buku;
                    $data[$dapodik]['smk']['pesan_harga'] += $row->pesan_harga;
                    $data[$dapodik]['smk']['kirim_buku'] += $row->kirim_buku;
                    $data[$dapodik]['smk']['kirim_harga'] += $row->kirim_harga;
                    $data[$dapodik]['smk']['bast'] += $row->bast;
                    $data[$dapodik]['smk']['bayar_tagihan'] += $row->bayar_tagihan;
                    $data[$dapodik]['smk']['bayar_terbayar'] += $row->bayar_terbayar;
                    $data[$dapodik]['smk']['bayar_sisa'] += $row->bayar_tagihan - $row->bayar_terbayar;
                }
            }
        }
        $data['page_title'] = 'Laporan - Rekapitulasi Jumlah Oplah Buku';
        $data['content'] = $this->load->view('admin/report/rekapitulasi_buku', $data, true);
        $this->load->view('admin/template', $data);
        return false;
    }
}
