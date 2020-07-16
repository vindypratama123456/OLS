<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Migrate extends CI_Controller {
    private $dbname;
    function __construct()
    {
        parent::__construct();
        // if(! $this->input->is_cli_request()) {
        //     show_404();
        //     exit;
        // }
        $this->load->library('migration');
    }
    function current()
    {
        if ($this->migration->current()) {
            log_message('error', 'Migration Success.');
            echo "Migration Success";
        } else {
            log_message('error', $this->migration->error_string());
            echo $this->migration->error_string();
        }
    }
    function rollback($version)
    {
        if ($this->migration->version($version)) {
            log_message('error', 'Migration Success.');
            echo "Migration Success";
        } else {
            log_message('error', $this->migration->error_string());
            echo $this->migration->error_string();
        }
    }
    function latest()
    {
        if ($this->migration->latest()) {
            log_message('error', 'Migration Success.');
            echo "Migration Success";
        } else {
            log_message('error', $this->migration->error_string());
            echo $this->migration->error_string();
        }
    }
}