<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mymail $mymail
 * @property Mod_general $mod_general
 * @property Mod_comission $mod_comission
 */
class Home extends CI_Controller
{
    public $data = [];
    public $list_jenjang = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_general', 'mod_general');
        $this->load->model('Mod_comission', 'mod_comission');
        $this->list_jenjang = ['1-6', '7-9', '10-12'];
    }

    public function index()
    {
        $this->data['title'] = 'Gramedia &raquo; Buku Sekolah';
        $this->load->view('tshops/home', $this->data);
    }

    public function generateJson2013($jenjang, $type = 1)
    {
        switch ($jenjang) {
            case '1-6':
                $in_category = (1 == $type) ? explode(',', getenv('K13_SD')) : [12];
                break;
            case '7-9':
                $in_category = (1 == $type) ? explode(',', getenv('K13_SMP')) : [15];
                break;
            case '10-12':
                $in_category = (1 == $type) ? explode(',', getenv('K13_SMA')) : [18];
                break;
            default:
                exit('Salah masukkan jenjang!');
                break;
        }
        $raw_query = '
                SELECT 
                    `o`.`id_product` AS `id_product`, 
                    `o`.`name` AS `name`, 
                    `o`.`reference` AS `isbn`, 
                    ROUND(`o`.`price_1`) AS `price_1`, 
                    ROUND(`o`.`price_2`) AS `price_2`, 
                    ROUND(`o`.`price_3`) AS `price_3`, 
                    ROUND(`o`.`price_4`) AS `price_4`, 
                    ROUND(`o`.`price_5`) AS `price_5`, 
                    `p`.`name` AS `category`, 
                    `p`.`id_category` AS `category_id`,
                    `o`.`kode_buku` AS `kode_buku`,
                    `q`.`name` AS type,
                    `q`.`alias` AS type_alias,
                    `q`.`id_category` AS type_id
				FROM `product` `o`
                JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
                JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
				WHERE 1
				AND `o`.`enable` = ?
				AND `o`.`id_product` IN (
					SELECT `a`.`id_product`
					FROM `category_product` `a`
					WHERE 1
					AND `a`.`id_category` = ?
					AND `a`.`id_product` IN (
					    SELECT `aa`.`id_product`
					    FROM `category_product` `aa`
					    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
					    WHERE 1
					    AND `bb`.`id_category` IN ?
					)
				)
				AND `o`.`kode_buku` IS NOT NULL
                ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_K13'), $in_category]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            $count = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/2013_'.$type.'.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/2013_'.$type.'.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJson2006($jenjang)
    {
        if ($jenjang) {
            switch ($jenjang) {
                case '1-6':
                    $in_category = explode(',', getenv('KTSP_SD'));
                    break;
                case '7-9':
                    $in_category = explode(',', getenv('KTSP_SMP'));
                    break;
                case '10-12':
                    $in_category = explode(',', getenv('KTSP_SMA'));
                    break;
                default:
                    exit('Salah masukkan jenjang!');
                    break;
            }

            $raw_query = '
                    SELECT 
                        `o`.`id_product` AS `id_product`, 
                        `o`.`name` AS `name`, 
                        `o`.`reference` AS `isbn`, 
                        ROUND(`o`.`price_1`) AS `price_1`, 
                        ROUND(`o`.`price_2`) AS `price_2`,
                        ROUND(`o`.`price_3`) AS `price_3`, 
                        ROUND(`o`.`price_4`) AS `price_4`, 
                        ROUND(`o`.`price_5`) AS `price_5`, 
                        `p`.`name` AS `category`, 
                        `p`.`id_category` AS `category_id`,
                        `o`.`kode_buku` AS `kode_buku`,
                        `q`.`name` AS type,
                        `q`.`alias` AS type_alias,
                        `q`.`id_category` AS type_id
                    FROM `product` `o`
                    JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
                    JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                    WHERE 1
                    AND `o`.`enable` = ?
                    AND `o`.`id_product` IN (
                        SELECT `a`.`id_product`
                        FROM `category_product` `a`
                        WHERE 1
                        AND `a`.`id_category` = ?
                        AND `a`.`id_product` IN (
                            SELECT `aa`.`id_product`
                            FROM `category_product` `aa`
                            LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                            WHERE 1
                            AND `bb`.`id_category` IN ?
                        )
                    )
                    AND `o`.`kode_buku` IS NOT NULL
                    ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

            $query = $this->db->query($raw_query, [1, getenv('PARENT_KTSP'), $in_category]);

            if ($query) {
                $result = $query->result_array();
                $posts = [];
                $count = [];
                foreach ($result as $datas) {
                    $count[$datas['category_id']] = 0;
                }
                foreach ($result as $row) {
                    $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                    $count[$row['category_id']]++;
                }

                $fp = fopen('assets/data/json/'.$jenjang.'/2006.json', 'wb+');
                $d = fwrite($fp, json_encode($posts));

                fclose($fp);
                $query->free_result();

                if ($d) {
                    echo json_encode([
                        'success' => true,
                        'message' => base_url().'assets/data/json/'.$jenjang.'/2006.json',
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Gagal membuat file json!',
                    ]);
                }
            } else {
                exit($this->db->error());
            }

        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Pilih jenjang terlebih dahulu (1-6, 7-9, 10-12)!',
            ]);
        }
    }

    public function generateJsonPeminatan($jenjang = '10-12')
    {
        if ( ! $jenjang || $jenjang !== '10-12') {
            exit('Salah masukkan jenjang!');
        }

        $inCategory = explode(',', getenv('MINAT_SMK'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_MINAT'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/peminatan_smk.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/peminatan_smk.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJsonAllTeks($jenjang)
    {
        if ($jenjang) {
            if (in_array($jenjang, $this->list_jenjang, true)) {
                $raw_query = '
                        SELECT 
                            `o`.`id_product` AS `id_product`, 
                            `o`.`name` AS `name`, 
                            `o`.`reference` AS `isbn`, 
                            ROUND(`o`.`price_1`) AS `price_1`, 
                            ROUND(`o`.`price_2`) AS `price_2`, 
                            ROUND(`o`.`price_3`) AS `price_3`, 
                            ROUND(`o`.`price_4`) AS `price_4`, 
                            ROUND(`o`.`price_5`) AS `price_5`, 
                            `p`.`name` AS `category`, 
                            `p`.`id_category` AS `category_id`, 
                            `o`.`kode_buku` AS `kode_buku`,
                            `q`.`name` AS type,
                            `q`.`alias` AS type_alias,
                            `q`.`id_category` AS type_id
                        FROM `product` `o`
                        JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
                        JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                        WHERE 1
                        AND `o`.`enable` = ?
                        AND `o`.`id_product` IN (
                            SELECT `a`.`id_product`
                            FROM `category_product` `a`
                            WHERE 1
                            AND `a`.`id_product` IN (
                                SELECT `aa`.`id_product`
                                FROM `category_product` `aa`
                                LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                                WHERE 1
                                AND `bb`.`jenjang` = ?
                            )
                        )
                        AND `o`.`kode_buku` IS NOT NULL
                        ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

                $query = $this->db->query($raw_query, [1, $jenjang]);

                if ($query) {
                    $result = $query->result_array();
                    $posts = [];
                    $count = [];
                    foreach ($result as $datas) {
                        $count[$datas['category_id']] = 0;
                    }
                    foreach ($result as $row) {
                        $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                        $count[$row['category_id']]++;
                    }

                    $fp = fopen('assets/data/json/'.$jenjang.'/all_teks.json', 'wb+');
                    $d = fwrite($fp, json_encode($posts));

                    fclose($fp);
                    $query->free_result();

                    if ($d) {
                        echo json_encode([
                            'success' => true,
                            'message' => base_url().'assets/data/json/'.$jenjang.'/all_teks.json',
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Gagal membuat file json!',
                        ]);
                    }
                } else {
                    exit($this->db->error());
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Salah masukkan jenjang!',
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Pilih jenjang terlebih dahulu (1-6, 7-9, 10-12)!',
            ]);
        }
    }

    public function generateJsonAllTeksKonfirmasi($jenjang)
    {
        if ($jenjang) {
            if (in_array($jenjang, $this->list_jenjang, true)) {
                $raw_query = '
                        SELECT 
                            `o`.`id_product` AS `id_product`, 
                            `o`.`name` AS `name`, 
                            `o`.`reference` AS `isbn`, 
                            ROUND(`o`.`price_1`) AS `price_1`, 
                            ROUND(`o`.`price_2`) AS `price_2`, 
                            ROUND(`o`.`price_3`) AS `price_3`, 
                            ROUND(`o`.`price_4`) AS `price_4`, 
                            ROUND(`o`.`price_5`) AS `price_5`, 
                            `p`.`name` AS `category`, 
                            `p`.`id_category` AS `category_id`, 
                            `o`.`kode_buku` AS `kode_buku`,
                            `q`.`name` AS type,
                            `q`.`alias` AS type_alias,
                            `q`.`id_category` AS type_id
                        FROM `product` `o`
                        JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
                        JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
                        WHERE 1
                        AND `o`.`enable` = ?
                        AND `o`.`id_product` IN (
                            SELECT `a`.`id_product`
                            FROM `category_product` `a`
                            WHERE 1
                            AND `a`.`id_product` IN (
                                SELECT `aa`.`id_product`
                                FROM `category_product` `aa`
                                LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                                WHERE 1
                                AND `bb`.`jenjang` = ?
                            )
                        )
                        AND `o`.`kode_buku` IS NOT NULL
                        ORDER BY `p`.`id_parent`, `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

                $query = $this->db->query($raw_query, [1, $jenjang]);

                if ($query) {
                    $result = $query->result_array();
                    $posts = [];
                    $count = 0;
                    foreach ($result as $row) {
                        $posts[$count] = $row;
                        $count++;
                    }

                    $fp = fopen('assets/data/json/'.$jenjang.'/all_teks_konfirmasi.json', 'wb+');
                    $d = fwrite($fp, json_encode($posts));

                    fclose($fp);
                    $query->free_result();

                    if ($d) {
                        echo json_encode([
                            'success' => true,
                            'message' => base_url().'assets/data/json/'.$jenjang.'/all_teks_konfirmasi.json',
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Gagal membuat file json!',
                        ]);
                    }
                } else {
                    exit($this->db->error());
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Salah masukkan jenjang!',
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Pilih jenjang terlebih dahulu (1-6, 7-9, 10-12)!',
            ]);
        }
    }

    public function generateJsonLiterasi($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }

        $inCategory = explode(',', getenv('LITERASI'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_LITERASI'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/literasi.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/literasi.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJsonPengayaan($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }

        $inCategory = explode(',', getenv('PENGAYAAN'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_PENGAYAAN'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/pengayaan.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/pengayaan.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJsonReferensi($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }

        $inCategory = explode(',', getenv('REFERENSI'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_REFERENSI'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/referensi.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/referensi.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJsonPandik($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }

        $inCategory = explode(',', getenv('PANDIK'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_PANDIK'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/pandik.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/pandik.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    // public function generateJsonPendampingk13($jenjang = '', $type = 1)
    public function generateJsonPendampingk13($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }

        // switch ($jenjang) {
        //     case '1-6':
        //         $inCategory = (1 == $type) ? explode(',', getenv('PENDAMPING_K13_SD')): [];
        //         break;
        //     case '7-9':
        //         $inCategory = (1 == $type) ? explode(',', getenv('PENDAMPING_K13_SMP')): [];
        //         break;
        //     case '10-12':
        //         $inCategory = (1 == $type) ? explode(',', getenv('PENDAMPING_K13_SMA')): [];
        //         break;
        //     default:
        //         exit('Salah masukkan jenjang!');
        //         break;
        // }
        
        switch ($jenjang) {
            case '1-6':
                $inCategory = explode(',', getenv('PENDAMPING_K13_SD'));
                break;
            case '7-9':
                $inCategory = explode(',', getenv('PENDAMPING_K13_SMP'));
                break;
            case '10-12':
                $inCategory = explode(',', getenv('PENDAMPING_K13_SMA'));
                break;
            default:
                exit('Salah masukkan jenjang!');
                break;
        } 
        
        // $inCategory = explode(',', getenv('PENDAMPING_K13'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_PENDAMPING_K13'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/pendamping_k13.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/pendamping_k13.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJsonPeminatanSmaMa($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }

        switch ($jenjang) {
            case '1-6':
                $inCategory = explode(',', getenv('PEMINATAN_SD'));
                break;
            case '7-9':
                $inCategory = explode(',', getenv('PEMINATAN_SMP'));
                break;
            case '10-12':
                $inCategory = explode(',', getenv('PEMINATAN_SMA_MA'));
                break;
            default:
                exit('Salah masukkan jenjang!');
                break;
        } 

        // $inCategory = explode(',', getenv('PEMINATAN_SMA_MA'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_PEMINATAN_SMA_MA'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/peminatan_sma_ma.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/peminatan_sma_ma.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJsonHetk13($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }
        
        switch ($jenjang) {
            case '1-6':
                $inCategory = explode(',', getenv('HET_K13_SD'));
                break;
            case '7-9':
                $inCategory = explode(',', getenv('HET_K13_SMP'));
                break;
            case '10-12':
                $inCategory = explode(',', getenv('HET_K13_SMA'));
                break;
            default:
                exit('Salah masukkan jenjang!');
                break;
        } 
        
        // $inCategory = explode(',', getenv('PENDAMPING_K13'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_HET_K13'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/het_k13.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/het_k13.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJsonProductIt($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }

        $inCategory = explode(',', getenv('PRODUCT_IT'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_PRODUCT_IT'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/product_it.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/product_it.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJsonProductCovid($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }

        $inCategory = explode(',', getenv('PRODUCT_COVID'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_PRODUCT_COVID'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/product_covid.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/product_covid.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJsonAlatTulis($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }

        $inCategory = explode(',', getenv('ALAT_TULIS'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_ALAT_TULIS'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/alat_tulis.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/alat_tulis.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function generateJsonSmartLibrary($jenjang = '')
    {
        if ( ! $jenjang || $jenjang == '') {
            exit('Masukkan jenjang! contoh : 1-6 atau 7-9 atau 10-12');
        }

        $inCategory = explode(',', getenv('SMART_LIBRARY'));

        $raw_query = '
            SELECT 
                `o`.`id_product` AS `id_product`, 
                `o`.`name` AS `name`, 
                `o`.`reference` AS `isbn`, 
                ROUND(`o`.`price_1`) AS `price_1`, 
                ROUND(`o`.`price_2`) AS `price_2`, 
                ROUND(`o`.`price_3`) AS `price_3`, 
                ROUND(`o`.`price_4`) AS `price_4`, 
                ROUND(`o`.`price_5`) AS `price_5`, 
                `p`.`name` AS `category`, 
                `p`.`id_category` AS `category_id`,
                `o`.`kode_buku` AS `kode_buku`,
                `q`.`name` AS type,
                `q`.`alias` AS type_alias,
                `q`.`id_category` AS type_id
            FROM `product` `o`
            JOIN `category` `p` ON `p`.`id_category`=`o`.`id_category_default` AND `p`.`active`=1
            JOIN `category` `q` ON `q`.`id_category`=`p`.`id_parent`
            WHERE 1
            AND `o`.`enable` = ?
            AND `o`.`id_product` IN (
                SELECT `a`.`id_product`
                FROM `category_product` `a`
                WHERE 1
                AND `a`.`id_category` = ?
                AND `a`.`id_product` IN (
                    SELECT `aa`.`id_product`
                    FROM `category_product` `aa`
                    LEFT JOIN `category` `bb` ON (`aa`.`id_category` = `bb`.`id_category`)
                    WHERE 1
                    AND `bb`.`id_category` IN ? 
                )
            )
            AND `o`.`kode_buku` IS NOT NULL
            ORDER BY `p`.`id_category`, `o`.`sort_order`, `o`.`id_product` ASC';

        $query = $this->db->query($raw_query, [1, getenv('PARENT_SMART_LIBRARY'), $inCategory]);

        if ($query) {
            $result = $query->result_array();
            $posts = [];
            foreach ($result as $datas) {
                $count[$datas['category_id']] = 0;
            }
            foreach ($result as $row) {
                $posts[$row['type']][$row['category']][$count[$row['category_id']]] = $row;
                $count[$row['category_id']]++;
            }

            $fp = fopen('assets/data/json/'.$jenjang.'/smart_library.json', 'wb+');
            $d = fwrite($fp, json_encode($posts));

            fclose($fp);
            $query->free_result();

            if ($d) {
                echo json_encode([
                    'success' => true,
                    'message' => base_url().'assets/data/json/'.$jenjang.'/smart_library.json',
                ]);
            } else {
                echo json_encode(array('success' => false));
            }
        } else {
            exit($this->db->error());
        }
    }

    public function testMail()
    {
        $subject = 'Just for testing';
        $to = $this->input->get('email');
        $content = 'Just for testing....man';
        $this->load->library('mymail');
        $this->mymail->send($subject, $to, $content);
        echo json_encode(['success' => true]);
    }

    public function processMail()
    {
        $this->load->library('mymail');
        $this->mymail->processSpool();
    }
}
