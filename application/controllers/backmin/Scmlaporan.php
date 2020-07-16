<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/Spout/Autoloader/autoload.php';

require_once APPPATH.'third_party/PhpExportExcel.php';
require_once APPPATH.'third_party/xlsxwriter.class.php';

/**
 * @property Mod_scm $mod_scm
 * @property Mod_general $mod_general
 */
class Scmlaporan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, $this->backmin_scm_area)) {
            redirect(BACKMIN_PATH);
        }
        $this->load->model('mod_scm');
        $this->load->model('mod_general');
    }

    public function index()
    {
        $this->indexStok();
    }

    public function indexStok()
    {
        $where                  = "";
        $where_konfirmasi       = "";
        $term                   = "";
        
        if ($this->input->post('search_input', true)) {
            $term               = $this->input->post('search_input', true);
            $where              = "b.name LIKE '%" . $this->input->post('search_input') . "%' OR b.kode_buku LIKE '%" . $this->input->post('search_input') . "%' OR c.name LIKE '%" . $this->input->post('search_input') . "%'";
            $where_konfirmasi   = "judul_buku LIKE '%" . $this->input->post('search_input') . "%' OR kode_buku LIKE '%" . $this->input->post('search_input') . "%' OR kelas LIKE '%" . $this->input->post('search_input') . "%'";
        }
        $selectDiambilIP        = "
            (SELECT sum(d.jumlah) FROM transaksi_detail d inner join transaksi e on d.id_transaksi = e.id_transaksi WHERE e.tujuan = 98 and e.asal = 1 and d.id_produk = a.id_produk) as gudang_medan,
            (SELECT sum(d.jumlah) FROM transaksi_detail d inner join transaksi e on d.id_transaksi = e.id_transaksi WHERE e.tujuan = 98 and e.asal = 6 and d.id_produk = a.id_produk) as gudang_palmerah,
            (SELECT sum(d.jumlah) FROM transaksi_detail d inner join transaksi e on d.id_transaksi = e.id_transaksi WHERE e.tujuan = 98 and e.asal = 8 and d.id_produk = a.id_produk) as gudang_bawen,
            (SELECT sum(d.jumlah) FROM transaksi_detail d inner join transaksi e on d.id_transaksi = e.id_transaksi WHERE e.tujuan = 98 and e.asal = 11 and d.id_produk = a.id_produk) as gudang_bandung,
            (SELECT sum(d.jumlah) FROM transaksi_detail d inner join transaksi e on d.id_transaksi = e.id_transaksi WHERE e.tujuan = 98 and e.asal = 14 and d.id_produk = a.id_produk) as gudang_cikarang,
            (SELECT sum(d.jumlah) FROM transaksi_detail d inner join transaksi e on d.id_transaksi = e.id_transaksi WHERE e.tujuan = 98 and e.asal = 17 and d.id_produk = a.id_produk) as gudang_surabaya,
            (SELECT sum(d.jumlah) FROM transaksi_detail d inner join transaksi e on d.id_transaksi = e.id_transaksi WHERE e.tujuan = 98 and e.asal = 19 and d.id_produk = a.id_produk) as gudang_gianyar
        ";
        $selectKirim            = "
            (
                select sum(x.jumlah)
                from transaksi_detail x
                inner join transaksi w on x.id_transaksi = w.id_transaksi
                where x.id_produk = a.id_produk and w.status_transaksi = 5 and w.asal = 1 and w.is_forward = 0
            ) as gudang_medan,
            (
                select sum(x.jumlah)
                from transaksi_detail x
                inner join transaksi w on x.id_transaksi = w.id_transaksi
                where x.id_produk = a.id_produk and w.status_transaksi = 5 and w.asal = 6 and w.is_forward = 0
            ) as gudang_palmerah,
            (
                select sum(x.jumlah)
                from transaksi_detail x
                inner join transaksi w on x.id_transaksi = w.id_transaksi
                where x.id_produk = a.id_produk and w.status_transaksi = 5 and w.asal = 8 and w.is_forward = 0
            ) as gudang_bawen,
            (
                select sum(x.jumlah)
                from transaksi_detail x
                inner join transaksi w on x.id_transaksi = w.id_transaksi
                where x.id_produk = a.id_produk and w.status_transaksi = 5 and w.asal = 11 and w.is_forward = 0
            ) as gudang_bandung,
            (
                select sum(x.jumlah)
                from transaksi_detail x
                inner join transaksi w on x.id_transaksi = w.id_transaksi
                where x.id_produk = a.id_produk and w.status_transaksi = 5 and w.asal = 14 and w.is_forward = 0
            ) as gudang_cikarang,
            (
                select sum(x.jumlah)
                from transaksi_detail x
                inner join transaksi w on x.id_transaksi = w.id_transaksi
                where x.id_produk = a.id_produk and w.status_transaksi = 5 and w.asal = 17 and w.is_forward = 0
            ) as gudang_surabaya,
            (
                select sum(x.jumlah)
                from transaksi_detail x
                inner join transaksi w on x.id_transaksi = w.id_transaksi
                where x.id_produk = a.id_produk and w.status_transaksi = 5 and w.asal = 19 and w.is_forward = 0
            ) as gudang_gianyar
        ";
        
        $data['term']                   = $term;
        $data['liststok_fisik']         = $this->mod_scm->getStockGudangByStockType('stok_fisik', $where);
        $data['liststok_booking']       = $this->mod_scm->getStockGudangByStockType('stok_booking', $where);
        $data['liststok_available']     = $this->mod_scm->getStockGudangByStockType('stok_available', $where);
        $data['liststok_diambil_ip']    = $this->mod_scm->getStockGudangAll($selectDiambilIP, $where);
        $data['liststok_kirim']         = $this->mod_scm->getStockGudangAll($selectKirim, $where);
        $data['liststok_belum_kirim']   = $this->mod_scm->getAll("report_stock_konfirmasi_gudang", "*", $where_konfirmasi, "id asc");

        $data['page_title']             = 'Laporan - Stok Buku';
        $data['content']                = $this->load->view(BACKMIN_PATH . '/scm/laporan/list', $data, true);
        $data['script_js']              = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_js', '', true);

        $this->load->view(BACKMIN_PATH . '/main', $data);
        return false;
    }

    public function indexSupplyChain()
    {
        $where              = "";
        $term               = "";
        $termGudang         = "";
        $select             = "*";

        if (!$this->input->post('search_gudang', true)) {
            $select         = "id_gudang, id_produk, judul_buku, kode_buku, kelas, SUM(stok_fisik) as stok_fisik, SUM(stok_ip) as stok_ip, SUM(stok_kirim) as stok_kirim, SUM(total_produksi) as total_produksi, SUM(stok_konfirmasi) as stok_konfirmasi, SUM(stok_booking) as stok_booking, SUM(stok_belum_kirim) as stok_belum_kirim, SUM(total_pesanan) as total_pesanan, SUM(stok_available) as stok_available";
        }

        if ($this->input->post('search_input', true)) {
            $term           = $this->input->post('search_input', true);
            $where          .= " AND judul_buku LIKE '%" . $this->input->post('search_input') . "%' OR kode_buku LIKE '%" . $this->input->post('search_input') . "%' OR kelas LIKE '%" . $this->input->post('search_input') . "%'";
        }

        if ( ! empty($this->input->post('search_gudang'))) {
            $termGudang     = $this->input->post('search_gudang', true);
            $where          .= " AND id_gudang = " . $this->input->post('search_gudang');
        }

        if ($where != null) {
            $where          = substr($where, 5);
        }

        $data['term']                       = $term;
        $data['term_gudang']                = $termGudang;
        $data['liststok_supplychain']       = $this->mod_scm->getAll("report_stock", $select, $where, "id asc", "id_produk");
        $data['select_query']               = $select;
        $data['where_query']                = $where;

        $gudang                             = $this->mod_scm->getAll("report_stock", "id_gudang", "", "id_gudang asc", "id_gudang");
        $idGudang                           = [];
        foreach ($gudang as $key => $value) {
            array_push($idGudang, $value->id_gudang);
        }

        $idGudang               = implode(",", $idGudang);
        $data['listgudang']     = $this->mod_scm->getAll("master_gudang", "id_gudang, nama_gudang", "id_gudang in (" . $idGudang . ") and status = 1", "nama_gudang asc");

        $data['page_title']     = 'Laporan - Supply Chain';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_supplychain', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_supplychain_js', '', true);

        $this->load->view(BACKMIN_PATH . '/main', $data);
        return false;
    }

    public function exportExcelSupplyChain()
    {
        $this->load->library('excel');

        $select                         = $this->input->post('select_query');
        $where                          = $this->input->post('where_query');
        $data['liststok_supplychain']   = $this->mod_scm->getAll("report_stock", $select, $where, "id asc", "id_produk");

        $this->load->view(BACKMIN_PATH . '/scm/laporan/cetak_list_supplychain', $data);
    }

    public function indexStokWarehouse()
    {
        $idGudang               = "";
        $where                  = "";
        $term                   = "";
        $termGudang             = "";
        
        $data['listgudang']     = $this->mod_scm->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');

        if ($this->input->post('search_gudang', true)) {
            $termGudang         = $this->input->post('search_gudang', true);
            $idGudang           = $this->input->post('search_gudang', true);
        } else {
            $termGudang         = "";
            $prefix = $idGudang = "";
            foreach ($data['listgudang'] as $datas) 
            {
                $idGudang       .= $prefix.$datas->id_gudang;
                $prefix         = ',';
            }
        }

        if ($this->input->post('search_input', true)) {
            $term               = $this->input->post('search_input', true);
            $where              = "(b.name LIKE '%" . $this->input->post('search_input') . "%' OR b.kode_buku LIKE '%" . $this->input->post('search_input') . "%' OR c.name LIKE '%" . $this->input->post('search_input') . "%' OR d.name LIKE '%" . $this->input->post('search_input') . "%')";
        }

        $data['term']           = $term;
        $data['term_gudang']    = $termGudang;
        $list_stok              = $this->mod_scm->listInfoStok($idGudang, $where);
        
        $data['list_stok']      = [];
        $count                  = [];
        foreach ($list_stok as $datas) {
            $count[$datas->category] = 0;
        }
        foreach ($list_stok as $row) {
            $data['list_stok'][$row->parent_category_name][$row->category_name][$count[$row->category]] = $row;
            $count[$row->category]++;
        }

        $data['page_title']     = 'Stok Barang';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_stok', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_stok_js', '', true);
        
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }


    public function index_report_transaction()
    {
        $data['listgudang'] = $this->mod_general->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');

        $data['page_title'] = 'Laporan Transaksi';
        $data['content'] = $this->load->view('backmin/gudang/laporan/report_transaction', $data, true);
        $data['script_js'] = $this->load->view('backmin/gudang/laporan/report_transaction_js', '', true);
        $data['script_css'] = $this->load->view('backmin/gudang/laporan/report_transaction_css', '', true);

        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function report_transaction()
    {
        try {
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            // $id_gudang = (int)$this->input->post('id_gudang') ?: null;

            // $report_receiving = $this->report_transaction_list($start_date, $end_date, $id_gudang);
            $report_receiving = $this->report_transaction_list($start_date, $end_date);

            $response = array(
                'success' => 'true',
                'data' => $report_receiving,
                // 'pagination' => $links
            );
            echo json_encode($response);

        } catch (Exception $e) {
            $callBack = array(
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            );
            echo json_encode($callBack, true);
        }
    }

    // public function report_transaction_list($start_date, $end_date, $id_gudang = null)
    public function report_transaction_list($start_date, $end_date)
    {
        try {
            // $report_receiving = $this->mod_scm->get_report_transaction($start_date, $end_date, $id_gudang);
            $report_receiving = $this->mod_scm->get_report_transaction($start_date, $end_date);

            // echo $this->db->last_query();
            // print_r($report_receiving);

            // exit();
            $report = array();
            $count = 0;
            $id_periode = 0;
            $id_gudang = 0;
            $id_bulan = 0;
            foreach ($report_receiving as $row => $data) {
                // if ($id_periode != $data['id_periode']) {
                //     $id_periode = $data['id_periode'];
                //     $id_gudang = $data['id_gudang'];
                //     $id_bulan = $data['bln_transaksi'];
                //     $count = 0;
                // } elseif ($id_gudang != $data['id_gudang']) {
                //     $id_gudang = $data['id_gudang'];
                //     $id_bulan = $data['bln_transaksi'];
                //     $count = 0;
                // } elseif ($id_bulan != $data['bln_transaksi']) {
                //     $id_bulan = $data['bln_transaksi'];
                //     $count = 0;
                // }

                // $total_cost = $data['jumlah_buku'] * $data['unit_cost'];

                // $report[$id_periode]['nama_periode'] = $data['nama_periode'];
                // $report[$id_periode]['row1'][$id_gudang]['nama_gudang'] = $data['nama_gudang'];
                // $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['nama_bulan'] = bulanIndo($data['bln_transaksi']);
                // $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['kode_buku'] = $data['kode_buku'];
                // $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['judul_buku'] = $data['judul_buku'];
                // $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['jumlah_buku'] = $data['jumlah_buku'];
                // $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['unit_cost'] = $data['unit_cost'];
                // $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['total_cost'] = $total_cost;
                // $count++;

                $report[$count]['kode_transaksi'] = $data['kode_transaksi'];
                $report[$count]['tgl_transaksi'] = $data['tgl_transaksi'];
                $report[$count]['status_transaksi'] = $data['status_transaksi'];
                $report[$count]['keterangan'] = $data['keterangan'];
                $report[$count]['asal'] = $data['asal'];
                $report[$count]['tujuan'] = $data['tujuan'];
                $report[$count]['kode_buku'] = $data['kode_buku'];
                $report[$count]['qty'] = $data['qty'];
                $count++;
            }

            return $report;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function printTransaksiExcel($start_date, $end_date)
    {
        try {
            if ($start_date && $end_date) {
                // $id_gudang = $id_gudang ? (int)$id_gudang : null;
                $show_start_date = date('d/m/Y', strtotime($start_date));
                $show_end_date = date('d/m/Y', strtotime($end_date));
                $report_transaksi = $this->report_transaction_list($start_date, $end_date);


                $this->load->library('excel');
                $worksheet = $this->excel->getActiveSheet();

                $this->excel->setActiveSheetIndex(0);
                $worksheet->setTitle('Laporan Transaksi');

                $worksheet->setCellValue('B2', 'Laporan Transaksi')->mergeCells('B2:I2')->setCellValue('B3',
                        'TANGGAL TRANSAKSI : '.$show_start_date.' s/d '.$show_end_date)->mergeCells('B3:I3');

                $worksheet->setCellValue('B5', 'KODE TRANSAKSI')->setCellValue('C5', 'TANGGAL TRANSAKSI')->setCellValue('D5',
                        'STATUS TRANSAKSI')->setCellValue('E5', 'KETERANGAN')->setCellValue('F5',
                        'ASAL')->setCellValue('G5', 'TUTJUAN')->setCellValue('H5', 'KODE BUKU')->setCellValue('I5',
                        'QUANTITY');

                $worksheet->getStyle('B2:I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $worksheet->getStyle('B2')->getFont()->setBold(true);

                $worksheet->getStyle('B5:I5')->getFont()->setBold(true);

                $grand_total_qty = 0;
                $grand_total = 0;
                $row_head1 = 6;

                foreach($report_transaksi as $data)
                {
                    $worksheet->setCellValue('B'.$row_head1, $data['kode_transaksi']);
                    $worksheet->setCellValue('C'.$row_head1, $data['tgl_transaksi']);
                    $worksheet->setCellValue('D'.$row_head1, $data['status_transaksi']);
                    $worksheet->setCellValue('E'.$row_head1, $data['keterangan']);
                    $worksheet->setCellValue('F'.$row_head1, $data['asal']);
                    $worksheet->setCellValue('G'.$row_head1, $data['tujuan']);
                    $worksheet->setCellValue('H'.$row_head1, $data['kode_buku']);
                    $worksheet->setCellValue('I'.$row_head1, $data['qty']);
                    $row_head1++;
                }

                // foreach ($report_receiving as $row => $periode) {
                //     $worksheet->setCellValue('B'.$row_head1,
                //             'PERIODE : '.$periode['nama_periode'])->mergeCells('B'.$row_head1.':I'.$row_head1)->getStyle('B'.$row_head1)->getFont()->setBold(true);


                //     $row_head2 = $row_head1 + 1;
                //     foreach ($periode['row1'] as $row1 => $gudang) {
                //         $worksheet->setCellValue('B'.$row_head2,
                //                 'LOKASI : '.$row1.' - '.strtoupper(substr($gudang['nama_gudang'],
                //                     7)))->mergeCells('B'.$row_head2.':I'.$row_head2)->getStyle('B'.$row_head2)->getFont()->setBold(true);

                //         $row_head3 = $row_head2 + 1;
                //         $sub_total_qty = 0;
                //         $sub_total = 0;
                //         foreach ($gudang['row2'] as $row2 => $bulan) {
                //             $worksheet->setCellValue('B'.$row_head3,
                //                     'BULAN : '.$bulan['nama_bulan'])->mergeCells('B'.$row_head3.':I'.$row_head3)->getStyle('B'.$row_head3)->getFont()->setBold(true);

                //             $row_detail = $row_head3 + 1;
                //             foreach ($bulan['row3'] as $row3 => $detail) {
                //                 $worksheet->setCellValue('B'.$row_detail,
                //                         $detail['kode_buku'])->setCellValue('C'.$row_detail,
                //                         strtoupper($detail['judul_buku']))->setCellValue('D'.$row_detail,
                //                         number_format($detail['jumlah_buku']), 0)->setCellValue('E'.$row_detail,
                //                         number_format($detail['unit_cost'], 2))->setCellValue('F'.$row_detail,
                //                         number_format($detail['total_cost'], 2))->setCellValue('G'.$row_detail,
                //                         '0')->setCellValue('H'.$row_detail, '0')->setCellValue('I'.$row_detail,
                //                         number_format($detail['total_cost'], 2));

                //                 $worksheet->getStyle('D'.$row_detail.':I'.$row_detail)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                //                 $row_detail++;

                //                 $sub_total_qty += $detail['jumlah_buku'];
                //                 $sub_total += $detail['total_cost'];
                //                 $grand_total_qty += $detail['jumlah_buku'];
                //                 $grand_total += $detail['total_cost'];
                //             }

                //             $row_head3 = $row_detail;
                //         }
                //         $row_head2 = $row_head3;

                //         $worksheet->setCellValue('B'.$row_head2,
                //                 'Sub Total')->mergeCells('B'.$row_head2.':C'.$row_head2);

                //         $worksheet->setCellValue('D'.$row_head2,
                //                 number_format($sub_total_qty, 0))->setCellValue('E'.$row_head2,
                //                 '-')->setCellValue('F'.$row_head2,
                //                 number_format($sub_total, 2))->setCellValue('G'.$row_head2,
                //                 '0')->setCellValue('H'.$row_head2, '0')->setCellValue('I'.$row_head2,
                //                 number_format($sub_total, 2));

                //         $worksheet->getStyle('B'.$row_head2.':I'.$row_head2)->getFont()->setBold(true);

                //         $worksheet->getStyle('B'.$row_head2.':I'.$row_head2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                //         ++$row_head2;
                //     }
                //     $row_head1 = $row_head2;
                // }
                // 
                

                // $worksheet->setCellValue('B'.$row_head1, 'Grand Total')->mergeCells('B'.$row_head1.':C'.$row_head1);

                // $worksheet->setCellValue('D'.$row_head1,
                //         number_format($grand_total_qty, 0))->setCellValue('E'.$row_head1,
                //         '0')->setCellValue('F'.$row_head1, number_format($grand_total, 2))->setCellValue('G'.$row_head1,
                //         '0')->setCellValue('H'.$row_head1, '0')->setCellValue('I'.$row_head1,
                //         number_format($grand_total, 2));

                // $worksheet->getStyle('B'.$row_head1.':I'.$row_head1)->getFont()->setBold(true);

                // $worksheet->getStyle('B'.$row_head1.':I'.$row_head1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                // foreach (range('B', 'I') as $columnID) {
                //     $worksheet->getColumnDimension($columnID)->setAutoSize(true);
                // }

                $filename = 'laporan_transaksi_'.$start_date.'_'.$end_date.'.xls';
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0'); //no cache
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                $objWriter->save('php://output');
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function testing()
    {
        $start_date = "2020-01-01";
        $end_date = "2020-01-30";
        $report_transaksi = $this->report_transaction_list($start_date, $end_date);
                foreach($report_transaksi as $data)
                {
                    print_r($data);
                }
    }
}
