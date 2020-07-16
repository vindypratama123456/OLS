<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_oef extends CI_Migration {

        public function up()
        {
                
                $order_columns = array(
                        'kirim_parsial_request_by_id' => array(
                                'type' => 'INT', 
                                'constraint' => '11'
                        ),
                        'kirim_parsial_request_by_name' => array(
                                'type' => 'VARCHAR', 
                                'constraint' => '100'
                        ),
                        'kirim_parsial_request_date' => array(
                                'type' => 'DATETIME',
                        ),
                        'kirim_parsial_accept_by_id' => array(
                                'type' => 'INT', 
                                'constraint' => '11'
                        ),
                        'kirim_parsial_accept_by_name' => array(
                                'type' => 'VARCHAR', 
                                'constraint' => '100'
                        ),
                        'kirim_parsial_accept_date' => array(
                                'type' => 'DATETIME'
                        )
                );
                $this->dbforge->add_column('orders', $order_columns);

                $transaksi_columns = array(
                        'file_bast' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '150'
                        )
                );
                $this->dbforge->add_column('transaksi', $transaksi_columns);
        }

        public function down()
        {
                $order_columns = array(
                        'kirim_parsial_request_by_id',
                        'kirim_parsial_request_by_name',
                        'kirim_parsial_request_date',
                        'kirim_parsial_accept_by_id',
                        'kirim_parsial_accept_by_name',
                        'kirim_parsial_accept_date'
                );
                $this->dbforge->drop_column('orders', $order_columns);

                $this->dbforge->drop_column('orders', 'file_bast');
        }
}
