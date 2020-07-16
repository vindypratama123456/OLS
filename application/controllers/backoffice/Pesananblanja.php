<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Authcustomer $authcustomer
 * @property Mod_general $Mod_general
 * @property Mod_pesanan $m_pesanan
 * @property Mod_pesananblanja $m_pesananblanja
 */
class Pesananblanja extends CI_Controller
{
    // private $userJenjang;
    private $tblHeader;
    private $tblHeaderDetail;
    private $tblTemp;

    public function __construct()
    {
        parent::__construct();
        // $this->authcustomer->restrict();
        $this->load->model('Mod_pesanan', 'm_pesanan');
        $this->load->model('Mod_pesananblanja', 'mod_pesananblanja');
        $this->load->model('Mod_general', 'mod_general');
         
        $this->tblHeader = "orders_siplah";
        $this->tblHeaderDetail = "orders_siplah_detail";
        $this->tblTemp = "orders_siplah_temp";
    }

    public function getDataSiplah()
    {
        $this->_output['content'] = $this->load->view('admin/pesananblanja/get_data_siplah', '', true);
        $this->_output['script_css'] = $this->load->view('admin/report/css', '', true);
        $this->_output['script_js'] = $this->load->view('admin/report/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function prosesPesananSiplah()
    {
        $this->db->trans_begin();


        $allKodeBukuErrorArray = array();
        $allKodeBukuPOErrorArray = array();


        // $checkDataKodeBukuPO = array();
        // $checkIsProductError = array();
        // $checkDataKodeBuku = array();
        
        // DECLARATION VARIABLE ARRAYS
        $checkDataKorwil = array();
        $checkDataRSM = array();
        $checkDataCustomer = array();
        $dataSiplah = array();
        $dataSiplahArray = array();
        $dataSiplahArrayNew = array();
        $poNumberOlsArray = array();

        /* tidak dipakai. Fa, 20200508
		$codeArray = array(
            'EKC'=> 7
            ,'GA' => 6 
            ,'GM' => 6
            ,'KA' => 6
            ,'KM' => 6 
            ,'MEN' => 12
            ,'PGC' => 7
            ,'SA' => 6 
            ,'SM' => 6
        );
		*/
		
        // $start_date = '2019-05-01';
        // $end_date = '2019-09-19';

        // DEFAULT DATE VALUE 
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+1 day"));

        // GET DATE VALUE FROM INPUT TEXT
        if($this->input->post('start_date'))
        {
            $start_date = $this->input->post('start_date');
        }
        if($this->input->post('end_date'))
        {
            $end_date = $this->input->post('end_date'); 
        }

        $params = "?seller_id=89&api_key=f21a297cadf045d8a36e950ac7585e81&start_date=".$start_date."&end_date=".$end_date;

        /**
         * GET DATA FROM API SIPLAH
         */
        // ========================================================================================================================================
        // INITIALIZE CURL
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => getenv('SIPLAH_API_GETLIST').$params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 180000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        // ========================================================================================================================================

        // PASSING DATA FROM SIPLAH
        $dataSiplah = json_decode($response);
        

        // GET TYPE OF SIPLAH DATA
        $varType = gettype($dataSiplah);

        // IF CONNECTED TO API SIPLAH
        if($varType != 'NULL' )
        {
            // FORMAT OBJECT DATA SIPLAH TO ARRAY
            foreach($dataSiplah as $arr)
            {
                $dataSiplahArray[] = (array) $arr;
            }

        /**
         * GET DATA FROM DATABASE WHERE START_DATE AND END_DATE
         */
        // ========================================================================================================================================
            // // GET DATA FROM orders_siplah
            // $dataOlsArray = $this->mod_pesananblanja->getAll($this->tblHeader, '*', 'created_date between "' . $start_date . '" AND "' . $end_date .'"')->result_array();

            // GET DATA FROM orders
            // $dataOlsArray = $this->mod_pesananblanja->getAll('orders', '*', 'date_add between "' . $start_date . '" AND "' . $end_date .'"')->result_array();

            $dataOlsArray = $this->mod_pesananblanja->getAll('orders', '*', 'date_add between "' . date('Y-m-d', strtotime('-2 days', strtotime($start_date))) . '" AND "' . $end_date .'"')->result_array();
        // ========================================================================================================================================
        
             
        /**
         * FUNCTION FOR UPDATE DATA ORDERS OLS
         */
        // ========================================================================================================================================
            // GET DATE NOW
            $dateNow = array('transfered_date' => date("Y-m-d h:i:s"));

            $updateOlsData = array();
            // GENERATE DATA SIPLAH WHERE NOT FOUND IN TABLE orders TO NEW ARRAY
            if( count($dataOlsArray) > 0 )
            {
                // PUSH PO NUMBER TO ARRAY
                foreach ( $dataOlsArray as $d1 ) {
                    // $poNumberOlsArray[] = substr($d1['po_number'],0,9);
                    $poNumberOlsArray[] = substr($d1['reference'],0,9);
                }

                $poNumberOlsArray = array_unique($poNumberOlsArray);
                
                foreach ( $dataSiplahArray as $d2 ) {
                    // CHECK PO NUMBER FROM DATA SIPLAH 
                    // IF NOT IN ARRAY, PUSH DATA TO ARRAY
                    if(!in_array($d2['po_number'], $poNumberOlsArray))
                    {
                        // GENERATE NEW ARRAY DATA FROM SIPLAH, WHERE NOT FOUND IN DATABASE OLS
                        if($d2['status_id_siplah'] != 'canceled')
                        {
                            $dataSiplahArrayNew[] = array_merge($d2,$dateNow);
                        }
                    }
                    else
                    {
                        $statusSiplah = $d2['status_id_siplah'] == 'canceled' ? 2 : 1;
                        if($statusSiplah == 2)
                        {
                            $updateOlsData[] = $d2['po_number'];
                        }
                    }
                }
            }
            else
            {
                foreach ( $dataSiplahArray as $d2 ) {
                    // CHECK PO NUMBER FROM DATA SIPLAH 
                    // IF NOT IN ARRAY, INSERT DATA INTO ARRAY
                    if($d2['status_id_siplah'] != 'canceled')
                    {
                        $dataSiplahArrayNew[] = array_merge($d2,$dateNow);
                    }
                }
            }

            $updateOlsData = array_unique($updateOlsData);

            // UPDATE CANCELED DATA FROM SIPLAH TO OLS
            $this->updateDataOrder($updateOlsData);
        // ========================================================================================================================================
        

            // GET DATA SIPLAH WHERE NOT FOUND IN TABLE orders_siplah_temp to new array
            $dsnPONumber = array();
            $dsnCheck = array();
            foreach($dataSiplahArrayNew as $dsn)
            {
                $dsnCheck[]=$dsn['po_number'];
            }

            if(count($dsnCheck) > 0)
            {
                $getDsnPO = $this->mod_pesananblanja->getWhereIn($this->tblTemp, "po_number", "po_number", $dsnCheck);
                $dsnPO = $getDsnPO->result_array();

                $dataSiplahTempArray = array();

                foreach($dsnPO as $key => $value){
                    $dsnPONumber[] = $value['po_number'];
                }

                foreach ($dataSiplahArrayNew as $ds) {
                    if(!in_array($ds['po_number'], $dsnPONumber))
                    {
                        if($ds['status_id_siplah'] != 'canceled')
                        {
                            $dataSiplahTempArray[] = $ds;   
                        }
                    }
                }

        /**
         * FUNCTION FOR SAVE DATA TO TABLE orders_siplah_temp, WITH ERROR DATA
         */
        // ========================================================================================================================================
                if( count($dataSiplahArrayNew) > 0 )
                {
                    if(count($dataSiplahTempArray) > 0)
                    {
                       $this->mod_general->addBatch($this->tblTemp, $dataSiplahTempArray);
                    }
                }
        // ========================================================================================================================================
        
                
                $arrayOrders = array();
                $arrayOrdersDetail = array();

                $checkPONumber = "";
                
                foreach ($dataSiplahArrayNew as $ds) {
                    $arrayOrdersDetail[$ds['po_number']][] = $ds;
                }
                
                $header = array();
                $headerDetail = $dataSiplah;
                $headerChecking = "";

        /**
         * FUNCTION FOR GET ALL KODE BARANG WHERE NOT FOUND IN OLS
         */
        // ========================================================================================================================================
                foreach($arrayOrdersDetail as $orders1 => $orders)
                {
                    foreach ($orders as $data) 
                    {
                        // GET DATA SKU
                        // $checkSku = preg_replace('/\d+/u', '', substr($data['sku'],0,5));
                        // if($checkSku == "")
                        // {
                        //     $dataSku = $data['sku'];
                        // }
                        // else
                        // {
                        //     $lengthSku = $codeArray[strtoupper($checkSku)];
                        //     $dataSku = strtoupper(substr($data['sku'],0,$lengthSku));
                        // }

                        $checkSku = $data['sku'][-1];
                        if($checkSku == 'X' || $checkSku == 'Y' || $checkSku == 'Z' || $checkSku == 'x' || $checkSku == 'y' || $checkSku == 'z')
                        {
                            $lengthSku = strlen($data['sku']) - 1;
                            $dataSku = strtoupper(substr($data['sku'],0,$lengthSku));
                        }    
                        else
                        {
                            $dataSku = $data['sku'];
                        }               

                        // GET DATA CATEGORY AND TYPE
                        $dataProduct = $this->mod_pesananblanja->getAll('product','*','kode_buku="'.$dataSku.'"')->row_array();
                            
                        $product_id = $dataProduct['id_product'];
                        if($product_id == null)
                        {
                            $allKodeBukuErrorArray[] = $dataSku;
                            $allKodeBukuPOErrorArray[] = $data['po_number'];
                        }
                    }
                }
                $allKodeBukuErrorArray = array_unique($allKodeBukuErrorArray);
                $allKodeBukuPOErrorArray = array_unique($allKodeBukuPOErrorArray);
        // ========================================================================================================================================

                $po_number_check_count = array();
                $count_value_array = array();
                foreach($arrayOrdersDetail as $orders1 => $orders)
                {
                    $count = 0;
                    $totalPay = 0;
                    $pesanan = array();
                    foreach ($orders as $data) 
                    {

                        $checkSku = $data['sku'][-1];
                        if($checkSku == 'X' || $checkSku == 'Y' || $checkSku == 'Z' || $checkSku == 'x' || $checkSku == 'y' || $checkSku == 'z')
                        {
                            $lengthSku = strlen($data['sku']) - 1;
                            $dataSku = strtoupper(substr($data['sku'],0,$lengthSku));
                        }    
                        else
                        {
                            $dataSku = $data['sku'];
                        } 
                        // echo "testing : ".$dataSku."<br><br>";

                        // GET DATA CATEGORY AND TYPE
                        $dataProduct = $this->mod_pesananblanja->getAll('product','*','kode_buku="'.$dataSku.'"')->row_array();
                        $product_id = $dataProduct['id_product'];

                        $id_category = $dataProduct['id_category_default'];
                        $dataCategory = $this->mod_pesananblanja->getAll('category','*','id_category="'.$id_category.'"')->row_array();
                        $categoryId = $dataCategory['id_category'];
                        $id_parent = $dataCategory['id_parent'];

                        $dataType = $this->mod_pesananblanja->getAll('category','*','id_category="'.$id_parent.'"')->row_array();
                        $typeId = $dataType['id_category'];

                        $pesanan[$typeId][$categoryId]['po_number'] = $data['po_number'];
                    } 


                    foreach ($pesanan as $category_ => $data_) {
                        // print_r($data_);
                        // echo "<br>". $category_;
                        // echo "<br><br>";
                        foreach ($data_ as $class_ => $value_) {
                            $po_number_check_count[] = $value_['po_number'];
                        }
                    }
                }
                $count_value_array = array_count_values($po_number_check_count);

                $check_po_proses = array();
                foreach($arrayOrdersDetail as $orders1 => $orders)
                {
                    $count = 0;
                    $totalPay = 0;
                    $pesanan = array();
                    foreach ($orders as $data) 
                    {
                        $checkSku = $data['sku'][-1];
                        if($checkSku == 'X' || $checkSku == 'Y' || $checkSku == 'Z' || $checkSku == 'x' || $checkSku == 'y' || $checkSku == 'z')
                        {
                            $lengthSku = strlen($data['sku']) - 1;
                            $dataSku = strtoupper(substr($data['sku'],0,$lengthSku));
                        }    
                        else
                        {
                            $dataSku = $data['sku'];
                        } 
                        // echo "testing : ".$dataSku."<br><br>";

                        // GET DATA CATEGORY AND TYPE
                        $dataProduct = $this->mod_pesananblanja->getAll('product','*','kode_buku="'.$dataSku.'"')->row_array();
                        $product_id = $dataProduct['id_product'];
                        // if($product_id == null)
                        // {
                        //     $checkDataKodeBuku[] = $dataSku;
                        //     $checkDataKodeBukuPO[] = $data['po_number'];
                        // }

                        $id_category = $dataProduct['id_category_default'];
                        $dataCategory = $this->mod_pesananblanja->getAll('category','*','id_category="'.$id_category.'"')->row_array();
                        $categoryId = $dataCategory['id_category'];
                        $category = $dataCategory['name'];
                        $id_parent = $dataCategory['id_parent'];

                        $dataType = $this->mod_pesananblanja->getAll('category','*','id_category="'.$id_parent.'"')->row_array();
                        $typeAlias = $dataType['alias'];
                        $typeName = $dataType['name'];
                        $typeId = $dataType['id_category'];
                        $type = $typeAlias ?? $typeName;

                        // GET DATA KORWIL
                        $kabupaten = $data['kab'];
                        $dataKorwil = $this->mod_pesananblanja->getDataKorwil($kabupaten)->row_array();
                        if($dataKorwil == null)
                        {
                            if($data['kab'] != null){
                                $checkDataKorwil[] = $kabupaten;
                            }
                        }

                        $korwilEmail = $dataKorwil['email'];
                        $korwilName = $dataKorwil['name'];
                        $korwilPhone = $dataKorwil['telp'];

                        // GET DATA RSM
                        $dataRSM = $this->mod_pesananblanja->getDataRSM($kabupaten)->row_array();
                        if($dataRSM == null)
                        {
                            if($data['kab'] != null){
                                $checkDataRSM[] = $kabupaten;  
                            }
                            
                        }
                        $rsm_name = $dataRSM['name'];
                            
                        // GENERATE DATA PESANAN WITH TYPE AND CATEGORY
                        $pesanan[$typeId][$categoryId]['pesanan'][$count] = [
                            'type' => $type,
                            'isbn' => $dataProduct['reference'],
                            'category' => $category,

                            'kode_buku' => $dataSku,
                            'product_id' => $product_id,
                            'product_name' => $dataProduct['name'], // $data['nama_produk'],
                            'product_quantity' => $data['qty'],
                            'unit_price' => $data['harga_satuan_dengan_ongkir'],
                            'total_price' => $data['harga_satuan_dengan_ongkir'] * $data['qty']
                        ];
                        $count++;
                        $totalPay += $data['harga_satuan_dengan_ongkir'] * $data['qty'];
                        if (array_key_exists('total', $pesanan[$typeId][$categoryId])) {
                            $pesanan[$typeId][$categoryId]['total'] += $data['harga_satuan_dengan_ongkir'] * $data['qty'];
                        } else {
                            $pesanan[$typeId][$categoryId]['total'] = $data['harga_satuan_dengan_ongkir'] * $data['qty'];
                        }
                        
                        $pesanan[$typeId][$categoryId]['id_product'][] = $dataProduct['id_product'];
                        $pesanan[$typeId][$categoryId]['sku'][] = $dataSku;
                        $pesanan[$typeId][$categoryId]['po_number'] = $data['po_number'];
                        $pesanan[$typeId][$categoryId]['created_date'] = $data['created_at'];
                        // $pesanan[$typeId][$categoryId]['created_date'] = $data['updated_at'];
                        $pesanan[$typeId][$categoryId]['korwil_email'] = $korwilEmail;
                        $pesanan[$typeId][$categoryId]['korwil_name'] = $korwilName;
                        $pesanan[$typeId][$categoryId]['korwil_phone'] = $korwilPhone;
                        $pesanan[$typeId][$categoryId]['rsm_name'] = $rsm_name;

                        $pesanan[$typeId][$categoryId]['npsn'] = $data['npsn'];
                        $pesanan[$typeId][$categoryId]['nama_sekolah'] = $data['nama_sekolah'];
                        $pesanan[$typeId][$categoryId]['alamat_lengkap_sekolah'] = $data['alamat_lengkap_sekolah'];
                        $pesanan[$typeId][$categoryId]['prov'] = $data['prov'];
                        $pesanan[$typeId][$categoryId]['kab'] = $data['kab'];
                        $pesanan[$typeId][$categoryId]['kec'] = $data['kec'];
                        $pesanan[$typeId][$categoryId]['desa'] = $data['desa'];
                        $pesanan[$typeId][$categoryId]['no_telepon'] = $data['no_telepon'];
                        $pesanan[$typeId][$categoryId]['bendahara_bos'] = $data['bendahara_bos'];
                        $pesanan[$typeId][$categoryId]['kepala_sekolah'] = $data['kepala_sekolah'];
                    } 

                    $checkErrorNumberPO = array();
                    $orderTemp = array();
                    $po_number_temp = array();

                    foreach ($pesanan as $category => $data1) {
                        $no = 0;
                        $po = "";

                        // SET VARIABLE FOR CHECKING PRODUCT
                        $isPoError = false;
                        $isCustomerError = array();

                        $korwilDataTemp = "";
                        $rsmDataTemp = "";
                        $customerDataTemp = "";
                        $noPoTemp = "";
                        $createdDateTemp = "";
                        foreach ($data1 as $class => $value) {
                            $check_po_proses[] = $value['po_number'];
                            $count_check_po_proses = array();
                            $count_check_po_proses = array_count_values($check_po_proses);

                            // ASSIGN PO NUMBER TO VARIABLE
                            $po_number = $value['po_number'];

                            // CHECKING PO NUMBER WITH PRODUCT NOT FOUND IN OLS
                            if(in_array($po_number,$allKodeBukuPOErrorArray))
                            {
                                $isPoError = true;
                            }

                            // GENERATE PO NUMBER
                            if($count_value_array[$po_number] > 1) 
                            {
                                $no = $count_check_po_proses[$po_number];
                                $nol = strlen($no) > 1 ? '' : '0';
                                $po = $po_number.'_'.$nol.$no;
                            }
                            else
                            {
                                $po = $po_number;
                            }


                            $po_number_temp[] = $po;

                            $order = $value['pesanan'];
                            $orderTemp = array_merge($orderTemp, $value['pesanan']);
                            $orderDetail = [];
                            $orderDetail['po_number'] = $po;
                            $orderDetail['reference_other'] = $po_number;

                            $customerDataTemp = "";
                            if($value['npsn'] == "" || $value['npsn'] == null)
                            {
                                $customerDataTemp = " Tidak ada data sekolah pada pesanan siplah.";
                            }

                            $isCustomerError = $value['npsn'] == "" || $value['npsn'] == null ? true : false;
                            $checkDataCustomer[] = $isCustomerError==true ? true : false;

                            if($isCustomerError == false)
                            {
                                // GET DATA CUSTOMER
                                $dataCustomer = $this->mod_pesananblanja->getAll('customer','*','no_npsn="'.$value['npsn'].'"')->row_array();
                                $id_customer = $dataCustomer['id_customer'];

                                if($id_customer == null )
                                {
                                    // IF CUSTOMER NOT FOUND, INSERT CUSTOMER DATA
                                    $dataSekolah = array(
                                        'no_npsn' => $value['npsn']
                                        ,'school_name' => $value['nama_sekolah']
                                        ,'alamat' => $value['alamat_lengkap_sekolah']
                                        ,'provinsi' => $value['prov']
                                        ,'kabupaten' => $value['kab']
                                        ,'kecamatan' => $value['kec']
                                        ,'desa' => $value['desa']
                                        ,'phone' => $value['no_telepon']
                                        ,'nama_bendahara' => $value['bendahara_bos']
                                        ,'name' => $value['kepala_sekolah']
                                    );
                                    $idSekolah = $this->mod_general->addData('customer', $dataSekolah);
                                    $id_customer = $idSekolah;

                                }
                                $orderDetail['id_customer'] = $id_customer;
                            }
                            else
                            {

                            }
                            
                            foreach ($order as $detail) {
                                $orderDetail['category'] = $detail['category'];
                                $orderDetail['type'] = $detail['type'];
                            }

                            $orderDetail['korwil_email'] = $value['korwil_email'];
                            $orderDetail['korwil_name'] = $value['korwil_name'];
                            $orderDetail['korwil_phone'] = $value['korwil_phone'];
                            $orderDetail['periode'] = date('Y');
                            $orderDetail['rsm_name'] = $value['rsm_name'];
                            $orderDetail['total_paid'] = $value['total'];
                            $orderDetail['created_date'] = $value['created_date'];

                            $isProductError = in_array(null , $value['id_product']) == true ? true : false ;

                            $isKorwilError = $value['korwil_name'] == null ? true : false ;
                            $korwilDataTemp = '';
                            if($value['korwil_name'] == null)
                            {
                                if($value['kab'] != "")
                                {    
                                    $korwilDataTemp = " Data korwil untuk Kabupaten <b>".$value['kab']."</b> belum ada.";
                                }
                            }

                            $isRSMError = $value['rsm_name'] == null ? true : false ;
                            $rsmDataTemp = "";
                            if($value['rsm_name'] == null)
                            {
                                if($value['kab'] != "")
                                {    
                                    $rsmDataTemp = " Data RSM untuk Kabupaten <b>".$value['kab']."</b> belum ada.";   
                                }
                            }

                            $createdDateTemp = $value['created_date'];

                            // SET VARIABLE ARRAY FOR CHECKING ERROR
                            $checkErrorCategory = array();
                            $checkErrorCategory[] = $isProductError;
                            $checkErrorCategory[] = $isKorwilError;
                            $checkErrorCategory[] = $isRSMError;
                            $checkErrorCategory[] = $isPoError;
                            $checkErrorCategory[] = $isCustomerError;

                            if(in_array(true, $checkErrorCategory))
                            {   
                                $checkErrorNumberPO[] = true;
                            }
                            else
                            {
                                $idOrder = $this->mod_pesananblanja->tambahPesananSiplah($orderDetail);
                                $this->mod_pesananblanja->tambahDetailPesananSiplah($order, $idOrder);
                            }
                            $no++;

                            $noPoTemp = $value['po_number'];
                        }

                        
                    }


        /**
         * FUNCTION FOR SAVE ALL FAILED TRANSFER DATA PER PO NUMBER
         */
        // ========================================================================================================================================
                    $orders_siplah_error = array();
                    $notes = "";
                    if(in_array(true, $checkErrorNumberPO))
                    {   
                        // $kodeBku = implode(array_merge(array_unique($checkDataKodeBuku)), ", ");
                        // $kodeBukuTemp = " Kode Buku ".$kodeBku." belum ada di database ols.";
                        $kodeBukuTemp = "";
                        if($allKodeBukuErrorArray != null)
                        {   
                            foreach ($orderTemp as $kd) {
                                if(in_array($kd['kode_buku'], $allKodeBukuErrorArray)){
                                    $kodeBukuTemp .= $kodeBukuTemp == "" ? $kd['kode_buku'] : ", ".$kd['kode_buku'];
                                }
                            }

                            if($kodeBukuTemp != ""){
                                $kodeBukuTemp = " Kode buku ". $kodeBukuTemp. " tidak ada pada database ols buku sekolah.";
                            }
                        }
                            
                        $notes = 'Kesalahan pada nomor PO '. $noPoTemp .".". $kodeBukuTemp . $korwilDataTemp . $rsmDataTemp . $customerDataTemp ;
                        $orders_siplah_error = array(
                            'po_number' => $noPoTemp,
                            'created_date' => $createdDateTemp,
                            'transfered_date' => date('Y-m-d H:i:s'),
                            'notes_error' => $notes
                        );


                        $checkOrdersSiplahError = $this->mod_pesananblanja->getAll('orders_siplah_error','*','po_number="'.$noPoTemp.'"');

                        if($checkOrdersSiplahError->num_rows() <= 0)
                        {
                            $this->mod_general->addData('orders_siplah_error', $orders_siplah_error);
                        }
                    }
        // ========================================================================================================================================
                }
                
                // // SAVE DATA TO TABLE orders_siplah_temp, WITHOUT ERROR DATA
                // $dataSiplahTempArrayNew = array();
                // if( count($dataSiplahArrayNew) > 0 )
                // {
                //     if(count($dataSiplahTempArray) > 0)
                //     {
                //         foreach ($$dataSiplahTempArray as $dst) {
                //             if(!in_array($dst['po_number'], $dataPOErrorArray)) // $dataPOErrorArray BELUM DI DEKLARASIKAN, ISI DENGAN NO PO ERROR
                //             {
                //                 $dataSiplahTempArrayNew = $dst;
                //             }
                //         }
                //        if($this->mod_general->addBatch($this->tblTemp, $dataSiplahTempArrayNew));
                //     }
                // }
                
                $messages = "";
                if($this->db->trans_status() == true)
                {
                    $this->db->trans_commit();

                    $messages .= "<h3>Data pesanan dari Siplah berhasil di import ke OLS Buku Sekolah.</h3>";
                    $messages .= "Ada beberapa pesanan yang tidak dapat diproses. karena : ";
                    // $messages .= "Ada beberapa pesanan yang tidak dapat diproses, karena : ";

                    $error = array();
                    if($allKodeBukuErrorArray != null)
                    {
                        // echo "<br>Daftar kode buku yang belum ada di database OLS : ";
                        $product = array_merge(array_unique($allKodeBukuErrorArray));
                        $messages .= "<br>* Daftar kode buku berikut belum ada di database OLS : <b>" . implode($product, ", ") . "</b>";
                        // $logFile = date('Y-m-d H:i:s') . " Proses Pesanan Siplah ".$start_date." - ".$end_date." --> Daftar kode buku yang belum ada di database buku sekolah (OLS) : " . implode($product, ", ");
                        // file_put_contents(FCPATH . 'tmp/siplah_logs/siplah_log_files_' . date("Y-m-d") . '.txt', $logFile . PHP_EOL, FILE_APPEND | LOCK_EX);

                        // print_r(array_merge(array_unique($dataKodeBuku)));
                        // echo implode($product, ", ")."<br>";
                        $error[] = true;
                    }
                        
                     if($checkDataKorwil != null)
                    {
                        // echo "<br>Daftar kabupaten yang belum ada data korwil : "; 
                        $korwil = array_merge(array_unique($checkDataKorwil));
                        $messages .= "<br>* Daftar kabupaten berikut belum ada data korwil : <b>" . implode($korwil, ", ") . "</b>";
                        // $logFile = date('Y-m-d H:i:s') . " Proses Pesanan Siplah ".$start_date." - ".$end_date." --> Daftar kabupaten yang belum ada data korwil di database buku sekolah (OLS) : " . implode($korwil, ", ");
                        // file_put_contents(FCPATH . 'tmp/siplah_logs/siplah_log_files_' . date("Y-m-d") . '.txt', $logFile . PHP_EOL, FILE_APPEND | LOCK_EX);
                        // echo implode($korwil, ", ")."<br>";
                        $error[] = true;
                    }
                        
                    if($checkDataRSM != null)
                    {
                        // echo "<br>Daftar Kabupaten yang belum ada data RSM : "; 
                        $rsm = array_merge(array_unique($checkDataRSM));
                        $messages .= "<br> * Daftar Kabupaten berikut belum ada data RSM : <b>" . implode($rsm, ", " . "</b>");
                        // $logFile = date('Y-m-d H:i:s') . " Proses Pesanan Siplah ".$start_date." - ".$end_date." --> Daftar Kabupaten yang belum ada data RSM di database buku sekolah (OLS) : " . implode($rsm, ", ");
                        // file_put_contents(FCPATH . 'tmp/siplah_logs/siplah_log_files_' . date("Y-m-d") . '.txt', $logFile . PHP_EOL, FILE_APPEND | LOCK_EX);
                        // echo implode($rsm, ", ")."<br>";
                        $error[] = true;
                    }

                    if( in_array(true, $checkDataCustomer) )
                    {
                        $messages .= "<br> * Tidak ada data sekolah pada pesanan siplah. </b>";
                        $error[] = true;
                    }

                    $callback = array(
                        'success' => true,
                        'message' => $messages,
                        'data' => $dataSiplahArrayNew,
                        'error' => in_array(true, $error) ? true : false
                    ); 
                }
                else
                {
                    $this->db->trans_rollback();

                    $callback = array(
                        'success' => false,
                        'message' => "Gagal melakukan import data dari Siplah ke OLS Buku Sekolah",
                        'data' => null,
                        'error' => null
                    ); 
                    // echo json_encode($dataSiplahArrayNew);
                }
            }
            else
            {
                if(count($dataOlsArray) > 0)
                {
                    $callback = array(
                        'success' => false,
                        'message' => "Data sudah di import ke OLS Buku Sekolah.",
                        'data' => null,
                        'error' => null
                    ); 
                }
                else
                {
                    $callback = array(
                        'success' => false,
                        'message' => "Maaf, data tidak ditemukan. Gagal melakukan import data dari Siplah ke OLS Buku Sekolah. ",
                        'data' => null,
                        'error' => null
                    ); 
                }
            }
        }
        else
        {
            $callback = array(
                'success' => false,
                'message' => "Maaf, Terjadi kesalahan pada sistem. Tidak dapat terhubung ke Siplah. Silahkan coba beberapa saat lagi.",
                'data' => null,
                'error' => null
            ); 
        }
        
        echo json_encode($callback);

        // print_r(json_encode($dataSiplahArrayNew));
        // echo json_encode($dataSiplahArrayNew);
        // return json_encode($dataSiplahArrayNew);
    }    

    public function viewError()
    {
        $this->_output['content'] = $this->load->view('admin/pesananblanja/list', '', true);
        $this->_output['script_js'] = $this->load->view('admin/pesananblanja/js', '', true);
        $this->load->view('admin/template', $this->_output);

        // $this->_output['content'] = $this->load->view('admin/pesananblanja/list', '', true);
        // $this->_output['script_css'] = $this->load->view('admin/report/css', '', true);
        // $this->_output['script_js'] = $this->load->view('admin/report/js', '', true);
        // $this->load->view('admin/template', $this->_output);
    }

    public function listError()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/orders');
        }
        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select('po_number as po_number, created_date as created_date, transfered_date as transfered_date, notes_error as notes_error');


        $this->datatables->from('orders_siplah_error');
        $this->datatables->where('status_retransfered', '0');
        $this->datatables->add_column('aksi', '<button type="button" id="btn-transfer" class="btn btn-success btn-sm" value="$1">TRANSFER</button>', 'po_number');
        $this->output->set_output($this->datatables->generate());
    }

    public function listErrorPost()
    {
        // $po_number = '000000849';
        
        $po_number = $this->input->post('po_number');

        $getDataError = $this->mod_pesananblanja->getAll('orders_siplah_error', '*', 'po_number = "'.$po_number.'"');
        $dataError = $getDataError->row_array();

        $date = $dataError['created_date'];
        $start_date = date('Y-m-d', strtotime("-1 day", strtotime($date)));
        $end_date = date('Y-m-d', strtotime("+1 day", strtotime($date)));

        $this->db->trans_begin();

        $allKodeBukuErrorArray = array();
        $allKodeBukuPOErrorArray = array();
        
        // DECLARATION VARIABLE ARRAYS
        $checkDataKorwil = array();
        $checkDataRSM = array();
        $checkDataCustomer = array();
        $dataSiplah = array();
        $dataSiplahArray = array();
        $dataSiplahArrayNew = array();
        $poNumberOlsArray = array();
		
		/* tidak dipakai. Fa, 20200508
        $codeArray = array(
            'EKC'=> 7
            ,'GA' => 6 
            ,'GM' => 6
            ,'KA' => 6
            ,'KM' => 6 
            ,'MEN' => 12
            ,'PGC' => 7
            ,'SA' => 6 
            ,'SM' => 6
        );
		*/
		
        // GET DATA FROM SIPLAH
        // ============================================================================================================================
        // BEGIN
        // INITIALIZE CURL


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => getenv('SIPLAH_API_GETLIST').$params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 180000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        // END
        // ============================================================================================================

        $dataSiplah = json_decode($response);
        
        // DECLARATION VARIABLE ARRAYS
        $poNumberArray = array();
        $dataSiplahArrayNew = array();
        $dataSiplahArray = array();

        $varType = gettype($dataSiplah);

        // IF CONNECTED TO API SIPLAH
        if($varType != 'NULL' )
        {
            foreach($dataSiplah as $arr)
            {
                if($arr->po_number == $po_number)
                {
                    $dataSiplahArray[] = (array) $arr;
                }
            }

                    foreach ($dataSiplahArray as $data) 
                    {
                        // GET DATA SKU
                        // $checkSku = preg_replace('/\d+/u', '', substr($data['sku'],0,5));
                        // if($checkSku == "")
                        // {
                        //     $dataSku = $data['sku'];
                        // }
                        // else
                        // {
                        //     $lengthSku = $codeArray[strtoupper($checkSku)];
                        //     $dataSku = strtoupper(substr($data['sku'],0,$lengthSku));
                        // }
                        $checkSku = $data['sku'][-1];
                        if($checkSku == 'X' || $checkSku == 'Y' || $checkSku == 'Z' || $checkSku == 'x' || $checkSku == 'y' || $checkSku == 'z')
                        {
                            $lengthSku = strlen($data['sku']) - 1;
                            $dataSku = strtoupper(substr($data['sku'],0,$lengthSku));
                        }    
                        else
                        {
                            $dataSku = $data['sku'];
                        }  
                        // GET DATA CATEGORY AND TYPE
                        $dataProduct = $this->mod_pesananblanja->getAll('product','*','kode_buku="'.$dataSku.'"')->row_array();
                            
                        $product_id = $dataProduct['id_product'];
                        if($product_id == null)
                        {
                            $allKodeBukuErrorArray[] = $dataSku;
                            $allKodeBukuPOErrorArray[] = $data['po_number'];
                        }
                    }

                $allKodeBukuErrorArray = array_unique($allKodeBukuErrorArray);
                $allKodeBukuPOErrorArray = array_unique($allKodeBukuPOErrorArray);

                    $count = 0;
                    $totalPay = 0;
                    $pesanan = array();
                    foreach ($dataSiplahArray as $data) {
                        // print_r($data);
                        // GET DATA SKU
                        // $checkSku = preg_replace('/\d+/u', '', substr($data['sku'],0,5));
                        // // echo "testing : ".$checkSku."<br>";
                        // if($checkSku == "")
                        // {
                        //     $dataSku = $data['sku'];
                        // }
                        // else
                        // {
                        //     $lengthSku = $codeArray[strtoupper($checkSku)];
                        //     $dataSku = strtoupper(substr($data['sku'],0,$lengthSku));
                        // }

                        $checkSku = $data['sku'][-1];
                        if($checkSku == 'X' || $checkSku == 'Y' || $checkSku == 'Z' || $checkSku == 'x' || $checkSku == 'y' || $checkSku == 'z')
                        {
                            $lengthSku = strlen($data['sku']) - 1;
                            $dataSku = strtoupper(substr($data['sku'],0,$lengthSku));
                        }    
                        else
                        {
                            $dataSku = $data['sku'];
                        }  
                        // echo "testing : ".$dataSku."<br><br>";

                        // GET DATA CATEGORY AND TYPE
                        $dataProduct = $this->mod_pesananblanja->getAll('product','*','kode_buku="'.$dataSku.'"')->row_array();
                        $product_id = $dataProduct['id_product'];
                        // if($product_id == null)
                        // {
                        //     $checkDataKodeBuku[] = $dataSku;
                        //     $checkDataKodeBukuPO[] = $data['po_number'];
                        // }

                        $id_category = $dataProduct['id_category_default'];
                        $dataCategory = $this->mod_pesananblanja->getAll('category','*','id_category="'.$id_category.'"')->row_array();
                        $categoryId = $dataCategory['id_category'];
                        $category = $dataCategory['name'];
                        $id_parent = $dataCategory['id_parent'];

                        $dataType = $this->mod_pesananblanja->getAll('category','*','id_category="'.$id_parent.'"')->row_array();
                        $typeAlias = $dataType['alias'];
                        $typeName = $dataType['name'];
                        $typeId = $dataType['id_category'];
                        $type = $typeAlias ?? $typeName;

                        // GET DATA KORWIL
                        $kabupaten = $data['kab'];
                        $dataKorwil = $this->mod_pesananblanja->getDataKorwil($kabupaten)->row_array();
                        if($dataKorwil == null)
                        {
                            $checkDataKorwil[] = $kabupaten;
                        }

                        $korwilEmail = $dataKorwil['email'];
                        $korwilName = $dataKorwil['name'];
                        $korwilPhone = $dataKorwil['telp'];

                        // GET DATA RSM
                        $dataRSM = $this->mod_pesananblanja->getDataRSM($kabupaten)->row_array();
                        if($dataRSM == null)
                        {
                            $checkDataRSM[] = $kabupaten;
                        }
                        $rsm_name = $dataRSM['name'];
                            
                        // GENERATE DATA PESANAN WITH TYPE AND CATEGORY
                        $pesanan[$typeId][$categoryId]['pesanan'][$count] = [
                            'type' => $type,
                            'isbn' => $dataProduct['reference'],
                            'category' => $category,

                            'kode_buku' => $dataSku,
                            'product_id' => $product_id,
                            'product_name' => $dataProduct['name'], // $data['nama_produk'],
                            'product_quantity' => $data['qty'],
                            'unit_price' => $data['harga_satuan_dengan_ongkir'],
                            'total_price' => $data['harga_satuan_dengan_ongkir'] * $data['qty']
                        ];
                        $count++;
                        $totalPay += $data['harga_satuan_dengan_ongkir'] * $data['qty'];
                        if (array_key_exists('total', $pesanan[$typeId][$categoryId])) {
                            $pesanan[$typeId][$categoryId]['total'] += $data['harga_satuan_dengan_ongkir'] * $data['qty'];
                        } else {
                            $pesanan[$typeId][$categoryId]['total'] = $data['harga_satuan_dengan_ongkir'] * $data['qty'];
                        }
                        
                        $pesanan[$typeId][$categoryId]['id_product'][] = $dataProduct['id_product'];
                        $pesanan[$typeId][$categoryId]['sku'][] = $dataSku;
                        $pesanan[$typeId][$categoryId]['po_number'] = $data['po_number'];
                        $pesanan[$typeId][$categoryId]['created_date'] = $data['created_at'];
                        // $pesanan[$typeId][$categoryId]['created_date'] = $data['updated_at'];
                        $pesanan[$typeId][$categoryId]['korwil_email'] = $korwilEmail;
                        $pesanan[$typeId][$categoryId]['korwil_name'] = $korwilName;
                        $pesanan[$typeId][$categoryId]['korwil_phone'] = $korwilPhone;
                        $pesanan[$typeId][$categoryId]['rsm_name'] = $rsm_name;

                        $pesanan[$typeId][$categoryId]['npsn'] = $data['npsn'];
                        $pesanan[$typeId][$categoryId]['nama_sekolah'] = $data['nama_sekolah'];
                        $pesanan[$typeId][$categoryId]['alamat_lengkap_sekolah'] = $data['alamat_lengkap_sekolah'];
                        $pesanan[$typeId][$categoryId]['prov'] = $data['prov'];
                        $pesanan[$typeId][$categoryId]['kab'] = $data['kab'];
                        $pesanan[$typeId][$categoryId]['kec'] = $data['kec'];
                        $pesanan[$typeId][$categoryId]['desa'] = $data['desa'];
                        $pesanan[$typeId][$categoryId]['no_telepon'] = $data['no_telepon'];
                        $pesanan[$typeId][$categoryId]['bendahara_bos'] = $data['bendahara_bos'];
                        $pesanan[$typeId][$categoryId]['kepala_sekolah'] = $data['kepala_sekolah'];
                    } 

                    $checkErrorNumberPO = array();
                    $orderTemp = array();
                    foreach ($pesanan as $category => $data1) {
                        $no = 0;
                        $po = "";

                        if(count($data1) > 1 ){
                            $no += 1;
                        }

                        // SET VARIABLE FOR CHECKING PRODUCT
                        $isPoError = false;
                        // $isPOProductError = array();
                        // $isPOProductError = array_unique($allKodeBukuPOErrorArray);
                        $isCustomerError = array();

                        $korwilDataTemp = "";
                        $rsmDataTemp = "";
                        $customerDataTemp = "";
                        $noPoTemp = "";
                        $createdDateTemp = "";
                        foreach ($data1 as $class => $value) {
                            // ASSIGN PO NUMBER TO VARIABLE
                            $po_number = $value['po_number'];

                            // CHECKING PO NUMBER WITH PRODUCT NOT FOUND IN OLS
                            if(in_array($po_number,$allKodeBukuPOErrorArray))
                            {
                                $isPoError = true;
                            }

                            // GENERATE PO NUMBER
                            if($no > 0)
                            {
                                $nol = strlen($no) > 1 ? '' : '0';
                                $po = $po_number.'_'.$nol.$no;
                            }
                            else
                            {
                                $po = $po_number;
                            }

                            $order = $value['pesanan'];
                            $orderTemp = array_merge($orderTemp, $value['pesanan']);
                            $orderDetail = [];
                            $orderDetail['po_number'] = $po;
                            $orderDetail['reference_other'] = $po_number;

                            $customerDataTemp = "";
                            if($value['npsn'] == null || $value['npsn'] == "")
                            {
                                $customerDataTemp = " Tidak ada data sekolah pada pesanan siplah.";
                            }

                            $isCustomerError = $value['npsn'] == null || $value['npsn'] == "" ? true : false;

                            $checkDataCustomer[] = $isCustomerError == true ? true : false;

                            if($isCustomerError == false)
                            {
                                // GET DATA CUSTOMER
                                $dataCustomer = $this->mod_pesananblanja->getAll('customer','*','no_npsn="'.$value['npsn'].'"')->row_array();
                                $id_customer = $dataCustomer['id_customer'];

                                if($id_customer == null )
                                {
                                    // IF CUSTOMER NOT FOUND, INSERT CUSTOMER DATA
                                    $dataSekolah = array(
                                        'no_npsn' => $value['npsn']
                                        ,'school_name' => $value['nama_sekolah']
                                        ,'alamat' => $value['alamat_lengkap_sekolah']
                                        ,'provinsi' => $value['prov']
                                        ,'kabupaten' => $value['kab']
                                        ,'kecamatan' => $value['kec']
                                        ,'desa' => $value['desa']
                                        ,'phone' => $value['no_telepon']
                                        ,'nama_bendahara' => $value['bendahara_bos']
                                        ,'name' => $value['kepala_sekolah']
                                    );
                                    $idSekolah = $this->mod_general->addData('customer', $dataSekolah);
                                    $id_customer = $idSekolah;

                                }
                                $orderDetail['id_customer'] = $id_customer;
                            }
                            else
                            {

                            }
                            
                            foreach ($order as $detail) {
                                $orderDetail['category'] = $detail['category'];
                                $orderDetail['type'] = $detail['type'];
                            }

                            $orderDetail['korwil_email'] = $value['korwil_email'];
                            $orderDetail['korwil_name'] = $value['korwil_name'];
                            $orderDetail['korwil_phone'] = $value['korwil_phone'];
                            $orderDetail['periode'] = date('Y');
                            $orderDetail['rsm_name'] = $value['rsm_name'];
                            $orderDetail['total_paid'] = $value['total'];
                            $orderDetail['created_date'] = $value['created_date'];

                            $isProductError = in_array(null , $value['id_product']) == true ? true : false ;

                            $isKorwilError = $value['korwil_name'] == null ? true : false ;
                            $korwilDataTemp = '';
                            if($value['korwil_name'] == null)
                            {
                                if($value['kab'] != "")
                                {    
                                    $korwilDataTemp = " Data korwil untuk Kabupaten <b>".$value['kab']."</b> belum ada.";
                                }
                            }

                            $isRSMError = $value['rsm_name'] == null ? true : false ;
                            $rsmDataTemp = "";
                            if($value['rsm_name'] == null)
                            {
                                if($value['kab'] != "")
                                {    
                                    $rsmDataTemp = " Data RSM untuk Kabupaten <b>".$value['kab']."</b> belum ada.";   
                                }
                            }

                            $createdDateTemp = $value['created_date'];

                            // SET VARIABLE ARRAY FOR CHECKING ERROR
                            $checkErrorCategory = array();
                            $checkErrorCategory[] = $isProductError;
                            $checkErrorCategory[] = $isKorwilError;
                            $checkErrorCategory[] = $isRSMError;
                            $checkErrorCategory[] = $isPoError;
                            $checkErrorCategory[] = $isCustomerError;

                            if(in_array(true, $checkErrorCategory))
                            {   
                                $checkErrorNumberPO[] = true;
                            }
                            else
                            {
                                // // SAVE DATA ORDER TO orders_siplah and orders_siplah_temp
                                // $idOrder = $this->mod_general->addData($this->tblHeader, $orderDetail);
                                // $this->mod_pesananblanja->tambahDetailPesananSiplah_temp($order, $idOrder);

                                // SAVE DATA ORDER TO orders and order_detail
                                $idOrder = $this->mod_pesananblanja->tambahPesananSiplah($orderDetail);
                                $this->mod_pesananblanja->tambahDetailPesananSiplah($order, $idOrder);

                                $data = array(
                                    'status_retransfered' => '1',
                                    'retransfered_date' => date('Y-m-d H:i:s')
                                );
                                $this->mod_general->updateData('orders_siplah_error', $data, 'po_number', $po_number);

                                
                                // UPDATE DATA SEMESTER FOR EVERY ORDERS
                                $semester = $this->m_pesanan->get_semester($idOrder);
                                if($semester->num_rows() > 0)
                                {
                                    foreach ($semester->result_array() as $data) {
                                        $this->m_pesanan->upd_semester($idOrder, $data['semester']);
                                    }
                                }
                            }
                            $no++;

                            $noPoTemp = $value['po_number'];
                        }
        
                    }
        /**
         * FUNCTION FOR SAVE ALL FAILED TRANSFER DATA PER PO NUMBER
         */
        // ========================================================================================================================================
                    $orders_siplah_error = array();
                    $notes = "";
                    if(in_array(true, $checkErrorNumberPO))
                    {   
                        // $kodeBku = implode(array_merge(array_unique($checkDataKodeBuku)), ", ");
                        // $kodeBukuTemp = " Kode Buku ".$kodeBku." belum ada di database ols.";
                        $kodeBukuTemp = "";
                        if($allKodeBukuErrorArray != null)
                        {   
                            foreach ($orderTemp as $kd) {
                                if(in_array($kd['kode_buku'], $allKodeBukuErrorArray)){
                                    $kodeBukuTemp .= $kodeBukuTemp == "" ? $kd['kode_buku'] : ", ".$kd['kode_buku'];
                                }
                            }

                            if($kodeBukuTemp != ""){
                                $kodeBukuTemp = " Kode buku ". $kodeBukuTemp. " tidak ada pada database ols buku sekolah.";
                            }
                        }
                            
                        $notes = 'Kesalahan pada nomor PO '. $noPoTemp .".". $kodeBukuTemp . $korwilDataTemp . $rsmDataTemp . $customerDataTemp ;
                        $orders_siplah_error = array(
                            'po_number' => $noPoTemp,
                            'created_date' => $createdDateTemp,
                            'transfered_date' => date('Y-m-d H:i:s'),
                            'notes_error' => $notes
                        );


                        $checkOrdersSiplahError = $this->mod_pesananblanja->getAll('orders_siplah_error','*','po_number="'.$noPoTemp.'"');

                        if($checkOrdersSiplahError->num_rows() <= 0)
                        {
                            // $this->mod_general->addData('orders_siplah_error', $orders_siplah_error);
                        }
                    }
        // ========================================================================================================================================

                $messages = "";
                if($this->db->trans_status() == true)
                {
                    $this->db->trans_commit();

                    $messages .= "<h3>Data pesanan dari Siplah gagal ditransfer ke OLS Buku Sekolah.</h3>";
                    $messages .= "Pesanan yang tidak dapat diproses, karena : ";

                    $error = array();
                    if($allKodeBukuErrorArray != null)
                    {
                        $product = array_merge(array_unique($allKodeBukuErrorArray));
                        $messages .= "<br>* Daftar kode buku berikut belum ada di database OLS : <b>" . implode($product, ", ") . "</b>";
                        $error[] = true;
                    }
                        
                     if($checkDataKorwil != null)
                    {
                        if( !in_array(true, $checkDataCustomer) )
                        {
                            $korwil = array_merge(array_unique($checkDataKorwil));
                            $messages .= "<br>* Daftar kabupaten berikut belum ada data korwil : <b>" . implode($korwil, ", ") . "</b>";
                            $error[] = true;
                        }
                    }
                        
                    if($checkDataRSM != null)
                    {
                        // echo "<br>Daftar Kabupaten yang belum ada data RSM : "; 
                        if( !in_array(true, $checkDataCustomer) )
                        {
                        $rsm = array_merge(array_unique($checkDataRSM));
                        $messages .= "<br> * Daftar Kabupaten berikut belum ada data RSM : <b>" . implode($rsm, ", " . "</b>");
                        $error[] = true;
                        }
                    }

                    if( in_array(true, $checkDataCustomer) )
                    {
                        $messages .= "<br> * Tidak ada data sekolah pada pesanan siplah. </b>";
                        $error[] = true;
                    }

                    $callback = array(
                        'success' => true,
                        'message' => $messages,
                        'data' => $checkDataCustomer,
                        'error' => in_array(true, $error) ? true : false
                    ); 
                }
                else
                {
                    $this->db->trans_rollback();

                    $callback = array(
                        'success' => false,
                        'message' => "Gagal melakukan import data dari Siplah ke OLS Buku Sekolah",
                        'data' => null,
                        'error' => null
                    ); 
                    // echo json_encode($dataSiplahArrayNew);
                }
        }
        else
        {
            $callback = array(
                'success' => false,
                'message' => "Maaf, Terjadi kesalahan pada sistem. Tidak dapat terhubung ke Siplah. Silahkan coba beberapa saat lagi.",
                'data' => null,
                'error' => null
            );
        }

        echo json_encode($callback);
    }

    public function testing()
    {
        $this->updateDataOrder('000001160');
    }

    public function updateDataOrder($po_number_array)
    {
        foreach ($po_number_array as $po_number) {
            $userId = '';
            if(empty($this->adm_id))
            {
                $userId = '76';
            }
            else
            {
                $userId = $this->session->userdata('adm_id');
            }

            $udo_id_order = $this->mod_pesananblanja->getAll('orders','id_order','left(reference,9)='.$po_number)->result_array();
            foreach($udo_id_order as $do)
            {
                $idOrder = $do['id_order'];
                $isInSCMProcess = $this->mod_pesananblanja->isInSCMProcess($idOrder);
                if ($isInSCMProcess) {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Pesanan tidak dapat dibatalkan. Silahkan hubungi bagian Supply Chain',
                    ];
                } else {
                    $data = [
                        'current_state' => 2,
                        'alasan_batal' => "Dibatalkan dari aplikasi siplah",
                        'date_upd' => date('Y-m-d H:i:s'),
                    ];
                    $proc = $this->mod_general->updateData('orders', $data, 'id_order', $idOrder);
                    if ($proc) {
                            $dataHistory = [
                                'id_employee' => $userId,
                                'id_order' => $idOrder,
                                'id_order_state' => 2,
                                'date_add' => date('Y-m-d H:i:s'),
                            ];
                            $procHistory = $this->mod_general->addData('order_history', $dataHistory);
                            if ($procHistory) {
                                if ( ! $isInSCMProcess) {
                                    $this->mod_general->deleteData('order_scm', 'id_order', $idOrder);
                                }
                            } else {
                               // failed message
                            }
                    } else {
                        // failed message
                    }
                }
            }
        }
    }
}
