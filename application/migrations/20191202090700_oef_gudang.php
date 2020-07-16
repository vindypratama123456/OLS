<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_oef_gudang extends CI_Migration {

        public function up()
        {
                $columns = array(
                        'id_gudang' => array('type' => 'INT', 'constraint' => '2', 'unsigned' => true, 'after' => 'no_oef')
                );
                $this->dbforge->add_column('production_order', $columns);
        }

        public function down()
        {
                $this->dbforge->drop_column('production_order', 'id_gudang');
        }
}
