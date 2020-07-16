<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_product $mod_product
 * @property Mod_mitra $mod_mitra
 * @property Mymail $mymail
 */
class Ongkir extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->load->model('mod_product');
        $this->table = 'product';
        $this->_output = [];
    }

    public function index()
    {
        $this->_output['content'] = $this->load->view('admin/ongkir/list', '', true);
        $this->_output['script_js'] = $this->load->view('admin/ongkir/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function listOngkir()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/product');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
            `id` as id, 
            `kd_prop` as kd_prop, 
            `provinsi` as provinsi, 
            `kd_kab_kota` as kd_kab_kota, 
            `kabupaten` as kabupaten, 
            `kd_kec` as kd_kec, 
            `kecamatan` as kecamatan, 
            `tarif_per_kg_komp_eco_min30kg` as tarif_per_kg_komp_eco_min30kg, 
            `tarif_per_kg_komp_reg_min1kg` as tarif_per_kg_komp_reg_min1kg, 
            `tarif_per_kg_lainlain_eco_min30kg` as tarif_per_kg_lainlain_eco_min30kg, 
            `tarif_per_kg_perlindungandiri_noncair_reg_min1kg` as tarif_per_kg_perlindungandiri_noncair_reg_min1kg

        ');

        $this->datatables->from('master_ongkos_kirim');
        $this->datatables->edit_column('id', '<a href="' . base_url(ADMIN_PATH . '/ongkir/detail/$1') . '">$1</a>', 'id');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($id)
    {
        $data['detil'] = $this->mod_product->getList("master_ongkos_kirim",'*',"id='$id'");
        $this->_output['content'] = $this->load->view('admin/ongkir/edit', $data, TRUE);
        $this->_output['script_js'] = $this->load->view('admin/ongkir/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function detail_post()
    {
        $id = $this->input->post('id');
        $kd_prop = $this->input->post('kd_prop');
        $provinsi = $this->input->post('provinsi');
        $kd_kab_kota = $this->input->post('kd_kab_kota');
        $kabupaten = $this->input->post('kabupaten');
        $kd_kec = $this->input->post('kd_kec');
        $kecamatan = $this->input->post('kecamatan');
        $tarif_per_kg_komp_eco_min30kg = $this->input->post('tarif_per_kg_komp_eco_min30kg');
        $tarif_per_kg_komp_reg_min1kg = $this->input->post('tarif_per_kg_komp_reg_min1kg');
        $tarif_per_kg_lainlain_eco_min30kg = $this->input->post('tarif_per_kg_lainlain_eco_min30kg');
        $tarif_per_kg_perlindungandiri_noncair_reg_min1kg = $this->input->post('tarif_per_kg_perlindungandiri_noncair_reg_min1kg');

        $data = array(
            'id' => $id
            ,'kd_prop' => $kd_prop
            ,'provinsi' => $provinsi
            ,'kd_kab_kota' => $kd_kab_kota
            ,'kabupaten' => $kabupaten
            ,'kd_kec' => $kd_kec
            ,'kecamatan' => $kecamatan
            ,'tarif_per_kg_komp_eco_min30kg' => $tarif_per_kg_komp_eco_min30kg
            ,'tarif_per_kg_komp_reg_min1kg' => $tarif_per_kg_komp_reg_min1kg
            ,'tarif_per_kg_lainlain_eco_min30kg' => $tarif_per_kg_lainlain_eco_min30kg
            ,'tarif_per_kg_perlindungandiri_noncair_reg_min1kg' => $tarif_per_kg_perlindungandiri_noncair_reg_min1kg
        );

        try {
            if (in_array($this->adm_level, $this->backoffice_admin_area) && in_array($this->adm_level, $this->auditor_area)) {
                $callBack   = [   
                    "success"   => "false",
                    "message"   => "Maaf, anda tidak dapat melakukan proses ini."
                ];
            } else {
                $this->db->trans_begin();
                $updateProduct = $this->mod_general->updateData('master_ongkos_kirim', $data, 'id', $id);
                if ($updateProduct) {
                    $this->db->trans_commit();
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.'
                    ];
                    $this->session->set_flashdata('msg_success', 'Data ongkos kirim: <b>' . $kecamatan . '</b> berhasil <b>DIPERBARUI</b></p>');
                } else {
                    $this->db->trans_rollback();
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Failed to update ongkos kirim.'
                    ];
                }                
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: ' . $e->getMessage()
            ];
            echo json_encode($callBack, true);
        }
    }

    function import()
    {
        // $this->load->view('admin/product/upload2');
        $this->_output['content'] = $this->load->view('admin/ongkir/upload', '', true);
        $this->_output['script_js'] = ''; //$this->load->view('admin/product/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function importPost()
    {
        $this->db->trans_begin();

        $file = $_FILES['mikon_file']['tmp_name'];
 
        //load the excel library
        $this->load->library('excel');
         
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);

        $sheetActive = $objPHPExcel->getSheet(0);
        // $sheetActive = $objPHPExcel->getActiveSheet();
         
        //get only the Cell Collection
        // $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $cell_collection = $sheetActive->getCellCollection();
         
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            $column = $sheetActive->getCell($cell)->getColumn();
            $row = $sheetActive->getCell($cell)->getRow();
            $data_value = $sheetActive->getCell($cell)->getValue();
         
            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }

        //send the data in an array format
        $data['header'] = $header;
        $data['values'] = $arr_data;

        $productArray = [];
        $dataArray = [];

        $category_product = [];
        $category_product_1 = [];
        $category_product_2 = [];

        foreach($arr_data as $vv){
            $ongkirArray[] = array(
            'id'=> 0,
            'kd_prop' => $vv['B'],
            'provinsi' => $vv['C'],
            'kd_kab_kota' => $vv['D'],
            'kabupaten' => $vv['E'],
            'kd_kec' => $vv['F'],
            'kecamatan' => $vv['G'],
            'tarif_per_kg_komp_eco_min30kg' => $vv['H'],
            'tarif_per_kg_komp_reg_min1kg' => $vv['I'],
            'tarif_per_kg_lainlain_eco_min30kg' => $vv['J'],
            'tarif_per_kg_perlindungandiri_noncair_reg_min1kg' => $vv['K']
            );
        }
        
        $queryProduct = $this->mod_product->productAdd('master_ongkos_kirim', $ongkirArray);

        if($queryProduct)
        {
            echo "Berhasil menyimpan data ongkos kirim <br>";
            
        }
        else
        {
            echo "Gagal menyimpan data ongkos kirim <br/>";
        }

        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
                return false;
        }
        else
        {
                $this->db->trans_commit();
                return true;
        }
    }
}
