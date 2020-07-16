<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'third_party/PhpExportExcel.php';
require_once APPPATH . 'third_party/xlsxwriter.class.php';

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_scm $mod_scm
 */
class Scmrequeststockpartial extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->adm_level, $this->backmin_scm_area)) {
            redirect(BACKMIN_PATH);
        }
        $this->load->model('mod_general');
        $this->load->model('mod_scm');
        $this->load->helper('download');
    }

    public function index()
    {
        redirect(BACKMIN_PATH . '/scmrequeststock/indexRequestStockMasuk');
    }

    public function indexRequestStockMasuk()
    {
        $data['page_title']     = 'List Permintaan Stok - Masuk';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/requeststock_partial/list', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/requeststock_partial/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    // public function listRequestStockMasuk()
    // {
    //     if ( ! $this->input->is_ajax_request()) {
    //         return false;
    //     }
    //     $this->load->library('datatables');
    //     $this->output->set_header('Content-Type:application/json; charset=utf-8');
    //     $this->datatables->select('
    //         a.id_request AS id_request, 
    //         b.nama_gudang AS nama_gudang, 
    //         a.total_jumlah AS total_jumlah,
    //         if(isnull(a.total_jumlah - sum(d.`jumlah`)),"0",a.total_jumlah - SUM(d.`jumlah`)) AS sisa_jumlah,
    //         a.is_intan, IF(a.is_intan = 1, "Ya", "Tidak") AS status_intan, 
    //         CONCAT("<center>",a.created_date,"</center>") AS date_add, 
    //         a.is_tag, if(a.is_tag = 1, "Ya", "Tidak") AS status_tag, 
    //         FORMAT(a.total_jumlah, 0, "de_DE") AS format_jumlah
    //     ');
    //     $this->datatables->from('request_stock a');
    //     $this->datatables->join('master_gudang b', 'b.id_gudang=a.id_gudang', 'inner');
    //     $this->datatables->join('transaksi c', 'c.`id_request`=a.`id_request`', 'left');
    //     $this->datatables->join('transaksi_detail d', 'd.`id_transaksi`=c.`id_transaksi`', 'left');
    //     $this->datatables->where('a.status', 1);
    //     $this->datatables->where('a.is_tag', 1);
    //     $this->datatables->group_by('a.id_request');
    //     $this->datatables->edit_column('id_request', '<center><a href="' . base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockMasuk/$1') . '">#$1</a></center>', 'id_request');
    //     $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
    //     $this->datatables->edit_column('status_intan', '<center>$1</center>', 'status_intan');
    //     $this->datatables->edit_column('status_tag', '<center>$1</center>', 'status_tag');
    //     $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockMasuk/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_request');
    //     $this->output->set_output($this->datatables->generate());
    // }
    public function listRequestStockMasuk()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
            tblz.id_request AS id_request, 
            tblz.nama_gudang AS nama_gudang, 
            tblz.total_jumlah AS total_jumlah,
            tblz.sisa_jumlah AS sisa_jumlah,
            tblz.status_intan AS status_intan,
            tblz.date_created AS date_created, 
            tblz.status_tag AS status_tag,
            tblz.format_jumlah AS format_jumlah,
            tblz.status_transaksi AS status_transaksi
            ');
            $this->datatables->from('(SELECT a.id_request AS id_request, 
            b.nama_gudang AS nama_gudang, 
            a.total_jumlah AS total_jumlah,
            IF(ISNULL(a.total_jumlah), 0, a.total_jumlah) - IF(ISNULL(tbx.`jumlah`), 0, tbx.`jumlah`) AS sisa_jumlah,
            a.is_intan, IF(a.is_intan = 1, "Ya", "Tidak") AS status_intan, 
            CONCAT("<center>",a.created_date,"</center>") AS date_created, 
            a.is_tag, IF(a.is_tag = 1, "Ya", "Tidak") AS status_tag, 
            FORMAT(a.total_jumlah, 0, "de_DE") AS format_jumlah,
            IF(ISNULL(tbx.status_transaksi), CASE a.status WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Dibuat</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diproses</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-warning\'>Menunggu TAG</span></center>") WHEN 4 THEN CONCAT("<center><span class=\'label label-warning\'>SPK Dibuat</span></center>") WHEN 5 THEN CONCAT("<center><span class=\'label label-primary\'>Dikirim Ekspedisi</span></center>") WHEN 6 THEN CONCAT("<center><span class=\'label label-success\'>Selesai</span></center>") WHEN 7 THEN CONCAT("<center><span class=\'label label-danger\'>Dibatalkan</span></center>") END, tbx.status_transaksi) AS status_transaksi
            FROM request_stock a
            INNER JOIN master_gudang b ON b.id_gudang=a.id_gudang
            LEFT JOIN (SELECT COUNT(tba.`id_request`) AS count_id, tba.`id_request`, GROUP_CONCAT(IF(tba.`status_transaksi`="1",CONCAT("<span class=\'label label-default\'>", yy.`nama_gudang`," : Dibuat</span>"),IF(tba.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",yy.`nama_gudang`," : Diproses</span>"),IF(tba.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",yy.`nama_gudang`," : Menunggu TAG</span>"),IF(tba.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",yy.`nama_gudang`," : SPK Dibuat</span>"),IF(tba.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",yy.`nama_gudang`," : Dikirim Ekspedisi</span>"),IF(tba.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",yy.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",yy.`nama_gudang`," : Dibatalkan</span>")))))))," ") AS status_transaksi, SUM(tba.jumlah) AS jumlah FROM (SELECT xx.`id_request`, xx.`id_transaksi`, xx.`status_transaksi`, xx.asal, SUM(yy.jumlah) AS jumlah FROM `transaksi` xx JOIN `transaksi_detail` yy ON xx.`id_transaksi`=yy.`id_transaksi` GROUP BY xx.`id_request`, xx.asal, xx.`id_transaksi`)tba INNER JOIN `master_gudang` yy ON tba.`asal`=yy.`id_gudang` WHERE tba.`id_request` IS NOT NULL GROUP BY tba.`id_request`)tbx ON a.id_request=tbx.id_request
            WHERE a.status=1
            AND a.is_tag=1
            GROUP BY a.id_request)tblz');
        $this->datatables->edit_column('id_request', '<center><a href="' . base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockMasuk/$1') . '">#$1</a></center>', 'id_request');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
        $this->datatables->edit_column('status_intan', '<center>$1</center>', 'status_intan');
        $this->datatables->edit_column('status_tag', '<center>$1</center>', 'status_tag');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockMasuk/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_request');
        $this->output->set_output($this->datatables->generate());
    }

    public function listRequestStockMasuk_temp()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
            a.id_request AS id_request, 
            b.nama_gudang AS nama_gudang, 
            a.total_jumlah AS total_jumlah,
            IF(ISNULL(a.total_jumlah - SUM(tbx.`jumlah`)),"0",a.total_jumlah - SUM(tbx.`jumlah`)) AS sisa_jumlah,
            a.is_intan, IF(a.is_intan = 1, "Ya", "Tidak") AS status_intan, 
            CONCAT("<center>",a.created_date,"</center>") AS date_add, 
            a.is_tag, IF(a.is_tag = 1, "Ya", "Tidak") AS status_tag, 
            FORMAT(a.total_jumlah, 0, "de_DE") AS format_jumlah,
            IF(ISNULL(tbx.status_transaksi), CASE a.status WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Dibuat</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diproses</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-warning\'>Menunggu TAG</span></center>") WHEN 4 THEN CONCAT("<center><span class=\'label label-warning\'>SPK Dibuat</span></center>") WHEN 5 THEN CONCAT("<center><span class=\'label label-primary\'>Dikirim Ekspedisi</span></center>") WHEN 6 THEN CONCAT("<center><span class=\'label label-success\'>Selesai</span></center>") WHEN 7 THEN CONCAT("<center><span class=\'label label-danger\'>Dibatalkan</span></center>") END, tbx.status_transaksi) AS status
            ');
        $this->datatables->from('request_stock a');
        $this->datatables->join('master_gudang b', 'b.id_gudang=a.id_gudang', 'inner');
        $this->datatables->join('(SELECT COUNT(tba.`id_request`) AS count_id, tba.`id_request`, GROUP_CONCAT(IF(tba.`status_transaksi`="1",CONCAT("<span class=\'label label-default\'>", yy.`nama_gudang`," : Dibuat</span>"),IF(tba.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",yy.`nama_gudang`," : Diproses</span>"),IF(tba.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",yy.`nama_gudang`," : Menunggu TAG</span>"),IF(tba.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",yy.`nama_gudang`," : SPK Dibuat</span>"),IF(tba.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",yy.`nama_gudang`," : Dikirim Ekspedisi</span>"),IF(tba.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",yy.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",yy.`nama_gudang`," : Dibatalkan</span>")))))))," ") AS status_transaksi, SUM(tba.jumlah) AS jumlah FROM (SELECT xx.`id_request`, xx.`id_transaksi`, xx.`status_transaksi`, xx.asal, SUM(yy.jumlah) AS jumlah FROM `transaksi` xx JOIN `transaksi_detail` yy ON xx.`id_transaksi`=yy.`id_transaksi` GROUP BY xx.`id_request`, xx.asal, xx.`id_transaksi`)tba INNER JOIN `master_gudang` yy ON tba.`asal`=yy.`id_gudang` WHERE tba.`id_request` IS NOT NULL GROUP BY tba.`id_request`)tbx', 'a.id_request=tbx.id_request', 'left');
        $this->datatables->where('a.status', 1);
        $this->datatables->where('a.is_tag', 1);
        // $this->datatables->where('tbx.status_transaksi', NULL);
        $this->datatables->group_by('a.id_request');
        $this->datatables->edit_column('id_request', '<center><a href="' . base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockMasuk/$1') . '">#$1</a></center>', 'id_request');
        $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
        $this->datatables->edit_column('status_intan', '<center>$1</center>', 'status_intan');
        $this->datatables->edit_column('status_tag', '<center>$1</center>', 'status_tag');
        $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockMasuk/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_request');
        $this->output->set_output($this->datatables->generate());
    }

    public function detailRequestStockMasuk($id)
    {
        if ($id && is_numeric($id)) {
            $data['detail']         = $this->mod_general->detailData('request_stock', 'id_request', $id);
            $data['gudang']         = $this->mod_general->detailData('master_gudang', 'id_gudang', $data['detail']['id_gudang']);
            $data['listproducts']   = $this->mod_scm->getListProductByRequestID($id, $data['detail']['id_gudang']);
            $data['listlog']        = $this->mod_scm->getListLog($id);
            $data['adm_level']      = $this->adm_level;

            $data['check_proses']   = $this->mod_general->getList("transaksi", '*', array("id_request" => $id));
            $data['transaksi']      = $this->mod_scm->getListTransaksiByRequestID($id);

            $data['status']         = '';
            // if($data['transaksi']['count_id'] > 1)
            if($data['transaksi'])
            {
                // $data['status'] = $data['transaksi']['status_transaksi'];

                $status = [];
                foreach($data['transaksi'] as $d)
                {
                    $status[]= '<a href="'.base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockPerGudang/').$d['id_transaksi'].'">'.$d['status_transaksi'].'</a>';
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

        $data['page_title']     = 'Detil Permintaan Stok - Masuk';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/requeststock_partial/detil', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/requeststock_partial/detil_js', '', true);

        $this->load->view(BACKMIN_PATH . '/main', $data);
    } else {
        redirect(BACKMIN_PATH . '/scmrequeststockpartial/indexrequeststockmasuk');
    }
}

public function addLog($id)
{
    if ( ! $this->input->is_ajax_request()) {
        return false;
    }

    $data['detil'] = $this->mod_general->detailData('request_stock', 'id_request', $id);
    $this->load->view(BACKMIN_PATH . '/scm/requeststock_partial/log_popup', $data);
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
            $id_request = $this->input->post('id_request');
            $reference = $this->input->post('reference');
                // insert tabel finance_logs
            $data = [
                'id_request' => $id_request,
                'notes' => $this->input->post('notes', true),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->adm_id,
            ];
            $proc = $this->mod_general->addData('request_stock_logs', $data);
            if ($proc) {
                $call_back = [
                    'success' => 'true',
                    'message' => 'Data successfully inserted.',
                ];
                $this->session->set_flashdata('msg_success',
                    'Log book penagihan untuk permintaan stok: <b> ID #'.$id_request.'</b> berhasil <b>DITAMBAHKAN</b></p>');
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

public function indexRequestStockDiproses()
{
    $data['page_title']     = 'List Permintaan Stok - Diproses';
    $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/requeststock_diproses_partial/list', $data, true);
    $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/requeststock_diproses_partial/list_js', '', true);

    $this->load->view(BACKMIN_PATH . '/main', $data);
}

    // public function listRequestStockDiproses()
    // {
    //     if ( ! $this->input->is_ajax_request()) {
    //         return false;
    //     }
    //     $this->load->library('datatables');
    //     $this->output->set_header('Content-Type:application/json; charset=utf-8');
    //     $this->datatables->select('
    //         a.id_request AS id_request, 
    //         IF(a.is_tag = 1 or a.is_intan = 1, (select x.nama_gudang from master_gudang x where x.id_gudang = c.asal), "<center>-</center>") AS gudang_pengirim,
    //         IF(a.is_intan = 2, b.nama_gudang, "<center>-</center>") AS nama_gudang, 
    //         c.total_jumlah AS total_jumlah, 
    //         a.is_intan, 
    //         IF(a.is_intan = 1, "Ya", "Tidak") AS status_intan, 
    //         a.is_tag, 
    //         IF(a.is_tag = 1, "Ya", "Tidak") AS status_tag, 
    //         FORMAT(c.total_jumlah, 0, "de_DE") AS format_jumlah,
    //         CONCAT("<center>",a.created_date,"</center>") AS date_add, 
    //         CASE c.status_transaksi WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Dibuat</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diproses</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-warning\'>Menunggu TAG</span></center>") WHEN 4 THEN CONCAT("<center><span class=\'label label-warning\'>SPK Dibuat</span></center>") WHEN 5 THEN CONCAT("<center><span class=\'label label-primary\'>Dikirim Ekspedisi</span></center>") WHEN 6 THEN CONCAT("<center><span class=\'label label-success\'>Selesai</span></center>") END AS status
    //     ');
    //     $this->datatables->from('request_stock a');
    //     $this->datatables->join('master_gudang b', 'b.id_gudang=a.id_gudang', 'inner');
    //     $this->datatables->join('transaksi c', 'c.id_request=a.id_request', 'inner');
    //     $this->datatables->where('a.status >', 1);
    //     $this->datatables->where('a.is_tag', 1);
    //     $this->datatables->edit_column('id_request', '<center><a href="' . base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockDiproses/$1') . '">#$1</a></center>', 'id_request');
    //     $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
    //     $this->datatables->edit_column('status_intan', '<center>$1</center>', 'status_intan');
    //     $this->datatables->edit_column('status_tag', '<center>$1</center>', 'status_tag');
    //     $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockDiproses/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_request');
    //     $this->output->set_output($this->datatables->generate());
    // }

public function listRequestStockDiproses()
{
    if ( ! $this->input->is_ajax_request()) {
        return false;
    }
    $this->load->library('datatables');
    $this->output->set_header('Content-Type:application/json; charset=utf-8');
    $this->datatables->select('
        id_request AS id_request,
        nama_gudang AS nama_gudang,
        total_jumlah AS total_jumlah,
        sisa_jumlah AS sisa_jumlah,
        status_intan AS status_intan,
        date_created AS date_created, 
        status_tag AS status_tag,
        format_jumlah AS format_jumlah,
        status_transaksi AS status_transaksi
    ');
    $this->datatables->from('(SELECT a.id_request AS id_request, 
        b.nama_gudang AS nama_gudang, 
        a.total_jumlah AS total_jumlah,
        IF(ISNULL(a.total_jumlah), 0, a.total_jumlah) - IF(ISNULL(tbx.`jumlah`), 0, tbx.`jumlah`) AS sisa_jumlah,
        a.is_intan, IF(a.is_intan = 1, "Ya", "Tidak") AS status_intan, 
        CONCAT("<center>",a.created_date,"</center>") AS date_created, 
        a.is_tag, IF(a.is_tag = 1, "Ya", "Tidak") AS status_tag, 
        FORMAT(a.total_jumlah, 0, "de_DE") AS format_jumlah,
        IF(ISNULL(tbx.status_transaksi), CASE a.status WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Dibuat</span></center>") WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Diproses</span></center>") WHEN 3 THEN CONCAT("<center><span class=\'label label-warning\'>Menunggu TAG</span></center>") WHEN 4 THEN CONCAT("<center><span class=\'label label-warning\'>SPK Dibuat</span></center>") WHEN 5 THEN CONCAT("<center><span class=\'label label-primary\'>Dikirim Ekspedisi</span></center>") WHEN 6 THEN CONCAT("<center><span class=\'label label-success\'>Selesai</span></center>") WHEN 7 THEN CONCAT("<center><span class=\'label label-danger\'>Dibatalkan</span></center>") END, tbx.status_transaksi) AS status_transaksi
FROM request_stock a
INNER JOIN master_gudang b ON b.id_gudang=a.id_gudang
LEFT JOIN (SELECT COUNT(tba.`id_request`) AS count_id, tba.`id_request`, GROUP_CONCAT(IF(tba.`status_transaksi`="1",CONCAT("<span class=\'label label-default\'>", yy.`nama_gudang`," : Dibuat</span>"),IF(tba.`status_transaksi`="2",CONCAT("<span class=\'label label-warning\'>",yy.`nama_gudang`," : Diproses</span>"),IF(tba.`status_transaksi`="3",CONCAT("<span class=\'label label-warning\'>",yy.`nama_gudang`," : Menunggu TAG</span>"),IF(tba.`status_transaksi`="4",CONCAT("<span class=\'label label-warning\'>",yy.`nama_gudang`," : SPK Dibuat</span>"),IF(tba.`status_transaksi`="5",CONCAT("<span class=\'label label-primary\'>",yy.`nama_gudang`," : Dikirim Ekspedisi</span>"),IF(tba.`status_transaksi`="6",CONCAT("<span class=\'label label-success\'>",yy.`nama_gudang`," : Selesai</span>"),CONCAT("<span class=\'label label-danger\'>",yy.`nama_gudang`," : Dibatalkan</span>")))))))," ") AS status_transaksi, SUM(tba.jumlah) AS jumlah FROM (SELECT xx.`id_request`, xx.`status_transaksi`, xx.asal, SUM(yy.jumlah) AS jumlah FROM `transaksi` xx JOIN `transaksi_detail` yy ON xx.`id_transaksi`=yy.`id_transaksi` GROUP BY xx.`id_request`, xx.asal, xx.`id_transaksi`)tba INNER JOIN `master_gudang` yy ON tba.`asal`=yy.`id_gudang` WHERE tba.`id_request` IS NOT NULL GROUP BY tba.`id_request`)tbx ON a.id_request=tbx.id_request
WHERE a.status > 1
AND a.is_tag IN (1,2)
GROUP BY a.id_request)tblx');
    $this->datatables->edit_column('id_request', '<center><a href="' . base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockDiproses/$1') . '">#$1</a></center>', 'id_request');
    $this->datatables->edit_column('total_jumlah', '<center>$1</center>', 'format_jumlah');
    $this->datatables->edit_column('status_intan', '<center>$1</center>', 'status_intan');
    $this->datatables->edit_column('status_tag', '<center>$1</center>', 'status_tag');
    $this->datatables->add_column('detail', '<center><a href="' . base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockMasuk/$1') . '" class="btn btn-default btn-rounded btn-condensed btn-sm"><span class="fa fa-search"></span></a></center>', 'id_request');
    $this->output->set_output($this->datatables->generate());
}

public function detailRequestStockDiproses($id)
{
    if ($id && is_numeric($id)) {
        $data['detail']         = $this->mod_general->detailData('request_stock', 'id_request', $id);
        $data['listproducts']   = $this->mod_scm->getListProductByRequestID($id, $data['detail']['id_gudang']);
        $data['transaksi']      = $this->mod_scm->getListTransaksiByRequestID($id);

        $data['status']         = '';
            // if($data['transaksi']['count_id'] > 1)
        if($data['transaksi'])
        {
                // $data['status'] = $data['transaksi']['status_transaksi'];

            $status = [];
            foreach($data['transaksi'] as $d)
            {
                $status[]= '<a href="'.base_url(BACKMIN_PATH . '/scmrequeststockpartial/detailRequestStockPerGudang/').$d['id_transaksi'].'">'.$d['status_transaksi'].'</a>';
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

    $data['gudang']         = $this->mod_general->detailData('master_gudang', 'id_gudang', $data['detail']['id_gudang']);
    if ($data['detail']['is_tag'] == 1) {
        $data['gudang_pengirim'] = $this->mod_scm->getGudangPengirimRequestStock($id)->nama_gudang;
    }

    $data['page_title']     = 'Detil Permintaan Stok - Diproses';
    $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/requeststock_diproses/detil', $data, true);
    $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/requeststock_diproses/detil_js', '', true);
    $this->load->view(BACKMIN_PATH . '/main', $data);
} else {
    redirect(BACKMIN_PATH . '/scmrequeststock/indexRequestStockDiproses');
}
}

public function detailRequestStockPerGudang($id_transaksi)
{
    if ($id_transaksi && is_numeric($id_transaksi)) 
    {
        $data['page_title']     = 'Detil Permintaan Stok Per Gudang';
        $data['detail']         = $this->mod_scm->get_detail_transaksi($id_transaksi);
            // print_r($data['detail'] );
            // echo "<br><br>";
        $data['gudang']         = $this->mod_general->detailData('master_gudang', 'id_gudang', $data['detail']['asal']);

            // print_r($data['gudang'] );
            // $data['transaksi']      = $this->mod_gudang->getListTransaksiByTransaksiID($id);
        $data['status_request'] = $this->mod_general->detailData('request_stock', 'id_request', $data['detail']['id_request']);
        $data['listproducts']   = $this->mod_scm->getListProductByTransaksiID($id_transaksi);

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

        $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/requeststock_diproses_partial/detil_per_gudang', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/request_stock_partial/detil_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    } else {
        redirect(BACKMIN_PATH . '/requeststock');
    }
}

public function popWarehouse($idGudang, $idProduct, $request, $idOrder, $sisa_request)
{
    if ( ! $this->input->is_ajax_request()) {
        return false;
    }
    if ($idGudang && $idProduct && $request && $idOrder && $sisa_request) {
        $data['id_order']           = $idOrder;
        $data['request']            = $request;
        $data['sisa_request']               = $sisa_request;
        $data['product']            = $this->mod_general->detailData('product', 'id_product', $idProduct);
        $data['list_warehouse']     = $this->mod_scm->getListWarehouse($idGudang, $idProduct);

        echo $this->load->view(BACKMIN_PATH . '/scm/requeststock_partial/pop_warehouse', $data, true);
    }
}

public function detailRequestStockMasukProses()
{
    $this->db->trans_begin();
    $id_request             = $this->input->post('id_request');
    $periodeRequest         = (int)$this->input->post('periode_request');
    $idGudangRequest        = $this->input->post('id_gudang_request');
    $id_site                = $this->input->post('id_site_request');
    $isTAG                  = $this->input->post('is_tag');
    $isIntan                = $this->input->post('is_intan');
    $periodeRequest         = $this->input->post('periode_request');
    $tglTransaksi           = $this->input->post('tgl_transaksi') ? $this->input->post('tgl_transaksi') : '';


    $id_produk              = $this->input->post('id_product');
    $jumlah                 = $this->input->post('product_quantity');
    $berat                  = $this->input->post('weight');

    $id_gudang              = $this->input->post('id_gudang');
    $qty                    = $this->input->post('qty');

    $data_request_temp      = [];
    $data_request           = [];
    $transaksi_detail       = [];
    $transaksi              = [];
    $request_stock          = [];

    for($i=0;$i<count($id_produk);$i++)
    {
        for($j=0;$j<count($id_gudang);$j++)
        {
            $id_gudang_temp = explode(',',$id_gudang[$j]);
            for($k=0;$k<count($id_gudang_temp);$k++)
            {
                $data_request_temp[$id_produk[$j]][$k]['id_gudang'] = $id_gudang_temp[$k];
                $data_request_temp[$id_produk[$j]][$k]['berat'] = $berat[$j];
            }
        }

        for($j=0;$j<count($qty);$j++)
        {
            $qty_temp = explode(',',$qty[$j]);
            for($k=0;$k<count($qty_temp);$k++)
            {
                $data_request_temp[$id_produk[$j]][$k]['qty'] = $qty_temp[$k];
            }
        }
    }

            // print_r($data_request_temp);

    foreach($data_request_temp as $id_produk => $data)
    {
        foreach($data as $d)
        {
            if($d['id_gudang'] == "")
            {

            }
            else
            {

                $data_request[$d['id_gudang']][$id_produk]['id_gudang'] = $d['id_gudang'];
                $data_request[$d['id_gudang']][$id_produk]['qty'] = $d['qty'];
                $data_request[$d['id_gudang']][$id_produk]['berat'] = $d['berat'];

                $stok   = $this->mod_scm->getStok($d['id_gudang'], $id_produk, "stok_booking, stok_available");

                $update_stok['stok_booking']     = $stok->stok_booking + $d['qty'];
                $update_stok['stok_available']   = $stok->stok_available - $d['qty'];

                if ($periodeRequest == $this->periode) {
                        // $stock_status   = $this->addReportStockStatusTAG($d['id_gudang'], $id_produk, $d['qty']);
                    $this->mod_scm->updateStok($d['id_gudang'], $id_produk, $update_stok);
                }

            }
        }
    }

    foreach($data_request as $id_gudang_tujuan => $datas)
    {
        $transaksi['id_request']           = $id_request;
        $transaksi['id_tipe']              = 2;
        $transaksi['asal']                 = $id_gudang_tujuan;
        $transaksi['tujuan']               = $idGudangRequest;
        $transaksi['have_tag']             = 0;
        $transaksi['is_to_school']         = 0;
        $transaksi['status_transaksi']     = 1;
        $transaksi['created_date']         = date('Y-m-d H:i:s');
        $transaksi['created_by']           = $this->adm_id;
        $transaksi['updated_date']         = date('Y-m-d H:i:s');
        $transaksi['updated_by']           = $this->adm_id;

        $id_transaksi = $this->mod_scm->add('transaksi', $transaksi);
        $this->mod_scm->addTransaksiHistory($id_transaksi, 1);

        $requestStockTambahan['total_jumlah']       = 0;
        $requestStockTambahan['total_berat']        = 0;
        foreach ($datas as $id_produk => $values) {
            $transaksi_detail['id_transaksi']          = $id_transaksi;
            $transaksi_detail['id_produk']             = $id_produk;
            $transaksi_detail['jumlah']                = $values['qty'];
            $transaksi_detail['berat']                 = $values['berat'];

            $this->mod_scm->addDetail('transaksi_detail', $transaksi_detail);

                            // Count Total
            $requestStockTambahan['total_jumlah']   += $values['qty'];
            $requestStockTambahan['total_berat']    += $values['berat'];
        }
        $this->mod_scm->edit($id_transaksi, $requestStockTambahan);
    }

    $jumlah_total_request      = $this->mod_general->getAll('request_stock', '*', array('id_request' => $id_request))[0]->total_jumlah;
    $jumlah_total_transaksi    = $this->mod_scm->getDataTransaksi($id_request)[0]->jumlah;

    if((int) $jumlah_total_request == (int) $jumlah_total_transaksi)
    {
        $statusRequestStock['status'] = 2;
    }

            // print_r($transaksi_detail);

                // if ($stock_status) {
    if ($this->db->trans_status() === true) {
        if((int) $jumlah_total_request == (int) $jumlah_total_transaksi)
        {
            $this->mod_scm->update('request_stock', 'id_request = ' . $id_request, $statusRequestStock);
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Request stock #<b>' . $id_request . '</b> berhasil diproses.');
        $callBack = [
            "success"   => "true",
            "message"   => "Transaski telah berhasil dibuat",
            "redirect"  => "backmin/scmrequeststockpartial/indexrequeststockmasuk"
        ];
    } else {
        $this->db->trans_rollback();
        $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
        $callBack = [
            "success"   => "false",
            "message"   => "Gagal melakukan proses.",
            "redirect"  => "backmin/scmrequeststockpartial/detailrequeststockmasuk/$idRequest",
        ];
    }
                // } else {
                //     $this->db->trans_rollback();
                //     $this->session->set_flashdata('error', 'Gagal melakukan update laporan stock status.');
                //     $callBack = [
                //         "success"       => "false",
                //         "message"       => "Gagal melakukan update laporan stock status.",
                //         "redirect"      => "backmin/scmrequeststockPartial/detailRequestStockMasuk/$idRequest",
                //     ];
                // }

    echo json_encode($callBack);
}

public function processRequestStockMasuk()
{
    if ( ! $this->input->is_ajax_request()) {
        redirect(BACKMIN_PATH . '/scmpesanan/indexPesananMasuk', 'refresh');
    }

    $idRequest      = $this->input->post('id_request');
    if (in_array($this->adm_level, $this->auditor_area)) {
        $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
        $callBack   = [   
            "success"       => "false",
            "message"       => "Tidak dapat melakukan proses ini.",
            "redirect"      => "backmin/scmrequeststock/detailRequestStockMasuk/$idRequest",
        ];
    } else {
        $this->db->trans_begin();
        $periodeRequest         = (int)$this->input->post('periode_request');
        $idGudangRequest        = $this->input->post('id_gudang_request');
        $id_site                = $this->input->post('id_site');
        $isTAG                  = $this->input->post('is_tag');
        $isIntan                = $this->input->post('is_intan');
        $idProduk               = $this->input->post('id_product');
        $jumlah                 = $this->input->post('product_quantity');
        $berat                  = $this->input->post('weight');
        $tglTransaksi           = $this->input->post('tgl_transaksi') ? $this->input->post('tgl_transaksi') : '';
        $requestStock           = [];

        $where_exist            = "id_request = $idRequest";
        $exist_order            = $this->mod_scm->getRow('transaksi', $where_exist);
        $stock_status           = true;

        if ($exist_order == 0) {
            if ($isTAG == 1) {
                    //////////// BUKAN GUDANG UTAMA ////////////
                $idGudangTo     = $this->input->post('id_gudang_to');
                $count          = 0;
                foreach ($idGudangTo as $row => $id_gudang_tujuan) {
                    $requestStock[$id_gudang_tujuan][$count]['id_produk']   = $idProduk[$row];
                    $requestStock[$id_gudang_tujuan][$count]['jumlah']      = $jumlah[$row];
                    $requestStock[$id_gudang_tujuan][$count]['berat']       = $berat[$row] * $jumlah[$row];

                    $stok   = $this->mod_scm->getStok($id_gudang_tujuan, $idProduk[$row], "stok_booking, stok_available");

                    $updateStok['stok_booking']     = $stok->stok_booking + $jumlah[$row];
                    $updateStok['stok_available']   = $stok->stok_available - $jumlah[$row];

                    if ($periodeRequest == $this->periode) {
                        $stock_status   = $this->addReportStockStatusTAG($id_gudang_tujuan, $idProduk[$row], $jumlah[$row]);
                        $this->mod_scm->updateStok($id_gudang_tujuan, $idProduk[$row], $updateStok);
                    }
                    $count++;
                }
                foreach ($requestStock as $id_gudang_tujuan => $datas) {
                    $requestStockGudang['id_request']           = $idRequest;
                    $requestStockGudang['id_tipe']              = 2;
                    $requestStockGudang['asal']                 = $id_gudang_tujuan;
                    $requestStockGudang['tujuan']               = $idGudangRequest;
                    $requestStockGudang['have_tag']             = 0;
                    $requestStockGudang['is_to_school']         = 0;
                    $requestStockGudang['status_transaksi']     = 1;
                    $requestStockGudang['created_date']         = date('Y-m-d H:i:s');
                    $requestStockGudang['created_by']           = $this->adm_id;
                    $requestStockGudang['updated_date']         = date('Y-m-d H:i:s');
                    $requestStockGudang['updated_by']           = $this->adm_id;

                    $idTransaksi = $this->mod_scm->add('transaksi', $requestStockGudang);
                    $this->mod_scm->addTransaksiHistory($idTransaksi, 1);

                    $requestStockTambahan['total_jumlah']       = 0;
                    $requestStockTambahan['total_berat']        = 0;
                    foreach ($datas as $values) {
                        $requestDetail['id_transaksi']          = $idTransaksi;
                        $requestDetail['id_produk']             = $values['id_produk'];
                        $requestDetail['jumlah']                = $values['jumlah'];
                        $requestDetail['berat']                 = $values['berat'];

                        $this->mod_scm->addDetail('transaksi_detail', $requestDetail);
                            // Count Total
                        $requestStockTambahan['total_jumlah']   += $values['jumlah'];
                        $requestStockTambahan['total_berat']    += $values['berat'];
                    }
                    $this->mod_scm->edit($idTransaksi, $requestStockTambahan);
                }
                $statusRequestStock['status'] = 2;
            } else {
                    //////////// GUDANG UTAMA ////////////
                if ($isIntan != 1) {
                    $periodeHPP = $this->mod_scm->getPeriodeHPP($tglTransaksi);
                    $reportReceiving = [
                        'id_request'        => $idRequest,
                        'id_periode'        => $periodeHPP['id'],
                        'tax'               => 0,
                        'created_date'      => date('Y-m-d H:i:s')
                    ];
                    $this->mod_scm->add('report_receiving', $reportReceiving);

                    $statusRequestStock['tgl_transaksi']    = $tglTransaksi;
                    $statusRequestStock['id_periode']       = $periodeHPP['id'];
                }

                foreach ($idProduk as $row => $idProduct) {
                    $requestStock['detail_request_stock'][$row]['id_produk']    = $idProduct;
                    $requestStock['detail_request_stock'][$row]['jumlah']       = $jumlah[$row];
                    $requestStock['detail_request_stock'][$row]['berat']        = $berat[$row] * $jumlah[$row];

                    $stockGudang = $this->mod_scm->getStok($idGudangRequest, $idProduct, 'stok_fisik, stok_available');
                    if ($isIntan == 1) {
                        $dataStock['stok_fisik']            = $stockGudang->stok_fisik - $jumlah[$row];
                        $dataStock['stok_available']        = $stockGudang->stok_available - $jumlah[$row];
                    } else {
                        $dataStock['stok_fisik']            = $stockGudang->stok_fisik + $jumlah[$row];
                        $dataStock['stok_available']        = $stockGudang->stok_available + $jumlah[$row];
                    }
                    if ($periodeRequest == $this->periode) {
                        $this->mod_scm->updateStok($idGudangRequest, $idProduct, $dataStock);
                    }
                }

                if ($isIntan == 1) {
                    $requestStockGudang['asal']                 = $idGudangRequest;
                    $requestStockGudang['tujuan']               = 98;
                } else {
                    $requestStockGudang['asal']                 = 99;
                    $requestStockGudang['tujuan']               = $idGudangRequest;
                }

                $requestStockGudang['id_request']               = $idRequest;
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
                        $requestDetail['id_transaksi']          = $idTransaksi;
                        $requestDetail['id_produk']             = $data['id_produk'];
                        $requestDetail['jumlah']                = $data['jumlah'];
                        $requestDetail['berat']                 = $data['berat'];

                        $this->mod_scm->addDetail('transaksi_detail', $requestDetail);
                            // Count Total
                        $requestStockTambahan['total_jumlah']   += $data['jumlah'];
                        $requestStockTambahan['total_berat']    += $data['berat'];

                        if ($periodeRequest == $this->periode) {
                                // if ($isIntan == 1) {
                                //     $stock_status       = $this->addReportStockStatus($idGudangRequest, $data['id_produk'], $data['jumlah'], $isIntan);
                                // } else {
                            $stock_status       = $this->addReportStockStatus($idGudangRequest, $data['id_produk'], $data['jumlah'], $isIntan, $periodeHPP['id'], $tglTransaksi);
                                // }
                        }
                    }
                }
                $this->mod_scm->edit($idTransaksi, $requestStockTambahan);
                $statusRequestStock['status']   = 6;
            }

            if ($stock_status) {
                if ($this->db->trans_status() === true) {
                    $this->mod_scm->update('request_stock', 'id_request = ' . $idRequest, $statusRequestStock);

                        ## ACTION LOG USER
                    $logs['id_request'] = $idRequest;
                    $this->logger->logAction('Proses Request Stock', $logs);

                    $this->db->trans_commit();
                    $this->session->set_flashdata('success', 'Request stock #<b>' . $idRequest . '</b> berhasil diproses.');
                    $callBack = [
                        "success"   => "true",
                        "message"   => "Transaski telah berhasil dibuat",
                        "redirect"  => "backmin/scmrequeststock/indexRequestStockMasuk"
                    ];
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                    $callBack = [
                        "success"   => "false",
                        "message"   => "Gagal melakukan proses.",
                        "redirect"  => "backmin/scmrequeststock/detailRequestStockMasuk/$idRequest",
                    ];
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal melakukan update laporan stock status.');
                $callBack = [
                    "success"       => "false",
                    "message"       => "Gagal melakukan update laporan stock status.",
                    "redirect"      => "backmin/scmrequeststock/detailRequestStockMasuk/$idRequest",
                ];
            }
        } else {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Request sudah diproses.');
            $callBack = [
                "success"       => "false",
                "message"       => "Request sudah diproses.",
                "redirect"      => "backmin/scmrequeststock/detailRequestStockMasuk/$idRequest",
            ];
        }
    }
    echo json_encode($callBack, true);
}

public function processCencelRequestStockMasuk()
{
    if ( ! $this->input->is_ajax_request()) {
        redirect(BACKMIN_PATH . '/scmpesanan/indexPesananMasuk', 'refresh');
    }

    $id = $this->input->post('id_request');
    if (in_array($this->adm_level, $this->auditor_area)) {
        $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
        $callBack   = [   
            "success"       => "false",
            "message"       => "Tidak dapat melakukan proses ini.",
            "redirect"      => "backmin/scmrequeststock/detailRequestStockMasuk/$id",
        ];
    } else {
        $this->db->trans_begin();
        $requestStock['status'] = 7;
        $this->mod_scm->update('request_stock', 'id_request = ' . $id, $requestStock);

        if ($this->db->trans_status() === true) {
                ## ACTION LOG USER
            $logs['id_request'] = $id;
            $this->logger->logAction('Proses Request Stock Dibatalkan', $logs);

            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Request stock #<b>' . $id . '</b> berhasil dibatalkan.');
            $callBack = [
                "success"   => "true",
                "message"   => "Reqeust stock berhasil dibatalkan",
                "redirect"  => "backmin/scmrequeststock/indexRequestStockMasuk"
            ];
        } else {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
            $callBack = [
                "success"   => "false",
                "message"   => "Gagal melakukan proses ini.",
                "redirect"  => "backmin/scmrequeststock/detailRequestStockMasuk/$id",
            ];
        }
    }
    echo json_encode($callBack, true);
}

public function cetakRequest($id)
{
    if ($id) {
        $general = $this->mod_scm->getAll('request_stock', '*', 'id_request = '.$id)[0];

        if ($general) {

            $transaksi              = $this->mod_scm->getAll('transaksi', '*', 'id_request = '.$id)[0];
            $detil                  = $this->mod_scm->getListProductByRequestID($id, $general->id_gudang);
            $gudang_reqeust         = $this->mod_scm->getAll('master_gudang', '*', 'id_gudang = '.$general->id_gudang)[0];
            $postfix                = strtolower(str_replace(" ", "_", $gudang_reqeust->nama_gudang));

            $asal                   = "";
            $tujuan                 = "";
            $keterangan             = "";
            if ($general->is_tag == 1) {
                $asal               = $this->mod_scm->getAll('master_gudang', '*', 'id_gudang = '.$transaksi->asal)[0]->nama_gudang;
                $tujuan             = $gudang_reqeust->nama_gudang;
                $keterangan         = "Transfer Antar Gudang";
            } else {
                if ($general->is_intan == 1) {
                    $asal           = $gudang_reqeust->nama_gudang;
                    $tujuan         = 'Intan Pariwara';
                    $keterangan     = "Permintaan Intan";
                } else {
                    $tujuan         = $gudang_reqeust->nama_gudang;
                    $keterangan     = "Pengisian Stok Sendiri";
                }
            }

            $status = "";
            switch ($general->status) {
                case 1:
                $status = 'Dibuat';
                break;
                case 2:
                $status = 'Diproses';
                break;
                case 3:
                $status = 'Menunggu TAG';
                break;
                case 4:
                $status = 'SPK Dibuat';
                break;
                case 5:
                $status = 'Dikirim Ekspedisi';
                break;
                case 6:
                $status = 'Selesai';
                break;
            }

            if ($general->status >= 1 && $detil && $transaksi) {

                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle('#' . $id);

                $this->excel->getActiveSheet()
                ->setCellValue('B2', 'Request Stock '.$gudang_reqeust->nama_gudang)
                ->mergeCells('B2'.':G2');

                $this->excel->getActiveSheet()
                ->setCellValue('B4', 'Kode Request =')
                ->setCellValue('B5', 'Gudang Asal =')
                ->setCellValue('B6', 'Gudang Tujuan =')
                ->setCellValue('B7', 'Keterangan =')
                ->setCellValue('B8', 'Tanggal Request =')
                ->setCellValue('B9', 'Status =');
                $this->excel->getActiveSheet()
                ->getStyle('B4:B9')
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $this->excel->getActiveSheet()
                ->setCellValue('C4', $id)
                ->setCellValue('C5', $asal)
                ->setCellValue('C6', $tujuan)
                ->setCellValue('C7', $keterangan)
                ->setCellValue('C8', $general->created_date)
                ->setCellValue('C9', $status);
                $this->excel->getActiveSheet()
                ->setCellValue('A11', 'NO')
                ->setCellValue('B11', 'KODE BUKU')
                ->setCellValue('C11', 'JUDUL BUKU')
                ->setCellValue('D11', 'ISBN')
                ->setCellValue('E11', 'KATEGORI')
                ->setCellValue('F11', 'KELAS')
                ->setCellValue('G11', 'QUANTITY');

                $worksheet = $this->excel->getActiveSheet();
                $rowNumber = 12;
                $nomor     = 1;
                $total     = 0;
                foreach ($detil as $row) {
                    $worksheet->setCellValue('A' . $rowNumber, $nomor);
                    $worksheet->setCellValue('B' . $rowNumber, $row->kode_buku);
                    $worksheet->setCellValue('C' . $rowNumber, $row->product_name);
                    $worksheet->setCellValue('D' . $rowNumber, $row->isbn);
                    $worksheet->setCellValue('E' . $rowNumber, $row->type);
                    $worksheet->setCellValue('F' . $rowNumber, $row->kelas);
                    $worksheet->setCellValue('G' . $rowNumber, $row->product_quantity);
                    $total += (int)$row->product_quantity;
                    $rowNumber++;
                    $nomor++;
                }

                $this->excel->getActiveSheet()
                ->setCellValue('B'.$rowNumber, 'TOTAL')
                ->setCellValue('G'.$rowNumber, $total)
                ->mergeCells('B'.$rowNumber.':F'.$rowNumber);
                $this->excel->getActiveSheet()
                ->getStyle('B'.$rowNumber)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $filename = $id . '_' . $postfix . '.xls';
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0'); //no cache
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                $objWriter->save('php://output');

            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Status pesanan belum 'Dikirim ke logistik' !!!",
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Data request tidak ditemukan !!!",
            ]);
        }
    }
}

public function indexRekapitulasiRequestStockProcess()
{
    $data['listgudang']     = $this->mod_scm->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');
    $data['page_title']     = 'Rekapitulasi Permintaan Stock Proses';
    $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_requeststock_process', $data, true);
    $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_requeststock_process_js', '', true);
    $data['script_css']     = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_requeststock_process_css', '', true);

    $this->load->view(BACKMIN_PATH . '/main', $data);
}

public function cetakRekapitulasiRequestStockProcess()
{
    if ( ! $this->input->is_ajax_request()) {
        return false;
    } else {
        try {
            $id_gudang          = $this->input->post('id_gudang');
            $start_date         = str_replace('/', '-', $this->input->post('start_date'));
            $end_date           = str_replace('/', '-', $this->input->post('end_date'));
            $daytime            = strtotime(date('Y-m-d H:i:s'));

            $start_time         = strtotime($start_date);
            $end_time           = strtotime($end_date);

            $diff_month         = 1 + (date("Y", $end_time) - date("Y", $start_time)) * 12;
            $diff_month         += date("m", $end_time) - date("m", $start_time);

            if ($diff_month > 3) {
                return false;
            } else {
                $folder         = "uploads".DIRECTORY_SEPARATOR."scm".DIRECTORY_SEPARATOR."rekapitulasi".DIRECTORY_SEPARATOR;
                $filename       = 'rekapitulasi_request_stock_proses_' . $start_date . '_' . $end_date . '_' . $daytime . '.xlsx';
                $path_fle       = $folder . $filename;

                $startDate      = $start_date . ' 00:00:00';
                $finishDate     = $end_date . ' 23:59:59';

                $where = '';
                if ($id_gudang)
                    $where = $id_gudang;

                $list_request_stock = $this->mod_scm->getRekapitulasiRequestStockProses($startDate, $finishDate, $where, $order_by = "");
                $header = [
                    'ID Request'       => 'string',
                    'Tgl Request'      => 'datetime',
                    'Kode Buku'        => 'string',
                    'Judul Buku'       => 'string',
                    'Kategori'         => 'string',
                    'Kelas'            => 'string',
                    'Berat'            => 'string',
                    'Permintaan Stock' => 'integer',
                    'Berat Total'      => 'integer',
                    'Koli'             => 'integer',
                    'Jumlah Per Koli'  => 'integer',
                    'Sisa Koli'        => 'integer',
                    // 'Jenis/Tipe'       => 'string',
                    'Gudang Pengirim'  => 'string',
                    'Gudang Request'   => 'string',
                    'Status'           => 'string',
                    'Tgl Status'       => 'datetime'
                    // 'No OEF'           => 'string',
                    // 'Tahap'            => 'string'
                ];
                $writer = new XLSXWriter();
                $writer->writeSheetHeader('Sheet1', $header);
                foreach ($list_request_stock as $row) {
                    $value = [
                        $row['id_request'],
                        $row['tgl_request'],
                        $row['kode_buku'],
                        $row['judul_buku'],
                        $row['kategori'],
                        $row['kelas'],
                        $row['berat'],
                        $row['quantity'],
                        $row['berat_total'],
                        $row['koli'],
                        $row['jumlah_per_koli'],
                        $row['sisa_koli'],
                        // $row['request_type'],
                        $row['gudang_asal'],
                        $row['gudang_tujuan'],
                        $row['request_status'],
                        $row['tgl_status'],
                        // $row['no_oef'],
                        // ''
                    ];
                    $writer->writeSheetRow('Sheet1', $value);
                }

                $writer->writeToFile(FCPATH . $path_fle);
                chmod($path_fle, 0777);
                    // force_download($path_fle, null);
                $response = [
                    'success'   => 'true',
                    'pathfile'  => $path_fle
                ];

                echo json_encode($response);    
            }
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage()
            ];
            echo json_encode($callBack, true);
        }
    }
}

public function indexRekapitulasiPemenuhanRequestStock()
{
    $data['listgudang']     = $this->mod_scm->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');
    $data['page_title']     = 'Rekapitulasi Pemenuhan Permintaan Stock Gudang';
    $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_pemenuhan_requeststock', $data, true);
    $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_pemenuhan_requeststock_js', '', true);
    $data['script_css']     = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_pemenuhan_requeststock_css', '', true);

    $this->load->view(BACKMIN_PATH . '/main', $data);
}

public function cetakRekapitulasiPemenuhanRequestStock()
{
    if ( ! $this->input->is_ajax_request()) {
        return false;
    } else {
        try {
            $id_gudang          = $this->input->post('id_gudang');
            $start_date         = str_replace('/', '-', $this->input->post('start_date'));
            $end_date           = str_replace('/', '-', $this->input->post('end_date'));
            $daytime            = strtotime(date('Y-m-d H:i:s'));

            $start_time         = strtotime($start_date);
            $end_time           = strtotime($end_date);

            $diff_month         = 1 + (date("Y", $end_time) - date("Y", $start_time)) * 12;
            $diff_month         += date("m", $end_time) - date("m", $start_time);

            if ($diff_month > 3) {
                return false;
            } else {
                $folder         = "uploads".DIRECTORY_SEPARATOR."scm".DIRECTORY_SEPARATOR."rekapitulasi".DIRECTORY_SEPARATOR;
                $filename       = 'rekapitulasi_pemenuhan_request_stock_' . $start_date . '_' . $end_date . '_' . $daytime . '.xlsx';
                $path_fle       = $folder . $filename;

                $startDate      = $start_date . ' 00:00:00';
                $finishDate     = $end_date . ' 23:59:59';

                $where = '';
                if ($id_gudang)
                    $where = $id_gudang;

                $list_request_stock = $this->mod_scm->getRekapitulasiPemenuhanRequestStock($startDate, $finishDate, $where, $order_by = "");
                $header = [
                    'ID Request'                 => 'string',
                    'Tgl Request'                => 'datetime',
                    'Gudang Request'             => 'string',
                    'Kode Buku'                  => 'string',
                    'Judul Buku'                 => 'string',
                    'Kategori'                   => 'string',
                    'Kelas'                      => 'string',
                    // 'Berat'                   => 'string',
                    'Permintaan Stock'           => 'integer',
                    // 'Berat Total'             => 'integer',
                    // 'Koli'                    => 'integer',
                    // 'Jumlah Per Koli'         => 'integer',
                    // 'Sisa Koli'               => 'integer',
                    'Permintaan Stock Terpenuhi' => 'integer',
                    'Sisa Permintaan Stock'      => 'integer',
                    'status_permintaan'          => 'string'
                ];
                $writer = new XLSXWriter();
                $writer->writeSheetHeader('Sheet1', $header);
                foreach ($list_request_stock as $row) {
                    $value = [
                        $row['id_request'],
                        $row['tgl_request'],
                        $row['gudang_request'],
                        $row['kode_buku'],
                        $row['judul_buku'],
                        $row['kategori'],
                        $row['kelas'],
                        // $row['berat'],
                        $row['qty_request'],
                        // $row['berat_total'],
                        // $row['koli'],
                        // $row['jumlah_per_koli'],
                        // $row['sisa_koli'],
                        $row['qty_terpenuhi'],
                        $row['sisa_request'],
                        $row['request_status']
                    ];
                    $writer->writeSheetRow('Sheet1', $value);
                }

                $writer->writeToFile(FCPATH . $path_fle);
                chmod($path_fle, 0777);
                    // force_download($path_fle, null);
                $response = [
                    'success'   => 'true',
                    'pathfile'  => $path_fle
                ];

                echo json_encode($response);    
            }
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage()
            ];
            echo json_encode($callBack, true);
        }
    }
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

public function addReportStockStatusTAG($id_gudang, $id_produk, $jumlah)
{
    $this->db->trans_begin();
    if ($id_gudang && $id_produk) {
        $today  = date('Y-m-d H:i:s');
        $month  = date('n');
        $year   = date('Y');

        $stock_status                       = $this->mod_scm->getLastStockStatus($now = 1, $id_gudang, $id_produk, $month, $year);
        $report                             = [];
        if ($stock_status) {
                // In same month and year
            $new_stok_booking               = (int)($stock_status['stok_booking'] + $jumlah);
            $new_stok_available             = (int)($stock_status['stok_available'] - $jumlah);
            $new_allocated_cost             = $new_stok_booking * $stock_status['average_cost'];

            $report = [
                'stok_booking'              => $new_stok_booking,
                'stok_available'            => $new_stok_available,
                'allocated_cost'            => $new_allocated_cost,
                'updated_date'              => $today
            ];

            $this->mod_scm->update("report_stock_status", "id = " . $stock_status['id'], $report);
        } else {
                // In different month and year
            $last_stock_status              = $this->mod_scm->getLastStockStatus($now = 0, $id_gudang, $id_produk, $month, $year);

            $report = [
                'id_periode'                => $last_stock_status['id_periode'],
                'id_gudang'                 => $id_gudang,
                'id_produk'                 => $id_produk,
                'bulan'                     => $month,
                'tahun'                     => $year
            ];

            if ($last_stock_status) {
                    // Have record below this month
                $new_stok_booking           = (int)($last_stock_status['stok_booking'] + $jumlah);
                $new_stok_available         = (int)($last_stock_status['stok_available'] - $jumlah);
                $new_allocated_cost         = $new_stok_booking * $last_stock_status['average_cost'];

                $report += [
                    'tgl_transaksi'         => $last_stock_status['tgl_transaksi'],
                    'stok_fisik'            => (int)$last_stock_status['stok_fisik'],
                    'stok_booking'          => $new_stok_booking,
                    'stok_available'        => $new_stok_available,
                    'average_cost'          => $last_stock_status['average_cost'],
                    'total_cost'            => $last_stock_status['total_cost'],
                    'allocated_cost'        => $new_allocated_cost,
                    'created_date'          => $today
                ];

                $this->mod_scm->add("report_stock_status", $report);
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

public function testing()
{
    $id_request = '2724';
    $jumlah_total_request      = $this->mod_general->getAll('request_stock', '*', array('id_request' => $id_request))[0]->total_jumlah;
    $jumlah_total_transaksi    = $this->mod_scm->getDataTransaksi($id_request)[0]->jumlah;

    echo $jumlah_total_request."---".$jumlah_total_transaksi;
}

}
