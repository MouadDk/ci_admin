<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends CI_Controller {

    /**
     * @var \Client_model
     */
    public $Client_model;
    /**
     * @var \User_model
     */
    public $User_model;
    /**
     * @var \Admin_model
     */
    public $Admin_model;
    /**
     * @var \CI_Form_validation
     */
    public $form_validation;
    /**
     * @var \CI_Input
     */
    public $input;
    /**
     * @var \CI_Session
     */
    public $session;
    /**
     * @var \CI_Output
     */
    public $output;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Client_model');
        $this->load->model('User_model');
        $this->load->model('Admin_model');
        $this->load->library('form_validation');
        $this->load->library('session'); // Ensure session library is loaded
    }

    public function index()
    {
        $data['clients'] = $this->Client_model->get_all_clients();
        $this->load->view('admin/clients', $data);
    }

    public function add() {
        $this->load->view('admin/client_form');
    }

    public function save()
    {
        $this->form_validation->set_rules('responsable', 'Responsable', 'required');
        $this->form_validation->set_rules('etablissement', 'Etablissement', 'required');
        $this->form_validation->set_rules('ville', 'Ville', 'required');
        $this->form_validation->set_rules('telephone1', 'Telephone 1', 'required');
        $this->form_validation->set_rules('nom_etablissement', 'Nom Etablissement', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');
        $this->form_validation->set_rules('provenance', 'Provenance', 'required');
        $this->form_validation->set_rules('referal', 'Referal', 'trim'); // Adding validation for referal, making it optional
        // User fields validation
        $this->form_validation->set_rules('gmailPME', 'Login Email', 'required|valid_email|is_unique[users.gmailPME]');
        $this->form_validation->set_rules('password_pme', 'PME Password', 'required|min_length[6]|matches[pass_confirm_pme]');
        $this->form_validation->set_rules('pass_confirm_pme', 'Confirm PME Password', 'required');
        $this->form_validation->set_rules('password_gmail', 'Gmail Password', 'required|min_length[6]|matches[pass_confirm_gmail]');
        $this->form_validation->set_rules('pass_confirm_gmail', 'Confirm Gmail Password', 'required');


        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/client_form');
        } else {
            $client_data = [
                'etablissement' => $this->input->post('etablissement'),
                'ville' => $this->input->post('ville'),
                'region' => $this->input->post('region'),
                'responsable' => $this->input->post('responsable'),
                'telephone1' => $this->input->post('telephone1'),
                'telephone2' => $this->input->post('telephone2'),
                'nom_etablissement' => $this->input->post('nom_etablissement'),
                'ice' => $this->input->post('ice'),
                'type' => $this->input->post('type'),
                'provenance' => $this->input->post('provenance'),
                'referal' => $this->input->post('referal'), // Adding referal field
                'is_active' => 1 // New clients are active by default
            ];

            // Check if ICE already exists
            $existing_client_by_ice = $this->Client_model->get_client_by_ice($client_data['ice']);
            if ($existing_client_by_ice) {
                $this->session->set_flashdata('error', 'This ICE (' . $client_data['ice'] . ') already exists for client: ' . $existing_client_by_ice->nom_etablissement . '.');
                $this->load->view('admin/client_form');
                return;
            }
            
            $client_id = $this->Client_model->create_client($client_data);
            if ($client_id) {
                $user_data = [
                    'client_id' => $client_id,
                    'gmailPME' => $this->input->post('gmailPME'),
                    'password_pme' => $this->input->post('password_pme'),
                    'password_gmail' => $this->input->post('password_gmail')
                ];
                $this->User_model->create_user($user_data);

                $this->session->set_flashdata('success', 'Client and associated user added successfully');
                redirect('clients');
            } else {
                $this->session->set_flashdata('error', 'Failed to add client');
                redirect('clients/add');
            }
        }
    }

    public function edit($id) {
        $data['client'] = $this->Client_model->get_client($id);
        $data['user'] = $this->User_model->get_user_by_client_id($id); // Assuming you add this function to User_model
        $this->load->view('admin/client_form', $data);
    }

    public function update($id)
    {
        $this->form_validation->set_rules('responsable', 'Responsable', 'required');
        $this->form_validation->set_rules('etablissement', 'Etablissement', 'required');
        $this->form_validation->set_rules('ville', 'Ville', 'required');
        $this->form_validation->set_rules('telephone1', 'Telephone 1', 'required');
        $this->form_validation->set_rules('nom_etablissement', 'Nom Etablissement', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');
        $this->form_validation->set_rules('provenance', 'Provenance', 'required');
        $this->form_validation->set_rules('referal', 'Referal', 'trim'); // Adding validation for referal, making it optional

        // User fields validation (optional for update)
        $this->form_validation->set_rules('gmailPME', 'Login Email', 'required|valid_email');
        if ($this->input->post('password_pme')) {
            $this->form_validation->set_rules('password_pme', 'PME Password', 'min_length[6]|matches[pass_confirm_pme]');
            $this->form_validation->set_rules('pass_confirm_pme', 'Confirm PME Password', 'required');
        }
        if ($this->input->post('password_gmail')) {
            $this->form_validation->set_rules('password_gmail', 'Gmail Password', 'min_length[6]|matches[pass_confirm_gmail]');
            $this->form_validation->set_rules('pass_confirm_gmail', 'Confirm Gmail Password', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $data['client'] = $this->Client_model->get_client($id);
            $data['user'] = $this->User_model->get_user_by_client_id($id);
            $this->load->view('admin/client_form', $data);
        } else {
            $client_data = [
                'etablissement' => $this->input->post('etablissement'),
                'ville' => $this->input->post('ville'),
                'region' => $this->input->post('region'),
                'responsable' => $this->input->post('responsable'),
                'telephone1' => $this->input->post('telephone1'),
                'telephone2' => $this->input->post('telephone2'),
                'nom_etablissement' => $this->input->post('nom_etablissement'),
                'ice' => $this->input->post('ice'),
                'type' => $this->input->post('type'),
                'provenance' => $this->input->post('provenance'),
                'referal' => $this->input->post('referal') // Adding referal field
                // is_active is no longer updated via form, it retains its value from the DB
            ];

            // Check if ICE already exists for another client
            $existing_client_by_ice = $this->Client_model->get_client_by_ice($client_data['ice'], $id);
            if ($existing_client_by_ice) {
                $this->session->set_flashdata('error', 'This ICE (' . $client_data['ice'] . ') already exists for client: ' . $existing_client_by_ice->nom_etablissement . '.');
                $data['client'] = $this->Client_model->get_client($id);
                $data['user'] = $this->User_model->get_user_by_client_id($id);
                $this->load->view('admin/client_form', $data);
                return;
            }
            
            if ($this->Client_model->update_client($id, $client_data)) {
                $user_data = [
                    'gmailPME' => $this->input->post('gmailPME')
                ];
                if ($this->input->post('password_pme')) {
                    $user_data['password_pme'] = $this->input->post('password_pme');
                }
                if ($this->input->post('password_gmail')) {
                    $user_data['password_gmail'] = $this->input->post('password_gmail');
                }

                $existing_user = $this->User_model->get_user_by_client_id($id);
                if ($existing_user) {
                    $this->User_model->update_user($existing_user->id, $user_data);
                } else {
                    $user_data['client_id'] = $id;
                    $this->User_model->create_user($user_data);
                }

                $this->session->set_flashdata('success', 'Client and associated user updated successfully');
                redirect('clients');
            } else {
                $this->session->set_flashdata('error', 'Failed to update client');
                redirect('clients/edit/'.$id);
            }
        }
    }

    public function deactivate($id)
    {
        $this->form_validation->set_rules('admin_password', 'Your Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('clients');
            return;
        }

        $admin_id = $this->session->userdata('login_id');
        $admin_password = $this->input->post('admin_password');

        if ($this->Admin_model->verify_password($admin_id, $admin_password)) {
            if ($this->Client_model->deactivate_client($id)) {
                $this->session->set_flashdata('success', 'Client deactivated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to deactivate client');
            }
        } else {
            $this->session->set_flashdata('error', 'Incorrect password. Client deactivation failed.');
        }
        redirect('clients');
    }

    public function activate($id)
    {
        $this->form_validation->set_rules('admin_password', 'Your Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('clients');
            return;
        }

        $admin_id = $this->session->userdata('login_id');
        $admin_password = $this->input->post('admin_password');

        if ($this->Admin_model->verify_password($admin_id, $admin_password)) {
            if ($this->Client_model->activate_client($id)) {
                $this->session->set_flashdata('success', 'Client activated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to activate client');
            }
        } else {
            $this->session->set_flashdata('error', 'Incorrect password. Client activation failed.');
        }
        redirect('clients');
    }

}