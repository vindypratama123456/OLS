<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_mitra $mod_mitra
 * @property Mod_general $mod_general
 * @property Mod_ec $mod_ec
 * @property ReCaptcha $recaptcha
 * @property BotDetectCaptcha $botdetectcaptcha
 * @property Mymail $mymail
 */
class Ecregistrasi extends CI_Controller
{
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('recaptcha');
        $this->load->model('mod_mitra');
        $this->load->model('mod_general');
        $this->load->model('mod_ec');
        $this->load->helper('form');
        $this->table = 'mitra_profile';
    }

    public function index()
    {
        $captchaConfig = [
            'CaptchaId' => 'RegisterEcCaptcha',
            'UserInputId' => 'CaptchaCode',
        ];
        $this->load->library('botdetect/BotDetectCaptcha', $captchaConfig);
        $data['page_title'] = 'Registrasi EC - Buku Sekolah Gramedia';
        $data['captchaHtml'] = $this->botdetectcaptcha->Html();
        $data['kabupaten'] = $this->mod_general->getAll("master_kabupaten_zona","kabupaten");
        $data['mitra'] = $this->mod_general->getAll("employee", "*", "level=4 AND active=1", "name");
        /**
         * Kata kunci : korwil
         * Vindy 2019-06-27
         * Korwil / Kode Wilayah
         * Mungkin dibutuhkan untuk kedepannya
         * Awal
         * $data['wilayah'] = $this->mod_general->getAll("wilayah");
         * Akhir
         */
        $this->load->view('ec/form_registrasi', $data);
    }

    public function add()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $captchaConfig = [
            'CaptchaId' => 'RegisterEcCaptcha',
            'UserInputId' => 'CaptchaCode',
        ];
        $this->load->library('botdetect/BotDetectCaptcha', $captchaConfig);
        $code = $this->input->post('CaptchaCode');
        $isHuman = $this->botdetectcaptcha->Validate($code);
        $kabupaten = array();
        $mitra = array();
        if ($isHuman) {
            $id = '';
            $level = '3';
            /**
             * Kata kunci : korwil
             * Vindy 2019-06-27
             * Korwil / Kode Wilayah
             * Mungkin dibutuhkan untuk kedepannya
             * Awal
             * $code = $this->input->post('wilayah');
             * Akhir
             */
            
            $name = trim($this->input->post('name', true));
            $email = strtolower(trim($this->input->post('email', true)));
            $password = sha1($this->input->post('password', true));
            $active = 0;
            $telp = trim($this->input->post('telp', true));
            $kabupaten = $this->input->post('kabupaten', true);
            $mitra = $this->input->post('mitra', true);
            $error = [];

            if ($this->mod_mitra->checkExist('employee', 'name', $name) > 0) {
                $error[] = "Nama <strong>'".$name."'</strong> sudah ada, silahkan masukkan nama yang lain!";
            }
            if ($this->mod_mitra->checkExist('employee', 'email', $email) > 0) {
                $error[] = "Email <strong>'".$email."'</strong> sudah ada, silahkan masukkan email yang lain!";
            }
            if (count($error) > 0) {
                $this->session->set_flashdata('alert_error', $error);
                $this->session->set_flashdata('error_count', count($error));
                $callBack = [
                    'success' => false,
                    'dupplicate' => true,
                    'message' => $error,
                    'csrf_token' => $this->security->get_csrf_hash(),
                ];
                ajaxResponse(400, $callBack);
                exit();
            }
            
            /**
             * 1. Simpan data EC ke tabel employee
             * 2. Simpan data Kabupaten ke tabel employe_kabupaten_kota
             * 3. update code_referral pada tabel mitra_profile
             *    - Dapatkan kode mitra lama dalam bentuk array
             *    - Dapatkan data referral dari kode mitra lama
             *    - Dapatkan kode mitra baru
             *    - Update code_referral dengan code mitra baru
             *    - Update / Insert data pada tabel korwil_sales
             * 4. Update code mitra pada tabel employee
             * 5. update code_korwil dan code_mitra pada tabel mitra_profile
             */

            // get kode EC where not used from 1 to 99 order ascending
            $codeTemp = $this->mod_ec->getCodeEC();
            $codeEC = $codeTemp->Number;

            // Check len digit code EC, must 2 digits
            if(strlen($codeEC) == 1)
            {
                $codeEC = "0".$codeEC;
            }

            $this->db->trans_begin();
            
            // Simpan data EC ke tabel employee
            $dataEmployee = [
                // 'code' => $code,
                'code' => $codeEC,
                'level' => $level,
                'name' => $name,
                'email' => $email,
                'passwd' => $password,
                'telp' => $telp,
                'active' => 1,
            ];
            $idEmployee = $this->mod_mitra->add('employee', $dataEmployee);

            $dataKabupaten = array();
            for($i=0; $i < count($kabupaten);$i++)
            {
                $dataKabupaten[] = array(
                    "id_employee" => $idEmployee,
                    "kabupaten_kota" => $kabupaten[$i],
                    "status" => 1
                );

                // $kabupatenArray[] = $kabupaten[$i][1];
            }

            // select id_employee berdasarkan kabupaten dan level 3
            $id_employee_old = $this->mod_ec->getEcByKabupaten($kabupaten);
            $id_employee_old = $id_employee_old[0]->id_employee;

            // delete data kabupaten berdasarkan kabupaten dan id_employee lama
            $query = $this->mod_ec->ec_del($kabupaten, $id_employee_old);
            
            // tambahkan kabupaten baru untuk id_employee baru
            // Simpan data Kabupaten ke tabel employe_kabupaten_kota
            $kabupatenAdd = $this->mod_general->addDataBatch('employee_kabupaten_kota', $dataKabupaten);

            // declare array dataMitra
            $dataMitraArray = array();
            $codeMitraNewArray = array();

            //default code mitra
            $codeMitraNew=$codeEC."000";
            $id_employee_mitra_temp = array();
            for($i=0; $i < count($mitra);$i++)
            {
                $codeMitraNew = $codeMitraNew + 1;
                if(strlen($codeMitraNew) <= 4)
                {
                    $codeMitraNew="0".$codeMitraNew;
                }
                $dataMitraArray[] = array(
                    "id_employee" => $mitra[$i],
                    "code" => $codeMitraNew
                );

                $codeMitraNewArray[$mitra[$i]]=$codeMitraNew;

                $id_employee_mitra_temp[] = $mitra[$i];

                $dataMitraProfile[] = array(
                    "id_employee" => $mitra[$i],
                    "code_korwil" => $codeEC,
                    "code_mitra" => $codeMitraNew
                );
            }


            /**
             * - Dapatkan kode mitra lama dalam bentuk array
             * - Dapatkan data referral dari kode mitra lama
             * - Dapatkan kode mitra baru
             * - Update code_referral dengan code mitra baru
             */

            // get code mitra lama
            $codeMitraOld = array();
            // $dataMitra = $this->mod_general->getCodeMitraByIdEmployee($id_employee_mitra_temp);

            // // generate array code_mitra
            // foreach($dataMitra as $data)
            // {
            //     $codeMitraOld[] = $data->code;
            //     //select berdasarkan email_sales pada tabel korwil_sales
            //     $checkKorwilSales = $this->mod_general->detailData("korwil_sales", "email_sales", $data->email);
            //     //jika ada data, update email_korwil
            //     //jika tidak ada data, insert data
            // }
            
            $dataMitra = $this->mod_ec->getCodeMitraByIdEmployee($id_employee_mitra_temp);

            $tableKorwilSales = "korwil_sales";
            foreach($dataMitra as $data)
            {

                // generate array code_mitra
                $codeMitraOld[] = $data->code;

                //select berdasarkan email_sales pada tabel korwil_sales
                $checkKorwilSales = $this->mod_general->detailData($tableKorwilSales, "email_sales", $data->email);

                IF($checkKorwilSales)
                {
                    // jika ada data, update email_korwil
                    $email_korwil = array(
                        "email_korwil" => $email
                    );
                    $this->mod_general->updateData($tableKorwilSales, $email_korwil, "email_sales", $data->email);
                }
                else
                {
                    // jika tidak ada data, insert data
                    $data_korwil_sales = array(
                        "email_sales" => $data->email,
                        "email_korwil" => $email
                    );
                    $this->mod_general->addData($tableKorwilSales,$data_korwil_sales);
                }
            }

            // get data referral
            $dataReferral = $this->mod_ec->getDataReferral($codeMitraOld);

            // Update code_referral dengan code mitra baru pada table mitra_profile
            foreach($dataReferral as $dr)
            {
                $updateReferral = $this->mod_ec->updateDataBatch3($dr->id_employee, $codeMitraNewArray[$dr->id_employee]);
            }

            // update code mitra pada table employee
            $mitraUpdate = $this->mod_ec->updateDataBatch('employee', $dataMitraArray, 'id_employee');

            // update code_korwil dan code_mitra pada table mitra_profile
            $mitra_profile_update = $this->mod_ec->updateDataBatch('mitra_profile', $dataMitraProfile, 'id_employee');

            if ($this->db->trans_status() === true) {
                // if ($idMitra) {
                //     $this->load->library('mymail');
                //     $korwilSubject = "Pendaftaran Mitra Baru - Buku Sekolah Gramedia";
                //     $korwilTo = array($korwilData->email);
                //     $korwilContent = "<p>Telah mendaftar sebagai mitra dengan detil data:<br><br></p>
                //                 <p>
                //                 - Email         : ".$email." <br>
                //                 - Nama          : ".$name." <br>
                //                 - Alamat        : ".$address." <br>
                //                 - No. Telpon/HP : ".$phone." <br><br>
                //                 </p>
                //                 <p>Mohon untuk segera dilakukan konfirmasi dan verifikasi.<br><br>Terima kasih</p>";
                //     $this->mymail->send($korwilSubject, $korwilTo, $korwilContent);
                //     $mitraSubject = "Konfirmasi Registrasi Mitra";
                //     $mitraTo = $email;
                //     $mitraContent = "<p>
                //                         Terima kasih telah mendaftar sebagai mitra.<br>
                //                         Data anda akan segera diverifikasi dan dikonfirmasi oleh tim kami.<br>
                //                         Mohon menunggu konfirmasi dari koordinator wilayah yang anda pilih.<br><br>
                //                         Salam Pendidikan,
                //                       </p>";
                //     $this->mymail->send($mitraSubject, $mitraTo, $mitraContent);
                // }
                $this->db->trans_commit();
                $callBack = [
                    'success' => true,
                    'message' => '<i class="glyphicon glyphicon-ok-circle"></i> &nbsp; Selamat, registrasi mitra berhasil.',
                ];
                ajaxResponse(201, $callBack);
            } else {
                $this->db->trans_rollback();
                $callBack = [
                    'success' => false,
                    'message' => 'Registrasi gagal!',
                    'csrf_token' => $this->security->get_csrf_hash(),
                ];
                ajaxResponse(400, $callBack);
            }
        } else {
            $callBack = [
                'message' => 'Captcha salah!',
                'success' => false,
                'csrf_token' => $this->security->get_csrf_hash(),
            ];
            ajaxResponse(400, $callBack);
        }
    }

    public function ecpindah()
    {
        $captchaConfig = [
            'CaptchaId' => 'RegisterEcCaptcha',
            'UserInputId' => 'CaptchaCode',
        ];
        $this->load->library('botdetect/BotDetectCaptcha', $captchaConfig);
        $data['page_title'] = 'Perpindahan EC - Buku Sekolah Gramedia';
        $data['captchaHtml'] = $this->botdetectcaptcha->Html();
        $data['kabupaten'] = $this->mod_general->getAll("master_kabupaten_zona","kabupaten");
        $data['mitra'] = $this->mod_general->getAll("employee", "*", "level=4 AND active=1", "name");
        /**
         * Kata kunci : korwil
         * Vindy 2019-06-27
         * Korwil / Kode Wilayah
         * Mungkin dibutuhkan untuk kedepannya
         * Awal
         * $data['wilayah'] = $this->mod_general->getAll("wilayah");
         * Akhir
         */
        $this->load->view('ec/form_pindah', $data);
    }

    public function ecpindahproses()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $captchaConfig = [
            'CaptchaId' => 'RegisterEcCaptcha',
            'UserInputId' => 'CaptchaCode',
        ];
        $this->load->library('botdetect/BotDetectCaptcha', $captchaConfig);
        $code = $this->input->post('CaptchaCode');
        $isHuman = $this->botdetectcaptcha->Validate($code);
        $kabupaten = array();
        $mitra = array();
        if ($isHuman) {

            $this->db->trans_begin();

            $id = '';
            $level = '3';  

            $name = trim($this->input->post('name', true));
            $email = strtolower(trim($this->input->post('email', true)));
            $active = 0;
            $telp = trim($this->input->post('telp', true));
            $kabupaten = $this->input->post('kabupaten', true);
            $mitra = $this->input->post('mitra', true); 

            $dataEC=$this->db->query("select * from employee where `email`='" . $email . "'")->result()[0]; // get id employee by email
            $idEmployee=$dataEC->id_employee;
            $codeEC=$dataEC->code;

            /**
             | KABUPATEN
             */
            if($kabupaten)
            {
                $dataKabupaten = array();
                for($i=0; $i < count($kabupaten);$i++)
                {
                    $dataKabupaten[] = array(
                        "id_employee" => $idEmployee,
                        "kabupaten_kota" => $kabupaten[$i],
                        "status" => 1
                    );

                    // $kabupatenArray[] = $kabupaten[$i][1];
                }

                // select id_employee berdasarkan kabupaten dan level 3
                $id_employee_old = $this->mod_ec->getEcByKabupaten($kabupaten);
                $id_employee_old = $id_employee_old[0]->id_employee;

                // delete data kabupaten berdasarkan kabupaten dan id_employee lama
                $query = $this->mod_ec->ec_del($kabupaten, $id_employee_old);
                
                // tambahkan kabupaten baru untuk id_employee baru
                // Simpan data Kabupaten ke tabel employe_kabupaten_kota
                $kabupatenAdd = $this->mod_general->addDataBatch('employee_kabupaten_kota', $dataKabupaten);
            }

            /**
             | MITRA 
             */
            if($mitra)
            {
                // declare array dataMitra
                $dataMitraArray = array();
                $codeMitraNewArray = array();

                $codeMitraLast = $this->db->query("select b.`code_mitra` from employee a join mitra_profile b on a.`code`=b.`code_korwil` where a.`email`='" . $email . "' order by b.`code_mitra` desc limit 1")->result()[0]->code_mitra;
                //default code mitra
                // $codeMitraNew=$codeEC."000";
                $codeMitraNew=$codeMitraLast;
                $id_employee_mitra_temp = array();
                for($i=0; $i < count($mitra);$i++)
                {
                    $codeMitraNew = $codeMitraNew + 1;
                    if(strlen($codeMitraNew) <= 4)
                    {
                        $codeMitraNew="0".$codeMitraNew;
                    }
                    $dataMitraArray[] = array(
                        "id_employee" => $mitra[$i],
                        "code" => $codeMitraNew
                    );

                    $codeMitraNewArray[$mitra[$i]]=$codeMitraNew;

                    $id_employee_mitra_temp[] = $mitra[$i];

                    $dataMitraProfile[] = array(
                        "id_employee" => $mitra[$i],
                        "code_korwil" => $codeEC,
                        "code_mitra" => $codeMitraNew
                    );
                }


                /**
                 * - Dapatkan kode mitra lama dalam bentuk array
                 * - Dapatkan data referral dari kode mitra lama
                 * - Dapatkan kode mitra baru
                 * - Update code_referral dengan code mitra baru
                 */

                // get code mitra lama
                $codeMitraOld = array();
                // $dataMitra = $this->mod_general->getCodeMitraByIdEmployee($id_employee_mitra_temp);

                // // generate array code_mitra
                // foreach($dataMitra as $data)
                // {
                //     $codeMitraOld[] = $data->code;
                //     //select berdasarkan email_sales pada tabel korwil_sales
                //     $checkKorwilSales = $this->mod_general->detailData("korwil_sales", "email_sales", $data->email);
                //     //jika ada data, update email_korwil
                //     //jika tidak ada data, insert data
                // }
                
                $dataMitra = $this->mod_ec->getCodeMitraByIdEmployee($id_employee_mitra_temp);

                $tableKorwilSales = "korwil_sales";
                foreach($dataMitra as $data)
                {

                    // generate array code_mitra
                    $codeMitraOld[] = $data->code;

                    //select berdasarkan email_sales pada tabel korwil_sales
                    $checkKorwilSales = $this->mod_general->detailData($tableKorwilSales, "email_sales", $data->email);

                    IF($checkKorwilSales)
                    {
                        // jika ada data, update email_korwil
                        $email_korwil = array(
                            "email_korwil" => $email
                        );
                        $this->mod_general->updateData($tableKorwilSales, $email_korwil, "email_sales", $data->email);
                    }
                    else
                    {
                        // jika tidak ada data, insert data
                        $data_korwil_sales = array(
                            "email_sales" => $data->email,
                            "email_korwil" => $email
                        );
                        $this->mod_general->addData($tableKorwilSales,$data_korwil_sales);
                    }
                }

                // get data referral
                $dataReferral = $this->mod_ec->getDataReferral($codeMitraOld);

                // Update code_referral dengan code mitra baru pada table mitra_profile
                foreach($dataReferral as $dr)
                {
                    $updateReferral = $this->mod_ec->updateDataBatch3($dr->id_employee, $codeMitraNewArray[$dr->id_employee]);
                }

                // update code mitra pada table employee
                $mitraUpdate = $this->mod_ec->updateDataBatch('employee', $dataMitraArray, 'id_employee');

                // update code_korwil dan code_mitra pada table mitra_profile
                $mitra_profile_update = $this->mod_ec->updateDataBatch('mitra_profile', $dataMitraProfile, 'id_employee');
            }

            if ($this->db->trans_status() === true) {
                // if ($idMitra) {
                //     $this->load->library('mymail');
                //     $korwilSubject = "Pendaftaran Mitra Baru - Buku Sekolah Gramedia";
                //     $korwilTo = array($korwilData->email);
                //     $korwilContent = "<p>Telah mendaftar sebagai mitra dengan detil data:<br><br></p>
                //                 <p>
                //                 - Email         : ".$email." <br>
                //                 - Nama          : ".$name." <br>
                //                 - Alamat        : ".$address." <br>
                //                 - No. Telpon/HP : ".$phone." <br><br>
                //                 </p>
                //                 <p>Mohon untuk segera dilakukan konfirmasi dan verifikasi.<br><br>Terima kasih</p>";
                //     $this->mymail->send($korwilSubject, $korwilTo, $korwilContent);
                //     $mitraSubject = "Konfirmasi Registrasi Mitra";
                //     $mitraTo = $email;
                //     $mitraContent = "<p>
                //                         Terima kasih telah mendaftar sebagai mitra.<br>
                //                         Data anda akan segera diverifikasi dan dikonfirmasi oleh tim kami.<br>
                //                         Mohon menunggu konfirmasi dari koordinator wilayah yang anda pilih.<br><br>
                //                         Salam Pendidikan,
                //                       </p>";
                //     $this->mymail->send($mitraSubject, $mitraTo, $mitraContent);
                // }
                $this->db->trans_commit();
                $callBack = [
                    'success' => true,
                    'message' => '<i class="glyphicon glyphicon-ok-circle"></i> &nbsp; Berhasil memperbaharui data Koordinator Wilayah (EC)',
                ];
                ajaxResponse(201, $callBack);
            } else {
                $this->db->trans_rollback();
                $callBack = [
                    'success' => false,
                    'message' => 'Registrasi gagal!',
                    'csrf_token' => $this->security->get_csrf_hash(),
                ];
                ajaxResponse(400, $callBack);
            }
        } else {
            $callBack = [
                'message' => 'Captcha salah!',
                'success' => false,
                'csrf_token' => $this->security->get_csrf_hash(),
            ];
            ajaxResponse(400, $callBack);
        }
    }

    public function getEcData()
    {
        $email = $this->input->post('email');
        // $result = $this->mod_general->detailData("employee", "email", $email);
        $result = $this->mod_general->getAll("employee", "*", "level=3 AND active=1 AND email='".$email."'", "name");
        echo json_encode($result);
    }

    public function checkDupplicateValidation()
    {
        echo json_encode($this->getCheckExistClientSide($this->input->post('values'), $this->input->post('table'),
            $this->input->post('select')));
    }

    public function getCheckExistClientSide($inputPost, $tableName, $select, $field = null, $value = null)
    {
        $inputPost = strtolower($inputPost);
        $checkExist = null;
        if ($field == null && $value == null) {
            $checkExist = $this->mod_mitra->checkExist($tableName, $select, $inputPost);
        } else {
            $checkExist = $this->mod_mitra->checkExist($tableName, $select, $inputPost, $field, $value);
        }
        if ($checkExist > 0) {
            $data = false;
        } else {
            $data = true;
        }
        return $data;
    }

    public function test()
    {

        // $query = $this->mod_general->getDataMitraAsReferral();
        // 295,294,298
        $id = array();
        $id[]="295";
        $id[]="294";
        $id[]="298";
        print_r($id);
        echo "<br><br>";

        /**
         * - Dapatkan kode mitra lama dalam bentuk array
         * - Dapatkan data referral dari kode mitra lama
         * - Dapatkan kode mitra baru
         * - Update code_referral dengan code mitra baru
         */

        // get code mitra lama
        $codeMitraOld = array();
        $dataMitra = $this->mod_ec->getCodeMitraByIdEmployee($id);

        print_r($dataMitra);
        echo "<br><br>";

        // generate array code_mitra
        foreach($dataMitra as $data)
        {
            $codeMitraOld[] = $data->code;
        }
        print_r($codeMitraOld);
        echo "<br><br>";
        echo json_encode($codeMitraOld);
        echo "<br><br>";

        // get data referral
        $dataReferral = $this->mod_ec->getDataReferral($codeMitraOld);
        print_r($dataReferral);
        echo "<br><br>";
        echo json_encode($dataReferral);
        echo "<br><br>";
        echo $this->db->last_query();
        echo "<br><br>";

        //code mitra baru
        
        // $codeMitraNew = array();
        // foreach($dataMitra as $dm)
        // {
        //     $codeMitraNew[$dm->id_employee]=$dm->code;
        // }
        
        // print_r($codeMitraNew);
        // echo "<br><br>";
        // echo json_encode($codeMitraNew);
        // echo "<br><br>";

        $codeMitraOld = Array( 
            "294" => 42003, 
            "295" => 42004,
            "298" => 41009 
         );

        $codeMitraNew = Array( 
            "294" => 2003, 
            "295" => 2004,
            "298" => 1009 
         );

        // Update code_referral dengan code mitra baru
        foreach($dataReferral as $dr)
        {
            $updateReferral = $this->mod_ec->updateDataBatch3($dr->id_employee, $codeMitraNew[$dr->id_employee]);
            if ($updateReferral) 
            {
                echo "berhasil<br>";
            }
            else
            {
                echo "gagal<br>";
            }
        }
        
    }

    function test2()
    {
        $id_employee_mitra_temp = array();
        $id_employee_mitra_temp = [1297,1052,1];

            $dataMitra = $this->mod_ec->getCodeMitraByIdEmployee($id_employee_mitra_temp);

            $tableKorwilSales = "korwil_sales";
            // generate array code_mitra
            foreach($dataMitra as $data)
            {
                $codeMitraOld[] = $data->code;
                //select berdasarkan email_sales pada tabel korwil_sales
                $checkKorwilSales = $this->mod_general->detailData($tableKorwilSales, "email_sales", $data->email);
                // print_r($checkKorwilSales);
                echo "<br><br>";
                IF($checkKorwilSales)
                {

                    // jika ada data, update email_korwil
                    // echo "data";
                    $email_korwil = array(
                        "email_korwil" => $email
                    );
                    $this->mod_general->updateData($tableKorwilSales, $email_korwil, "email_sales", $data->email);
                }
                else
                {
                    // jika tidak ada data, insert data
                    // echo "no data";
                    $data_korwil_sales = array(
                        "email_sales" => "",
                        "email_korwil" => ""
                    );
                    $this->mod_general->addData($tableKorwilSales,$data_korwil_sales);
                }
            }
    }

    function test_kabupaten()
    {
        // $kabupaten = array(
        //     array('1','a'),
        //     array('2','b'),
        //     array('3','c'),
        //     array('4','d')
        // );
        // // Simpan data Kabupaten ke tabel employe_kabupaten_kota
        //     $dataKabupaten = array();
        //     for($i=0; $i < count($kabupaten);$i++)
        //     {
        //         $dataKabupaten[] = array(
        //             "id_employee" => $idEmployee,
        //             "kabupaten_kota" => $kabupaten[$i],
        //             "status" => 1
        //         );

        //         $kabupatenArray[] = $kabupaten[$i][1];
        //     }

            // print_r($kabupatenArray);
            $kabupaten = "Kab. Bantaeng";

            $id_employee_old = $this->mod_ec->getEcByKabupaten($kabupaten);
            print_r($id_employee_old);

            echo "<br><br>".$id_employee_old[0]->id_employee;

    }

    function testCode()
    {
        $email="bsetiadi@gramediaprinting.com";
        $codeMitraLast = $this->db->query("select b.`code_mitra` from employee a join mitra_profile b on a.`code`=b.`code_korwil` where a.`email`='" . $email . "' order by b.`code_mitra` desc limit 1")->result()[0]->code_mitra;
        print_r($codeMitraLast);
    }
}
