<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_alamat $m_alamat
 * @property Authcustomer $authcustomer
 */
class Alamat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->authcustomer->restrict();
        $this->load->model('Mod_alamat', 'm_alamat');
    }

    public function index()
    {
        $data['alamat'] = $this->m_alamat->getAlamat($this->session->userdata("id_customer"));
        $data['title'] = "Alamat &raquo; Gramedia.com";
        $this->load->view('tshops/alamat/list', $data);
    }

    public function edit($id_address)
    {
        $data['provinsi'] = $this->m_alamat->getProvinsi();
        $data['alamat'] = $this->m_alamat->getDetailAlamat($id_address);
        $data['title'] = "Edit Alamat &raquo; Gramedia.com";
        $this->load->view('tshops/alamat/edit', $data);
    }

    public function tambah()
    {
        $data['title'] = "Tambah Alamat &raquo; Gramedia";
        $data['provinsi'] = $this->m_alamat->getProvinsi();
        $this->load->view('tshops/alamat/tambah', $data);
    }

    public function process()
    {
        if (empty($this->input->post('id_address'))) {
            $data['id_address'] = '';
            $data['id_provinsi'] = $this->input->post('provinsi');
            $data['id_kab_kota'] = $this->input->post('kabupaten_kota');
            $data['id_kecamatan'] = $this->input->post('kecamatan');
            $data['id_kelurahan'] = $this->input->post('kelurahan');
            $data['id_customer'] = $this->input->post('id');
            $data['alias'] = $this->input->post('alias');
            $data['address'] = $this->input->post('alamat');
            $data['phone'] = $this->input->post('nomor_handphone');
            $data['postcode'] = $this->input->post('postcode');
            $data['date_add'] = date("Y-m-d H:i:s");
            $data['date_upd'] = date("Y-m-d H:i:s");
            $data['active'] = 1;
            $data['deleted'] = 0;
            if ($this->m_alamat->tambah($data)) {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissable">Alamat baru anda, berhasil <b>dimasukkan</b>!</div>');
                redirect("alamat");
            }
        } else {
            $data = [
                'id_provinsi' => $this->input->post('provinsi'),
                'id_kab_kota' => $this->input->post('kabupaten_kota'),
                'id_kecamatan' => $this->input->post('kecamatan'),
                'id_kelurahan' => $this->input->post('kelurahan'),
                'alias' => $this->input->post('alias'),
                'address' => $this->input->post('alamat'),
                'phone' => $this->input->post('nomor_handphone'),
                'postcode' => $this->input->post('postcode'),
                'date_upd' => date("Y-m-d H:i:s")
            ];
            if ($this->m_alamat->edit($data, $this->input->post('id_address'))) {
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissable">Alamat "'.$this->input->post('alias').'", berhasil <b>diedit</b>!</div>');
                redirect("alamat/edit/".$this->input->post('id_address'));
            }
        }
    }

    public function hapus($idAddress)
    {
        if ($this->m_alamat->hapus($idAddress)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissable">Alamat "'.$this->input->post('alias').'", berhasil <b>dihapus</b>!</div>');
            redirect("alamat");
        }
    }

    public function getKabupatenKota()
    {
        echo json_encode($this->m_alamat->getKabupatenKota($this->input->post('id_provinsi')));
    }

    public function getKecamatan()
    {
        echo json_encode($this->m_alamat->getKecamatan($this->input->post('id_kab_kota')));
    }

    public function getKelurahan()
    {
        echo json_encode($this->m_alamat->getKelurahan($this->input->post('id_kecamatan')));
    }
}
