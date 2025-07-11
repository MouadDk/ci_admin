<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cleanup_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dossier_model');
        $this->load->model('File_model');
        $this->load->helper('file');
    }

    public function sync_folder_with_db() {
        $upload_base_path = FCPATH . 'uploads/dossiers/';
        
        // 1. Get all files from the upload directory
        $files_on_disk = get_filenames($upload_base_path);
        if ($files_on_disk === FALSE) { // get_filenames returns FALSE if directory doesn't exist or is not readable
            log_message('error', 'Dossier upload directory not found or not readable: ' . $upload_base_path);
            return;
        }

        // 2. Get all dossier records from the database
        $files_in_db = $this->File_model->get_all_files();
        
        $db_file_paths = [];
        $db_file_ids = [];
        foreach ($files_in_db as $file) {
            $db_file_paths[] = $file->path; // Store full relative path from DB
            $db_file_ids[$file->path] = $file->id; // Map path to ID for easy lookup
        }

        // --- Phase 1: Delete orphaned files on disk (files without a DB record) ---
        $disk_orphaned_count = 0;
        foreach ($files_on_disk as $file_name) {
            $relative_path = 'uploads/dossiers/' . $file_name; // Construct relative path as stored in DB
            if (!in_array($relative_path, $db_file_paths)) {
                $file_path_on_disk = $upload_base_path . $file_name;
                if (is_file($file_path_on_disk)) {
                    if (unlink($file_path_on_disk)) {
                        $disk_orphaned_count++;
                        log_message('debug', 'Deleted orphaned file from disk: ' . $file_path_on_disk);
                    } else {
                        log_message('error', 'Failed to delete orphaned file from disk: ' . $file_path_on_disk);
                    }
                }
            }
        }
        log_message('info', 'Cleanup Phase 1: Deleted ' . $disk_orphaned_count . ' orphaned files from disk.');

        // --- Phase 2: Delete orphaned DB records (records pointing to non-existent files) ---
        $db_orphaned_count = 0;
        foreach ($files_in_db as $file) {
            $file_name_from_db_path = basename($file->path);
            if (!in_array($file_name_from_db_path, $files_on_disk)) {
                if ($this->File_model->delete_file($file->id)) { // Use File_model to delete file records
                    $db_orphaned_count++;
                    log_message('debug', 'Deleted orphaned DB record for non-existent file: ' . $file->path);
                } else {
                    log_message('error', 'Failed to delete orphaned DB record for: ' . $file->path);
                }
            }
        }
        log_message('info', 'Cleanup Phase 2: Deleted ' . $db_orphaned_count . ' orphaned DB records.');

        log_message('info', 'Dossier cleanup completed. Disk orphaned: ' . $disk_orphaned_count . ', DB orphaned: ' . $db_orphaned_count);
    }
}