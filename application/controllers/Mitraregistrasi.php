<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_mitra $mod_mitra
 * @property ReCaptcha $recaptcha
 * @property BotDetectCaptcha $botdetectcaptcha
 * @property Mymail $mymail
 */
class Mitraregistrasi extends CI_Controller
{
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('recaptcha');
        $this->load->model('mod_mitra');
        $this->load->helper('form');
        $this->table = 'mitra_profile';
    }

    public function index()
    {
        $captchaConfig = [
            'CaptchaId' => 'RegisterMitraCaptcha',
            'UserInputId' => 'CaptchaCode',
        ];
        $this->load->library('botdetect/BotDetectCaptcha', $captchaConfig);
        $data['page_title'] = 'Registrasi Mitra - Buku Sekolah Gramedia';
        $data['korwil'] = $this->mod_mitra->getAll("employee", "*", "level = 3 and active = 1", "code asc, name asc");
        $data['referensi'] = $this->mod_mitra->getAll("employee", "*", "(level='3' OR level='4') and active = 1",
            "code asc, level asc, name asc");
        $data['bank'] = $this->mod_mitra->getAll("master_bank", "*", "status = 1", "id asc");
        $data['captchaHtml'] = $this->botdetectcaptcha->Html();
        $this->load->view('mitra/form_registrasi', $data);
    }

    public function add()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }
        $captchaConfig = [
            'CaptchaId' => 'RegisterMitraCaptcha',
            'UserInputId' => 'CaptchaCode',
        ];
        $this->load->library('botdetect/BotDetectCaptcha', $captchaConfig);
        $code = $this->input->post('CaptchaCode');
        $isHuman = $this->botdetectcaptcha->Validate($code);
        if ($isHuman) {
            $codeKorwil = $this->input->post('reg_korwil');
            $gender = $this->input->post('reg_gender');
            $name = trim($this->input->post('reg_name', true));
            $identity = trim($this->input->post('reg_identity', true));
            $name_npwp = trim($this->input->post('reg_name_npwp', true));
            $npwp = trim($this->input->post('reg_npwp', true));
            $address_npwp = trim($this->input->post('reg_address_npwp', true));
            $address = trim($this->input->post('reg_address', true));
            $phone = trim($this->input->post('reg_phone', true));
            $email = strtolower(trim($this->input->post('reg_email', true)));
            $password = sha1($this->input->post('reg_password', true));
            $bankName = trim($this->input->post('reg_bank_name', true));
            $accountNumber = trim($this->input->post('reg_account_number', true));
            $accountName = trim($this->input->post('reg_account_name', true));
            $codeReferral = $this->input->post('reg_referral');
            $error = [];
            if ($this->mod_mitra->checkExist('employee', 'name', $name) > 0) {
                $error[] = "Nama <strong>'".$name."'</strong> sudah ada, silahkan masukkan nama yang lain!";
            }
            if ($identity && $this->mod_mitra->checkExist('mitra_profile', 'identity_code', $identity) > 0) {
                $error[] = "Nomor identitas <strong>'".$identity."'</strong> sudah ada, silahkan masukkan nomor identitas yang lain!";
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

            $korwilData = $this->mod_mitra->getAll('employee', '*', 'code = '.$codeKorwil)[0];
            $korwilSalesList = $this->mod_mitra->getList('mitra_profile', 'code_mitra', 'code_korwil = '.$codeKorwil);
            $codeMitra = null;
            $idMitra = null;
            if ($korwilSalesList > 0) {
                $last_mitra = $this->mod_mitra->getAll('mitra_profile', 'code_mitra', 'code_korwil = '.$codeKorwil,
                    'code_mitra DESC')[0];
                $codeMitra = $last_mitra->code_mitra + 1;
            } else {
                $codeMitra = $codeKorwil."001";
            }
            $cfg['upload_path'] = config_item('upload_path').'mitra/';
            $cfg['allowed_types'] = 'jpg|jpeg|png|gif|tif';
            $cfg['max_size'] = '10240';
            $cfg['file_name'] = $codeMitra;
            $cfg['file_ext_tolower'] = true;
            $cfg['overwrite'] = true;
            $cfg['remove_spaces'] = true;
            if ( ! is_dir($cfg['upload_path'])) {
                if ( ! mkdir($concurrentDirectory = $cfg['upload_path'], 0777,
                        true) && ! is_dir($concurrentDirectory)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
                }
                chmod($cfg['upload_path'], 0777);
            }
            $this->load->library('upload', $cfg);
            if ( ! $this->upload->do_upload('reg_photo')) {
                $callBack = [
                    'success' => false,
                    'message' => error_form($this->upload->display_errors('<span>', '</span>')),
                    'csrf_token' => $this->security->get_csrf_hash(),
                ];
                ajaxResponse(400, $callBack);
                exit();
            }

            $this->db->trans_begin();
            $upload_file = $this->upload->data();
            $dataEmployee = [
                'code' => $codeMitra,
                'level' => 4,
                'name' => $name,
                'email' => $email,
                'passwd' => $password,
                'telp' => $phone,
                'active' => 0,
            ];
            $idEmployee = $this->mod_mitra->add('employee', $dataEmployee);
            if ($idEmployee) {
                $dataMitra = [
                    'id_employee' => $idEmployee,
                    'code_korwil' => $codeKorwil,
                    'code_mitra' => $codeMitra,
                    'identity_code' => $identity,
                    'name_npwp' => $name_npwp,
                    'no_npwp' => $npwp,
                    'address_npwp' => $address_npwp,
                    'gender' => $gender,
                    'address' => $address,
                    'photo' => $upload_file['file_name'],
                    'bank_account_number' => $accountNumber,
                    'bank_account_name' => $accountName,
                    'bank_account_type' => $bankName,
                    'percent_tax' => ( ! empty($npwp) || $npwp != '') ? 0.025 : 0.030,
                    'code_referral' => $codeReferral,
                    'date_add' => date('Y-m-d H:i:s'),
                    'date_modified' => date('Y-m-d H:i:s'),
                ];
                $idMitra = $this->mod_mitra->add('mitra_profile', $dataMitra);
            }
            if ($this->db->trans_status() === true) {
                if ($idMitra) {
                    $this->load->library('mymail');
                    $korwilSubject = "Pendaftaran Mitra Baru - Buku Sekolah Gramedia";
                    $korwilTo = array($korwilData->email);
                    $korwilContent = "<p>Telah mendaftar sebagai mitra dengan detil data:<br><br></p>
                                <p>
                                - Email         : ".$email." <br>
                                - Nama          : ".$name." <br>
                                - Alamat        : ".$address." <br>
                                - No. Telpon/HP : ".$phone." <br><br>
                                </p>
                                <p>Mohon untuk segera dilakukan konfirmasi dan verifikasi.<br><br>Terima kasih</p>";
                    $this->mymail->send($korwilSubject, $korwilTo, $korwilContent);
                    $mitraSubject = "Konfirmasi Registrasi Mitra";
                    $mitraTo = $email;
                    $mitraContent = "<p>
                                        Terima kasih telah mendaftar sebagai mitra.<br>
                                        Data anda akan segera diverifikasi dan dikonfirmasi oleh tim kami.<br>
                                        Mohon menunggu konfirmasi dari koordinator wilayah yang anda pilih.<br><br>
                                        Salam Pendidikan,
                                      </p>";
                    $this->mymail->send($mitraSubject, $mitraTo, $mitraContent);
                }
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

    public function getRefensiByKorwil()
    {
        $codeKorwil = $this->input->post('regKorwil');
        if ($codeKorwil) {
            $mitra = $this->mod_mitra->getMitraByKorwil($codeKorwil, "code asc, level asc, name asc");
            $callBack = [
                "row" => $mitra,
                "success" => true,
            ];
            echo json_encode($callBack);
        } else {
            $callBack = ['success' => false];
            echo json_encode($callBack);
        }
    }
}
