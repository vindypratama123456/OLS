<?php
defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;

/**
 * @property Mod_general $mod_general
 * @property Mod_comission $mod_comission
 * @property Mod_mitra $mod_mitra
 */
class Comission extends CI_Controller
{
    private $client;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mod_general');
        $this->load->model('mod_comission');
        $this->load->model('mod_mitra');

        $this->client = new Client([
            'base_uri' => (string) env('PD_API_URL'),
            'timeout'  => 10.0,
        ]);
    }

    public function index()
    {
        echo "Fungsi sudah tidak tersedia";
        return false;
        exit();

        $this->updatePaidStatus();
    }

    public function updatePaidStatus($date = false)
    {
        echo "Fungsi sudah tidak tersedia";
        return false;
        exit();

        $paidDate = $date ? $date : date('Y-m-d');
        $adm_id = 76;
        $now = date('Y-m-d H:i:s');
        $this->db->trans_begin();
        $dataComission = $this->mod_general->getAll("payout_detail", "", "transfer_date='$paidDate' AND status=3","transfer_date asc, no_pd asc");
        if ($dataComission) {
            try {
                $tmpNoPD = [];
                $tmpIDPayout = [];
                foreach ($dataComission as $itemHeader) {
                    $dataPayoutComission = [
                        'status' => 4,
                        'modified_date' => $now,
                        'modified_by' => $adm_id
                    ];
                    $tipe = $itemHeader->type == 1 ? 'langsung' : 'referensi';
                    $paidDate = $itemHeader->transfer_date;
                    $notes = 'Transfer dana komisi ' . $tipe . ' (' . $paidDate . ')';
                    $this->mod_general->updateData('payout_detail', $dataPayoutComission, 'id', $itemHeader->id);
                    $this->mod_comission->addHistory($itemHeader->id, $adm_id, 4, $notes);
                    $payoutComissionDetail = $this->mod_comission->getPayoutComissionDetail($itemHeader->no_pd, $itemHeader->id_employee);
                    $totalAmount = 0;
                    foreach ($payoutComissionDetail as $itemDetail) {
                        if ( ! in_array($itemHeader->no_pd, $tmpNoPD)) {
                            $tmpNoPD[] = $itemHeader->no_pd;
                            // $this->postPDHeader($itemHeader);
                            $sequence = 1;
                        }
                        if ( ! in_array($itemDetail['id'], $tmpIDPayout)) {
                            $tmpIDPayout[] = $itemDetail['id'];
                            $totalAmount += $itemDetail['total_amount'];
                            $dataDetail = [
                                'hpd_no' => $itemHeader->no_pd,
                                'dpd_seq_no' => $sequence,
                                'dpd_amount' => $totalAmount,
                                'dpd_note' => ' (' . $itemDetail['persen_komisi'] * 100 . '%) Pesanan #' . $itemDetail['kode_pesanan'],
                                'dpd_atas_nama' => strtoupper($itemDetail['nama']),
                                'dpd_due_date' => tgl_indo($itemHeader->transfer_date, 8),
                                'dpd_transfer_atas_nama' => strtoupper($itemDetail['nama_rekening']),
                                'dpd_bank_name' => strtoupper($itemDetail['alias_bank']),
                                'dpd_bank_address' => '',
                                'dpd_bank_city' => '',
                                'dpd_bank_account' => $itemDetail['no_rekening'],
                                'sandi_bi' => $itemDetail['kode_bank'],
                            ];
                            // $this->postPDDetail($dataDetail);
                            $sequence++;
                        }
                    }
                }
                if ($this->db->trans_status() === true) {
                    // $this->postPDValidasi();
                    $this->db->trans_commit();
                    $callBack['message'] = "Update comission paid status successfully";
                } else {
                    $this->db->trans_rollback();
                    $callBack['message'] = "Failed to update comission paid status :(";
                }
            } catch (Exception $e) {
                $callBack = [
                    'success' => true,
                    'message' => $e->getMessage(),
                ];
            }
            echo json_encode($callBack);
        } else {
            $callBack['message'] = "Sorry, nothing found";
        }
        echo json_encode($callBack);
    }

    public function sendPesananDana($noPd = false)
    {
        echo "Fungsi sudah tidak tersedia";
        return false;
        exit();
        
        $dataComission = $this->mod_general->getAll("payout_detail", "", "no_pd='$noPd'");
        if ($dataComission) {
            $tmpNoPD = [];
            $tmpIDPayout = [];
            foreach ($dataComission as $itemHeader) {
                $payoutComissionDetail = $this->mod_comission->getPayoutComissionDetail($itemHeader->no_pd, $itemHeader->id_employee);
                $totalAmount = 0;
                foreach ($payoutComissionDetail as $itemDetail) {
                    if (!in_array($itemHeader->no_pd, $tmpNoPD)) {
                        $tmpNoPD[] = $itemHeader->no_pd;
                        $this->postPDHeader($itemHeader);
                        $sequence = 1;
                    }
                    if (!in_array($itemDetail['id'], $tmpIDPayout)) {
                        $tmpIDPayout[] = $itemDetail['id'];
                        $totalAmount += $itemDetail['total_amount'];
                        $dataDetail = [
                            'hpd_no'                    => $itemHeader->no_pd,
                            'dpd_seq_no'                => $sequence,
                            'dpd_amount'                => $totalAmount,
                            'dpd_note'                  => ' (' . $itemDetail['persen_komisi']*100 . '%) Pesanan #' . $itemDetail['kode_pesanan'],
                            'dpd_atas_nama'             => strtoupper($itemDetail['nama']),
                            'dpd_due_date'              => tgl_indo($itemHeader->transfer_date, 8),
                            'dpd_transfer_atas_nama'    => strtoupper($itemDetail['nama_rekening']),
                            'dpd_bank_name'             => strtoupper($itemDetail['alias_bank']),
                            'dpd_bank_address'          => '',
                            'dpd_bank_city'             => '',
                            'dpd_bank_account'          => $itemDetail['no_rekening'],
                            'sandi_bi'                  => $itemDetail['kode_bank'],
                        ];
                        $this->postPDDetail($dataDetail);
                        $sequence++;
                    }
                }
            }
            $this->postPDValidasi();
            $callBack = [
                'success' => true,
                'message' => "Pesanan dana: #$noPd successfully sent.",
            ];
        } else {
            $callBack = [
                'success' => false,
                'message' => "Sorry, nothing found.",
            ];
        }
        echo json_encode($callBack);
    }

    public function postPDHeader($array)
    {
        if ( ! empty($array)) {
            $header = [
                'hpd_type' => env('HPD_TYPE'),
                'hpd_unit' => env('HPD_UNIT'),
                'hpd_kelompok' => env('HPD_KELOMPOK'),
                'hpd_bagian' => env('HPD_BAGIAN'),
                'hpd_beban_unit' => env('HPD_BEBAN_UNIT'),
                'hpd_note' => env('HPD_NOTE'),
                'hpd_penyusun' => env('HPD_PENYUSUN'),
                'hpd_dir_kel' => env('HPD_DIR_KEL'),
                'hpd_jabdirkel' => env('HPD_JABDIRKEL'),
                'hpd_jabdirkeu' => env('HPD_JABDIRKEU'),
                'hpd_nik_penyusun' => env('HPD_NIK_PENYUSUN'),
                'hpd_sign' => env('HPD_SIGN'),
                'LegacyId' => env('LEGACYID'),
            ];
            $this->client->request('POST', '/api/PDHeader', [
                'json' => [
                    'hpd_no' => $array->no_pd,
                    'hpd_trx_date' => tgl_indo($array->created_date, 8),
                    'hpd_type' => $header['hpd_type'],
                    'hpd_unit' => $header['hpd_unit'],
                    'hpd_kelompok' => $header['hpd_kelompok'],
                    'hpd_bagian' => $header['hpd_bagian'],
                    'hpd_beban_unit' => $header['hpd_beban_unit'],
                    'hpd_due_date' => tgl_indo($array->transfer_date, 8),
                    'hpd_note' => $header['hpd_note'],
                    'hpd_pemesan' => '',
                    'hpd_penyusun' => $header['hpd_penyusun'],
                    'hpd_dir_kel' => $header['hpd_dir_kel'],
                    'hpd_dir_keu' => '',
                    'hpd_jabdirkel' => $header['hpd_jabdirkel'],
                    'hpd_jabdirkeu' => $header['hpd_jabdirkeu'],
                    'hpd_nik_penyusun' => $header['hpd_nik_penyusun'],
                    'hpd_kolektif_id' => $array->no_pd_kolektif,
                    'hpd_sign' => $header['hpd_sign'],
                    'LegacyId' => $header['LegacyId'],
                ]
            ]);
        } else {
            return false;
        }
    }

    public function postPDDetail($array)
    {
        if ( ! empty($array)) {
            $detail = [
                'dpd_pay_id' => env('DPD_PAY_ID'),
                'dpd_currency' => env('DPD_CURRENCY'),
                'dpd_biaya_transfer' => 0,
                'dpd_note' => env('DPD_NOTE'),
                'dpd_curr_account' => env('DPD_CURR_ACCOUNT'),
                'dpd_biaya_transfer_flag' => 0,
                'LegacyId' => env('LEGACYID'),
            ];
            $this->client->request('POST', '/api/PDDetail', [
                'json' => [
                    'hpd_no' => $array['hpd_no'],
                    'dpd_seq_no' => $array['dpd_seq_no'],
                    'dpd_pay_id' => $detail['dpd_pay_id'],
                    'dpd_currency' => $detail['dpd_currency'],
                    'dpd_amount' => $array['dpd_amount'],
                    'dpd_biaya_transfer' => $detail['dpd_biaya_transfer'],
                    'dpd_note' => $detail['dpd_note'] . $array['dpd_note'],
                    'dpd_atas_nama' => $array['dpd_atas_nama'],
                    'dpd_due_date' => $array['dpd_due_date'],
                    'dpd_bg_atas_nama' => '',
                    'dpd_transfer_atas_nama' => $array['dpd_transfer_atas_nama'],
                    'dpd_bank_name' => $array['dpd_bank_name'],
                    'dpd_bank_address' => $array['dpd_bank_address'],
                    'dpd_bank_city' => $array['dpd_bank_city'],
                    'dpd_bank_account' => $array['dpd_bank_account'],
                    'dpd_bank_iban' => '',
                    'dpd_curr_account' => $detail['dpd_curr_account'],
                    'dpd_draft_atas_nama' => '',
                    'dpd_draft_address' => '',
                    'dpd_draft_country' => '',
                    'dpd_biaya_transfer_flag' => $detail['dpd_biaya_transfer_flag'],
                    'dpd_bank_swift_code' => '',
                    'dpd_bank_sort_code' => '',
                    'dpd_bank_branch_id_code' => '',
                    'dpd_bank_vat' => '',
                    'dpd_bank_aba_routing' => '',
                    'sandi_bi' => $array['sandi_bi'],
                    'LegacyId' => $detail['LegacyId'],
                ]
             ]);
        } else {
            return false;
        }
    }

    public function postPDValidasi()
    {
        if ( ! empty(env('LEGACYID'))) {
            $this->client->request('POST', '/api/ValidasiPD', [
                'form_params' => [
                    'LegacyId' => env('LEGACYID'),
                ]
            ]);
        } else {
            return false;
        }
    }
}
