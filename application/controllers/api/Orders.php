<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Orders extends RestController {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function data_get()
    {
        $kode_pesanan = $this->get('kode_pesanan');

        if($kode_pesanan == null)
        {
            $where = "";
        }
        else
        {
            $where = " where a.`reference`='". $kode_pesanan. "'";
        }

        $orders = $this->rest->db->query("select a.`reference` as kode_pesanan, a.`date_add` as tanggal, b.school_name as pemesan, a.`sales_name` as sales, a.`korwil_name` as korwil, count(c.`id_order`) as item from orders a inner join `customer` b on a.`id_customer`=b.`id_customer` inner join order_detail c on a.`id_order`=c.`id_order` ". $where ." group by c.`id_order`")->result_array();

        // $orders = $this->rest->db->query("select a.`reference` as kode_pesanan, a.`date_add` as tanggal, b.school_name as pemesan, a.`sales_name` as sales, a.`korwil_name` as korwil, count(c.`id_order`) as item from orders a inner join `customer` b on a.`id_customer`=b.`id_customer` inner join order_detail c on a.`id_order`=c.`id_order` where a.`reference`='". $kode_pesanan. "' group by c.`id_order`")->result_array();

        if ($orders)
        {
            // Set the response and exit
            $this->response($orders, 200);
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => 404,
                'message' => 'Data pesanan tidak ditemukan.'
            ], 404 );
        }
    }

    public function ongkir_detail_get()
    {
        // GET DATA VARIABLE
        $kode_pesanan = $this->get('kode_pesanan');
        $kabupaten = $this->get('kabupaten');
        $no_npsn = $this->get('no_npsn');
        $sku = $this->get('sku');
        $qty = $this->get('qty');

        if($qty == "" || $qty == null || $qty == 0)
        {
            // Set the response and exit
            $this->response([
                'status' => 422,
                'message' => 'Qty tidak boleh nol (0) atau kosong'
            ], 422 );
            exit();
        }

        if($sku == "" || $sku == null)
        {
            // Set the response and exit
            $this->response([
                'status' => 422,
                'message' => 'SKU tidak boleh kosong'
            ], 422 );
            exit();
        }

        // GET DATA SKU
        // $check_sku = preg_replace('/\d+/u', '', substr($sku,0,5));
        // if($check_sku == "")
        // {
        //     $data_sku = $sku;
        // }
        // else
        // {
        //     $length_sku = $code_array[strtoupper($check_sku)];
        //     $data_sku = strtoupper(substr($sku,0,$length_sku));
        // }

        $check_sku = $sku[-1];
        if($check_sku == 'P' || $check_sku == 'X' || $check_sku == 'p' || $check_sku == 'x')
        {
            $length_sku = strlen($sku) - 1;
            $data_sku = strtoupper(substr($sku,0,$length_sku));
        }    
        else
        {
            $data_sku = $sku;
        }

        $kabupaten_check = array();

        if($kabupaten !== null)
        {
            $kabupaten_check = $this->rest->db->query("select * from master_kabupaten_zona where kabupaten='".$kabupaten."'")->result_array();
        }

        if(count($kabupaten_check) == 0)
        {
            // Set the response and exit
            $this->response([
                'status' => 404,
                'message' => 'Data kabupaten tidak ditemukan.'
            ], 404 );
            exit();
        }

        if($no_npsn == "")
        {
            $no_npsn = "-";
        }

        $no_npsn_check = $this->rest->db->query("select * from customer where no_npsn='".$no_npsn."'")->result_array();

        if(count($no_npsn_check) == 0)
        {
            $query = "SELECT `biaya_kirim_per_kg`, `kabupaten` from master_kabupaten_zona where  `kabupaten`='".$kabupaten."'";
            $ongkir_per_kg = $this->rest->db->query($query)->result_array();
        }
        else
        {
            // GET DATA ONGKIR BERDASARKAN KABUPATEN
            $ongkir_per_kg = $this->rest->db->query("SELECT b.`biaya_kirim_per_kg` as biaya_kirim_per_kg, b.`kabupaten` as kabupaten FROM `customer` a INNER JOIN `master_kabupaten_zona` b ON a.`kabupaten`=b.`kabupaten` WHERE a.`no_npsn`='".$no_npsn."'")->result_array();
        }

        $test = array(
            "data" => $this->rest->db->last_query(),
            "data 2" => $ongkir_per_kg[0]
        );

        if(count($ongkir_per_kg) == 0)
        {
            // Set the response and exit
            $this->response([
                'status' => 404,
                'message' => 'Data ongkir tidak ditemukan.'
            ], 404 );
            exit();
        }

        // GET DATA PRODUCT
        $product_data = $this->rest->db->query("SELECT weight FROM product WHERE kode_buku='".$data_sku."'")->result_array();

        if(count($product_data) == 0)
        {
            // Set the response and exit
            $ongkir_per_item_default       = $ongkir_per_kg[0]['biaya_kirim_per_kg'] * 0.5 * 1.1;
            $ongkir_per_item_total_default = $ongkir_per_item_default * $qty;

            $data_ongkir_default = array(
                "kode_pesanan"          => $kode_pesanan,
                "kabupaten"             => $ongkir_per_kg[0]['kabupaten'],
                "no_npsn"               => $no_npsn,
                "sku"                   => $sku,
                "qty"                   => $qty,
                "ongkir_per_item"       => sprintf("%.2f", $ongkir_per_item_default),
                "ongkir_per_item_total" => sprintf("%.2f", $ongkir_per_item_total_default)
            );

            $this->response($data_ongkir_default, 200);

            // $this->response([
            //     'status' => 404,
            //     'message' => 'Data product tidak ditemukan.'
            // ], 404 );
            exit();
        }

        $ongkir_per_item       = $ongkir_per_kg[0]['biaya_kirim_per_kg'] * $product_data[0]['weight'] * 1.1;
        $ongkir_per_item_total = $ongkir_per_item * $qty;

        $data_ongkir = array(
            "kode_pesanan"          => $kode_pesanan,
            "kabupaten"             => $ongkir_per_kg[0]['kabupaten'],
            "no_npsn"               => $no_npsn,
            "sku"                   => $sku,
            "qty"                   => $qty,
            "ongkir_per_item"       => sprintf("%.2f", $ongkir_per_item),
            "ongkir_per_item_total" => sprintf("%.2f", $ongkir_per_item_total)
        );

        if ($data_ongkir)
        {
            // Set the response and exit
            $this->response($data_ongkir, 200);
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => 404,
                'message' => 'Data ongkir tidak ditemukan.'
            ], 404 );
        }
    }

    public function ongkir_detail_temp_get()
    {
        // localhost/api/orders/ongkir_detail?GRM-API-KEY=8cwow00w8ow00kos40ss84sw88kcwc48cokos8k1&first_date=2019-10-01&last_date=2019-10-01&kode_pesanan=TNOTQYFCO
        // localhost/api/orders/ongkir_detail?GRM-API-KEY=8cwow00w8ow00kos40ss84sw88kcwc48cokos8k1&first_date=2019-10-01&last_date=2019-10-01
        // localhost/api/orders/ongkir_detail?GRM-API-KEY=8cwow00w8ow00kos40ss84sw88kcwc48cokos8k1&kode_pesanan=TNOTQYFCO
        $where_kode = "";
        $where_date = "";
        $kode_pesanan = $this->get('kode_pesanan');
        $first_date = $this->get('first_date');
        $last_date = $this->get('last_date');

        if($first_date !== null)
        {
            $where_date = " where a.`date_add` >= '" . $first_date . " 00:00:01' and a.`date_add` <= '" . $last_date . " 23:59:59' ";
        }

        if($kode_pesanan != null) 
        {
            if($first_date == null)
            {
                $where_kode = " where a.reference='" . $kode_pesanan . "' ";
            }
            else
            {
                $where_kode = " and a.reference='" . $kode_pesanan . "' ";
            }
        }

        $orders = $this->rest->db->query("SELECT a.reference as no_order, d.kabupaten as kabupaten, b.kode_buku as sku, b.product_quantity as qty, (d.`biaya_kirim_per_kg` * e.`weight`) * 1.1 as ongkir_per_item, ((d.`biaya_kirim_per_kg` * e.`weight`) * 1.1) * b.product_quantity as ongkir_per_order FROM orders a INNER JOIN order_detail b ON a.`id_order`=b.`id_order` INNER JOIN `customer` c ON a.`id_customer`=c.`id_customer` INNER JOIN `master_kabupaten_zona` d ON d.`kabupaten`=c.`kabupaten` inner join `product` e on b.`kode_buku`=e.`kode_buku`".$where_date.$where_kode)->result_array();

        if ($orders)
        {
            // Set the response and exit
            $this->response($orders, 200);
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => 404,
                'message' => 'Data pesanan detail ongkir tidak ditemukan.'
            ], 404 );
        }
    }



    public function data_ongkir_get()
    {
        $data_ongkir = $this->rest->db->query("select * from master_ongkos_kirim")->result_array();

        if(count($data_ongkir) == 0)
        {
            // Set the response and exit
            $this->response([
                'status' => 404,
                'success' => false,
                'message' => 'Data ongkir tidak ditemukan.'
            ], 404 );
            exit();
        }

        $response = array(
            'status' => 200,
            'success' => true,
            'data_ongkir' => $data_ongkir
        );
        $this->response($response, 200);
    }

    public function data_post()
    {
        $kode_pesanan = $this->post('kode_pesanan');

        if($kode_pesanan == null)
        {
            $where = "";
        }
        else
        {
            $where = " where a.`reference`='". $kode_pesanan. "'";
        }

        $orders = $this->rest->db->query("select a.`reference` as kode_pesanan, a.`date_add` as tanggal, b.school_name as pemesan, a.`sales_name` as sales, a.`korwil_name` as korwil, count(c.`id_order`) as item from orders a inner join `customer` b on a.`id_customer`=b.`id_customer` inner join order_detail c on a.`id_order`=c.`id_order` ". $where ." group by c.`id_order`")->result_array();

        // $orders = $this->rest->db->query("select a.`reference` as kode_pesanan, a.`date_add` as tanggal, b.school_name as pemesan, a.`sales_name` as sales, a.`korwil_name` as korwil, count(c.`id_order`) as item from orders a inner join `customer` b on a.`id_customer`=b.`id_customer` inner join order_detail c on a.`id_order`=c.`id_order` where a.`reference`='". $kode_pesanan. "' group by c.`id_order`")->result_array();

        if ($orders)
        {
            // Set the response and exit
            $this->response($orders, 200);
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => 404,
                'message' => 'Data pesanan tidak ditemukan.'
            ], 404 );
        }
    }

    // ORDER PROCESS FOR CATEGORY STEAM
    public function order_process_post()
    {
        $this->rest->db->trans_begin();
        /**
         * NOTE :
         * -. DEFAULT STATUS ORDER = 3
         *
         * request type         => POST
         * settingan header     => Content-Type = application/json; charset=UTF-8;
         * key                  => GRM_API_KEY = 8cwow00w8ow00kos40ss84sw88kcwc48cokos8k1
         */
        
        /**
         CONTOH DATA JSON 
         // DATA JSON DEFAULT
        {
            "data_order" :
                {
                    "kode_pesanan" : "XX1000000", // maksimal 15 karakter, huruf dan angka tanpa special karakter

                    "id_customer" : "20101506", // maximal 50 karakter, 
                    "nama_customer" : "SMKS MUHAMMADIYAH 4 JAKARTA",
                    "kabupaten" : "Kota Jakarta Barat",

                    "category" : "Produk STEAM", 
                    "type" : "STEAM",
                    "total_bayar" : "200000",
                    "periode" : "2020",
                    "tanggal" : "2020-01-27 09:08:00"
                },
            "data_order_detail" :
            [
                {
                    "kode_produk" : "ST0001",
                    "qty" : "15"
                },
                {
                    "kode_produk" : "ST0002",
                    "qty" : "10"
                }
            ]
        }

        // DATA JSON MODIFIKASI WITH STATIC INPUT 
        {
            "data_order" :
                {
                    "kode_pesanan" : "20200116100037799", 
                    "total_bayar" : "200000",
                    "tanggal" : "2020-01-30T09:00:37.801Z"
                    "notes" : "notes" // buyer notes
                },
            "data_order_detail" :
            [
                {
                    "kode_produk" : "ST0001",
                    "qty" : "15"
                },
                {
                    "kode_produk" : "ST0002",
                    "qty" : "10"
                }
            ]
        }
         */
        
        // DEFINE VARIABLE
        $id_customer = "";
        
        // GET DATA FROM INPUT FORM
        // $data_customer = $this->post('data_customer');
        $data_order = $this->post('data_order');
        $data_order_detail = $this->post('data_order_detail');

        // $id_customer_check = $this->rest->db->query('select * from customer where no_npsn = "'.$data_customer['id_customer'].'"')->result_array();


        $notes = $data_order["notes"];

        $notes_explode  = explode('#', $notes);
        $nama_customer  = $notes_explode[0];
        $alamat         = $notes_explode[1];
        $phone          = preg_replace('/\D/', '', $notes_explode[2]);;
        $provinsi       =  "Prop. D.K.I. Jakarta"; // $data_order["propinsi"];
        $kabupaten      =  "Kota Jakarta Barat"; // $data_order["kabupaten"];
        $kecamatan      =  "Kec. Palmerah"; // $data_order["kecamatan"];
        
        $this->rest->db->select('*');
        $this->rest->db->from('customer');
        $this->rest->db->where('phone', $phone);
        $id_customer_check = $this->rest->db->get()->row_array();

        /** CUSTOMER */
        if($id_customer_check == false)
        {
            $data_customer_new = array(
                "no_npsn" => $id_customer, // hanya angka
                "school_name" => $nama_customer,
                "provinsi" => $provinsi, // default, untuk sementara   =>  Prop. D.K.I. Jakarta
                "kabupaten" => $kabupaten, // default, untuk sementara =>  Kota Jakarta Barat
                "kecamatan" => $kecamatan, // default, untuk sementara =>  Kec. Palmerah
                "phone" => $phone,
                "bentuk" => "SMA", // default, untuk sementara => SMA
                "zona" => 1, // default, untuk sementara => 1

            );
            // insert data customer
            $query = $this->rest->db->insert('customer', $data_customer_new);
            $id_customer = $this->rest->db->insert_id();
        }
        else
        {
            $id_customer = $id_customer_check["id_customer"];
        }


        /** SALES */
        $id_employee = "";
        $code_employee = $data_order['kode_pembeli'];
        $name_employee = $data_order['nama_pembeli'];
        $phone_employee = $data_order['phone_pembeli'];
        $this->rest->db->select('*');
        $this->rest->db->from('employee');
        $this->rest->db->where('telp', $phone);
        $id_employee_check = $this->rest->db->get()->row_array();

        $length = 15;
        $rand_string = substr(sha1(rand()), 0, $length);;
        if($id_employee_check == false)
        {
            $data_employee_new = array(
                "level" => 4, // hanya angka
                "name" => $name_employee,
                "email" => $rand_string."@mail.com",
                "passwd" => SHA1('steam123'), // default, untuk sementara   =>  Prop. D.K.I. Jakarta
                "active" => 1, // default, untuk sementara =>  Kota Jakarta Barat
                "telp" => $phone_employee, // default, untuk sementara =>  Kec. Palmerah
            );
            // insert data customer
            $query = $this->rest->db->insert('employee', $data_employee_new);
            $id_employee = $this->rest->db->insert_id();

            $data_mitra_profile = array(
                'id_employee' => $id_employee,
                'date_add' => date("Y-m-d H:i:s"),
                'date_modified' => date("Y-m-d H:i:s")
            );

            // $this->rest->db->insert('mitra_profile', $data_mitra_profile);
        }
        else
        {
            $id_employee = $id_employee_check["id_employee"];
        }

        $kode_pesanan = $data_order['kode_pesanan'];
        $this->rest->db->select('*');
        $this->rest->db->from('order_steam');
        $this->rest->db->where('reference', $kode_pesanan);
        $kode_pesanan_check = $this->rest->db->get()->row_array();

        if($kode_pesanan_check == true)
        {
            $this->response([
                'status' => 409, // not modified status
                'message' => "Data order dengan kode ". $kode_pesanan. " sudah terinput ke sistem."
            ], 409 );
            exit();
        }

        /** ORDER */
        // current_state = 1
        $reference = $kode_pesanan;
        // $id_customer = $id_customer;
        $category = "Produk STEAM";         // default, untuk sementara => Produk STEAM
        $type = "STEAM";             // default, untuk sementara => STEAM
        // $current_state = $this->input->post('');    // DEFAULT = 3
        $total_paid = $data_order['total_bayar'];
        $periode = getenv('PERIODE');
        $date = $data_order['tanggal'];

        $date_add = nice_date($date, 'Y-m-d H:i:s');
        // $korwil_email = $this->input->post('');     // null
        // $korwil_name = $this->input->post('');      // null
        // $korwil_phone = $this->input->post('');     // null
        // $rsm_name = $this->input->post('');         // null

        // current_state = 3
        $date_upd = date('Y-m-d H:i:s');
        $tgl_konfirmasi = date('Y-m-d H:i:s');
        $kesepakatan_sampai = 14;
        $jangka_waktu = 14;
        // $recommended_sales = $this->input->post(''); // null
        
        // cari cara untuk mendapatkan data user yang mengakses
        /**
        $sales_referrer = $this->input->post(''); // jika tidak ada, di isi otomatis dengan user
        $sales_name = $this->input->post(''); // jika tidak ada, di isi otomatis dengan user
        $sales_phone = $this->input->post(''); // jika tidak ada, di isi otomatis dengan user
        $kesepakatan_sampai = 14;
        $jangka_waktu = 14;
        */

        $header = array(
            'reference' => $reference,
            'id_customer' => $id_customer,
            'category' => $category,
            'type' => $type,
            'current_state' => 9,
            'sales_referer' => $rand_string,
            'sales_name' => $name_employee,
            'sales_phone' => $phone_employee,
            'total_paid' => $total_paid,
            'periode' => $periode,
            'date_add' => $date_add,
            'sts_bayar' => 2
            // 'date_upd' => $date_upd,
            // 'tgl_konfirmasi' => $tgl_konfirmasi,
            // 'kesepakatan_sampai' => $kesepakatan_sampai,
            // 'jangka_waktu' => $jangka_waktu
        );
        $query = $this->rest->db->insert('order_steam', $header);
        $id_order = $this->rest->db->insert_id();
        

        // $id_order = $this->rest->db->insert_id();
        // $kode_buku = "";
        // $product_id = ""; // get from database
        // $product_name = ""; // get from database
        // $product_quantity = "";
        // $unit_price = ""; // get from database
        // $total_price = $product_quantity * $unit_price;

        $total_paid = 0;
        $total_price = 0;
        foreach($data_order_detail as $d)
        {
            // echo "<br>".$d['kode_produk']."<br><br>";
            $get_data_product = $this->rest->db->query("select * from product where kode_buku='".$d["kode_produk"]."'")->row_array();

            if($get_data_product == false)
            {
                $this->rest->db->trans_rollback();
                $this->response([
                    'status' => 400, // bad request status
                    'message' => "Data product dengan kode  ". $d["kode_produk"]. " tidak ditemukan di buku sekolah."
                ], 400 );
                exit();
            }

            $data_detail = array(
                "id_order" => $id_order,
                "kode_buku" => $d['kode_produk'],
                "product_id" => $get_data_product['id_product'],
                "product_name" => $get_data_product['name'],
                "product_quantity" => $d['qty'],
                "unit_price" => $get_data_product['price_1'],
                "total_price" => $d['qty'] * $get_data_product['price_1']
            );
            // print_r($data_detail);
            // echo "<br>==========================================================<br><br>";
            $total_price = $d['qty'] * $get_data_product['price_1'];
            $total_paid += $total_price;

            // $this->rest->db->insert('order_detail', $data_detail);
        }

        $this->rest->db->query('update order_steam set total_paid="'.$total_paid.'" where id_order="'.$id_order.'"');

        if($this->rest->db->trans_status() == TRUE)
        {
            $this->rest->db->trans_commit();
            $this->response([
                'status' => 200,
                'message' => "Berhasil memproses data order"
            ], 200 );
        }
        else
        {
            $this->rest->db->trans_rollback();
            $this->response([
                'status' => 304, // not modified status
                'message' => "Gagal memproses data order"
            ], 304 );
        } 
        

        // /** ORDER HISTORY */
        // $order_history = array(
        //     'id_employee' => "1482", // default, sementara
        //     'id_order' => $id_order,
        //     'id_order_state' => 3,
        //     'notes' => 'Otomatis dari sistem',
        //     'date_add' => date('Y-m-d H:i:s')
        // );

        // $query = $this->rest->db->insert('order_history', $data_detail);

        /**
         * langkah - langkah menyimpan data order
         * -. cek data customer             => jika tidak ada data customer, maka create data customer
         * -. simpan data orders            => current_state = 3
         * -. simpan data order_detail
         * -. simpan data order_history     => id_order_state = 3
         */
    }
}