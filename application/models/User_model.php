<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_user_by_username($username) {
        $this->db->where('LOWER(gmailPME)', strtolower($username)); // Use gmailPME as the username field
        $query = $this->db->get('users');
        return ($query->num_rows() == 1) ? $query->row() : false;
    }

    public function get_user_by_client_id($client_id) {
        $this->db->where('client_id', $client_id);
        $query = $this->db->get('users');
        return $query->row();
    }

    public function get_all_users() {
        $this->db->select('users.*, clients.nom_etablissement');
        $this->db->from('users');
        $this->db->join('clients', 'clients.id = users.client_id', 'left');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_unassigned_users() {
        $this->db->where('client_id IS NULL');
        $query = $this->db->get('users');
        return $query->result();
    }

    public function get_user($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    public function create_user($data) {
        return $this->db->insert('users', $data);
    }

    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    /**
     * Get recent users with a specific limit
     */
    public function get_recent_users($limit = 5) {
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('users');
        return $query->result();
    }
}