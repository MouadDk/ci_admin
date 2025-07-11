<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Dashboard</title>

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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Administrator Panel</h1>
                        <div>
                            <a href="<?= base_url('clients/save') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                                <i class="fas fa-plus fa-sm text-white-50"></i> New Client
                            </a>
                            <a href="<?= base_url('user_management/save') ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
                                <i class="fas fa-plus fa-sm text-white-50"></i> New User
                            </a>
                            <a href="<?= base_url('admins/add') ?>" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                                <i class="fas fa-plus fa-sm text-white-50"></i> New Admin
                            </a>
                        </div>
                    </div>
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

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Total Clients Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Clients</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_clients ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Users Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_users ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Admins Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Admins</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_admins ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Establishment Distribution Chart -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Client Distribution by Type</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-pie">
                                        <canvas id="establishmentChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- City Distribution Chart -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Client Distribution by City</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-pie">
                                        <canvas id="cityChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Latest Clients and Users Tables -->
                    <div class="row">
                        <!-- Latest Clients Table -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Latest Clients</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Etablissement</th>
                                                    <th>Nom Etablissement</th>
                                                    <th>Ville</th>
                                                    <th>Responsable</th>
                                                    <th>Login Email</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($latest_clients)): ?>
                                                    <?php foreach ($latest_clients as $client): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($client->id ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td><?= htmlspecialchars($client->etablissement ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td><?= htmlspecialchars($client->nom_etablissement ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td><?= htmlspecialchars($client->ville ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td><?= htmlspecialchars($client->responsable ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td><?= htmlspecialchars($client->login_email ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td class="text-nowrap">
                                                                <a href="<?= base_url('clients/update/'.$client->id) ?>" class="btn btn-primary btn-sm mr-1">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </a>
                                                                <a href="<?= base_url('clients/delete/'.$client->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this client?');">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center">No clients found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Latest Clients with Open Dossiers Table -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Latest Clients with Open Dossiers</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Etablissement</th>
                                                    <th>Nom Etablissement</th>
                                                    <th>Dossier Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($clients_with_open_dossiers)): ?>
                                                    <?php foreach ($clients_with_open_dossiers as $client): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($client->id ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td><?= htmlspecialchars($client->etablissement ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td><?= htmlspecialchars($client->nom_etablissement ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td>
                                                                <span class="status-badge <?php
                                                                    $status_class = 'status-pending'; // Default to pending
                                                                    if ($client->dossier_status == 'valide') {
                                                                        $status_class = 'status-approved';
                                                                    } elseif ($client->dossier_status == 'non valide') {
                                                                        $status_class = 'status-rejected';
                                                                    }
                                                                    echo $status_class;
                                                                ?>">
                                                                    Status: <?= htmlspecialchars($client->dossier_status, ENT_QUOTES, 'UTF-8') ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <a href="<?= base_url('dossiers/client_dossiers/'.$client->id) ?>" class="btn btn-info btn-sm mr-1">
                                                                    <i class="fas fa-folder-open"></i> View Dossier
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No clients with open dossiers found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
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
                        <span>Copyright &copy; Your Website 2024</span>
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

    <!-- Page level plugins -->
    <script src="<?= base_url('assets/vendor/chart.js/Chart.min.js') ?>"></script>

    <!-- Charts initialization -->
    <script>
    // Establishment Distribution Chart
    var establishmentCtx = document.getElementById("establishmentChart");
    var establishmentChart = new Chart(establishmentCtx, {
        type: 'pie',
        data: {
            labels: <?= $establishment_labels ?>,
            datasets: [{
                data: <?= $establishment_data ?>,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#fd7e14', '#6f42c1', '#20c997', '#e83e8c', '#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40', '#c9cbcf', '#7b8d8e', '#a2b9bc', '#87a330', '#037d9a', '#564235', '#a39171', '#b85d6b', '#5a5b9f', '#f7cac9', '#f7786b', '#cbeaa6', '#5b5b5b'],
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            cutoutPercentage: 0,
        },
    });

    // City Distribution Chart
    var cityCtx = document.getElementById("cityChart");
    var cityChart = new Chart(cityCtx, {
        type: 'pie',
        data: {
            labels: <?= $city_labels ?>,
            datasets: [{
                data: <?= $city_data ?>,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#fd7e14', '#6f42c1', '#20c997', '#e83e8c', '#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40', '#c9cbcf', '#7b8d8e', '#a2b9bc', '#87a330', '#037d9a', '#564235', '#a39171', '#b85d6b', '#5a5b9f', '#f7cac9', '#f7786b', '#cbeaa6', '#5b5b5b'],
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            cutoutPercentage: 0,
        },
    });
    </script>
</body>
</html>