<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siplah extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo "halaman index";
    }

    public function check_sku($kode_buku)
    {
        // echo $kode_buku;
        // exit();

        $dbsiplah = $this->load->database('dbsiplah', true);

        $connected = $dbsiplah->initialize();
        if($connected)
        {
            echo "koneksi berhasil";
        }
        else
        {
            echo "koneksi gagal";
        }

        $check_kode_buku = $dbsiplah->query('SELECT * FROM `catalog_product_entity_int` a INNER JOIN catalog_product_entity b ON a.`entity_id`=b.`entity_id` WHERE a.attribute_id=138 and a.`value`=89 and b.sku like "'.$kode_buku.'%"');

        if($check_kode_buku->num_rows() == 0)
        {
            echo "Kode buku belum terdaftar di Siplah.";
        }
        elseif($check_kode_buku->num_rows() > 1)
        {
            $kode_buku = $check_kode_buku->result_array()[0]['sku'];
        }
        else
        {
            $kode_buku = $check_kode_buku->row_array()['sku'];
        }

        if($kode_buku == null || $kode_buku =="")
        {
            echo "Kode buku gramedia tidak ditemukan.";
        }
        else
        {
            echo $kode_buku;
        }
    }

    public function check_data_insert($kode_pesanan, $kode_buku, $qty, $update)
    {
        // echo $kode_pesanan;
        // echo $kode_buku;
        // echo $qty;
        // echo $idOrder;
        // echo $update;

        // if($update == "true")
        // {
        //     echo "true";
        // }
        // else
        // {
        //     echo "false";
        // }

        // exit();

        $dbsiplah = $this->load->database('dbsiplah', true);

        $connected = $dbsiplah->initialize();
        if($connected)
        {
            echo "koneksi berhasil";
            echo "<br><br>";
        }
        else
        {
            echo "koneksi gagal";
            echo "<br><br>";
        }

        $seller_id = '89';
        $attribute_id_name='73';
        $attribute_id_price='77';
        $key_api=getenv("SIPLAH_API_KEY");

        /**
         * MENDAPATKAN KODE BUKU
         * AWAL
         */
        $check_kode_buku = $dbsiplah->query('SELECT * FROM `catalog_product_entity_int` a INNER JOIN catalog_product_entity b ON a.`entity_id`=b.`entity_id` WHERE a.attribute_id=138 and a.`value`=89 and b.sku like "'.$kode_buku.'%"');
        
        if($check_kode_buku->num_rows() == 0)
        {
            echo "Buku belum terdaftar di siplah";
            exit();
        }
        elseif($check_kode_buku->num_rows() > 1)
        {
            $kode_buku = $check_kode_buku->result_array()[0]['sku'];
        }
        else
        {
            $kode_buku = $check_kode_buku->row_array()['sku'];
        }

        if($kode_buku == null || $kode_buku =="")
        {
            echo "kode buku tidak ditemukan di siplah.";
            exit();
        }
         
        /**
         * MENDAPATKAN CUSTOMER GROUP
         */
        $group_id = $dbsiplah->query('select group_id from `customer_entity` where entity_id=(SELECT customer_id FROM sales_order WHERE increment_id='.$kode_pesanan.')')->row_array()['group_id'];

        /**
         * MENDAPATKAN DATA PRODUK
         */
        $query_item = $dbsiplah->select('a.`entity_id`, a.`type_id`, a.`sku`, b.`value`, d.`value` AS original_price')
                    ->from('`catalog_product_entity` a')
                    ->join('`catalog_product_entity_varchar` b','b.`entity_id`=a.`entity_id`','inner')
                    ->join('`eav_attribute` c','c.`attribute_id`=b.`attribute_id`','inner')
                    ->join('`catalog_product_entity_decimal` d', 'd.`entity_id`=a.`entity_id`', 'inner')
                    ->where('a.sku', $kode_buku)
                    ->where('b.`attribute_id`', $attribute_id_name)
                    ->where('d.`attribute_id`', $attribute_id_price)
                    ->order_by('b.store_id desc')
                    ->get();

        $data_item = $query_item->result_array();

        $original_price = $data_item[0]['original_price'];

        /**
         * MENDAPATKAN HARGA ZONA
         */
        $entity_id = $data_item[0]['entity_id'];
        $query_index_price = $dbsiplah->select('*')
                            ->from('catalog_product_index_price')
                            ->where('entity_id', $entity_id)
                            ->where('customer_group_id', $group_id)
                            ->get()
                            ->row_array();

        $query_tier_price = $dbsiplah->select('*')
                            ->from('catalog_product_entity_tier_price')
                            ->where('entity_id', $entity_id)
                            ->where('customer_group_id', $group_id)
                            ->get()
                            ->row_array();

        if($query_index_price)
        {
            $price = $query_index_price['tier_price'];
        }
        elseif($query_tier_price)
        {
            $price = $query_tier_price['value'];
        }
        else
        {
            $price = $original_price;
        }

        $no_po = $kode_pesanan;
        $no_item = ''; // IF(no_item=''){echo 'insert';}elseif(no_item != ''){echo 'update';} 
        $sku = $data_item[0]['sku'];
        $product_id = $entity_id;
        $name_product = $data_item[0]['value'];


        if($update == "true")
        {
            $no_item = $this->get_no_item($no_po, $product_id, $dbsiplah);

            if($no_item == "" || $no_item == null || empty($no_item))
            {
                return false;
            }
        }

        // print_r($name_product);

        $data = array(
            'no_po'          => $no_po,
            'no_item'        => $no_item,
            'price'          => $price,
            'original_price' => $original_price,
            'sku'            => $sku,
            'product_id'     => $product_id,
            'qty'            => $qty,
            'key_api'        => $key_api,
            'seller_id'      => $seller_id,
            'nama_product'   => $name_product
        );

        print_r($data);
    }

    public function get_no_item($no_po, $product_id, $dbsiplah)
    {
        $order_id = $dbsiplah->select('entity_id')
                                ->from('sales_order')
                                ->where('increment_id', $no_po)
                                ->get()
                                ->row_array()['entity_id'];

        $item_id = $dbsiplah->select('item_id')
                                ->from('sales_order_item')
                                ->where('order_id', $order_id)
                                ->where('product_id', $product_id)
                                ->get()
                                ->row_array()['item_id'];

        return $item_id;
    }
}
