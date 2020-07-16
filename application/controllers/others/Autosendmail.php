<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_general $mod_general
 * @property Mymail $mymail
 */
class Autosendmail extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->load->library('mymail');
        $this->load->helper('file');
    }

    public function sendFaktur()
    {
        $this->db->trans_begin();
        $url = 'http://gramediaprinting.com/bukusekolah/';
        $out = [];
        $files = file_get_contents($url, false, stream_context_create(arrSSLContext()));
        preg_match_all('/<a[^>]*href=[\"|\'](.*)[\"|\']/Ui', $files, $out, PREG_PATTERN_ORDER);
        $data = [];
        $count = 1;
        foreach ($out[1] as $row => $value) {
            if ( ! preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬]/', $value) && $value != "/" && $value != "Thumbs.db" && $value != "autorun.inf/") {
                $reference = substr($value, 0, 9);
                if (read_file(FCPATH . 'uploads/faktur/' . $value) == false) {
                    $customer = $this->mod_general->getEmailCustomer("orders.reference = '" . $reference . "'");
                    if ($customer != null) {
                        if (isset($data[$reference])) {
                            array_push($data[$reference], $value);
                        } else {
                            $data[$reference][0] = $value;
                        }
                        $file = $url . $value;
                        $newfile = FCPATH . 'uploads/faktur/' . $value;
                        if (copy($file, $newfile)) {
                            $toEmail = array();
                            if ($customer[0]->email != null) {
                                array_push($toEmail, $customer[0]->email);
                            }
                            if ($customer[0]->email_kepsek != null) {
                                array_push($toEmail, $customer[0]->email_kepsek);
                            }
                            if ($customer[0]->email_operator != null) {
                                array_push($toEmail, $customer[0]->email_operator);
                            }

                            // vindy 2019-08-22
                            // Menambahkan function email blacklist
                            $toEmail = $this->getDataEmailBlacklist($toEmail);

                            if (count($toEmail) > 0) {
                                /* Ditutup. Fa, 20200319
								$subject = 'Faktur Pajak Pesanan #' . $reference;
                                $to = $toEmail;
                                $attachment = FCPATH . 'uploads/faktur/' . $value;
                                $content = '<p>Terlampir Faktur Pajak Pesanan Buku Sekolah anda di PT. Gramedia dengan Kode: <b>#' . $reference . '</b></p><p>Untuk melihat detil pesanan anda, silahkan <a href ="' . base_url('pesanan/detail/' . $customer[0]->id_order) . '"><b>KLIK DISINI</b></a></p>';
                                $this->mymail->sendBlast($subject, $to, $content, $attachment);
								*/
                                $logFile = date('Y-m-d H:i:s') . " --> Pesanan " . $reference . " telah dikirimkan ke : " . implode($toEmail,
                                        ", ");
                                file_put_contents(FCPATH . 'tmp/logs/log_files_' . date("Y-m-d") . '.txt',
                                    $logFile . PHP_EOL, FILE_APPEND | LOCK_EX);
                                $callBack['message'] = "Faktur Pesanan #" . $reference . " berhasil dikirimkan";
                                echo json_encode($callBack);
                                if (($count > 0) && ($count % 10 == 0)) {
                                    sleep(3);
                                }
                                $count++;
                            } else {
                                $callBack['message'] = "Tidak ada email untuk pesanan #" . $reference;
                                echo json_encode($callBack);
                            }
                        } else {
                            $callBack['message'] = "Gagal untuk mengunduh file #" . $reference;
                            echo json_encode($callBack);
                        }
                    } else {
                        $callBack['message'] = "Kode Pesanan #" . $reference . " tidak ada di orders";
                        echo json_encode($callBack);
                    }
                }
            }
        }
        $separator = "--------------------------------------------------------------------------------------------------------------------------------------------";
        file_put_contents(FCPATH . 'tmp/logs/log_files_' . date("Y-m-d") . '.txt', $separator . PHP_EOL, FILE_APPEND | LOCK_EX);
        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Kirim faktur berhasil";
            echo json_encode($callBack);
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Kirim faktur tidak berhasil";
            echo json_encode($callBack);
        }
    }

    public function getDataEmailBlacklist($mail_to) 
    {
        $bl_email = array();

        // array email recipient
        $to_email = $mail_to;

        // get email blacklist from tabel email_blacklist
        $query = $this->Mod_general->getWhereIn('email_blacklist', 'email', 'email', $to_email);
        if($query)
        {
            foreach ($query as $d) {
                $bl_email[]=$d['email'];
            }
        }

        // remove email blacklist from email recipient
        $result = array_diff(
            $to_email, 
            $bl_email
        );

        $data = array_merge($result);
        return $data;
    }
}
