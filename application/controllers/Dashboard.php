<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('client_model');
        $this->load->model('user_model');
        $this->load->model('admin_model'); // Load Admin_model

        // Check if the user is logged in
        if (!$this->session->userdata('is_logged_in')) {
            redirect('auth');
        }
    }

    public function admin() {
        // Check if the logged-in user has the 'admin' role
        if ($this->session->userdata('role') !== 'admin') {
            redirect('auth'); // Redirect to login if not an admin
        }


        // Get data for admin dashboard
        $data = [
            'total_clients' => count($this->client_model->get_all_clients()),
            'total_users' => count($this->user_model->get_all_users()),
            'total_admins' => count($this->admin_model->get_all_admins()), // Get total admins
            'latest_clients' => $this->client_model->get_recent_clients(5),
            'latest_users' => $this->user_model->get_recent_users(5), // Keep this for now, will replace in view
            'clients_with_open_dossiers' => $this->client_model->get_recent_clients_with_open_dossiers(5)
        ];

        // Get establishment type distribution for charts
        $establishment_stats = $this->client_model->get_client_count_by_type();
        $establishment_labels = [];
        $establishment_data = [];
        foreach ($establishment_stats as $stat) {
            $establishment_labels[] = $stat->etablissement;
            $establishment_data[] = $stat->count;
        }
        $data['establishment_labels'] = json_encode($establishment_labels);
        $data['establishment_data'] = json_encode($establishment_data);

        // Get city distribution for charts
        $city_stats = $this->client_model->get_client_count_by_city();
        $city_labels = [];
        $city_data = [];
        foreach ($city_stats as $stat) {
            $city_labels[] = $stat->ville;
            $city_data[] = $stat->count;
        }
        $data['city_labels'] = json_encode($city_labels);
        $data['city_data'] = json_encode($city_data);

        // Load the admin dashboard view
        $this->load->view('admin/admin_dashboard', $data);
    }
}
