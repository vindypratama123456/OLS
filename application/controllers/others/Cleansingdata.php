<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_cleansing $mod_cleansing
 * @property Mod_akunku $m_akunku
 * @property Authcustomer $authcustomer
 * @property ReCaptcha $recaptcha
 * @property Mymail $mymail
 */
class Cleansingdata extends CI_Controller
{
    public $periode;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_cleansing');
        $this->periode = (int)env('PERIODE');
    }

    public function generateReference()
    {
        return generateRandomString();
    }

    public function repairStok($id_transaksi, $idSPK = null)
    {
        if ($id_transaksi) {
            $this->db->trans_begin();
            $getSumData = $this->mod_cleansing->getAll("transaksi_detail", "sum(jumlah) as total_jumlah, sum(berat) as total_berat, sum(harga) as total_harga", "id_transaksi=" . $id_transaksi)[0];
            $dataTransaksi['total_jumlah'] = $getSumData->total_jumlah;
            $dataTransaksi['total_berat'] = $getSumData->total_berat;
            $dataTransaksi['total_harga'] = $getSumData->total_harga;
            // ubah transaksi
            $this->mod_cleansing->edit("transaksi", "id_transaksi=" . $id_transaksi, $dataTransaksi);
            if ($idSPK) {
                $dataSPKDetail['jumlah'] = $getSumData->total_jumlah;
                $dataSPKDetail['berat'] = $getSumData->total_berat;
                // ubah spk_detail
                $this->mod_cleansing->edit("spk_detail", "id_spk=" . $idSPK, $dataSPKDetail);
                $dataSPK['total_jumlah'] = $getSumData->total_jumlah;
                $dataSPK['total_berat'] = $getSumData->total_berat;
                // ubah spk
                $this->mod_cleansing->edit("spk", "id_spk=" . $idSPK, $dataSPK);
            }
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $callBack['message'] = "Repair stock successfull";
                echo json_encode($callBack);
            } else {
                $this->db->trans_rollback();
                $callBack['message'] = "Repair stock unsuccessfull";
                echo json_encode($callBack);
            }
        } else {
            $callBack['message'] = "Please input id_transaksi";
            echo json_encode($callBack);
        }
    }

    public function changeStok($idTransaksi)
    {
        if ( ! $idTransaksi) {
            echo "Berikan id transaksi";
        } else {
            $this->db->trans_begin();
            $getData = $this->mod_cleansing->getAll("transaksi", "*", "id_transaksi = " . $idTransaksi);
            foreach ($getData as $row => $dataTransaksi) {
                if ($dataTransaksi->status_transaksi < 3 || ($dataTransaksi->status_transaksi > 3 && $dataTransaksi->status_transaksi < 6)) {
                    // get detail transaksi
                    $getDataDetail = $this->mod_cleansing->getAll("transaksi_detail", "*", "id_transaksi=" . $dataTransaksi->id_transaksi);
                    // restore stock
                    $dataStock = [];
                    foreach ($getDataDetail as $rows => $dataTransaksiDetail) {
                        $currentStock = $this->mod_cleansing->getAll("info_gudang", "*", "periode = " . $this->periode . " and id_gudang = " . $dataTransaksi->asal . " and id_produk = " . $dataTransaksiDetail->id_produk)[0];
                        $dataStock['stok_booking'] = $currentStock->stok_booking - $dataTransaksiDetail->jumlah;
                        $dataStock['stok_available'] = $currentStock->stok_available + $dataTransaksiDetail->jumlah;
                        $this->mod_cleansing->edit("info_gudang", "periode = " . $this->periode . " and id_gudang = " . $dataTransaksi->asal . " and id_produk = " . $dataTransaksiDetail->id_produk, $dataStock);
                    }
                    // if spk has been created
                    if ($dataTransaksi->status_transaksi > 3 && $dataTransaksi->status_transaksi < 6) {
                        $idSPK = $this->mod_cleansing->getAll("spk_detail", "*", "id_transaksi = " . $dataTransaksi->id_transaksi)[0]->id_spk;
                        $getDetailSPK = $this->mod_cleansing->getList("spk_detail", "*", "id_spk = " . $idSPK);
                        if ($getDetailSPK == 1) {
                            $this->mod_cleansing->delete("spk_detail", "id_spk = " . $idSPK);
                            $this->mod_cleansing->delete("spk", "id_spk = " . $idSPK);
                        } else {
                            $this->mod_cleansing->delete("spk_detail", "id_transaksi = " . $dataTransaksi->id_transaksi);
                        }
                    }
                    // delete useless data
                    $this->mod_cleansing->delete("transaksi_history", "id_transaksi = " . $dataTransaksi->id_transaksi);
                    $this->mod_cleansing->delete("transaksi_detail", "id_transaksi = " . $dataTransaksi->id_transaksi);
                    $this->mod_cleansing->delete("transaksi", "id_transaksi = " . $dataTransaksi->id_transaksi);
                } elseif ($dataTransaksi->status_transaksi == 6) {
                    // get detail transaksi
                    $getDataDetail = $this->mod_cleansing->getAll("transaksi_detail", "*", "id_transaksi=" . $dataTransaksi->id_transaksi);
                    // restore stock
                    $dataStock = [];
                    foreach ($getDataDetail as $rows => $dataTransaksiDetail) {
                        $currentStock = $this->mod_cleansing->getAll("info_gudang", "*", "periode = " . $this->periode . " and id_gudang = " . $dataTransaksi->asal . " and id_produk = " . $dataTransaksiDetail->id_produk)[0];
                        $dataStock['stok_fisik'] = $currentStock->stok_fisik + $dataTransaksiDetail->jumlah;
                        $dataStock['stok_available'] = $currentStock->stok_available + $dataTransaksiDetail->jumlah;
                        $this->mod_cleansing->edit("info_gudang", "periode = " . $this->periode . " and id_gudang = " . $dataTransaksi->asal . " and id_produk = " . $dataTransaksiDetail->id_produk, $dataStock);
                    }
                    // delete spk data
                    $idSPK = $this->mod_cleansing->getAll("spk_detail", "*", "id_transaksi = " . $dataTransaksi->id_transaksi)[0]->id_spk;
                    $getDetailSPK = $this->mod_cleansing->getList("spk_detail", "*", "id_spk = " . $idSPK);
                    if ($getDetailSPK == 1) {
                        $this->mod_cleansing->delete("spk_detail", "id_spk = " . $idSPK);
                        $this->mod_cleansing->delete("spk", "id_spk = " . $idSPK);
                    } else {
                        $this->mod_cleansing->delete("spk_detail", "id_transaksi = " . $dataTransaksi->id_transaksi);
                    }
                    // delete useless data
                    $this->mod_cleansing->delete("transaksi_history", "id_transaksi = " . $dataTransaksi->id_transaksi);
                    $this->mod_cleansing->delete("transaksi_detail", "id_transaksi = " . $dataTransaksi->id_transaksi);
                    $this->mod_cleansing->delete("transaksi", "id_transaksi = " . $dataTransaksi->id_transaksi);
                } elseif ($dataTransaksi->status_transaksi == 3) {
                    // get detail transaksi
                    $getDataDetail = $this->mod_cleansing->getAll("transaksi_detail", "*", "id_transaksi=" . $dataTransaksi->id_transaksi);
                    $getDataTAG = $this->mod_cleansing->getAll("transaksi", "*", "ref_id=" . $dataTransaksi->id_transaksi);
                    $dataTAG = [];
                    foreach ($getDataTAG as $value) {
                        $detailTAG = $this->mod_cleansing->getAll('transaksi_detail', '*', 'id_transaksi=' . $value->id_transaksi);
                        foreach ($detailTAG as $valueData) {
                            $dataTAG[$valueData->id_produk] = $valueData->jumlah;
                        }
                    }
                    // restore stock
                    $dataStock = [];
                    foreach ($getDataDetail as $rows => $dataTransaksiDetail) {
                        $jumlah = $dataTransaksiDetail->jumlah;
                        foreach ($dataTAG as $idProduk => $jumlahProduk) {
                            if ($idProduk == $dataTransaksiDetail->id_produk) {
                                $jumlah = $dataTransaksiDetail->jumlah - $jumlahProduk;
                            }
                        }
                        $currentStock = $this->mod_cleansing->getAll("info_gudang", "*", "periode = " . $this->periode . " and id_gudang = " . $dataTransaksi->asal . " and id_produk = " . $dataTransaksiDetail->id_produk)[0];
                        $dataStock['stok_booking'] = $currentStock->stok_booking - $jumlah;
                        $dataStock['stok_available'] = $currentStock->stok_available + $jumlah;
                        $this->mod_cleansing->edit("info_gudang", "periode = " . $this->periode . " and id_gudang = " . $dataTransaksi->asal . " and id_produk = " . $dataTransaksiDetail->id_produk, $dataStock);
                    }
                    // delete useless data
                    $this->mod_cleansing->delete("transaksi_history", "id_transaksi = " . $dataTransaksi->id_transaksi);
                    $this->mod_cleansing->delete("transaksi_detail", "id_transaksi = " . $dataTransaksi->id_transaksi);
                    $this->mod_cleansing->delete("transaksi", "id_transaksi = " . $dataTransaksi->id_transaksi);
                }
            }
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $callBack['message'] = "Cleansing data successfull";
                echo json_encode($callBack);
            } else {
                $this->db->trans_rollback();
                $callBack['message'] = "Cleansing data unsuccessfull";
                echo json_encode($callBack);
            }
        }
    }
    
    public function cancelMultiTransaction($idTransaksi)
    {
        $idTransaksi = explode("-", $idTransaksi);
        foreach ($idTransaksi as $id_transaksi) {
            $this->changeStok($id_transaksi);
        }
    }

    public function fixStokBooking()
    {
        $this->db->trans_begin();
        
        $info_gudang = $this->mod_cleansing->getRealStockBooking();

        foreach ($info_gudang as $data) {
            $id                 = (int)$data['id'];
            $id_produk          = (int)$data['id_produk'];
            $id_gudang          = (int)$data['id_gudang'];
            $stok_fisik         = (int)$data['stok_fisik'];
            $stok_booking       = (int)$data['stok_booking'];
            $stok_available     = (int)$data['stok_available'];
            $real_booking       = (int)$data['real_booking'];
            $new_data           = [];

            if ($stok_booking !== $real_booking) {
                if ($stok_fisik < $real_booking) {
                    $stok_fisik = $real_booking;
                }

                $new_data = [
                    'stok_fisik'        => $stok_fisik,
                    'stok_booking'      => $real_booking,
                    'stok_available'    => ($stok_fisik - $real_booking)
                ];

                $this->mod_cleansing->edit("info_gudang", "id = $id", $new_data);
            }
        }

        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Fixing stok info gudang successfull";
            echo json_encode($callBack);
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Fixing stok info gudang unsuccessfull";
            echo json_encode($callBack);
        }
    }
    
    public function fixStokKirim()
    {
        $this->db->trans_begin();
        
        $info_gudang = $this->mod_cleansing->getRealStockKirim();

        foreach ($info_gudang as $data) {
            $id                 = (int)$data['id'];
            $id_produk          = (int)$data['id_produk'];
            $id_gudang          = (int)$data['id_gudang'];
            $stok_fisik         = (int)$data['stok_fisik'];
            $stok_booking       = (int)$data['stok_booking'];
            $stok_available     = (int)$data['stok_available'];
            $real_kirim         = (int)($data['real_kirim_1'] + $data['real_kirim_2']);
            $new_data           = [];

            if ($real_kirim !== 0) {
                $new_fisik      = $stok_fisik - $real_kirim;
                $new_booking    = $stok_booking - $real_kirim;
                $new_data       = [
                    'stok_fisik'        => $new_fisik,
                    'stok_booking'      => $new_booking,
                    'stok_available'    => ($new_fisik - $new_booking)
                ];

                $this->mod_cleansing->edit("info_gudang", "id = $id", $new_data);
            }
        }

        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Fixing stok kirim successfull";
            echo json_encode($callBack);
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Fixing stok kirim unsuccessfull";
            echo json_encode($callBack);
        }
    }
    
    public function injectStockStatus()
    {
        $this->db->trans_begin();
        
        $today          = date("Y-m-d H:i:s");
        $month          = date('n');
        $year           = date('Y');

        $info_gudang    = $this->mod_cleansing->getFirstStockStatus();
        $new_data       = [];

        foreach ($info_gudang as $data) {
            $id                 = (int)$data['id'];
            $id_produk          = (int)$data['id_produk'];
            $id_gudang          = (int)$data['id_gudang'];
            $stok_fisik         = (int)$data['stok_fisik'];
            $stok_booking       = (int)$data['stok_booking'];
            $stok_available     = (int)$data['stok_available'];
            $stok_lunas         = (int)$data['stok_lunas'];
            $stok_belum_lunas   = (int)$data['stok_belum_lunas'];
            $hpp                = (int)$data['hpp'];

            $new_fisik          = $stok_fisik - $stok_lunas + $stok_belum_lunas;
            $new_booking        = $stok_booking - $stok_lunas + $stok_belum_lunas;
            $new_available      = $new_fisik - $new_booking;
            $new_total_cost     = $new_fisik * $hpp;
            $new_alloc_cost     = $new_booking * $hpp;

            $new_data[]         = [
                'id_periode'        => 4,
                'id_gudang'         => $id_gudang,
                'id_produk'         => $id_produk,
                'bulan'             => $month,
                'tahun'             => $year,
                'tgl_transaksi'     => $today,
                'stok_fisik'        => $new_fisik,
                'stok_booking'      => $new_booking,
                'stok_available'    => $new_available,
                'average_cost'      => $hpp,
                'total_cost'        => $new_total_cost,
                'allocated_cost'    => $new_alloc_cost,
                'created_date'      => $today,
                'updated_date'      => $today
            ];
        }

        $this->mod_cleansing->addBatch('report_stock_status', $new_data);

        if ($this->db->trans_status() === true) {
            $this->db->trans_commit();
            $callBack['message'] = "Inject stock status report successfull";
            echo json_encode($callBack);
        } else {
            $this->db->trans_rollback();
            $callBack['message'] = "Inject stock status report unsuccessfull";
            echo json_encode($callBack);
        }
    }

    public function getReportStockStatus() {
        try {
            
            // get this month
            // jika gada bulan ini maka ambil bulan sebelumnya
            // query group by terus order by bulan desc 
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
