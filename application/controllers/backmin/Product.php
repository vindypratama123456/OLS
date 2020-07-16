<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_product $mod_product
 * @property Mod_mitra $mod_mitra
 * @property Mymail $mymail
 */
class Product extends MY_Controller
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
        $this->_output['content'] = $this->load->view('backmin/product/list', '', true);
        $this->_output['script_js'] = $this->load->view('backmin/product/js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $this->_output);
    }

    public function listProduct()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(BACKMIN_PATH . '/product');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('
            a.`id_product` as id_product
            ,a.`kode_buku` as kode_buku
            ,a.`reference` as reference
            ,b.`name` as category
            ,a.`name` as name
            ,a.`description` as description
            ,a.`supplier` as supplier
            ,a.`quantity` as quantity
            ,a.`price_1` as price_1
            ,a.`price_2` as price_2
            ,a.`price_3` as price_3
            ,a.`price_4` as price_4
            ,a.`price_5` as price_5
            ,a.`non_r1` as non_r1
            ,a.`non_r2` as non_r2
            ,a.`non_r3` as non_r3
            ,a.`non_r4` as non_r4
            ,a.`non_r5` as non_r5
            ,a.`width` as width
            ,a.`height` as height
            ,a.`weight` as weight
            ,a.`pages` as pages
            ,a.`capacity` as capacity
            ,a.`url_image` as image
            ,if(a.`active`=0, CONCAT("<span class=\"label label-danger\">Tidak</span>"), CONCAT("<span class=\"label label-success\">Ya</span>")) AS active
            ,if(a.`enable`=0, CONCAT("<span class=\"label label-danger\">Tidak</span>"), CONCAT("<span class=\"label label-success\">Ya</span>")) AS enable
            ,a.`date_add` as date_add
            ,a.`date_upd` as date_upd
        ');

        $this->datatables->from($this->table.' a');
        $this->datatables->join('category b', 'b.`id_category`=a.`id_category_default`', 'inner');
        $this->datatables->edit_column('kode_buku', '<a href="' . base_url(BACKMIN_PATH . '/product/detail/$2') . '">$1</a>', 'kode_buku, id_product');
        // $this->datatables->edit_column('image', '<a href="' . base_url('assets/img/product/$1.jpg') . '">$1</a>'.'<img src="' . base_url('assets/img/product/$1.jpg'), 'id_product');
        $this->datatables->add_column('image','<img id="imgView" src="'.base_url('assets/img/product/').'$1.jpg" width="25px">', 'id_product');
        $this->output->set_output($this->datatables->generate());
    }

    public function detail($id_product)
    {
        // $data['kategori'] = $this->mod_product->getAll("category",'id_category, name',"active=1 and id_parent <> 0");
        $data['kategori'] = $this->mod_product->get_category_product();
        $data['detil'] = $this->mod_product->getList("product",'*',"id_product='$id_product'");
        $this->_output['content'] = $this->load->view('backmin/product/edit', $data, TRUE);
        $this->_output['script_js'] = $this->load->view('backmin/product/js', '', true);
        $this->load->view(BACKMIN_PATH . '/main', $this->_output);
    }

    public function detail_post()
    {
        $id_product = $this->input->post('id_product');
        // $kode_buku = $this->input->post('kode_buku');
        // $reference = $this->input->post('reference');
        // $id_category_default = $this->input->post('id_category_default');
        $name = $this->input->post('name');
        // $description = $this->input->post('description');
        // $supplier = $this->input->post('supplier');
        // $quantity = $this->input->post('quantity');
        // $price_1 = $this->input->post('price_1');
        // $price_1 = $this->input->post('price_1');
        // $price_2 = $this->input->post('price_2');
        // $price_3 = $this->input->post('price_3');
        // $price_4 = $this->input->post('price_4');
        // $price_5 = $this->input->post('price_5');
        // $non_r1 = $this->input->post('non_r1');
        // $non_r2 = $this->input->post('non_r2');
        // $non_r3 = $this->input->post('non_r3');
        // $non_r4 = $this->input->post('non_r4');
        // $non_r5 = $this->input->post('non_r5');
        $width = $this->input->post('width');
        $height = $this->input->post('height');
        $weight = $this->input->post('weight');
        $pages = $this->input->post('pages');
        $capacity = $this->input->post('capacity');
        $active = $this->input->post('active');
        $enable = $this->input->post('enable');

        $data = array(
            // 'kode_buku' => $kode_buku
            // ,'reference' => $reference
            // ,'id_category_default' => $id_category_default
            // ,'name' => $name
            // ,'description' => $description
            // ,'supplier' => $supplier
            // ,'quantity' => $quantity
            // ,'price_1' => $price_1
            // ,'price_2' => $price_2
            // ,'price_3' => $price_3
            // ,'price_4' => $price_4
            // ,'price_5' => $price_5
            // ,'non_r1' => $non_r1
            // ,'non_r2' => $non_r2
            // ,'non_r3' => $non_r3
            // ,'non_r4' => $non_r4
            // ,'non_r5' => $non_r5
            'width' => $width
            ,'height' => $height
            ,'weight' => $weight
            ,'pages' => $pages
            ,'capacity' => $capacity
            ,'active' => $active
            ,'enable' => $enable
        );

        $config['upload_path'] = 'assets/img/product/';
        $config['allowed_types'] = 'jpg';
        $config['file_name'] = $id_product;
        $config['overwrite'] = TRUE;
        $config['max-size'] = '2048';

        $this->load->library('upload', $config);
        // $this->upload->overwrite = TRUE;
        if($this->upload->do_upload('gambar'))
        {
            // echo 'berhasil unggah gambar';
            $data1 = array(
                'images' => 1
            );
            $data = array_merge($data,$data1);
        }
        else
        {
            // print_r($this->upload->display_errors());
        }

        // print_r($data);
        try {
            if (in_array($this->adm_level, $this->backoffice_admin_area) && in_array($this->adm_level, $this->auditor_area)) {
                $callBack   = [   
                    "success"   => "false",
                    "message"   => "Maaf, anda tidak dapat melakukan proses ini."
                ];
            } else {
                $this->db->trans_begin();
                $updateProduct = $this->mod_general->updateData($this->table, $data, 'id_product', $id_product);
                if ($updateProduct) {
                    $this->db->trans_commit();
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.'
                    ];
                    $this->session->set_flashdata('msg_success', 'Data product: <b>' . $name . '</b> berhasil <b>DIPERBARUI</b></p>');
                } else {
                    $this->db->trans_rollback();
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Failed to update employee.'
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
    
    public function import()
    {
        // $this->load->view('admin/product/import');
        $this->_output['content'] = $this->load->view('backmin/product/import', '', true);
        $this->_output['script_js'] = ''; //$this->load->view('admin/product/js', '', true);
        $this->load->view('admin/template', $this->_output);
    } 

    public function importPost()
    {
        $this->db->trans_begin();

        $id_periode = '7';
        $periode = '2019';

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
            $productArray[] = array(
            'id_product'=> 0,
            'kode_buku' => $vv['B'],
            'reference' => $vv['C'],
            'id_category_default' => $vv['D'],
            'name' => $vv['E'],
            'description' => $vv['F'],
            'supplier' => $vv['G'],
            'quantity' => 0,
            'price_1' => $vv['H'],
            'price_2' => $vv['I'],
            'price_3' => $vv['J'],
            'price_4' => $vv['K'],
            'price_5' => $vv['L'],
            'non_r1' => $vv['M'],
            'non_r2' => $vv['N'],
            'non_r3' => $vv['O'],
            'non_r4' => $vv['P'],
            'non_r5' => $vv['Q'],
            'width' => str_replace(',', '.', $vv['S']),
            'height' => str_replace(',', '.', $vv['T']),
            'weight' => str_replace(',', '.', $vv['V']),
            'pages' => $vv['R'],
            'capacity' => null,
            'active' => 1,
            'enable' => 1,
            'sort_order' => $vv['X'],
            'date_add' => 'now()',
            'date_upd' => 'now()',
            'images' => $vv['R'],
            'url_image' => ''
            );

            $dataArray[] = array(
                'kode_buku' => $vv['B'],
                'hpp' =>  $vv['W']
            );
        }
        
        $queryProduct = $this->mod_product->productAdd('product', $productArray);

        if($queryProduct)
        {
            echo "Berhasil menyimpan data produk <br>";
            foreach($arr_data as $dd)
            {
                $idproduct = $this->mod_product->getIdProduct('product','id_product',$dd['B'])[0]['id_product'];
                $category_product_1[] = array(
                    'id_product' => $idproduct,
                    'id_category' =>  $dd['D']
                );

                $category_product_2[] = array(
                    'id_product' => $idproduct,
                    'id_category' =>  $dd['Y']
                );
            }

            $category_product = array_merge($category_product_1, $category_product_2);
            // print_r($category_product);

            $queryCategoryProduct = $this->mod_product->Add('category_product', $category_product);

            if($queryCategoryProduct){
                echo "Berhasil menyimpan data kategori produk <br>";

                $table = "master_gudang";
                $select = "id_gudang";
                $where = "1";
                $gudangAktif = $this->mod_product->getGudangAktif($table, $select, $where);

                $hppArray = [];
                $infoGudangArray = [];

                foreach($gudangAktif as $d)
                {
                    foreach ($dataArray as $da) {
                        $id_product = $this->mod_product->getIdProduct('product','id_product',$da['kode_buku'])[0]['id_product'];
                        $hppArray[] = array(
                            'id' => 0,
                            'id_gudang' => $d['id_gudang'],
                            'id_produk' => $id_product,
                            'id_periode' => $id_periode,
                            'hpp' =>  $da['hpp'],
                            'diskon' =>  0,
                            'created_date' =>  'now()',
                            'updated_date' =>  'now()'
                        );

                        $infoGudangArray[] = array(
                            'id' => 0,
                            'id_produk' => $id_product,
                            'id_gudang' => $d['id_gudang'],
                            'Stok_fisik' => 0,
                            'stok_booking' =>  0,
                            'stok_available' =>  0,
                            'periode' =>  $periode,
                            'date_created' =>  'now()',
                            'date_updated' =>  'now()'
                        );
                    }
                }
                $query = $this->mod_product->Add('master_hpp', $hppArray);

                if($query)
                {
                    echo "Berhasil menyimpan data HPP <br>";

                    $qry = $this->mod_product->Add('info_gudang', $infoGudangArray);

                    if($qry)
                    {
                        echo "Berhasil menyimpan data info gudang <br>";

                    }
                    else
                    {
                        echo "Gagal menyimpan data info gudang <br/>";
                    }
                }
                else
                {
                    echo "Gagal menyimpan data HPP <br/>";
                }
            }
            else
            {
                echo "Gagal menyimpan data kategori produk <br/>";
            }
        }
        else
        {
            echo "Gagal menyimpan data produk <br/>";
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
            // echo $id_product."-".$vv['B']."<br>";
        }

        // print_r($dataArray);

        $query = $this->mod_product->Delete('product',$dataArray,'id_product');
        if($query)
        {
            echo "Berhasil menghapus produk <br/>";
            $query1 = $this->mod_product->Delete('category_product',$dataArray,'id_product');  
            if($query1)
            {
                echo "Berhasil menghapus kategori produk <br/>";
                $query2 = $this->mod_product->Delete('master_hpp',$dataArray,'id_produk');  
                if($query2)
                {
                    echo "Berhasil menghapus hpp produk <br/>";
                    $query3 = $this->mod_product->Delete('info_gudang',$dataArray,'id_produk');  
                    if($query3)
                    {
                        echo "Berhasil menghapus info gudang <br/>";
                    }
                    else
                    {
                        echo "Gagal menghapus info_gudang <br/>";
                    }
                }
                else
                {
                    echo "Gagal menghapus hpp produk <br/>";
                }
            }
            else
            {
                echo "Gagal menghapus kategori produk <br/>";
            }
        }
        else
        {
            echo "Gagal menghapus produk <br/>";
        }

        echo "<br/> <a href='".base_url('backoffice/product')."'>Kembali ke daftar produk</a>";
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

        // echo "<br><br>";
        // print_r($newData);
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

    function importProduct()
    {
        // $this->load->view('admin/product/upload2');
        $this->_output['content'] = $this->load->view('admin/product/upload2', '', true);
        $this->_output['script_js'] = ''; //$this->load->view('admin/product/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    function importProductPost()
    {
        $product = $this->importPost();
        if($product == true)
        {
            $this->uploadFiles2();
        }
    }

    function uploadFiles2()
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
                'images' =>  $vv['U']
            );
        }

        $this->imageDataUpload2($dataArray);
    }

    public function imageDataUpload2($data){
        // $files = glob("coba2/*.jpg");
        // print_r($files);
        $path = "coba2/";
        $newData = [];
        array_multisort(array_map($this->imageDataGet2($data), ($files = glob($path."*.jpg*"))), SORT_DESC, $files);
        foreach($files as $namafile){
            $id_product = str_replace($path,"",str_replace(".jpg", "", $namafile));
            $url_image = str_replace($path,"",$namafile);
            $images = 1;

            $newData[]=array(
                'id_product' => $id_product,
                'url_image' => $url_image,
                'images' => $images
            );

            // echo "update product set images = 1, url_image='$id_product.jpg' where id_product = '$id_product';<br />";
        }

        // echo "<br><br>";
        // print_r($newData);
    }

    public function imageDataGet2($datas)
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
         
        // echo "<br>";
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
        // echo "<br/>";

        $this->recursiveRemoveDirectory("coba/");
    }

    function recursiveRemoveDirectory($directory)
    {
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) { 
                recursiveRemoveDirectory($file);
            } else {
                // unlink($file); 

                $type = explode(".", $file);
                $count = count($type) - 1;
                // echo $type[$count];
                if($type[$count] == 'jpg')
                {
                    unlink($file);   
                }
            }
        }
        echo "<br/> Berhasil upload image produk";
        echo "<br/> <a href='".base_url('backoffice/product')."'>Kembali ke daftar produk</a>";
        // rmdir($directory);
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
