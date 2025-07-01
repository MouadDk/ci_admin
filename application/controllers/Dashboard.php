<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function admin()
    {
        $this->load->view('dashboard/admin_dashboard');
    }

    public function client()
    {
        $this->load->view('dashboard/client_dashboard');
    }
}
