<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Datatables $datatables
 * @property Mod_general $mod_general
 * @property Mod_kontrak $mod_kontrak
 * @property Mymail $mymail
 */
class Kontrak extends MY_Controller
{
    private $table_employee;
    private $table_kontrak;
    private $_output;
    private $path_up;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->load->model('mod_kontrak');
        $this->table_employee = 'employee';
        $this->table_kontrak = 'mitra_kontrak';
        $this->path_up = './uploads/kontrak';
        $this->_output = [];
    }

    public function index()
    {
        $this->_output['content'] = $this->load->view('admin/kontrak/list', '', true);
        $this->_output['script_js'] = $this->load->view('admin/kontrak/js', '', true);
        $this->load->view('admin/template', $this->_output);
    }

    public function listKontrak()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect(ADMIN_PATH . '/kontrak');
        }

        $this->load->library('datatables');
        $this->output->set_header('Content-Type:application/json; charset=utf-8');
        $this->datatables->select(" 
            `employee`.`id_employee` AS id_employee,
            `employee`.`code` AS code,
            `employee`.`name` AS name,
            `employee`.`email` AS email,
            `ta`.`mikon_tanggal` AS mikon_tanggal,
            `ta`.`mikon_tanggal_akhir` AS mikon_tanggal_akhir,
            `ta`.`mikon_periode` AS mikon_periode,
            IF(ISNULL(`ta`.`mikon_file`),'no_image.png',`ta`.`mikon_file`) AS mikon_file,
            IF(ISNULL(tb.`mikon_employee_id`), 'Tidak aktif', 'Aktif') AS status_kontrak
            ");

        $this->datatables->from($this->table_employee);
        $this->datatables->join("(SELECT a.* FROM mitra_kontrak a LEFT OUTER JOIN mitra_kontrak b ON a.`mikon_employee_id`=b.`mikon_employee_id` AND a.`mikon_tanggal_akhir` < b.`mikon_tanggal_akhir` WHERE b.`mikon_employee_id` IS NULL)ta", 'employee.`id_employee`=ta.mikon_employee_id', 'left');
        $this->datatables->join("(SELECT * FROM mitra_kontrak WHERE '".date('Y-m-d')."' BETWEEN mikon_tanggal AND mikon_tanggal_akhir GROUP BY mikon_employee_id)tb", 'ta.mikon_employee_id=tb.mikon_employee_id', 'left');
        $this->datatables->where('`employee`.`level`=4');
        $this->datatables->where('`employee`.`active`=1');

        $this->datatables->edit_column('code', '<a href="' . base_url(ADMIN_PATH . '/kontrak/detail/$1') . '">$1</a>', 'code, id_employee');

        // $this->datatables->edit_column('mikon_file', '<img height="80px" class="testing" src="'. base_url($this->checkTest('$1')). '"/>', 'mikon_file');

        $this->datatables->edit_column('mikon_file', '<a target="_blank "href="'.base_url($this->checkTest('$1')).'"><img height="80px" src="'. base_url($this->checkTest('$1')). '"/></a>', 'mikon_file');

        $this->output->set_output($this->datatables->generate());
    }

    public function checkTest($data)
    {
        if(strlen($data) > 0)
        {
            return '/uploads/kontrak/'.$data;
        }
        else
        {
            return '/assets/img/export_icons/test.jpg';
        }
        return gettype($data);
    }

    public function detail($id)
    {
        if ($id && is_numeric($id)) {
            $data['detil'] = $this->mod_kontrak->getDetail($id);
            if ($data['detil']) {

                $data['adm_level'] = $this->adm_level;
                $data['listkontrak'] = $this->mod_kontrak->getAll("mitra_kontrak", "*", "mikon_employee_id='".$data['detil']['id_employee']."'");
                $this->_output['content'] = $this->load->view('admin/kontrak/detail', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/kontrak/css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/kontrak/js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH . '/kontrak', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH . '/kontrak', 'refresh');
        }
    }

    public function edit($id)
    {
        if ($id && is_numeric($id)) {
            $data['detil'] = $this->mod_kontrak->getDetail($id);
            if ($data['detil']) {
                $data['adm_level'] = $this->adm_level;
                $this->_output['content'] = $this->load->view('admin/kontrak/edit', $data, true);
                $this->_output['script_css'] = $this->load->view('admin/kontrak/css', '', true);
                $this->_output['script_js'] = $this->load->view('admin/kontrak/js', '', true);
                $this->load->view('admin/template', $this->_output);
            } else {
                redirect(ADMIN_PATH . '/kontrak', 'refresh');
            }
        } else {
            redirect(ADMIN_PATH . '/kontrak', 'refresh');
        }
    }

    public function kontrak_popup($id, $code)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $data['mikon_id'] = $id;
        $data['code'] = $code;
        $data['adm_level'] = $this->adm_level;
        $data['detil'] = $this->mod_general->detailData("mitra_kontrak", "mikon_id", $id);
        $this->load->view('admin/kontrak/kontrak_popup', $data);
    }

    public function kontrak_popup_post()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $config['upload_path']=$this->path_up;
        $config['allowed_types']='gif|jpg|png|pdf';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload',$config);

        $id = $this->input->post('mikon_id');
        $code = $this->input->post('code');

        $mikon_no_kontrak = $this->input->post('mikon_no_kontrak');
        $mikon_tempat_kontrak = $this->input->post('mikon_tempat_kontrak');

        $mikon_tanggal = $this->input->post('mikon_tanggal');
        $mikon_tanggal_akhir = $this->input->post('mikon_tanggal_akhir');
        $mikon_periode = trim($this->input->post('mikon_periode'));
        $active = $this->input->post('active');

        if(!empty($_FILES['mikon_file']['name'])){
            if($this->upload->do_upload("mikon_file"))
            {
                $data = array('upload_data' => $this->upload->data());
                $dataKontrakMitra = [
                    'mikon_no_kontrak' => $mikon_no_kontrak,
                    'mikon_tempat_kontrak' => $mikon_tempat_kontrak,
                    'mikon_tanggal' => $mikon_tanggal,
                    'mikon_tanggal_akhir' => $mikon_tanggal_akhir,
                    // 'mikon_periode' => $mikon_periode,
                    'mikon_file' => $data['upload_data']['file_name'],
                    'active' => $active,
                    'updated_by' => $this->adm_id,
                    'updated_date' => date("Y-m-d H:i:s")
                ];

                // update data kontrak ke tabel mitra_kontrak
                $query_add= $this->mod_general->updateData($this->table_kontrak,$dataKontrakMitra,'mikon_id', $id);
                if($query_add)
                {
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Update data successed query_add : '. $query_add,
                        'redirect' => 'kontrak/detail/'.$code
                    ];
                }
                else
                {
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Update data failed query_add : ' . $query_add,
                        'redirect' => 'kontrak/detail/'.$code
                    ];
                }
                echo json_encode($callBack, true);
            }
            else
            {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Upload data failed. Please check data type.'
                ];
                echo json_encode($callBack, true);
            }
        }
        else
        {
            $dataKontrakMitra = [
                'mikon_no_kontrak' => $mikon_no_kontrak,
                'mikon_tempat_kontrak' => $mikon_tempat_kontrak,
                'mikon_tanggal' => $mikon_tanggal,
                'mikon_tanggal_akhir' => $mikon_tanggal_akhir,
                // 'mikon_periode' => $mikon_periode,
                'active' => $active,
                'updated_by' => $this->adm_id,
                'updated_date' => date("Y-m-d H:i:s")
            ];

            // Update data kontrak ke tabel mitra_kontrak
            $result= $this->mod_general->updateData($this->table_kontrak,$dataKontrakMitra,'mikon_id', $id);
            if($result)
            {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Update data successed result : '.$result,
                    'redirect' => 'kontrak/detail/'.$code
                ];
            }
            else
            {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Update data failed result : '.$result,
                    'redirect' => 'kontrak/detail/'.$code
                ];
            }
            echo json_encode($callBack, true);
        }
    }

    public function kontrak_popup_add($code)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $employee = $this->mod_general->detailData('employee', 'code', $code);
        $data['id_employee'] = $employee['id_employee'];
        $data['code'] = $code;
        // $data['adm_level'] = $this->adm_level;
        // $data['detil'] = $this->mod_general->detailData("mitra_kontrak", "mikon_id", $id);
        $this->load->view('admin/kontrak/kontrak_popup_add', $data);
    }

    public function kontrak_popup_add_post()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $config['upload_path']=$this->path_up;
        $config['allowed_types']='gif|jpg|png|pdf';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload',$config);

        $code = $this->input->post('code'); 

        $mikon_employee_id = $this->input->post('id_employee');

        $mikon_no_kontrak = $this->input->post('mikon_no_kontrak');
        $mikon_tempat_kontrak = $this->input->post('mikon_tempat_kontrak');

        $mikon_tanggal = $this->input->post('mikon_tanggal');
        $mikon_tanggal_akhir = $this->input->post('mikon_tanggal_akhir');
        $mikon_periode = trim($this->input->post('mikon_periode'));
        $active = $this->input->post('active');

        if(!empty($_FILES['mikon_file']['name'])){
            if($this->upload->do_upload("mikon_file"))
            {
                $data = array('upload_data' => $this->upload->data());

                $dataKontrakMitra = [
                    'mikon_id' => '',
                    'mikon_employee_id' =>  $mikon_employee_id,
                    'mikon_no_kontrak' => $mikon_no_kontrak,
                    'mikon_tempat_kontrak' => $mikon_tempat_kontrak,
                    'mikon_tanggal' => $mikon_tanggal,
                    'mikon_tanggal_akhir' => $mikon_tanggal_akhir,
                    // 'mikon_periode' => trim($this->input->post('mikon_periode', true)),
                    'mikon_file' => $data['upload_data']['file_name'],
                    'created_by' => $this->adm_id,
                    'created_date' => date("Y-m-d H:i:s"),
                    'updated_by' => $this->adm_id,
                    'updated_date' => date("Y-m-d H:i:s")
                ];

                // Insert data kontrak ke tabel mitra_kontrak
                $query_add= $this->mod_kontrak->add($this->table_kontrak,$dataKontrakMitra);
                if($query_add)
                {
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Update data successed query_add : '. $query_add, 
                        'redirect' => 'kontrak/detail/'.$code
                    ];
                }
                else
                {
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Update data failed query_add : ' . $query_add, 
                        'redirect' => 'kontrak/detail/'.$code
                    ];
                }
                echo json_encode($callBack, true);
            }
            else
            {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Upload data failed. Please check data type.', 
                    'redirect' => 'kontrak/detail/'.$code
                ];
                echo json_encode($callBack, true);
            }
        }
        else
        {
            $dataKontrakMitra = [
                'mikon_id' => '',
                'mikon_employee_id' =>  $mikon_employee_id,
                'mikon_no_kontrak' => $mikon_no_kontrak,
                'mikon_tempat_kontrak' => $mikon_tempat_kontrak,
                'mikon_tanggal' => $mikon_tanggal,
                'mikon_tanggal_akhir' => $mikon_tanggal_akhir,
                // 'mikon_periode' => trim($this->input->post('mikon_periode', true)),
                'created_by' => $this->adm_id,
                'created_date' => date("Y-m-d H:i:s"),
                'updated_by' => $this->adm_id,
                'updated_date' => date("Y-m-d H:i:s")
            ];

            // Insert data kontrak ke tabel mitra_kontrak
            $result= $this->mod_kontrak->add($this->table_kontrak,$dataKontrakMitra);
            if($result)
            {
                $callBack = [
                    'success' => 'true',
                    'message' => 'Update data successed result : '.$result, 
                    'redirect' => 'kontrak/detail/'.$code
                ];
            }
            else
            {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Update data failed result : '.$result, 
                    'redirect' => 'kontrak/detail/'.$code
                ];
            }
            echo json_encode($callBack, true);
        }
    }

    public function updatePost()
    {
        if ( ! $this->input->is_ajax_request()) 
        {
            redirect(ADMIN_PATH . '/kontrak');
        }

        try 
        {
            $config['upload_path']=$this->path_up;
            $config['allowed_types']='gif|jpg|png|pdf';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload',$config);
            
            $query_check = $this->mod_kontrak->getlist($this->table_kontrak,"","mikon_employee_id = '". $this->input->post('mikon_employee_id') . "'");
            if( $query_check < 1)
            {   
                // jika tidak ada data kontrak
                // Upload dokumen kontrak
                if(!empty($_FILES['mikon_file']['name'])){
                    if($this->upload->do_upload("mikon_file"))
                    {
                        $data = array('upload_data' => $this->upload->data());

                        $dataKontrakMitra = [
                            'mikon_id' => '',
                            'mikon_employee_id' =>  $this->input->post('mikon_employee_id'),
                            'mikon_tanggal' => date("Y-m-d H:i:s", strtotime($this->input->post('mikon_tanggal'))),
                            'mikon_periode' => trim($this->input->post('mikon_periode', true)),
                            'mikon_file' => $data['upload_data']['file_name'],
                            'created_by' => $this->adm_id,
                            'created_date' => date("Y-m-d H:i:s"),
                            'updated_by' => $this->adm_id,
                            'updated_date' => date("Y-m-d H:i:s")
                        ];
                        // Insert data kontrak ke tabel mitra_kontrak
                        $query_add= $this->mod_kontrak->add($this->table_kontrak,$dataKontrakMitra);
                        if($query_add)
                        {
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Update data successed query_add : '. $query_add 
                            ];
                        }
                        else
                        {
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Update data failed query_add : ' . $query_add
                            ];
                        }
                        echo json_encode($callBack, true);
                    }
                    else
                    {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Upload data failed. Please check data type.'
                        ];
                        echo json_encode($callBack, true);
                    }
                }
                else
                {
                    $dataKontrakMitra = [
                        'mikon_id' => '',
                        'mikon_employee_id' =>  $this->input->post('mikon_employee_id'),
                        'mikon_tanggal' => date("Y-m-d H:i:s", strtotime($this->input->post('mikon_tanggal'))),
                        'mikon_periode' => trim($this->input->post('mikon_periode', true)),
                        'created_by' => $this->adm_id,
                        'created_date' => date("Y-m-d H:i:s"),
                        'updated_by' => $this->adm_id,
                        'updated_date' => date("Y-m-d H:i:s")
                    ];
                    // Insert data kontrak ke tabel mitra_kontrak
                    $result= $this->mod_kontrak->add($this->table_kontrak,$dataKontrakMitra);
                    if($result)
                    {
                        $callBack = [
                            'success' => 'true',
                            'message' => 'Update data successed result : '.$result
                        ];
                    }
                    else
                    {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Update data failed result : '.$result
                        ];
                    }
                    echo json_encode($callBack, true);
                }
            }
            else
            {   
                // jika ada data kontrak
                $mikon_employee_id = $this->input->post('mikon_employee_id');

                $dataKontrakMitra = [
                    'mikon_tanggal' => date("Y-m-d H:i:s", strtotime($this->input->post('mikon_tanggal'))),
                    'mikon_periode' => trim($this->input->post('mikon_periode', true)),
                    'updated_by' => $this->adm_id,
                    'updated_date' => date("Y-m-d H:i:s")
                ];

                // update data pada tabel mitra_kontrak
                $update = $this->mod_kontrak->updateKontrak($this->table_kontrak, 'mikon_employee_id = '.$mikon_employee_id, $dataKontrakMitra);

                if($update)
                {

                    $mikon_file_temp = $this->input->post('mikon_file_temp', true);

                    if( ! empty($_FILES['mikon_file']['name']))
                        {   // Jika ada data pada form mikon_file, hapus data sebelumnya kemudian upload data yang baru

                        // Delete dokumen
                        // add method here
                            if($mikon_file_temp != "")
                            {
                                $file = $this->path_up . "/" . $mikon_file_temp;
                                if (is_readable($file) && unlink($file)) 
                                {
                                // echo "The file has been deleted";
                                // $callBack = [
                                //     'success' => 'true',
                                //     'message' => 'The file has been deleted'
                                // ];
                                // echo json_encode($callBack, true);
                                }
                                else
                                {
                                // echo "The file was not found or not readable and could not be deleted : " . $file;
                                // $callBack = [
                                //     'success' => 'true',
                                //     'message' => 'The file was not found or not readable and could not be deleted'
                                // ];
                                // echo json_encode($callBack, true);
                                }
                            }
                        // end methode


                        // Upload dokumen
                            if($this->upload->do_upload("mikon_file"))
                            {
                                $data = array('upload_data' => $this->upload->data());

                                $dataDocumentMitra = [
                                    'mikon_file' => $data['upload_data']['file_name']
                                ];

                            // Update data kontrak ke tabel mitra_kontrak
                                $result_update= $this->mod_kontrak->updateKontrak($this->table_kontrak, 'mikon_employee_id = '.$mikon_employee_id, $dataDocumentMitra);
                                if($result_update)
                                {
                                    $callBack = [
                                        'success' => 'true',
                                        'message' => 'Update data successed'
                                    ];
                                    echo json_encode($callBack, true);
                                // redirect('/kontak', 'refresh');
                                }
                                else
                                {
                                    $callBack = [
                                        'success' => 'false',
                                        'message' => 'Update data failed'
                                    ];
                                    echo json_encode($callBack, true);
                                }
                            }
                            else
                            {
                                $error = array('error' => $this->upload->display_errors());
                                $this->session->set_flashdata('error',$error['error']); 
                                $callBack = [
                                    'success' => 'false',
                                    'message' => 'Upload data failed. Please check data type.'
                                ];
                                echo json_encode($callBack, true);
                            // redirect('kontrak','refresh');
                            }
                        }
                        else
                        {
                            $callBack = [
                                'success' => 'true',
                                'message' => 'Dont make changes'
                            ];
                            echo json_encode($callBack, true);
                        }
                    }
                    else
                    {
                        $callBack = [
                            'success' => 'false',
                            'message' => 'Update data failed : '.$update
                        ];
                        echo json_encode($callBack, true);
                    }
                }
            } catch (Exception $e) {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Caught exception: '
                ];
                echo json_encode($callBack, true);
            }


        }
    }
    ?>
