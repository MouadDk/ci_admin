<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dossier_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Client_model'); // For getting client info to determine folder name
        $this->load->model('File_model');   // For deleting associated files
        $this->load->helper('directory');   // For deleting physical folders
    }

    public function get_dossiers_by_client_id($client_id) {
        $this->db->select('dossiers.*');
        $this->db->from('dossiers');
        $this->db->where('client_id', $client_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_dossier_by_id($id) {
        $this->db->select('dossiers.*');
        $this->db->from('dossiers');
        $this->db->where('dossiers.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_dossier_by_client_id($client_id) {
        $this->db->select('dossiers.*');
        $this->db->from('dossiers');
        $this->db->where('client_id', $client_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function add_dossier($data) {
        return $this->db->insert('dossiers', $data);
    }

    public function get_or_create_dossier_for_client($client_id, $admin_id) {
        // Check if a dossier already exists for this client
        $this->db->where('client_id', $client_id);
        $query = $this->db->get('dossiers');
        
        if ($query->num_rows() > 0) {
            $dossier = $query->row();
            // Check if sub-dossiers exist, if not, create them
            $this->ensure_sub_dossiers_exist($dossier->id);
            return $dossier;
        } else {
            // Create a new dossier for this client
            $dossier_data = [
                'client_id' => $client_id,
                'name' => 'Client Documents',
                'status' => 'non valide',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($this->db->insert('dossiers', $dossier_data)) {
                $dossier_id = $this->db->insert_id();
                // Create the three sub-dossiers
                $this->ensure_sub_dossiers_exist($dossier_id);
                return $this->get_dossier_by_id($dossier_id);
            }
            return false;
        }
    }

    public function ensure_sub_dossiers_exist($dossier_id) {
        for ($i = 1; $i <= 3; $i++) {
            $this->db->where('dossier_id', $dossier_id);
            $this->db->where('sub_dossier_type', $i);
            $query = $this->db->get('sub_dossiers');

            if ($query->num_rows() == 0) {
                $sub_dossier_data = [
                    'dossier_id' => $dossier_id,
                    'sub_dossier_type' => $i,
                    'status' => 'non valide',
                    'validation_date' => null, // Initialize validation_date as null
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $this->db->insert('sub_dossiers', $sub_dossier_data);
            }
        }
    }

    public function update_dossier_status($dossier_id, $status) {
        $data = ['status' => $status];
        if ($status === 'valide') {
            $data['validation_date'] = date('Y-m-d H:i:s');
        } else {
            $data['validation_date'] = null;
        }
        $this->db->where('id', $dossier_id);
        return $this->db->update('dossiers', $data);
    }

    public function update_sub_dossier_status($sub_dossier_id, $status) {
        $data = ['status' => $status];
        // Set validation_date if status is 'valide'
        if ($status === 'valide') {
            $data['validation_date'] = date('Y-m-d H:i:s');
        } else {
            // Optionally, clear validation_date if status is not 'valide'
            $data['validation_date'] = null;
        }

        $this->db->where('id', $sub_dossier_id);
        $result = $this->db->update('sub_dossiers', $data);
        log_message('debug', 'Dossier_model->update_sub_dossier_status called for ID: ' . $sub_dossier_id . ' with status: ' . $status);
        if (!$result) {
            log_message('error', 'Failed to update sub_dossier status for ID ' . $sub_dossier_id . ': ' . $this->db->error()['message']);
        } else {
            log_message('info', 'Successfully updated sub_dossier status for ID ' . $sub_dossier_id . ' to ' . $status);
        }
        return $result;
    }

    public function delete_dossier($dossier_id) {
        // The ON DELETE CASCADE constraint in the database should handle deleting related sub_dossiers and files.
        // We just need to delete the dossier record and the physical folder.

        // Get dossier details
        $dossier = $this->get_dossier_by_id($dossier_id);
        if (!$dossier) {
            return false; // Dossier not found
        }

        // Get client details to determine the folder path
        $client = $this->Client_model->get_client($dossier->client_id);
        if (!$client) {
            // This should ideally not happen if dossier has a valid client_id
            log_message('error', 'Dossier ' . $dossier_id . ' found but associated client ' . $dossier->client_id . ' not found.');
            return false;
        }

        $client_folder_name = url_title($client->nom_etablissement, 'dash', TRUE);
        $dossier_path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'dossiers' . DIRECTORY_SEPARATOR . $client_folder_name . DIRECTORY_SEPARATOR;

        // 1. Delete all associated physical files from the folder
        // Note: The logic for deleting physical files needs to be updated to get files from all sub-dossiers.
        // This will be handled after the File_model is updated. For now, we focus on DB records.
        $sub_dossiers = $this->get_sub_dossiers($dossier_id);
        foreach ($sub_dossiers as $sub) {
            $files_in_sub_dossier = $this->File_model->get_files_by_sub_dossier_id($sub->id);
            foreach ($files_in_sub_dossier as $file) {
                if (file_exists($file->path)) {
                    unlink($file->path);
                }
            }
        }

        // 2. Delete all file records from the database
        // Deleting the dossier will cascade to sub_dossiers and files due to foreign key constraints.

        // 3. Delete the physical dossier folder if it's empty or contains only system files
        // Use recursive_delete from file helper
        if (is_dir($dossier_path)) {
            // Check if the directory is actually empty after deleting files
            // CodeIgniter's delete_files() function (part of file_helper)
            // can remove all files in a directory, but not the directory itself.
            // rmdir() only works on empty directories.
            // So, we'll use recursive_delete for simplicity and robustness.
            delete_files($dossier_path, TRUE); // Delete all files in the directory recursively
            if (is_dir($dossier_path) && rmdir($dossier_path)) {
                log_message('info', 'Successfully deleted dossier folder: ' . $dossier_path);
            } else {
                log_message('error', 'Failed to delete dossier folder or folder was not empty: ' . $dossier_path);
            }
        }

        // 4. Delete the dossier record from the database
        $this->db->where('id', $dossier_id);
        return $this->db->delete('dossiers');
    }
    public function get_all_dossiers() {
        $this->db->select('*');
        $query = $this->db->get('dossiers');
        return $query->result();
    }

    public function get_sub_dossiers($dossier_id) {
        $this->db->where('dossier_id', $dossier_id);
        $this->db->order_by('sub_dossier_type', 'ASC');
        $query = $this->db->get('sub_dossiers');
        return $query->result();
    }

    public function get_sub_dossier_by_type($dossier_id, $type) {
        $this->db->where('dossier_id', $dossier_id);
        $this->db->where('sub_dossier_type', $type);
        $query = $this->db->get('sub_dossiers');
        return $query->row();
    }

    public function get_sub_dossier_by_id($sub_dossier_id) {
        $this->db->where('id', $sub_dossier_id);
        $query = $this->db->get('sub_dossiers');
        return $query->row();
    }
    public function get_all_actions() {
        $query = $this->db->get('actions');
        return $query->result();
    }

    public function get_actions_for_sub_dossier($sub_dossier_id) {
        $this->db->select('action_id');
        $this->db->from('sub_dossier_actions');
        $this->db->where('sub_dossier_id', $sub_dossier_id);
        $query = $this->db->get();
        
        $action_ids = [];
        foreach ($query->result() as $row) {
            $action_ids[] = $row->action_id;
        }
        return $action_ids;
    }

    public function save_actions_for_sub_dossier($sub_dossier_id, $action_ids, $external_description = null) {
        $this->db->trans_start();

        // Update the external_description for the sub-dossier
        $this->db->where('id', $sub_dossier_id);
        $this->db->update('sub_dossiers', ['external_description' => $external_description]);

        // Delete existing actions for this sub-dossier
        $this->db->where('sub_dossier_id', $sub_dossier_id);
        $this->db->delete('sub_dossier_actions');

        // Insert new actions if any are provided
        if (!empty($action_ids)) {
            $data = [];
            foreach ($action_ids as $action_id) {
                $data[] = [
                    'sub_dossier_id' => $sub_dossier_id,
                    'action_id' => $action_id
                ];
            }
            $this->db->insert_batch('sub_dossier_actions', $data);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function check_required_documents_uploaded($sub_dossier_id) {
        $sub_dossier = $this->get_sub_dossier_by_id($sub_dossier_id);
        if (!$sub_dossier) {
            return false; // Sub-dossier not found
        }

        // Get predefined documents for this sub-dossier type
        // Note: This requires access to the client data and get_predefined_documents_for_sub_dossier logic
        // which is currently in Dossiers controller. We'll need to refactor that or pass it.
        // For now, let's assume we can get the required document types.
        
        // Get client data using the already loaded Client_model
        $dossier = $this->get_dossier_by_id($sub_dossier->dossier_id);
        $client = $this->Client_model->get_client($dossier->client_id);
        
        // This part is problematic: calling a private method of another controller
        // Ideally, this logic would be in the model or a helper.
        // For now, we'll simulate the required docs based on sub_dossier_type
        // In a real scenario, you'd pass the actual list of required docs from the controller.
        $required_docs_meta = [];
        if ($sub_dossier->sub_dossier_type == 1) {
            $required_docs_meta = [
                'registre_commerce', 'chiffre_affaire', 'declaration_honneur', 'status'
            ];
            // Add conditional required docs based on client type, similar to Dossiers controller
            if ($client) {
                switch (strtolower($client->etablissement)) {
                    case 'agence de voyage':
                        $required_docs_meta[] = 'licence_odv';
                        break;
                    case 'transport touristique':
                        $required_docs_meta[] = 'agrement';
                        break;
                    case 'hotel':
                    case 'restaurant':
                        $required_docs_meta[] = 'classement';
                        break;
                }
            }
        } elseif ($sub_dossier->sub_dossier_type == 2) {
            $required_docs_meta = [
                'rapport'
            ];
        }

        if (empty($required_docs_meta)) {
            return true; // No required documents for this sub-dossier type
        }

        // Get uploaded documents for this sub-dossier
        $uploaded_docs = $this->File_model->get_predefined_documents_by_sub_dossier($sub_dossier_id);
        $uploaded_doc_types = array_column($uploaded_docs, 'document_type');

        // Check if all required documents are in the uploaded list
        foreach ($required_docs_meta as $required_doc_type) {
            if (!in_array($required_doc_type, $uploaded_doc_types)) {
                return false; // A required document is missing
            }
        }

        return true; // All required documents are uploaded
    }
    public function get_action_by_name($action_name) {
        log_message('debug', 'Dossier_model->get_action_by_name called with action_name: ' . $action_name);
        $this->db->where('LOWER(name)', strtolower($action_name));
        $query = $this->db->get('actions');
        $result = $query->row();
        log_message('debug', 'Result for action_name "' . $action_name . '": ' . json_encode($result));
        return $result;
    }
}