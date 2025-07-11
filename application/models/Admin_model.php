<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_admin_by_username($username) {
        $this->db->where('LOWER(username)', strtolower($username));
        $query = $this->db->get('admins');
        return ($query->num_rows() == 1) ? $query->row() : false;
    }

    public function get_all_admins() {
        $query = $this->db->get('admins');
        return $query->result();
    }

    public function get_admin($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('admins');
        return $query->row();
    }

    public function hash_password($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function create_admin($data) {
        if (isset($data['password'])) {
            $data['password'] = $this->hash_password($data['password']);
        }
        return $this->db->insert('admins', $data);
    }

    public function update_admin($id, $data) {
        if (isset($data['password'])) {
            $data['password'] = $this->hash_password($data['password']);
        }
        $this->db->where('id', $id);
        return $this->db->update('admins', $data);
    }

    public function delete_admin($id) {
        $this->db->where('id', $id);
        return $this->db->delete('admins');
    }

    public function verify_password($id, $password) {
        $admin = $this->get_admin($id);
        if ($admin) {
            return password_verify($password, $admin->password);
        }
        return false;
    }
}