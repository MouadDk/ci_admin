<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property Client_model $Client_model
 * @property Dossier_model $Dossier_model
 * @property File_model $File_model
 * @property Admin_model $Admin_model
 * @property Cleanup_model $Cleanup_model
 * @property CI_Upload $upload
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property CI_DB_query_builder $db // Add this line for database property
 * @property CI_Output $output
 */
class Dossiers extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dossier_model');
        $this->load->model('Client_model'); // Load Client_model
        $this->load->model('File_model');   // Load File_model
        $this->load->model('Admin_model');  // Load Admin_model for password verification
        $this->load->helper('url'); // Load URL Helper for redirect()
        $this->load->helper('form'); // Load Form Helper for form_open_multipart()
        $this->load->library('form_validation'); // Load form validation library
        $this->load->database(); // Load the database library
        $this->load->model('Cleanup_model'); // Load Cleanup_model
    }

    public function client_dossiers($client_id, $active_tab = 'dossier_1')
    {
        $this->Cleanup_model->sync_folder_with_db();
        $admin_id = $this->session->userdata('login_id');
        if (!$admin_id) {
            $this->session->set_flashdata('error', 'Admin not logged in.');
            redirect('auth');
        }

        $client = $this->Client_model->get_client($client_id);
        if (!$client) {
            $this->session->set_flashdata('error', 'Client not found.');
            redirect('clients');
        }

        $dossier = $this->Dossier_model->get_or_create_dossier_for_client($client_id, $admin_id);
        if (!$dossier) {
            $this->session->set_flashdata('error', 'Could not retrieve or create dossier.');
            redirect('clients');
        }

        // Fetch all sub-dossiers and key them by type for easy access
        $all_sub_dossiers_raw = $this->Dossier_model->get_sub_dossiers($dossier->id);
        $all_sub_dossiers = [];
        foreach ($all_sub_dossiers_raw as $sd) {
            $all_sub_dossiers[$sd->sub_dossier_type] = $sd;
        }

        $sub_dossier_type_map = ['dossier_1' => 1, 'dossier_2' => 2, 'dossier_3' => 3];
        $sub_dossier_type = $sub_dossier_type_map[$active_tab] ?? 1;

        // Enforce validation flow
        // Check if Dossier 3 should be inaccessible due to "extern" action in Dossier 2
        // Check if Dossier 3 should be inaccessible due to "extern" action in Dossier 2
        // Only redirect if Dossier 3 is not already 'skipped'
        if ($active_tab === 'dossier_3') {
            $dossier_2 = $all_sub_dossiers[2] ?? null;
            $dossier_3_obj = $all_sub_dossiers[3] ?? null; // Get Dossier 3 object
            if ($dossier_2 && $dossier_3_obj && $dossier_3_obj->status !== 'skipped') { // Check status
                $selected_actions_dossier_2 = $this->Dossier_model->get_actions_for_sub_dossier($dossier_2->id);
                $extern_action = $this->Dossier_model->get_action_by_name('Externe');
                if ($extern_action && in_array($extern_action->id, $selected_actions_dossier_2)) {
                    $this->session->set_flashdata('error', 'Dossier 3 is not accessible because "Externe" action was selected in Dossier 2. It has been marked as skipped.');
                    // Automatically mark Dossier 3 as skipped if 'Externe' is selected in Dossier 2
                    $this->Dossier_model->update_sub_dossier_status($dossier_3_obj->id, 'skipped');
                    redirect('dossiers/client_dossiers/' . $client_id . '/dossier_2'); // Redirect to Dossier 2
                    return;
                }
            }
        }

        // Enforce validation flow for previous dossiers
        if ($sub_dossier_type > 1 && isset($all_sub_dossiers[$sub_dossier_type - 1]) && $all_sub_dossiers[$sub_dossier_type - 1]->status !== 'valide') {
            $this->session->set_flashdata('error', 'Please validate Dossier ' . ($sub_dossier_type - 1) . ' before accessing Dossier ' . $sub_dossier_type . '.');
            redirect('dossiers/client_dossiers/' . $client_id . '/dossier_' . ($sub_dossier_type - 1));
            return;
        }

        $sub_dossier = $all_sub_dossiers[$sub_dossier_type] ?? null;
        if (!$sub_dossier) {
            $this->session->set_flashdata('error', "Sub-dossier type {$sub_dossier_type} not found.");
            redirect('clients');
        }

        $data['client'] = $client;
        $data['dossier'] = $dossier;
        $data['sub_dossier'] = $sub_dossier;
        $data['all_sub_dossiers'] = $all_sub_dossiers; // Pass all sub-dossiers to the view
        $data['active_tab'] = $active_tab;

        // Pass extern_action_id to the view
        $extern_action = $this->Dossier_model->get_action_by_name('Externe');
        $data['extern_action_id'] = $extern_action ? $extern_action->id : null;

        // Fetch all documents for the current sub-dossier
        $all_documents = $this->File_model->get_files_by_sub_dossier_id($sub_dossier->id);
        
        $predefined_docs_meta = $this->get_predefined_documents_for_sub_dossier($client, $sub_dossier_type);
        $document_list = [];
        $existing_docs_by_type = [];

        foreach ($all_documents as $doc) {
            $doc_array = (array)$doc;
            $doc_array['is_uploaded'] = true;
            if (!empty($doc->document_type) && isset($predefined_docs_meta[$doc->document_type])) {
                $meta = $predefined_docs_meta[$doc->document_type];
                $doc_array['is_predefined'] = true;
                $doc_array['icon'] = $meta['icon'];
                $doc_array['required'] = $meta['required'];
                $existing_docs_by_type[$doc->document_type] = true;
            } else {
                $doc_array['is_predefined'] = false;
                $doc_array['icon'] = 'fas fa-file';
                $doc_array['required'] = false;
            }
            $document_list[] = (object)$doc_array;
        }

        foreach ($predefined_docs_meta as $type => $meta) {
            if (!isset($existing_docs_by_type[$type])) {
                $document_list[] = (object)[
                    'id' => null, 'sub_dossier_id' => $sub_dossier->id, 'name' => $meta['name'], 'document_type' => $type,
                    'path' => null, 'file_type' => null, 'file_size' => null, 'uploaded_by_admin_id' => null, 'created_at' => null, 'updated_at' => null,
                    'is_predefined' => true, 'is_uploaded' => false, 'icon' => $meta['icon'], 'required' => $meta['required']
                ];
            }
        }
        
        usort($document_list, function($a, $b) {
            if ($a->is_predefined !== $b->is_predefined) return $a->is_predefined ? -1 : 1;
            if ($a->is_predefined && $b->is_predefined) {
                if ($a->required !== $b->required) return $a->required ? -1 : 1;
            }
            return strcmp($a->name, $b->name);
        });

        $data['all_documents'] = $document_list;
        $this->load->view('admin/client_dossiers', $data);
    }

    private function get_predefined_documents_for_sub_dossier($client, $sub_dossier_type)
    {
        $docs = [];
        if ($sub_dossier_type == 1) {
            $docs = [
                'registre_commerce' => ['name' => 'Registre de Commerce', 'icon' => 'fas fa-building', 'required' => true],
                'chiffre_affaire' => ['name' => 'Chiffre d\'Affaire', 'icon' => 'fas fa-chart-line', 'required' => true],
                'declaration_honneur' => ['name' => 'Déclaration sur l\'Honneur', 'icon' => 'fas fa-file-signature', 'required' => true],
                'status' => ['name' => 'STATUS', 'icon' => 'fas fa-info-circle', 'required' => true]
            ];
            switch (strtolower($client->etablissement)) {
                case 'agence de voyage':
                    $docs['licence_odv'] = ['name' => 'Licence (ODV)', 'icon' => 'fas fa-plane', 'required' => true];
                    break;
                case 'transport touristique':
                    $docs['agrement'] = ['name' => 'Agrément', 'icon' => 'fas fa-bus', 'required' => true];
                    break;
                case 'hotel':
                case 'restaurant':
                    $docs['classement'] = ['name' => 'Classement', 'icon' => 'fas fa-star', 'required' => true];
                    break;
            }
        } elseif ($sub_dossier_type == 2) {
            $docs = [
                'rapport' => ['name' => 'Rapport', 'icon' => 'fas fa-file-alt', 'required' => true]
            ];
        }
        // No predefined docs for sub_dossier 3 (archives) by default
        return $docs;
    }

    // This function is now consolidated into upload_file
    // public function upload_predefined_document($client_id) { ... }

    private function get_document_name_by_type($type)
    {
        $names = [
            'registre_commerce' => 'Registre de Commerce',
            'chiffre_affaire' => 'Chiffre d\'Affaire',
            'declaration_honneur' => 'Déclaration sur l\'Honneur',
            'licence_odv' => 'Licence (ODV)',
            'agrement' => 'Agrément',
            'classement' => 'Classement',
            'status' => 'STATUS',
            'rapport' => 'Rapport' // Add rapport document type
        ];
        
        return $names[$type] ?? $type;
    }

    // This function is now consolidated into upload_file
    // public function upload_rapport_document($client_id) { ... }

    public function create_dossier_for_client($client_id)
    {
        $admin_id = $this->session->userdata('login_id');
        if (!$admin_id) {
            $this->session->set_flashdata('error', 'Admin not logged in.');
            redirect('auth');
        }

        $dossier = $this->Dossier_model->get_or_create_dossier_for_client($client_id, $admin_id);

        if ($dossier) {
            $this->session->set_flashdata('success', 'Dossier created/retrieved successfully.');
            redirect('dossiers/client_dossiers/' . $client_id);
        } else {
            $this->session->set_flashdata('error', 'Failed to create or retrieve dossier.');
            redirect('clients');
        }
    }

    public function upload_file($client_id)
    {
        $admin_id = $this->session->userdata('login_id');
        if (!$admin_id) {
            echo json_encode(['status' => 'error', 'message' => 'Admin not logged in.']);
            return;
        }

        $dossier = $this->Dossier_model->get_or_create_dossier_for_client($client_id, $admin_id);
        if (!$dossier) {
            echo json_encode(['status' => 'error', 'message' => 'Dossier not found or could not be created.']);
            return;
        }

        $sub_dossier_type = $this->input->post('sub_dossier_type');
        $sub_dossier = $this->Dossier_model->get_sub_dossier_by_type($dossier->id, $sub_dossier_type);
        if (!$sub_dossier) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid sub-dossier type.']);
            return;
        }

        $client = $this->Client_model->get_client($client_id);
        $client_folder_name = url_title($client->nom_etablissement, 'dash', TRUE);
        $subfolder_name = 'dossier_' . $sub_dossier_type;
        $upload_path = FCPATH . 'uploads/dossiers/' . $client_folder_name . '/' . $subfolder_name . '/';
        
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = '*';
        $config['max_size'] = 10240; // 10MB
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            $error = $this->upload->display_errors('', '');
            echo json_encode(['status' => 'error', 'message' => $error]);
            return;
        }

        $upload_data = $this->upload->data();
        $document_type = $this->input->post('document_type'); // For predefined docs
        $document_name = $this->input->post('document_name'); // For additional docs

        $file_name = $document_type ? $this->get_document_name_by_type($document_type) : ($document_name ?: $upload_data['orig_name']);

        // If it's a predefined doc, delete the old one first
        if ($document_type) {
            $existing_doc = $this->File_model->get_predefined_document_by_type($sub_dossier->id, $document_type);
            if ($existing_doc) {
                if (file_exists($existing_doc->path)) {
                    unlink($existing_doc->path);
                }
                $this->File_model->delete_file($existing_doc->id);
            }
        }

        $file_data = [
            'sub_dossier_id' => $sub_dossier->id,
            'name' => $file_name,
            'document_type' => $document_type ?: null,
            'path' => 'uploads/dossiers/' . $client_folder_name . '/' . $subfolder_name . '/' . $upload_data['file_name'],
            'file_type' => $upload_data['file_type'],
            'file_size' => $upload_data['file_size'],
            'uploaded_by_admin_id' => $admin_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->File_model->add_file($file_data)) {
            // For non-ajax form submissions
            if ($this->input->is_ajax_request() === false) {
                 $this->session->set_flashdata('success', 'Document uploaded successfully.');
                 redirect('dossiers/client_dossiers/' . $client_id . '/dossier_' . $sub_dossier_type);
            }
            echo json_encode(['status' => 'success', 'message' => 'Document uploaded successfully.']);
        } else {
            if (file_exists($file_data['path'])) {
                unlink($file_data['path']);
            }
            if ($this->input->is_ajax_request() === false) {
                 $this->session->set_flashdata('error', 'Failed to save document record.');
                 redirect('dossiers/client_dossiers/' . $client_id . '/dossier_' . $sub_dossier_type);
            }
            echo json_encode(['status' => 'error', 'message' => 'Failed to save document record.']);
        }
    }

    public function update_sub_dossier_status()
    {
        $this->form_validation->set_rules('sub_dossier_id', 'Sub Dossier ID', 'required|numeric');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[valide,non valide,skipped]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $sub_dossier_id = $this->input->post('sub_dossier_id');
        $status = $this->input->post('status');

        if ($status === 'valide') {
            // Check if all required documents are uploaded before validating
            if (!$this->Dossier_model->check_required_documents_uploaded($sub_dossier_id)) {
                echo json_encode(['status' => 'error', 'message' => 'Please upload all required documents before validating this dossier.']);
                return;
            }
        }

        if ($this->Dossier_model->update_sub_dossier_status($sub_dossier_id, $status)) {
            // Also update the main dossier status based on sub-dossiers
            $sub_dossier = $this->Dossier_model->get_sub_dossier_by_id($sub_dossier_id);
            if ($sub_dossier) {
                $this->update_main_dossier_status_from_sub($sub_dossier->dossier_id);
            }
            echo json_encode(['status' => 'success', 'message' => 'Sub-dossier status updated.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update sub-dossier status.']);
        }
    }

    public function update_dossier_status_global()
    {
        $this->form_validation->set_rules('dossier_id', 'Dossier ID', 'required|numeric');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[valide,non valide]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $dossier_id = $this->input->post('dossier_id');
        $status = $this->input->post('status');

        if ($this->Dossier_model->update_dossier_status($dossier_id, $status)) {
            echo json_encode(['status' => 'success', 'message' => 'Dossier status updated globally.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update global dossier status.']);
        }
    }

    private function update_main_dossier_status_from_sub($dossier_id) {
        $all_sub_dossiers = $this->Dossier_model->get_sub_dossiers($dossier_id);
        $all_valide = true;
        foreach ($all_sub_dossiers as $sd) {
            if ($sd->status !== 'valide') {
                $all_valide = false;
                break;
            }
        }
        $main_status = $all_valide ? 'valide' : 'non valide';
        $this->Dossier_model->update_dossier_status($dossier_id, $main_status);
    }

    public function delete_document($file_id)
    {
        $file = $this->File_model->get_file_by_id($file_id);
        if (!$file) {
            $this->session->set_flashdata('error', 'File not found.');
            redirect('clients'); // Fallback redirect
            return;
        }

        // Get sub-dossier to find dossier and then client
        $this->db->select('dossier_id, sub_dossier_type')->from('sub_dossiers')->where('id', $file->sub_dossier_id);
        $sub_dossier_query = $this->db->get();
        $sub_dossier = $sub_dossier_query->row();

        if (!$sub_dossier) {
             $this->session->set_flashdata('error', 'Could not find parent dossier.');
             redirect('clients');
             return;
        }

        $dossier = $this->Dossier_model->get_dossier_by_id($sub_dossier->dossier_id);
        $client_id = $dossier->client_id;
        $active_tab = 'dossier_' . $sub_dossier->sub_dossier_type;

        // Delete the physical file
        if (file_exists($file->path)) {
            unlink($file->path);
        }
        
        // Delete the record from the database
        if ($this->File_model->delete_file($file_id)) {
            $this->session->set_flashdata('success', 'Document deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete document record.');
        }
        redirect('dossiers/client_dossiers/' . $client_id . '/' . $active_tab);
    }

    public function delete_dossier($dossier_id)
    {
        $admin_id = $this->session->userdata('login_id');
        if (!$admin_id) {
            $this->session->set_flashdata('error', 'Admin not logged in.');
            redirect('auth');
        }

        // Load the admin model to verify password
        $this->load->model('Admin_model');

        $this->form_validation->set_rules('admin_password', 'Admin Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $dossier = $this->Dossier_model->get_dossier_by_id($dossier_id);
            if ($dossier) {
                $client_id = $dossier->client_id;
                $this->session->set_flashdata('error', validation_errors());
                redirect('dossiers/client_dossiers/' . $client_id);
            } else {
                $this->session->set_flashdata('error', 'Dossier not found.');
                redirect('clients');
            }
            return;
        }

        $admin_password = $this->input->post('admin_password');
        
        // Verify admin password
        if (!$this->Admin_model->verify_password($admin_id, $admin_password)) {
            $dossier = $this->Dossier_model->get_dossier_by_id($dossier_id);
            $client_id = $dossier ? $dossier->client_id : null;
            $this->session->set_flashdata('error', 'Incorrect password.');
            redirect('dossiers/client_dossiers/' . $client_id);
            return;
        }

        $dossier = $this->Dossier_model->get_dossier_by_id($dossier_id);
        if (!$dossier) {
            $this->session->set_flashdata('error', 'Dossier not found.');
            redirect('clients');
            return;
        }
        $client_id = $dossier->client_id;

        if ($this->Dossier_model->delete_dossier($dossier_id)) {
            $this->session->set_flashdata('success', 'Dossier and all associated files/folders deleted successfully.');
            redirect('clients'); // Redirect to clients page
        } else {
            $this->session->set_flashdata('error', 'Failed to delete dossier.');
            redirect('clients'); // Redirect to clients page
        }
    }
    // Obsolete function removed
    // public function create_db_tables_temp() { ... }
    public function actions_partial($sub_dossier_id)
    {
        $data['sub_dossier_id'] = $sub_dossier_id;
        $data['all_actions'] = $this->Dossier_model->get_all_actions();
        $data['selected_actions'] = $this->Dossier_model->get_actions_for_sub_dossier($sub_dossier_id);
        $extern_action = $this->Dossier_model->get_action_by_name('Externe'); // Corrected to 'Externe'
        $data['extern_action_id'] = $extern_action ? $extern_action->id : null;

        // Fetch external description if 'Externe' is selected
        if ($data['extern_action_id'] && in_array($data['extern_action_id'], $data['selected_actions'])) {
            $sub_dossier = $this->Dossier_model->get_sub_dossier_by_id($sub_dossier_id);
            $data['external_description'] = $sub_dossier->external_description ?? '';
        } else {
            $data['external_description'] = '';
        }

        $this->load->view('admin/sub_dossier_actions_partial', $data);
    }

    public function get_sub_dossier_actions_by_client($client_id) {
        $this->output->set_content_type('application/json');

        // Get the main dossier for the client
        $dossier = $this->Dossier_model->get_dossier_by_client_id($client_id);

        if (!$dossier) {
            echo json_encode(['status' => 'success', 'actions' => [], 'message' => 'Dossier not found for this client.']);
            return;
        }

        // Get the sub-dossier of type 2 (Dossier 2)
        $sub_dossier = $this->Dossier_model->get_sub_dossier_by_type($dossier->id, 2);

        if (!$sub_dossier) {
            echo json_encode(['status' => 'success', 'actions' => [], 'message' => 'Dossier 2 not available for this client.']);
            return;
        }

        $actions = $this->Dossier_model->get_actions_for_sub_dossier($sub_dossier->id);
        $all_actions = $this->Dossier_model->get_all_actions();

        $action_names = [];
        foreach ($all_actions as $action) {
            if (in_array($action->id, $actions)) {
                $action_names[] = $action->name;
            }
        }
        
        // Get the external description from the sub_dossier object
        $external_description = $sub_dossier->external_description ?? null;

        if (!empty($action_names) || !empty($external_description)) {
            echo json_encode(['status' => 'success', 'actions' => $action_names, 'external_description' => $external_description]);
        } else {
            echo json_encode(['status' => 'success', 'actions' => [], 'external_description' => null, 'message' => 'Actions and external description are not defined yet.']);
        }
    }

    public function save_actions()
    {
        $this->output->set_content_type('application/json');

        $sub_dossier_id = $this->input->post('sub_dossier_id');
        $action_ids = $this->input->post('actions'); // Array of selected action IDs
        $external_description = $this->input->post('external_description'); // New: Get external description

        if (empty($sub_dossier_id)) {
// Get the ID for the "Externe" action
        $extern_action = $this->Dossier_model->get_action_by_name('Externe');
        $extern_action_id = $extern_action ? $extern_action->id : null;

        // If "Externe" action is not selected, set external_description to null
        if ($extern_action_id && (!is_array($action_ids) || !in_array($extern_action_id, $action_ids))) {
            $external_description = null;
        }
            echo json_encode(['status' => 'error', 'message' => 'Sub-dossier ID is missing.']);
            return;
        }

        // Pass external_description to the model
        if ($this->Dossier_model->save_actions_for_sub_dossier($sub_dossier_id, $action_ids, $external_description)) {
            echo json_encode(['status' => 'success', 'message' => 'Actions and description saved successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save actions and description.']);
        }
    }

    public function update_dossier_3_status()
    {
        $this->output->set_content_type('application/json');

        $sub_dossier_id = $this->input->post('sub_dossier_id');
        $action_type = $this->input->post('action_type'); // 'skip' or 'unskip'

        if (empty($sub_dossier_id) || empty($action_type)) {
            echo json_encode(['status' => 'error', 'message' => 'Sub-dossier ID or action type is missing.']);
            return;
        }

        $sub_dossier_2 = $this->Dossier_model->get_sub_dossier_by_id($sub_dossier_id);

        if (!$sub_dossier_2) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid sub-dossier ID provided (Dossier 2).']);
            return;
        }

        $dossier = $this->Dossier_model->get_dossier_by_id($sub_dossier_2->dossier_id);
        if (!$dossier) {
            echo json_encode(['status' => 'error', 'message' => 'Main dossier not found.']);
            return;
        }

        $dossier_3 = $this->Dossier_model->get_sub_dossier_by_type($dossier->id, 3);

        if (!$dossier_3) {
            echo json_encode(['status' => 'error', 'message' => 'Dossier 3 not found for this client.']);
            return;
        }

        $new_status = ($action_type === 'skip') ? 'skipped' : 'non valide'; // Default status when unskipped

        if ($this->Dossier_model->update_sub_dossier_status($dossier_3->id, $new_status)) {
            echo json_encode(['status' => 'success', 'message' => 'Dossier 3 status updated to ' . $new_status . '.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update Dossier 3 status.']);
        }
    }
}