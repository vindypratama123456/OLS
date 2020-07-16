<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_mitra_kontrak extends CI_Migration {

        public function up()
        {
                $fields=array(
                        'mikon_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'mikon_employee_id' => array(
                                'type' => 'INT',
                                'constraint' => 11
                        ),
                        'mikon_tanggal' => array(
                                'type' => 'DATE',
                                'null' => TRUE,
                        ),
                        'mikon_periode' => array(
                                'type' => 'CHAR',
                                'constraint' => 4,
                                'null' => TRUE,
                        ),
                        'mikon_file' => array(
                                'type' => 'TEXT',
                                'null' => TRUE,
                        ),
                        'created_by' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'null' => TRUE,
                        ),
                        'created_date' => array(
                                'type' => 'DATETIME',
                                'null' => TRUE,
                        ),
                        'updated_by' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'null' => TRUE,
                        ),
                        'updated_date' => array(
                                'type' => 'DATETIME',
                                'null' => TRUE,
                        ),
                );
                $this->dbforge->add_field($fields);
                $this->dbforge->add_key('mikon_id', TRUE);
                $this->dbforge->create_table('mitra_kontrak');
        }

        public function down()
        {
                $this->dbforge->drop_table('mitra_kontrak');
        }
}
