<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    // Default function, shows the login page
    public function index() {
       
        $this->load->view('auth/login_page');
    }

    // Handles the login form submission
    public function login_process() {
        // Set validation rules for the form fields
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, show the login page again with errors
            $this->load->view('auth/login_page');
        } else {
            // Validation passed, process the login
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // Find the user in the database by their login email
            $user = $this->user_model->get_user_by_login_email($email);

            // Check if user was found AND the password is correct
            // password_verify() securely compares the plain text password with the hashed one in the DB
            if ($user && password_verify($password, $user->password)) {
                // Login is successful, create session data
                $session_data = array(
                    'user_id'       => $user->id,
                    'login_email'   => $user->gmailPME, // Store the login email
                    'role'          => $user->role,
                    'is_logged_in'  => TRUE
                );
                $this->session->set_userdata($session_data);

                // Redirect user based on their role
                if ($user->role == 'admin') {
                    redirect('dashboard/admin');
                } else {
                    redirect('dashboard/client');
                }
            } else {
                // Login failed, set an error message and redirect back to login page
                $this->session->set_flashdata('error', 'Invalid email or password.');
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