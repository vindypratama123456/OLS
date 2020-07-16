<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatable $datatable
 * @property Datatables $datatables
 * @property Excel $excel
 * @property Mod_general $mod_general
 * @property Mod_order $mod_order
 * @property Mod_pesanan $m_pesanan
 * @property Mymail $mymail
 * @property Dompdf_gen $dompdf_gen
 */



class Orders extends MY_Controller
{
    private $table;
    private $_output;

    // private $dbsiplah;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->load->model('mod_order');
        $this->table = 'orders';
        $this->_output = [];
        // $this->dbsiplah = $this->load->database('dbsiplah', true);
    }

    public function index()
    {
        $data['page_title'] = 'Pesanan Online | '.date('Y-m-d_His');
        $data['total_omset'] = $this->mod_order->getOmset($this->adm_id)[0]['total_omset'];
        $data['order_terbuat'] = $this->mod_order->getCreatedOrder($this->adm_id)[0]['order_terbuat'];
        $data['order_terkonfirmasi'] = $this->mod_order->getConfirmedOrder($this->adm_id)[0]['order_terkonfirmasi'];
        $data['is_operator'] = false;
        if ($this->adm_level == 4) {
            $this->_output['content'] = $this->load->view('admin/orders/sales_list', $data, true);
        } elseif (in_array($this->adm_level, array_merge($this->backoffice_admin_area, [3, 5, 8]))) {
            $this->_output['content'] = $this->load->view('admin/orders/list', $data, true);
        } else {
            $this->_output['content'] = $this->load->view('admin/orders/no_access', $data, true);
        }
        $this->_output['script_js'] = $this->load->view('admin/orders/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_orders($variant = 1)
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH.'/orders');
        }
        $sign = $variant == 1 ? '!=' : '=';
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_order AS id_order, 
           a.reference AS kode, 
           b.no_npsn AS no_npsn, 
           b.school_name AS nama_sekolah, 
           b.provinsi AS propinsi, 
           b.kabupaten AS kabupaten, 
           b.kecamatan AS kecamatan,
           a.category AS kelas, 
           a.type AS tipe,
           a.semester as semester,
           a.date_add AS tgl_pesan, 
           c.name AS status, 
           c.label AS label, 
           a.total_paid AS total_harga,
           a.sales_name AS mitra,
           a.reference_other as reference_other,
           a.reference_other_from as reference_other_from');
        $this->datatables->from('orders a');
        $this->datatables->join('customer b', 'b.id_customer=a.id_customer', 'inner');
        $this->datatables->join('order_state c', 'c.id_order_state=a.current_state', 'inner');
        //$this->datatables->join('(select tz.`id_order`, tx.`semester` from order_detail tz inner join `product` ty on tz.`product_id`=ty.`id_product` left join `product_semester` tx on ty.`id_product`=tx.`id_product` group by id_order)tbl', 'tbl.id_order=a.id_order', 'inner');
        $this->datatables->where('a.is_offline '.$sign, 1);
        if ($this->adm_level == 4) {
            $this->datatables->where('a.sales_referer = (select aa.email from employee aa where aa.id_employee = '.$this->adm_id.')');
        } elseif ($this->adm_level == 5) {
            $this->datatables->where('a.current_state BETWEEN 5 AND 8');
        } elseif (in_array($this->adm_level, [2, 3, 8])) {
            $this->datatables->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        $this->datatables->edit_column('status', '<span class="label $1">$2</span>', 'label, status');
        $this->datatables->edit_column('kode', '<a href="'.base_url(ADMIN_PATH.'/orders/detail/$1').'">$2</a>',
            'id_order, kode');
        $this->output->set_output($this->datatables->generate());
    }

    public function list_filter_index()
    {
        $data['page_title'] = 'Pesanan Online | '.date('Y-m-d_His');
        $data['total_omset'] = $this->mod_order->getOmset($this->adm_id)[0]['total_omset'];
        $data['order_terbuat'] = $this->mod_order->getCreatedOrder($this->adm_id)[0]['order_terbuat'];
        $data['order_terkonfirmasi'] = $this->mod_order->getConfirmedOrder($this->adm_id)[0]['order_terkonfirmasi'];
        $data['is_operator'] = false;
        if ($this->adm_level == 4) {
            $this->_output['content'] = $this->load->view('admin/orders/sales_list_filter', $data, true);
        } elseif (in_array($this->adm_level, array_merge($this->backoffice_admin_area, [3, 5, 8]))) {
            $this->_output['content'] = $this->load->view('admin/orders/list_filter', $data, true);
        } else {
            $this->_output['content'] = $this->load->view('admin/orders/no_access', $data, true);
        }
        $this->_output['script_js'] = $this->load->view('admin/orders/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function list_filter($variant = 1)
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH.'/orders');
        }

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $cari = $this->input->post('cari');

        $sign = $variant == 1 ? '!=' : '=';
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('a.id_order AS id_order, 
           a.reference AS kode, 
           b.no_npsn AS no_npsn, 
           b.school_name AS nama_sekolah, 
           b.provinsi AS propinsi, 
           b.kabupaten AS kabupaten, 
           b.kecamatan AS kecamatan,
           a.category AS kelas, 
           a.type AS tipe,
           a.semester as semester,
           a.date_add AS tgl_pesan, 
           c.name AS status, 
           c.label AS label, 
           a.total_paid AS total_harga,
           a.sales_name AS mitra,
           a.reference_other as reference_other,
           a.reference_other_from as reference_other_from');
        $this->datatables->from('orders a');
        $this->datatables->join('customer b', 'b.id_customer=a.id_customer', 'inner');
        $this->datatables->join('order_state c', 'c.id_order_state=a.current_state', 'inner');
        //$this->datatables->join('(select tz.`id_order`, tx.`semester` from order_detail tz inner join `product` ty on tz.`product_id`=ty.`id_product` left join `product_semester` tx on ty.`id_product`=tx.`id_product` group by id_order)tbl', 'tbl.id_order=a.id_order', 'inner');
        if($start_date != null && $end_date != null)
        {
            $this->datatables->where('a.date_add BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
        }
        if($cari != null or $cari != "")
        {
            $this->datatables->where('(a.reference like "%'.$cari.'%" or b.school_name like "%'.$cari.'%" or b.no_npsn like "%'.$cari.'%")');
        }
        $this->datatables->where('a.is_offline '.$sign, 1);
        if ($this->adm_level == 4) {
            $this->datatables->where('a.sales_referer = (select aa.email from employee aa where aa.id_employee = '.$this->adm_id.')');
        } elseif ($this->adm_level == 5) {
            $this->datatables->where('a.current_state BETWEEN 5 AND 8');
        } elseif (in_array($this->adm_level, [2, 3, 8])) {
            $this->datatables->where('b.kabupaten IN (SELECT aa.kabupaten_kota FROM employee_kabupaten_kota aa WHERE aa.id_employee = '.$this->adm_id.')');
        }
        $this->datatables->edit_column('status', '<span class="label $1">$2</span>', 'label, status');
        $this->datatables->edit_column('kode', '<a href="'.base_url(ADMIN_PATH.'/orders/detail/$1').'">$2</a>',
            'id_order, kode');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($id)
    {
        // if($this->adm_level==3) {
        //     if(false==$this->isHaveAccess($id))
        //         redirect(ADMIN_PATH.'/orders','refresh');
        // }
        if ($id && is_numeric($id)) {
            $data['order_states'] = $this->mod_general->getWhere('order_state', 'deleted', 0, 'id_order_state', 'asc');
            $data['detil'] = $this->mod_general->detailData($this->table, 'id_order', $id);
            if ($data['detil']) {
                // if($data['detil']['sales_referer']=="" || $data['detil']['sales_referer']==null)
                // {
                //    $data['check_kontrak']="test";
                // }
                // else
                // {
                //    $data['check_kontrak'] = $this->mod_order->check_kontrak($data['detil']['sales_referer']);
                // }

                $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                    $data['detil']['id_customer']);
                if (in_array($this->adm_level, $this->backoffice_admin_area) || $this->adm_level == 8) {
                    $kabupaten = get_data([
                        'field' => 'kabupaten',
                        'table' => 'customer',
                        'key' => 'id_customer',
                        'data' => $data['detil']['id_customer'],
                    ]);
                    $korwil = $this->mod_order->getKorwilById($kabupaten);
                } else {
                    $korwil = $this->adm_id;
                }
                $data['listproducts'] = $this->mod_order->getListProducts($id);
                $data['category_books'] = $data['listproducts'][0]->category;
                $data['class_books'] = $data['listproducts'][0]->class;
                $data['liststatus'] = $this->mod_order->getListStatus($id);
                $data['listhistory'] = $this->mod_order->getListHistory($id);
                $data['listsales'] = $this->mod_order->getSalesPerson($korwil, true);
                $data['korwil'] = $this->mod_order->getKorwil($data['customer']['kabupaten'])[0];
                $data['adm_level'] = $this->adm_level;
                $data['isCoverageArea'] = $this->mod_order->isCoverageArea($data['customer']['kabupaten']);
                $data['isInComission'] = $this->mod_order->isInComission($id);
                $data['isInSCMProcess'] = $this->mod_order->isInSCMProcess($id);
                $this->_output['content'] = $this->load->view('admin/orders/detail', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/orders/css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/orders/js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH.'/orders', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH.'/orders', 'refresh');
        }
    }

    # TODO : add logs and block permission for auditor
    public function editPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH.'/orders');
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack = [
                    "success" => "false",
                    "message" => "Maaf, anda tidak dapat melakukan proses ini.",
                ];
            } else {
                $this->db->trans_begin();
                $id = $this->input->post('id_order');
                $idCustomer = $this->input->post('id_customer');
                $reference = $this->input->post('reference');
                $currentState = $this->input->post('current_state');
                $idState = $this->input->post('id_order_state');
                $toEmail = [];
                if ( ! empty($this->input->post('email_sekolah'))) {
                    $toEmail[] = $this->input->post('email_sekolah');
                }
                if ( ! empty($this->input->post('email_kepsek'))) {
                    $toEmail[] = $this->input->post('email_kepsek');
                }
                if ( ! empty($this->input->post('email_operator'))) {
                    $toEmail[] = $this->input->post('email_operator');
                }
                $data = [
                    'current_state' => $idState,
                    'date_upd' => date('Y-m-d H:i:s'),
                ];

                // vindy 2019-08-22
                // Menambahkan function email blacklist
                //$toEmail = $this->getDataEmailBlacklist($toEmail);

                if ($idState == 1) {
                    if((!empty($this->input->post('persetujuan_keterangan', true)) || $this->input->post('persetujuan_keterangan', true) !=='') && $this->adm_level == '8')
                    {
                        $data['persetujuan_rsm'] = $this->input->post('persetujuan_keterangan', true)=='' ? NULL : $this->adm_name;
                        $data['persetujuan_keterangan'] = $this->input->post('persetujuan_keterangan', true)=='' ? NULL : $this->input->post('persetujuan_keterangan', true);
                        $data['persetujuan_tanggal'] = $this->input->post('persetujuan_keterangan', true)=='' ? NULL : date('Y-m-d H:i:s');
                    }
                }

                if ($idState == 3) {
                    $listKorwil = $this->mod_order->getKorwil($this->input->post('customer_kabkota'))[0];
                    $salesReferer = $this->input->post('sales_referer', true);
                    $dataSales = $this->mod_general->getAll("employee", "name, telp, id_employee",
                        "email = '$salesReferer'")[0];
                    $data['tgl_konfirmasi'] = date('Y-m-d H:i:s');
                    $data['jangka_waktu'] = $this->input->post('jangka_waktu', true);
                    $data['kesepakatan_sampai'] = $this->input->post('kesepakatan_sampai', true);
                    $data['sales_referer'] = $salesReferer;
                    $data['sales_name'] = $dataSales->name;
                    $data['sales_phone'] = $dataSales->telp;
                    $data['korwil_email'] = $listKorwil['email'];
                    $data['korwil_name'] = $listKorwil['name'];
                    $data['korwil_phone'] = $listKorwil['telp'];

                    if((!empty($this->input->post('persetujuan_keterangan', true)) || $this->input->post('persetujuan_keterangan', true) !=='') && $this->adm_level == '8')
                    {
                        $data['persetujuan_rsm'] = $this->input->post('persetujuan_keterangan', true)=='' ? NULL : $this->adm_name;
                        $data['persetujuan_keterangan'] = $this->input->post('persetujuan_keterangan', true)=='' ? NULL : $this->input->post('persetujuan_keterangan', true);
                        $data['persetujuan_tanggal'] = $this->input->post('persetujuan_keterangan', true)=='' ? NULL : date('Y-m-d H:i:s');
                    }
                } elseif ($idState == 5) {
                    $is_intan = $this->input->post('is_intan') ?: 2;
                    $data['tgl_logistik'] = date('Y-m-d H:i:s');
                    $data['is_intan'] = $is_intan;

                    if((!empty($this->input->post('persetujuan_keterangan', true)) || $this->input->post('persetujuan_keterangan', true) !=='') && $this->adm_level == '8')
                    {
                        $data['persetujuan_rsm'] = $this->input->post('persetujuan_keterangan', true)=='' ? NULL : $this->adm_name;
                        $data['persetujuan_keterangan'] = $this->input->post('persetujuan_keterangan', true)=='' ? NULL : $this->input->post('persetujuan_keterangan', true);
                        $data['persetujuan_tanggal'] = $this->input->post('persetujuan_keterangan', true)=='' ? NULL : date('Y-m-d H:i:s');
                    }
                } elseif ($idState == 6) {
                    $data['tgl_kirim'] = date('Y-m-d');
                } elseif ($idState == 7) {
                    $data['tgl_sampai'] = $this->input->post('tgl_sampai', true);
                    $data['nama_penerima'] = $this->input->post('nama_penerima', true);
                } elseif ($idState == 8) {
                    $data['tgl_terima'] = $this->input->post('tgl_terima', true);
                    $data['nomor_surat'] = $reference;
                    $data['tanggal_surat'] = $this->input->post('tgl_terima', true);
                    if ( ! empty($_FILES['file_bast']['name'])) {
                        $config['upload_path'] = 'uploads/bast';
                        $config['file_name'] = $reference;
                        $config['overwrite'] = true;
                        $config["allowed_types"] = 'jpg|jpeg|png|gif|tif|pdf';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('file_bast')) {
                            $callBack = [
                                'success' => 'false',
                                'message' => $this->upload->display_errors(),
                            ];
                            echo json_encode($callBack, true);
                            exit();
                        }
                        $my = $this->upload->data();
                        $myFilename = $my['file_name'];
                        $data['file_bast'] = $myFilename;
                    }
                } elseif ($idState == 9) {
                    $data['tgl_bayar'] = $this->input->post('tgl_bayar');
                    $data['jumlah_bayar'] = $this->input->post('jumlah_bayar');
                }
                $proc1 = $this->mod_general->updateData($this->table, $data, 'id_order', $id);
                if ($proc1) {
                    if ($currentState == $idState) {
                        if ($this->db->trans_status() == true) {
                            $this->db->trans_commit();
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Data successfully updated.',
                            ];
                            echo json_encode($callBack, true);
                            exit();
                        }
                        $this->db->trans_rollback();
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Data unsuccessfully updated.',
                        ];
                        echo json_encode($callBack, true);
                        exit();
                    }
                    // Jika logistik Gramedia
                    if ($idState == 5 && $is_intan != 1) {
                        $checkExist = $this->mod_general->getList("order_scm", "*", "id_order=".$id);
                        $gudangTujuan = $this->mod_order->getRecommendedWarehouse($this->input->post('customer_kabkota'))->id_gudang;
                        if ($checkExist == 0) {
                            $order_scm = [
                                'id_order' => $id,
                                'reference' => $reference,
                                'id_customer' => $idCustomer,
                                'id_gudang' => $gudangTujuan,
                                'status' => 1,
                                'date_created' => date('Y-m-d H:i:s'),
                                'date_modified' => date('Y-m-d H:i:s'),
                            ];
                            $this->mod_general->addData('order_scm', $order_scm);
                        }
                    }
                    // data bayar
                    if ($idState == 9) {
                        $dataBayar = [
                            'id_order' => $id,
                            'nama_bank' => $this->input->post('nama_bank', true),
                            'nama_pembayar' => $this->input->post('nama_pembayar', true),
                            'tgl_bayar' => $data['tgl_bayar'],
                            'jumlah_bayar' => $data['jumlah_bayar'],
                            'created_date' => date('Y-m-d H:i:s'),
                            'created_by' => $this->adm_id,
                        ];
                        $this->mod_general->addData('payment_confirmation', $dataBayar);
                    }
                    // insert ke tabel order_history
                    $dataHistory = [
                        'id_employee' => $this->adm_id,
                        'id_order' => $id,
                        'id_order_state' => $idState,
                        'date_add' => date('Y-m-d H:i:s'),
                    ];
                    $proc2 = $this->mod_general->addData('order_history', $dataHistory);
                    if ($proc2) {
                        if ($this->db->trans_status() == true) {
                            $this->db->trans_commit();
                            if ($idState == 3 || $idState == 5 || $idState == 6) {
                                if (count($toEmail) > 0 && env('CI_ENV') == 'production') {
                                    // kirim email notifikasi ke sekolah
                                    if ($idState == 3) {
                                        $attachmentPesanan = $this->genPdfPesanan($reference, false);
                                        $this->sendMailBuktiPesan($toEmail, $reference, $id, $attachmentPesanan);
                                    }
                                    $attachment = $this->genPdf($reference, false);
                                    if ($idState == 3 && false == $this->isCompleteProfile($idCustomer)) {
                                        $this->sendMail($idState, $toEmail, $reference, $id, false, $attachment);
                                    } else {
                                        $this->sendMail($idState, $toEmail, $reference, $id, true, $attachment);
                                    }
                                    // kirim email ke intan
                                    if ($idState == 5 && $is_intan == 1) {
                                        $this->genExcel($id, true);
                                    }
                                }
                            }
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Data successfully inserted.',
                            ];
                            $this->session->set_flashdata('msg_success',
                                'Data pesanan dengan Kode: <b>'.$reference.'</b> berhasil <b>DIPERBARUI</b></p>');
                        } else {
                            $this->db->trans_rollback();
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Data unsuccessfully inserted.',
                            ];
                        }
                    } else {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Failed to insert order history.',
                        ];
                    }
                } else {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Failed to update data.',
                    ];
                }
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    public function genPdfPesanan($reference, $stream = false)
    {
        if ($reference) {
            $data['detil'] = $this->mod_general->detailData($this->table, 'reference', $reference);
            if ($data['detil']) {
                $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                    $data['detil']['id_customer']);
                $data['detailpesanan'] = $this->mod_order->getListProducts($data['detil']['id_order']);
                $data['category'] = $this->mod_general->detailData('category', 'alias', $data['detil']['type']);
                $html = $this->load->view('admin/orders/print_pesanan', $data, true);
                $postfix = strtolower(str_replace(" ", "_", str_replace(".", "", $data['customer']['school_name'])));
                $filename = 'pesanan_'.$reference.'_'.$postfix; //save file name
                $pathfile = 'assets/data/pesanan/';
                if ( ! is_dir($pathfile)) {
                    if ( ! mkdir($pathfile, 0777, true) && ! is_dir($pathfile)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $pathfile));
                    }
                    chmod($pathfile, 0777);
                } else {
                    chmod($pathfile, 0777);
                }
                //yang umum A4, Legal, Letter
                $paper = "Legal";
                //portrait or landscape
                $orientation = "portrait";
                //Open Browser 1=Download 0=Open Browser
                $attachment = 1;
                // Load library
                $this->load->library(['dompdf_gen', 'parser']);
                // Convert to PDF
                if (false == $stream) {
                    $this->dompdf_gen->pdf_create($html, $pathfile.$filename, $paper, $orientation, $attachment, false);

                    return $pathfile.$filename.'.pdf';
                }

                $this->dompdf_gen->pdf_create($html, $filename, $paper, $orientation, $attachment, true);
            }
        }
    }

    private function sendMailBuktiPesan($to, $reference, $id, $attachment)
    {
        /* Ditutup. Fa, 20200319
		    $this->load->library('mymail');
        $subject = 'Salinan Bukti Pesanan #'.$reference;
        $toEmail = $to;
        $content = '<p>Terlampir Bukti Pesanan Buku Sekolah anda di PT. Gramedia dengan Kode: <b>#'.$reference.'</b></p><p>Untuk melihat detil pesanan anda, silahkan <a href ="'.base_url('pesanan/detail/'.$id).'"><b>KLIK DISINI</b></a></p>';

        $this->mymail->send($subject, $toEmail, $content, $attachment);
		    */

    }

    public function genPdf($reference, $stream = false)
    {
        if ($reference) {
            $data['detil'] = $this->mod_general->detailData($this->table, 'reference', $reference);
            if ($data['detil']) {
                $data['customer'] = $this->mod_general->detailData('customer', 'id_customer',
                    $data['detil']['id_customer']);
                $html = $this->load->view('admin/orders/print_pernyataan', $data, true);
                $postfix = strtolower(str_replace(" ", "_", str_replace(".", "", $data['customer']['school_name'])));
                $filename = $reference.'_'.$postfix; //save file name
                $pathfile = 'assets/data/pernyataan/';
                if ( ! is_dir($pathfile)) {
                    if ( ! mkdir($pathfile, 0777, true) && ! is_dir($pathfile)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $pathfile));
                    }
                    chmod($pathfile, 0777);
                } else {
                    chmod($pathfile, 0777);
                }
                //yang umum A4, Legal, Letter
                $paper = "A4";
                //portrait or landscape
                $orientation = "portrait";
                //Open Browser 1=Download 0=Open Browser
                $attachment = 1;
                // Load library
                $this->load->library(['dompdf_gen', 'parser']);
                // Convert to PDF
                if (false == $stream) {
                    $this->dompdf_gen->pdf_create($html, $pathfile.$filename, $paper, $orientation, $attachment, false);

                    return $pathfile.$filename.'.pdf';
                }

                $this->dompdf_gen->pdf_create($html, $filename, $paper, $orientation, $attachment, true);
            }
        }
    }

    private function sendMail($order_state, $to, $reference, $id, $is_complete = true, $attachment = false)
    {
        /* Ditutup. Fa, 20200319
		    switch ($order_state) {
            case '3':
            $state = 'Dikonfirmasi';
            break;
            case '5':
            $state = 'Dikirim ke logistik';
            break;
            case '6':
            $state = 'Dikirim ke sekolah';
            break;
            case '7':
            $state = 'Telah Sampai';
            break;
        }
        // Kirim email
        $this->load->library('mymail');
        $subject = 'Kode Pesanan #'.$reference.' telah '.strtoupper($state).'';
        $toEmail = $to;
        if (true == $is_complete) {
            $content = '<p>Pesanan Buku Sekolah anda di PT. Gramedia dengan Kode: <b>#'.$reference.'</b> baru saja <b>'.strtoupper($state).'</b></p><p>Untuk melihat detil pesanan anda, silahkan <a href ="'.base_url('pesanan/detail/'.$id).'"><b>KLIK DISINI</b></a></p>';
        } else {
            $content = '<p>Yang terhormat Bapak/Ibu Kepala Sekolah,<br /><br /></p>
            <p>Kami sudah menerima dan mengkonfirmasi pesanan buku sekolah Bapak/Ibu melalui website kami di <a href="http://bukusekolah.gramedia.com" target="_blank">http://bukusekolah.gramedia.com</a>.</p>
            <p>Pesanan Bapak/Ibu sudah tercatat di server <b>Lembaga Kebijakan Pengadaan Barang/Jasa Pemerintah</b> dan <b>Kementerian Pendidikan dan Kebudayaan</b>.</p>
            <p>Saat ini pesanan Bapak/Ibu sedang kami siapkan, oleh karena itu untuk melengkapi berkas administrasi penagihan dan laporan pertanggungjawaban dana BOS, kami mohon kerjasamanya untuk dapat <b>melengkapi profil sekolah</b> dengan mengklik <b><a href="http://bukusekolah.gramedia.com/akunsaya/profil">tautan ini</a>.</b></p><p>Terima kasih atas kepercayaan Bapak/Ibu kepada kami.<br /><br />Salam,<br /><br /><br /><b>PT. Gramedia</b></p>';
        }
        
        if ($order_state == 3) {
            $this->mymail->send($subject, $toEmail, $content, $attachment);
        } else {
            $this->mymail->send($subject, $toEmail, $content);
        }
        */
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
                if ($detil['current_state'] >= 5) {
                    $customer = $this->mod_general->detailData('customer', 'id_customer', $detil['id_customer']);
                    $listproducts = $this->mod_order->getListProduct($id);
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
                        'Email =')->setCellValue('B21', 'Cara Bayar =')->setCellValue('B22', 'Perwakilan');
                        $this->excel->getActiveSheet()->getStyle('B1:B22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $this->excel->getActiveSheet()->setCellValue('C1', $detil['reference'])->setCellValue('C2',
                            'Perwakilan')->setCellValue('C4', $customer['kabupaten'])->setCellValue('C5',
                            '')->setCellValue('C6', $customer['kecamatan'])->setCellValue('C7', '')->setCellValue('C9',
                            $customer['school_name'])->setCellValue('C10', $customer['alamat'])->setCellValue('C11',
                            $customer['desa'])->setCellValue('C13', $customer['nama_bendahara'])->setCellValue('C15',
                            $customer['name'])->setCellValue('C18', $customer['operator'])->setCellValue('C20',
                            $customer['email'])->setCellValue('C21', 'Transfer')->setCellValue('C22', 'Perwakilan');
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
                            $this->excel->getActiveSheet()->setCellValue('A23', 'NO')->setCellValue('B23',
                                'ISBN')->setCellValue('C23', 'JUDUL')->setCellValue('D23', 'JENJANG')->setCellValue('E23',
                                'PENERBIT')->setCellValue('F23', 'PENGARANG')->setCellValue('G23',
                                'KELAS')->setCellValue('H23', 'QTY')->setCellValue('I23', 'HARGA')->setCellValue('J23',
                                'KODE BUKU');
                                $worksheet = $this->excel->getActiveSheet();
                                $rowNumber = 24;
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
                    $pathfile = 'assets/data/orders/'.date('Y-m-d').'/';
                    // $pathfile = 'assets/data/orders/'.date('Y-m-d_His').'/';
                    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                    //if you want to save it as .XLSX Excel 2007 format
                    if ($auto == true) {
                        /* Ditutup. Fa, 20200319
						if ( ! is_dir($pathfile)) {
                            if ( ! mkdir($pathfile, 0777, true) && ! is_dir($pathfile)) {
                                throw new \RuntimeException(sprintf('Directory "%s" was not created', $pathfile));
                            }
                            chmod($pathfile, 0777);
                        } else {
                            chmod($pathfile, 0777);
                        }
                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        //upload to server directory
                        $objWriter->save($pathfile.$filename);
                        // prepare for mail
                        $subject = 'Kode Pesanan #'.$detil['reference'].' Mohon Untuk Dikirimkan';
                        $to = ['pesangramedia@intanpariwara.co.id', 'juliantinugraheni544@gmail.com'];
                        $content = '<p>Terlampir Detil Data Kode Pesanan <b>#'.$detil['reference'].'</b> di PT. Gramedia</p><p>Terima Kasih</p>';
                        $attach = $pathfile.$filename;
                        $this->load->library('mymail');
                        $this->mymail->send($subject, $to, $content, $attach);
						*/
                    } else {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'.$filename.'"');
                        header('Cache-Control: max-age=0'); //no cache
                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        $objWriter->save('php://output');
                    }
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Status pesanan belum 'Dikirim ke logistik' !!!",
                    ]);
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Data pesanan tidak ditemukan !!!",
                ]);
            }
        }
    }

    public function edit($id, $qty)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $data['old_qty'] = $qty;
        $data['detil'] = $this->mod_general->detailData('order_detail', 'id_order_detail', $id);
        $this->load->view('admin/orders/edit_detail', $data);
    }

    # TODO : add logs and block permission for auditor
    public function updatePost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH.'/orders');
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack = [
                    "success" => "false",
                    "message" => "Maaf, anda tidak dapat melakukan proses ini.",
                ];
            } else {
                $idOrder = $this->input->post('id_order');
                $idOrderDetail = $this->input->post('id_order_detail');
                $productId = $this->input->post('product_id');
                $productName = $this->input->post('product_name');
                $unitPrice = $this->input->post('unit_price');
                $productQuantity = $this->input->post('product_quantity');
                $oldQty = $this->input->post('old_qty');
                // jika quantity tidak berubah
                if ($productQuantity == $oldQty) {
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.',
                        'id_order' => $idOrder,
                    ];
                    echo json_encode($callBack, true);
                    exit();
                }


                $check_reference_other = $this->mod_general->detailData('orders', 'id_order', $idOrder);
                $product = $this->mod_general->detailData('product', 'id_product', $productId);

                // insert ke tabel order_detail_revisi
                $data1 = [
                    'id_order' => $idOrder,
                    'id_order_detail' => $idOrderDetail,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'quantity_before' => $oldQty,
                    'quantity_after' => $productQuantity,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->adm_id,
                ];
                // $proc1 = $this->mod_general->addData('order_detail_history', $data1);
                // if ($proc1) {
                    if ($productQuantity <= 0) {

                        if($check_reference_other['reference_other'] != null)
                        {
                            if($check_reference_other['reference_other_from'] == 'Siplah.id')
                            {
                                $kode_pesanan = $check_reference_other['reference_other'];
                                $kode_buku = $product['kode_buku'];
                                $qty = $productQuantity;
                                $update = true;
                                if($this->add_item_siplah($kode_pesanan, $kode_buku, $qty, $idOrder, $update))
                                {
                                    $data2 = [
                                        'product_quantity' => $productQuantity,
                                        'total_price' => $unitPrice * $productQuantity,
                                    ];
                                    $proc2 = $this->mod_general->deleteData('order_detail', 'id_order_detail', $idOrderDetail);
                                }
                                else
                                {
                                    $callBack = [
                                        'success' => 'false',
                                        'message' => 'Failed to update data siplah.',
                                    ];
                                    echo json_encode($callBack, true);
                                    exit;
                                }
                            }
                        }
                        else
                        {
                            $proc2 = $this->mod_general->deleteData('order_detail', 'id_order_detail', $idOrderDetail); 
                        }
                    } else {
                        // update tabel order_detail
                        

                        if($check_reference_other['reference_other'] != null)
                        {

                            if($check_reference_other['reference_other_from'] == 'Siplah.id')
                            {
                                $kode_pesanan = $check_reference_other['reference_other'];
                                $kode_buku = $product['kode_buku'];
                                $qty = $productQuantity;
                                $update = true;
                                if($this->add_item_siplah($kode_pesanan, $kode_buku, $qty, $idOrder, $update))
                                {
                                    $data2 = [
                                        'product_quantity' => $productQuantity,
                                        'total_price' => $unitPrice * $productQuantity,
                                    ];
                                    $proc2 = $this->mod_general->updateData('order_detail', $data2, 'id_order_detail',
                                        $idOrderDetail);
                                }
                                else
                                {
                                    $callBack = [
                                        'success' => 'false',
                                        'message' => 'Failed to update data siplah.',
                                    ];
                                    echo json_encode($callBack, true);
                                    exit;
                                }
                            }
                        }
                        else
                        {
                            $data2 = [
                                'product_quantity' => $productQuantity,
                                'total_price' => $unitPrice * $productQuantity,
                            ];
                            $proc2 = $this->mod_general->updateData('order_detail', $data2, 'id_order_detail',
                                $idOrderDetail);
                        }
                    }
                    if ($proc2) {

                        // update tabel orders
                        $q_total = $this->db->query("SELECT SUM(`total_price`) AS `total` FROM `order_detail` WHERE `id_order`=".$this->db->escape($idOrder));
                        $total_paid = $q_total->row('total');
                        $data3 = ['total_paid' => $total_paid];
                        $proc3 = $this->mod_general->updateData('orders', $data3, 'id_order', $idOrder);

                        $proc1 = $this->mod_general->addData('order_detail_history', $data1);
                        if ($proc3) {
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Data successfully updated.',
                                'id_order' => $idOrder,
                            ];
                            $this->session->set_flashdata('msg_success',
                                'Data detil pesanan berhasil <b>DIPERBARUI</b></p>');
                        } else {
                            $callBack = [
                                'success' => 'false',
                                'message' => 'Failed to update orders.',
                            ];
                        }
                    } else {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Failed to update orders_detail',
                        ];
                    }
                // } else {
                //     $callBack = [
                //         'success' => 'false',
                //         'message' => 'Failed to insert data.',
                //     ];
                // }
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    public function cancel($id)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $data['detil'] = $this->mod_general->detailData('orders', 'id_order', $id);
        $this->load->view('admin/orders/batal_popup', $data);
    }

    # TODO : add logs and block permission for auditor
    public function deletePost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack = [
                    "success" => "false",
                    "message" => "Maaf, anda tidak dapat melakukan proses ini.",
                ];
            } else {
                $idOrder = $this->input->post('id_order');
                $reference = $this->input->post('reference');
                $isInSCMProcess = $this->mod_order->isInSCMProcess($idOrder);
                if ($isInSCMProcess) {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Pesanan tidak dapat dibatalkan. Silahkan hubungi bagian Supply Chain',
                    ];
                } else {
                    $this->db->trans_begin();
                    $data = [
                        'current_state' => 2,
                        'alasan_batal' => $this->input->post('alasan_batal', true),
                        'date_upd' => date('Y-m-d H:i:s'),
                    ];
                    $proc = $this->mod_general->updateData('orders', $data, 'id_order', $idOrder);
                    if ($proc) {
                        $dataHistory = [
                            'id_employee' => $this->adm_id,
                            'id_order' => $idOrder,
                            'id_order_state' => 2,
                            'date_add' => date('Y-m-d H:i:s'),
                        ];
                        $procHistory = $this->mod_general->addData('order_history', $dataHistory);
                        if ($procHistory) {
                            if ( ! $isInSCMProcess) {
                                $this->mod_general->deleteData('order_scm', 'id_order', $idOrder);
                            }
                            $this->db->trans_commit();
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Data successfully updated.',
                            ];
                            $this->session->set_flashdata('msg_success',
                                'Data pesanan dengan kode: <b>'.$reference.'</b> berhasil <b>DIBATALKAN</b></p>');
                        } else {
                            $this->db->trans_rollback();
                            $callBack = [
                                'success' => 'false',
                                'message' => 'Failed to update data.',
                            ];
                            echo json_encode($callBack, true);
                            exit;
                        }
                    } else {
                        $this->db->trans_rollback();
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Failed to update data.',
                        ];
                    }
                }
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    public function changeSales($id)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $data['detil'] = $this->mod_general->detailData('orders', 'id_order', $id);
        $kabupaten = get_data([
            'field' => 'kabupaten',
            'table' => 'customer',
            'key' => 'id_customer',
            'data' => $data['detil']['id_customer'],
        ]);
        if (in_array($this->adm_level, $this->backoffice_admin_area) || $this->adm_level == 8) {
            $korwil = $this->mod_order->getKorwilById($kabupaten);
        } else {
            $korwil = $this->adm_id;
        }
        $data['listsales'] = $this->mod_order->getSalesPerson($korwil, true);
        $data['korwil'] = $this->mod_order->getKorwil($kabupaten)[0];
        $this->load->view('admin/orders/sales_popup', $data);
    }

    # TODO : add logs and block permission for auditor
    public function changeSalesPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack = [
                    "success" => "false",
                    "message" => "Maaf, anda tidak dapat melakukan proses ini.",
                ];
            } else {
                $idOrder = $this->input->post('id_order');
                $emailSales = $this->input->post('emailsales');
                $profile = $this->mod_general->detailData('employee', 'email', $emailSales);
                $data = [
                    'sales_referer' => $profile['email'],
                    'sales_name' => $profile['name'],
                    'sales_phone' => $profile['telp'],
                ];
                $proc = $this->mod_general->updateData('orders', $data, 'id_order', $idOrder);
                if ($proc) {
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.',
                    ];
                    $this->session->set_flashdata('msg_success', 'Data sales representatif berhasil <b>DIUBAH</b></p>');
                } else {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Failed to update data.',
                    ];
                }
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    public function cetakPesanan($reference)
    {
        $data['detil'] = $this->mod_general->detailData($this->table, 'reference', $reference);
        $data['customer'] = $this->mod_general->detailData('customer', 'id_customer', $data['detil']['id_customer']);
        $data['detailpesanan'] = $this->mod_order->getListProducts($data['detil']['id_order']);
        $this->load->view('admin/orders/print_pesanan', $data);
    }

    public function cetakBAST($id)
    {
        if ($this->adm_level == 3 && $id && false == $this->isHaveAccess($id)) {
            redirect(ADMIN_PATH.'/orders', 'refresh');
        }
        $data['order_states'] = $this->mod_general->getWhere('order_state', 'deleted', 0, 'id_order_state', 'asc');
        $data['detil'] = $this->mod_general->detailData($this->table, 'id_order', $id);
        $data['customer'] = $this->mod_general->detailData('customer', 'id_customer', $data['detil']['id_customer']);
        $data['listproducts'] = $this->mod_general->getWhere('order_detail', 'id_order', $id, 'id_order_detail', 'asc');
        $params = [
            'field' => 'jenjang',
            'table' => 'customer',
            'key' => 'id_customer',
            'data' => $data['detil']['id_customer'],
        ];
        $jenjang = get_data($params);
        $data['jenjang'] = $jenjang;
        $this->load->view('admin/orders/print_bast', $data);
    }

    private function isHaveAccess($id_order)
    {
        $query = $this->db->query("SELECT `b`.`id_group` AS `region` FROM `orders` `a` JOIN `customer` `b` ON `b`.`id_customer`=`a`.`id_customer` WHERE `a`.`id_order`=".$this->db->escape($id_order));
        $region = $query->row('region');

        return $region == $this->session->userdata('adm_region');
    }

    public function cetakKwintansi($id)
    {
        if ( ! in_array($this->adm_level, array_merge($this->backoffice_admin_area, [8]))) {
            return false;
        }
        $data['detil'] = $this->mod_general->getAll($this->table, '*', 'id_order='.$id)[0];
        $data['customer'] = $this->mod_general->getAll('customer', '*', 'id_customer='.$data['detil']->id_customer)[0];
        $data['korwil'] = $this->mod_order->getKorwil($data['customer']->kabupaten, "a.name")[0];
        $data['product'] = $this->mod_order->getListProducts($id);
        $this->load->view('admin/orders/print_kwitansi', $data);
    }

    public function cetakFaktur($id)
    {
        if ($this->adm_level == 3 && $id && false == $this->isHaveAccess($id)) {
            redirect(ADMIN_PATH.'/orders', 'refresh');
        }
        $data['order_states'] = $this->mod_general->getWhere('order_state', 'deleted', 0, 'id_order_state', 'asc');
        $data['detil'] = $this->mod_general->detailData($this->table, 'id_order', $id);
        $data['customer'] = $this->mod_general->detailData('customer', 'id_customer', $data['detil']['id_customer']);
        $data['listproducts'] = $this->mod_general->getWhere('order_detail', 'id_order', $id, 'id_order_detail', 'asc');
        $this->load->view('admin/orders/print_faktur', $data);
    }

    public function books()
    {
        $data['listdata'] = $this->mod_order->getListAllBooks();
        $this->_output['content'] = $this->load->view('admin/orders/list_books', $data, true);
        $this->load->view('admin/template', $this->_output);
    }

    public function deliverydetail()
    {
        $data['order_siap_kirim'] = $this->mod_order->getOrderReadyToShip();
        $this->_output['content'] = $this->load->view('admin/orders/delivery_detail', $data, true);
        $this->load->view('admin/template', $this->_output);
    }

    # TODO : add logs and block permission for auditor
    public function uploadbast()
    {
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('msg_failed', 'Maaf, anda tidak dapat melakukan proses ini.');
            redirect(ADMIN_PATH.'/orders/deliverydetail');
        } else {
            $config['upload_path'] = 'uploads/bast';
            $config['file_name'] = $this->input->post('no_reference');
            $config['overwrite'] = true;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|tif|pdf';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('bast')) {
                $this->session->set_flashdata('msg_failed', $this->upload->display_errors());
                redirect(ADMIN_PATH.'/orders/deliverydetail');
            } else {
                $this->session->set_flashdata('msg_success',
                    'Upload bast untuk order '.$this->input->post('no_reference').' Berhasil!');
                redirect(ADMIN_PATH.'/orders/deliverydetail');
            }
        }
    }

    # TODO : add logs and block permission for auditor
    public function tugaskansales()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH.'/orders');
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack = [
                    "success" => "false",
                    "message" => "Maaf, anda tidak dapat melakukan proses ini.",
                ];
            } else {
                $idOrder = $this->input->post('id_order');
                $orderReference = $this->input->post('reference');
                $namaSekolah = $this->input->post('sekolah_nama');
                $propinsiSekolah = $this->input->post('sekolah_propinsi');
                $kabkotaSekolah = $this->input->post('sekolah_kabkota');
                $emailSales = $this->input->post('emailsales', true);
                if ($emailSales) {
                    $dataSales = $this->mod_general->getAll("employee", "name, telp, id_employee",
                        "email = '$emailSales'")[0];
                    $dataKorwil = $this->mod_order->getKorwil($kabkotaSekolah)[0];
                    $data = [
                        'sales_referer' => $emailSales,
                        'sales_name' => $dataSales->name,
                        'sales_phone' => $dataSales->telp,
                        'korwil_email' => $dataKorwil['email'],
                        'korwil_name' => $dataKorwil['name'],
                        'korwil_phone' => $dataKorwil['telp'],
                    ];
                    $this->db->where('id_order', $idOrder);
                    $proc = $this->db->update('orders', $data);
                    if ($proc) {
                        $callBack = [
                            'success' => 'true',
                            'message' => 'Data successfully updated.',
                        ];
                        $this->session->set_flashdata('msg_success',
                            'Data pesanan dengan Kode: <b>'.$orderReference.'</b> berhasil <b>DITERUSKAN</b> ke sales: <b>'.$emailSales.'</b></p>');
                        if ($emailSales != $dataKorwil['email']) {
                            /* Ditutup. Fa, 20200319
							$this->load->library('mymail');
                            $subject = 'Fwd: Pesanan #'.$orderReference.' telah dibuat oleh '.$namaSekolah.' / '.$propinsiSekolah.' / '.$kabkotaSekolah;
                            $to = [$emailSales];
                            $content = '<p>Pesanan dengan Kode: <b>#'.$orderReference.'</b> telah dibuat oleh <b>'.$namaSekolah.' / '.$propinsiSekolah.' / '.$kabkotaSekolah.'</b></p><p>Segera lakukan konfirmasi dengan <a href ="'.base_url(ADMIN_PATH.'/orders/detail/'.$idOrder).'"><b>KLIK DISINI</b></a></p>';
                            $this->mymail->send($subject, $to, $content);
							*/
                        }
                    } else {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Failed to update orders.',
                        ];
                    }
                } else {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'No email specified.',
                    ];
                }
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    /*
    public function genExcelBulk()
    {
    $kueri = $this->mod_general->get_where($this->table,'current_state',5,'id_order','asc');
    foreach ($kueri as $row) {
        $exec = $this->genExcel($row->id_order);
        if(!$exec) {
            echo "File: <b>".$row->id_order."</b> successfully created<br />";
        }
        else {
            echo "Failed to create: ".$row->id_order."<br />";
        }
    }
    */

    public function listBooks($id_order, $jenjang, $zona, $categoryBooks, $classBooks)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $data['id_order'] = $id_order;
        $data['jenjang'] = $jenjang;
        $data['zona'] = $zona;
        $data['category'] = $this->mod_order->getCategoryName($categoryBooks);
        $data['listbooks'] = $this->mod_order->getListBooks($id_order, $zona, $categoryBooks, $classBooks);
        $this->load->view('admin/orders/books_popup', $data);
    }

    public function update_reference($id_order)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $orders = $this->mod_general->detailData('orders', 'id_order', $id_order);

        $reference_other = "";
        $reference_other_from = "";

        if(count($orders) > 0)
        {
            $reference_other = $orders['reference_other'];
            $reference_other_from = $orders['reference_other_from'];
        }

        // $check_reference = $this->mod_general->getList('orders', '', array('reference_other' => $reference_other));
        if($orders['reference_other'] == null || $orders['reference_other'] == "")
        {
            $data["button_text"] = "Tambah Reference";
            $data["title_text"] = "Tambah Data Reference";
            $data["pesan"] = "Yakin ingin menambah No. Reference?";
        }
        else
        {
            $data["button_text"] = "Ubah Reference";
            $data["title_text"] = "Ubah Data Reference";
            $data["pesan"] = "Yakin ingin mengubah No. Reference?";
        }

        $data['reference_other'] = $reference_other;
        $data['reference_other_from'] = $reference_other_from; 
        $data['partner'] = $this->mod_general->getAll('partner', '');
        $data['id_order'] = $id_order;
        $data['detil'] = $orders;
        $this->load->view('admin/orders/reference_popup', $data);
    }

    public function update_reference_post()
    {
        $id = $this->input->post("id_order");
        if($this->input->post("reference_other"))
        {  
            $reference_other = $this->input->post("reference_other");
            $reference_other_from = explode(":", $this->input->post("reference_other_from"))[1];
        }
        else
        {
            $reference_other = null;
            $reference_other_from = null;
        }
        $data = array(
            "reference_other" => $reference_other,
            "reference_other_from" => $reference_other_from
        );

        // $check_reference = $this->mod_general->getList('orders', '', array('reference_other' => $reference_other));
        // if($check_reference == 0)
        // {
            $query = $this->mod_general->updateData('orders', $data, 'id_order', $id);
            if ($query) 
            {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully updated.',
                    'redirect' => 'orders/detail/'.$id,
                ];
                $this->session->set_flashdata('msg_success', 'Berhasil mengubah data reference.');
            } 
            else 
            {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Failed to update data.',
                ];
            }
        // }
        // else
        // {
        //     $callBack = [
        //         'success' => 'false',
        //         'message' => 'No. Reference sudah terdaftar.',
        //     ];
        // }
        echo json_encode($callBack, true);
    }

    public function check_reference()
    {
        $reference_other = $this->input->post('reference_other');
        $check_reference = $this->mod_general->getList('orders', '', array('reference_other' => $reference_other));
        if($check_reference > 0)
        {
            $callBack = [
                'success' => 'true',
                'message' => 'Data reference '. $reference_other .', sudah terinput ke sistem.',
            ];
        }
        else
        {
            $callBack = [
                'success' => 'false',
                'message' => 'Data tidak ditemukan.',
            ];
        }

        echo json_encode($callBack, true);
    }

    # TODO : add logs and block permission for auditor
    public function addBooksPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack = [
                    "success" => "false",
                    "message" => "Maaf, anda tidak dapat melakukan proses ini.",
                ];
            } else {
                $this->load->model('mod_pesanan', 'm_pesanan');
                $idOrder = $this->input->post('id_order');
                $totalPay = 0;
                $pesanan = [];
                $count = 0;
                $zona = $this->input->post('zona');



                if($zona == null || $zona == "")
                {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Gagal menambahkan buku. Data zona tidak ada.',
                    ];
                    echo json_encode($callBack, true);
                    exit();
                }

                $price = 'price_'.$zona;
                // foreach (json_decode(file_get_contents(base_url()."/assets/data/json/".$this->input->post('jenjang')."/all_teks_konfirmasi.json",
                //     false, stream_context_create(arrSSLContext()))) as $bukubos) {
                $arrLiterasi  = [];
                $arrPengayaan = []; 
                $arrReferensi = []; 
                $arrPandik    = [];  
                $arrProductIt    = []; 
                $arrProductCovid    = []; 
                $arrAlatTulis    = [];
                $arrSmartLibrary = [];   

                $list_wajib          = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->input->post('jenjang').'/all_teks_konfirmasi.json', false, stream_context_create(arrSSLContext())));
                $list_literasi       = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->input->post('jenjang').'/literasi.json', false, stream_context_create(arrSSLContext())));
                $list_pengayaan      = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->input->post('jenjang').'/pengayaan.json', false, stream_context_create(arrSSLContext())));
                $list_referensi      = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->input->post('jenjang').'/referensi.json', false, stream_context_create(arrSSLContext())));
                $list_pandik         = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->input->post('jenjang').'/pandik.json', false, stream_context_create(arrSSLContext())));
                $list_product_it         = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->input->post('jenjang').'/product_it.json', false, stream_context_create(arrSSLContext())));
                $list_product_covid         = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->input->post('jenjang').'/product_covid.json', false, stream_context_create(arrSSLContext())));
                $list_alat_tulis         = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->input->post('jenjang').'/alat_tulis.json', false, stream_context_create(arrSSLContext())));
                $list_smart_library         = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->input->post('jenjang').'/smart_library.json', false, stream_context_create(arrSSLContext())));

                if(!empty($list_literasi)){
                    $arrLiterasi = $list_literasi->Literasi->{'Buku Literasi'};
                }

                if(!empty($list_pengayaan)){
                    $arrPengayaan = $list_pengayaan->Pengayaan->{'Buku Pengayaan'};
                }

                if(!empty($list_referensi)){
                    $arrReferensi = $list_referensi->Referensi->{'Buku Referensi'};
                }

                if(!empty($list_pandik)){
                    $arrPandik = $list_pandik->{'Panduan Pendidik'}->{'Buku Pandik'};
                }

                if(!empty($list_product_it)){
                    $arrProductIt = $list_product_it->{'Produk IT'}->{'Produk IT'};
                }

                if(!empty($list_product_covid)){
                    $arrProductCovid = $list_product_covid->{'Produk Covid'}->{'Produk Covid'};
                }

                if(!empty($list_alat_tulis)){
                    $arrAlatTulis = $list_alat_tulis->{'Alat Tulis'}->{'Alat Tulis'};
                }

                if(!empty($list_smart_library)){
                    $arrSmartLibrary = $list_smart_library->{'Smart Library'}->{'Smart Library'};
                }

                // print_r($list_pengayaan);

                // $list_all = array_merge($list_wajib, $list_literasi->Literasi->{'Buku Literasi'}, $list_pengayaan->Pengayaan->{'Buku Pengayaan'}, $list_referensi->Referensi->{'Buku Referensi'}, $list_pandik->{'Panduan Pendidik'}->{'Buku Pandik'});
                $list_all = array_merge($list_wajib, $arrLiterasi, $arrPengayaan, $arrReferensi, $arrPandik, $arrProductIt, $arrProductCovid, $arrAlatTulis, $arrSmartLibrary);

                // foreach (json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/all_teks_konfirmasi.json', false, stream_context_create(arrSSLContext()))) as $bukubos) {
                foreach ($list_all as $bukubos) {
                    $jml = $this->input->post('qty-'.$bukubos->id_product);
                    $qty = is_numeric($jml) ? $jml : 0;
                    $type = $bukubos->type_alias ?? $bukubos->type;
                    if ($qty > 0) {
                        $pesanan[$count] = [
                            'product_id' => $bukubos->id_product,
                            'kode_buku' => $bukubos->kode_buku,
                            'product_name' => $bukubos->name,
                            'isbn' => $bukubos->isbn,
                            'type' => $type,
                            'category' => $bukubos->category,
                            'product_quantity' => $qty,
                            'unit_price' => $bukubos->$price,
                            'total_price' => ceil($bukubos->$price) * $qty,
                        ];
                        $count++;
                        $totalPay += ceil($bukubos->$price) * $qty;
                    }
                }

                if(count($pesanan) <= 0)
                {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Gagal menambahkan buku.',
                    ];
                    echo json_encode($callBack, true);
                    exit();
                }

                $check_reference_other = $this->mod_general->detailData('orders', 'id_order', $idOrder);
                if($check_reference_other['reference_other'] != null)
                {

                    if($check_reference_other['reference_other_from'] == 'Siplah.id')
                    {
                        foreach($pesanan as $orders)
                        {
                            $kode_pesanan = $check_reference_other['reference_other'];
                            $kode_buku = $orders['kode_buku'];
                            $qty = $orders['product_quantity'];
                            $update = false;
                            if($this->add_item_siplah($kode_pesanan, $kode_buku, $qty, $idOrder, $update))
                            {
                                $proc1 = $this->m_pesanan->tambahDetailPesanan2020($orders, $idOrder);
                                $idOrderDetail = $this->db->insert_id();
                                if ($proc1) {
                            // update tabel orders
                                    $queryTotal = $this->db->query("SELECT SUM(`total_price`) AS `total` FROM `order_detail` WHERE `id_order`=".$this->db->escape($idOrder));
                                    $totalPaid = $queryTotal->row('total');
                                    $data2 = ['total_paid' => $totalPaid];
                                    $proc2 = $this->mod_general->updateData('orders', $data2, 'id_order', $idOrder);
                                    if ($proc2) {
                                    // insert ke tabel order_detail_revisi
                                        $data_detail_history = [
                                            'id_order' => $idOrder,
                                            'id_order_detail' => $idOrderDetail,
                                            'product_id' => $orders['product_id'],
                                            'product_name' => $orders['product_name'],
                                            'quantity_before' => 0,
                                            'quantity_after' => $orders['product_quantity'],
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'created_by' => $this->adm_id,
                                        ];
                                        $proc_detail_history = $this->mod_general->addData('order_detail_history', $data_detail_history);

                                        $callBack = [
                                            'success' => 'true',
                                            'message' => 'Data successfully updated.',
                                            'redirect' => 'orders/detail/'.$idOrder,
                                        ];
                                        $this->session->set_flashdata('msg_success', 'Data pesanan buku berhasil <b>DITAMBAH</b></p>');
                                    } else {
                                        $callBack = [
                                            'success' => 'false',
                                            'message' => 'Failed to update data.',
                                        ];
                                        echo json_encode($callBack, true);
                                        exit;
                                    }
                                } else {
                                    $callBack = [
                                        'success' => 'false',
                                        'message' => 'Failed to update data.',
                                    ];
                                }
                            }
                            else
                            {
                                $callBack = [
                                    'success' => 'false',
                                    'message' => 'Failed to insert data to siplah.',
                                ];
                                echo json_encode($callBack, true);
                                exit;
                            }
                        }
                    }
                }
                else
                {
                    // $proc1 = $this->m_pesanan->tambahDetailPesanan($pesanan, $idOrder);
                    // if ($proc1) {
                    // // update tabel orders
                    //     $queryTotal = $this->db->query("SELECT SUM(`total_price`) AS `total` FROM `order_detail` WHERE `id_order`=".$this->db->escape($idOrder));
                    //     $totalPaid = $queryTotal->row('total');
                    //     $data2 = ['total_paid' => $totalPaid];
                    //     $proc2 = $this->mod_general->updateData('orders', $data2, 'id_order', $idOrder);
                    //     if ($proc2) {
                    //         $callBack = [
                    //             'success' => 'true',
                    //             'message' => 'Data successfully updated.',
                    //             'redirect' => 'orders/detail/'.$idOrder,
                    //         ];
                    //         $this->session->set_flashdata('msg_success', 'Data pesanan buku berhasil <b>DITAMBAH</b></p>');
                    //     } else {
                    //         $callBack = [
                    //             'success' => 'false',
                    //             'message' => 'Failed to update data.',
                    //         ];
                    //         echo json_encode($callBack, true);
                    //         exit;
                    //     }
                    // } else {
                    //     $callBack = [
                    //         'success' => 'false',
                    //         'message' => 'Failed to update data.',
                    //     ];
                    // }

                    foreach($pesanan as $orders)
                    {
                        $kode_pesanan = $check_reference_other['reference_other'];
                        $kode_buku = $orders['kode_buku'];
                        $qty = $orders['product_quantity'];

                            $proc1 = $this->m_pesanan->tambahDetailPesanan2020($orders, $idOrder);
                            $idOrderDetail = $this->db->insert_id();
                            if ($proc1) {
                            // update tabel orders
                                $queryTotal = $this->db->query("SELECT SUM(`total_price`) AS `total` FROM `order_detail` WHERE `id_order`=".$this->db->escape($idOrder));
                                $totalPaid = $queryTotal->row('total');
                                $data2 = ['total_paid' => $totalPaid];
                                $proc2 = $this->mod_general->updateData('orders', $data2, 'id_order', $idOrder);
                                if ($proc2) {
                                    // insert ke tabel order_detail_revisi
                                    $data_detail_history = [
                                        'id_order' => $idOrder,
                                        'id_order_detail' => $idOrderDetail,
                                        'product_id' => $orders['product_id'],
                                        'product_name' => $orders['product_name'],
                                        'quantity_before' => 0,
                                        'quantity_after' => $orders['product_quantity'],
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'created_by' => $this->adm_id,
                                    ];
                                    $proc_detail_history = $this->mod_general->addData('order_detail_history', $data_detail_history);

                                    $callBack = [
                                        'success' => 'true',
                                        'message' => 'Data successfully updated.',
                                        'redirect' => 'orders/detail/'.$idOrder,
                                    ];
                                    $this->session->set_flashdata('msg_success', 'Data pesanan buku berhasil <b>DITAMBAH</b></p>');
                                } else {
                                    $callBack = [
                                        'success' => 'false',
                                        'message' => 'Failed to update data.',
                                    ];
                                    echo json_encode($callBack, true);
                                    exit;
                                }
                            } else {
                                $callBack = [
                                    'success' => 'false',
                                    'message' => 'Failed to update data.',
                                ];
                            }
                    }
                }
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    public function sendMailBulk()
    {
        $raw_queryMail = "
        SELECT  a.`id_customer`, b.`reference`,a.`school_name`, a.`email`, a.`email_kepsek`, a.`email_operator`
        FROM    `customer` a
        INNER JOIN `orders` b ON b.`id_customer`=a.`id_customer`
        WHERE   (a.`id_customer` IN (SELECT `id_customer` FROM `orders` WHERE `current_state`>= ?)) AND b.`date_add` < ? 
        ORDER BY b.`date_add` ASC";

        $queryMail = $this->db->query($raw_queryMail, [3, '2016-08-05 12:00:00']);
        /*
        $arrEmail = [);
        $arrRef = [);
        foreach ($q->result() as $row){
            if(!empty($row->email))
                array_push($arrEmail, $row->email);
            if(!empty($row->email_kepsek) && $row->email_kepsek<>$row->email)
                array_push($arrEmail, $row->email_kepsek);
            if(!empty($row->email_operator) && $row->email_operator<>$row->email)
                array_push($arrEmail, $row->email_operator);
        }
        foreach ($q->result() as $r){
            // array_push($arrRef, $r->reference);
            $attachment = $this->genPdf($r->reference);
            echo 'Generate PDF Order #'.$r->reference.'<br />';
        }
        */
        $no = 1;

        /* Ditutup. Fa, 20200319
        foreach ($queryMail->result() as $r) {
            $arrEmail = [];
            if ( ! empty($r->email)) {
                $arrEmail[] = $r->email;
            }
            if ( ! empty($r->email_kepsek) && $r->email_kepsek != $r->email) {
                $arrEmail[] = $r->email_kepsek;
            }
            if ( ! empty($r->email_operator) && $r->email_operator != $r->email) {
                $arrEmail[] = $r->email_operator;
            }
            $subject = 'Silahkan unduh, cetak, lengkapi dan tanda tangani surat pernyataan';
            $to = $arrEmail;
            $content = '<p>Yang terhormat Bapak/Ibu Kepala Sekolah,<br /><br /></p>
            <p>Kami sudah menerima pesanan buku sekolah Bapak/Ibu melalui website kami di <a href="http://bukusekolah.gramedia.com" target="_blank">http://bukusekolah.gramedia.com</a>.</p>
            <p>Pesanan Bapak/Ibu sudah tercatat di server <b>Lembaga Kebijakan Pengadaan Barang/Jasa Pemerintah</b> dan <b>Kementerian Pendidikan dan Kebudayaan</b>.</p>
            <p>Saat ini pesanan Bapak/Ibu sedang kami siapkan, oleh karena itu untuk melengkapi berkas administrasi penagihan dan laporan pertanggungjawaban dana BOS, kami mohon kerjasamanya untuk dapat melengkapi dan menandatangani <b>Surat Pernyataan Kesanggupan</b> yang kami <b>lampirkan</b> pada email ini.</b></p><p>Terima kasih atas kepercayaan Bapak/Ibu kepada kami.<br /><br />Salam,<br /><br /><br /><b>PT. Gramedia</b></p>';
            $attachment = $this->genPdf($r->reference);
            $this->load->library('mymail');
            $this->mymail->send($subject, $to, $content, $attachment);
            echo $no.". Email untuk kode pesanan #".$r->reference." telah dikirim.<br />";
            $no++;
        } */
    }

    public function genKodePesanan($qty = 1)
    {
        for ($i = 1; $i <= $qty; $i++) {
            $kode = generateRandomString();
            echo 'Kode Pesanan: '.$kode.'<br />';
        }
    }

    public function offline()
    {
        $data['page_title'] = 'Pesanan Offline | '.date('Y-m-d_His');
        $data['total_omset'] = $this->mod_order->getOmset($this->adm_id, true)[0]['total_omset'];
        $data['order_terbuat'] = $this->mod_order->getCreatedOrder($this->adm_id, true)[0]['order_terbuat'];
        $data['order_terkonfirmasi'] = $this->mod_order->getConfirmedOrder($this->adm_id,
            true)[0]['order_terkonfirmasi'];
        $data['is_operator'] = false;
        if ($this->adm_level == 4) {
            $this->_output['content'] = $this->load->view('admin/orders/sales_list_offline', $data, true);
        } elseif (in_array($this->adm_level, array_merge($this->backoffice_admin_area, [3, 8]))) {
            $this->_output['content'] = $this->load->view('admin/orders/list_offline', $data, true);
        } else {
            $this->_output['content'] = $this->load->view('admin/orders/no_access', $data, true);
        }
        $this->_output['script_js'] = $this->load->view('admin/orders/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function offlineAdd()
    {
        if (env('ORDER_OFFLINE') == 'true') {
            $data['provinsi'] = $this->mod_general->getAll('customer', 'distinct(provinsi)', '', 'provinsi ASC');
            $this->_output['content'] = $this->load->view('admin/orders/add_school_offline', $data, true);
            $this->_output['script_css'] = $this->load->view('admin/orders/css', '', true);
            $this->_output['script_js'] = $this->load->view('admin/orders/js', '', true);
            $this->load->view('admin/template', $this->_output);
        } else {
            redirect(base_url().ADMIN_PATH.'/orders/offline', 'refresh');
        }
    }

    # TODO : add logs and block permission for auditor
    public function offlineSchoolPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        try {
            if (in_array($this->adm_level, $this->auditor_area)) {
                $callBack = [
                    "success" => "false",
                    "message" => "Maaf, anda tidak dapat melakukan proses ini.",
                ];
                echo json_encode($callBack, true);
            } else {
                $dataSchool = [
                    'no_npsn' => $this->input->post('reg_no_npsn', true),
                    'school_name' => $this->input->post('reg_school_name', true),
                    'jenjang' => $this->input->post('reg_jenjang'),
                    'bentuk' => ($this->input->post('reg_jenjang') == '1-6') ? 'SD' : (($this->input->post('reg_jenjang') == '7-9') ? 'SMP' : 'SMA/SMK'),
                    'zona' => $this->mod_general->getZonaByKabupaten($this->input->post('reg_kabupaten', true)),
                    'user_k13' => $this->input->post('reg_user_k13'),
                    'provinsi' => $this->input->post('reg_provinsi', true),
                    'kabupaten' => $this->input->post('reg_kabupaten', true),
                    'kecamatan' => $this->input->post('reg_kecamatan'),
                    'desa' => $this->input->post('reg_desa'),
                    'alamat' => $this->input->post('reg_alamat', true),
                    'kodepos' => $this->input->post('reg_kodepos'),
                    'phone' => $this->input->post('reg_phone', true),
                    'email' => $this->input->post('reg_email'),
                    'name' => $this->input->post('reg_kepsek_name', true),
                    'nip_kepsek' => $this->input->post('reg_kepsek_nip'),
                    'phone_kepsek' => $this->input->post('reg_kepsek_phone'),
                    'email_kepsek' => $this->input->post('reg_kepsek_email'),
                    'nama_bendahara' => $this->input->post('reg_bendahara_name'),
                    'nip_bendahara' => $this->input->post('reg_bendahara_nip'),
                    'phone_bendahara' => $this->input->post('reg_bendahara_phone'),
                    'operator' => $this->input->post('reg_operator_name'),
                    'email_operator' => $this->input->post('reg_operator_email'),
                    'hp_operator' => $this->input->post('reg_operator_phone'),
                    'is_offline' => 1,
                    'date_add' => date('Y-m-d H:i:s'),
                ];
                if ( ! $this->isSchoolExist($dataSchool['no_npsn'])) {
                    if ( ! $this->isSchoolNameExist($dataSchool['school_name'])) {
                        $proc = $this->mod_general->addData('customer', $dataSchool);
                        if ($proc) {
                            $idCustomer = $proc;
                            $jenjang = $dataSchool['jenjang'];
                            $zona = $dataSchool['zona'];
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Data successfully added.',
                                'redirect' => 'orders/offlineBooksAdd/'.$idCustomer.'/'.$jenjang.'/'.$zona,
                            ];
                            $this->session->set_flashdata('msg_success',
                                'Data sekolah berhasil disimpan, silahkan masukkan jumlah pesanan buku.');
                        } else {
                            $callBack = [
                                'success' => 'false',
                                'message' => 'Gagal menyimpan data sekolah :(',
                            ];
                        }
                        echo json_encode($callBack, true);
                    } else {
                        $idCustomer = $this->isSchoolNameExist($dataSchool['school_name']);
                        $customer = $this->mod_general->detailData('customer', 'id_customer', $idCustomer);
                        $jenjang = $dataSchool['jenjang'];
                        $zona = $dataSchool['zona'];
                        $dataSchoolUpdate = [
                            'no_npsn' => $this->input->post('reg_no_npsn', true),
                            'zona' => $zona,
                            'jenjang' => $jenjang,
                            'user_k13' => $customer['user_k13'] ?: $this->input->post('reg_user_k13'),
                            'alamat' => $customer['alamat'] ?: $this->input->post('reg_alamat',
                                true),
                            'kodepos' => $customer['kodepos'] ?: $this->input->post('reg_kodepos'),
                            'phone' => $customer['phone'] ?: $this->input->post('reg_phone', true),
                            'email' => $customer['email'] ?: $this->input->post('reg_email'),
                            'name' => $customer['name'] ?: $this->input->post('reg_kepsek_name',
                                true),
                            'nip_kepsek' => $customer['nip_kepsek'] ?: $this->input->post('reg_kepsek_nip'),
                            'phone_kepsek' => $customer['phone_kepsek'] ?: $this->input->post('reg_kepsek_phone'),
                            'email_kepsek' => $customer['email_kepsek'] ?: $this->input->post('reg_kepsek_email'),
                            'nama_bendahara' => $customer['nama_bendahara'] ?: $this->input->post('reg_bendahara_name'),
                            'nip_bendahara' => $customer['nip_bendahara'] ?: $this->input->post('reg_bendahara_nip'),
                            'phone_bendahara' => $customer['phone_bendahara'] ?: $this->input->post('reg_bendahara_phone'),
                            'operator' => $customer['operator'] ?: $this->input->post('reg_operator_name'),
                            'email_operator' => $customer['email_operator'] ?: $this->input->post('reg_operator_email'),
                            'hp_operator' => $customer['hp_operator'] ?: $this->input->post('reg_operator_phone'),
                        ];
                        $this->mod_general->edit('customer', $dataSchoolUpdate,
                            $where = 'id_customer = '.$customer['id_customer']);
                        $callBack = [
                            'success' => 'true',
                            'message' => 'Data successfully updated.',
                            'redirect' => 'orders/offlineBooksAdd/'.$idCustomer.'/'.$jenjang.'/'.$zona,
                        ];
                        $this->session->set_flashdata('msg_success',
                            'Data sekolah berhasil disimpan, silahkan masukkan jumlah pesanan buku.');
                        echo json_encode($callBack, true);
                    }
                } else {
                    $idCustomer = $this->isSchoolExist($dataSchool['no_npsn']);
                    $customer = $this->mod_general->detailData('customer', 'id_customer', $idCustomer);
                    $jenjang = $dataSchool['jenjang'];
                    $zona = $dataSchool['zona'];
                    $dataSchoolUpdate = [
                        'zona' => $zona,
                        'jenjang' => $jenjang,
                        'user_k13' => $customer['user_k13'] ?: $this->input->post('reg_user_k13'),
                        'alamat' => $customer['alamat'] ?: $this->input->post('reg_alamat', true),
                        'kodepos' => $customer['kodepos'] ?: $this->input->post('reg_kodepos'),
                        'phone' => $customer['phone'] ?: $this->input->post('reg_phone', true),
                        'email' => $customer['email'] ?: $this->input->post('reg_email'),
                        'name' => $customer['name'] ?: $this->input->post('reg_kepsek_name', true),
                        'nip_kepsek' => $customer['nip_kepsek'] ?: $this->input->post('reg_kepsek_nip'),
                        'phone_kepsek' => $customer['phone_kepsek'] ?: $this->input->post('reg_kepsek_phone'),
                        'email_kepsek' => $customer['email_kepsek'] ?: $this->input->post('reg_kepsek_email'),
                        'nama_bendahara' => $customer['nama_bendahara'] ?: $this->input->post('reg_bendahara_name'),
                        'nip_bendahara' => $customer['nip_bendahara'] ?: $this->input->post('reg_bendahara_nip'),
                        'phone_bendahara' => $customer['phone_bendahara'] ?: $this->input->post('reg_bendahara_phone'),
                        'operator' => $customer['operator'] ?: $this->input->post('reg_operator_name'),
                        'email_operator' => $customer['email_operator'] ?: $this->input->post('reg_operator_email'),
                        'hp_operator' => $customer['hp_operator'] ?: $this->input->post('reg_operator_phone'),
                    ];
                    $this->mod_general->edit('customer', $dataSchoolUpdate,
                        $where = 'id_customer = '.$customer['id_customer']);
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.',
                        'redirect' => 'orders/offlineBooksAdd/'.$idCustomer.'/'.$jenjang.'/'.$zona,
                    ];
                    $this->session->set_flashdata('msg_success',
                        'Data sekolah berhasil disimpan, silahkan masukkan jumlah pesanan buku.');
                    echo json_encode($callBack, true);
                }
            }
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    private function isSchoolExist($npsn)
    {
        if ( ! $npsn) {
            return false;
        }
        $qCustomer = $this->db->query("SELECT `id_customer` FROM `customer` WHERE `no_npsn`=".$this->db->escape($npsn));
        $resultCustomer = $qCustomer->row('id_customer');
        if ($resultCustomer) {
            return $resultCustomer;
        }

        return false;
    }

    private function isSchoolNameExist($school_name)
    {
        if ( ! $school_name) {
            return false;
        }
        $qSchool = $this->db->query("SELECT `id_customer` FROM `customer` WHERE `school_name`=".$this->db->escape($school_name));
        $rSchool = $qSchool->row('id_customer');
        if ($rSchool) {
            return $rSchool;
        }

        return false;
    }

    public function offlineBooksAdd($idCustomer, $jenjang, $zona)
    {
        if ($idCustomer && $jenjang && $zona) {
            $data['id_customer'] = $idCustomer;
            $data['jenjang'] = $jenjang;
            $data['zona'] = $zona;
            $data['customer'] = $this->mod_general->detailData('customer', 'id_customer', $idCustomer);
            $data['listbooks'] = [];
            $books1 = [];
            $books2 = [];
            // BUKU KURIKULUM 2013
            $list1 = $this->mod_order->getListBooksOffline($zona, $jenjang);
            foreach ($list1 as $datas1) {
                $count1[$datas1['category_id']] = 0;
            }
            foreach ($list1 as $row1) {
                $books1[$row1['type']][$row1['kelas']][$count1[$row1['category_id']]] = $row1;
                $count1[$row1['category_id']]++;
            }
            // BUKU KTSP
            $list2 = $this->mod_order->getListBooksOfflineKTSP($zona, $jenjang);
            foreach ($list2 as $datas2) {
                $count2[$datas2['category_id']] = 0;
            }
            foreach ($list2 as $row2) {
                $books2[$row2['type']][$row2['kelas']][$count2[$row2['category_id']]] = $row2;
                $count2[$row2['category_id']]++;
            }
            $data['listbooks'] = array_merge($books1, $books2);
            $this->_output['content'] = $this->load->view('admin/orders/books_offline', $data, true);
            $this->_output['script_js'] = $this->load->view('admin/orders/js', '', true);
            $this->load->view('admin/template', $this->_output);
        } else {
            redirect(ADMIN_PATH.'/orders/offline', 'refresh');
        }
    }

    # TODO : add logs and block permission for auditor
    public function offlineBooksPost()
    {
        if ( ! $this->input->is_ajax_request()) 
        {
            return false;
        }

        try 
        {
            if (in_array($this->adm_level, $this->auditor_area)) 
            {
                $callBack = [
                    "success" => "false",
                    "message" => "Maaf, anda tidak dapat melakukan proses ini.",
                ];
            } 
            else 
            {
                $this->load->model('mod_pesanan', 'm_pesanan');
                $idCustomer = $this->input->post('id_customer');
                $pesanan = [];
                $count = 0;
                $zona = $this->input->post('zona');
                $price = 'price_'.$zona;
                $dataKorwil = $this->mod_order->getKorwil($this->input->post('kabupaten'))[0];
                $updateData = [
                    'korwil_email' => $dataKorwil['email'],
                    'korwil_name' => $dataKorwil['name'],
                    'korwil_phone' => $dataKorwil['telp'],
                ];
                foreach (json_decode(file_get_contents(base_url()."/assets/data/json/".$this->input->post('jenjang')."/all_teks_konfirmasi.json",
                    false, stream_context_create(arrSSLContext()))) as $bukubos) 
                {
                    $jml = $this->input->post('qty-'.$bukubos->id_product);
                    $qty = is_numeric($jml) ? $jml : 0;
                    $type = $bukubos->type_alias ?? $bukubos->type;
                    if ($qty > 0) 
                    {
                        $pesanan[$bukubos->type_id][$bukubos->category_id]['pesanan'][$count] = [
                            'product_id' => $bukubos->id_product,
                            'kode_buku' => $bukubos->kode_buku,
                            'product_name' => $bukubos->name,
                            'isbn' => $bukubos->isbn,
                            'type' => $type,
                            'category' => $bukubos->category,
                            'product_quantity' => $qty,
                            'unit_price' => $bukubos->$price,
                            'total_price' => ceil($bukubos->$price) * $qty,
                        ];
                        $count++;
                        if (array_key_exists('total', $pesanan[$bukubos->type_id][$bukubos->category_id])) 
                        {
                            $pesanan[$bukubos->type_id][$bukubos->category_id]['total'] += ceil($bukubos->$price) * $qty;
                        } 
                        else 
                        {
                            $pesanan[$bukubos->type_id][$bukubos->category_id]['total'] = ceil($bukubos->$price) * $qty;
                        }
                    }
                }

                $this->db->trans_begin();
                if ($pesanan) 
                {
                    $kodePesanan = [];
                    $this->session->set_userdata('id_customer_offline', $idCustomer);
                    foreach ($pesanan as $category => $data) 
                    {
                        foreach ($data as $class => $value) 
                        {
                            $order = $value['pesanan'];
                            $orderReference = generateRandomString();
                            $orderDetail = [];
                            $orderDetail['totalPay'] = $value['total'];
                            foreach ($order as $detail) 
                            {
                                $orderDetail['category'] = $detail['category'];
                                $orderDetail['type'] = $detail['type'];
                            }
                            $id_order = $this->m_pesanan->tambahPesanan($orderReference, $orderDetail, true);
                            $proc = $this->m_pesanan->tambahDetailPesanan($value['pesanan'], $id_order);
                            if ($proc) 
                            {
                                $this->m_pesanan->editPesanan($id_order, $updateData);
                                $kodePesanan[] = $orderReference;
                            }
                        }
                    }
                    if ($this->db->trans_status() == true) 
                    {
                        $this->db->trans_commit();
                        $jmlPesanan = count($kodePesanan);
                        $kodePesanan = implode(', ', $kodePesanan);
                        $this->session->unset_userdata('id_customer_offline');
                        $this->session->set_flashdata('msg_success',
                            $jmlPesanan.' Data pesanan buku berhasil dengan kode: <b>'.$kodePesanan.'</b> berhasil <b>DIBUAT</b></p>');
                        $callBack = [
                            'success' => 'true',
                            'message' => 'Data successfully inserted.',
                            'redirect' => 'orders/offline',
                        ];
                    } 
                    else 
                    {
                        $this->db->trans_rollback();
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Failed to insert data.',
                        ];
                    }
                } 
                else 
                {
                    $this->db->trans_rollback();
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Failed to insert data.',
                    ];
                }
            }
            echo json_encode($callBack, true);
        } 
        catch (Exception $e) 
        {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    public function realisasiPesanan($id_order)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $data['id_order'] = $id_order;
        $data['listproducts'] = $this->mod_order->getListProducts($id_order);
        $this->load->view('admin/orders/realisasi_popup', $data);
    }

        # TODO : add logs and block permission for auditor
    public function updateRealisasiBooksPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        if (in_array($this->adm_level, $this->auditor_area)) {
            $callBack = [
                "success" => "false",
                "message" => "Maaf, anda tidak dapat melakukan proses ini.",
            ];
        } else {
            $key = $this->input->post('id');
            $realisasi = $this->input->post('realisasi');
            $i = 0;
            foreach ($key as $val) {
                $this->db->where('id_order_detail', $val);
                $this->db->update('order_detail', ['quantity_fullfil' => $realisasi[$i]]);
                $i++;
            }
            $this->session->set_flashdata('msg_success', 'Data realisasi pesanan buku berhasil <b>DIPERBARUI</b></p>');
            $callBack = ['success' => 'true'];
        }
        echo json_encode($callBack, true);
    }

    public function autoComplete($type)
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->mod_order->getAuto($type, $q);
        }
    }

        # TODO : add logs and block permission for auditor
    public function updateDataRealisasi()
    {

        if (in_array($this->adm_level, $this->auditor_area)) {
            $callBack = [
                "success" => "false",
                "message" => "Maaf, anda tidak dapat melakukan proses ini.",
            ];
            return $callBack;
        }

        $qOrder = $this->db->query('SELECT `b`.`id_order`, `a`.* FROM `temp_realisasi` `a` INNER JOIN `orders` `b` ON `b`.`reference`=a.`kode_pesanan`');
        if ($qOrder->num_rows() > 0) {
            foreach ($qOrder->result() as $row) {
                echo 'UPDATE id_order:'.$row->id_order.', kode_buku:'.$row->kode_buku.', realisasi: '.$row->realisasi.'<br />';
                $data = ['quantity_fullfil' => $row->realisasi];
                $this->db->where('id_order', $row->id_order);
                $this->db->where('kode_buku', $row->kode_buku);
                $this->db->update('order_detail', $data);
            }
            $qOrder->free_result();
        }
    }

    public function getKabupatenByProvinsi()
    {
        $provinsi = $this->input->post('provinsi');
        if ($provinsi) {
            $kabupaten = $this->mod_general->getAll('customer', 'distinct(kabupaten)', 'provinsi = "'.$provinsi.'"',
                'kabupaten ASC');
            $callBack = [
                "row" => $kabupaten,
                "success" => true,
            ];
            echo json_encode($callBack);
        } else {
            $callBack = ['success' => false];
            echo json_encode($callBack);
        }
    }

    public function getKecamatanByKabupaten()
    {
        $kabupaten = $this->input->post('kabupaten');
        if ($kabupaten) {
            $kecamatan = $this->mod_general->getAll('customer', 'distinct(kecamatan)', 'kabupaten = "'.$kabupaten.'"',
                'kecamatan ASC');
            $callBack = [
                "row" => $kecamatan,
                "success" => true,
            ];
            echo json_encode($callBack);
        } else {
            $callBack = ["success" => false];
            echo json_encode($callBack);
        }
    }

    public function checkDupplicateValidation()
    {
        echo json_encode($this->getCheckExistClientSide($this->input->post('values'), $this->input->post('table'),
            $this->input->post('select')));
    }

    public function getCheckExistClientSide($inputPost, $tableName, $select, $field = null, $value = null)
    {
        $inputPost = strtolower($inputPost);
        $checkExist = null;
        if ($field == null && $value == null) {
            $checkExist = $this->mod_general->checkExist($tableName, $select, $inputPost);
        } else {
            $checkExist = $this->mod_general->checkExist($tableName, $select, $inputPost, $field, $value);
        }
        if ($checkExist > 0) {
            $data = false;
        } else {
            $data = true;
        }

        return $data;
    }

    public function cekNPSN()
    {
        $npsn = $this->input->post('cek_no_npsn');
        $checkExist = $this->mod_general->getList("customer", "no_npsn", "no_npsn = ".$npsn);
        $callBack = ['is_exist' => $checkExist];
        echo json_encode($callBack);
    }

    public function checkStatusBayar()
    {
            // $id_customer = '305';
        $id_customer = $this->input->post('id_customer');
        $query = $this->mod_order->checkStatusBayar($id_customer);
            // echo $this->db->last_query();
        echo json_encode($query->result());
    }
      
    public function getDataEmailBlacklist($mail_to) 
    {
        $bl_email = array();

        // array email recipient
        $to_email = $mail_to;

        // get email blacklist from tabel email_blacklist
        $query = $this->Mod_general->getWhereIn('email_blacklist', 'email', 'email', $to_email);
        if($query)
        {
            foreach ($query as $d) {
                $bl_email[]=$d['email'];
            }
        }

        // remove email blacklist from email recipient
        $result = array_diff(
            $to_email, 
            $bl_email
        );

        $data = array_merge($result);
        return $data;
    }

    public function add_item_siplah($kode_pesanan, $kode_buku, $qty, $idOrder, $update)
    {
        $dbsiplah = $this->load->database('dbsiplah', true);

        $url = getenv('SIPLAH_API_UPDATE'); 
        $key_api = getenv('SIPLAH_API_KEY');
        $seller_id = '89';
        $attribute_id_name='73';
        $attribute_id_price='77';

        /**
         * MENDAPATKAN KODE BUKU
         * AWAL
         */
        $check_kode_buku = $dbsiplah->query('SELECT * FROM `catalog_product_entity_int` a INNER JOIN catalog_product_entity b ON a.`entity_id`=b.`entity_id` WHERE a.attribute_id=138 and a.`value`=89 and b.sku like "'.$kode_buku.'%"');
        
        if($check_kode_buku->num_rows() == 0)
        {
            $callBack = [
                'success' => 'false',
                'message' => 'Gagal Menambahkan buku. Buku belum terdaftar di siplah',
                'redirect' => 'orders/detail/'.$idOrder,
            ];
            $this->session->set_flashdata('msg_error', 'Gagal Menambahkan buku. Buku belum terdaftar di siplah');
            echo json_encode($callBack, true);
            exit();
        }
        elseif($check_kode_buku->num_rows() > 1)
        {
            $kode_buku = $check_kode_buku->result_array()[0]['sku'];
        }
        else
        {
            $kode_buku = $check_kode_buku->row_array()['sku'];
        }

        if($kode_buku == null || $kode_buku =="")
        {
            return false;
        }
         
        /**
         * MENDAPATKAN CUSTOMER GROUP
         */
        $group_id = $dbsiplah->query('select group_id from `customer_entity` where entity_id=(SELECT customer_id FROM sales_order WHERE increment_id='.$kode_pesanan.')')->row_array()['group_id'];

        /**
         * MENDAPATKAN DATA PRODUK
         */
        $query_item = $dbsiplah->select('a.`entity_id`, a.`type_id`, a.`sku`, b.`value`, d.`value` AS original_price')
                    ->from('`catalog_product_entity` a')
                    ->join('`catalog_product_entity_varchar` b','b.`entity_id`=a.`entity_id`','inner')
                    ->join('`eav_attribute` c','c.`attribute_id`=b.`attribute_id`','inner')
                    ->join('`catalog_product_entity_decimal` d', 'd.`entity_id`=a.`entity_id`', 'inner')
                    ->where('a.sku', $kode_buku)
                    ->where('b.`attribute_id`', $attribute_id_name)
                    ->where('d.`attribute_id`', $attribute_id_price)
                    ->order_by('b.store_id desc')
                    ->get();

        $data_item = $query_item->result_array();

        $original_price = $data_item[0]['original_price'];

        /**
         * MENDAPATKAN HARGA ZONA
         */
        $entity_id = $data_item[0]['entity_id'];
        $query_index_price = $dbsiplah->select('*')
                            ->from('catalog_product_index_price')
                            ->where('entity_id', $entity_id)
                            ->where('customer_group_id', $group_id)
                            ->get()
                            ->row_array();

        $query_tier_price = $dbsiplah->select('*')
                            ->from('catalog_product_entity_tier_price')
                            ->where('entity_id', $entity_id)
                            ->where('customer_group_id', $group_id)
                            ->get()
                            ->row_array();


        if($query_index_price)
        {
            $price = $query_index_price['tier_price'];
        }
        elseif($query_tier_price)
        {
            $price = $query_tier_price['value'];
        }
        else
        {
            $price = $original_price;
        }

        $no_po = $kode_pesanan;
        $no_item = ''; // IF(no_item=''){echo 'insert';}elseif(no_item != ''){echo 'update';} 
        $sku = $data_item[0]['sku'];
        $product_id = $entity_id;
        $name_product = $data_item[0]['value'];

        if($update == true)
        {
            $no_item = $this->get_no_item($no_po, $product_id, $dbsiplah);

            if($no_item == "" || $no_item == null || empty($no_item))
            {
                return false;
            }
        }

        $data = array(
            'no_po'          => $no_po,
            'no_item'        => $no_item,
            'price'          => $price,
            'original_price' => $original_price,
            'sku'            => $sku,
            'product_id'     => $product_id,
            'qty'            => $qty,
            'key_api'        => $key_api,
            'seller_id'      => $seller_id,
            'nama_product'   => $name_product
        );

        // print_r($data);

        $send = $this->my_curl($url, $data);


        if(trim($send) == trim("berhasil insert"))
        {
            return true;
        }
        elseif(trim(json_decode($send, true)['message']) == trim("berhasil_update"))
        {
            return true;
        }
        else
        {
            return false;
        }
        return false;
    }

    public function get_no_item($no_po, $product_id, $dbsiplah)
    {
        $order_id = $dbsiplah->select('entity_id')
                                ->from('sales_order')
                                ->where('increment_id', $no_po)
                                ->get()
                                ->row_array()['entity_id'];

        $item_id = $dbsiplah->select('item_id')
                                ->from('sales_order_item')
                                ->where('order_id', $order_id)
                                ->where('product_id', $product_id)
                                ->get()
                                ->row_array()['item_id'];

        return $item_id;
    }

    public function my_curl($url, $data){
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $output = curl_exec($ch); 

        if($output == false)
        {
            // return curl_error($ch);
            $errno = curl_errno($ch);
            $error_message = curl_strerror($errno);
            $messages =  "cURL error ({$errno}): {$error_message}";
            $callBack = [
                'success' => 'false',
                'message' => $messages,
            ];
            echo json_encode($callBack, true);
            exit;
        }
        curl_close($ch);   
        return $output;
    }

    public function print_pesanan_siplah($no_po)
    {
        $dbsiplah = $this->load->database('dbsiplah', true);

        $order_id = $dbsiplah->query("select entity_id from sales_order where increment_id='".$no_po."'")->row_array()['entity_id'];
        $params = "?nota=".$order_id."&toko=89";

        // echo getenv('SIPLAH_API_PRINT_PO').$params;
        redirect(getenv('SIPLAH_API_PRINT_PO').$params);
        
    }
}



