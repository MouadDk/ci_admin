<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Determine the form's action URL based on whether we are adding or editing
$form_action = isset($user) ? 'user_management/update/' . $user->id : 'user_management/save';
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= isset($user) ? 'Edit User' : 'Add New User' ?></title>

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
                    <h1 class="h3 mb-4 text-gray-800"><?= isset($user) ? 'Edit User' : 'Add New User' ?></h1>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (validation_errors()) : ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <?= validation_errors() ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        </div>
                                    <?php endif; ?>

                                    <?= form_open($form_action); ?>
                                    
                                        <?php if(isset($user)): ?>
                                            <!-- Hidden ID field for edit mode -->
                                            <input type="hidden" name="id" value="<?= $user->id ?>">
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label for="client_id">Associated Client *</label>
                                            <select class="form-control" id="client_id" name="client_id" required>
                                                <option value="" disabled selected>Select a Client...</option>
                                                <?php if (!empty($clients)): ?>
                                                    <?php foreach ($clients as $client): ?>
                                                        <option value="<?= $client->id ?>" <?= set_select('client_id', $client->id, isset($user) && $user->client_id == $client->id) ?>>
                                                            <?= htmlspecialchars($client->nom_etablissement, ENT_QUOTES, 'UTF-8') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="gmailPME">Login Email *</label>
                                            <input type="email" class="form-control" id="gmailPME" name="gmailPME"
                                                value="<?= set_value('gmailPME', isset($user) ? $user->gmailPME : '') ?>"
                                                required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="password_pme">PME Password *</label>
                                            <input type="password" class="form-control" id="password_pme" name="password_pme" <?= isset($user) ? '' : 'required' ?>>
                                            <?php if(isset($user)): ?>
                                                <small class="form-text text-muted">Leave blank to keep current PME password.</small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="pass_confirm_pme">Confirm PME Password *</label>
                                            <input type="password" class="form-control" id="pass_confirm_pme" name="pass_confirm_pme" <?= isset($user) ? '' : 'required' ?>>
                                        </div>
  
                                        <div class="form-group">
                                            <label for="password_gmail">Gmail Password *</label>
                                            <input type="password" class="form-control" id="password_gmail" name="password_gmail" <?= isset($user) ? '' : 'required' ?>>
                                            <?php if(isset($user)): ?>
                                                <small class="form-text text-muted">Leave blank to keep current Gmail password.</small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="pass_confirm_gmail">Confirm Gmail Password *</label>
                                            <input type="password" class="form-control" id="pass_confirm_gmail" name="pass_confirm_gmail" <?= isset($user) ? '' : 'required' ?>>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save User</button>
                                        <a href="<?= base_url('user_management') ?>" class="btn btn-secondary">Cancel</a>
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