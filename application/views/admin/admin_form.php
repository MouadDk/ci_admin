<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Mocking data and functions for standalone testing if needed
if (!function_exists('base_url')) {
    function base_url($path = '') { return rtrim('./' . $path, '/'); }
}
if (!function_exists('validation_errors')) {
    function validation_errors() { return ''; /* Or return a mock error string to test */ }
}
if (!function_exists('set_value')) {
    function set_value($field, $default = '') { return $default; }
}
if (!function_exists('form_open')) {
    function form_open($action = '', $attributes = '', $hidden = []) {
        $action = base_url($action);
        return "<form action=\"$action\" method=\"post\" accept-charset=\"utf-8\">";
    }
}
if (!function_exists('form_close')) {
    function form_close($extra = '') { return "</form>" . $extra; }
}

// Determine the form's action URL based on whether we are adding or editing
$form_action = isset($admin) ? 'admins/update/' . $admin->id : 'admins/save';
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= isset($admin) ? 'Edit Admin' : 'Add New Admin' ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/css/admin-dashboard.css') ?>" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php $this->load->view('includes/admin_sidebar'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php $this->load->view('includes/admin_topbar'); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= isset($admin) ? 'Edit Admin' : 'Add New Admin' ?></h1>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Admin Information</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (validation_errors()) : ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <?= validation_errors() ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        </div>
                                    <?php endif; ?>

                                    <?= form_open($form_action); ?>
                                    
                                        <?php if(isset($admin)): ?>
                                            <!-- Hidden ID field for edit mode -->
                                            <input type="hidden" name="id" value="<?= $admin->id ?>">
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label for="username">Username *</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                value="<?= set_value('username', isset($admin) ? $admin->username : '') ?>"
                                                <?= isset($admin) ? 'readonly' : 'required' ?>>
                                            <?php if(isset($admin)): ?>
                                                <small class="form-text text-muted">Username cannot be changed directly. To change the username, please delete this admin and create a new one with the desired username.</small>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="password">Password <?= isset($admin) ? '' : '*' ?></label>
                                            <input type="password" class="form-control" id="password" name="password" <?= isset($admin) ? '' : 'required' ?>>
                                            <?php if(isset($admin)): ?>
                                                <small class="form-text text-muted">Leave blank to keep current password.</small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="pass_confirm">Confirm Password <?= isset($admin) ? '' : '*' ?></label>
                                            <input type="password" class="form-control" id="pass_confirm" name="pass_confirm" <?= isset($admin) ? '' : 'required' ?>>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Admin</button>
                                        <a href="<?= base_url('admins') ?>" class="btn btn-secondary">Cancel</a>
                                    <?= form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright © Your Website 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?= base_url('auth/logout') ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
</body>
</html>