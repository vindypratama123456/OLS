<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_general $Mod_general
 * @property Mod_akunku $m_akunku
 * @property Authcustomer $authcustomer
 * @property ReCaptcha $recaptcha
 * @property Mymail $mymail
 * @property BotDetectCaptcha $botdetectcaptcha
 */
class Akunsaya extends CI_Controller
{
    private $clientState;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $tokenEndpoint;
    private $authorizeEndpoint;
    private $sekolahEndpoint;
    private $sessEndpoint;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('recaptcha');
        $this->load->model('Mod_general');
        $this->load->model('Mod_akunku', 'm_akunku');
        $this->clientState       = "100100";
        $this->clientId          = "bkk13ad";
        $this->clientSecret      = "ae035c5653e256aa8a0a53ed3cbd9db6";
        $this->redirectUri       = 'http://bukusekolah.gramedia.com/akunsaya/verify';
        $this->tokenEndpoint     = "http://data.dikdasmen.kemdikbud.go.id/sso/token";
        $this->authorizeEndpoint = "http://data.dikdasmen.kemdikbud.go.id/sso/auth";
        $this->sekolahEndpoint   = "http://data.dikdasmen.kemdikbud.go.id/sso/infosp";
        $this->sessEndpoint      = "http://data.dikdasmen.kemdikbud.go.id/sso/sessid";
    }

    public function index()
    {
        $this->verify();
    }

    public function login()
    {
        $this->verify();
    }

    public function verify()
    {
        $authCode  = isset($_GET["code"]) ? $_GET["code"] : null;
        $tokenForm = isset($_GET["token_form"]) ? true : false;
        if ($authCode && !$tokenForm) {
            $gToken = $this->getToken($authCode);
            if (!isset($gToken['error'])) {
                $accessToken = $gToken['access_token'];
                $this->session->set_userdata('access_token', $accessToken);
                $gProfile = $this->getProfile($accessToken);
                $gSekolah = $this->getSekolah($accessToken);
                $tempData = array_merge($gProfile, $gSekolah);
                $zona     = $this->Mod_general->getZonaByKabupaten($tempData['kab']);
                $jenjang  = '10-12';
                if (isset($tempData['bentuk_pendidikan'])) {
                    if (strposArr($tempData['bentuk_pendidikan'], ['SD', 'SDLB'])) {
                        $jenjang = '1-6';
                    } elseif (strposArr($tempData['bentuk_pendidikan'], ['SMP', 'SMPLB', 'SPK SMP'])) {
                        $jenjang = '7-9';
                    } else {
                        $jenjang = '10-12';
                    }
                }
                if (empty($tempData['npsn']) || $tempData['npsn'] == '') {
                    if ($tempData) {
                        $data['tempData']            = $tempData;
                        $data['tempData']['jenjang'] = $jenjang;
                        $data['tempData']['zona']    = $zona;
                        $data['title']               = "Login &raquo; Gramedia &raquo; Buku Sekolah";
                        $this->load->view('tshops/add_npsn', $data);
                    } else {
                        redirect($this->authorizeEndpoint . '?response_type=code&client_id=' . $this->clientId . '&state=' . $this->clientState . '&redirect_uri=' . $this->redirectUri, 'refresh');
                    }
                } else {
                    if (!$this->isExist($tempData['npsn'])) {
                        $data = [
                            'sekolah_id'     => $tempData['sekolah_id'],
                            'no_npsn'        => $tempData['npsn'],
                            'jenjang'        => $jenjang,
                            'bentuk'         => $tempData['bentuk_pendidikan'],
                            'school_name'    => $tempData['nama_sekolah'],
                            'kd_prop'        => $tempData['kd_prov'],
                            'provinsi'       => $tempData['prov'],
                            'kd_kab_kota'    => $tempData['kd_kab'],
                            'kabupaten'      => $tempData['kab'],
                            'kd_kec'         => $tempData['kd_kec'],
                            'kecamatan'      => $tempData['kec'],
                            'desa'           => $tempData['desa'],
                            'kodepos'        => $tempData['kode_pos'],
                            'alamat'         => $tempData['alamat'],
                            'phone'          => $tempData['no_telepon'],
                            'email'          => $tempData['email'],
                            'name'           => $tempData['nama_kepsek'],
                            'phone_kepsek'   => $tempData['hp_kepsek'],
                            'zona'           => $zona,
                            'operator'       => $tempData['nama_operator'],
                            'hp_operator'    => $tempData['hp_operator'],
                            'email_operator' => $tempData['email_operator'],
                            'user_k13'       => $tempData['user_k13'],
                            'date_add'       => date('Y-m-d H:i:s')
                        ];
                        $id = $this->Mod_general->addData('customer', $data);
                    } else {
                        $data = [
                            'sekolah_id'     => $tempData['sekolah_id'],
                            'bentuk'         => $tempData['bentuk_pendidikan'],
                            'school_name'    => $tempData['nama_sekolah'],
                            'kd_prop'        => $tempData['kd_prov'],
                            'provinsi'       => $tempData['prov'],
                            'kd_kab_kota'    => $tempData['kd_kab'],
                            'kabupaten'      => $tempData['kab'],
                            'kd_kec'         => $tempData['kd_kec'],
                            'kecamatan'      => $tempData['kec'],
                            'desa'           => $tempData['desa'],
                            'kodepos'        => $tempData['kode_pos'],
                            'alamat'         => $tempData['alamat'],
                            'phone'          => $tempData['no_telepon'],
                            'email'          => $tempData['email'],
                            'name'           => $tempData['nama_kepsek'],
                            'phone_kepsek'   => $tempData['hp_kepsek'],
                            'zona'           => $zona,
                            'id_group'       => $zona,
                            'operator'       => $tempData['nama_operator'],
                            'hp_operator'    => $tempData['hp_operator'],
                            'email_operator' => $tempData['email_operator'],
                            'user_k13'       => $tempData['user_k13'],
                            'date_upd'       => date('Y-m-d H:i:s')
                        ];
                        $this->Mod_general->updateData('customer', $data, 'no_npsn', $tempData['npsn']);
                        $id = $this->getId($tempData['npsn']);
                    }
                    $this->session->set_userdata('data_user', $tempData);
                    $this->session->set_userdata('id_customer', $id);
                    $this->session->set_userdata('name', $tempData['nama_operator']);
                    $this->session->set_userdata('school_name', $tempData['nama_sekolah']);
                    $this->session->set_userdata('jenjang', $jenjang);
                    $this->session->set_userdata('zona', $zona);
                    $this->authcustomer->redirect();
                }
            } else {
                redirect($this->authorizeEndpoint . '?response_type=code&client_id=' . $this->clientId . '&state=' . $this->clientState . '&redirect_uri=' . $this->redirectUri, 'refresh');
            }
        } else {
            redirect($this->authorizeEndpoint . '?response_type=code&client_id=' . $this->clientId . '&state=' . $this->clientState . '&redirect_uri=' . $this->redirectUri, 'refresh');
        }
    }

    public function verifyNPSN()
    {
        $tempData = $this->input->post('tempData');
        $noNPSN  = $this->input->post('u_npsn');
        if ($tempData && $noNPSN) {
            $tempData['npsn'] = $noNPSN;
            $zona = $this->Mod_general->getZonaByKabupaten($tempData['kab']);
            if (!$this->isExistSchool($tempData['nama_sekolah'])) {
                $data = [
                    'sekolah_id'     => $tempData['sekolah_id'],
                    'no_npsn'        => $tempData['npsn'],
                    'jenjang'        => $tempData['jenjang'],
                    'bentuk'         => $tempData['bentuk_pendidikan'],
                    'school_name'    => $tempData['nama_sekolah'],
                    'kd_prop'        => $tempData['kd_prov'],
                    'provinsi'       => $tempData['prov'],
                    'kd_kab_kota'    => $tempData['kd_kab'],
                    'kabupaten'      => $tempData['kab'],
                    'kd_kec'         => $tempData['kd_kec'],
                    'kecamatan'      => $tempData['kec'],
                    'desa'           => $tempData['desa'],
                    'kodepos'        => $tempData['kode_pos'],
                    'alamat'         => $tempData['alamat'],
                    'phone'          => $tempData['no_telepon'],
                    'email'          => $tempData['email'],
                    'name'           => $tempData['nama_kepsek'],
                    'phone_kepsek'   => $tempData['hp_kepsek'],
                    'zona'           => $zona,
                    'operator'       => $tempData['nama_operator'],
                    'hp_operator'    => $tempData['hp_operator'],
                    'email_operator' => $tempData['email_operator'],
                    'user_k13'       => $tempData['user_k13'],
                    'date_add'       => date('Y-m-d H:i:s')
                ];
                $id = $this->Mod_general->addData('customer', $data);
            } else {
                $data = [
                    'no_npsn'        => $tempData['npsn'],
                    'bentuk'         => $tempData['bentuk_pendidikan'],
                    'school_name'    => $tempData['nama_sekolah'],
                    'kd_prop'        => $tempData['kd_prov'],
                    'provinsi'       => $tempData['prov'],
                    'kd_kab_kota'    => $tempData['kd_kab'],
                    'kabupaten'      => $tempData['kab'],
                    'kd_kec'         => $tempData['kd_kec'],
                    'kecamatan'      => $tempData['kec'],
                    'desa'           => $tempData['desa'],
                    'kodepos'        => $tempData['kode_pos'],
                    'alamat'         => $tempData['alamat'],
                    'phone'          => $tempData['no_telepon'],
                    'email'          => $tempData['email'],
                    'name'           => $tempData['nama_kepsek'],
                    'phone_kepsek'   => $tempData['hp_kepsek'],
                    'zona'           => $zona,
                    'id_group'       => $zona,
                    'operator'       => $tempData['nama_operator'],
                    'hp_operator'    => $tempData['hp_operator'],
                    'email_operator' => $tempData['email_operator'],
                    'user_k13'       => $tempData['user_k13'],
                    'date_upd'       => date('Y-m-d H:i:s')
                ];
                $this->Mod_general->updateData('customer', $data, 'school_name', $tempData['nama_sekolah']);
                $id = $this->getId($tempData['npsn']);
            }
            $this->session->set_userdata('data_user', $tempData);
            $this->session->set_userdata('id_customer', $id);
            $this->session->set_userdata('name', $tempData['nama_operator']);
            $this->session->set_userdata('school_name', $tempData['nama_sekolah']);
            $this->session->set_userdata('jenjang', $tempData['jenjang']);
            $this->session->set_userdata('zona', $zona);
            $this->authcustomer->redirect();
        } else {
            redirect($this->authorizeEndpoint . '?response_type=code&client_id=' . $this->clientId . '&state=' . $this->clientState . '&redirect_uri=' . $this->redirectUri, 'refresh');
        }
    }

    public function loginLocal()
    {
        $this->authcustomer->restrict(true);
        $captchaConfig = [
            'CaptchaId' => 'RegisterMitraCaptcha',
            'UserInputId' => 'CaptchaCode'
        ];
        $this->load->library('botdetect/BotDetectCaptcha', $captchaConfig);
        $data['captchaHtml'] = $this->botdetectcaptcha->Html();
        $data['title'] = "Login &raquo; Gramedia &raquo; Buku Sekolah";
        $this->load->view('tshops/login', $data);
    }

    public function processLogin()
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        $captchaConfig = [
            'CaptchaId' => 'RegisterMitraCaptcha',
            'UserInputId' => 'CaptchaCode'
        ];
        $this->load->library('botdetect/BotDetectCaptcha', $captchaConfig);
        $code = $this->input->post('CaptchaCode', true);
        $isHuman = $this->botdetectcaptcha->Validate($code);
        if ($isHuman) {
            $userEmail = $this->input->post('u_email', true);
            $userNPSN  = $this->input->post('u_npsn', true);
            $userTelp  = $this->input->post('u_telp', true);
            $telp      = preg_replace('/\D/', '', $userTelp);
            if ($userEmail && $userNPSN && $userTelp) {
                $dataSekolah = [
                    'email_operator' => $userEmail,
                    'no_npsn' => $userNPSN,
                    'phone' => $userTelp,
                ];
                $profileExist = $this->m_akunku->getSchoolLogin($dataSekolah);
                if ($profileExist > 0) {
                    $profile = $this->m_akunku->getSchoolLogin($dataSekolah, 2)[0];
                    $phoneExist = preg_replace('/\D/', '', $profile->phone);
                    if ($phoneExist == $telp) {
                        $zona = $this->Mod_general->getZonaByKabupaten($profile->kabupaten);
                        $params = [
                            'sekolah_id'        => $profile->sekolah_id,
                            'bentuk_pendidikan' => $profile->bentuk,
                            'npsn'              => $profile->no_npsn,
                            'nama_sekolah'      => $profile->school_name,
                            'alamat'            => $profile->alamat,
                            'name'              => $profile->name,
                            'nipkep'            => $profile->nip_kepsek,
                            'phone_kepsek'      => $profile->phone_kepsek,
                            'nama_operator'     => $profile->operator,
                            'hp_operator'       => $profile->hp_operator,
                            'email_operator'    => $profile->email_operator,
                            'email'             => $profile->email,
                            'no_telepon'        => $profile->phone,
                            'kode_pos'          => $profile->kodepos,
                            'desa'              => $profile->desa,
                            'kec'               => $profile->kecamatan,
                            'kd_kec'            => $profile->kd_kec,
                            'kab'               => $profile->kabupaten,
                            'kd_kab'            => $profile->kd_kab_kota,
                            'prov'              => $profile->provinsi,
                            'user_k13'          => $profile->user_k13,
                            'referensi'         => 'GMR'
                        ];
                        $this->session->set_userdata('data_user', $params);
                        $this->session->set_userdata('id_customer', $profile->id_customer);
                        $this->session->set_userdata('name', $profile->operator);
                        $this->session->set_userdata('school_name', $profile->school_name);
                        $this->session->set_userdata('jenjang', $profile->jenjang);
                        $this->session->set_userdata('zona', $zona);
                        $callBack = [
                            'success' => true,
                            'message' => 'Login berhasil!',
                        ];
                        ajaxResponse(201, $callBack);
                    } else {
                        $this->session->set_flashdata('error', 'Nomor Telepon anda belum terdaftar, silahkan melakukan registrasi terlebih dahulu!');
                        $this->session->set_flashdata('userEmail', $userEmail);
                        $this->session->set_flashdata('userNPSN', $userNPSN);
                        $this->session->set_flashdata('userTelp', $userTelp);
                        $callBack = [
                            'success' => false,
                            'message' => 'Login gagal!',
                            'csrf_token' => $this->security->get_csrf_hash()
                        ];
                        ajaxResponse(400, $callBack);
                    }
                } else {
                    $dataNPSN = [
                        'no_npsn' => $userNPSN
                    ];
                    $npsnExist = $this->m_akunku->getSchoolLogin($dataNPSN);
                    if ($npsnExist > 0) {
                        $this->session->set_flashdata('error', 'Email anda belum terdaftar, silahkan melakukan registrasi terlebih dahulu!');
                    } else {
                        $this->session->set_flashdata('error', 'Anda belum terdaftar, silahkan melakukan registrasi terlebih dahulu!');
                    }
                    $this->session->set_flashdata('userEmail', $userEmail);
                    $this->session->set_flashdata('userNPSN', $userNPSN);
                    $this->session->set_flashdata('userTelp', $userTelp);
                    $callBack = [
                        'success' => false,
                        'message' => 'Login gagal!',
                        'csrf_token' => $this->security->get_csrf_hash()
                    ];
                    ajaxResponse(400, $callBack);
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Maaf, login anda gagal. Silahkan coba lagi!');
                $callBack = [
                    'success' => false,
                    'message' => 'Login gagal!',
                    'csrf_token' => $this->security->get_csrf_hash()
                ];
                ajaxResponse(400, $callBack);
            }
        } else {
            $this->session->set_flashdata('error', 'Captcha Salah!');
            $callBack = [
                'success' => false,
                'message' => 'Captcha salah!',
                'csrf_token' => $this->security->get_csrf_hash()
            ];
            ajaxResponse(400, $callBack);
        }
    }

    public function registerLink()
    {
        $this->session->set_flashdata('reg_email', $this->input->post('u_email', true));
        $this->session->set_flashdata('reg_npsn', $this->input->post('u_npsn', true));
        $this->session->set_flashdata('reg_phone', $this->input->post('u_telp', true));
        $response = [
            'redirectURL' => 'register'
        ];
        echo json_encode($response);
    }

    public function register()
    {
        $this->authcustomer->restrict(true);
        $captchaConfig = [
            'CaptchaId' => 'RegisterSchoolCaptcha',
            'UserInputId' => 'CaptchaCode'
        ];
        $this->load->library('botdetect/BotDetectCaptcha', $captchaConfig);
        $data['title'] = "Register &raquo; Gramedia &raquo; Buku Sekolah";
        $data['bentuk']  = $this->m_akunku->getAll('customer', 'distinct(bentuk)', '', 'bentuk ASC');
        $data['jenjang']  = $this->m_akunku->getAll('customer', 'distinct(jenjang)', '', 'jenjang ASC');
        $data['provinsi'] = $this->m_akunku->getAll('customer', 'distinct(provinsi)', '', 'provinsi ASC');
        $data['captchaHtml'] = $this->botdetectcaptcha->Html();
        $this->load->view('tshops/register', $data);
    }

    public function getKabupatenByProvinsi()
    {
        $provinsi = $this->input->post('provinsi');
        if ($provinsi) {
            $kabupaten = $this->m_akunku->getAll('customer', 'distinct(kabupaten)', 'provinsi = "' . $provinsi . '"', 'kabupaten ASC');
            $callBack = [
                "row"     => $kabupaten,
                "success" => true
            ];
        } else {
            $callBack = [
                "success" => false
            ];
        }
        echo json_encode($callBack);
    }

    public function processRegister()
    {
        if (!$this->input->is_ajax_request()) {
            return false;
        }
        $captchaConfig = [
            'CaptchaId' => 'RegisterSchoolCaptcha',
            'UserInputId' => 'CaptchaCode'
        ];
        $this->load->library('botdetect/BotDetectCaptcha', $captchaConfig);
        $code = $this->input->post('CaptchaCode');
        $isHuman = $this->botdetectcaptcha->Validate($code);
        if ($isHuman) {
            $no_npsn     = trim($this->input->post('reg_no_npsn', true));
            $school_name = trim($this->input->post('reg_school_name', true));
            $bentuk      = $this->input->post('reg_bentuk', true);
            $jenjang     = $this->input->post('reg_jenjang', true);
            $alamat      = trim($this->input->post('reg_alamat', true));
            $provinsi    = $this->input->post('reg_provinsi', true);
            $kabupaten   = $this->input->post('reg_kabupaten', true);
            $phone       = trim($this->input->post('reg_phone', true));
            $email       = trim(strtolower($this->input->post('reg_email', true)));
            $zona        = $this->Mod_general->getZonaByKabupaten($kabupaten);
            $error       = [];
            if ($this->m_akunku->checkExist('customer', 'email_operator', $email, 'no_npsn', $no_npsn) > 0) {
                $error[] = "Email <strong>'" . $email . "'</strong> sudah ada, silahkan masukkan email yang lain!";
            }
            if (count($error) > 0) {
                $this->session->set_flashdata('alert_error', $error);
                $this->session->set_flashdata('error_count', count($error));
                $callBack = [
                    'success' => false,
                    'message' => $error
                ];
                ajaxResponse(400, $callBack);
                exit();
            }
            $this->db->trans_begin();
            $checkExist = $this->m_akunku->getList('customer', '*', 'no_npsn = ' . $this->db->escape($no_npsn));
            $idCustomer = null;
            if ($checkExist > 0) {
                $dataCustomerExist = $this->m_akunku->getAll('customer', '*', 'no_npsn = ' . $this->db->escape($no_npsn))[0];
                $idCustomer = $dataCustomerExist->id_customer;
                $dataUpdate = [
                    'school_name'    => ($dataCustomerExist->school_name != '') ? $dataCustomerExist->school_name : $school_name,
                    'bentuk'         => ($dataCustomerExist->bentuk != '') ? $dataCustomerExist->bentuk : $bentuk,
                    'jenjang'        => ($dataCustomerExist->jenjang != '') ? $dataCustomerExist->jenjang : $jenjang,
                    'zona'           => $zona,
                    'alamat'         => ($dataCustomerExist->alamat != '') ? $dataCustomerExist->alamat : $alamat,
                    'provinsi'       => ($dataCustomerExist->provinsi != '') ? $dataCustomerExist->provinsi : $provinsi,
                    'kabupaten'      => ($dataCustomerExist->kabupaten != '') ? $dataCustomerExist->kabupaten : $kabupaten,
                    'phone'          => $phone,
                    'hp_operator'    => ($dataCustomerExist->hp_operator != '') ? $dataCustomerExist->hp_operator : $phone,
                    'email'          => ($dataCustomerExist->email != '') ? $dataCustomerExist->email : $email,
                    'email_operator' => $email,
                    'active'         => 1,
                    'date_upd'       => date('Y-m-d H:i:s')
                ];
                $this->m_akunku->edit('customer', 'id_customer = ' . $this->db->escape($idCustomer), $dataUpdate);
            } else {
                $dataAdd = [
                    'no_npsn'          => $no_npsn,
                    'school_name'      => $school_name,
                    'bentuk'           => $bentuk,
                    'jenjang'          => $jenjang,
                    'zona'             => $zona,
                    'alamat'           => $alamat,
                    'provinsi'         => $provinsi,
                    'kabupaten'        => $kabupaten,
                    'phone'            => $phone,
                    'hp_operator'      => $phone,
                    'email'            => $email,
                    'email_operator'   => $email,
                    'active'           => 1,
                    'is_self_register' => 1,
                    'date_add'         => date('Y-m-d H:i:s')
                ];
                $idCustomer = $this->m_akunku->add('customer', $dataAdd);
            }
            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();
                $profile = $this->m_akunku->getAll('customer', '*', 'id_customer = ' . $this->db->escape($idCustomer))[0];
                $params  = [
                    'sekolah_id'        => $profile->sekolah_id,
                    'bentuk_pendidikan' => $profile->bentuk,
                    'npsn'              => $profile->no_npsn,
                    'nama_sekolah'      => $profile->school_name,
                    'alamat'            => $profile->alamat,
                    'name'              => $profile->name,
                    'nipkep'            => $profile->nip_kepsek,
                    'phone_kepsek'      => $profile->phone_kepsek,
                    'nama_operator'     => $profile->operator,
                    'hp_operator'       => $profile->hp_operator,
                    'email_operator'    => $profile->email_operator,
                    'email'             => $profile->email,
                    'no_telepon'        => $profile->phone,
                    'kode_pos'          => $profile->kodepos,
                    'desa'              => $profile->desa,
                    'kec'               => $profile->kecamatan,
                    'kd_kec'            => $profile->kd_kec,
                    'kab'               => $profile->kabupaten,
                    'kd_kab'            => $profile->kd_kab_kota,
                    'prov'              => $profile->provinsi,
                    'user_k13'          => $profile->user_k13,
                    'referensi'         => 'GMR'
                ];
                $this->session->set_userdata('data_user', $params);
                $this->session->set_userdata('id_customer', $profile->id_customer);
                $this->session->set_userdata('name', $profile->operator);
                $this->session->set_userdata('school_name', $profile->school_name);
                $this->session->set_userdata('jenjang', $profile->jenjang);
                $this->session->set_userdata('zona', $profile->zona);
                /* Ditutup. Fa, 20200320
                $this->load->library('mymail');
                $customerSubject = "Registrasi customer - Buku Sekolah Gramedia";
                $customerTo      = array($profile->email_operator);
                $customerContent = "<p>Selamat anda telah mendaftar sebagai pelanggan kami dengan data berikut:<br><br></p>
                        <p>
                        - Nomor NPSN    : " . $profile->no_npsn . " <br>
                        - Email         : " . $profile->email_operator . " <br>
                        - Nama Sekolah  : " . $profile->school_name . " <br>
                        - Alamat        : " . $profile->alamat . ", " . $profile->provinsi . ", " . $profile->kabupaten . " <br>
                        - No. Telpon/HP : " . $profile->hp_operator . " <br><br>
                        </p>
                        <p>
                            Anda dapat melakukan login dengan menggunakan nomor NPSN dan alamat email yang terdaftar. <br><br>
                            Terima kasih
                        </p>";
                $this->mymail->send($customerSubject, $customerTo, $customerContent);
                */
                $this->session->set_flashdata('success', 'Selamat, anda berhasil melakukan registrasi.');
                $callBack = [
                    'success' => true,
                    'message' => 'Registrasi berhasil!',
                ];
                ajaxResponse(201, $callBack);
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Maaf, registrasi anda gagal. Silahkan coba lagi!');
                $callBack = [
                    'success' => false,
                    'message' => 'Registrasi gagal!',
                ];
                ajaxResponse(400, $callBack);
            }
        } else {
            $this->session->set_flashdata('error', 'Captcha Salah!');
            $callBack = [
                'success' => false,
                'message' => 'Captcha salah!',
            ];
            ajaxResponse(400, $callBack);
        }
    }

    public function logout()
    {
        if ($this->authcustomer->logout()) {
            redirect('', 'refresh');
        }
    }

    public function profil()
    {
        $this->authcustomer->restrict();
        $data['customer'] = $this->m_akunku->getDetail($this->session->userdata("id_customer"));
        $data['title']    = "Profil &raquo; Gramedia.com";
        $this->load->view('tshops/profil', $data);
    }

    public function edit()
    {
        try {
            $id   = $this->session->userdata("id_customer");
            $data = [
                'email'           => $this->input->post('email', true),
                'phone'           => $this->input->post('phone', true),
                'name'            => $this->input->post('nama_lengkap', true),
                'nip_kepsek'      => $this->input->post('nip_kepsek', true),
                'phone_kepsek'    => $this->input->post('phone_kepsek', true),
                'email_kepsek'    => $this->input->post('email_kepsek', true),
                'nama_bendahara'  => $this->input->post('nama_bendahara', true),
                'nip_bendahara'   => $this->input->post('nip_bendahara', true),
                'phone_bendahara' => $this->input->post('phone_bendahara', true),
                'nama_bank'       => $this->input->post('nama_bank', true),
                'nama_rekening'   => $this->input->post('nama_rekening', true),
                'nomor_rekening'  => $this->input->post('nomor_rekening', true),
                'npwp'            => $this->input->post('npwp', true),
                'nama_npwp'       => $this->input->post('nama_npwp', true),
                'operator'        => $this->input->post('operator', true),
                'hp_operator'     => $this->input->post('hp_operator', true),
                'email_operator'  => $this->input->post('email_operator', true),
                'is_complete'     => 1,
                'date_upd'        => date('Y-m-d H:i:s')
            ];
            $updateProfil = $this->m_akunku->updateProfil($data, $id);
            if ($updateProfil) {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissable">Profil anda, berhasil <b>diperbarui</b>! Terima kasih atas kerjasamanya.</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable"><b>Gagal memperbarui</b> Profil anda!</div>');
            }
            redirect("akunsaya/profil");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    private function getToken($code)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->tokenEndpoint . "?token_form=1&code=" . $code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => "client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret . "&grant_type=authorization_code&redirect_uri=http%3A%2F%2Fbukusekolah.gramedia.com%2Fakunsaya%2Fverify&code=" . $code,
            CURLOPT_HTTPHEADER     => [
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ]
        ]);
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err) {
            redirect($this->authorizeEndpoint . '?response_type=code&client_id=' . $this->clientId . '&state=' . $this->clientState . '&redirect_uri=' . $this->redirectUri, 'refresh');
        } else {
            return json_decode($response, true);
        }
    }

    private function getProfile($access_token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->sessEndpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => "access_token=" . $access_token,
            CURLOPT_HTTPHEADER     => [
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ]
        ]);
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response, true);
        }
    }

    private function getSekolah($access_token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->sekolahEndpoint . "?sekolah_form=1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => "access_token=" . $access_token,
            CURLOPT_HTTPHEADER     => [
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ]
        ]);
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response, true);
        }
    }

    private function isExist($npsn)
    {
        $query = $this->db->query("SELECT `no_npsn` FROM `customer` WHERE `no_npsn`=".$this->db->escape($npsn));
        return $query->num_rows() > 0;
    }

    private function isExistSchool($school_name)
    {
        $query = $this->db->query("SELECT `school_name` FROM `customer` WHERE `school_name`=".$this->db->escape($school_name));
        return $query->num_rows() > 0;
    }

    private function getId($npsn)
    {
        $query = $this->db->query("SELECT `id_customer` FROM `customer` WHERE `no_npsn`=".$this->db->escape($npsn));
        if ($query->num_rows() > 0) {
            return $query->row('id_customer');
        }
        return false;
    }

    public function getCheckExistClientSide($inputPost, $tableName, $select, $field = null, $value = null)
    {
        $inputPost = strtolower($inputPost);
        $checkExist = null;
        if ($field == null && $value == null) {
            $checkExist = $this->m_akunku->checkExist($tableName, $select, $inputPost);
        } else {
            $checkExist = $this->m_akunku->checkExist($tableName, $select, $inputPost, $field, $value);
        }
        if ($checkExist > 0) {
            $data = false;
        } else {
            $data = true;
        }
        return $data;
    }

    public function checkDupplicateValidation()
    {
        echo json_encode($this->getCheckExistClientSide($this->input->post('values'), $this->input->post('table'), $this->input->post('select'), $this->input->post('where'), $this->input->post('where_value')));
    }

    public function dummyLogin($jenjang = null, $month = null, $year = null)
    {
        if ($jenjang && $month && $year) {
            $m = date('m');
            $y = date('y');
            if ($month == $m && $year == $y) {
                $profile = $this->m_akunku->getAll("customer", "", "is_dummy = 1 and active = 1 and bentuk = '".strtolower($jenjang)."'", 'id_customer DESC')[0];
                if ($profile) {
                    $params = [
                        'sekolah_id'        => $profile->sekolah_id,
                        'bentuk_pendidikan' => $profile->bentuk,
                        'npsn'              => $profile->no_npsn,
                        'nama_sekolah'      => $profile->school_name,
                        'alamat'            => $profile->alamat,
                        'name'              => $profile->name,
                        'nipkep'            => $profile->nip_kepsek,
                        'phone_kepsek'      => $profile->phone_kepsek,
                        'nama_operator'     => $profile->operator,
                        'hp_operator'       => $profile->hp_operator,
                        'email_operator'    => $profile->email_operator,
                        'email'             => $profile->email,
                        'no_telepon'        => $profile->phone,
                        'kode_pos'          => $profile->kodepos,
                        'desa'              => $profile->desa,
                        'kec'               => $profile->kecamatan,
                        'kd_kec'            => $profile->kd_kec,
                        'kab'               => $profile->kabupaten,
                        'kd_kab'            => $profile->kd_kab_kota,
                        'prov'              => $profile->provinsi,
                        'user_k13'          => $profile->user_k13,
                        'referensi'         => 'GMR'
                    ];
                    $this->session->set_userdata('data_user', $params);
                    $this->session->set_userdata('id_customer', $profile->id_customer);
                    $this->session->set_userdata('name', $profile->operator);
                    $this->session->set_userdata('school_name', $profile->school_name);
                    $this->session->set_userdata('jenjang', $profile->jenjang);
                    $this->session->set_userdata('zona', $profile->zona);
                    $this->authcustomer->redirect();
                } else {
                    redirect('', 'refresh');
                }
            } else {
                redirect('', 'refresh');
            }
        } else {
            redirect('', 'refresh');
        }
    }
}
