<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_table_data() {
    $this->db->select('id, etablissement, ville, responsable, telephone1, telephone2, contact_email');
    $query = $this->db->get('clients');
    return $query->result();
}
}