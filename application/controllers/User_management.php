<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_management extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Admin_model');
        $this->load->model('Client_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['users'] = $this->User_model->get_all_users();
        $this->load->view('admin/users', $data);
    }

    public function add() {
        $data['clients'] = $this->Client_model->get_all_clients();
        $this->load->view('admin/user_form', $data);
    }

    public function save() {
        $this->form_validation->set_rules('gmailPME', 'Login Email', 'required|valid_email|is_unique[users.gmailPME]');
        $this->form_validation->set_rules('password_pme', 'PME Password', 'required|min_length[6]|matches[pass_confirm_pme]');
        $this->form_validation->set_rules('pass_confirm_pme', 'Confirm PME Password', 'required');
        $this->form_validation->set_rules('password_gmail', 'Gmail Password', 'required|min_length[6]|matches[pass_confirm_gmail]');
        $this->form_validation->set_rules('pass_confirm_gmail', 'Confirm Gmail Password', 'required');
        $this->form_validation->set_rules('client_id', 'Associated Client', 'required');


        if ($this->form_validation->run() == FALSE) {
            $data['clients'] = $this->Client_model->get_all_clients();
            $this->load->view('admin/user_form', $data);
        } else {
            $user_data = [
                'gmailPME' => $this->input->post('gmailPME'),
                'password_pme' => $this->input->post('password_pme'),
                'password_gmail' => $this->input->post('password_gmail'),
                'client_id' => $this->input->post('client_id')
            ];
            
            if ($this->User_model->create_user($user_data)) {
                $this->session->set_flashdata('success', 'User added successfully');
                redirect('user_management');
            } else {
                $this->session->set_flashdata('error', 'Failed to add user');
                redirect('user_management/add');
            }
        }
    }

    public function edit($id) {
        $data['user'] = $this->User_model->get_user($id);
        $data['clients'] = $this->Client_model->get_all_clients();
        $this->load->view('admin/user_form', $data);
    }

    public function update($id) {
        $data['user'] = $this->User_model->get_user($id);
        
        $this->form_validation->set_rules('gmailPME', 'Login Email', 'required|valid_email');
        $this->form_validation->set_rules('client_id', 'Associated Client', 'required');

        // Only validate password_pme if provided
        if ($this->input->post('password_pme')) {
            $this->form_validation->set_rules('password_pme', 'PME Password', 'min_length[6]|matches[pass_confirm_pme]');
            $this->form_validation->set_rules('pass_confirm_pme', 'Confirm PME Password', 'required');
        }

        // Only validate password_gmail if provided
        if ($this->input->post('password_gmail')) {
            $this->form_validation->set_rules('password_gmail', 'Gmail Password', 'min_length[6]|matches[pass_confirm_gmail]');
            $this->form_validation->set_rules('pass_confirm_gmail', 'Confirm Gmail Password', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $data['clients'] = $this->Client_model->get_all_clients();
            $this->load->view('admin/user_form', $data);
        } else {
            $user_data = [
                'gmailPME' => $this->input->post('gmailPME'),
                'client_id' => $this->input->post('client_id')
            ];

            if ($this->input->post('password_pme')) {
                $user_data['password_pme'] = $this->input->post('password_pme');
            }
            if ($this->input->post('password_gmail')) {
                $user_data['password_gmail'] = $this->input->post('password_gmail');
            }
            
            if ($this->User_model->update_user($id, $user_data)) {
                $this->session->set_flashdata('success', 'User updated successfully');
                redirect('user_management');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user');
                redirect('user_management/edit/'.$id);
            }
        }
    }

    public function delete($id) {
        $this->form_validation->set_rules('admin_password', 'Your Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('user_management');
            return;
        }

        $admin_id = $this->session->userdata('login_id');
        $admin_password = $this->input->post('admin_password');

        if ($this->Admin_model->verify_password($admin_id, $admin_password)) {
            if ($this->User_model->delete_user($id)) {
                $this->session->set_flashdata('success', 'User deleted successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete user');
            }
        } else {
            $this->session->set_flashdata('error', 'Incorrect password. User deletion failed.');
        }
        redirect('user_management');
    }

}