<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class YourController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('table_model');
    }

    public function index() {
        $data['table_data'] = $this->table_model->get_table_data();
        $this->load->view('your_view_file', $data);
    }
}