<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_client_by_dossier($dossier_id) {
        $this->db->select('clients.*');
        $this->db->from('clients');
        $this->db->join('dossiers', 'dossiers.client_id = clients.id');
        $this->db->where('dossiers.id', $dossier_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_all_clients() {
        $this->db->select('clients.*, clients.referal, users.id as user_id, users.gmailPME, users.password_pme, users.password_gmail, MAX(dossiers.status) as dossier_status, MAX(dossiers.id) as dossier_id, sd2.id as sub_dossier_id_2');
        $this->db->from('clients');
        $this->db->join('users', 'users.client_id = clients.id', 'left');
        $this->db->join('dossiers', 'dossiers.client_id = clients.id', 'left');
        $this->db->join('sub_dossiers sd2', 'dossiers.id = sd2.dossier_id AND sd2.sub_dossier_type = 2', 'left'); // Join for sub_dossier_type 2
        $this->db->group_by('clients.id');
        $this->db->order_by('clients.is_active DESC, clients.nom_etablissement ASC'); // Order by active status then name
        $query = $this->db->get();
        return $query->result();
    }

    public function get_client($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('clients');
        return $query->row();
    }

    public function get_client_by_ice($ice, $exclude_id = null) {
        $this->db->where('ice', $ice);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $query = $this->db->get('clients');
        return $query->row();
    }

    public function create_client($client_data) {
        $this->db->insert('clients', $client_data);
        return $this->db->insert_id();
    }

    public function update_client($id, $client_data) {
        $this->db->where('id', $id);
        return $this->db->update('clients', $client_data);
    }

    public function deactivate_client($id) {
        $this->db->where('id', $id);
        return $this->db->update('clients', ['is_active' => 0]);
    }

    public function activate_client($id) {
        $this->db->where('id', $id);
        return $this->db->update('clients', ['is_active' => 1]);
    }

    /**
     * Get recent clients with a specific limit
     */
    public function get_recent_clients($limit = 5) {
        $this->db->select('clients.*');
        $this->db->from('clients');
        $this->db->order_by('clients.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get client count by establishment type
     */
    public function get_client_count_by_type() {
        $this->db->select('etablissement, COUNT(*) as count');
        $this->db->from('clients');
        $this->db->group_by('etablissement');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get client count by city
     */
    public function get_client_count_by_city() {
        $this->db->select('ville, COUNT(*) as count');
        $this->db->from('clients');
        $this->db->group_by('ville');
        $query = $this->db->get();
        return $query->result();
    }
    /**
     * Get recent clients that have an open dossier (status not 'valide').
     *
     * @param int $limit The maximum number of clients to return.
     * @return array An array of client objects.
     */
    public function get_recent_clients_with_open_dossiers($limit = 5) {
        $this->db->select('clients.id, clients.etablissement, clients.ville, clients.responsable, clients.nom_etablissement, clients.referal, dossiers.status as dossier_status');
        $this->db->from('clients');
        $this->db->join('dossiers', 'dossiers.client_id = clients.id');
        $this->db->where('dossiers.status', 'non valide'); // Display only 'non valide' dossiers
        $this->db->order_by('dossiers.updated_at', 'DESC'); // Order by last update of the dossier
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }
}