<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cleanup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cleanup_model'); // Load the new Cleanup_model
    }

    // This method is intended to be called internally, via a cron job, or by an authorized admin.
    // For this example, it's accessible via a URL, but in a production environment,
    // you should secure it to prevent unauthorized access.
    public function sync_dossiers() {
        if (!$this->input->is_cli_request() && !$this->session->userdata('login_id')) { // Use login_id for session check
            // If not CLI and not logged in, just return false or throw an error.
            // The calling controller (Dashboard) will handle redirection and messages.
            return;
        }

        $this->Cleanup_model->sync_folder_with_db(); // Call the synchronization method from the model

        if ($this->input->is_cli_request()) {
            echo "Orphaned files cleanup completed.\n";
        }
        // No flashdata or redirect here, handled by the calling controller
    }

}