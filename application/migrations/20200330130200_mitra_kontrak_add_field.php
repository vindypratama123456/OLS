<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_mitra_kontrak_add_field extends CI_Migration {

        public function up()
        {       
                $columns = array(
                        'mikon_tanggal_akhir' => array(
                                'type' => 'DATE', 
                                'after' => 'mikon_tanggal'
                        ),
                        'active' => array(
                                'type' => 'INT', 
                                'constraint' => '2',
                                'after' => 'mikon_file'
                        )
                );
                $this->dbforge->add_column('kontrak_mitra', $columns);
        }

        public function down()
        {
                $this->dbforge->drop_column('kontrak_mitra', 'mikon_tanggal_akhir');
                $this->dbforge->drop_column('kontrak_mitra', 'active');
        }
}