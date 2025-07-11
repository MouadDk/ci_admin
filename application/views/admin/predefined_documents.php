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

    <title>Documents Requis - <?= htmlspecialchars($client->nom_etablissement ?? $client->responsable, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Custom fonts for this template -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= base_url('assets/css/admin-dashboard.css') ?>" rel="stylesheet">

    <style>
        /* Status Badge Styles from client-dashboard.css */
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

        /* Existing styles for predefined_documents.php */
        .document-card {
            border: 2px solid #e3e6f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            background: #fff;
            position: relative;
            min-height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .document-card:hover {
            border-color: #4e73df;
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.15);
            transform: translateY(-2px);
        }

        .document-card.uploaded {
            border-color: #1cc88a;
            background: linear-gradient(135deg, #f8fff9 0%, #e8f8f0 100%);
        }

        .document-card.uploaded:hover {
            border-color: #17a673;
        }

        .document-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #5a5c69;
        }

        .document-card.uploaded .document-icon {
            color: #1cc88a;
        }

        .document-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 10px;
        }

        .document-card.uploaded .document-title {
            color: #1cc88a;
        }

        .upload-area {
            border: 2px dashed #d1d3e2;
            border-radius: 8px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fc;
            width: 100%;
            margin-top: 10px;
        }

        .upload-area:hover {
            border-color: #4e73df;
            background: #f0f3ff;
        }

        .upload-area.dragover {
            border-color: #1cc88a;
            background: #f0fff4;
        }

        .check-mark {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #1cc88a;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .required-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #e74a3b;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .upload-btn {
            background: #4e73df;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            background: #2e59d9;
            transform: translateY(-1px);
        }

        .file-info {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .progress-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fc;
            border-radius: 8px;
            border-left: 4px solid #4e73df;
        }

        .progress-bar-custom {
            height: 8px;
            border-radius: 4px;
            background: #e3e6f0;
            overflow: hidden;
            margin-top: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4e73df, #1cc88a);
            transition: width 0.3s ease;
        }

        .action-buttons {
            margin-top: 30px;
            text-align: center;
        }

        .btn-enhanced {
            border-radius: 6px;
            font-weight: 500;
            padding: 10px 20px;
            transition: all 0.2s ease;
            margin: 0 10px;
        }

        .btn-enhanced:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h1 class="h3 mb-0 text-gray-800">Documents Requis</h1>
                                <p class="mb-0 text-muted">Client: <?= htmlspecialchars($client->nom_etablissement ?? $client->responsable, ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="mb-0 text-muted">Dossier: <?= htmlspecialchars($dossier->name, ENT_QUOTES, 'UTF-8') ?>
                                    <span class="status-badge <?php
                                        $status_class = 'status-pending'; // Default to pending
                                        if ($dossier->status == 'valide') {
                                            $status_class = 'status-approved';
                                        } elseif ($dossier->status == 'non valide') {
                                            $status_class = 'status-rejected';
                                        }
                                        echo $status_class;
                                    ?>"
                                          data-id="<?= $dossier->id ?>"
                                          data-status="<?= $dossier->status ?>"
                                          title="Click to change dossier status">
                                        Status: <?= htmlspecialchars($dossier->status, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </p>
                            </div>
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


                    <!-- Documents Grid -->
                    <div class="row mt-4">
                        <?php foreach ($predefined_docs as $doc_type => $doc_info): ?>
                            <?php $is_uploaded = isset($existing_docs[$doc_type]); ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="document-card <?= $is_uploaded ? 'uploaded' : '' ?>" data-doc-type="<?= $doc_type ?>">
                                    <?php if ($doc_info['required']): ?>
                                        <div class="required-badge">REQUIS</div>
                                    <?php endif; ?>
                                    
                                    <?php if ($is_uploaded): ?>
                                        <div class="check-mark">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    <?php endif; ?>

                                    <div class="document-icon">
                                        <i class="<?= $doc_info['icon'] ?>"></i>
                                    </div>
                                    
                                    <div class="document-title">
                                        <?= htmlspecialchars($doc_info['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </div>

                                    <?php if ($is_uploaded): ?>
                                        <div class="file-info">
                                            <i class="fas fa-file"></i> Document téléchargé
                                            <br>
                                            <small class="text-muted">
                                                <?= date('d/m/Y H:i', strtotime($existing_docs[$doc_type]->created_at)) ?>
                                            </small>
                                            <div class="mt-2">
                                                <a href="<?= base_url($existing_docs[$doc_type]->path) ?>" 
                                                   class="btn btn-sm btn-info" target="_blank">
                                                    <i class="fas fa-download"></i> Télécharger
                                                </a>
                                                <button class="btn btn-sm btn-warning replace-btn" 
                                                        data-doc-type="<?= $doc_type ?>">
                                                           <i class="fas fa-sync"></i> Remplacer
                                                       </button>
                                                       <button class="btn btn-sm btn-danger delete-btn"
                                                               data-file-id="<?= $existing_docs[$doc_type]->id ?>"
                                                               data-doc-name="<?= htmlspecialchars($doc_info['name'], ENT_QUOTES, 'UTF-8') ?>">
                                                           <i class="fas fa-trash"></i> Supprimer
                                                       </button>
                                                   </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="upload-area" data-doc-type-upload="<?= $doc_type ?>">
                                            <i class="fas fa-cloud-upload-alt mb-2"></i>
                                            <p class="mb-1"><strong>Cliquer pour télécharger</strong></p>
                                            <p class="mb-0 text-muted">ou glisser-déposer le fichier</p>
                                        </div>
                                        <input type="file" id="file_<?= $doc_type ?>" style="display: none;" data-doc-type-input="<?= $doc_type ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="<?= base_url('dossiers/client_dossiers/' . $client->id) ?>" 
                           class="btn btn-primary btn-enhanced">
                            <i class="fas fa-plus"></i> Ajouter Documents Supplémentaires
                        </a>
                        <a href="<?= base_url('clients') ?>" 
                           class="btn btn-secondary btn-enhanced">
                            <i class="fas fa-arrow-left"></i> Retour aux Clients
                        </a>
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

    <!-- Replace Document Modal -->
    <div class="modal fade" id="replaceDocumentModal" tabindex="-1" role="dialog" aria-labelledby="replaceDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="replaceDocumentModalLabel">
                        <i class="fas fa-sync"></i> Remplacer le Document
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Sélectionnez un nouveau fichier pour remplacer le document existant:</p>
                    <input type="file" id="replaceFileInput" class="form-control">
                    <input type="hidden" id="replaceDocType">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button class="btn btn-warning" id="confirmReplaceBtn">
                        <i class="fas fa-sync"></i> Remplacer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirmer la Suppression
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer le document <strong id="documentNameToDelete"></strong> ? Cette action est irréversible.</p>
                    <input type="hidden" id="fileIdToDelete">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusUpdateModal" tabindex="-1" role="dialog" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="statusUpdateModalLabel">
                        <i class="fas fa-edit"></i> Update Dossier Status
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Select the new status for this dossier:</p>
                    <input type="hidden" id="dossierToUpdateId">
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
                        <span class="loading-spinner" id="statusSpinner"></span>
                        <i class="fas fa-check"></i> Update Status
                    </button>
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

    <script>
        function triggerFileInput(docType) {
            document.getElementById('file_' + docType).click();
        }

        function uploadDocument(docType, input) {
            if (input.files.length > 0) {
                var formData = new FormData();
                formData.append('userfile', input.files[0]);
                formData.append('document_type', docType);
                formData.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash(); ?>');

                // Show loading state
                var card = document.querySelector('[data-doc-type="' + docType + '"]');
                card.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><br><p class="mt-2">Téléchargement en cours...</p></div>';

                fetch('<?= base_url('dossiers/upload_predefined_document/' . $client->id) ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) // Parse JSON response
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        location.reload(); // Reload to show flashdata error message
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    location.reload(); // Reload to show flashdata error message
                });
            }
        }

        $(document).ready(function() {
            // Event listener for clicking the upload area
            $(document).on('click', '.upload-area', function() {
                var docType = $(this).data('doc-type-upload');
                $('#file_' + docType).trigger('click');
            });

            // Event listener for file input change
            $(document).on('change', 'input[type="file"][data-doc-type-input]', function() {
                var docType = $(this).data('doc-type-input');
                uploadDocument(docType, this);
            });

            // --- Status Update ---
            $(document).on('click', '.status-badge', function() {
                var dossierId = $(this).data('id');
                var currentStatus = $(this).data('status');
                $('#dossierToUpdateId').val(dossierId);
                $('#newStatus').val(currentStatus);
                $('#statusUpdateModal').modal('show');
            });

            $('#confirmUpdateStatusBtn').on('click', function() {
                var dossierId = $('#dossierToUpdateId').val();
                var newStatus = $('#newStatus').val();
                var btn = $(this);
                
                // Show spinner and disable button
                $('#statusSpinner').show();
                btn.prop('disabled', true);

                $.ajax({
                    url: '<?= base_url('dossiers/update_status') ?>',
                    type: 'POST',
                    data: {
                        dossier_id: dossierId,
                        status: newStatus,
                        '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
                            location.reload(); // Reload to show updated status and flashdata
                        } else {
                            location.reload(); // Reload to show flashdata error message
                        }
                    },
                    error: function() {
                        location.reload(); // Reload to show flashdata error message
                    },
                    complete: function() {
                        $('#statusSpinner').hide();
                        btn.prop('disabled', false);
                    }
                });
            });

            // Handle drag and drop
            var uploadAreas = document.querySelectorAll('.upload-area');
            
            uploadAreas.forEach(function(area) {
                area.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('dragover');
                });

                area.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                });

                area.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                    
                    var files = e.dataTransfer.files;
                    if (files.length > 0) {
                        var docType = this.closest('.document-card').getAttribute('data-doc-type');
                        var fileInput = document.getElementById('file_' + docType);
                        fileInput.files = files;
                        uploadDocument(docType, fileInput);
                    }
                });
            });

            // Handle replace button clicks
            document.querySelectorAll('.replace-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var docType = this.getAttribute('data-doc-type');
                    document.getElementById('replaceDocType').value = docType;
                    $('#replaceDocumentModal').modal('show');
                });
            });

            // Handle replace confirmation
            document.getElementById('confirmReplaceBtn').addEventListener('click', function() {
                var docType = document.getElementById('replaceDocType').value;
                var fileInput = document.getElementById('replaceFileInput');
                
                if (fileInput.files.length > 0) {
                    uploadDocument(docType, fileInput);
                    $('#replaceDocumentModal').modal('hide');
                } else {
                    alert('Veuillez sélectionner un fichier.');
                }
            });

            // Handle delete button clicks
            document.querySelectorAll('.delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var fileId = this.getAttribute('data-file-id');
                    var docName = this.getAttribute('data-doc-name');
                    document.getElementById('fileIdToDelete').value = fileId;
                    document.getElementById('documentNameToDelete').innerText = docName;
                    $('#deleteConfirmationModal').modal('show');
                });
            });

            // Handle delete confirmation
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                var fileId = document.getElementById('fileIdToDelete').value;
                window.location.href = '<?= base_url('dossiers/delete_document/') ?>' + fileId;
            });
        });
    </script>
</body>
</html>