<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_email_blacklist extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'unsigned' => TRUE,
                                'auto_increment' => TRUE
                        ),
                        'email' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '250',
                        ),
                ));
                $this->dbforge->add_key('blog_id', TRUE);
                $this->dbforge->create_table('email_blacklist');
        }

        public function down()
        {
                $this->dbforge->drop_table('email_blacklist');
        }
}
