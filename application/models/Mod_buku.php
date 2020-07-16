<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Mod_buku extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getTitle($id_product)
    {
        $sql = "SELECT name FROM product WHERE 1 AND id_product = " . $this->db->escape($id_product);
        return $this->db->query($sql)->row();
    }

    public function getDetailBuku($id_product)
    {
        return $this->db->query("SELECT * FROM product WHERE 1 AND id_product = " . $this->db->escape($id_product))->result();
    }

    public function generateJsonBukuBos($jenjang)
    {
        $sql =
        "SELECT
        a.id_product,
        a.name,
        a.price,
        a.weight,
        c.name as category

        FROM product a

        LEFT JOIN category_product b ON (a.id_product = b.id_product)
        LEFT JOIN category c ON (b.id_category = c.id_category)

        WHERE 1
        AND b.id_category IN (SELECT aa.id_category FROM category aa WHERE aa.jenjang = " . $this->db->escape($jenjang) . ")

        GROUP BY b.id_product

        ORDER BY c.name ASC, a.id_product ASC";
        return $this->db->query($sql)->result();
    }

    public function generateJsonBukuNonBos()
    {
        $qRawSQL =
        "SELECT
            a.id_product,
            a.name,
            a.price,
            a.weight,
            b.name as category
        FROM product a
        LEFT JOIN category b ON (a.id_category_default = b.id_category)
        WHERE 1
        AND a.id_product IN (
            SELECT
            aa.id_product
            FROM category_product aa
            LEFT JOIN category bb ON (aa.id_category = bb.id_category)
            WHERE 1
            AND bb.jenjang IS NULL
            AND aa.id_product NOT IN(
                SELECT cc.id_product FROM category_product cc WHERE cc.id_category IN ? GROUP BY cc.id_product
            )
            GROUP BY aa.id_product
        )
        ORDER BY b.id_category ASC, a.id_product ASC";
        return $this->db->query($qRawSQL, [2, 3])->result();
    }
}
