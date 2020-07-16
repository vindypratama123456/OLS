<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_mitra $mod_mitra
 * @property Mymail $mymail
 */
class Emailblacklist extends MY_Controller
{
    private $table;
    private $_output;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->load->model('mod_product');
        $this->load->model('mod_mitra');
        $this->table = 'email_blacklist';
        $this->_output = [];
    }

    public function index()
    {
        $this->_output['content'] = $this->load->view('admin/emailblacklist/list', '', true);
        $this->_output['script_js'] = $this->load->view('admin/emailblacklist/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function listEmail()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/emailblacklist');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('id as id, email as email');


        $this->datatables->from($this->table);
        $this->datatables->add_column('aksi', '<a class="btn btn-danger btn-sm" id="btnDelete" data-value="$1" title="Klik untuk menghapus data">Hapus</a>', 'id');
        // $this->datatables->add_column('aksi', '<a class="btn btn-danger btn-sm" href="' . base_url(ADMIN_PATH . '/emailblacklist/delete/$1') . '" title="Klik untuk menghapus data">Hapus</a>', 'id');
        $this->output->set_output($this->datatables->generate());
    }

    // public function detail($id)
    // {
    //     if ($id && is_numeric($id)) {
    //         $data['detil'] = $this->mod_mitra->getDetail($id);
    //         if ($data['detil']) {
    //             if (in_array($this->adm_level, $this->backoffice_superadmin_area)) {
    //                 $data['korwil'] = $this->mod_mitra->getAll("employee", "*", "level=3 and active=1", "code asc, name asc");
    //             }
    //             $data['referensi'] = $this->mod_mitra->getAll("employee", "*", "(level='3' OR level='4') and active = 1", "code asc, level asc, name asc");
    //             $data['listBank'] = $this->mod_mitra->getAll("master_bank", "*", "status = 1", "id asc");
    //             $data['adm_level'] = $this->adm_level;
    //             $this->_output['content'] = $this->load->view('admin/mitra/edit', $data, true);
    //             $this->_output['script_css'] = $this->load->view('admin/mitra/css', '', true);
    //             $this->_output['script_js'] = $this->load->view('admin/mitra/js', '', true);
    //             $this->load->view('admin/template', $this->_output);
    //         } else {
    //             redirect(ADMIN_PATH . '/mitra', 'refresh');
    //         }
    //     } else {
    //         redirect(ADMIN_PATH . '/mitra', 'refresh');
    //     }
    // }

    // # TODO : add logs and block permission for auditor
    // public function updatePost()
    // {
    //     if ( ! $this->input->is_ajax_request()) {
    //         redirect(ADMIN_PATH . '/mitra');
    //     }
    //     try {
    //         if (in_array($this->adm_level, $this->auditor_area)) {
    //             $callBack   = [   
    //                 "success"   => "false",
    //                 "message"   => "Maaf, anda tidak dapat melakukan proses ini."
    //             ];
    //         } else {
    //             $id = $this->input->post('id_employee');
    //             $currentStatus = $this->input->post('current_status');
    //             $newStatus = $this->input->post('active');
    //             $isActivated = $this->input->post('is_activated');
    //             $emailSales = trim(strtolower($this->input->post('email', true)));
    //             $emailKorwil = trim(strtolower($this->input->post('email_korwil', true)));
    //             $percentTax = trim($this->input->post('percent_tax', true))/100;
    //             $npwp = trim($this->input->post('no_npwp', true));
    //             $codeReferral = $this->input->post('code_referral');
    //             $dataEmployee = [
    //                 'name' => trim($this->input->post('name', true)),
    //                 'telp' => trim($this->input->post('telp', true)),
    //                 'active' => $newStatus
    //             ];
    //             if ($npwp == trim($this->input->post('identity_code', true))) {
    //                 $validPercentTax = 0.030;
    //             } elseif ( ! empty($npwp) || $npwp != '') {
    //                 $validPercentTax = $percentTax;
    //             } else {
    //                 $validPercentTax = 0.030;
    //             }
    //             $dataMitra = [
    //                 'identity_code' => trim($this->input->post('identity_code', true)),
    //                 'gender' => $this->input->post('gender'),
    //                 'address' => trim($this->input->post('address', true)),
    //                 'name_npwp' => trim($this->input->post('name_npwp', true)),
    //                 'no_npwp' => $npwp,
    //                 'address_npwp' => trim($this->input->post('address_npwp', true)),
    //                 'code_referral' => $codeReferral,
    //                 'bank_account_number' => trim($this->input->post('bank_account_number', true)),
    //                 'bank_account_name' => trim($this->input->post('bank_account_name', true)),
    //                 'bank_account_type' => $this->input->post('bank_account_type'),
    //                 'percent_comission' => trim($this->input->post('percent_comission', true))/100,
    //                 'percent_tax' => $validPercentTax,
    //                 'date_modified' => date('Y-m-d H:i:s')
    //             ];
    //             if (0==$isActivated) {
    //                 $dataEmployee['email'] = $emailSales;
    //             }
    //             $this->db->trans_begin();
    //             $updateEmployee = $this->mod_general->updateData($this->table, $dataEmployee, 'id_employee', $id);
    //             if ($updateEmployee) {
    //                 $updateMitra = $this->mod_general->updateData('mitra_profile', $dataMitra, 'id_employee', $id);
    //                 if ($updateMitra) {
    //                     if ($currentStatus == 0 && $newStatus == 1 && $isActivated == 0) {
    //                         $this->mod_general->updateData('mitra_profile', ['is_activated' => 1], 'id_employee', $id);
    //                         $dataKorwilSales = [
    //                             'email_sales' => $emailSales,
    //                             'email_korwil' => $emailKorwil
    //                         ];
    //                         $korwilSalesInsert = $this->db->insert('korwil_sales', $dataKorwilSales);
    //                         if ($korwilSalesInsert) {
    //                             $this->mailNotification($emailSales);
    //                         }
    //                     }
    //                     $this->db->trans_commit();
    //                     $callBack = [
    //                         'success' => 'true',
    //                         'message' => 'Data successfully updated.'
    //                     ];
    //                     $this->session->set_flashdata('msg_success', 'Data mitra: <b>' . $dataEmployee['name'] . '</b> berhasil <b>DIPERBARUI</b></p>');
    //                 } else {
    //                     $this->db->trans_rollback();
    //                     $callBack = [
    //                         'success' => 'false',
    //                         'message' => 'Failed to update mitra.'
    //                     ];
    //                 }
    //             } else {
    //                 $this->db->trans_rollback();
    //                 $callBack = [
    //                     'success' => 'false',
    //                     'message' => 'Failed to update employee.'
    //                 ];
    //             }
    //         }
    //         echo json_encode($callBack, true);
    //     } catch (Exception $e) {
    //         $callBack = [
    //             'success' => 'false',
    //             'message' => 'Caught exception: ' . $e->getMessage()
    //         ];
    //         echo json_encode($callBack, true);
    //     }
    // }
    
    public function add()
    {
        $this->_output['content'] = $this->load->view('admin/emailblacklist/add', '', true);
        $this->_output['script_js'] = $this->load->view('admin/emailblacklist/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }
    
    public function addPost()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/category');
        }
        try {
            $email = $this->input->post('email');
            $data[] = array(
                'id' => null,
                'email' => $email
            );

            $proc = $this->mod_product->Add('email_blacklist', $data);
            if ($proc) {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Data successfully added.'
                ];
                $this->session->set_flashdata('msg_success', 'Data email blacklist berhasil <b>DITAMBAHKAN</b></p>');
            } else {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Failed to add data.'
                ];
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        // $this->_output['content'] = $this->load->view('admin/emailblacklist/add', '', true);
        // $this->_output['script_js'] = $this->load->view('admin/emailblacklist/js', '', true);
        // $this->load->view('admin/template', $this->_output);
    }
    

    public function delete()
    {
        $id = $this->input->post('id');
        $query = $this->mod_general->deleteData($this->table,'id',$id);
        if($query)
        {
            $callBack = array(
                'success' => 'true',
                'message' => 'Berhasil menghapus data'
            );
        }
        else
        {
            $callBack = array(
                'success' => 'false',
                'message' => 'Gagal menghapus data'
            );
        }
        echo json_encode($callBack, true);
    }
    
    public function import()
    {
        $this->_output['content'] = $this->load->view('admin/emailblacklist/import', '', true);
        $this->_output['script_js'] = $this->load->view('admin/emailblacklist/js', '', true);
        $this->load->view('admin/template', $this->_output);
    } 

    public function importPost()
    {
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

        $data_email=array();
        // print_r($arr_data);
        foreach($arr_data as $vv){
            // $data_email[] = array(
            //     'email'=> $vv['A'],
            // );
            if($this->getCheckExistClientSide($vv['A'],'email_blacklist','email')==true)
            {
                $data_email[] = array(
                    'email'=> $vv['A'],
                );
            }
        }
        print_r($data_email);

        $query = $this->mod_product->Add('email_blacklist', $data_email);

        if($query)
        {
            echo "<br> Berhasil";
        }
        else
        {
            echo "<br> Gagal";
        }

    }

    public function checkDupplicateValidation()
    {
        echo json_encode($this->getCheckExistClientSide($this->input->post('values'), $this->input->post('table'),
            $this->input->post('select')));
    }

    public function testing()
    {
        if($this->getCheckExistClientSide('email@testing.commp','email_blacklist','email')==false)
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    }

    public function getCheckExistClientSide($inputPost, $tableName, $select, $field = null, $value = null)
    {
        $inputPost = strtolower($inputPost);
        $checkExist = null;
        if ($field == null && $value == null) {
            $checkExist = $this->mod_mitra->checkExist($tableName, $select, $inputPost);
        } else {
            $checkExist = $this->mod_mitra->checkExist($tableName, $select, $inputPost, $field, $value);
        }
        if ($checkExist > 0) {
            $data = false;
        } else {
            $data = true;
        }

        return $data;
    }

    // public function importPost()
    // {
    //     $this->db->trans_begin();

    //     $id_periode = '7';
    //     $periode = '2019';

    //     $file = $_FILES['mikon_file']['tmp_name'];
 
    //     //load the excel library
    //     $this->load->library('excel');
         
    //     //read file from path
    //     $objPHPExcel = PHPExcel_IOFactory::load($file);

    //     $sheetActive = $objPHPExcel->getSheet(0);
    //     // $sheetActive = $objPHPExcel->getActiveSheet();
         
    //     //get only the Cell Collection
    //     // $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
    //     $cell_collection = $sheetActive->getCellCollection();
         
    //     //extract to a PHP readable array format
    //     foreach ($cell_collection as $cell) {
    //         $column = $sheetActive->getCell($cell)->getColumn();
    //         $row = $sheetActive->getCell($cell)->getRow();
    //         $data_value = $sheetActive->getCell($cell)->getValue();
         
    //         //The header will/should be in row 1 only. of course, this can be modified to suit your need.
    //         if ($row == 1) {
    //             $header[$row][$column] = $data_value;
    //         } else {
    //             $arr_data[$row][$column] = $data_value;
    //         }
    //     }

    //     //send the data in an array format
    //     $data['header'] = $header;
    //     $data['values'] = $arr_data;

    //     $productArray = [];
    //     $dataArray = [];

    //     $category_product = [];
    //     $category_product_1 = [];
    //     $category_product_2 = [];

    //     foreach($arr_data as $vv){
    //         $productArray[] = array(
    //         'id_product'=> 0,
    //         'kode_buku' => $vv['B'],
    //         'reference' => $vv['C'],
    //         'id_category_default' => $vv['D'],
    //         'name' => $vv['E'],
    //         'description' => $vv['F'],
    //         'supplier' => $vv['G'],
    //         'quantity' => 0,
    //         'price_1' => $vv['H'],
    //         'price_2' => $vv['I'],
    //         'price_3' => $vv['J'],
    //         'price_4' => $vv['K'],
    //         'price_5' => $vv['L'],
    //         'non_r1' => $vv['M'],
    //         'non_r2' => $vv['N'],
    //         'non_r3' => $vv['O'],
    //         'non_r4' => $vv['P'],
    //         'non_r5' => $vv['Q'],
    //         'width' => str_replace(',', '.', $vv['S']),
    //         'height' => str_replace(',', '.', $vv['T']),
    //         'weight' => str_replace(',', '.', $vv['V']),
    //         'pages' => $vv['R'],
    //         'capacity' => null,
    //         'active' => 1,
    //         'enable' => 1,
    //         'sort_order' => $vv['X'],
    //         'date_add' => 'now()',
    //         'date_upd' => 'now()',
    //         'images' => $vv['R'],
    //         'url_image' => ''
    //         );

    //         $dataArray[] = array(
    //             'kode_buku' => $vv['B'],
    //             'hpp' =>  $vv['W']
    //         );
    //     }
        
    //     $queryProduct = $this->mod_product->productAdd('product', $productArray);

    //     if($queryProduct)
    //     {
    //         echo "berhasil product <br>";
    //         foreach($arr_data as $dd)
    //         {
    //             $idproduct = $this->mod_product->getIdProduct('product','id_product',$dd['B'])[0]['id_product'];
    //             $category_product_1[] = array(
    //                 'id_product' => $idproduct,
    //                 'id_category' =>  $dd['D']
    //             );

    //             $category_product_2[] = array(
    //                 'id_product' => $idproduct,
    //                 'id_category' =>  $dd['Y']
    //             );
    //         }

    //         $category_product = array_merge($category_product_1, $category_product_2);
    //         // print_r($category_product);

    //         $queryCategoryProduct = $this->mod_product->Add('category_product', $category_product);

    //         if($queryCategoryProduct){
    //             echo "berhasil category_product <br>";

    //             $table = "master_gudang";
    //             $select = "id_gudang";
    //             $where = "1";
    //             $gudangAktif = $this->mod_product->getGudangAktif($table, $select, $where);

    //             $hppArray = [];
    //             $infoGudangArray = [];

    //             foreach($gudangAktif as $d)
    //             {
    //                 foreach ($dataArray as $da) {
    //                     $id_product = $this->mod_product->getIdProduct('product','id_product',$da['kode_buku'])[0]['id_product'];
    //                     $hppArray[] = array(
    //                         'id' => 0,
    //                         'id_gudang' => $d['id_gudang'],
    //                         'id_produk' => $id_product,
    //                         'id_periode' => $id_periode,
    //                         'hpp' =>  $da['hpp'],
    //                         'diskon' =>  0,
    //                         'created_date' =>  'now()',
    //                         'updated_date' =>  'now()'
    //                     );

    //                     $infoGudangArray[] = array(
    //                         'id' => 0,
    //                         'id_produk' => $id_product,
    //                         'id_gudang' => $d['id_gudang'],
    //                         'Stok_fisik' => 0,
    //                         'stok_booking' =>  0,
    //                         'stok_available' =>  0,
    //                         'periode' =>  $periode,
    //                         'date_created' =>  'now()',
    //                         'date_updated' =>  'now()'
    //                     );
    //                 }
    //             }
    //             $query = $this->mod_product->Add('master_hpp', $hppArray);

    //             if($query)
    //             {
    //                 echo "berhasil master_hpp <br>";

    //                 $qry = $this->mod_product->Add('info_gudang', $infoGudangArray);

    //                 if($qry)
    //                 {
    //                     echo "berhasil info_gudang <br>";

    //                 }
    //                 else
    //                 {
    //                     echo "gagal info_gudang";
    //                 }
    //             }
    //             else
    //             {
    //                 echo "gagal master_hpp";
    //             }
    //         }
    //         else
    //         {
    //             echo "gagal category_product";
    //         }
    //     }
    //     else
    //     {
    //         echo "gagal product";
    //     }

    //     if ($this->db->trans_status() === FALSE)
    //     {
    //             $this->db->trans_rollback();
    //     }
    //     else
    //     {
    //             $this->db->trans_commit();
    //     }
    // }
    
    public function importDelete()
    {
        // $this->load->view('admin/product/import');
        $this->_output['content'] = $this->load->view('admin/product/importdelete', '', true);
        $this->_output['script_js'] = ''; //$this->load->view('admin/product/js', '', true);
        $this->load->view('admin/template', $this->_output);
    } 

    public function importDeletePost()
    {
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

        $dataArray = [];

        foreach($arr_data as $vv){
            $id_product = $this->mod_product->getIdProduct('product','id_product',$vv['B'])[0]['id_product'];
            // $dataArray[] = array(
            //     'id_product' => $id_product
            // );
            $dataArray[] = $id_product;
            echo $id_product."-".$vv['B']."<br>";
        }

        print_r($dataArray);

        $query = $this->mod_product->Delete('product',$dataArray,'id_product');
        if($query)
        {
            echo "Berhasil";
            $query1 = $this->mod_product->Delete('category_product',$dataArray,'id_product');  
            if($query1)
            {
                echo "Berhasil";
                $query2 = $this->mod_product->Delete('master_hpp',$dataArray,'id_produk');  
                if($query2)
                {
                    echo "Berhasil";
                    $query3 = $this->mod_product->Delete('info_gudang',$dataArray,'id_produk');  
                    if($query3)
                    {
                        echo "Berhasil";
                    }
                    else
                    {
                        echo "gagal";
                    }
                }
                else
                {
                    echo "gagal";
                }
            }
            else
            {
                echo "gagal";
            }
        }
        else
        {
            echo "gagal";
        }
    }

    function uploadFilesForm()
    {
    	$this->load->view('admin/product/upload');
    }

    function uploadFiles()
    {
    	$count = 0;
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		    foreach ($_FILES['files']['name'] as $i => $name) {
		        if (strlen($_FILES['files']['name'][$i]) > 1) {
		            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], './coba/'.$name)) {
		                $count++;
		            }
		        }
		    }
		}


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

        $dataArray = [];

        foreach($arr_data as $vv){
        	$idproduct = $this->mod_product->getIdProduct('product','id_product',$vv['B'])[0]['id_product'];
            $dataArray[] = array(
                'id_product' => $idproduct,
                'images' =>  $vv['W']
            );
        }

        $this->imageDataUpload($dataArray);
    }

    public function imageDataUpload($data){
        // $files = glob("coba2/*.jpg");
        // print_r($files);
        $path = "coba2/";
        $newData = [];
        array_multisort(array_map($this->imageDataGet($data), ($files = glob($path."*.jpg*"))), SORT_DESC, $files);
        foreach($files as $namafile){
            $id_product = str_replace($path,"",str_replace(".jpg", "", $namafile));
            $url_image = str_replace($path,"",$namafile);
            $images = 1;

            $newData[]=array(
            	'id_product' => $id_product,
            	'url_image' => $url_image,
            	'images' => $images
            );

            echo "update product set images = 1, url_image='$id_product.jpg' where id_product = '$id_product';<br />";
        }

        echo "<br><br>";
        print_r($newData);
    }

    public function imageDataGet($datas)
    {
        //===================
    	// config php.ini
        //===================
    	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        set_time_limit(21600000000);
        ini_set('memory_limit', '-1');

        //===================
        // Set variable path
        //===================
        $path = "coba2/";        

        //===================
        // set variable url
        //===================
        // $url = "http://bukusekolah.gramedia.test/coba/";
        $url = "./coba/";

        //===================
        // example array
        //===================
        // $datas = Array(Array ( 'id_product' => 1001, 'images' => 'gambar1.jpg' ), Array ( 'id_product' => 1002, 'images' => 'gambar2.jpg'));
         
        echo "<br>";
        // print_r($datas);

        foreach($datas as $data){
            $images = $data['images'];
            $imagesUrl = $url.$images;
            $id = $data['id_product'];
            // $name = strtolower(str_replace(" ", "-", str_replace(":","",$data['name']))).".jpg";
            // $fileName = $id."-".$name;
            echo $imagesUrl."  ##  ";
            echo $id.".jpg"."<br>";

            //===========================
            // destination file location
            //===========================
            $destFileUrl = $path.$id.".jpg";

            try {
                file_put_contents( $destFileUrl, file_get_contents($imagesUrl));
            } catch (Exception $e) {
                echo $e;
            }
            
            sleep(1);
        }
        echo "<br/>";
    }

    // private function upload_files()
    // {
    // 	$path = './coba2/';
    //     $config = array(
    //         'upload_path'   => $path,
    //         'allowed_types' => 'jpg|gif|png',
    //         'overwrite'     => 1,                       
    //     );

    //     $this->load->library('upload', $config);

    //     $images = array();

    //     foreach ($files['name'] as $key => $image) {
    //         $_FILES['images[]']['name']= $files['name'][$key];
    //         $_FILES['images[]']['type']= $files['type'][$key];
    //         $_FILES['images[]']['tmp_name']= $files['tmp_name'][$key];
    //         $_FILES['images[]']['error']= $files['error'][$key];
    //         $_FILES['images[]']['size']= $files['size'][$key];

    //         $fileName = $title .'_'. $image;

    //         $images[] = $fileName;

    //         $config['file_name'] = $fileName;

    //         $this->upload->initialize($config);

    //         if ($this->upload->do_upload('images[]')) {
    //             $this->upload->data();
    //         } else {
    //             return false;
    //         }
    //     }

    //     return $images;
    // }

    // public function importProduct(){
    //     // $this->_output['content'] = $this->load->view('admin/product/import', '', true);
    //     // $this->_output['script_js'] = ""; //$this->load->view('admin/product/js', '', true);
    //     // $this->load->view('admin/template', $this->_output);

    //     $this->load->view('admin/product/importproduct');
    // }

    // public function importProductPost(){
    //     $file = $_FILES['mikon_file']['tmp_name'];
 
    //     //load the excel library
    //     $this->load->library('excel');
         
    //     //read file from path
    //     $objPHPExcel = PHPExcel_IOFactory::load($file);

    //     $sheetActive = $objPHPExcel->getSheet(0);
    //     // $sheetActive = $objPHPExcel->getActiveSheet();
         
    //     //get only the Cell Collection
    //     // $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
    //     $cell_collection = $sheetActive->getCellCollection();
         
    //     //extract to a PHP readable array format
    //     foreach ($cell_collection as $cell) {
    //         $column = $sheetActive->getCell($cell)->getColumn();
    //         $row = $sheetActive->getCell($cell)->getRow();
    //         $data_value = $sheetActive->getCell($cell)->getValue();
         
    //         //The header will/should be in row 1 only. of course, this can be modified to suit your need.
    //         if ($row == 1) {
    //             $header[$row][$column] = $data_value;
    //         } else {
    //             $arr_data[$row][$column] = $data_value;
    //         }
    //     }


         
    //     //send the data in an array format
    //     $data['header'] = $header;
    //     $data['values'] = $arr_data;


    //     // print_r($data['header']);
        
    //     // echo "<table border=1>";
    //     // foreach ($arr_data as $vv) {
    //     //     echo "<tr>";
    //     //     foreach($vv as $vvv){
    //     //         echo "<td>".$vvv."</td>";
    //     //     }
    //     //     echo "</tr>";
    //     // }
    //     // echo "</table>";

    //     $dataArray = [];

    //     foreach($arr_data as $vv){
    //         $dataArray[] = array(
    //         'id_product'=> 0,
    //         'kode_buku' => $vv['B'],
    //         'reference' => $vv['C'],
    //         'id_category_default' => $vv['D'],
    //         'name' => $vv['E'],
    //         'description' => $vv['F'],
    //         'supplier' => $vv['G'],
    //         'quantity' => 0,
    //         'price_1' => $vv['H'],
    //         'price_2' => $vv['I'],
    //         'price_3' => $vv['J'],
    //         'price_4' => $vv['K'],
    //         'price_5' => $vv['L'],
    //         'non_r1' => $vv['M'],
    //         'non_r2' => $vv['N'],
    //         'non_r3' => $vv['O'],
    //         'non_r4' => $vv['P'],
    //         'non_r5' => $vv['Q'],
    //         'width' => str_replace(',', '.', $vv['S']),
    //         'height' => str_replace(',', '.', $vv['T']),
    //         'weight' => str_replace(',', '.', $vv['V']),
    //         'pages' => $vv['R'],
    //         'capacity' => null,
    //         'active' => 1,
    //         'enable' => 1,
    //         'sort_order' => $vv['X'],
    //         'date_add' => 'now()',
    //         'date_upd' => 'now()',
    //         'images' => $vv['R'],
    //         'url_image' => ''
    //         );
    //     }
    //     // print_r($dataArray);
    //     $rslt = $this->mod_product->productAdd('product', $dataArray);
    // }
}
