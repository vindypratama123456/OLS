<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_oef_transaksi_detail extends CI_Migration {

        public function up()
        {       
                $columns = array(
                        'no_oef' => array('type' => 'CHAR', 'constraint' => '8')
                );
                $this->dbforge->add_column('transaksi_detail', $columns);
        }

        public function down()
        {
                $this->dbforge->drop_column('transaksi_detail', 'no_oef');
        }
}
