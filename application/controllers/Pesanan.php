<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Authcustomer $authcustomer
 * @property Mod_general $mod_general
 * @property Mod_order $Mod_order
 * @property Mod_pesanan $m_pesanan
 * @property Mod_akunku $m_akunku
 * @property Mod_buku $m_buku
 * @property Excel $excel
 * @property Mymail $mymail
 * @property Dompdf_gen $dompdf_gen
 */
class Pesanan extends CI_Controller
{
    private $userJenjang;

    public function __construct()
    {
        parent::__construct();
        $this->authcustomer->restrict();
        $this->load->model('Mod_pesanan', 'm_pesanan');
        $this->load->model('Mod_general');
        $this->load->model('Mod_order');
        $this->userJenjang = $this->session->userdata('jenjang');
    }

    public function index()
    {
        $data['title'] = 'Daftar Pesanan Saya &raquo; Gramedia';
        $data['pesanan'] = $this->m_pesanan->getPesanan();
        $this->load->view('tshops/pesanan/list', $data);
    }

    public function detail($idOrder)
    {
        if ($this->isHaveAccess($idOrder)) {
            $data['title'] = 'Detail pesanan &raquo; Gramedia';
            $data['pesanan'] = $this->m_pesanan->getPesananInfo($idOrder);
            $data['detailpesanan'] = $this->m_pesanan->getDetailPesanan($idOrder);
            $data['liststatus'] = $this->Mod_order->getListStatus($idOrder);
            $data['isCommented'] = $this->m_pesanan->is_commented($idOrder);
            $data['feedback'] = $this->m_pesanan->getFeedback($idOrder);
            if ($data['pesanan'] && $data['detailpesanan']) {
                $this->load->view('tshops/pesanan/detail', $data);
            } else {
                redirect('pesanan', 'refresh');
            }
        } else {
            redirect('pesanan', 'refresh');
        }
    }

    private function isHaveAccess($id)
    {
        $query = $this->db->query('SELECT `id_customer` FROM `orders` WHERE `id_order`='.$this->db->escape($id));
        if ($query->num_rows() > 0) {
            return $query->row('id_customer') == $this->session->userdata('id_customer');
        }

        return false;
    }

    public function formpesanan()
    {
        $this->session->unset_userdata('order');
        $this->load->model('Mod_akunku', 'm_akunku');

        $data['customer'] = $this->m_akunku->getDetail($this->session->userdata('id_customer'));
        $data['title'] = 'Isi Form Pesanan &raquo; Gramedia';
        $bentuk = $this->session->userdata('data_user')['bentuk_pendidikan'];
        // $is_k_13 = $this->session->userdata('data_user')['user_k13'];

        $list_pendamping_k13 = array();
        $list_het_k13 = array();

        $is_allowed = 1;
        if ($this->userJenjang == '1-6') {
            $is_allowed = $this->m_akunku->getAll('master_kabupaten_zona', 'is_allowed_sd',
                'kabupaten = "'.$data['customer']->kabupaten.'"')[0]->is_allowed_sd;
        }

        if ($is_allowed > 0) {
            $data['list_books'] = [];
            $list1 = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/2013_1.json', false, stream_context_create(arrSSLContext())), true);
            $list_literasi = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/literasi.json', false, stream_context_create(arrSSLContext())), true);
            $list_pengayaan = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/pengayaan.json', false, stream_context_create(arrSSLContext())), true);
            $list_referensi = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/referensi.json', false, stream_context_create(arrSSLContext())), true);
            $list_pandik = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/pandik.json', false, stream_context_create(arrSSLContext())), true);
            $list_product_it = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/product_it.json', false, stream_context_create(arrSSLContext())), true);
            $list_product_covid = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/product_covid.json', false, stream_context_create(arrSSLContext())), true);
            $list_alat_tulis = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/alat_tulis.json', false, stream_context_create(arrSSLContext())), true);
            $list_smart_library = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/smart_library.json', false, stream_context_create(arrSSLContext())), true);
            $list_peminatan_sma_ma = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/peminatan_sma_ma.json', false, stream_context_create(arrSSLContext())), true);
            $list_pendamping_k13 = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/pendamping_k13.json', false, stream_context_create(arrSSLContext())), true); 
            $list_het_k13 = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/het_k13.json', false, stream_context_create(arrSSLContext())), true); 
            // if(strtoupper($is_k_13) === 'YA')
            // {
            // 
                  
            // }
            if ($this->userJenjang == '1-6') {
                // $data['list_books'] = array_merge($list1);
                $data['list_books'] = array_merge($list1, $list_literasi, $list_pengayaan, $list_referensi, $list_pandik, $list_product_it, $list_product_covid, $list_alat_tulis, $list_smart_library, $list_peminatan_sma_ma, $list_pendamping_k13, $list_het_k13);
            } else {
                $list2 = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/2006.json',
                    false, stream_context_create(arrSSLContext())), true);
                if (strpos($bentuk, 'SMK') !== false) {
                    $list3 = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/peminatan_smk.json',
                        false, stream_context_create(arrSSLContext())), true);
                    // $data['list_books'] = array_merge($list1, $list2, $list3);
                    $data['list_books'] = array_merge($list1, $list2, $list3, $list_literasi, $list_pengayaan, $list_referensi, $list_pandik, $list_product_it, $list_product_covid, $list_alat_tulis, $list_smart_library, $list_peminatan_sma_ma, $list_pendamping_k13, $list_het_k13);
                } else if (strpos($bentuk, 'SMA') !== false) {
                    $list3 = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/peminatan_sma_ma.json', false, stream_context_create(arrSSLContext())), true);
                    // $data['list_books'] = array_merge($list1, $list2, $list3);
                    $data['list_books'] = array_merge($list1, $list2, $list3, $list_literasi, $list_pengayaan, $list_referensi, $list_pandik, $list_product_it, $list_product_covid, $list_alat_tulis, $list_smart_library, $list_peminatan_sma_ma, $list_pendamping_k13, $list_het_k13);
                }
                else {
                    // $data['list_books'] = array_merge($list1, $list2);
                    $data['list_books'] = array_merge($list1, $list2, $list_literasi, $list_pengayaan, $list_referensi, $list_pandik, $list_product_it, $list_product_covid, $list_alat_tulis, $list_smart_library, $list_peminatan_sma_ma, $list_pendamping_k13, $list_het_k13);
                }
            }

            $data['partner'] = $this->Mod_general->getAll('partner', '');
            $this->load->view('tshops/pesanan/formpesanan', $data);
        } else {
            $this->load->view('tshops/pesanan/formpesanan_empty', $data);
        }
    }

    public function konfirmasipesanan()
    {
        $idKorwil = $this->Mod_order->getKorwil($this->input->post('cust_kabupaten'),
                'a.id_employee')[0]['id_employee'] ?? null;
        $data['sales_representatif'] = $this->Mod_order->getSalesPerson($idKorwil, true) ?? null;
        $data['korwil'] = $this->Mod_order->getKorwil($this->input->post('cust_kabupaten'))[0] ?? null;
        $data['title'] = 'Konfirmasi Pesanan &raquo; Gramedia';

        if($this->input->post('reference_other'))
        {
            $data['reference_other'] = $this->input->post('reference_other');
            $data['reference_other_from'] = explode(":", $this->input->post("reference_other_from"))[1];
        }
        else
        {
            $data['reference_other'] = "";
            $data['reference_other_from'] = "";
        }

        $totalPay = 0;
        $pesanan = [];
        $count = 0;
        $zona = $this->session->userdata('zona');
        $price = 'price_'.$zona;

        $arrLiterasi = [];
        $arrPengayaan = []; 
        $arrReferensi = []; 
        $arrPandik = []; 
        $arrProductIt = []; 
        $arrProductCovid = [];    
        $arrAlatTulis = [];  
        $arrSmartLibrary = [];

        $list_wajib = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/all_teks_konfirmasi.json',
            false, stream_context_create(arrSSLContext())));
        $list_literasi = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/literasi.json',
            false, stream_context_create(arrSSLContext())));
        $list_pengayaan = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/pengayaan.json', false, stream_context_create(arrSSLContext())));
        $list_referensi = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/referensi.json', false, stream_context_create(arrSSLContext())));
        $list_pandik = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/pandik.json', false, stream_context_create(arrSSLContext())));
        $list_product_it = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/product_it.json', false, stream_context_create(arrSSLContext())));
        $list_product_covid = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/product_covid.json', false, stream_context_create(arrSSLContext())));
        $list_alat_tulis = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/alat_tulis.json', false, stream_context_create(arrSSLContext())));
        $list_smart_library = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/smart_library.json', false, stream_context_create(arrSSLContext())));

        if(!empty($list_literasi)){
            $arrLiterasi = $list_literasi->Literasi->{'Buku Literasi'};
        }

        if(!empty($list_pengayaan)){
            $arrPengayaan = $list_pengayaan->Pengayaan->{'Buku Pengayaan'};
        }

        if(!empty($list_referensi)){
            $arrReferensi = $list_referensi->Referensi->{'Buku Referensi'};
        }

        if(!empty($list_pandik)){
            $arrPandik = $list_pandik->{'Panduan Pendidik'}->{'Buku Pandik'};
        }

        if(!empty($list_product_it)){
            $arrProductIt = $list_product_it->{'Produk IT'}->{'Produk IT'};
        }

        if(!empty($list_product_covid)){
            $arrProductCovid = $list_product_covid->{'Produk Covid'}->{'Produk Covid'};
        }

        if(!empty($list_alat_tulis)){
            $arrAlatTulis = $list_alat_tulis->{'Alat Tulis'}->{'Alat Tulis'};
        }

        if(!empty($list_smart_library)){
            $arrSmartLibrary = $list_smart_library->{'Smart Library'}->{'Smart Library'};
        }
        // print_r($list_pengayaan);

        // $list_all = array_merge($list_wajib, $list_literasi->Literasi->{'Buku Literasi'}, $list_pengayaan->Pengayaan->{'Buku Pengayaan'}, $list_referensi->Referensi->{'Buku Referensi'}, $list_pandik->{'Panduan Pendidik'}->{'Buku Pandik'});
        $list_all = array_merge($list_wajib, $arrLiterasi, $arrPengayaan, $arrReferensi, $arrPandik, $arrProductIt, $arrProductCovid, $arrAlatTulis, $arrSmartLibrary);


        // foreach (json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/all_teks_konfirmasi.json', false, stream_context_create(arrSSLContext()))) as $bukubos) {
        foreach ($list_all as $bukubos) {
            $qty = $this->input->post('qty-'.$bukubos->id_product);
            $type = $bukubos->type_alias ?? $bukubos->type;
            if ($qty > 0) {
                $group_category = $bukubos->category.' ('.$bukubos->type.')';
                $pesanan[$group_category]['items'][$count] = [
                    'product_id' => $bukubos->id_product,
                    'kode_buku' => $bukubos->kode_buku,
                    'product_name' => $bukubos->name,
                    'isbn' => $bukubos->isbn,
                    'type' => $type,
                    'category' => $bukubos->category,
                    'product_quantity' => $qty,
                    'unit_price' => $bukubos->$price,
                    'total_price' => $bukubos->$price * $qty,
                ];
                $count++;
                $totalPay += $bukubos->$price * $qty;

                if (array_key_exists('total', $pesanan[$group_category])) {
                    $pesanan[$group_category]['total'] += $bukubos->$price * $qty;
                } else {
                    $pesanan[$group_category]['total'] = $bukubos->$price * $qty;
                }
            }
        }        

        $data['pesanan'] = $pesanan;
        $data['total_pay'] = $totalPay;
        $data['partner'] = $this->Mod_general->getAll('partner', '');
        $this->session->set_userdata('order', $pesanan);
        $this->session->set_userdata('total_pay', $totalPay);
        $this->load->view('tshops/pesanan/konfirmasipesanan', $data);
    }

    public function importfilepesanan()
    {
        $this->load->model('Mod_akunku', 'm_akunku');
        $data['customer'] = $this->m_akunku->getDetail($this->session->userdata('id_customer'));
        $data['title'] = 'Import file pesanan &raquo; Gramedia';
        $data['pesanan'] = $this->m_pesanan->getPesanan();
        $this->load->view('tshops/pesanan/importfilepesanan', $data);
    }

    public function konfirmasipesananupload()
    {
        $file = explode('.', $_FILES['file_csv_pesanan']['name']);
        $length = count($file);
        if (in_array(strtolower($file[$length - 1]), ['xls', 'xlsx'])) {
            $tmp = $_FILES['file_csv_pesanan']['tmp_name'];
            $this->load->library('excel');
            $read = PHPExcel_IOFactory::createReaderForFile($tmp);
            $read->setReadDataOnly(true);
            $excel = $read->load($tmp);
            $sheets = $read->listWorksheetNames($tmp);
            $count = 0;
            $pesanan = [];
            $totalPay = 0;
            $zona = $this->session->userdata('zona');
            $price = 'price_'.$zona;
            foreach ($sheets as $sheet) {
                $_sheet = $excel->setActiveSheetIndexByName($sheet);
                $maxRow = $_sheet->getHighestRow();
                $maxCol = $_sheet->getHighestColumn();
                $field = [];
                $sql = [];
                $maxCol = range('A', $maxCol);
                foreach ($maxCol as $key => $coloumn) {
                    $field[$key] = $_sheet->getCell($coloumn.'1')->getCalculatedValue();
                }
                for ($i = 2; $i <= $maxRow; $i++) {
                    foreach ($maxCol as $k => $coloumn) {
                        $sql[$field[$k]] = $_sheet->getCell($coloumn.$i)->getCalculatedValue();
                    }
                    $id = $sql[$field[0]];
                    $qty = $sql[$field[5]];
                    $category = $sql[$field[4]];
                    $this->load->model('mod_buku', 'm_buku');
                    $dataBuku = $this->m_buku->getDetailBuku($id);
                    if (($dataBuku !== '') && $qty > 0) {
                        $class_category = substr($category, 6, 2);
                        $pesanan[$class_category]['items'][$count] = [
                            'product_id' => $dataBuku[0]->id_product,
                            'kode_buku' => $dataBuku[0]->kode_buku,
                            'isbn' => $dataBuku[0]->reference,
                            'category' => $category,
                            'product_name' => $dataBuku[0]->name,
                            'product_quantity' => $qty,
                            'unit_price' => $dataBuku[0]->$price,
                            'total_price' => $dataBuku[0]->$price * $qty,
                        ];
                        $count++;
                        $totalPay += ($dataBuku[0]->$price * $qty);

                        if (array_key_exists('total', $pesanan[$class_category])) {
                            $pesanan[$class_category]['total'] += ($dataBuku[0]->$price * $qty);
                        } else {
                            $pesanan[$class_category]['total'] = ($dataBuku[0]->$price * $qty);
                        }
                    }
                }
            }
            $idKorwil = $this->Mod_order->getKorwil($this->input->post('cust_kabupaten'),
                'a.id_employee')[0]['id_employee'];
            $data['sales_representatif'] = $this->Mod_order->getSalesPerson($idKorwil);
            $data['korwil'] = $this->Mod_order->getKorwil($this->input->post('cust_kabupaten'))[0];
            $data['title'] = 'Konfirmasi Pesanan &raquo; Gramedia';
            $idKorwil = $this->Mod_order->getKorwil($this->input->post('cust_kabupaten'),
                'a.id_employee')[0]['id_employee'];
            $data['sales_representatif'] = $this->Mod_order->getSalesPerson($idKorwil);
            $data['korwil'] = $this->Mod_order->getKorwil($this->input->post('cust_kabupaten'))[0];
            $data['pesanan'] = $pesanan;
            $data['total_pay'] = $totalPay;
            $data['is_impor'] = true;
            $this->session->set_userdata('order', $pesanan);
            $this->session->set_userdata('total_pay', $totalPay);
            $this->load->view('tshops/pesanan/konfirmasipesanan', $data);
        } else {
            exit('File yang diizinkan adalah format .xls atau .xlsx');
        }
    }

    public function prosespesanan()
    {
        if ( ! $this->input->is_ajax_request()) {
            redirect('pesanan/formpesanan');
        }
        try {
            $totalPay = 0;
            $pesanan = [];
            $count = 0;
            $zona = $this->session->userdata('zona');
            
            // if($this->input->post('reference_other'))
            // {
            //     $updateOrders['reference_other'] = $this->input->post('reference_other');
            //     $updateOrders['reference_other_from'] = $this->input->post('reference_other_from');
            // }

            $price = 'price_'.$zona;

            $arrLiterasi = [];
            $arrPengayaan = []; 
            $arrReferensi = []; 
            $arrPandik = [];    
            $arrProductIt = [];  
            $arrProductCovid = []; 
            $arrAlatTulis = [];  
            $arrSmartLibrary = [];  

            $list_wajib = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/all_teks_konfirmasi.json',
            false, stream_context_create(arrSSLContext())));
            $list_literasi = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/literasi.json',
                false, stream_context_create(arrSSLContext())));
            $list_pengayaan = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/pengayaan.json',
                false, stream_context_create(arrSSLContext())));
            $list_referensi = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/referensi.json',
                false, stream_context_create(arrSSLContext())));
            $list_pandik = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/pandik.json',
                false, stream_context_create(arrSSLContext())));
            $list_product_it = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/product_it.json',
                false, stream_context_create(arrSSLContext())));
            $list_product_covid = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/product_covid.json',
                false, stream_context_create(arrSSLContext())));
            $list_alat_tulis = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/alat_tulis.json',
                false, stream_context_create(arrSSLContext())));
            $list_smart_library = json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/smart_library.json',
                false, stream_context_create(arrSSLContext())));

            if(!empty($list_literasi)){
                $arrLiterasi = $list_literasi->Literasi->{'Buku Literasi'};
            }
            if(!empty($list_pengayaan)){
                $arrPengayaan = $list_pengayaan->Pengayaan->{'Buku Pengayaan'};
            }
            if(!empty($list_referensi)){
                $arrReferensi = $list_referensi->Referensi->{'Buku Referensi'};
            }
            if(!empty($list_pandik)){
                $arrPandik = $list_pandik->{'Panduan Pendidik'}->{'Buku Pandik'};
            }
            if(!empty($list_product_it)){
                $arrProductIt = $list_product_it->{'Produk IT'}->{'Produk IT'};
            }
            if(!empty($list_product_covid)){
                $arrProductCovid = $list_product_covid->{'Produk Covid'}->{'Produk Covid'};
            }
            if(!empty($list_alat_tulis)){
                $arrAlatTulis = $list_alat_tulis->{'Alat Tulis'}->{'Alat Tulis'};
            }
            if(!empty($list_smart_library)){
                $arrSmartLibrary = $list_smart_library->{'Smart Library'}->{'Smart Library'};
            }

            // $list_all = array_merge($list_wajib, $list_literasi->Literasi->{'Buku Literasi'}, $list_pengayaan->Pengayaan->{'Buku Pengayaan'}, $list_referensi->Referensi->{'Buku Referensi'}, $list_pandik->{'Panduan Pendidik'}->{'Buku Pandik'});
            $list_all = array_merge($list_wajib, $arrLiterasi, $arrPengayaan, $arrReferensi, $arrPandik, $arrProductIt, $arrProductCovid, $arrAlatTulis, $arrSmartLibrary);

        // foreach (json_decode(file_get_contents(base_url().'assets/data/json/'.$this->userJenjang.'/all_teks_konfirmasi.json', false, stream_context_create(arrSSLContext()))) as $bukubos) {
            foreach ($list_all as $bukubos) {
                $qty = $this->input->post('qty-'.$bukubos->id_product);
                if ($qty > 0) {
                    $type = $bukubos->type_alias ?? $bukubos->type;
                    $pesanan[$bukubos->type_id][$bukubos->category_id]['pesanan'][$count] = [
                        'product_id' => $bukubos->id_product,
                        'kode_buku' => $bukubos->kode_buku,
                        'product_name' => $bukubos->name,
                        'type' => $type,
                        'isbn' => $bukubos->isbn,
                        'category' => $bukubos->category,
                        'product_quantity' => $qty,
                        'unit_price' => $bukubos->$price,
                        'total_price' => $bukubos->$price * $qty,
                    ];
                    $count++;
                    $totalPay += $bukubos->$price * $qty;
                    if (array_key_exists('total', $pesanan[$bukubos->type_id][$bukubos->category_id])) {
                        $pesanan[$bukubos->type_id][$bukubos->category_id]['total'] += $bukubos->$price * $qty;
                    } else {
                        $pesanan[$bukubos->type_id][$bukubos->category_id]['total'] = $bukubos->$price * $qty;
                    }
                }
            }

            $this->session->set_userdata('order', $pesanan);
            if ($this->session->userdata('order')) {
                $this->db->trans_begin();
                $this->load->library('mymail');
                $dataUser = $this->session->userdata('data_user');
                $propinsiSekolah = $dataUser['prov'];
                $kabkotaSekolah = $dataUser['kab'];
                $listKorwil = $this->Mod_order->getKorwil($kabkotaSekolah)[0];
                $listRSM = $this->Mod_order->getRSM($kabkotaSekolah)[0];
                $mitraPembantu = $this->input->post('sales_representatif');
                if ($mitraPembantu) {
                    $dataSales = $this->Mod_general->getAll('employee', '*', 'id_employee = '.$mitraPembantu)[0];
                    $updateOrders = [
                        'recommended_sales' => $dataSales->email,
                        'korwil_email' => $listKorwil['email'],
                        'korwil_name' => $listKorwil['name'],
                        'korwil_phone' => $listKorwil['telp'],
                    ];
                } else {
                    $updateOrders = [
                        'korwil_email' => $listKorwil['email'],
                        'korwil_name' => $listKorwil['name'],
                        'korwil_phone' => $listKorwil['telp'],
                    ];
                }
                $updateOrders['periode'] = date('Y');
                $updateOrders['rsm_name'] = $listRSM['name'];
                $to_email = ['pesananbuku@gramediaprinting.com'];
                $to_email[] = $listKorwil['email'];
                $to_email[] = $listRSM['email'];

                if($this->input->post('reference_other'))
                {
                    $updateOrders['reference_other'] = $this->input->post('reference_other');
                    $updateOrders['reference_other_from'] = explode(":", $this->input->post("reference_other_from"))[1];
                }


                // vindy 2019-08-22
                // Menambahkan function email blacklist
                $to_email = $this->getDataEmailBlacklist($to_email);

                $sendMail = [];
                $kodePesanan = [];
                foreach ($this->session->userdata('order') as $category => $data) {
                    foreach ($data as $class => $value) {
                        $order = $value['pesanan'];
                        $orderReference = generateRandomString();
                        $orderDetail = [];
                        $orderDetail['totalPay'] = $value['total'];
                        foreach ($order as $detail) {
                            $orderDetail['category'] = $detail['category'];
                            $orderDetail['type'] = $detail['type'];
                        }
                        $idOrder = $this->m_pesanan->tambahPesanan($orderReference, $orderDetail);
                        if ($this->m_pesanan->tambahDetailPesanan($order, $idOrder)) {
                            $this->m_pesanan->editPesanan($idOrder, $updateOrders);
                            $sendMail[$class]['subject'] = 'Pesanan #'.$orderReference.' dibuat oleh '.$this->session->userdata('school_name').' / '.$propinsiSekolah.' / '.$kabkotaSekolah;
                            $sendMail[$class]['content'] = '<p>Pesanan dengan Kode: <b>#'.$orderReference.'</b> baru saja dibuat oleh '.$this->session->userdata('school_name').' / '.$propinsiSekolah.' / '.$kabkotaSekolah.'</p><p>Nilai Pesanan: <b>'.toRupiah($totalPay).'</b></p><p>Segera lakukan konfirmasi dengan <a href ="'.base_url(ADMIN_PATH.'/orders/detail/'.$idOrder).'"><b>KLIK DISINI</b></a></p>';
                            $kodePesanan[] = $orderReference;
                        } else {
                            $this->db->trans_rollback();
                            $callBack = [
                                'success' => 'false',
                                'message' => 'Maaf, pesanan anda gagal DIBUAT! Silahkan coba beberapa saat lagi.',
                            ];
                            echo json_encode($callBack, true);
                            exit();
                        }

                        // blok coding update semester pada setiap order
                        $semester = $this->m_pesanan->get_semester($idOrder);
                        if($semester->num_rows() > 0)
                        {
                            foreach ($semester->result_array() as $data) {
                                // if($data['semester'] == null)
                                // {
                                    // $this->m_pesanan->upd_semester($idOrder,'');
                                // }
                                // else
                                // {
                                    $this->m_pesanan->upd_semester($idOrder, $data['semester']);
                                // }
                            }
                        }
                        // else
                        // {
                            // $this->m_pesanan->upd_semester($idOrder,'');
                        // }

                        // akhir blok coding update semester
                    }
                }
                if ($this->db->trans_status() == true) {
                    $this->db->trans_commit();

                    /* Ditutup. Fa, 20200319
                    foreach ($sendMail as $datas) {
                        $this->mymail->send($datas['subject'], $to_email, $datas['content']);
                    } */
                    $jmlPesanan = count($kodePesanan);
                    $kodePesanan = implode(', ', $kodePesanan);
                    $this->session->unset_userdata('order');
                    $this->session->set_flashdata('order_success',
                        '<div class="alert alert-success alert-dismissable">'.$jmlPesanan.' Pesanan anda dengan kode: <b>'.$kodePesanan.'</b> berhasil <b>DIBUAT</b>! Kami akan segera menghubungi Anda.</div>');
                    $callBack = [
                        'success' => 'true',
                        'message' => $jmlPesanan.' Pesanan anda dengan kode: '.$kodePesanan.' berhasil <b>DIBUAT',
                    ];
                } else {
                    $this->db->trans_rollback();
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Maaf, pesanan anda gagal DIBUAT! Silahkan coba beberapa saat lagi.',
                    ];
                }
            } else {
                $this->session->set_flashdata('message',
                    '<div class="alert alert-danger alert-dismissable">Maaf, pesanan anda gagal <b>dibuat</b>! Silahkan coba beberapa saat lagi.</div>');
                $callBack = [
                    'success' => 'false',
                    'message' => 'Maaf, pesanan anda gagal DIBUAT! Silahkan coba beberapa saat lagi.',
                ];
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => $e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    public function feedback()
    {
        $data['comment'] = $this->input->post('feedback');
        $data['rating'] = $this->input->post('rating');
        $data['id_order'] = $this->input->post('id_order');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        if ($this->m_pesanan->insertFeedback($data)) {
            redirect('pesanan/detail/'.$data['id_order'], 'refresh');
        }
    }

    public function popup_batal($id)
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        $data['detil'] = $this->Mod_general->detailData('orders', 'id_order', $id);
        $this->load->view('tshops/pesanan/batal_popup', $data);
    }

    public function batal()
    {
        if ( ! $this->input->is_ajax_request()) {
            return false;
        }

        try {
            $idOrder = $this->input->post('id_order');
            $reference = $this->input->post('reference');
            if ($this->isPending($idOrder) && $this->isHaveAccess($idOrder)) {
                $data = [
                    'current_state' => 2,
                    'alasan_batal' => $this->input->post('alasan_batal', true),
                    'date_upd' => date('Y-m-d H:i:s'),
                ];
                $proc = $this->Mod_general->updateData('orders', $data, 'id_order', $idOrder);
                if ($proc) {
                    $callBack = [
                        'success' => 'true',
                        'message' => 'Data successfully updated.',
                    ];
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-success alert-dismissable">Pesanan anda <b>#'.$reference.'</b> telah <b>DIBATALKAN</b>! Silahkan lakukan pesanan kembali sesuai dengan buku kebutuhan sekolah anda.</div>');
                } else {
                    $callBack = [
                        'success' => 'false',
                        'message' => 'Gagal memproses pesanan',
                    ];
                }
            } else {
                $callBack = [
                    'success' => 'false',
                    'message' => 'Tidak dapat memproses pesanan',
                ];
            }
            echo json_encode($callBack, true);
        } catch (Exception $e) {
            $callBack = [
                'success' => 'false',
                'message' => 'Caught exception: '.$e->getMessage(),
            ];
            echo json_encode($callBack, true);
        }
    }

    private function isPending($id)
    {
        $query = $this->db->query('SELECT `current_state` FROM `orders` WHERE `id_order`='.$this->db->escape($id));
        if ($query->num_rows() > 0) {
            return $query->row('current_state') == 1;
        }

        return false;
    }

    public function cetakInvoice($id_order)
    {
        if ($this->isHaveAccess($id_order)) {
            $data['pesanan'] = $this->m_pesanan->getPesananInfo($id_order);
            $data['detailpesanan'] = $this->m_pesanan->getDetailPesanan($id_order);
            if ($data['pesanan'] && $data['detailpesanan']) {
                $this->load->view('tshops/pesanan/print_invoice', $data);
            }
        }

        return false;
    }

    public function cetakPernyataan($reference, $stream = true)
    {
        if ($reference) {
            $data['detil'] = $this->Mod_general->detailData('orders', 'reference', $reference);
            if ($data['detil']) {
                $data['customer'] = $this->Mod_general->detailData('customer', 'id_customer',
                    $data['detil']['id_customer']);
                $data['category'] = $this->Mod_general->detailData('category', 'alias', $data['detil']['type']);
                $html = $this->load->view('admin/orders/print_pernyataan', $data, true);
                $postfix = strtolower(str_replace(' ', '_', str_replace('.', '', $data['customer']['school_name'])));
                $filename = $reference.'_'.$postfix; //save file name
                $pathfile = 'assets/data/pernyataan/';
                if ( ! is_dir($pathfile)) {
                    if ( ! mkdir($pathfile, 0777, true) && ! is_dir($pathfile)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $pathfile));
                    }
                    chmod($pathfile, 0777);
                } else {
                    chmod($pathfile, 0777);
                }
                //yang umum A4, Legal, Letter
                $paper = 'A4';
                //portrait or landscape
                $orientation = 'portrait';
                //Open Browser 1=Download 0=Open Browser
                $attachment = 1;
                // Load library
                $this->load->library(['dompdf_gen', 'parser']);
                // Convert to PDF
                if (false == $stream) {
                    $this->dompdf_gen->pdf_create($html, $pathfile.$filename, $paper, $orientation, $attachment, false);

                    return $pathfile.$filename.'.pdf';
                }
                $this->dompdf_gen->pdf_create($html, $filename, $paper, $orientation, $attachment, true);
            }
        }
    }

    public function semesterDua()
    {
        $this->load->model('Mod_akunku', 'm_akunku');
        $data = $this->m_akunku->getDetail($this->session->userdata('id_customer'));
        $params = [
            'sek_id' => $data->sekolah_id,
            'jenjang' => $data->bentuk,
            'login_npsn' => $data->no_npsn,
            'sekolah' => $data->school_name,
            'alamat' => $data->alamat,
            'kepsek' => $data->name,
            'nipkep' => $data->nip_kepsek,
            'hpkep' => $data->phone_kepsek,
            'namopr' => $data->operator,
            'hpopr' => $data->hp_operator,
            'emailopr' => $data->email_operator,
            'email' => $data->email,
            'telp' => $data->phone,
            'kodepos' => $data->kodepos,
            'desa' => $data->desa,
            'namakec' => $data->kecamatan,
            'kdkec' => $data->kd_kec,
            'namakab' => $data->kabupaten,
            'kdkab' => $data->kd_kab_kota,
            'namaprov' => $data->provinsi,
            'user' => $data->user_k13,
            'referensi' => 'GMR',
        ];
        $url = 'https://www.intanonline.com/sso_dr_gramed.php';
        header('Location: '.$url.'?'.http_build_query($params));
    }

    public function addPhone()
    {
        $noTelp = $this->input->post('no_telp');
        if ($noTelp) {
            $this->db->trans_begin();
            $dataSekolah = [
                'phone' => $noTelp,
                'date_upd' => date('Y-m-d H:i:s'),
            ];
            $this->Mod_general->edit('customer', $dataSekolah,
                'id_customer = '.$this->session->userdata('id_customer'));
            if ($this->db->trans_status() == true) {
                $this->db->trans_commit();
                $this->session->set_flashdata('message',
                    '<div class="alert alert-success alert-dismissable"><span class="glyphicon glyphicon-ok-circle"></span>&nbsp; Nomor telepon sekolah berhasil didaftarkan.</div>');
                redirect('pesanan/formpesanan');
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message',
                    '<div class="alert alert-danger alert-dismissable"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp; Nomor telepon sekolah gagal didaftarkan.</div>');
                redirect('pesanan/formpesanan');
            }
        } else {
            $this->session->set_flashdata('message',
                '<div class="alert alert-danger alert-dismissable"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp; Nomor telepon sekolah masih kosong.</div>');
            redirect('pesanan/formpesanan');
        }
    }

    public function testing()
    {
        $mail_to = array('admin@gramediaprinting.com','egohermansyah@gmail.com','admin@gramedia.com');
        $this->getDataEmailBlacklist($mail_to);
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
