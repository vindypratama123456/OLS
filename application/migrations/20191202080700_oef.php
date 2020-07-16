<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_oef extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'no_oef' => array(
                                'type' => 'CHAR',
                                'constraint' => '8'
                        ),
                        'kode_buku' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '32',
                                'null' => TRUE
                        ),
                        'judul' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255'
                        ),
                        'jumlah_request' => array(
                                'type' => 'INT',
                                'constraint' => '11'
                        ),
                        'jumlah_kirim' => array(
                                'type' => 'INT',
                                'constraint' => '11',
                                'default' => '0'
                        ),
                        'catatan_alokasi' => array(
                                'type' => 'TEXT',
                                'null' => 'TRUE'
                        ),
                        'status' => array(
                                'type' => 'INT',
                                'constraint' => '2',
                                'default' => '1',
                                'comment' => '0=Canceled; 1=Active; 2=Closed'
                        ),
                        'created_date' => array(
                                'type' => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
                        ),
                        'created_by' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '50',
                                'null' => TRUE
                        ),
                        'updated_date' => array(
                                'type' => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
                        ),
                        'updated_by' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '50',
                                'null' => TRUE
                        ),
                );
                $this->dbforge->add_field($fields);
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('production_order');

                
                $columns = array(
                        'no_oef' => array('type' => 'CHAR', 'constraint' => '8')
                );
                $this->dbforge->add_column('request_stock_detail', $columns);
        }

        public function down()
        {
                $this->dbforge->drop_table('production_order');
                $this->dbforge->drop_column('request_stock_detail', 'no_oef');
        }
}
