<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Mocking data and functions for standalone testing if needed
if (!isset($admins)) {
    $admins = [
        (object)['id' => 1, 'username' => 'admin@example.com'],
        (object)['id' => 2, 'username' => 'superadmin@example.com'],
    ];
}
if (!function_exists('base_url')) {
    function base_url($path = '') { return rtrim('./' . $path, '/'); }
}
if (!isset($this->session)) {
    $this->session = new class { public function flashdata($key) { return null; } };
}
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admins</title>

    <!-- Custom fonts for this template -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= base_url('assets/css/admin-dashboard.css') ?>" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
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
                    <h1 class="h3 mb-2 text-gray-800">Admins</h1>
                    
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('error') ?>
                             <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                    <?php endif; ?>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Admins Data</h6>
                            <div>
                                <a href="<?= base_url('admins/add') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> New Admin
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($admins)): ?>
                                            <?php foreach ($admins as $admin): ?>
                                                <tr <?= ($admin->username === $this->session->userdata('login_username')) ? 'class="table-info font-weight-bold"' : '' ?>>
                                                    <td><?= htmlspecialchars($admin->id, ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td>
                                                       <?= htmlspecialchars($admin->username ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                                                       <?php if ($admin->username === $this->session->userdata('login_username')): ?>
                                                           <span class="badge badge-success ml-2">You</span>
                                                       <?php endif; ?>
                                                   </td>
                                                    <td>
                                                        <?php if ($admin->username !== $this->session->userdata('login_username')): ?>
                                                            <a href="<?= base_url('admins/edit/'.$admin->id) ?>" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                            <button type="button" class="btn btn-danger btn-sm delete-admin-btn" data-id="<?= $admin->id ?>">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        <?php else: ?>
                                                            <!-- Actions for the currently logged-in admin are removed -->
                                                            <span class="text-muted">No actions available</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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

    <!-- Delete Admin Confirmation Modal -->
    <div class="modal fade" id="deleteAdminModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAdminModalLabel">Confirm Admin Deletion</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this admin? This action cannot be undone.</p>
                    <p class="text-danger">To confirm, please enter your current password:</p>
                    <div class="form-group">
                        <input type="password" class="form-control" id="currentPassword" placeholder="Current Password">
                    </div>
                    <input type="hidden" id="adminToDeleteId">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" id="confirmDeleteBtn">Delete Admin</button>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Page level plugins -->
    <script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "columnDefs": [
                    { "orderable": false, "targets": 2 } // Disable sorting for the "Actions" column (index 2)
                ]
            });

            // Handle delete button click to populate modal
            $('#dataTable').on('click', '.delete-admin-btn', function() {
                var adminId = $(this).data('id');
                $('#adminToDeleteId').val(adminId);
                $('#deleteAdminModal').modal('show');
            });

            // Handle confirm delete button click in modal
            $('#confirmDeleteBtn').on('click', function() {
                var adminId = $('#adminToDeleteId').val();
                var currentPassword = $('#currentPassword').val();

                // Create a form dynamically and submit it
                var form = $('<form action="<?= base_url('admins/delete/') ?>' + adminId + '" method="post"></form>');
                form.append('<input type="hidden" name="id" value="' + adminId + '">');
                form.append('<input type="hidden" name="current_password" value="' + currentPassword + '">');
                $('body').append(form);
                form.submit();
            });
        });

    </script>
</body>
</html>