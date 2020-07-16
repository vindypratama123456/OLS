<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Mod_uploadfiles $m_uploadfiles
 */
class Install extends CI_Controller
{
    public $data = [];

    public function __construct()
    {
        parent::__construct();
    }

    function index(){
        $this->load->view('install');    
    }

}