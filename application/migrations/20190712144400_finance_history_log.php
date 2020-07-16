<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_finance_history_log extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(
                array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'auto_increment' => true
                        ),
                        'id_order' => array(
                                'type' => 'INT',
                                'constraint' => '10',
                        ),
                        'amount' => array(
                                'type' => 'DECIMAL',
                                'constraint' => '20,2',
                        ),
                        'pay_date' => array(
                                'type' => 'DATE',
                        ),
                        'notes' => array(
                                'type' => 'TEXT',
                        ),
                        'action_date' => array(
                                'type' => 'DATETIME',
                        ),
                        'action_by' => array(
                                'type' => 'INT',
                                'constraint' => '4',
                        ),
                )
        );

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('finance_history_log');
    }

    public function down()
    {
        $this->dbforge->drop_table('finance_history_log');
    }
}
