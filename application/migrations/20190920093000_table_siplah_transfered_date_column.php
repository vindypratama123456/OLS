<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_table_siplah_transfered_date_column extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'transfered_date' => array('type' => 'DATETIME', 'after' => 'kepala_sekolah')
                );
                $this->dbforge->add_column('orders_siplah_temp', $fields);


                $fieldsTable = array(
                        'created_date' => array(
                                'type' => 'DATETIME'
                        ),
                        'transfered_date' => array(
                                'type' => 'DATETIME'
                        ),
                        'notes_error' => array(
                                'type' =>'TEXT',
                        ),
                        'status_retransfered' => array(
                                'type' => 'enum("0","1")',
                                'default' => '0'
                        ),
                        'retransfered_date' => array(
                                'type' => 'DATETIME'
                        ),
                );
                $this->dbforge->add_field("id INT(9) AUTO_INCREMENT NOT NULL PRIMARY KEY");
                $this->dbforge->add_field("po_number INT(9) UNSIGNED ZEROFILL NOT NULL");
                $this->dbforge->add_field($fieldsTable);
                $this->dbforge->create_table('orders_siplah_error');
        }

        public function down()
        {
                $this->dbforge->drop_column('orders_siplah_temp', 'transfered_date');
                $this->dbforge->drop_table('orders_siplah_error');
        }
}
