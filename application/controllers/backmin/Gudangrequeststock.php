<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_gudang $mod_gudang
 */
class Gudangrequeststock extends MY_Controller
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
        redirect(BACKMIN_PATH . '/gudangrequeststock/indexRequestStock');
    }

    public function indexRequestStock()
    {
        $data['page_title']     = 'List Request Stok';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/requeststock/list', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/requeststock/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function listRequestStock()
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
        $this->datatables->edit_column('id_request', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangrequeststock/detailRequestStock/$1') . '">#$1</a></center>', 'id_request');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangrequeststock/detailRequestStock/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_request');
        $this->output->set_output($this->datatables->generate());
    }

    public function detailRequestStock($id)
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

            $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/requeststock/detil', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/requeststock/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/requeststock');
        }
    }

    public function add()
    {
        $data['page_title']         = 'Buat Permintaan Stok';
        $data['listBukuSD']         = $this->mod_gudang->getBookLevel('1-6');
        $data['listBukuSMP']        = $this->mod_gudang->getBookLevel('7-9');
        $data['listBukuSMA']        = $this->mod_gudang->getBookLevel('10-12');
        $data['listBukuSMP_ktsp']   = $this->mod_gudang->getBookLevelKTSP('7-9');
        $data['listBukuSMK']        = $this->mod_gudang->getBookLevelSMK('10-12');
        $data['listBukuLiterasi']   = $this->mod_gudang->getBookLiterasi();
        $data['listBukuPengayaan']  = $this->mod_gudang->getBookPengayaan();
        $data['listBukuReferensi']  = $this->mod_gudang->getBookReferensi();
        $data['listBukuPandik']     = $this->mod_gudang->getBookPandik();

        $data['tipeGudang']         = $this->mod_gudang->getAll('master_gudang', 'is_utama', 'id_gudang=' . $this->adm_id_gudang)[0];
        $data['content']            = $this->load->view(BACKMIN_PATH . '/gudang/requeststock/add', $data, true);
        $data['script_js']          = $this->load->view(BACKMIN_PATH . '/gudang/requeststock/add_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function addConfirmation()
    {
        echo $this->input->post('request_id_produk');
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
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/requeststock/add_konfirmasi', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/requeststock/add_js', '', true);
        
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function prosesAddRequestStock()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/requeststock', 'refresh');
        }
        
        if (in_array($this->adm_level, $this->auditor_area)) {
            $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
            $callBack   = [   
                "success"       => "false",
                "message"       => "Tidak dapat melakukan proses ini.",
                "redirect"      => "backmin/gudangrequeststock/add",
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
            $requestDetail['no_oef']    = $this->input->post('no_oef');

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
                $dataDetail['no_oef']           = $values['no_oef'];

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
                    "redirect"  => "backmin/gudangrequeststock/indexRequestStock"
                ];
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                $callBack = [
                    "success"   => "false",
                    "message"   => "Gagal melakukan proses ini.",
                    "redirect"  => "backmin/gudangrequeststock/add",
                ];
            }
        }
        echo json_encode($callBack);
    }
}
