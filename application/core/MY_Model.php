<?php
/**
 * Created by PhpStorm.
 * User: trenggana
 * Date: 10/01/19
 * Time: 11.23
 */
defined('BASEPATH') || exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $limitOne;
    protected $colIdOrder;
    protected $colNoPD;
    protected $typeInner;
    protected $tblCustomer;
    protected $tblEmployee;
    protected $tblPayout;
    protected $tblMitraProfile;

    public function __construct()
    {
        parent::__construct();
        $this->limitOne = 'LIMIT 1';
        $this->colIdOrder = 'id_order';
        $this->colNoPD = 'no_pd';
        $this->typeInner = 'inner';
        $this->tblCustomer = 'customer';
        $this->tblEmployee = 'employee';
        $this->tblPayout = 'payout_detail';
        $this->tblMitraProfile = 'mitra_profile';
    }
}
