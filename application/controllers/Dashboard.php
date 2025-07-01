<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function index()
    {
        $this->load->helper('url'); // Just in case it's not autoloaded
        $this->load->view('dashboard');
    }
}
