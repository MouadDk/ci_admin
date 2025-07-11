<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin_model'); // Load the Admin_model
    }

    // Default function, shows the login page
    public function index() {
       
        $this->load->view('auth/login_page');
    }

    // Handles the login form submission
    public function login_process() {
        // Set validation rules for the form fields
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show the login page again with errors
            $this->load->view('auth/login_page');
        } else {
            // Validation passed, process the login
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Attempt to log in as an admin
            $admin = $this->admin_model->get_admin_by_username($username);

            if ($admin && $this->admin_model->verify_password($admin->id, $password)) {
                $session_data = array(
                    'login_id'      => $admin->id, // Use login_id for consistency with Dossiers controller
                    'login_username'   => $admin->username,
                    'role'          => 'admin',
                    'is_logged_in'  => TRUE
                );
                $this->session->set_userdata($session_data);
                redirect('dashboard/admin');
            } else {
                // Login failed
                $this->session->set_flashdata('error', 'Invalid username or password.');
                redirect('auth');
            }
        }
    }

    // Logs the user out
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}