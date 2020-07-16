<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_persetujuan_keterangan extends CI_Migration {

        public function up()
        {
                $fields=array(
                        'persetujuan_rsm' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                                'null' => TRUE,

                        ),
                        'persetujuan_keterangan' => array(
                                'type' => 'TEXT',
                                'null' => TRUE,
                        ),
                        'persetujuan_tanggal' => array(
                                'type' => 'DATETIME',
                                'null' => TRUE,
                        ),
                );
                $this->dbforge->add_column('orders',$fields);
        }

        public function down()
        {
                $colName[] = array(
                        "persetujuan_rsm", 
                        "persetujuan_keterangan", 
                        "persetujuan_tanggal"
                );
            
                $this->dbforge->drop_column('orders', $colName);
                
                // $this->dbforge->drop_column('orders', 'persetujuan_rsm');
                // $this->dbforge->drop_column('orders', 'persetujuan_keterangan');
                // $this->dbforge->drop_column('orders', 'persetujuan_tanggal');
        }
}
