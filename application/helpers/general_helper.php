<?php
defined('BASEPATH') or exit('No direct script access allowed');

function this_ci()
{
    $ci =& get_instance();
    return $ci;
}

if ( ! function_exists('potongJudul')) {
    function potongJudul($text, $length)
    {
        $text = trim($text);
        $txtl = strlen($text);
        if ($txtl > $length) {
            for ($i = 1; $text[$length - $i] != " "; $i++) {
                if ($i == $length) {
                    return substr($text, 0, $length);
                }
            }
            $text = substr($text, 0, $length - $i + 1);
        }
        return $text;
    }
}

if ( ! function_exists('rupiah')) {
    function rupiah($nilai, $pecahan = 0, $type = 1)
    {
        if ($type == 1) {
            return number_format($nilai, $pecahan, ',', '.');
        } else if ($type == 2) {
            return number_format($nilai, $pecahan);
        }
    }
}

if ( ! function_exists('error_form')) {
    function error_form($message)
    {
        return '<div role="alert" class="alert alert-danger">
                   <button data-dismiss="alert" class="close" type="button">
                       <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                   </button>
                   ' . $message . '
               </div>';
    }
}

if ( ! function_exists('notif')) {
    function notif($sts = 'success', $msg = '')
    {
        return '<div class="alert alert-' . $sts . ' alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    ' . $msg . '
                </div>';
    }
}

if ( ! function_exists('toRupiah')) {
    function toRupiah($harga)
    {
        return 'Rp. ' . number_format($harga, 0, ",", ".");
    }
}

if ( ! function_exists('generateRandomString')) {
    function generateRandomString($length = 9)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strtoupper($randomString);
    }
}

if ( ! function_exists('tanggalIndo')) {
    function tanggalIndo($type = 'all')
    {
        //Array Hari
        $array_hari = array(1 => "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu");
        $hari = $array_hari[date("N")];
        //Format Tanggal
        $tanggal = date("j");
        //Array Bulan
        $array_bulan = array(
            1 => "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        );
        $bulan = $array_bulan[date("n")];
        //Format Tahun
        $tahun = date("Y");
        //Menampilkan hari dan tanggal

        switch ($type) {
            case 'hari':
                $output = $hari;
                break;

            case 'tanggal':
                $output = $tanggal;
                break;

            case 'bulan':
                $output = $bulan;
                break;

            case 'tahun':
                $output = $tahun;
                break;

            default:
                $output = $hari . ", " . $tanggal . " " . $bulan . " " . $tahun;
                break;
        }

        echo $output;
    }
}

if ( ! function_exists('bulanIndo')) {
    function bulanIndo($bulan)
    {
        $bulan = $bulan ? $bulan : date('n');
        
        $array_bulan = array(
            1 => "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        );
        
        $output = $array_bulan[$bulan];        
        return $output;
    }
}

if ( ! function_exists('tgl_indo')) {
    function tgl_indo($tanggal, $type = null)
    {

        $tgl = substr($tanggal, 8, 2);
        $bln = substr($tanggal, 5, 2);
        $thn = substr($tanggal, 0, 4);

        switch ($bln) {
            case '01':
                $bln_nama = "Januari";
                break;
            case '02':
                $bln_nama = "Februari";
                break;
            case '03':
                $bln_nama = "Maret";
                break;
            case '04':
                $bln_nama = "April";
                break;
            case '05':
                $bln_nama = "Mei";
                break;
            case '06':
                $bln_nama = "Juni";
                break;
            case '07':
                $bln_nama = "Juli";
                break;
            case '08':
                $bln_nama = "Agustus";
                break;
            case '09':
                $bln_nama = "September";
                break;
            case '10':
                $bln_nama = "Oktober";
                break;
            case '11':
                $bln_nama = "November";
                break;
            default:
                $bln_nama = "Desember";
        }

        switch ($type) {
            case 2:
                $data = $tgl . ' ' . $bln_nama . ' ' . $thn;
                break;
            case 4:
                $data = $thn . '/' . $bln . '/' . $tgl;
                break;
            case 5:
                $data = $tgl . '-' . $bln . '-' . $thn;
                break;
            case 7:
                $data = $tgl . '/' . $bln . '/' . $thn;
                break;
            case 8:
                $data = $bln . '/' . $tgl . '/' . $thn;
                break;
            case 9:
                $data = $tgl . '.' . $bln . '.' . $thn;
                break;
        }

        return $data;
    }
}

if ( ! function_exists('get_data')) {
    function get_data($param)
    {
        $query = this_ci()->db->query("SELECT " . $param['field'] . " FROM " . $param['table'] . " WHERE " . $param['key'] . "=" . this_ci()->db->escape($param['data']) . " LIMIT 1");
        if ($query->num_rows() > 0) {
            return $query->row($param['field']);
        }
        return false;
    }
}

if ( ! function_exists('kekata')) {
    function kekata($x)
    {
        $x = abs($x);
        $angka = array(
            "",
            "satu",
            "dua",
            "tiga",
            "empat",
            "lima",
            "enam",
            "tujuh",
            "delapan",
            "sembilan",
            "sepuluh",
            "sebelas"
        );
        $temp = "";
        if ($x < 12) {
            $temp = " " . $angka[$x];
        } elseif ($x < 20) {
            $temp = kekata($x - 10) . " belas";
        } elseif ($x < 100) {
            $temp = kekata($x / 10) . " puluh" . kekata($x % 10);
        } elseif ($x < 200) {
            $temp = " seratus" . kekata($x - 100);
        } elseif ($x < 1000) {
            $temp = kekata($x / 100) . " ratus" . kekata($x % 100);
        } elseif ($x < 2000) {
            $temp = " seribu" . kekata($x - 1000);
        } elseif ($x < 1000000) {
            $temp = kekata($x / 1000) . " ribu" . kekata($x % 1000);
        } elseif ($x < 1000000000) {
            $temp = kekata($x / 1000000) . " juta" . kekata($x % 1000000);
        } elseif ($x < 1000000000000) {
            $temp = kekata($x / 1000000000) . " milyar" . kekata(fmod($x, 1000000000));
        } elseif ($x < 1000000000000000) {
            $temp = kekata($x / 1000000000000) . " triliun" . kekata(fmod($x, 1000000000000));
        }
        return $temp;
    }
}

if ( ! function_exists('tkoma')) {
    function tkoma($x)
    {
        $x = stristr($x, '.');
        $angka = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan");
        $temp = "";
        $pjg = strlen($x);
        $pos = 1;

        while ($pos < $pjg) {
            $char = substr($x, $pos, 1);
            $pos++;
            $temp .= " " . $angka[$char];
        }

        return $temp;
    }
}

if ( ! function_exists('terbilang')) {
    function terbilang($x)
    {
        if ($x < 0) {
            $hasil = "minus " . trim(kekata($x));
        } else {
            $poin = false; // trim(tkoma($x));
            $hasil = trim(kekata($x));
        }

        if ($poin) {
            $hasil = $hasil . " koma " . $poin;
        } else {
            $hasil = $hasil;
        }

        return ucwords($hasil . ' rupiah');
    }
}

if ( ! function_exists('selisihHari')) {
    function selisihHari($tgl1, $tgl2, $format = '%a')
    {
        $waktu1 = date_create($tgl1);
        $waktu2 = date_create($tgl2);
        $interval = date_diff($waktu1, $waktu2);
        return $interval->format($format);
    }
}

if ( ! function_exists('tglFaktur')) {
    function tglFaktur($tanggal)
    {
        $thn = substr($tanggal, 0, 4);
        $bln = substr($tanggal, 5, 2);
        $tgl = substr($tanggal, 8, 2);
        $data = $bln . '/' . $tgl . '/' . $thn;
        return $data;
    }
}

if ( ! function_exists('error_form')) {
    function error_form($message)
    {
        return '<div role="alert" class="alert alert-danger">
                    <button data-dismiss="alert" class="close" type="button">
                        <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                    </button>
                    ' . $message . '
                </div>';
    }
}

if ( ! function_exists('arrSSLContext')) {
    function arrSSLContext()
    {
        $arrSSLContext = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ];
        return $arrSSLContext;
    }
}

if ( ! function_exists('ajaxResponse')) {
    function ajaxResponse($statusCode, $callBack = array())
    {
        this_ci()->output
                 ->set_status_header($statusCode)
                 ->set_content_type('application/json', 'utf-8')
                 ->set_output(json_encode($callBack));
    }
}

if ( ! function_exists('strposArr')) {
    function strposArr($haystack, $needles = array(), $offset = 0)
    {
        $chr = array();
        foreach ($needles as $needle) {
            $res = strpos($haystack, $needle, $offset);
            if ($res !== false) {
                $chr[$needle] = $res;
            }
        }
        if (empty($chr)) {
            return false;
        }
        return min($chr);
    }
}

if ( ! function_exists('env')) {
    function env($varname) {
        return getenv($varname);
    }
}
