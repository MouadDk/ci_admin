<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get user information by their PME email address
     * @param string $pme_email The user's login email (from gmailPME column)
     * @return object|bool The user object or false if not found
     */
    public function get_user_by_login_email($pme_email) {
        // We select from the 'users' table where the 'gmailPME' column matches
        // Use LOWER() for case-insensitive email comparison
        $this->db->where('LOWER(gmailPME)', strtolower($pme_email));
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            return $query->row(); // Return the single user row
        }
        return false; // User not found
    }

}