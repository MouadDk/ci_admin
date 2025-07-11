<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Clients</title>

    <!-- Custom fonts for this template -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= base_url('assets/css/admin-dashboard.css') ?>" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

    <style>
        .d-flex.flex-nowrap.align-items-center > .btn {
            margin-right: 5px; /* Adjust as needed */
        }
        .d-flex.flex-nowrap.align-items-center > .btn:last-child {
            margin-right: 0;
        }
        .deactivated-client {
            color: #888; /* Gray out text */
            background-color: #f8f9fa; /* Light gray background */
        }
        .deactivated-client a {
            color: #888; /* Gray out links within the row */
        }
        /* Status Badge Styles */
        .status-badge {
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 20px;
            color: #fff;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }
        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .status-pending {
            background: linear-gradient(45deg, #f6c23e, #f4b942);
        }
        .status-approved {
            background: linear-gradient(45deg, #1cc88a, #17a673);
        }
        .status-rejected {
            background: linear-gradient(45deg, #e74a3b, #dc3545);
        }
        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }
        .toast {
            opacity: 1 !important;
            min-width: 300px;
        }
        .toast-header {
            font-weight: 600;
        }
    </style>
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
                    <h1 class="h3 mb-2 text-gray-800">Clients</h1>
                    
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                         <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Clients Data</h6>
                            <a href="<?= base_url('clients/add') ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> New Client
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- This hidden div holds our custom filter. JS will move it to the correct place. -->
                             <div id="customFilterContainer" class="d-none">
                                <label class="ml-2">
                                    <select name="establishmentFilter" id="establishmentFilter" class="form-control form-control-sm">
                                        <option value="">All Establishments</option>
                                        <option value="Hotel">Hotel</option>
                                        <option value="Restaurant">Restaurant</option>
                                        <option value="Agence de voyage">Agence de voyage</option>
                                        <option value="Transport touristique">Transport touristique</option>
                                    </select>
                                </label>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ICE</th>
                                            <th>Etablissement</th>
                                            <th>Nom Etablissement</th>
                                            <th>Dossier Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($clients)): ?>
                                            <?php foreach ($clients as $client): ?>
                                                <tr class="<?= ($client->is_active == 0) ? 'deactivated-client' : '' ?>">
                                            <td><?= htmlspecialchars($client->ice ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($client->etablissement ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($client->nom_etablissement ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                            <td>
                                                <?php if (!empty($client->dossier_status)): ?>
                                                    <?php
                                                        $status_class = '';
                                                        if ($client->dossier_status == 'valide') {
                                                            $status_class = 'status-approved';
                                                        } else if ($client->dossier_status == 'non valide') {
                                                            $status_class = 'status-rejected';
                                                        } else {
                                                            $status_class = 'status-pending'; // For 'Pending' or other statuses
                                                        }
                                                    ?>
                                                    <span class="status-badge <?= $status_class ?>"
                                                          data-dossier-id="<?= $client->dossier_id ?>"
                                                          data-current-status="<?= $client->dossier_status ?>"
                                                          data-type="global"
                                                          data-interactive="true"
                                                          style="cursor: pointer;">
                                                        <?= htmlspecialchars($client->dossier_status, ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">No Dossier</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-nowrap align-items-center">
                                                    <!-- More Info Button (first) -->
                                                    <button type="button" class="btn btn-info btn-sm mr-1 view-more-info-btn"
                                                            data-client-id="<?= $client->id ?>"
                                                            data-ville="<?= htmlspecialchars($client->ville ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-region="<?= htmlspecialchars($client->region ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-responsable="<?= htmlspecialchars($client->responsable ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-telephone1="<?= htmlspecialchars($client->telephone1 ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-telephone2="<?= htmlspecialchars($client->telephone2 ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-gmailpme="<?= htmlspecialchars($client->gmailPME ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-type-legal="<?= htmlspecialchars($client->type ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-provenance="<?= htmlspecialchars($client->provenance ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-referal="<?= htmlspecialchars($client->referal ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-dossier-status="<?= htmlspecialchars($client->dossier_status ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-password-pme="<?= htmlspecialchars($client->password_pme ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-password-gmail="<?= htmlspecialchars($client->password_gmail ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-client-id="<?= htmlspecialchars($client->id ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                            title="More Info">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>

                                                    <!-- Edit Button -->
                                                    <a href="<?= base_url('clients/edit/'.$client->id) ?>" class="btn btn-primary btn-sm mr-1 <?= ($client->is_active == 0) ? 'disabled' : '' ?>" <?= ($client->is_active == 0) ? 'aria-disabled="true"' : '' ?> title="Edit Client">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Dossier Button -->
                                                    <?php if (!empty($client->dossier_status)): ?>
                                                    <a href="<?= base_url('dossiers/client_dossiers/'.$client->id) ?>" class="btn btn-warning btn-sm mr-1 <?= ($client->is_active == 0) ? 'disabled' : '' ?>" <?= ($client->is_active == 0) ? 'aria-disabled="true"' : '' ?> title="View Dossier">
                                                        <i class="fas fa-folder"></i>
                                                    </a>
                                                    <?php else: ?>
                                                    <a href="<?= base_url('dossiers/client_dossiers/'.$client->id) ?>" class="btn btn-success btn-sm mr-1 <?= ($client->is_active == 0) ? 'disabled' : '' ?>" <?= ($client->is_active == 0) ? 'aria-disabled="true"' : '' ?> title="Create Dossier">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                    <?php endif; ?>

                                                    <!-- Activate/Deactivate Buttons -->
                                                    <?php if ($client->is_active): ?>
                                                    <button type="button" class="btn btn-danger btn-sm deactivate-client-btn" data-id="<?= $client->id ?>" title="Deactivate Client">
                                                      <i class="fas fa-ban"></i>
                                                    </button>
                                                    <?php else: ?>
                                                    <button type="button" class="btn btn-success btn-sm activate-client-btn" data-id="<?= $client->id ?>" title="Activate Client">
                                                      <i class="fas fa-check-circle"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
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

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusUpdateModal" tabindex="-1" role="dialog" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="statusUpdateModalLabel">
                        <i class="fas fa-edit"></i> Update Dossier Status
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Select the new status for this dossier:</p>
                    <input type="hidden" id="dossierToUpdateId">
                    <input type="hidden" id="updateType"> <!-- New hidden input for type -->
                    <div class="form-group">
                        <label for="newStatus"><i class="fas fa-flag"></i> Status</label>
                        <select class="form-control" id="newStatus">
                            <option value="valide">🟢 valide</option>
                            <option value="non valide">🔴 non valide</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn btn-primary btn-enhanced" id="confirmUpdateStatusBtn">
                        <i class="fas fa-check"></i> Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- More Info Modal -->
    <div class="modal fade" id="moreInfoModal" tabindex="-1" role="dialog" aria-labelledby="moreInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="moreInfoModalLabel">
                        <i class="fas fa-info-circle"></i> Client Information
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="moreInfoModalBody" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Dynamic content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Deactivate Client Confirmation Modal -->
    <div class="modal fade" id="deactivateClientModal" tabindex="-1" role="dialog" aria-labelledby="deactivateClientModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deactivateClientModalLabel">Confirm Client Deactivation</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to deactivate this client? This will move them to the bottom of the list.</p>
                    <p class="text-danger">To confirm, please enter your current password:</p>
                    <div class="form-group">
                        <input type="password" class="form-control" id="adminPasswordDeactivate" placeholder="Your Password">
                    </div>
                    <input type="hidden" id="clientToDeactivateId">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" id="confirmDeactivateClientBtn">Deactivate Client</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate Client Confirmation Modal -->
    <div class="modal fade" id="activateClientModal" tabindex="-1" role="dialog" aria-labelledby="activateClientModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activateClientModalLabel">Confirm Client Activation</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to activate this client? This will make them active again.</p>
                    <p class="text-danger">To confirm, please enter your current password:</p>
                    <div class="form-group">
                        <input type="password" class="form-control" id="adminPasswordActivate" placeholder="Your Password">
                    </div>
                    <input type="hidden" id="clientToActivateId">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" id="confirmActivateClientBtn">Activate Client</button>
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
                        <span aria-hidden="true">&times;</span>
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
            // Initialize the DataTable
            var table = $('#dataTable').DataTable({
                "order": [[0, "desc"]],
                "columnDefs": [
                    { "orderable": false, "targets": 4 } // Actions column (now at index 4)
                ]
            });

            // Get the HTML content of our custom filter
            var customFilter = $("#customFilterContainer").html();
            
            // Append the custom filter to the DataTables-generated filter container
            $("#dataTable_filter").append(customFilter);
            
            // Remove the original hidden container
            $("#customFilterContainer").remove();

            // Add an event listener to the now-visible dropdown
            $('#establishmentFilter').on('change', function() {
                // Filter by the 'Etablissement' column (index 1)
                table.column(1).search(this.value).draw();
            });

            // --- Toast Notification Function ---
            function showToast(message, type) {
                var bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
                var icon = type === 'success' ? 'fas fa-check-circle' : type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-info-circle';

                var toastHtml = `
                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000">
                        <div class="toast-header ${bgClass} text-white">
                            <i class="${icon} mr-2"></i>
                            <strong class="mr-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                            <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            ${message}
                        </div>
                    </div>`;
                $('.toast-container').append(toastHtml);
                $('.toast').last().toast('show').on('hidden.bs.toast', function () {
                    $(this).remove();
                });
            }

            // --- Status Update ---
            // Handle clicks on the main dossier status badge (data-interactive="true")
            $(document).on('click', '.status-badge[data-interactive="true"]', function() {
                var updateType = $(this).attr('data-type');
                var idToUpdate = $(this).attr('data-dossier-id');
                var currentStatus = $(this).attr('data-current-status');
                
                $('#dossierToUpdateId').val(idToUpdate);
                $('#updateType').val(updateType);
                $('#newStatus').val(currentStatus);
                $('#statusUpdateModal').modal('show');
            });

            $('#confirmUpdateStatusBtn').on('click', function() {
                var idToUpdate = $('#dossierToUpdateId').val();
                var newStatus = $('#newStatus').val();
                var updateType = $('#updateType').val(); // Get the type of update
                var btn = $(this);

                btn.prop('disabled', true);

                var postData = {
                    status: newStatus,
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                };
                var url;

                if (updateType === 'global') {
                    postData.dossier_id = idToUpdate;
                    url = '<?= base_url('dossiers/update_dossier_status_global') ?>'; // New function for global status
                } else {
                    postData.sub_dossier_id = idToUpdate;
                    url = '<?= base_url('dossiers/update_sub_dossier_status') ?>';
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(result) {
                        if (result.status === 'success') {
                            showToast(result.message, 'success');
                            $(document.activeElement).blur();
                            $('#statusUpdateModal').modal('hide');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            showToast('Failed to update status: ' + result.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        showToast('An AJAX error occurred: ' + error, 'error');
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                    }
                });
            });

            // Handle deactivate button click to populate modal
            $('#dataTable').on('click', '.deactivate-client-btn', function() {
                var clientId = $(this).data('id');
                $('#clientToDeactivateId').val(clientId);
                $('#deactivateClientModal').modal('show');
            });
 
            // Handle confirm deactivate button click in modal
            $('#confirmDeactivateClientBtn').on('click', function() {
                var clientId = $('#clientToDeactivateId').val();
                var adminPassword = $('#adminPasswordDeactivate').val();
 
                // Create a form dynamically and submit it
                var form = $('<form action="<?= base_url('clients/deactivate/') ?>' + clientId + '" method="post"></form>');
                form.append('<input type="hidden" name="id" value="' + clientId + '">');
                form.append('<input type="hidden" name="admin_password" value="' + adminPassword + '">');
                $('body').append(form);
                form.submit();
            });
 
            // Handle activate button click to populate modal
            $('#dataTable').on('click', '.activate-client-btn', function() {
                var clientId = $(this).data('id');
                $('#clientToActivateId').val(clientId); // Using a different ID for activation modal
                $('#activateClientModal').modal('show');
            });
 
            // Handle confirm activate button click in modal
            $('#confirmActivateClientBtn').on('click', function() {
                var clientId = $('#clientToActivateId').val();
                var adminPassword = $('#adminPasswordActivate').val(); // Using a different ID for activation password field
 
                // Create a form dynamically and submit it
                var form = $('<form action="<?= base_url('clients/activate/') ?>' + clientId + '" method="post"></form>');
                form.append('<input type="hidden" name="id" value="' + clientId + '">');
                form.append('<input type="hidden" name="admin_password" value="' + adminPassword + '">');
                $('body').append(form);
                form.submit();
            });

            // Handle "More Info" button click
            $('#dataTable').on('click', '.view-more-info-btn', function() {
                var data = $(this).data(); // Get all data attributes
                var infoHtml = '';

                // General Information
                infoHtml += '<div class="card mb-3 shadow-sm">';
                infoHtml += '<div class="card-header bg-primary text-white"><i class="fas fa-user-circle mr-2"></i>General Information</div>';
                infoHtml += '<div class="card-body">';
                infoHtml += '<ul class="list-group list-group-flush">';
                infoHtml += '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>Responsable:</strong> <span>' + (data.responsable ?? 'N/A') + '</span></li>';
                infoHtml += '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>Telephone 1:</strong> <span>' + (data.telephone1 ?? 'N/A') + '</span></li>';
                infoHtml += '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>Telephone 2:</strong> <span>' + (data.telephone2 ?? 'N/A') + '</span></li>';
                infoHtml += '</ul>';
                infoHtml += '</div></div>';

                // Location Information
                infoHtml += '<div class="card mb-3 shadow-sm">';
                infoHtml += '<div class="card-header bg-info text-white"><i class="fas fa-map-marker-alt mr-2"></i>Location Details</div>';
                infoHtml += '<div class="card-body">';
                infoHtml += '<ul class="list-group list-group-flush">';
                infoHtml += '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>Ville:</strong> <span>' + (data.ville ?? 'N/A') + '</span></li>';
                infoHtml += '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>Region:</strong> <span>' + (data.region ?? 'N/A') + '</span></li>';
                infoHtml += '</ul>';
                infoHtml += '</div></div>';

                // Other Client Specifics
                infoHtml += '<div class="card mb-3 shadow-sm">';
                infoHtml += '<div class="card-header bg-success text-white"><i class="fas fa-tags mr-2"></i>Client Specifics</div>';
                infoHtml += '<div class="card-body">';
                infoHtml += '<ul class="list-group list-group-flush">';
                infoHtml += '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>Type Legal:</strong> <span>' + (data.typeLegal ?? 'N/A') + '</span></li>';
                infoHtml += '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>Provenance:</strong> <span>' + (data.provenance ?? 'N/A') + '</span></li>';
                infoHtml += '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>Referal:</strong> <span>' + (data.referal ?? 'N/A') + '</span></li>';
                infoHtml += '</ul>';
                infoHtml += '</div></div>';

                // Access Credentials
                infoHtml += '<div class="card mb-3 shadow-sm">';
                infoHtml += '<div class="card-header bg-warning text-white"><i class="fas fa-key mr-2"></i>Access Credentials</div>';
                infoHtml += '<div class="card-body">';
                infoHtml += '<ul class="list-group list-group-flush">';
                infoHtml += '<li class="list-group-item"><strong>Gmail PME:</strong> <span>' + (data.gmailpme ?? 'N/A') + '</span></li>';
                infoHtml += '<li class="list-group-item">';
                infoHtml += '<div><strong>PME Password:</strong></div>';
                infoHtml += '<div><input type="text" class="form-control-plaintext" value="' + (data.passwordPme ?? 'N/A') + '" readonly></div>';
                infoHtml += '</li>';
                infoHtml += '<li class="list-group-item">';
                infoHtml += '<div><strong>Gmail Password:</strong></div>';
                infoHtml += '<div><input type="text" class="form-control-plaintext" value="' + (data.passwordGmail ?? 'N/A') + '" readonly></div>';
                infoHtml += '</li>';
                infoHtml += '</ul>';
                infoHtml += '</div></div>';

                // Sub-dossier 2 Actions
                infoHtml += '<div class="card mb-3 shadow-sm">';
                infoHtml += '<div class="card-header bg-secondary text-white"><i class="fas fa-tasks mr-2"></i>Dossier 2 Actions</div>';
                infoHtml += '<div class="card-body">';
                infoHtml += '<div id="dossier2Actions"><i class="fas fa-spinner fa-spin"></i> Loading actions...</div>'; // Placeholder for actions
                infoHtml += '</div></div>';
                
                $('#moreInfoModalBody').html(infoHtml);
                $('#moreInfoModal').modal('show');

                // Fetch and display actions for Dossier 2
                var clientId = data.clientId;
                if (clientId) {
                    $.ajax({
                        url: '<?= base_url('dossiers/get_sub_dossier_actions_by_client/') ?>' + clientId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                var contentHtml = '';
                                var contentHtml = '';
                                if (response.actions && response.actions.length > 0) {
                                    contentHtml += '<ul class="list-group list-group-flush">';
                                    $.each(response.actions, function(index, action) {
                                        contentHtml += '<li class="list-group-item d-flex justify-content-between align-items-center"><i class="fas fa-check-circle text-success mr-2"></i><span>' + action + '</span></li>';
                                    });
                                    contentHtml += '</ul>';
                                }

                                if (response.external_description && response.external_description.trim() !== '') {
                                    contentHtml += '<div class="mt-3">';
                                    contentHtml += '<strong>External Description:</strong>';
                                    // Use text() to safely insert content, preventing XSS
                                    contentHtml += '<p id="externalDescriptionText"></p>';
                                    contentHtml += '</div>';
                                }

                                if (contentHtml === '') {
                                    $('#dossier2Actions').html('<div class="alert alert-info" role="alert"><i class="fas fa-info-circle mr-2"></i>No actions or external description defined for this client.</div>');
                                } else {
                                    $('#dossier2Actions').html(contentHtml);
                                    // Set the text content after appending to the DOM
                                    if (response.external_description && response.external_description.trim() !== '') {
                                        $('#externalDescriptionText').text(response.external_description);
                                    }
                                }
                            } else {
                                $('#dossier2Actions').html('<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle mr-2"></i>Error loading actions. Please try again.</div>');
                            }
                        },
                        error: function() {
                            $('#dossier2Actions').html('<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle mr-2"></i>An unexpected error occurred while loading actions.</div>');
                        }
                    });
                } else {
                    $('#dossier2Actions').html('<div class="alert alert-warning" role="alert"><i class="fas fa-exclamation-triangle mr-2"></i>Dossier 2 not available for this client.</div>');
                }
            });
        });
    </script>
</body>
</html>