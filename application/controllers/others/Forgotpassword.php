<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Auth $auth
 * @property Mod_akunku $mod_akunku
 * @property Mod_general $mod_general
 * @property Mymail $mymail
 */
class Forgotpassword extends CI_Controller
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_akunku');
        $this->load->model('mod_general');
        $this->table = 'employee';
    }

    public function index($error = '')
    {
        $this->auth->restrict(true);
        $this->load->view('admin/forgot_password/index');
    }

    public function request()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        try {
            $this->auth->restrict(true);
            $userEmail = $this->input->post('email', true);
            if ($this->mod_akunku->checkUserEmail($userEmail)) {
                $token = generateRandomString(100);
                $this->mod_general->updateData($this->table, ['token' => $token], 'email', $userEmail);
                $message = '<div class="col-lg-12 text-center">
                                <p class="text-center">
                                    Permintaan ubah kata sandi anda akan diproses<br>
                                    Silahkan periksa email anda untuk proses selanjutnya<br><br>
                                </p>
                            </div>';
                $this->sendMail($userEmail, $token);
                $callBack = [
                    'success' => 'true',
                    'message' => $message,
                ];
            } else {
                $error = '<div class="alert alert-danger alert-dismissable" style="margin-top:30px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="fa fa-info-warning"></i> Email tidak terdaftar atau nonaktif.
                        </div>';
                $callBack = [
                    'success' => 'false',
                    'message' => $error,
                    'csrf_token' => $this->security->get_csrf_hash(),
                ];
            }
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
                'csrf_token' => $this->security->get_csrf_hash(),
            ];
        }
        echo json_encode($callBack, true);
    }

    private function sendMail($email, $token)
    {
        $this->load->library('mymail');
        $subject = "Permintaan Ubah Kata Sandi - Buku Sekolah Gramedia";
        $emailTo = $email;
        $content = '<p>Halo, '.$email.'</p>
                    <p>Permintaan untuk melakukan ubah kata sandi baru saja dibuat.<br><br>
                    Silahkan klik tautan dibawah ini untuk melanjutkan proses ubah kata sandi:</p>
                    <p><b><a href="'.getenv("BASE_URL").'others/forgotpassword/token/'.$token.'">Klik Tautan Ini</a></b></p>
                    <p><br><br>Terima kasih</p>';
        $this->mymail->send($subject, $emailTo, $content);
    }

    public function token($token = false)
    {
        if (empty($token)) {
            redirect('others/forgotpassword', 'refresh');
        }

        $this->auth->restrict(true);
        if ($this->mod_akunku->checkToken($token)) {
            $data['token'] = $token;
            $this->load->view('admin/forgot_password/change_password', $data);
        } else {
            $this->session->set_flashdata('msg_error', 'Maaf, Token yang anda gunakan tidak dikenal.');
            redirect('others/forgotpassword', 'refresh');
        }
    }

    public function updatePassword()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        try {
            $this->auth->restrict(true);
            $token = $this->input->post('token');
            $detilUser = $this->mod_general->detailData($this->table, 'token', $token);
            $idUser = $detilUser['id_employee'];
            $newPassword = $this->input->post('new_pass');
            $data = [
                'id_employee' => $idUser,
                'passwd' => sha1($newPassword),
                'token' => '',
            ];
            $this->mod_general->updateData($this->table, $data, 'id_employee', $idUser);
            $messageContent = '<div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="fa fa-info-warning"></i> Kata sandi anda berhasil diperbarui<br>Silahkan login kembali
                                </div>';
            $this->session->set_flashdata('msg_success', $messageContent);
            $callBack = ['success' => 'true'];
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
                'csrf_token' => $this->security->get_csrf_hash(),
            ];
        }
        echo json_encode($callBack, true);
    }
}
