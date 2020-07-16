<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_gudang $mod_gudang
 */
class Gudangrequeststockpartial extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, $this->backmin_gudang_area)) {
            redirect(BACKMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->load->model('mod_gudang');
    }

    public function index()
    {
        redirect(BACKMIN_PATH . '/gudangrequeststockpartial/indexRequestStock');
    }

    public function indexRequestStock()
    {
        $data['page_title']     = 'List Request Stok';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/list', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function listRequestStock()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        // $this->datatables->select('j.id_request, total_jumlah, is_tag, status_tag, format_jumlah, DATE_ADD, if(count_id > "1", concat("<center>",status_transaksi,"</center>"), status) as status');
        $this->datatables->select('j.id_request, total_jumlah, is_tag, status_tag, format_jumlah, date_add, IF(count_id > 0, CONCAT(cast("<center>" as char), status_transaksi,cast("</center>" AS CHAR)), STATUS) AS status');
        $this->datatables->from('(select id_request, total_jumlah, is_tag, IF(is_tag = 1, "Ya", "Tidak") as status_tag, FORMAT(total_jumlah, 0, "de_DE") AS format_jumlah, CONCAT("<center>",created_date,"</center>") AS date_add, CASE status WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Dibuat</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diproses</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-warning\'>Menunggu TAG</span></center>") WHEN 4 THEN CONCAT("<center><span class=\'label label-warning\'>SPK Dibuat</span></center>") WHEN 5 THEN CONCAT("<center><span class=\'label label-primary\'>Dikirim Ekspedisi</span></center>") WHEN 6 THEN CONCAT("<center><span class=\'label label-success\'>Selesai</span></center>") WHEN 7 THEN CONCAT("<center><span class=\'label label-danger\'>Dibatalkan</span></center>") END AS status from request_stock where id_gudang="'.$this->adm_id_gudang.'" and is_intan="2")j');
        // $this->datatables->join('(select count(`id_request`) as count_id, a.`id_request`, group_concat(if(a.`status_transaksi`="1",concat("<span class=\'label label-default\'>", b.`nama_gudang`," : Dibuat</span>"),if(a.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Diproses</span>"),if(a.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Menunggu TAG</span>"),if(a.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : SPK Dibuat</span>"),if(a.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",b.`nama_gudang`," : Dikirim Ekspedisi</span>"),if(a.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",b.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",b.`nama_gudang`," : Dibatalkan</span>")))))))," ") as status_transaksi from `transaksi` a inner join `master_gudang` b on a.`asal`=b.`id_gudang` WHERE a.`id_request` IS NOT NULL GROUP BY a.`id_request`)k','j.id_request=k.id_request','left');

        $this->datatables->join('(SELECT COUNT(tba.`id_request`) AS count_id, tba.`id_request`, GROUP_CONCAT(IF(tba.`status_transaksi`="1",CONCAT("<span class=\'label label-default\'>", b.`nama_gudang`," : Dibuat</span>"),IF(tba.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Diproses</span>"),IF(tba.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : Menunggu TAG</span>"),IF(tba.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",b.`nama_gudang`," : SPK Dibuat</span>"),IF(tba.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",b.`nama_gudang`," : Dikirim Ekspedisi</span>"),IF(tba.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",b.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",b.`nama_gudang`," : Dibatalkan</span>")))))))," ") AS status_transaksi FROM (SELECT a.`id_request`, a.`status_transaksi`, a.asal FROM `transaksi` a JOIN `transaksi_detail` b ON a.`id_transaksi`=b.`id_transaksi` GROUP BY a.`id_request`, a.asal, a.id_transaksi)tba INNER JOIN `master_gudang` b ON tba.`asal`=b.`id_gudang` WHERE tba.`id_request` IS NOT NULL GROUP BY tba.`id_request`)k', 'j.id_request=k.id_request', 'left');
        // $this->datatables->where('id_gudang', $this->adm_id_gudang);
        // $this->datatables->where('is_intan', 2);
        $this->datatables->edit_column('status_tag', '<center>$1</center>', 'status_tag');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
        $this->datatables->edit_column('id_request', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangrequeststockpartial/detailRequestStock/$1') . '">#$1</a></center>', 'id_request');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangrequeststockpartial/detailRequestStock/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_request');
        $this->output->set_output($this->datatables->generate());
    }

    public function listRequestStock_temp()
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
        $this->datatables->edit_column('status_tag', '<center>$1</center>', 'status_tag');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
        $this->datatables->edit_column('id_request', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangrequeststockpartial/detailRequestStock/$1') . '">#$1</a></center>', 'id_request');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangrequeststockpartial/detailRequestStock/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_request');
        $this->output->set_output($this->datatables->generate());
    }

    public function detailRequestStock($id)
    {
        if ($id && is_numeric($id)) {
            $data['page_title']     = 'Detil Permintaan Stok';
            $data['detail']         = $this->mod_general->detailData('request_stock', 'id_request', $id);
            $data['gudang']         = $this->mod_general->detailData('master_gudang', 'id_gudang', $this->adm_id_gudang);
            $data['transaksi']      = $this->mod_gudang->getListTransaksiByRequestID($id);
            $data['listproducts']   = $this->mod_gudang->getListProductByRequestID($id);
            $data['listlog']        = $this->mod_gudang->getListLog($id);
            $data['adm_level']      = $this->adm_level;

            $data['status']         = '';
            // if($data['transaksi']['count_id'] > 1)
            if($data['transaksi'])
            {
                $status = [];
                foreach($data['transaksi'] as $d)
                {
                    $status[]= '<a href="'.base_url(BACKMIN_PATH . '/gudangrequeststockpartial/detailRequestStockPerGudang/').$d['id_transaksi'].'">'.$d['status_transaksi'].'</a>';
                }
                $data['status'] = implode(" ", $status);
            }
            else
            {
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
            }

            // print_r($data['status']);
            // exit();

            $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/detil', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/requeststock');
        }
    }

    public function detailRequestStockPerGudang($id_transaksi)
    {
        if ($id_transaksi && is_numeric($id_transaksi)) 
        {
            $data['page_title']     = 'Detil Permintaan Stok Per Gudang';
            $data['detail']         = $this->mod_gudang->get_detail_transaksi($id_transaksi);
            // print_r($data['detail'] );
            // echo "<br><br>";
            $data['gudang']         = $this->mod_general->detailData('master_gudang', 'id_gudang', $data['detail']['asal']);

            // print_r($data['gudang'] );
            // $data['transaksi']      = $this->mod_gudang->getListTransaksiByTransaksiID($id);
            $data['listproducts']   = $this->mod_gudang->getListProductByTransaksiID($id_transaksi);

            $data['status']         = '';

            switch ($data['detail']['status_transaksi']) 
            {
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

            $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/detil_per_gudang', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/requeststock');
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
        $data['content']            = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/add', $data, true);
        $data['script_js']          = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/add_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function addConfirmation()
    {
        echo $this->input->post('request_id_produk');
        $requestDetail['id_produk']     = explode(',', $this->input->post('request_id_produk'));
        $requestDetail['berat']         = explode(',', $this->input->post('request_berat'));
        $requestDetail['jumlah']        = explode(',', $this->input->post('request_jumlah'));

        $data['list_request']   = [];
        $count                  = 0;
        foreach ($requestDetail['id_produk'] as $rows => $id_produk) {
            $data['list_request'][$count]           = $this->mod_gudang->getListProduct('a.id_product AS id_product, a.kode_buku AS kode_buku, a.reference AS isbn, a.name AS judul, a.weight AS weight, b.name AS kelas, c.name AS type', 'id_product=' . $id_produk)[0];
            $data['list_request'][$count]->berat    = $requestDetail['berat'][$count];
            $data['list_request'][$count]->jumlah   = $requestDetail['jumlah'][$count];
            $count++;
        }

        $data['is_tag']         = $this->input->post('is_tags');
        $data['tipeGudang']     = $this->mod_gudang->getAll('master_gudang', 'is_utama', 'id_gudang=' . $this->adm_id_gudang)[0];
        $data['page_title']     = 'Konfirmasi Permintaan Stok';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/add_konfirmasi', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/add_js', '', true);
        
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function prosesAddRequestStock()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/requeststockpartial', 'refresh');
        }
        
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/gudangrequeststockpartial/add",
            ];
        } else {
            $this->db->trans_begin();
            $request['id_gudang']       = $this->adm_id_gudang;
            $request['is_tag']          = $this->input->post('is_tag');
            $request['is_intan']        = 2;
            $request['status']          = 1;
            $request['periode']         = date('Y');
            $request['created_date']    = date('Y-m-d H:i:s');
            $request['created_by']      = $this->adm_id;
            $request['updated_date']    = date('Y-m-d H:i:s');
            $request['updated_by']      = $this->adm_id;

            $id = $this->mod_gudang->add('request_stock', $request);

            $requestDetail['id_produk'] = explode(',', $this->input->post('id_produk'));
            $requestDetail['berat']     = explode(',', $this->input->post('berat'));
            $requestDetail['jumlah']    = explode(',', $this->input->post('jumlah'));

            $dataRequestDetail          = [];
            foreach ($requestDetail as $field => $data) {
                foreach ($data as $row => $value) {
                    $dataRequestDetail[$row][$field] = $value;
                }
            }

            $dataTambahanRequest['total_jumlah']    = 0;
            $dataTambahanRequest['total_berat']     = 0;
            foreach ($dataRequestDetail as $rows => $values) {
                $dataDetail['id_request']           = $id;
                $dataDetail['id_produk']            = $values['id_produk'];
                $dataDetail['berat']                = $values['berat'] * $values['jumlah'];
                $dataDetail['jumlah']               = $values['jumlah'];

                $this->mod_gudang->addDetail('request_stock_detail', $dataDetail);

                $dataTambahanRequest['total_jumlah']    += $values['jumlah'];
                $dataTambahanRequest['total_berat']     += $values['berat'] * $values['jumlah'];
            }
            $this->mod_gudang->edit('request_stock', 'id_request =' . $id, $dataTambahanRequest);

            if ($this->db->trans_status() === true) {
                ## ACTION LOG USER
                $logs['id_request'] = $id;
                $this->logger->logAction('Proses Request Stock Dibuat', $logs);
                
                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Data permintaan stok berhasil dibuat dengan kode: <b>' . $id . '</b>.');
                $callBack = [
                    "success"   => "true",
                    "message"   => "Data permintaan stok berhasil dibuat dengan kode: <b>$id</b>.",
                    "redirect"  => "backmin/gudangrequeststockpartial/indexRequestStock"
                ];
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                $callBack = [
                    "success"   => "false",
                    "message"   => "Gagal melakukan proses ini.",
                    "redirect"  => "backmin/gudangrequeststockpartial/add",
                ];
            }
        }
        echo json_encode($callBack);
    }

    public function close_request_stock($id_request)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $data['id_request'] = $id_request;
        // $data['detil'] = $this->mod_gudang->getListTransaksiDetail($id);
        $this->load->view('backmin/gudang/request_stock_partial/close_permintaan', $data);
    }

    public function close_request_stock_post()
    {
        $id_request = $this->input->post('id_request');
        $catatan = $this->input->post('catatan');
        // // $id_request = '2724';
        $check_status_transaksi = array();
        $status = "";
        $data = array();

        $callBack = array();

        // Mendapatkan data transaksi berdasarkan id request
        $data_transaksi   = $this->mod_gudang->getTransaksiDetailByRequestID($id_request);

        if(count($data_transaksi) == 0)
        {
            // echo "Data tidak ditemukan";
            $callBack = array(
                "success" => TRUE,
                "message" => "Gagal menutup permintaan. Data transaksi tidak ditemukan.",
                "redirect" => 'backmin/gudangrequeststockpartial/detailRequestStock/'.$id_request
            );
            $this->session->set_flashdata('error', 'Gagal menutup permintaan. Data transaksi tidak ditemukan.');
        }
        else
        {
            // foreach($data_transaksi as $d)
            // {

            //     print_r($d);
            //     echo '<br><br>';
            //     if($d['status_transaksi'] == 6)
            //     {
            //         $check_status_transaksi[] = 1;
            //     }
            //     else
            //     {
            //         $check_status_transaksi[] = 0;
            //     }
            // }
            // // print_r($check_status_transaksi);

            // if(in_array('0',$check_status_transaksi))
            // {
            //     $data['status'] = '3';
            // }
            // else
            // {
            //     $data['status'] = '6';
            // }
            // 
            $data = array(
                "status"        => '6',
                "catatan"       => $catatan,
                "updated_date"  => date('Y-m-d H:i:s'),
                "updated_by"    => $this->adm_id
            );

            // $data['status'] = '6';
            // $data['catatan'] = $catatan;
            
            $this->mod_general->edit('request_stock', $data, 'id_request ='. $id_request);
            if($this->db->affected_rows() > 0){
                // echo "berhasil";
                $callBack = array(
                    "success" => TRUE,
                    "message" => "Berhasil menutup permintaan.",
                    "redirect" => 'backmin/gudangrequeststockpartial/indexRequestStock'
                );
                $this->session->set_flashdata('success', 'Berhasil menutup permintaan.');
                // redirect('backmin/gudangrequeststockpartial/indexRequestStock');
            }
            else
            {
                // echo "gagal";
                $callBack = array(
                    "success" => TRUE,
                    "message" => "Gagal menutup permintaan.",
                    "redirect" => 'backmin/gudangrequeststockpartial/detailRequestStock/'.$id_request
                );
                $this->session->set_flashdata('error', 'Gagal menutup permintaan.');
                // redirect('backmin/gudangrequeststockpartial/detailRequestStock/'.$id_request);
            }
        }
        // print_r($data['status']);


        // Melakukan pengecekan status transaksi : 
        // Jika data tidak ditemukan, berikan alert
        // jika status belum selesai, maka status = 3 (menunggu tag)
        // Jika selesai, set status = 6
        echo json_encode($callBack);
    }
}
