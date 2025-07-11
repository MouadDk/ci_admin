<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $all_admins = $this->Admin_model->get_all_admins();
        $logged_in_admin_id = $this->session->userdata('admin_id');

        // Filter out the currently logged-in admin from the list
        $filtered_admins = array_filter($all_admins, function($admin) use ($logged_in_admin_id) {
            return $admin->id != $logged_in_admin_id;
        });

        $data['admins'] = array_values($filtered_admins); // Re-index the array
        $this->load->view('admin/admins', $data);
    }

    public function add() {
        $this->load->view('admin/admin_form');
    }

    public function save() {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[admins.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[pass_confirm]');
        $this->form_validation->set_rules('pass_confirm', 'Confirm Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/admin_form');
        } else {
            $admin_data = [
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password')
            ];
            
            if ($this->Admin_model->create_admin($admin_data)) {
                $this->session->set_flashdata('success', 'Admin added successfully');
                redirect('admins');
            } else {
                $this->session->set_flashdata('error', 'Failed to add admin');
                redirect('admins/add');
            }
        }
    }

    public function edit($id) {
        $data['admin'] = $this->Admin_model->get_admin($id);
        if (empty($data['admin'])) {
            show_404();
        }
        $this->load->view('admin/admin_form', $data);
    }

    public function update($id) {
        $data['admin'] = $this->Admin_model->get_admin($id);
        if (empty($data['admin'])) {
            show_404();
        }

        // If it's an edit operation, and the username is not the current admin's username,
        // then apply the uniqueness rule. Otherwise, allow the current username.
        $current_admin_username = $data['admin']->username;
        $new_username = $this->input->post('username');

        if ($new_username !== $current_admin_username) {
            $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[admins.username]');
        } else {
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
        }
        // Only validate password if it's provided (i.e., user wants to change it)
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]|matches[pass_confirm]');
            $this->form_validation->set_rules('pass_confirm', 'Confirm Password', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/admin_form', $data);
        } else {
            $admin_data = [
                'username' => $this->input->post('username')
            ];

            if ($this->input->post('password')) {
                $admin_data['password'] = $this->input->post('password');
            }
            
            if ($this->Admin_model->update_admin($id, $admin_data)) {
                $this->session->set_flashdata('success', 'Admin updated successfully');
                redirect('admins');
            } else {
                $this->session->set_flashdata('error', 'Failed to update admin');
                redirect('admins/edit/'.$id);
            }
        }
    }

    public function delete($id) {
        $admin_to_delete = $this->Admin_model->get_admin($id);
        if (!$admin_to_delete) {
            $this->session->set_flashdata('error', 'Admin not found.');
            redirect('admins');
            return;
        }

        // Prevent self-deletion
        if ($admin_to_delete->id === $this->session->userdata('admin_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('admins');
            return;
        }

        // Password confirmation for deletion
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, redirect back to the admins list with an error
            $this->session->set_flashdata('error', validation_errors());
            redirect('admins');
            return;
        }

        $current_admin_id = $this->session->userdata('admin_id');
        $current_admin = $this->Admin_model->get_admin($current_admin_id);

        if (!$current_admin || ($this->input->post('current_password') !== $current_admin->password)) {
            $this->session->set_flashdata('error', 'Incorrect password for the logged-in account.');
            redirect('admins');
            return;
        }

        if ($this->Admin_model->delete_admin($id)) {
            $this->session->set_flashdata('success', 'Admin deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete admin.');
        }
        redirect('admins');
    }
    public function profile() {
        $admin_id = $this->session->userdata('login_id'); // Corrected to 'login_id'
        log_message('debug', 'Admins/profile: login_id from session: ' . ($admin_id ? $admin_id : 'NULL'));

        if (!$admin_id) {
            $this->session->set_flashdata('error', 'Admin not logged in or session expired.');
            redirect('auth');
            return;
        }

        $data['admin'] = $this->Admin_model->get_admin($admin_id);
        log_message('debug', 'Admins/profile: Admin data retrieved: ' . ($data['admin'] ? json_encode($data['admin']) : 'NULL'));

        if (empty($data['admin'])) {
            $this->session->set_flashdata('error', 'Admin profile not found in database.');
            redirect('dashboard/admin'); // Redirect to admin dashboard
            return;
        }

        $this->load->view('admin/profile', $data);
    }

    public function change_username() {
        $admin_id = $this->session->userdata('login_id'); // Corrected to 'login_id'
        $current_username = $this->session->userdata('login_username');

        $this->form_validation->set_rules('new_username', 'New Username', 'required|trim|callback_username_check['.$admin_id.']');

        if ($this->form_validation->run() == FALSE) {
            $this->profile(); // Reload profile page with validation errors
        } else {
            $new_username = $this->input->post('new_username');
            
            if ($this->Admin_model->update_admin($admin_id, ['username' => $new_username])) {
                $this->session->set_userdata('login_username', $new_username); // Update session
                $this->session->set_flashdata('success', 'Username changed successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to change username.');
            }
            redirect('admins/profile');
        }
    }

    // Callback function for username uniqueness check, allowing current user's username
    public function username_check($new_username, $admin_id) { // This $admin_id comes from the callback, not session
        $admin = $this->Admin_model->get_admin_by_username($new_username);
        if ($admin && $admin->id != $admin_id) { // Compare with the $admin_id passed to the callback
            $this->form_validation->set_message('username_check', 'The {field} is already taken.');
            return FALSE;
        }
        return TRUE;
    }

    public function change_password() {
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]|matches[confirm_new_password]');
        $this->form_validation->set_rules('confirm_new_password', 'Confirm New Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->profile(); // Reload profile page with validation errors
        } else {
            $admin_id = $this->session->userdata('login_id'); // Corrected to 'login_id'
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');

            if ($this->Admin_model->verify_password($admin_id, $current_password)) {
                if ($this->Admin_model->update_admin($admin_id, ['password' => $new_password])) {
                    $this->session->set_flashdata('success', 'Password changed successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to change password.');
                }
            } else {
                $this->session->set_flashdata('error', 'Incorrect current password.');
            }
            redirect('admins/profile');
        }
    }

    public function delete_account() {
        $this->form_validation->set_rules('delete_password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->profile(); // Reload profile page with validation errors
        } else {
            $admin_id = $this->session->userdata('login_id'); // Corrected to 'login_id'
            $delete_password = $this->input->post('delete_password');

            if ($this->Admin_model->verify_password($admin_id, $delete_password)) {
                if ($this->Admin_model->delete_admin($admin_id)) {
                    $this->session->set_flashdata('success', 'Your account has been successfully deleted.');
                    redirect('auth/logout'); // Log out after successful deletion
                } else {
                    $this->session->set_flashdata('error', 'Failed to delete account.');
                }
            } else {
                $this->session->set_flashdata('error', 'Incorrect password.');
            }
            redirect('admins/profile');
        }
    }
}