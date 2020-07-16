<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'third_party/PhpExportExcel.php';
require_once APPPATH . 'third_party/xlsxwriter.class.php';

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_gudangproduction $mod_gudangproduction
 */
class Gudangproduction extends MY_Controller
{
    /**
     * Request stock
     */
    
    public function __construct()
    {
        parent::__construct();
        // if (!in_array($this->adm_level, $this->backmin_gudang_area)) {
        //     redirect(BACKMIN_PATH);
        // }
        $this->load->model('mod_general');
        $this->load->model('mod_gudangproduction');
    }

    public function check_oef_limit()
    {
        $kode_buku = $this->input->post("kode_buku");
        $no_oef = $this->input->post("no_oef");

        // $data = $this->mod_general->getAll('production_order', "*", 'no_oef="'.$no_oef.'" AND kode_buku="'. $kode_buku .'"');
        $data = $this->mod_general->getAll('production_order', "*", 'no_oef="'.$no_oef.'"');
        echo json_encode($data);
    }

    public function index()
    {
        redirect(BACKMIN_PATH . '/gudangproduction/order');
    }

    public function order()
    {
        $data['page_title']     = 'List Prodution Order';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/production/list', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/production/list_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function listOrder()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
            `no_oef` AS no_oef,
            `kode_buku` AS kode_buku,
            `judul` AS judul,
            `jumlah_request` AS jumlah_request,
            `jumlah_kirim` AS jumlah_kirim,
            `catatan_alokasi` AS catatan_alokasi,
            CASE STATUS 
            WHEN 0 THEN CONCAT("<center><span class=\'label label-warning\'>Canceled</span></center>") 
            WHEN 1 THEN CONCAT("<center><span class=\'label label-default\'>Active</span></center>") 
            WHEN 2 THEN CONCAT("<center><span class=\'label label-warning\'>Closed</span></center>") END AS stat_production,
            `created_date` AS created_date');
        $this->datatables->from('production_order');
        $this->datatables->edit_column('no_oef', '<center><a href="' . base_url(BACKMIN_PATH . '/gudangproduction/detailorder/$1') . '">#$1</a></center>', 'no_oef');
        $this->output->set_output($this->datatables->generate());
    }

    public function detailOrder($no_oef)
    {
        if ($no_oef) {
            $data['page_title']     = 'Detil Permintaan Stok';
            $data['detail']         = $this->mod_general->detailData('production_order', 'no_oef', $no_oef);
            $data['admin']         = $this->mod_general->detailData('employee', 'id_employee', $data['detail']['created_by']);
            $data['history']         = $this->mod_gudangproduction->getDataHistory($data['detail']['id']);
            $data['gudang']         = $this->mod_general->detailData('master_gudang', 'id_gudang', $data['detail']['id_gudang']);

            $data['status']         = '';
            switch ($data['detail']['status']) {
                case 0:
                $data['status'] = 'Canceled';
                break;
                case 1:
                $data['status'] = 'Active';
                break;
                case 2:
                $data['status'] = 'Closed';
                break;
            }

            $check_proses = "";

            $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/production/detil', $data, true);
            $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/production/detil_js', '', true);
            $this->load->view(BACKMIN_PATH . '/main', $data);
        } else {
            redirect(BACKMIN_PATH . '/gudangproduction');
        }
    }

    public function detailOrderpost()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $no_oef = $this->input->post('no_oef');
        $catatan = '';

        if($this->input->post('catatan'))
        {
            $catatan = $this->input->post('catatan');
        }

        $data = array(
            'status' => $status,
            'notes' => $catatan
        );

        $data_history = array(
            'id_production_order' => $id,
            'status' => $status,
            'notes' => $catatan,
            'created_by' => $this->adm_id
        );

        $query = $this->mod_gudangproduction->edit('production_order', 'id="'.$id.'"', $data);
        if($query)
        {
            $this->mod_gudangproduction->addDetail('production_order_history',$data_history);
            $this->session->set_flashdata('success','Berhasil mengubah status');
            redirect(BACKMIN_PATH.'/gudangproduction/order','refresh');
        }
        else
        {
            $this->session->set_flashdata('error','Gagal mengubah status');
            redirect(BACKMIN_PATH.'/gudangproduction/detailOrder/'.$no_oef,'refresh');
        }
    }

    public function detailOrderUpdate($id)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $data['gudang'] = $this->mod_general->getAll('master_gudang', '*', array('status' => 1));
        $data['detail'] = $this->mod_gudangproduction->get_data_production($id);
        $this->load->view('backmin/gudang/production/edit_popup', $data);
    }

    public function detailOrderUpdatePost()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $id = $this->input->post('id');
        $no_oef = $this->input->post('no_oef');
        $id_gudang = $this->input->post('id_gudang');
        $kode_buku = $this->input->post('kode_buku');
        $judul = $this->input->post('judul');
        $jumlah_request = $this->input->post('jumlah_request');
        $jumlah_kirim = $this->input->post('jumlah_kirim');
        $updated_date = date('Y-m-d H:i:s');
        $updated_by = $this->adm_id;

        $data = array(
            'id_gudang'         => $id_gudang,
            'kode_buku'         => $kode_buku,
            'judul'             => $judul,
            'jumlah_request'    => $jumlah_request,
            'jumlah_kirim'      => $jumlah_kirim,
            'updated_date'      => $updated_date,
            'updated_by'        => $updated_by
        );

        $query = $this->mod_general->updateData('production_order', $data, 'id', $id);
        if($query)
        {
            $callBack = [
                'success'   => 'true',
                'message'   => "Berhasil mengubah data order produksi"
            ];
        }
        else
        {
            $callBack = array(
                'success'   => 'false',
                'message'   => "Gagal mengubah data order produksi"
            );
        }

        echo json_encode($callBack);
    }

    public function add()
    {
        $data['page_title']               = 'Production Order';
        $data['listBukuSD']               = $this->mod_gudangproduction->getBookLevel('1-6');
        $data['listBukuSMP']              = $this->mod_gudangproduction->getBookLevel('7-9');
        $data['listBukuSMA']              = $this->mod_gudangproduction->getBookLevel('10-12');
        $data['listBukuSMP_ktsp']         = $this->mod_gudangproduction->getBookLevelKTSP('7-9');
        $data['listBukuSMK']              = $this->mod_gudangproduction->getBookLevelSMK('10-12');
        $data['listBukuLiterasi']         = $this->mod_gudangproduction->getBookLiterasi();
        $data['listBukuPengayaan']        = $this->mod_gudangproduction->getBookPengayaan();
        $data['listBukuReferensi']        = $this->mod_gudangproduction->getBookReferensi();
        $data['listBukuPandik']           = $this->mod_gudangproduction->getBookPandik();
        $data['listProductIt']            = $this->mod_gudangproduction->getProductIt();
        $data['listProductCovid']         = $this->mod_gudangproduction->getProductCovid();
        $data['listAlatTulis']            = $this->mod_gudangproduction->getAlatTulis();
        $data['listBukuPendampingK13SD']  = $this->mod_gudangproduction->getBookPendampingK13('1-6');
        $data['listBukuPendampingK13SMP'] = $this->mod_gudangproduction->getBookPendampingK13('7-9');
        $data['listBukuPendampingK13SMA'] = $this->mod_gudangproduction->getBookPendampingK13('10-12');
        $data['listBukuPeminatanSmaMa']   = $this->mod_gudangproduction->getBookPeminatanSmaMA('10-12');
        $data['listBukuHetK13SD']         = $this->mod_gudangproduction->getBookHetK13('1-6');
        $data['listBukuHetK13SMP']        = $this->mod_gudangproduction->getBookHetK13('7-9');
        $data['listBukuHetK13SMA']        = $this->mod_gudangproduction->getBookHetK13('10-12');
        $data['listGudang']               = $this->mod_gudangproduction->getlistGudang();

        // $data['tipeGudang']         = $this->mod_gudangproduction->getAll('master_gudang', 'is_utama', 'id_gudang=' . $this->adm_id_gudang)[0];
        $data['content']            = $this->load->view(BACKMIN_PATH . '/gudang/production/add', $data, true);
        $data['script_js']          = $this->load->view(BACKMIN_PATH . '/gudang/production/add_js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function addConfirmation()
    {
        $requestDetail['id_produk']     = explode(',', $this->input->post('request_id_produk'));
        $requestDetail['berat']         = explode(',', $this->input->post('request_berat'));
        $requestDetail['jumlah']        = explode(',', $this->input->post('request_jumlah'));
        $requestDetail['no_oef']        = explode(',', $this->input->post('request_no_oef'));
        $requestDetail['id_gudang']        = explode(',', $this->input->post('request_id_gudang'));

        $data['list_request']   = [];
        $count                  = 0;
        foreach ($requestDetail['id_produk'] as $rows => $id_produk) {
            $data['list_request'][$count]           = $this->mod_gudangproduction->getListProduct('a.id_product AS id_product, a.kode_buku AS kode_buku, a.reference AS isbn, a.name AS judul, a.weight AS weight, b.name AS kelas, c.name AS type', 'id_product=' . $id_produk)[0];
            $data['list_request'][$count]->jumlah   = $requestDetail['jumlah'][$count];
            $data['list_request'][$count]->no_oef   = $requestDetail['no_oef'][$count];
            $data['list_request'][$count]->id_gudang   = $requestDetail['id_gudang'][$count];
            $data['list_request'][$count]->nama_gudang   = $this->mod_gudangproduction->getAll('master_gudang','nama_gudang', array('id_gudang'=> $requestDetail['id_gudang'][$count]))[0]->nama_gudang;
            $count++;
        }

        // $data['is_tag']         = $this->input->post('is_tags');
        // $data['tipeGudang']     = $this->mod_gudangproduction->getAll('master_gudang', 'is_utama', 'id_gudang=' . $this->adm_id_gudang)[0];
        $data['page_title']     = 'Konfirmasi Permintaan Stok';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/gudang/production/add_konfirmasi', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/gudang/production/add_js', '', true);
        
        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function addConfirmationPost()
    {
        if ( ! $this->input->is_ajax_request()) 
        {
            redirect(BACKMIN_PATH . '/gudangproduction', 'refresh');
        }
        
        // if (in_array($this->adm_level, $this->auditor_area)) 
        // {
        //     $this->session->set_flashdata('error', 'Maaf, anda tidak dapat melakukan proses ini.');
        //     $callBack   = [   
        //         "success"       => "false",
        //         "message"       => "Tidak dapat melakukan proses ini.",
        //         "redirect"      => "backmin/gudangrequeststock/add",
        //     ];
        // } 
        else 
        {
            $this->db->trans_begin();
            // $request['id_gudang']       = $this->adm_id_gudang;
            // $request['is_tag']          = $this->input->post('is_tag');
            // $request['is_intan']        = 2;
            // $request['status']          = 1;
            // $request['periode']         = date('Y');
            // $request['created_date']    = date('Y-m-d H:i:s');
            // $request['created_by']      = $this->adm_id;
            // $request['updated_date']    = date('Y-m-d H:i:s');
            // $request['updated_by']      = $this->adm_id;

            // $id = $this->mod_gudang->add('request_stock', $request);
            
            // $test = $this->input->post('kode_buku');
            // print_r($test);
            // exit();

            // $requestDetail['kode'] = explode(',', $this->input->post('id_produk'));
            // $requestDetail['kode_buku']     = explode(',', $this->input->post('kode_buku'));
            // $requestDetail['judul']     = explode(',', $this->input->post('judul'));
            // $requestDetail['jumlah_request']    = explode(',', $this->input->post('jumlah'));
            // $requestDetail['no_oef']    = explode(',', $this->input->post('no_oef'));
            // $requestDetail['id_gudang']    = explode(',', $this->input->post('id_gudang'));

            $requestDetail['kode'] = $this->input->post('id_produk');
            $requestDetail['kode_buku']     = $this->input->post('kode_buku');
            $requestDetail['judul']     = $this->input->post('judul');
            $requestDetail['jumlah_request']    = $this->input->post('jumlah');
            $requestDetail['no_oef']    = $this->input->post('no_oef');
            $requestDetail['id_gudang']    = $this->input->post('id_gudang');

            // print_r($requestDetail);
            // exit();

            $dataRequestDetail          = [];
            foreach ($requestDetail as $field => $data) {
                foreach ($data as $row => $value) {
                    $dataRequestDetail[$row][$field] = $value;
                }
            }

            // print_r($dataRequestDetail);
            // exit();

            // $id = $this->mod_gudangproduction->addBatch('production_order', $request);

            // print_r($dataRequestDetail);
            // $callBack = [
            //         "success"   => "false",
            //         "message"   => "Gagal melakukan proses ini.",
            //         "data"  => $dataRequestDetail,
            //     ];

            // $dataTambahanRequest['total_jumlah']    = 0;
            // $dataTambahanRequest['total_berat']     = 0;
            foreach ($dataRequestDetail as $rows => $values) {
                $dataDetail['no_oef']           = $values['no_oef'];
                $dataDetail['id_gudang']        = $values['id_gudang'];
                $dataDetail['kode_buku']        = $values['kode_buku'];
                $dataDetail['judul']            = $values['judul'];
                $dataDetail['jumlah_request']   = $values['jumlah_request'];
                $dataDetail['created_by']       = $this->adm_id;
                $dataDetail['updated_by']       = $this->adm_id;

                $this->mod_gudangproduction->addDetail('production_order', $dataDetail);

                // $dataTambahanRequest['total_jumlah']    += $values['jumlah'];
                // $dataTambahanRequest['total_berat']     += $values['berat'] * $values['jumlah'];
            }
            // $this->mod_gudang->edit('request_stock', 'id_request =' . $id, $dataTambahanRequest);

            if ($this->db->trans_status() === true) {
                ## ACTION LOG USER
                // $logs['id_request'] = $id;
                // $this->logger->logAction('Proses Request Stock Dibuat', $logs);

                $this->db->trans_commit();
                $this->session->set_flashdata('success', 'Data production order berhasil disimpan.');
                $callBack = [
                    "success"   => "true",
                    "message"   => "Data production order berhasil disimpan.",
                    "redirect"  => "backmin/gudangproduction"
                ];
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal melakukan proses ini.');
                $callBack = [
                    "success"   => "false",
                    "message"   => "Gagal melakukan proses ini.",
                    "redirect"  => "backmin/gudangproduction/add",
                ];
            }
        }
        echo json_encode($callBack);
    }

    public function indexReceivingReport()
    {
        $data['listgudang']     = $this->mod_gudangproduction->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');
        $data['page_title']     = 'Laporan Rekapitulasi Receiving Stock Gudang';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_receiving', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_receiving_js', '', true);
        $data['script_css']     = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_receiving_css', '', true);

        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function cetakReceivingReport()
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
                    $filename       = 'laporan_rekap_receiving_stock_' . $start_date . '_' . $end_date . '_' . $daytime . '.xlsx';
                    $path_fle       = $folder . $filename;

                    $startDate      = $start_date . ' 00:00:00';
                    $finishDate     = $end_date . ' 23:59:59';

                    $where = '';
                    if ($id_gudang)
                        $where = $id_gudang;

                    $list_request_stock = $this->mod_gudangproduction->getRekapitulasiReceiving($startDate, $finishDate, $where, $order_by = "");
                    $header = [
                        'ID Receiving'     => 'string',
                        'Tgl Receiving'    => 'datetime',
                        'Kode Buku'        => 'string',
                        'No OEF'           => 'string',
                        'Judul Buku'       => 'string',
                        'Kategori'         => 'string',
                        'Kelas'            => 'string',
                        'Berat'            => 'string',
                        'QTY Receiving'    => 'integer',
                        'Berat Total'      => 'string',
                        'Koli'             => 'integer',
                        'Jumlah Per Koli'  => 'integer',
                        'Sisa Koli'        => 'integer',
                        'Jenis/Tipe'       => 'string',
                        // 'Gudang Asal'   => 'string',
                        // 'Gudang Tujuan' => 'string',
                        'Gudang'           => 'string',
                        'Status'           => 'string',
                        'Tgl Status'       => 'datetime',
                        'Tahap'            => 'string'
                    ];
                    $writer = new XLSXWriter();
                    $writer->writeSheetHeader('Sheet1', $header);
                    foreach ($list_request_stock as $row) {
                        $value = [
                            $row['id_request'],
                            $row['tgl_request'],
                            $row['kode_buku'],
                            $row['no_oef'],
                            $row['judul_buku'],
                            $row['kategori'],
                            $row['kelas'],
                            $row['berat'],
                            $row['quantity'],
                            $row['berat_total'],
                            $row['koli'],
                            $row['jumlah_per_koli'],
                            $row['sisa_koli'],
                            $row['request_type'],
                            // $row['gudang_request'],
                            // $row['gudang_tujuan'],
                            $row['gudang_tujuan'],
                            $row['request_status'],
                            $row['tgl_status'],
                            ''
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

    public function indexProductionReport()
    {
        $data['listgudang']     = $this->mod_gudangproduction->getAll('master_gudang', '*', 'status = 1', 'nama_gudang ASC');
        $data['page_title']     = 'Laporan Rekapitulasi Production';
        $data['content']        = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_production', $data, true);
        $data['script_js']      = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_production_js', '', true);
        $data['script_css']     = $this->load->view(BACKMIN_PATH . '/scm/laporan/list_rekap_production_css', '', true);

        $this->load->view(BACKMIN_PATH . '/main', $data);
    }

    public function cetakProductionReport()
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
                    $filename       = 'laporan_rekap_production_' . $start_date . '_' . $end_date . '_' . $daytime . '.xlsx';
                    $path_fle       = $folder . $filename;

                    $startDate      = $start_date . ' 00:00:00';
                    $finishDate     = $end_date . ' 23:59:59';

                    $where = '';
                    if ($id_gudang)
                        $where = $id_gudang;

                    $list_request_stock = $this->mod_gudangproduction->getRekapitulasiProduction($startDate, $finishDate, $where, $order_by = "");
                    $header = [
                        'No OEF'                => 'string',
                        'Tgl Production'        => 'datetime',
                        'Nama Gudang'           => 'string',
                        'Kode Buku'             => 'string',
                        'Judul Buku'            => 'string',
                        'Kategori'              => 'string',
                        'Kelas'                 => 'string',
                        'Total Quota'           => 'integer',
                        'Total Quota Terpenuhi' => 'integer',
                        'Sisa Quota'            => 'integer',
                        'Status'                => 'string',
                        'Keterangan'            => 'string'
                    ];
                    $writer = new XLSXWriter();
                    $writer->writeSheetHeader('Sheet1', $header);
                    foreach ($list_request_stock as $row) {
                        $value = [
                            $row['no_oef'],
                            $row['tgl_production'],
                            $row['nama_gudang'],
                            $row['kode_buku'],
                            $row['judul'],
                            $row['kategori'],
                            $row['kelas'],
                            $row['quota_total'],
                            $row['quota_terpenuhi'],
                            $row['quota_sisa'],
                            $row['status_production'],
                            $row['keterangan']
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

    public function get_data_buku()
    {
        $kode_buku = $this->input->post('kode_buku');
        $data_buku = $this->mod_general->detailData('product', 'kode_buku', $kode_buku);
        echo json_encode($data_buku);
    }
}
