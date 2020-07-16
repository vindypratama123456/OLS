<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_wilayah extends CI_Migration {

        public function up()
        {
                 $this->dbforge->add_field(
                        array(
                                'wil_id' => array(
                                        'type' => 'INT',
                                        'constraint' => 2
                                ),
                                'wil_name' => array(
                                        'type' => 'VARCHAR',
                                        'constraint' => 255,
                                        'null' => TRUE,

                                ),
                                'wil_enable' => array(
                                        'type' => 'INT',
                                        'constraint' => 1
                                )
                        )
                );
                $this->dbforge->add_key('wil_id', TRUE);
                $this->dbforge->create_table('wilayah');

                $data=array(
                        array('wil_id'=>'11', 'wil_name'=>'ACEH', 'wil_enable'=>'1'),
                        array('wil_id'=>'12', 'wil_name'=>'SUMATERA UTARA','wil_enable'=>'1'),
                        array('wil_id'=>'13', 'wil_name'=>'SUMATERA BARAT','wil_enable'=>'1'),
                        array('wil_id'=>'14', 'wil_name'=>'RIAU','wil_enable'=>'1'),
                        array('wil_id'=>'15', 'wil_name'=>'JAMBI','wil_enable'=>'1'),
                        array('wil_id'=>'16', 'wil_name'=>'SUMATERA SELATAN','wil_enable'=>'1'),
                        array('wil_id'=>'17', 'wil_name'=>'BENGKULU','wil_enable'=>'1'),
                        array('wil_id'=>'18', 'wil_name'=>'LAMPUNG','wil_enable'=>'1'),
                        array('wil_id'=>'19', 'wil_name'=>'KEPULAUAN BANGKA BELITUNG','wil_enable'=>'1'),
                        array('wil_id'=>'21', 'wil_name'=>'KEPULAUAN RIAU','wil_enable'=>'1'),
                        array('wil_id'=>'31', 'wil_name'=>'DKI JAKARTA','wil_enable'=>'1'),
                        array('wil_id'=>'32', 'wil_name'=>'JAWA BARAT','wil_enable'=>'1'),
                        array('wil_id'=>'33', 'wil_name'=>'JAWA TENGAH','wil_enable'=>'1'),
                        array('wil_id'=>'34', 'wil_name'=>'DI YOGYAKARTA','wil_enable'=>'1'),
                        array('wil_id'=>'35', 'wil_name'=>'JAWA TIMUR','wil_enable'=>'1'),
                        array('wil_id'=>'36', 'wil_name'=>'BANTEN','wil_enable'=>'1'),
                        array('wil_id'=>'51', 'wil_name'=>'BALI','wil_enable'=>'1'),
                        array('wil_id'=>'52', 'wil_name'=>'NUSA TENGGARA BARAT','wil_enable'=>'1'),
                        array('wil_id'=>'53', 'wil_name'=>'NUSA TENGGARA TIMUR','wil_enable'=>'1'),
                        array('wil_id'=>'61', 'wil_name'=>'KALIMANTAN BARAT','wil_enable'=>'1'),
                        array('wil_id'=>'62', 'wil_name'=>'KALIMANTAN TENGAH','wil_enable'=>'1'),
                        array('wil_id'=>'63', 'wil_name'=>'KALIMANTAN SELATAN','wil_enable'=>'1'),
                        array('wil_id'=>'64', 'wil_name'=>'KALIMANTAN TIMUR','wil_enable'=>'1'),
                        array('wil_id'=>'65', 'wil_name'=>'KALIMANTAN UTARA','wil_enable'=>'1'),
                        array('wil_id'=>'71', 'wil_name'=>'SULAWESI UTARA','wil_enable'=>'1'),
                        array('wil_id'=>'72', 'wil_name'=>'SULAWESI TENGAH','wil_enable'=>'1'),
                        array('wil_id'=>'73', 'wil_name'=>'SULAWESI SELATAN','wil_enable'=>'1'),
                        array('wil_id'=>'74', 'wil_name'=>'SULAWESI TENGGARA','wil_enable'=>'1'),
                        array('wil_id'=>'75', 'wil_name'=>'GORONTALO','wil_enable'=>'1'),
                        array('wil_id'=>'76', 'wil_name'=>'SULAWESI BARAT','wil_enable'=>'1'),
                        array('wil_id'=>'81', 'wil_name'=>'MALUKU','wil_enable'=>'1'),
                        array('wil_id'=>'82', 'wil_name'=>'MALUKU UTARA','wil_enable'=>'1'),
                        array('wil_id'=>'91', 'wil_name'=>'PAPUA BARAT','wil_enable'=>'1'),
                        array('wil_id'=>'94', 'wil_name'=>'PAPUA','wil_enable'=>'1')
                );

                $this->db->insert_batch('wilayah', $data);
        }

        public function down()
        {
                $this->dbforge->drop_table('wilayah');
        }
}
