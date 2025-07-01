<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Table_model');
    }

    public function admin() {
        $this->load->view('dashboard/admin_dashboard');
    }

    public function client() {
        $this->load->view('dashboard/client_dashboard');
    }

    public function tables() {
        // Get data from model
        $data['table_data'] = $this->Table_model->get_table_data();
        
        // Load view with data
        $this->load->view('tables', $data);
    }

    public function index() {
        // Redirect to tables or use as alternative
        $this->tables();
    }
}