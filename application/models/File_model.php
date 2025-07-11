<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbforge();
    }

    public function get_files_by_sub_dossier_id($sub_dossier_id) {
        $this->db->select('files.*, admins.username as uploaded_by_admin_username');
        $this->db->from('files');
        $this->db->join('admins', 'admins.id = files.uploaded_by_admin_id', 'left');
        $this->db->where('sub_dossier_id', $sub_dossier_id);
        $this->db->order_by('files.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_file_by_id($id) {
        $this->db->select('files.*, admins.username as uploaded_by_admin_username');
        $this->db->from('files');
        $this->db->join('admins', 'admins.id = files.uploaded_by_admin_id', 'left');
        $this->db->where('files.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function add_file($data) {
        if ($this->db->insert('files', $data)) {
            return $this->db->insert_id(); // Retourne l'ID inséré en cas de succès
        } else {
            log_message('error', 'Database insert failed for table "files": ' . $this->db->error()['message']);
            return FALSE;
        }
    }

    public function delete_file($id) {
        $this->db->where('id', $id);
        return $this->db->delete('files');
    }

    // This function is no longer needed as ON DELETE CASCADE will handle it.
    // public function delete_files_by_dossier_id($dossier_id) { ... }

    public function get_all_files() {
        $this->db->select('*');
        $query = $this->db->get('files');
        return $query->result();
    }

    // Obsolete folder functions removed

    public function get_predefined_documents_by_sub_dossier($sub_dossier_id) {
        $this->db->select('files.*, admins.username as uploaded_by_admin_username');
        $this->db->from('files');
        $this->db->join('admins', 'admins.id = files.uploaded_by_admin_id', 'left');
        $this->db->where('sub_dossier_id', $sub_dossier_id);
        $this->db->where('document_type IS NOT NULL');
        $this->db->order_by('files.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_predefined_document_by_type($sub_dossier_id, $document_type) {
        $this->db->select('files.*');
        $this->db->from('files');
        $this->db->where('sub_dossier_id', $sub_dossier_id);
        $this->db->where('document_type', $document_type);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_non_predefined_files_by_sub_dossier($sub_dossier_id) {
        $this->db->select('files.*, admins.username as uploaded_by_admin_username');
        $this->db->from('files');
        $this->db->join('admins', 'admins.id = files.uploaded_by_admin_id', 'left');
        $this->db->where('sub_dossier_id', $sub_dossier_id);
        $this->db->where('(document_type IS NULL OR document_type = "")');
        $this->db->order_by('files.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    // Obsolete function removed
    // public function get_non_predefined_files_by_dossier_and_subfolder(...)

    // Obsolete table creation function removed
    // public function create_dossier_tables() { ... }
}