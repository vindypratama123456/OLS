<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_kategori extends CI_Model
{
    private $_sessJenjang;

    public function __construct()
    {
        parent::__construct();
        $this->_sessJenjang = $this->session->userdata('jenjang');
    }

    public function getTitle($id_category)
    {
        $sql = 'SELECT name FROM category WHERE 1 AND id_category = ' . $this->db->escape($id_category);
        return $this->db->query($sql)->row();
    }

    public function getAllData($id_category, $limit = 10, $start = 0)
    {
        if (!$this->_sessJenjang || $id_category > 3) {
            $queryRaw = 'SELECT id_product, name, description, images
                FROM product
                WHERE 1
                AND active = ?
                AND id_product IN (
                    SELECT a.id_product
                    FROM category_product a
                    WHERE 1
                    AND a.id_category = ?
                )
                ORDER BY id_product
                LIMIT ?, ?';
            return $this->db->query($queryRaw, [1, $id_category, $start, $limit])->result();
        }

        $queryRaw = 'SELECT id_product, name, description, images, active
            FROM product
            WHERE 1
            AND active = ?
            AND id_product IN (
                SELECT a.id_product
                FROM category_product a
                WHERE 1
                AND a.id_category = ' . $this->db->escape($id_category) . '
                AND a.id_product IN (
                    SELECT aa.id_product
                    FROM category_product aa
                    LEFT JOIN category bb ON (aa.id_category = bb.id_category)
                    WHERE 1
                    AND bb.jenjang = ?
                )
            )
            ORDER BY id_product
            LIMIT ?, ?';
        return $this->db->query($queryRaw, [1, $this->_sessJenjang, $start, $limit])->result();
    }

    public function numRows($id_category)
    {
        if (!$this->_sessJenjang) {
            $queryRawCat = 'SELECT id_product, name, description, active
                FROM product
                WHERE 1
                AND active = ?
                AND id_product IN (
                    SELECT a.id_product
                    FROM category_product a
                    WHERE 1
                    AND a.id_category = ?
                )
                ORDER BY id_product';
            return $this->db->query($queryRawCat, [1, $id_category])->num_rows();
        }

        if ($id_category > 3) {
            $queryRawCat = 'SELECT id_product, name, description, active
                FROM product
                WHERE 1
                AND active = ?
                AND id_product IN (
                    SELECT a.id_product
                    FROM category_product a
                    WHERE 1
                    AND a.id_category = ?
                )
                ORDER BY id_product';
            return $this->db->query($queryRawCat, [1, $id_category])->num_rows();
        }

        $queryRawCat = 'SELECT id_product, name, description, images, active
            FROM product
            WHERE 1
            AND active = ?
            AND id_product IN (
                SELECT a.id_product
                FROM category_product a
                WHERE 1
                AND a.id_category = ?
                AND a.id_product IN (
                    SELECT aa.id_product
                    FROM category_product aa
                    LEFT JOIN category bb ON (aa.id_category = bb.id_category)
                    WHERE 1
                    AND bb.jenjang = ?
                )
            )';
        return $this->db->query($queryRawCat, [1, $id_category, $this->_sessJenjang])->num_rows();
    }
}
