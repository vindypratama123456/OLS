<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_table_siplah extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field("po_number INT(9) UNSIGNED ZEROFILL NOT NULL");
                $this->dbforge->add_field(array(
                        'created_at' => array(
                                'type' => 'DATETIME',
                                'null' => true
                        ),
                        'updated_at' => array(
                                'type' => 'DATETIME',
                                'null' => true
                        ),
                        'status_id_siplah' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => true
                        ),
                        'order_status' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => true
                        ),
                        'product_id_siplah' => array(
                                'type' => 'INT',
                                'constraint' => '9',
                                'null' => true
                        ),
                        'sku' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '32',
                                'null' => true
                        ),
                        'nama_produk' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                                'null' => true
                        ),
                        'qty' => array(
                                'type' => 'DECIMAL',
                                'constraint' => '20,2',
                                'null' => true
                        ),
                        'harga_katalog' => array(
                                'type' => 'DECIMAL',
                                'constraint' => '20,2',
                                'null' => true
                        ),
                        'harga_ongkir' => array(
                                'type' => 'DECIMAL',
                                'constraint' => '20,2',
                                'null' => true
                        ),
                        'harga_satuan_dengan_ongkir' => array(
                                'type' => 'DECIMAL',
                                'constraint' => '20,2',
                                'null' => true
                        ),
                        'harga_satuan_dengan_pajak' => array(
                                'type' => 'DECIMAL',
                                'constraint' => '20,2',
                                'null' => true
                        ),
                        'harga_sampai_disekolah' => array(
                                'type' => 'DECIMAL',
                                'constraint' => '20,2',
                                'null' => true
                        ),
                        'store_name' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                                'null' => true
                        ),
                        'nama_sekolah' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                                'null' => true
                        ),
                        'npsn' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '50',
                                'null' => true
                        ),
                        'alamat_lengkap_sekolah' => array(
                                'type' => 'TINYTEXT',
                                'null' => true
                        ),
                        'desa' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => true
                        ),
                        'kec' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => true
                        ),
                        'kab' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => true
                        ),
                        'prov' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '100',
                                'null' => true
                        ),
                        'no_telepon' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '25',
                                'null' => true
                        ),
                        'bendahara_bos' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                                'null' => true
                        ),
                        'kepala_sekolah' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                                'null' => true
                        ),
                ));
                $this->dbforge->create_table('orders_siplah_temp');
        }

        public function down()
        {
                $this->dbforge->drop_table('orders_siplah_temp');
        }
}
