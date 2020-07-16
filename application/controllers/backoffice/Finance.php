<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH.'third_party/PhpExportExcel.php';
require_once APPPATH.'third_party/xlsxwriter.class.php';

/**
 * @property Datatables $datatables
 * @property Excel $excel
 * @property Mod_finance $mod_finance
 * @property Mod_general $mod_general
 * @property Mod_order $mod_order
 * @property Mymail $mymail
 */
class Finance extends MY_Controller
{
    private $table;
    private $_output;
    private $controller_name;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->load->model('mod_finance');
        $this->load->model('mod_order');
        $this->table = 'orders';
        $this->controller_name = $this->uri->segment(2);
        $this->_output = [];
        $this->load->helper('download');
    }

    public function index()
    {
        if (in_array($this->adm_level, [3, 4, 8])) {
            redirect(ADMIN_PATH.'/finance/allOrder', 'refresh');
        } else {
            $data['page_title'] = 'Pesanan Sekolah (Belum Lunas) | '.date('Y-m-d_His');
            $data['nilai_pesanan'] = $this->mod_finance->totalRupiah(1);
            $data['nilai_diinput'] = $this->mod_finance->totalRupiah(4);
            $data['nilai_piutang'] = $data['nilai_pesanan'] - $data['nilai_diinput'];
            $data['nilai_lunas'] = $this->mod_finance->totalRupiah(2);
            $data['nilai_diangsur'] = $this->mod_finance->totalRupiah(3);
            $this->_output['content'] = $this->load->view('admin/finance/list', $data, true);
        }
        $this->_output['script_js'] = $this->load->view('admin/finance/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_orders()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $startDate = $this->input->post('start_date') ?? '2016-01-01';
        $endDate = $this->input->post('end_date') ?? date('Y-m-d');
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_order AS id_order, 
                                   a.reference AS reference, 
                                   b.school_name AS school_name, 
                                   a.category AS category, 
                                   a.type AS type, 
                                   a.date_add AS date_add, 
                                   c.name AS order_state, 
                                   c.label AS label, 
                                   a.korwil_name AS korwil_name,
                                   a.total_paid AS total_paid, 
                                   a.nilai_dibayar AS nilai_dibayar, 
                                   (a.total_paid - a.nilai_dibayar) AS nilai_piutang, 
                                   b.phone AS phone, 
                                   b.operator AS operator, 
                                   b.hp_operator AS hp_operator, 
                                   b.name AS name, 
                                   b.phone_kepsek AS phone_kepsek,
                                   a.sales_name as nama_mitra,
                                   a.rsm_name as nama_rsm,
                                   a.konfirmasi_hasil as hasil_konfirmasi,
                                   a.konfirmasi_tanggal as tanggal_konfirmasi');

        $this->datatables->from('orders a');
        $this->datatables->join('customer b', 'b.id_customer=a.id_customer', 'inner');
        $this->datatables->join('order_state c', 'c.id_order_state=a.current_state', 'inner');
        $this->datatables->where('a.current_state NOT IN (1, 2, 4, 9)');
        $this->datatables->where('a.sts_bayar !=', 2);
        $this->datatables->where('a.date_add BETWEEN \''.$startDate.' 00:00:00\' AND \''.$endDate.' 23:59:59\'');
        if ($this->adm_level == 4) {
            $this->datatables->where('a.sales_referer = (select aa.email from employee aa where aa.id_employee = '.$this->adm_id.')');
        } elseif (in_array($this->adm_level, [3, 8])) {
            $this->datatables->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        $this->datatables->edit_column('order_state', '<span class="label $1">$2</span>', 'label, order_state');
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/finance/detail/$1').'">$2</a>',
            'id_order, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($id)
    {
        $data['detil'] = $this->mod_general->detailData($this->table, 'id_order', $id);
        $data['customer'] = $this->mod_general->detailData('customer', 'id_customer', $data['detil']['id_customer']);
        $data['listproducts'] = $this->mod_general->getWhere('order_detail', 'id_order', $id, 'id_order_detail', 'asc');
        $data['liststatus'] = $this->mod_order->getListStatus($id);
        $data['listlog'] = $this->mod_finance->getListLog($id);
        $data['listpay'] = $this->mod_finance->getListPay($id);
        $data['adm_level'] = $this->adm_level;
        $this->_output['content'] = $this->load->view('admin/finance/detail', $data, true);
        $this->_output['script_css'] = $this->load->view('admin/finance/css', '', true);
        $this->_output['script_js'] = $this->load->view('admin/finance/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function addLog($id)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $data['detil'] = $this->mod_general->detailData('orders', 'id_order', $id);
        $this->load->view('admin/finance/log_popup', $data);
    }

    # TODO : add logs and block permission for auditor
    public function logPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $call_back = [
                    "success" => "false",
                    "message" => "Maaf, anda tidak dapat melakukan proses ini.",
                ];
            } else {
                $id_order = $this->input->post('id_order');
                $reference = $this->input->post('reference');
                // insert tabel finance_logs
                $data = [
                    'id_order' => $id_order,
                    'notes' => $this->input->post('notes', true),
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->adm_id,
                ];
                $proc = $this->mod_general->addData('finance_logs', $data);
                if ($proc) {
                    $call_back = [
                        'success' => 'true',
                        'message' => 'Data successfully inserted.',
                    ];
                    $this->session->set_flashdata('msg_success',
                        'Log book penagihan untuk pesanan: <b>#'.$reference.'</b> berhasil <b>DITAMBAHKAN</b></p>');
                } else {
                    $call_back = [
                        'success' => 'false',
                        'message' => 'Failed to insert data.',
                    ];
                }
            }
            echo json_encode($call_back, true);
        } catch (Exception $e) {
            $call_back = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($call_back, true);
        }
    }

    public function addAmount($id)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $data['detil'] = $this->mod_general->detailData('orders', 'id_order', $id);

        $this->load->view('admin/finance/amount_popup', $data);
    }

    # TODO : add logs and block permission for auditor
    public function amountPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area, true)) {
                $call_back = [
                    'success' => 'false',
                    'message' => 'Maaf, anda tidak dapat melakukan proses ini.',
                ];
            } else {
                $this->db->trans_begin();

                $id_order = $this->input->post('id_order', true);
                $reference = $this->input->post('reference', true);
                $total_tagihan = $this->input->post('total_paid', true);
                $status = $this->input->post('current_state', true);
                $periode = $this->input->post('periode', true);

                // Insert tabel finance_history
                $data = [
                    'id_order' => $id_order,
                    'amount' => $this->input->post('amount', true),
                    'pay_date' => $this->input->post('pay_date'),
                    'notes' => $this->input->post('notes', true),
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->adm_id,
                ];

                $proc = $this->mod_general->addData('finance_history', $data);
                
                // Menambahkan log pembayaran
                $data = [
                    'id_order' => $id_order,
                    'amount' => $this->input->post('amount', true),
                    'pay_date' => $this->input->post('pay_date'),
                    'notes' => "Pembayaran dibuat oleh ".$this->adm_name." pada tanggal ".date('Y-m-d H:i:s') ,
                    'action_date' => date('Y-m-d H:i:s'),
                    'action_by' => $this->adm_id,
                ];

                $proc = $this->mod_general->addData('finance_history_log', $data);

                if ($proc) {
                    // Calculation
                    $total_terbayar = $this->mod_finance->getTotalPay($id_order);
                    $selisih = $total_tagihan - $total_terbayar;

                    // Update value
                    $data_update = [
                        'nilai_dibayar' => $total_terbayar,
                        'nilai_piutang' => $selisih,
                        'sts_bayar' => ($selisih > 0) ? 1 : 2, // 1=Angsur; 2=Lunas;
                        'tgl_lunas' => ($selisih <= 0) ? $data['pay_date'] : null,
                        'tgl_bayar' => ($selisih <= 0) ? $data['pay_date'] : null,
                        'jumlah_bayar' => ($selisih <= 0) ? $total_terbayar : null,
                    ];

                    // Update current_state
                    if ($selisih <= 0 && $status >= 7) {
                        $data_update['current_state'] = 9;
                    }

                    $update_tagihan = $this->mod_general->updateData('orders', $data_update, 'id_order', $id_order);
                    if ($update_tagihan) {
                        if ($data_update['sts_bayar'] == 2) {
							/* ditutup 20200519
                            // Update report stock status
                            if ($status >= 5) {
                                $exist_scm = $this->mod_general->checkExist('order_scm', 'id_order', $id_order);
                                if ($exist_scm > 0) {
                                    $status_scm = $this->mod_general->getAll('order_scm', '*',
                                        'id_order='.$id_order)[0]->status;
                                    if ($status_scm >= 2 && $this->periode == $periode) {
                                        $id_gudang = $this->mod_general->getWarehouseOrder($id_order)->id_gudang;
                                        $detail_order = $this->mod_general->getAll('order_detail', '*',
                                            'id_order='.$id_order, 'id_order_detail ASC');
										// $stock_status = false;
                                        // foreach ($detail_order as $row => $val) {
                                        //     $stock_status = $this->addReportStockStatus($id_gudang, $val->product_id,
                                        //         $val->product_quantity);
                                        // }
                                        // if ($stock_status == false) {
                                        //     $this->db->trans_rollback();
                                        //     $call_back = [
                                        //         'success' => 'false',
                                        //         'message' => error_form('Gagal update laporan stock status.'),
                                        //     ];
                                        //     echo json_encode($call_back, true);
                                        //     exit();
                                        // }
                                    }
                                }
                            }
							*/
                            
                            // insert ke tabel order_history
                            $data_history = [
                                'id_employee' => $this->adm_id,
                                'id_order' => $id_order,
                                'id_order_state' => 9,
                                'date_add' => date('Y-m-d H:i:s'),
                            ];
                            $this->mod_general->addData('order_history', $data_history);
                            $this->genExcel($id_order, true);
                        }

                        if ($this->db->trans_status() == true) {
                            $this->db->trans_commit();
                            $this->session->set_flashdata('msg_success',
                                'Data pembayaran untuk pesanan: <b>#'.$reference.'</b> berhasil <b>DITAMBAHKAN</b></p>');
                            $call_back = [
                                'success' => 'true',
                                'message' => 'Data berhasil disimpan.',
                            ];
                        } else {
                            $this->db->trans_rollback();
                            $call_back = [
                                'success' => 'false',
                                'message' => error_form('Gagal melakukan proses.'),
                            ];
                        }
                    } else {
                        $this->db->trans_rollback();
                        $call_back = [
                            'success' => 'false',
                            'message' => 'Gagal menyimpan data.',
                        ];
                    }
                } else {
                    $this->db->trans_rollback();
                    $call_back = [
                        'success' => 'false',
                        'message' => 'Failed to insert data.',
                    ];
                }
            }
        } catch (Exception $e) {
            $call_back = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
        }
        echo json_encode($call_back, true);
    }

    public function addReportStockStatus($id_gudang, $id_produk, $jumlah)
    {
        $this->db->trans_begin();
        if ($id_gudang && $id_produk) {

            $today = date('Y-m-d H:i:s');
            $month = date('n');
            $year = date('Y');

            $stock_status = $this->mod_finance->getLastStockStatus($now = 1, $id_gudang, $id_produk, $month, $year);

            if ($stock_status) {
                // In same month and year
                $hpp_produk = (int)$this->mod_general->getAll("master_hpp", "hpp",
                    "id_gudang = $id_gudang and id_produk = $id_produk and id_periode = ".$stock_status['id_periode'])[0]->hpp;

                $total_expense = (int)($jumlah * $hpp_produk);
                $total_cost = (int)$stock_status['total_cost'];
                $total_qty = (int)($stock_status['stok_fisik'] - $jumlah);

                $new_stok_booking = (int)($stock_status['stok_booking'] - $jumlah);
                $new_average_cost = $total_qty == 0 ? 0 : (($total_cost - $total_expense) / $total_qty);
                $new_total_cost = $total_qty * $new_average_cost;
                $new_allocated_cost = $new_stok_booking * $new_average_cost;

                $report = [
                    'stok_fisik' => $total_qty,
                    'stok_booking' => $new_stok_booking,
                    'average_cost' => $new_average_cost,
                    'total_cost' => $new_total_cost,
                    'allocated_cost' => $new_allocated_cost,
                    'updated_date' => $today,
                ];
                $this->mod_finance->edit("report_stock_status", "id = ".$stock_status['id'], $report);
            } else {
                // In different month and year
                $last_stock_status = $this->mod_finance->getLastStockStatus($now = 0, $id_gudang, $id_produk, $month,
                    $year);

                $report = [
                    'id_periode' => $last_stock_status['id_periode'],
                    'id_gudang' => $id_gudang,
                    'id_produk' => $id_produk,
                    'bulan' => $month,
                    'tahun' => $year,
                ];

                if ($last_stock_status) {
                    // Have record below this month
                    $hpp_produk = (int)$this->mod_general->getAll("master_hpp", "hpp",
                        "id_gudang = $id_gudang and id_produk = $id_produk and id_periode = ".$last_stock_status['id_periode'])[0]->hpp;

                    $total_expense = (int)($jumlah * $hpp_produk);
                    $total_cost = (int)$last_stock_status['total_cost'];
                    $total_qty = (int)($last_stock_status['stok_fisik'] - $jumlah);

                    $new_stok_booking = (int)($last_stock_status['stok_booking'] - $jumlah);
                    $new_average_cost = $total_qty == 0 ? 0 : (($total_cost - $total_expense) / $total_qty);
                    $new_total_cost = $total_qty * $new_average_cost;
                    $new_allocated_cost = $new_stok_booking * $new_average_cost;

                    $report += [
                        'tgl_transaksi' => $last_stock_status['tgl_transaksi'],
                        'stok_fisik' => $total_qty,
                        'stok_booking' => $new_stok_booking,
                        'stok_available' => (int)$last_stock_status['stok_available'],
                        'average_cost' => $new_average_cost,
                        'total_cost' => $new_total_cost,
                        'allocated_cost' => $new_allocated_cost,
                        'created_date' => $today,
                    ];

                    $this->mod_finance->add("report_stock_status", $report);
                } else {
                    // Don't have record below this month
                    $this->db->trans_rollback();

                    return false;
                }
            }

            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();

                return true;
            }

            $this->db->trans_rollback();

            return false;
        }

        $this->db->trans_rollback();

        return false;
    }

    public function genExcel($id, $auto = false)
    {
        if ($id) {
            $detil = $this->mod_general->detailData($this->table, 'id_order', $id);
            if ( ! $detil) {
                $detil = $this->mod_general->detailData($this->table, 'reference', $id);
                $id = $detil['id_order'];
            }
            if ($detil) {
                $customer = $this->mod_general->detailData('customer', 'id_customer', $detil['id_customer']);
                $listproducts = $this->mod_finance->getListProduct($id);
                $postfix = strtolower(str_replace(" ", "_", str_replace(".", "", $customer['kabupaten'])));
                //load our new PHPExcel library
                $this->load->library('excel');
                //activate worksheet number 1
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('#'.$detil['reference']);
                $this->excel->getActiveSheet()->setCellValue('B1', 'Kode Pesanan =')->setCellValue('B2',
                        'Perwakilan =')->setCellValue('B3', 'Kode Kab =')->setCellValue('B4',
                        'Kabupaten =')->setCellValue('B5', 'Kode Kec =')->setCellValue('B6',
                        'Kecamatan =')->setCellValue('B7', 'Sales =')->setCellValue('B8',
                        'No Dapodik/NPSN =')->setCellValue('B9', 'Nama Sekolah =')->setCellValue('B10',
                        'Alamat =')->setCellValue('B11', 'Desa =')->setCellValue('B12',
                        'Kode Pos =')->setCellValue('B13', 'Bendahara =')->setCellValue('B14',
                        'Nip Bendahara =')->setCellValue('B15', 'Kepala Sekolah =')->setCellValue('B16',
                        'Nip KepSek =')->setCellValue('B17', 'Hp KepSek =')->setCellValue('B18',
                        'Nama Operator =')->setCellValue('B19', 'Hp Operator =')->setCellValue('B20',
                        'Email =')->setCellValue('B21', 'Cara Bayar =')->setCellValue('B22',
                        'Tanggal Pesan =')->setCellValue('B23', 'Tanggal Lunas =')->setCellValue('B24',
                        'Total Bayar =')->setCellValue('B25', 'Perwakilan');
                $this->excel->getActiveSheet()->getStyle('B1:B25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $this->excel->getActiveSheet()->setCellValue('C1', $detil['reference'])->setCellValue('C2',
                        'Perwakilan')->setCellValue('C4', $customer['kabupaten'])->setCellValue('C5',
                        '')->setCellValue('C6', $customer['kecamatan'])->setCellValue('C7',
                        $detil['sales_referer'])->setCellValue('C9', $customer['school_name'])->setCellValue('C10',
                        $customer['alamat'])->setCellValue('C11', $customer['desa'])->setCellValue('C13',
                        $customer['nama_bendahara'])->setCellValue('C15', $customer['name'])->setCellValue('C18',
                        $customer['operator'])->setCellValue('C20', $customer['email'])->setCellValue('C21',
                        'Transfer')->setCellValue('C22', tglFaktur($detil['date_add']))->setCellValue('C23',
                        tglFaktur($detil['tgl_lunas']))->setCellValue('C25', 'Perwakilan');
                $this->excel->getActiveSheet()->setCellValueExplicit('C3', $customer['kd_kab_kota'],
                    PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('C8', $customer['no_npsn'],
                    PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('C12', $customer['kodepos'],
                    PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('C14', $customer['nip_bendahara'],
                    PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('C16', $customer['nip_kepsek'],
                    PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('C17', $customer['phone_kepsek'],
                    PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('C19', $customer['hp_operator'],
                    PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValueExplicit('C24', toRupiah($detil['nilai_dibayar']),
                    PHPExcel_Cell_DataType::TYPE_STRING);
                $this->excel->getActiveSheet()->setCellValue('A26', 'NO')->setCellValue('B26',
                        'ISBN')->setCellValue('C26', 'JUDUL')->setCellValue('D26', 'JENJANG')->setCellValue('E26',
                        'PENERBIT')->setCellValue('F26', 'PENGARANG')->setCellValue('G26', 'KELAS')->setCellValue('H26',
                        'QTY')->setCellValue('I26', 'HARGA')->setCellValue('J26', 'KODE BUKU');
                $worksheet = $this->excel->getActiveSheet();
                $rowNumber = 27;
                $nomor = 1;
                // Loop through the result set
                foreach ($listproducts as $row) {
                    $worksheet->setCellValue('A'.$rowNumber, $nomor);
                    $worksheet->setCellValue('B'.$rowNumber, $row['isbn']);
                    $worksheet->setCellValue('C'.$rowNumber, $row['judul']);
                    $worksheet->setCellValue('D'.$rowNumber, $row['jenjang']);
                    $worksheet->setCellValue('E'.$rowNumber, $row['penerbit']);
                    $worksheet->setCellValue('F'.$rowNumber, $row['pengarang']);
                    $worksheet->setCellValue('G'.$rowNumber, $row['kelas']);
                    $worksheet->setCellValue('H'.$rowNumber, $row['qty']);
                    $worksheet->setCellValue('I'.$rowNumber, $row['harga']);
                    $worksheet->setCellValue('J'.$rowNumber, $row['kode_buku']);
                    $rowNumber++;
                    $nomor++;
                }
                $filename = $detil['reference'].'_'.$postfix.'.xls'; //save our workbook as this file name
                $pathfile = 'assets/data/orders/lunas/';
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                if ($auto == true) {
                    if ( ! is_dir($pathfile)) {
                        if ( ! mkdir($pathfile, 0777, true) && ! is_dir($pathfile)) {
                            throw new \RuntimeException(sprintf('Directory "%s" was not created', $pathfile));
                        }
                        chmod($pathfile, 0777);
                    } else {
                        chmod($pathfile, 0777);
                    }
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save($pathfile.$filename);
                    $subject = 'Kode Pesanan #'.$detil['reference'].' Telah Dibayar Lunas';
                    $to = ['infolunas@gramediaprinting.com'];
                    $content = '<p>Terlampir Detil Data Kode Pesanan <b>#'.$detil['reference'].'</b> di PT. Gramedia yang telah dibayar lunas.</p><p>Terima Kasih</p>';
                    $attach = $pathfile.$filename;
                    $this->load->library('mymail');
                    $this->mymail->send($subject, $to, $content, $attach);
                } else {
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="'.$filename.'"');
                    header('Cache-Control: max-age=0');
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Data pesanan tidak ditemukan !!!",
                ]);
            }
        }
    }

    public function complete()
    {
        $data['page_title'] = 'Pesanan Sekolah (Lunas) | '.date('Y-m-d_His');
        $data['nilai_dibayar'] = $this->mod_finance->totalRupiah(2);
        $this->_output['content'] = $this->load->view('admin/finance/list_complete', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/finance/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_orders_complete()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_order AS id_order, 
                                   a.reference AS reference, 
                                   b.school_name AS school_name, 
                                   CONCAT(a.category, " / ",a.type) AS category, 
                                   b.provinsi AS provinsi, 
                                   b.kabupaten AS kabupaten, 
                                   a.date_add AS date_add, 
                                   a.date_upd AS date_upd, 
                                   a.korwil_name AS korwil_name,
                                   a.total_paid AS total_paid, 
                                   a.tgl_lunas AS tgl_lunas, 
                                   a.nilai_dibayar AS nilai_dibayar, 
                                   b.phone AS phone, 
                                   b.operator AS operator, 
                                   b.hp_operator AS hp_operator, 
                                   b.name AS name, 
                                   b.phone_kepsek AS phone_kepsek');
        $this->datatables->from('orders a');
        $this->datatables->join('customer b', 'b.id_customer=a.id_customer', 'inner');
        $this->datatables->join('order_state c', 'c.id_order_state=a.current_state', 'inner');
        $this->datatables->where('a.current_state NOT IN (1, 2, 4)');
        $this->datatables->where('a.sts_bayar', 2);
        if ($this->adm_level == 4) {
            $this->datatables->where('a.sales_referer = (select aa.email from employee aa where aa.id_employee = '.$this->adm_id.')');
        } elseif (in_array($this->adm_level, [3, 8])) {
            $this->datatables->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/finance/detail/$1').'">$2</a>',
            'id_order, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function allInput()
    {
        $data['page_title'] = 'Pesanan Sekolah (Semua Inputan) | '.date('Y-m-d_His');
        $data['nilai_diinput'] = $this->mod_finance->totalRupiah(4);
        $this->_output['content'] = $this->load->view('admin/finance/list_all_input', $data, true);
        $this->_output['script_js'] = $this->load->view('admin/finance/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_orders_all_input()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('d.created_at AS tanggal_sistem, 
                                   d.pay_date AS tanggal_bayar, 
                                   d.amount AS jumlah_bayar, 
                                   d.notes AS catatan, 
                                   a.reference AS no_pesanan, 
                                   b.no_npsn AS npsn, 
                                   a.date_add AS tgl_pesan, 
                                   b.school_name AS nama_sekolah, 
                                   a.category AS category, 
                                   a.type AS type, 
                                   CASE a.is_offline WHEN 1 THEN CONCAT("<b><i>Offline</i></b>") WHEN 0 THEN CONCAT("Online") END AS jenis_pesanan, 
                                   c.name AS order_state, 
                                   c.label AS label');
        $this->datatables->from('orders a');
        $this->datatables->join('customer b', 'b.id_customer=a.id_customer', 'inner');
        $this->datatables->join('order_state c', 'c.id_order_state=a.current_state', 'inner');
        $this->datatables->join('finance_history d', 'd.id_order=a.id_order', 'inner');
        $this->datatables->where('a.current_state NOT IN (1, 2, 4)');
        $this->datatables->where('a.sts_bayar >=', 1);
        $this->datatables->edit_column('order_state', '<span class="label $1">$2</span>', 'label, order_state');
        $this->output->set_output($this->datatables->generate());
    }

    public function allOrder()
    {
        if (in_array($this->adm_level, [3, 4, 8])) {
            $data['page_title'] = 'Status Bayar Pesanan Sekolah | '.date('Y-m-d_His');
            $this->_output['content'] = $this->load->view('admin/finance/list_rsm_korwil_sales', $data, true);
            $this->_output['script_js'] = $this->load->view('admin/finance/js', '', true);
            $this->load->view('admin/template', $this->_output);
        } else {
            redirect(ADMIN_PATH.'/finance', 'refresh');
        }
    }

    public function list_orders_all()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $startDate = $this->input->post('start_date') ?? '2016-01-01';
        $endDate = $this->input->post('end_date') ?? date('Y-m-d');
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_order AS id_order, 
                                   a.reference AS reference, 
                                   b.school_name AS school_name, 
                                   b.provinsi AS provinsi, 
                                   b.kabupaten AS kabupaten, 
                                   a.category AS category, 
                                   a.type AS type, 
                                   a.date_add AS date_add, 
                                   c.name AS order_state, 
                                   c.label AS label, 
                                   a.total_paid AS total_paid, 
                                   a.nilai_dibayar AS nilai_dibayar, 
                                   (a.total_paid - a.nilai_dibayar) AS nilai_piutang, 
                                   a.sales_name AS mitra_name,
                                   b.phone AS phone, 
                                   b.operator AS operator, 
                                   b.hp_operator AS hp_operator, 
                                   b.name AS name, 
                                   b.phone_kepsek AS phone_kepsek');
        $this->datatables->from('orders a');
        $this->datatables->join('customer b', 'b.id_customer=a.id_customer', 'inner');
        $this->datatables->join('order_state c', 'c.id_order_state=a.current_state', 'inner');
        $this->datatables->where('a.current_state NOT IN (1, 2, 4)');
        $this->datatables->where('a.date_add BETWEEN \''.$startDate.' 00:00:00\' AND \''.$endDate.' 23:59:59\'');
        if ($this->adm_level == 4) {
            $this->datatables->where('a.sales_referer = (select aa.email from employee aa where aa.id_employee = '.$this->adm_id.')');
        } elseif (in_array($this->adm_level, [3, 8])) {
            $this->datatables->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        $this->datatables->edit_column('order_state', '<span class="label $1">$2</span>', 'label, order_state');
        $this->datatables->edit_column('reference', '<a href="'.base_url(ADMIN_PATH.'/finance/detail/$1').'">$2</a>',
            'id_order, reference');
        $this->output->set_output($this->datatables->generate());
    }

    public function reportStock()
    {
        $where = null;
        $term = null;
        if ($this->input->post('search_input', true)) {
            $term = $this->input->post('search_input', true);
            $where = "judul_buku LIKE '%".$this->input->post('search_input')."%' OR kode_buku LIKE '%".$this->input->post('search_input')."%' OR kelas LIKE '%".$this->input->post('search_input')."%'";
        }
        $data['page_title'] = 'Laporan - Stok Finance';
        $data['term'] = $term;
        $data['liststok_finance'] = $this->mod_finance->getAll("report_stock_finance", "*", $where, "id asc");
        $data['content'] = $this->load->view('admin/finance/laporan_stok', $data, true);
        $this->load->view('admin/template', $data);

        return false;
    }

    public function reportStockRupiah()
    {
        $where = null;
        $term = null;
        if ($this->input->post('search_input', true)) {
            $term = $this->input->post('search_input', true);
            $where = "judul_buku LIKE '%".$this->input->post('search_input')."%' OR kode_buku LIKE '%".$this->input->post('search_input')."%' OR kelas LIKE '%".$this->input->post('search_input')."%'";
        }
        $data['page_title'] = 'Laporan - Stok Finance';
        $data['term'] = $term;
        $data['liststok_finance'] = $this->mod_finance->getAll("report_stock_finance", "*", $where, "id asc");
        $data['content'] = $this->load->view('admin/finance/laporan_stok_rupiah', $data, true);
        $this->load->view('admin/template', $data);

        return false;
    }

    public function indexReportReceiving()
    {
        $data['listgudang'] = $this->mod_finance->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');

        $data['page_title'] = 'Laporan Receiving - Inventory Stock';
        $data['content'] = $this->load->view('admin/finance/inventory_stock/laporan_receiving', $data, true);
        $data['script_js'] = $this->load->view('admin/finance/inventory_stock/laporan_receiving_js', '', true);
        $data['script_css'] = $this->load->view('admin/finance/inventory_stock/laporan_receiving_css', '', true);

        $this->load->view('admin/template', $data);
    }

    public function reportReceiving($offset = 0)
    {
        try {
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $id_gudang = (int)$this->input->post('id_gudang') ?: null;

            $report_receiving = $this->inventoryReceiving($start_date, $end_date, $id_gudang);

            // $config = array(
            //     'base_url' => base_url().$this->controller_name."/reportReceiving/",
            //     'num_links' => 5,
            //     'per_page' => 5,
            //     'total_rows' => count($total_receiving),
            //     'uri_segment' => 3
            // );

            // $report_receiving = $this->mod_finance->getReportReceiving($start_date, $end_date, $id_gudang, $config['per_page'], $offset);

            // $this->pagination->initialize($config);
            // $links = $this->pagination->create_links();

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

    public function inventoryReceiving($start_date, $end_date, $id_gudang = null)
    {
        try {
            $report_receiving = $this->mod_finance->getReportReceiving($start_date, $end_date, $id_gudang);

            $report = array();
            $count = 0;
            $id_periode = 0;
            $id_gudang = 0;
            $id_bulan = 0;
            foreach ($report_receiving as $row => $data) {
                if ($id_periode != $data['id_periode']) {
                    $id_periode = $data['id_periode'];
                    $id_gudang = $data['id_gudang'];
                    $id_bulan = $data['bln_transaksi'];
                    $count = 0;
                } elseif ($id_gudang != $data['id_gudang']) {
                    $id_gudang = $data['id_gudang'];
                    $id_bulan = $data['bln_transaksi'];
                    $count = 0;
                } elseif ($id_bulan != $data['bln_transaksi']) {
                    $id_bulan = $data['bln_transaksi'];
                    $count = 0;
                }

                $total_cost = $data['jumlah_buku'] * $data['unit_cost'];

                $report[$id_periode]['nama_periode'] = $data['nama_periode'];
                $report[$id_periode]['row1'][$id_gudang]['nama_gudang'] = $data['nama_gudang'];
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['nama_bulan'] = bulanIndo($data['bln_transaksi']);
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['kode_buku'] = $data['kode_buku'];
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['judul_buku'] = $data['judul_buku'];
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['jumlah_buku'] = $data['jumlah_buku'];
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['unit_cost'] = $data['unit_cost'];
                $report[$id_periode]['row1'][$id_gudang]['row2'][$id_bulan]['row3'][$count]['total_cost'] = $total_cost;
                $count++;
            }

            return $report;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function printReceiving($start_date, $end_date, $id_gudang = null)
    {
        if ($start_date && $end_date) {
            $id_gudang = $id_gudang ? (int)$id_gudang : null;

            $report_receiving = $this->inventoryReceiving($start_date, $end_date, $id_gudang);

            $data['report'] = $report_receiving;
            $data['start_date'] = date('d/m/Y', strtotime($start_date));
            $data['end_date'] = date('d/m/Y', strtotime($end_date));

            $this->load->view('admin/finance/inventory_stock/print/report_receiving', $data);
        } else {
            return false;
        }
    }

    public function printReceivingExcel($start_date, $end_date, $id_gudang = null)
    {
        try {
            if ($start_date && $end_date) {
                $id_gudang = $id_gudang ? (int)$id_gudang : null;
                $show_start_date = date('d/m/Y', strtotime($start_date));
                $show_end_date = date('d/m/Y', strtotime($end_date));
                $report_receiving = $this->inventoryReceiving($start_date, $end_date, $id_gudang);


                $this->load->library('excel');
                $worksheet = $this->excel->getActiveSheet();

                $this->excel->setActiveSheetIndex(0);
                $worksheet->setTitle('Laporan Receiving');

                $worksheet->setCellValue('B2', 'Laporan Penerimaan Barang')->mergeCells('B2:I2')->setCellValue('B3',
                        'TRANSACTION DATE : '.$show_start_date.' s/d '.$show_end_date)->mergeCells('B3:I3');

                $worksheet->setCellValue('B5', 'ITEM NO')->setCellValue('C5', 'DESCRIPTION')->setCellValue('D5',
                        'QUANTITY')->setCellValue('E5', 'UNIT COST')->setCellValue('F5',
                        'BY.MATERIAL')->setCellValue('G5', 'BY.JASA')->setCellValue('H5', 'TAX')->setCellValue('I5',
                        'TOTAL');

                $worksheet->getStyle('B2:I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $worksheet->getStyle('B2')->getFont()->setBold(true);

                $worksheet->getStyle('B5:I5')->getFont()->setBold(true);

                $grand_total_qty = 0;
                $grand_total = 0;
                $row_head1 = 6;
                foreach ($report_receiving as $row => $periode) {
                    $worksheet->setCellValue('B'.$row_head1,
                            'PERIODE : '.$periode['nama_periode'])->mergeCells('B'.$row_head1.':I'.$row_head1)->getStyle('B'.$row_head1)->getFont()->setBold(true);


                    $row_head2 = $row_head1 + 1;
                    foreach ($periode['row1'] as $row1 => $gudang) {
                        $worksheet->setCellValue('B'.$row_head2,
                                'LOKASI : '.$row1.' - '.strtoupper(substr($gudang['nama_gudang'],
                                    7)))->mergeCells('B'.$row_head2.':I'.$row_head2)->getStyle('B'.$row_head2)->getFont()->setBold(true);

                        $row_head3 = $row_head2 + 1;
                        $sub_total_qty = 0;
                        $sub_total = 0;
                        foreach ($gudang['row2'] as $row2 => $bulan) {
                            $worksheet->setCellValue('B'.$row_head3,
                                    'BULAN : '.$bulan['nama_bulan'])->mergeCells('B'.$row_head3.':I'.$row_head3)->getStyle('B'.$row_head3)->getFont()->setBold(true);

                            $row_detail = $row_head3 + 1;
                            foreach ($bulan['row3'] as $row3 => $detail) {
                                $worksheet->setCellValue('B'.$row_detail,
                                        $detail['kode_buku'])->setCellValue('C'.$row_detail,
                                        strtoupper($detail['judul_buku']))->setCellValue('D'.$row_detail,
                                        number_format($detail['jumlah_buku']), 0)->setCellValue('E'.$row_detail,
                                        number_format($detail['unit_cost'], 2))->setCellValue('F'.$row_detail,
                                        number_format($detail['total_cost'], 2))->setCellValue('G'.$row_detail,
                                        '0')->setCellValue('H'.$row_detail, '0')->setCellValue('I'.$row_detail,
                                        number_format($detail['total_cost'], 2));

                                $worksheet->getStyle('D'.$row_detail.':I'.$row_detail)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                                $row_detail++;

                                $sub_total_qty += $detail['jumlah_buku'];
                                $sub_total += $detail['total_cost'];
                                $grand_total_qty += $detail['jumlah_buku'];
                                $grand_total += $detail['total_cost'];
                            }

                            $row_head3 = $row_detail;
                        }
                        $row_head2 = $row_head3;

                        $worksheet->setCellValue('B'.$row_head2,
                                'Sub Total')->mergeCells('B'.$row_head2.':C'.$row_head2);

                        $worksheet->setCellValue('D'.$row_head2,
                                number_format($sub_total_qty, 0))->setCellValue('E'.$row_head2,
                                '-')->setCellValue('F'.$row_head2,
                                number_format($sub_total, 2))->setCellValue('G'.$row_head2,
                                '0')->setCellValue('H'.$row_head2, '0')->setCellValue('I'.$row_head2,
                                number_format($sub_total, 2));

                        $worksheet->getStyle('B'.$row_head2.':I'.$row_head2)->getFont()->setBold(true);

                        $worksheet->getStyle('B'.$row_head2.':I'.$row_head2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        ++$row_head2;
                    }
                    $row_head1 = $row_head2;
                }

                $worksheet->setCellValue('B'.$row_head1, 'Grand Total')->mergeCells('B'.$row_head1.':C'.$row_head1);

                $worksheet->setCellValue('D'.$row_head1,
                        number_format($grand_total_qty, 0))->setCellValue('E'.$row_head1,
                        '0')->setCellValue('F'.$row_head1, number_format($grand_total, 2))->setCellValue('G'.$row_head1,
                        '0')->setCellValue('H'.$row_head1, '0')->setCellValue('I'.$row_head1,
                        number_format($grand_total, 2));

                $worksheet->getStyle('B'.$row_head1.':I'.$row_head1)->getFont()->setBold(true);

                $worksheet->getStyle('B'.$row_head1.':I'.$row_head1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                foreach (range('B', 'I') as $columnID) {
                    $worksheet->getColumnDimension($columnID)->setAutoSize(true);
                }

                $filename = 'laporan_receiving_'.$start_date.'_'.$end_date.'.xls';
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

    public function indexReportStockStatus()
    {
        $data['listgudang'] = $this->mod_finance->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');

        $data['page_title'] = 'Laporan Stock Status - Inventory Stock';
        $data['content'] = $this->load->view('admin/finance/inventory_stock/laporan_stock_status', $data, true);
        $data['script_js'] = $this->load->view('admin/finance/inventory_stock/laporan_stock_status_js', '', true);
        $data['script_css'] = $this->load->view('admin/finance/inventory_stock/laporan_stock_status_css', '', true);

        $this->load->view('admin/template', $data);
    }

    public function reportStockStatus()
    {
        try {
            $month = (int)$this->input->post('month');
            $year = (int)$this->input->post('year');
            $id_gudang = (int)$this->input->post('id_gudang') ?: null;

            $report_stock_status = $this->mod_finance->getReportStockStatus($month, $year, $id_gudang);

            $response = array(
                'success' => 'true',
                'data' => $report_stock_status,
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

    public function printStockStatusExcel($month, $year, $id_gudang = null)
    {
        try {
            if ($month && $year) {
                $title = 'STOCK STATUS TOTAL';
                $sub_title = 'PERIODE '.bulanIndo((int)$month).' '.$year;

                $report_stock_status = $this->mod_finance->getReportStockStatus($month, $year, $id_gudang);

                $this->load->library('excel');
                $worksheet = $this->excel->getActiveSheet();
                $this->excel->setActiveSheetIndex(0);
                $worksheet->setTitle('Laporan Stock Status');

                $worksheet->setCellValue('B2', strtoupper($title))->mergeCells('B2:I2')->setCellValue('B3',
                        strtoupper($sub_title))->mergeCells('B3:I3');

                $worksheet->setCellValue('B5', 'ITEM NO')->setCellValue('C5', 'DESCRIPTION')->setCellValue('D5',
                        'QTY ON HAND')->setCellValue('E5', 'QTY ALLOC.')->setCellValue('F5',
                        'NET AVAIL.')->setCellValue('G5', 'AVERAGE COST')->setCellValue('H5',
                        'TOTAL COST')->setCellValue('I5', 'COST ALLOCATED');

                $worksheet->getStyle('B2:I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $worksheet->getStyle('B2:B3')->getFont()->setBold(true);

                $worksheet->getStyle('B5:I5')->getFont()->setBold(true);

                $grand_total = 0;
                $grand_total_alloc = 0;
                $grand_total_fisik = 0;
                $grand_total_booking = 0;
                $grand_total_availbale = 0;
                $rows = 6;
                foreach ($report_stock_status as $row => $val) {
                    $worksheet->setCellValue('B'.$rows, strtoupper($val['kode_buku']))->setCellValue('C'.$rows,
                            $val['judul_buku'])->setCellValue('D'.$rows,
                            rupiah($val['stok_fisik'], 0, 2))->setCellValue('E'.$rows,
                            rupiah($val['stok_booking'], 0, 2))->setCellValue('F'.$rows,
                            rupiah($val['stok_available'], 0, 2))->setCellValue('G'.$rows,
                            rupiah($val['average_cost'], 2, 2))->setCellValue('H'.$rows,
                            rupiah($val['total_cost'], 2, 2))->setCellValue('I'.$rows,
                            rupiah($val['allocated_cost'], 2, 2));

                    $grand_total_fisik += $val['stok_fisik'];
                    $grand_total_booking += $val['stok_booking'];
                    $grand_total_availbale += $val['stok_available'];
                    $grand_total += $val['total_cost'];
                    $grand_total_alloc += $val['allocated_cost'];
                    $rows++;
                }

                $worksheet->getStyle('D6:I'.($rows - 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue('B'.$rows, 'Grand Total')->mergeCells('B'.$rows.':C'.$rows);

                $worksheet->setCellValue('D'.$rows, rupiah($grand_total_fisik, 0, 2))->setCellValue('E'.$rows,
                        rupiah($grand_total_booking, 0, 2))->setCellValue('F'.$rows,
                        rupiah($grand_total_availbale, 0, 2))->setCellValue('G'.$rows, '-')->setCellValue('H'.$rows,
                        rupiah($grand_total, 2, 2))->setCellValue('I'.$rows, rupiah($grand_total_alloc, 2, 2));

                $worksheet->getStyle('B'.$rows.':I'.$rows)->getFont()->setBold(true);

                $worksheet->getStyle('B'.$rows.':I'.$rows)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                foreach (range('B', 'I') as $columnID) {
                    $worksheet->getColumnDimension($columnID)->setAutoSize(true);
                }

                $filename = 'laporan_stock_status_'.$year.'-'.$month.'_'.$id_gudang.'.xls';
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

    public function indexReportSalesAnalysis($type = 0)
    {
        $data['listgudang'] = $this->mod_finance->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');
        $data['type'] = $type;

        if ($type > 0) {
            $data['page_title'] = 'Laporan Sales Analysis by Product Belum Kirim - Inventory Stock';
        } else {
            $data['page_title'] = 'Laporan Sales Analysis by Product - Inventory Stock';
        }

        $data['content'] = $this->load->view('admin/finance/inventory_stock/laporan_sales_analysis', $data, true);
        $data['script_js'] = $this->load->view('admin/finance/inventory_stock/laporan_sales_analysis_js', '', true);
        $data['script_css'] = $this->load->view('admin/finance/inventory_stock/laporan_sales_analysis_css', '', true);

        $this->load->view('admin/template', $data);
    }

    public function reportSalesAnalysis()
    {
        try {
            $month = (int)$this->input->post('month');
            $year = (int)$this->input->post('year');
            $id_gudang = (int)$this->input->post('id_gudang') ?: null;
            $type = (int)$this->input->post('type');

            if ($type > 0) {
                $report_sales_analysis = $this->mod_finance->getReportSalesAnalysisBeforeSend($month, $year,
                    $id_gudang);
            } else {
                $report_sales_analysis = $this->mod_finance->getReportSalesAnalysis($month, $year, $id_gudang);
            }

            $response = array(
                'success' => 'true',
                'data' => $report_sales_analysis,
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

    public function printSalesAnalysisExcel($type = 0, $month, $year, $id_gudang = null)
    {
        try {
            if ($month && $year) {
                $title = 'SALES ANALYSIS BY PRODUCT';
                $sub_title1 = 'Laporan Analisa Produk Total';
                $sub_title2 = 'TRANSACTION DATE : '.bulanIndo((int)$month).' - '.$year;

                if ($type > 0) {
                    $report_sales_analysis = $this->mod_finance->getReportSalesAnalysisBeforeSend($month, $year,
                        $id_gudang);
                    $names = 'laporan_sales_analysis_belum_kirim_';
                } else {
                    $report_sales_analysis = $this->mod_finance->getReportSalesAnalysis($month, $year, $id_gudang);
                    $names = 'laporan_sales_analysis_';
                }

                $this->load->library('excel');
                $worksheet = $this->excel->getActiveSheet();
                $this->excel->setActiveSheetIndex(0);
                $worksheet->setTitle('Laporan Sales Analysis');

                $worksheet->setCellValue('B2', strtoupper($title))->mergeCells('B2:M2')->setCellValue('B3',
                        strtoupper($sub_title1))->mergeCells('B3:M3')->setCellValue('B4',
                        strtoupper($sub_title2))->mergeCells('B4:M4');

                $worksheet->setCellValue('B6', 'ITEM NO')->mergeCells('B6:B7')->setCellValue('C6',
                        'DESCRIPTION')->mergeCells('C6:C7')->setCellValue('D6',
                        'CURRENT')->mergeCells('D6:H6')->setCellValue('I6',
                        'YEAR TO DATE')->mergeCells('I6:M6')->setCellValue('D7', 'QTY')->setCellValue('E7',
                        'COST')->setCellValue('F7', 'BRUTO')->setCellValue('G7', 'DISC')->setCellValue('H7',
                        'NETTO')->setCellValue('I7', 'QTY')->setCellValue('J7', 'COST')->setCellValue('K7',
                        'BRUTO')->setCellValue('L7', 'DISC')->setCellValue('M7', 'NETTO');

                $worksheet->getStyle('B2:M7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $worksheet->getStyle('B2:B4')->getFont()->setBold(true);

                $worksheet->getStyle('B6:M7')->getFont()->setBold(true);

                $grand_total_qty = 0;
                $grand_total_price = 0;
                $grand_total_year_qty = 0;
                $grand_total_year_price = 0;
                $rows = 8;
                foreach ($report_sales_analysis as $row => $val) {
                    $worksheet->setCellValue('B'.$rows, strtoupper($val['kode_buku']))->setCellValue('C'.$rows,
                            $val['judul_buku'])->setCellValue('D'.$rows,
                            rupiah($val['qty'], 0, 2))->setCellValue('E'.$rows,
                            rupiah($val['cost'], 2, 2))->setCellValue('F'.$rows,
                            rupiah($val['total_price'], 2, 2))->setCellValue('G'.$rows, '0')->setCellValue('H'.$rows,
                            rupiah($val['total_price'], 2, 2))->setCellValue('I'.$rows,
                            rupiah($val['year_qty'], 0, 2))->setCellValue('J'.$rows,
                            rupiah($val['year_cost'], 2, 2))->setCellValue('K'.$rows,
                            rupiah($val['year_total_price'], 2, 2))->setCellValue('L'.$rows,
                            '0')->setCellValue('M'.$rows, rupiah($val['year_total_price'], 2, 2));

                    $grand_total_qty += $val['qty'];
                    $grand_total_price += $val['total_price'];
                    $grand_total_year_qty += $val['year_qty'];
                    $grand_total_year_price += $val['year_total_price'];
                    $rows++;
                }

                $worksheet->getStyle('D8:M'.($rows - 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $worksheet->setCellValue('B'.$rows, 'Grand Total')->mergeCells('B'.$rows.':C'.$rows);

                $worksheet->setCellValue('D'.$rows, rupiah($grand_total_qty, 0, 2))->setCellValue('E'.$rows,
                        '-')->setCellValue('F'.$rows, rupiah($grand_total_price, 2, 2))->setCellValue('G'.$rows,
                        '-')->setCellValue('H'.$rows, rupiah($grand_total_price, 2, 2))->setCellValue('I'.$rows,
                        rupiah($grand_total_qty, 0, 2))->setCellValue('J'.$rows, '-')->setCellValue('K'.$rows,
                        rupiah($grand_total_price, 2, 2))->setCellValue('L'.$rows, '-')->setCellValue('M'.$rows,
                        rupiah($grand_total_price, 2, 2));

                $worksheet->getStyle('B'.$rows.':M'.$rows)->getFont()->setBold(true);

                $worksheet->getStyle('B'.$rows.':M'.$rows)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


                foreach (range('B', 'M') as $columnID) {
                    $worksheet->getColumnDimension($columnID)->setAutoSize(true);
                }

                $filename = $names.$year.'-'.$month.'_'.$id_gudang.'.xls';
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

    public function amountDelete()
    {
        // get amount data with id_amount
        $id = $this->input->post('id');

        // $csrftokenbs = $this->input->post('csrftokenbs');
        $data["csrftokenbs"] = $this->security->get_csrf_hash();

        // get data pembayaran per id
        $dataPembayaran = $this->mod_finance->getAll("finance_history","*","id=".$id);

        // id order
        $id_order = $dataPembayaran[0]->id_order;
        $amount = $dataPembayaran[0]->amount;
        $pay_date = $dataPembayaran[0]->pay_date;
        $created_at = $dataPembayaran[0]->created_at;

        $dataOrders = $this->mod_finance->getRowsArray("orders","*","id_order=".$id_order);
        $orderStatus = $dataOrders[0]["current_state"];
        $jumlah_bayar = $dataOrders[0]["jumlah_bayar"];
        $nilai_dibayar = $dataOrders[0]["nilai_dibayar"];
        $nilai_piutang = $dataOrders[0]["nilai_piutang"];
        $sts_bayar = $dataOrders[0]["sts_bayar"];
        $reference = $dataOrders[0]["reference"];
        $periode = $dataOrders[0]["periode"];


        // $jumlah_bayar = (double)$jumlah_bayar - (double)$amount;  // logika yang seharusnya, untuk menggunakan ini, ubah koding pada function amountPost
        $jumlah_bayar = 0;
        $nilai_dibayar = (double)$nilai_dibayar - (double)$amount;
        $nilai_piutang = (double)$nilai_piutang + (double)$amount;

        // jika status pembayaran lunas
        if($orderStatus == 9)
        {
            $orderStatus=8;
            // insert data ke tabel order_history
        }

        // Tambahkan function updateReportStockStatus
        // Jika sts_bayar == 2
        // jika $status=current_state >=5, maka cek data pada tabel order_scm
        // jika ada data pada table order_scm, maka dapatkan status pada tabel order_scm
        // jika status pada tabel order_scm >= 2 && periode pada tabel orders == periode sekarang
        // 
        // UPDATE TABEL report_stock_status
        // 

        // Mulai record transaction
        $this->db->trans_begin();

        if($sts_bayar==2)
        {
            if ($orderStatus >= 5) {
                $exist_scm = $this->mod_general->checkExist('order_scm', 'id_order', $id_order);
                if ($exist_scm > 0) {
                    $status_scm = $this->mod_general->getAll('order_scm', '*', 'id_order='.$id_order)[0]->status;
                    // change $this->periode to date_input pembayaran
                    
                    $inputPeriode = date('Y', strtotime($created_at));
                    if ($status_scm >= 2 && $inputPeriode == $periode) {
                        $id_gudang = $this->mod_general->getWarehouseOrder($id_order)->id_gudang;
                        $detail_order = $this->mod_general->getAll('order_detail', '*','id_order='.$id_order, 'id_order_detail ASC');
                        // $stock_status = false;
                        // foreach ($detail_order as $row => $val) {
                        //     $stock_status = $this->updateReportStockStatus($id_gudang, $val->product_id, $val->product_quantity, $created_at);
                        // }
                        // if ($stock_status == false) {
                        //     $this->db->trans_rollback();
                        //     $call_back = [
                        //         'success' => 'false',
                        //         'message' => error_form('Gagal update laporan stock status.'),
                        //     ];
                        //     echo json_encode($call_back, true);
                        //     exit();
                        // }
                    }
                }
            }
        }

        // Menambahkan log hapus pembayaran
        $data = [
            'id_order' => $id_order,
            'amount' => $amount,
            'pay_date' => $pay_date,
            'notes' => "Pembayaran dihapus Oleh ".$this->adm_name." pada tanggal ".date('Y-m-d H:i:s') ,
            'action_date' => date('Y-m-d H:i:s'),
            'action_by' => $this->adm_id,
        ];

        $proc = $this->mod_general->addData('finance_history_log', $data);

        // delete data pembayaran dari tabel finance_history
        $deletePembayaran = $this->mod_general->deleteData("finance_history","id", $id);

        // get data  berapa kali pembayaran
        $listAmount = $this->mod_finance->getRowsArray("finance_history","*","id_order=".$id_order, "pay_date desc");

        // get pay date last
        // $pay_date_last = $listAmount[0]["pay_date"]; // logika yang seharusnya, untuk menggunakan ini, ubah koding pada function amountPost
        $pay_date_last = null;

        // Jika jumlah row >= 1, maka update status bayar menjadi 1
        if(count($listAmount)>=1)
        {
            // update status pembayaran menjadi 1
            $sts_bayar = 1;
        }
        else
        {
            // update status pembayaran menjadi 0
            $sts_bayar=0;
        }

        $where = [
            'id_order' => $id_order,
            'id_order_state' => 9
        ];

        $this->mod_finance->deleteData('order_history', $where);

        $orderUpdate = array(
            "jumlah_bayar" => $jumlah_bayar,
            "nilai_dibayar" => $nilai_dibayar,
            "tgl_lunas" => null,
            "tgl_bayar" => $pay_date_last,
            "sts_bayar" => $sts_bayar,
            "nilai_piutang" => $nilai_piutang,
            "current_state" => $orderStatus
        );

        // echo json_encode($orderUpdate);

        $this->mod_general->updateData("orders",$orderUpdate,"id_order", $id_order);

        // NOTE
        // 1. delete data bayar pada tabel finance_logs
        // 2. update data order pada tabel orders
        // 3. insert data ke tabel order_history, jika orders_status=8
        
        // insert ke tabel order_history
        
            
        if ($this->db->trans_status() == true) {
            $this->db->trans_commit();
            $this->session->set_flashdata('msg_success',
            'Data pembayaran untuk pesanan: <b>#'.$reference.'</b> berhasil <b>DIHAPUS</b></p>');
            $call_back = [
                'success' => 'true',
                'message' => 'Data berhasil dihapus.',
            ];
        } 
        else 
        {
            $this->db->trans_rollback();
            $call_back = [
                'success' => 'false',
                'message' => error_form('Gagal melakukan proses.'),
            ];
        }

        echo json_encode($call_back);
    }

    public function updateReportStockStatus($id_gudang, $id_produk, $jumlah, $created_at)
    {
        $this->db->trans_begin();
        if ($id_gudang && $id_produk) {

            // get date_created dari tabel finance_history
            // $today = date('Y-m-d H:i:s');
            // $month = date('n');
            // $year = date('Y');

            // $created_at dapat dari hasil query finance_history
            $today = date('Y-m-d H:i:s');
            $year = date('Y', strtotime($created_at));
            $month = date('n', strtotime($created_at));

            $stock_status = $this->mod_finance->getLastStockStatus($now = 0, $id_gudang, $id_produk, $month, $year);

            if ($stock_status) {
                // In same month and year
                $hpp_produk = (int)$this->mod_general->getAll("master_hpp","hpp","id_gudang = $id_gudang and id_produk = $id_produk and id_periode = ".$stock_status['id_periode'])[0]->hpp;

                $total_expense = (int)($jumlah * $hpp_produk);
                $total_cost = (int)$stock_status['total_cost'];
                $total_qty = (int)($stock_status['stok_fisik'] + $jumlah);

                $new_stok_booking = (int)($stock_status['stok_booking'] + $jumlah);
                $new_average_cost = $total_qty == 0 ? 0 : (($total_cost + $total_expense) / $total_qty);
                $new_total_cost = $total_qty * $new_average_cost;
                $new_allocated_cost = $new_stok_booking * $new_average_cost;

                $report = [
                    'stok_fisik' => $total_qty,
                    'stok_booking' => $new_stok_booking,
                    'average_cost' => $new_average_cost,
                    'total_cost' => $new_total_cost,
                    'allocated_cost' => $new_allocated_cost,
                    'updated_date' => $today,
                ];
                $this->mod_finance->edit("report_stock_status", "id = ".$stock_status['id'], $report);
            } 
            // else 
            // {
            //     // In different month and year
            //     $last_stock_status = $this->mod_finance->getLastStockStatus($now = 0, $id_gudang, $id_produk, $month,
            //         $year);

            //     $report = [
            //         'id_periode' => $last_stock_status['id_periode'],
            //         'id_gudang' => $id_gudang,
            //         'id_produk' => $id_produk,
            //         'bulan' => $month,
            //         'tahun' => $year,
            //     ];

            //     if ($last_stock_status) {
            //         // Have record below this month
            //         $hpp_produk = (int)$this->mod_general->getAll("master_hpp", "hpp",
            //             "id_gudang = $id_gudang and id_produk = $id_produk and id_periode = ".$last_stock_status['id_periode'])[0]->hpp;

            //         $total_expense = (int)($jumlah * $hpp_produk);
            //         $total_cost = (int)$last_stock_status['total_cost'];
            //         $total_qty = (int)($last_stock_status['stok_fisik'] - $jumlah);

            //         $new_stok_booking = (int)($last_stock_status['stok_booking'] - $jumlah);
            //         $new_average_cost = $total_qty == 0 ? 0 : (($total_cost - $total_expense) / $total_qty);
            //         $new_total_cost = $total_qty * $new_average_cost;
            //         $new_allocated_cost = $new_stok_booking * $new_average_cost;

            //         $report += [
            //             'tgl_transaksi' => $last_stock_status['tgl_transaksi'],
            //             'stok_fisik' => $total_qty,
            //             'stok_booking' => $new_stok_booking,
            //             'stok_available' => (int)$last_stock_status['stok_available'],
            //             'average_cost' => $new_average_cost,
            //             'total_cost' => $new_total_cost,
            //             'allocated_cost' => $new_allocated_cost,
            //             'created_date' => $today,
            //         ];

            //         $this->mod_finance->add("report_stock_status", $report);
            //     } else {
            //         // Don't have record below this month
            //         $this->db->trans_rollback();

            //         return false;
            //     }
            // }

            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();

                return true;
            }

            $this->db->trans_rollback();

            return false;
        }

        $this->db->trans_rollback();

        return false;
    }

    function testing()
    {
        $created_at = "2016-08-10 14:45:48";
        echo $year = date('Y', strtotime($created_at));
        echo "<br>";
        echo $month = date('n', strtotime($created_at));
    }

}
