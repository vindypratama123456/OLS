<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_oef_history extends CI_Migration {

        public function up()
        {
                $fields = array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'id_production_order' => array(
                                'type' => 'INT'
                        ),
                        'status' => array(
                                'type' => 'INT',
                                'constraint' => '2',
                                'null' => TRUE
                        ),
                        'notes' => array(
                                'type' => 'TEXT',
                        ),
                        'created_date' => array(
                                'type' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
                        ),
                        'created_by' => array(
                                'type' => 'INT',
                                'constraint' => '10'
                        ),
                );
                $this->dbforge->add_field($fields);
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('production_order_history');
        }

        public function down()
        {
                $this->dbforge->drop_table('production_order_history');
        }
}
