<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_gudang $mod_gudang
 * @property Mod_smc $mod_scm
 */
class Gudangreceiving extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, $this->backmin_gudang_area)) {
            redirect(BACKMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->load->model('mod_gudang');
        $this->load->model('mod_scm');
    }

    public function check_oef_limit()
    {
        $kode_buku = $this->input->post("kode_buku");
        $no_oef = $this->input->post("no_oef");

        $data = $this->mod_general->getAll('production_order', "*", 'no_oef="'.$no_oef.'" AND kode_buku="'. $kode_buku .'"');
        echo json_encode($data);
    }

    public function index()
    {
        redirect(BACKMIN_PATH . '/Gudangreceiving/list');
    }

    public function list()
    {
        $data['page_title']     = 'List Request Stok';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/receiving/list', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/receiving/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function list_receiving()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
            id_request, 
            total_jumlah, 
            is_tag, 
            IF(is_tag = 1, "Ya", "Tidak") as status_tag, 
            FORMAT(total_jumlah, 0, "de_DE") AS format_jumlah,
            CONCAT("<center>",created_date,"</center>") AS date_add, 
            CASE status WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Dibuat</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diproses</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-warning\'>Menunggu TAG</span></center>") WHEN 4 THEN CONCAT("<center><span class=\'label label-warning\'>SPK Dibuat</span></center>") WHEN 5 THEN CONCAT("<center><span class=\'label label-primary\'>Dikirim Ekspedisi</span></center>") WHEN 6 THEN CONCAT("<center><span class=\'label label-success\'>Selesai</span></center>") WHEN 7 THEN CONCAT("<center><span class=\'label label-danger\'>Dibatalkan</span></center>") END AS status');
        $this->datatables->from('request_stock');
        $this->datatables->where('id_gudang', $this->adm_id_gudang);
        $this->datatables->where('is_intan', 2);
        $this->datatables->where('is_tag', 2);
        $this->datatables->edit_column('status_tag', '<center>$1</center>', 'status_tag');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
        $this->datatables->edit_column('id_request', '<center><a href="' . base_url(BACKMIN_PATH . '/Gudangreceiving/detailreceiving/$1') . '">#$1</a></center>', 'id_request');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/Gudangreceiving/detailreceiving/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_request');
        $this->output->set_output($this->datatables->generate());
    }

    public function detailreceiving($id)
    {
        if ($id && is_numeric($id)) {
            $data['page_title']     = 'Detil Permintaan Stok';
            $data['detail']         = $this->mod_general->detailData('request_stock', 'id_request', $id);
            $data['gudang']         = $this->mod_general->detailData('master_gudang', 'id_gudang', $this->adm_id_gudang);
            $data['listproducts']   = $this->mod_gudang->getListProductByRequestID($id);

            $data['status']         = '';
            switch ($data['detail']['status']) {
                case 1:
                    $data['status'] = 'Dibuat';
                    break;
                case 2:
                    $data['status'] = 'Diproses';
                    break;
                case 3:
                    $data['status'] = 'Menunggu TAG';
                    break;
                case 4:
                    $data['status'] = 'SPK Dibuat';
                    break;
                case 5:
                    $data['status'] = 'Dikirim Ekspedisi';
                    break;
                case 6:
                    $data['status'] = 'Selesai';
                    break;
                case 7:
                    $data['status'] = 'Dibatalkan';
                    break;
            }

            $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/receiving/detil', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/receiving/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/receiving');
        }
    }

    public function add()
    {
        $data['page_title']               = 'Buat Permintaan Stok';
        $data['listBukuSD']               = $this->mod_gudang->getBookLevel('1-6');
        $data['listBukuSMP']              = $this->mod_gudang->getBookLevel('7-9');
        $data['listBukuSMA']              = $this->mod_gudang->getBookLevel('10-12');
        $data['listBukuSMP_ktsp']         = $this->mod_gudang->getBookLevelKTSP('7-9');
        $data['listBukuSMK']              = $this->mod_gudang->getBookLevelSMK('10-12');
        $data['listBukuLiterasi']         = $this->mod_gudang->getBookLiterasi();
        $data['listBukuPengayaan']        = $this->mod_gudang->getBookPengayaan();
        $data['listBukuReferensi']        = $this->mod_gudang->getBookReferensi();
        $data['listBukuPandik']           = $this->mod_gudang->getBookPandik();
        $data['listProductIt']            = $this->mod_gudang->getProductIt();
        $data['listProductCovid']         = $this->mod_gudang->getProductCovid();
        $data['listAlatTulis']            = $this->mod_gudang->getAlatTulis();
        $data['listBukuPendampingK13SD']  = $this->mod_gudang->getBookPendampingK13('1-6');
        $data['listBukuPendampingK13SMP'] = $this->mod_gudang->getBookPendampingK13('7-9');
        $data['listBukuPendampingK13SMA'] = $this->mod_gudang->getBookPendampingK13('10-12');
        $data['listBukuPeminatanSmaMa']   = $this->mod_gudang->getBookPeminatanSmaMA('10-12');
        $data['listBukuHetK13SD']         = $this->mod_gudang->getBookHetK13('1-6');
        $data['listBukuHetK13SMP']        = $this->mod_gudang->getBookHetK13('7-9');
        $data['listBukuHetK13SMA']        = $this->mod_gudang->getBookHetK13('10-12');

        $data['tipeGudang']         = $this->mod_gudang->getAll('master_gudang', 'is_utama', 'id_gudang=' . $this->adm_id_gudang)[0];

        $data['listOef']         = $this->mod_gudang->getListOef($this->adm_id_gudang);

        $data['content']            = $this->load->view(BACKMIN_PATH . '/gudang/receiving/add', $data, true);
        $data['script_js']          = $this->load->view(BACKMIN_PATH . '/gudang/receiving/add_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function addConfirmation()
    {
        $requestDetail['id_produk']     = explode(',', $this->input->post('request_id_produk'));
        $requestDetail['berat']         = explode(',', $this->input->post('request_berat'));
        $requestDetail['jumlah']        = explode(',', $this->input->post('request_jumlah'));
        $requestDetail['no_oef']        = explode(',', $this->input->post('request_no_oef'));

        $data['list_request']   = [];
        $count                  = 0;
        foreach ($requestDetail['id_produk'] as $rows => $id_produk) {
            $data['list_request'][$count]           = $this->mod_gudang->getListProduct('a.id_product AS id_product, a.kode_buku AS kode_buku, a.reference AS isbn, a.name AS judul, a.weight AS weight, b.name AS kelas, c.name AS type', 'id_product=' . $id_produk)[0];
            $data['list_request'][$count]->berat    = $requestDetail['berat'][$count];
            $data['list_request'][$count]->no_oef   = $requestDetail['no_oef'][$count];
            $data['list_request'][$count]->jumlah   = $requestDetail['jumlah'][$count];
            $count++;
        }

        $data['is_tag']         = $this->input->post('is_tags');
        $data['tipeGudang']     = $this->mod_gudang->getAll('master_gudang', 'is_utama', 'id_gudang=' . $this->adm_id_gudang)[0];
        $data['page_title']     = 'Konfirmasi Permintaan Stok';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/receiving/add_konfirmasi', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/receiving/add_js', '', true);
        
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    // public function addConfirmationPost()
    // {
    //     if ( ! $this->input->is_ajax_request()) {
    //         redirect(BACKMIN_PATH . '/receiving', 'refresh');
    //     }
        
    //     if (in_array($this->adm_level, $this->auditor_area)) {
    //         $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
    //         $callBack   = [   
    //             "success"       => "false",
    //             "message"       => "Tidak dapat melakukan proses ini.",
    //             "redirect"      => "backmin/Gudangreceiving/add",
    //         ];
    //     } else {
    //         $this->db->trans_begin();
    //         $request['id_gudang']       = $this->adm_id_gudang;
    //         $request['is_tag']          = $this->input->post('is_tag');
    //         $request['is_intan']        = 2;
    //         $request['status']          = 1;
    //         $request['periode']         = date('Y');
    //         $request['created_date']    = date('Y-m-d H:i:s');
    //         $request['created_by']      = $this->adm_id;
    //         $request['updated_date']    = date('Y-m-d H:i:s');
    //         $request['updated_by']      = $this->adm_id;

    //         $id = $this->mod_gudang->add('request_stock', $request);

    //         $requestDetail['id_produk'] = explode(',', $this->input->post('id_produk'));
    //         $requestDetail['berat']     = explode(',', $this->input->post('berat'));
    //         $requestDetail['jumlah']    = explode(',', $this->input->post('jumlah'));
    //         $requestDetail['no_oef']    = $this->input->post('no_oef');

    //         $dataRequestDetail          = [];
    //         foreach ($requestDetail as $field => $data) {
    //             foreach ($data as $row => $value) {
    //                 $dataRequestDetail[$row][$field] = $value;
    //             }
    //         }

    //         $dataTambahanRequest['total_jumlah']    = 0;
    //         $dataTambahanRequest['total_berat']     = 0;
    //         foreach ($dataRequestDetail as $rows => $values) {
    //             $dataDetail['id_request']           = $id;
    //             $dataDetail['id_produk']            = $values['id_produk'];
    //             $dataDetail['berat']                = $values['berat'] * $values['jumlah'];
    //             $dataDetail['jumlah']               = $values['jumlah'];
    //             $dataDetail['no_oef']               = $values['no_oef'];

    //             $this->mod_gudang->addDetail('request_stock_detail', $dataDetail);

    //             $dataTambahanRequest['total_jumlah']    += $values['jumlah'];
    //             $dataTambahanRequest['total_berat']     += $values['berat'] * $values['jumlah'];
    //         }
    //         $this->mod_gudang->edit('request_stock', 'id_request =' . $id, $dataTambahanRequest);

    //         if ($this->db->trans_status() === true) {
    //             ## ACTION LOG USER
    //             $logs['id_request'] = $id;
    //             $this->logger->logAction('Proses Request Stock Dibuat', $logs);
                
    //             $this->db->trans_commit();
    //             $this->session->set_flashdata('success', 'Data permintaan stok berhasil dibuat dengan kode: <b>' . $id . '</b>.');
    //             $callBack = [
    //                 "success"   => "true",
    //                 "message"   => "Data permintaan stok berhasil dibuat dengan kode: <b>$id</b>.",
    //                 "redirect"  => "backmin/Gudangreceiving/list"
    //             ];
    //         } else {
    //             $this->db->trans_rollback();
    //             $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
    //             $callBack = [
    //                 "success"   => "false",
    //                 "message"   => "Gagal melakukan proses ini.",
    //                 "redirect"  => "backmin/Gudangreceiving/add",
    //             ];
    //         }
    //     }
    //     echo json_encode($callBack);
    // }

    public function addConfirmationPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/receiving', 'refresh');
        }
        
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/Gudangreceiving/add",
            ];
        } else {
            $this->db->trans_begin();
            $request['id_gudang']       = $this->adm_id_gudang;
            $request['is_tag']          = 2; // 1 = tag, 2=site sendiri
            $request['is_intan']        = 2;
            $request['status']          = 1;
            $request['periode']         = date('Y');
            $request['created_date']    = date('Y-m-d H:i:s');
            $request['created_by']      = $this->adm_id;
            $request['updated_date']    = date('Y-m-d H:i:s');
            $request['updated_by']      = $this->adm_id;

            /**
             * =====================================================================
             */
            $periodeRequest         = (int) date('Y');
            $idGudangRequest        = $this->adm_id_gudang;
            $detailGudang           = $this->mod_general->detailData('master_gudang', 'id_gudang', $this->adm_id_gudang);
            $id_site                = $detailGudang['id_site'];
            $isIntan                = 2;
            $tglTransaksi           = $this->input->post('tgl_transaksi') ? $this->input->post('tgl_transaksi') : date('Y-m-d');
            $requestStock           = [];

            $dataOef                = [];

            $id = $this->mod_gudang->add('request_stock', $request);

            $requestDetail['id_produk'] = explode(',', $this->input->post('id_produk'));
            $requestDetail['berat']     = explode(',', $this->input->post('berat'));
            $requestDetail['jumlah']    = explode(',', $this->input->post('jumlah'));
            $requestDetail['no_oef']    = explode(',', $this->input->post('no_oef'));

            $dataRequestDetail          = [];
            foreach ($requestDetail as $field => $data) {
                foreach ($data as $row => $value) {
                    $dataRequestDetail[$row][$field] = $value;
                }
            }

            $dataTambahanRequest['total_jumlah']    = 0;
            $dataTambahanRequest['total_berat']     = 0;
            $oefMessage                             = "";

            $count = 0;
            foreach ($dataRequestDetail as $rows => $values) {
                $persen_toleransi           = 10;
                $getOef                     = $this->mod_general->detailData('production_order','no_oef', $values['no_oef']);
                $jumlahRequest              = $getOef['jumlah_request'];
                $jumlahKirim                = $getOef['jumlah_kirim'];
                $jumlahKirimTotal           = $jumlahKirim + $values['jumlah'];
                $toleransi                  = $persen_toleransi/100;
                $jumlahKirimToleransi       = ($toleransi * $jumlahRequest) + $jumlahRequest;
                $sisaKuota                  = $jumlahKirimToleransi - $jumlahKirim;

                if($jumlahKirimToleransi < $jumlahKirimTotal)
                {
                    if(empty($oefMessage))
                    {
                        $oefMessage = "Kuota No. OEF ". $values['no_oef'] ." tidak mencukupi. sisa kuota : ".$sisaKuota;
                    }
                    else
                    {
                        $oefMessage .= ", Kuota No. OEF ". $values['no_oef'] ." tidak mencukupi. sisa kuota : ".$sisaKuota;
                    }
                }
                else
                {
                    $dataDetail['id_request']           = $id;
                    $dataDetail['id_produk']            = $values['id_produk'];
                    $dataDetail['jumlah']               = $values['jumlah'];
                    $dataDetail['berat']                = $values['berat'] * $values['jumlah'];
                    $dataDetail['no_oef']               = $values['no_oef'];

                    $this->mod_gudang->addDetail('request_stock_detail', $dataDetail);

                    $dataTambahanRequest['total_jumlah']    += $values['jumlah'];
                    $dataTambahanRequest['total_berat']     += $values['berat'] * $values['jumlah'];

                    $dataOef['jumlah_kirim']    = $jumlahKirimTotal;

                    $dataOef['updated_date']    = date('Y-m-d H:i:s');
                    $dataOef['updated_by']      = $this->adm_id;
                    $catatan_alokasi            = empty($getOef['catatan_alokasi']) ? $detailGudang['nama_gudang']." : ".$values['jumlah'] : $getOef['catatan_alokasi'].", ".$detailGudang['nama_gudang']." : ".$values['jumlah'];
                    $dataOef['catatan_alokasi'] = $catatan_alokasi;
                    $this->mod_gudang->edit('production_order','no_oef="'. $values['no_oef'] .'"', $dataOef);

                    /**
                     * ===================================================================================================
                     */
                    $requestStock['detail_request_stock'][$count]['id_produk']    = $values['id_produk'];
                    $requestStock['detail_request_stock'][$count]['jumlah']       = $values['jumlah'];
                    $requestStock['detail_request_stock'][$count]['berat']        = $values['berat'] * $values['jumlah'];
                    $requestStock['detail_request_stock'][$count]['no_oef']       = $values['no_oef'];

                    $stockGudang = $this->mod_scm->getStok($idGudangRequest, $values['id_produk'], 'stok_fisik, stok_available');
                    $dataStock['stok_fisik']            = $stockGudang->stok_fisik + $values['jumlah'];
                    $dataStock['stok_available']        = $stockGudang->stok_available + $values['jumlah'];
                    
                    if ($periodeRequest == $this->periode) {
                        $this->mod_scm->updateStok($idGudangRequest, $values['id_produk'], $dataStock);
                    }

                    if($jumlahKirimTotal >= $jumlahRequest)
                    {
                        $status = '2';
                        $data = array(
                            'status' => $status
                        );
                        $where = array(
                            'no_oef' => $values['no_oef']
                        );
                        $this->mod_general->edit('production_order',$data, $where);

                        $data_history = array(
                            'id_production_order' => $getOef["id"],
                            'status' => $status,
                            'notes' => "Otomatis Closed ketika transaksi",
                            'created_by' => $this->adm_id
                        );
                        $this->mod_gudang->addDetail('production_order_history',$data_history);
                    }
                }
                $count++;
            }

            if(empty($oefMessage))
            {
                $this->mod_gudang->edit('request_stock', 'id_request =' . $id, $dataTambahanRequest);

                if ($isIntan != 1) {
                    $periodeHPP = $this->mod_scm->getPeriodeHPP($tglTransaksi);
                    $reportReceiving = [
                        'id_request'        => $id,
                        'id_periode'        => $periodeHPP['id'],
                        'tax'               => 0,
                        'created_date'      => date('Y-m-d H:i:s')
                    ];
                    $this->mod_scm->add('report_receiving', $reportReceiving);
                    $statusRequestStock['tgl_transaksi']    = $tglTransaksi;
                    $statusRequestStock['id_periode']       = $periodeHPP['id'];
                }
                         
                $requestStockGudang['asal']                 = 99;
                $requestStockGudang['tujuan']               = $idGudangRequest;

                $requestStockGudang['id_request']               = $id;
                $requestStockGudang['id_tipe']                  = 2;
                $requestStockGudang['have_tag']                 = 0;
                $requestStockGudang['is_to_school']             = 0;
                $requestStockGudang['status_transaksi']         = 6;
                $requestStockGudang['created_date']             = date('Y-m-d H:i:s');
                $requestStockGudang['created_by']               = $this->adm_id;
                $requestStockGudang['updated_date']             = date('Y-m-d H:i:s');
                $requestStockGudang['updated_by']               = $this->adm_id;

                $idTransaksi = $this->mod_scm->add('transaksi', $requestStockGudang);

                $this->mod_scm->addTransaksiHistory($idTransaksi, 1);
                $this->mod_scm->addTransaksiHistory($idTransaksi, 6);

                $requestStockTambahan['total_jumlah']           = 0;
                $requestStockTambahan['total_berat']            = 0;

                foreach ($requestStock as $key => $value) {
                    foreach ($value as $data) {
                        $requestDetailTransaksi['id_transaksi']          = $idTransaksi;
                        $requestDetailTransaksi['id_produk']             = $data['id_produk'];
                        $requestDetailTransaksi['jumlah']                = $data['jumlah'];
                        $requestDetailTransaksi['berat']                 = $data['berat'];
                        $requestDetailTransaksi['no_oef']                = $data['no_oef'];

                        $this->mod_scm->addDetail('transaksi_detail', $requestDetailTransaksi);
                        
                        $requestStockTambahan['total_jumlah']   += $data['jumlah'];
                        $requestStockTambahan['total_berat']    += $data['berat'];

                        // if ($periodeRequest == $this->periode) {
                        //     $stock_status       = $this->addReportStockStatus($idGudangRequest, $data['id_produk'], $data['jumlah'], $isIntan, $periodeHPP['id'], $tglTransaksi);
                        // }
                    }
                }

                $this->mod_scm->edit($idTransaksi, $requestStockTambahan);
                $statusRequestStock['status']   = 6;

            // if ($stock_status) {
                if ($this->db->trans_status() === true) {
                    $this->mod_scm->update('request_stock', 'id_request = ' . $id, $statusRequestStock);
                    
                    ## ACTION LOG USER
                    $logs['id_request'] = $id;
                    $this->logger->logAction('Proses Request Stock Dibuat', $logs);
                    
                    $this->db->trans_commit();
                    $this->session->set_flashdata('success', 'Data permintaan stok berhasil dibuat dengan kode: <b>' . $id . '</b>.');
                    $callBack = [
                        "success"   => "true",
                        "message"   => "Data permintaan stok berhasil dibuat dengan kode: <b>$id</b>.",
                        "redirect"  => "backmin/Gudangreceiving/list"
                    ];
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                    $callBack = [
                        "success"   => "false",
                        "message"   => "Gagal melakukan proses ini.",
                        "redirect"  => "backmin/Gudangreceiving/add",
                    ];
                }
            }
            else
            {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal membuat stok receiving. Silahkan cek sisa kuota OEF');
                $callBack = [
                    "success"       => "false",
                    "message"       => $oefMessage,
                    "redirect"      => "backmin/gudangreceiving/add",
                ];
            }
        }
        echo json_encode($callBack);
    }

    public function addReportStockStatus($id_gudang, $id_produk, $jumlah, $is_intan = 0, $id_periode = 0, $tgl_transaksi = null)
    {
        ## $isIntan = ($is_intan == 0 ? TAG : ($is_intan == 1 ? Request Intan : Request Stock));

        $this->db->trans_begin();
        if ($id_gudang && $id_produk) {

            $today              = date('Y-m-d H:i:s');
            $month              = date('n');
            $year               = date('Y');

            $stock_status       = $this->mod_scm->getLastStockStatus($now = 1, $id_gudang, $id_produk, $month, $year);
            $report             = [];

            if ($is_intan == 2) {
                $check_hpp      = $this->mod_scm->getRow("master_hpp", "id_gudang = $id_gudang and id_produk = $id_produk and id_periode = $id_periode");
                if ($check_hpp > 0) {
                    $hpp_produk     = (int)$this->mod_scm->getAll("master_hpp", "hpp", "id_gudang = $id_gudang and id_produk = $id_produk and id_periode = $id_periode")[0]->hpp;
                    $total_income   = (int)($jumlah * $hpp_produk);
                } else {
                    return false;
                }
            }

            if ($stock_status) {
                // In same month and year
                if ($is_intan == 2) {
                    $total_cost                 = (int)$stock_status['total_cost'];
                    $total_qty                  = (int)($stock_status['stok_fisik'] + $jumlah);
    
                    $new_average_cost           = ($total_cost + $total_income) / $total_qty;
                    $new_total_cost             = $total_qty * $new_average_cost;
                    $new_allocated_cost         = $stock_status['stok_booking'] * $new_average_cost;
    
                    $report = [
                        'id_periode'            => $id_periode,
                        'tgl_transaksi'         => $tgl_transaksi,
                        'stok_fisik'            => $total_qty,
                        'stok_available'        => (int)($stock_status['stok_available'] + $jumlah),
                        'average_cost'          => $new_average_cost,
                        'total_cost'            => $new_total_cost,
                        'allocated_cost'        => $new_allocated_cost,
                        'updated_date'          => $today
                    ];
                } elseif ($is_intan == 1) {
                    $total_qty                  = (int)($stock_status['stok_fisik'] - $jumlah);
                    $new_total_cost             = $total_qty * $stock_status['average_cost'];

                    $report = [
                        'stok_fisik'            => $total_qty,
                        'stok_available'        => (int)($stock_status['stok_available'] - $jumlah),
                        'total_cost'            => $new_total_cost,
                        'updated_date'          => $today
                    ];
                }
                $this->mod_scm->update("report_stock_status", "id = " . $stock_status['id'], $report);
            } else {
                // In different month and year
                $last_stock_status  = $this->mod_scm->getLastStockStatus($now = 0, $id_gudang, $id_produk, $month, $year);

                $report = [
                    'id_gudang'                 => $id_gudang,
                    'id_produk'                 => $id_produk,
                    'bulan'                     => $month,
                    'tahun'                     => $year
                ];

                if ($last_stock_status) {
                    // Have record below this month
                    if ($is_intan == 2) {
                        $total_cost             = (int)$last_stock_status['total_cost'];
                        $total_qty              = (int)($last_stock_status['stok_fisik'] + $jumlah);

                        $new_average_cost       = ($total_cost + $total_income) / $total_qty;
                        $new_total_cost         = $total_qty * $new_average_cost;
                        $new_allocated_cost     = $last_stock_status['stok_booking'] * $new_average_cost;

                        $report += [
                            'id_periode'        => $id_periode,
                            'tgl_transaksi'     => $tgl_transaksi,
                            'stok_fisik'        => $total_qty,
                            'stok_booking'      => (int)$last_stock_status['stok_booking'],
                            'stok_available'    => (int)($last_stock_status['stok_available'] + $jumlah),
                            'average_cost'      => $new_average_cost,
                            'total_cost'        => $new_total_cost,
                            'allocated_cost'    => $new_allocated_cost,
                            'created_date'      => $today
                        ];
                    } elseif ($is_intan == 1) {
                        $total_qty              = (int)($last_stock_status['stok_fisik'] - $jumlah);
                        $new_total_cost         = $total_qty * $last_stock_status['average_cost'];
    
                        $report += [
                            'id_periode'        => $last_stock_status['id_periode'],
                            'tgl_transaksi'     => $last_stock_status['tgl_transaksi'],
                            'stok_fisik'        => $total_qty,
                            'stok_booking'      => (int)$last_stock_status['stok_booking'],
                            'stok_available'    => (int)($last_stock_status['stok_available'] - $jumlah),
                            'average_cost'      => $last_stock_status['average_cost'],
                            'total_cost'        => $new_total_cost,
                            'allocated_cost'    => $last_stock_status['allocated_cost'],
                            'created_date'      => $today
                        ];
                    }
                    
                    $this->mod_scm->add("report_stock_status", $report);
                } else {
                    // Don't have record below this month
                    if ($is_intan == 2) {
                        $new_average_cost   = $hpp_produk;
                        $new_total_cost     = $jumlah * $new_average_cost;
    
                        $report += [
                            'id_periode'        => $id_periode,
                            'tgl_transaksi'     => $tgl_transaksi,
                            'stok_fisik'        => (int)$jumlah,
                            'stok_booking'      => 0,
                            'stok_available'    => (int)$jumlah,
                            'average_cost'      => $new_average_cost,
                            'total_cost'        => $new_total_cost,
                            'created_date'      => $today
                        ];
                        $this->mod_scm->add("report_stock_status", $report);
                    }
                }
            }
            
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                return true;
            } else {
                $this->db->trans_rollback();
                return false;
            }
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }

    function testing()
    {
        $getOef                     = $this->mod_general->detailData('production_order','no_oef', '11qq22ww');
        echo "testing : ". $getOef['catatan_alokasi'];

        if(empty($getOef['catatan_alokasi']))
        {
            echo "<br>kosong";
        }
        else
        {
            echo "<br>".$getOef['catatan_alokasi'];
        }
    }
}
