<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_alamat extends CI_Model
{
    private $_table;

    public function __construct()
    {
        parent::__construct();
        $this->_table = 'address';
    }

    public function getAlamat($id_customer)
    {
        $sql =
        "SELECT
        a.id_address,
        a.address,
        a.postcode,
        a.alias,
        b.name as provinsi,
        c.name as kab_kota,
        d.name as kecamatan,
        e.name as kelurahan

        FROM address a

        LEFT JOIN provinsi b ON (a.id_provinsi = b.id_provinsi)
        LEFT JOIN kabupaten c ON (a.id_kab_kota = c.id_kab_kota)
        LEFT JOIN kecamatan d ON (a.id_kecamatan = d.id_kecamatan)
        LEFT JOIN kelurahan e ON (a.id_kelurahan = e.id_kelurahan)

        WHERE 1
        AND a.id_customer = " . $this->db->escape($id_customer);
        return $this->db->query($sql)->result();
    }

    public function getDetailAlamat($id_address)
    {
        $sql =
        "SELECT a.*,
        b.name as provinsi,
        c.name as kab_kota,
        d.name as kecamatan,
        e.name as kelurahan

        FROM address a

        LEFT JOIN provinsi b ON (a.id_provinsi = b.id_provinsi)
        LEFT JOIN kabupaten c ON (a.id_kab_kota = c.id_kab_kota)
        LEFT JOIN kecamatan d ON (a.id_kecamatan = d.id_kecamatan)
        LEFT JOIN kelurahan e ON (a.id_kelurahan = e.id_kelurahan)

        WHERE 1
        AND a.id_address = " . $this->db->escape($id_address);
        return $this->db->query($sql)->row();
    }

    public function getProvinsi()
    {
        $sql = "SELECT * FROM provinsi";
        return $this->db->query($sql)->result();
    }

    public function getKabupatenKota($id_provinsi)
    {
        $sql = "SELECT * FROM kabupaten WHERE 1 AND id_provinsi = " . $this->db->escape($id_provinsi);
        return $this->db->query($sql)->result();
    }

    public function getKecamatan($id_kab_kota)
    {
        $sql = "SELECT * FROM kecamatan WHERE 1 AND id_kab_kota = " . $this->db->escape($id_kab_kota);
        return $this->db->query($sql)->result();
    }

    public function getKelurahan($id_kecamatan)
    {
        $sql = "SELECT * FROM kelurahan WHERE 1 AND id_kecamatan = " . $this->db->escape($id_kecamatan);
        return $this->db->query($sql)->result();
    }

    public function tambah($data)
    {
        return $this->db->insert($this->_table, $data);
    }

    public function edit($data, $id_address)
    {
        $this->db->where('id_address', $id_address);
        return $this->db->update($this->_table, $data);
    }

    public function hapus($id_address)
    {
        $this->db->where('id_address', $id_address);
        return $this->db->delete($this->_table);
    }
}
