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

    <title>Client Dossiers - <?= htmlspecialchars($client->nom_etablissement ?? $client->responsable, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Custom fonts for this template -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= base_url('assets/css/admin-dashboard.css') ?>" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

    <style>
        /* Enhanced File Upload Styles */
        #content-wrapper .drop-zone {
            border: 2px dashed #d1d3e2;
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            color: #6c757d;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #f8f9fc;
        }
        #content-wrapper .drop-zone:hover {
            border-color: #4e73df;
            background-color: #f0f3ff;
        }
        #content-wrapper .drop-zone.dragover {
            background-color: #e3f2fd;
            border-color: #2196f3;
            color: #1976d2;
        }
        #content-wrapper .drop-zone i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #4e73df;
        }
        #content-wrapper .file-selected {
            color: #28a745;
            font-weight: 500;
            margin-top: 10px;
        }



        /* Enhanced Button Styles */
        #content-wrapper .btn-enhanced {
            border-radius: 6px;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }
        #content-wrapper .btn-enhanced:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        /* Card Enhancements - Scoped to avoid sidebar conflicts */
        .content-wrapper .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .content-wrapper .card-header {
            background: linear-gradient(45deg, #4e73df, #224abe);
            color: white;
            border-bottom: none;
        }
        .content-wrapper .card-header h6 {
            color: white !important;
            margin: 0;
        }

        /* Table Enhancements */
        #content-wrapper .table th {
            border-top: none;
            font-weight: 600;
            color: #5a5c69;
            background-color: #f8f9fc;
        }
        #content-wrapper .table td {
            vertical-align: middle;
        }

    </style>

    <style>
        /* Styles merged from predefined_documents.php */
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
                        <div>
                            <h1 class="h3 mb-0 text-gray-800">Documents Supplémentaires</h1>
                            <p class="mb-0 text-muted">Client: <?= htmlspecialchars($client->nom_etablissement ?? $client->responsable, ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="mb-0 text-muted">Dossier: <?= htmlspecialchars($dossier->name, ENT_QUOTES, 'UTF-8') ?>
                                <?php if ($dossier->status == 'valide' && !empty($dossier->validation_date)): ?>
                                    <small class="text-muted ml-2">(Validated: <?= date('Y-m-d', strtotime($dossier->validation_date)) ?>)</small>
                                <?php endif; ?>
                            </p>
                      </div>
                      <a href="<?= base_url('clients') ?>"
                         class="btn btn-primary btn-enhanced">
                          <i class="fas fa-arrow-left"></i> Retour aux Clients
                      </a>
                  </div>

                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <?php
                                    $d1_status = $all_sub_dossiers[1]->status ?? 'non valide';
                                    $d2_status = $all_sub_dossiers[2]->status ?? 'non valide';
                                    $d2_enabled = $d1_status === 'valide';

                                    $extern_action_selected_in_d2 = false;
                                    if (isset($all_sub_dossiers[2]) && isset($extern_action_id)) {
                                        $selected_actions_d2 = $this->Dossier_model->get_actions_for_sub_dossier($all_sub_dossiers[2]->id);
                                        if (in_array($extern_action_id, $selected_actions_d2)) {
                                            $extern_action_selected_in_d2 = true;
                                        }
                                    }

                                    $d3_enabled = $d2_enabled && $d2_status === 'valide' && !$extern_action_selected_in_d2;
                                    ?>
                                    <a class="nav-link <?= ($active_tab == 'dossier_1') ? 'active' : '' ?>" href="<?= base_url('dossiers/client_dossiers/' . $client->id . '/dossier_1') ?>">Dossier 1</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($active_tab == 'dossier_2') ? 'active' : '' ?> <?= !$d2_enabled ? 'disabled' : '' ?>" href="<?= $d2_enabled ? base_url('dossiers/client_dossiers/' . $client->id . '/dossier_2') : '#' ?>">Dossier 2</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($active_tab == 'dossier_3') ? 'active' : '' ?> <?= !$d3_enabled ? 'disabled' : '' ?>" href="<?= $d3_enabled ? base_url('dossiers/client_dossiers/' . $client->id . '/dossier_3') : '#' ?>">Dossier 3</a>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <div class="row">
                        <!-- Unified Content Area for All Tabs -->
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Documents for <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $active_tab)), ENT_QUOTES, 'UTF-8') ?>
                                           <span class="status-badge <?php $status_class = ($sub_dossier->status == 'valide') ? 'status-approved' : 'status-rejected'; echo $status_class; ?>"
                                                 data-sub-dossier-id="<?= $sub_dossier->id ?>"
                                                 data-current-status="<?= $sub_dossier->status ?>"
                                                 style="margin-left: 10px;">
                                               <?= htmlspecialchars($sub_dossier->status, ENT_QUOTES, 'UTF-8') ?>
                                           </span>
                                           <?php if ($sub_dossier->status == 'valide' && !empty($sub_dossier->validation_date)): ?>
                                               <small class="text-muted ml-2">(Validated: <?= date('Y-m-d', strtotime($sub_dossier->validation_date)) ?>)</small>
                                           <?php endif; ?>
                                       </h6>
                                       <?php if ($active_tab == 'dossier_2'): ?>
                                           <button type="button" class="btn btn-info btn-sm ml-2" data-toggle="modal" data-target="#actionsModal" data-sub-dossier-id="<?= $sub_dossier->id ?>">
                                               <i class="fas fa-cogs"></i> Actions
                                           </button>
                                       <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Name</th>
                                                    <th>Upload Date</th>
                                                    <th>Uploaded By</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($all_documents)): ?>
                                                    <?php foreach ($all_documents as $doc): ?>
                                                        <tr>
                                                            <td>
                                                                <i class="<?= htmlspecialchars($doc->icon, ENT_QUOTES, 'UTF-8') ?>"></i>
                                                                <?= $doc->is_predefined ? 'Document Requis' : 'Fichier Supplémentaire' ?>
                                                            </td>
                                                            <td><?= htmlspecialchars($doc->name, ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td><?= htmlspecialchars($doc->created_at ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td><?= htmlspecialchars($doc->uploaded_by_admin_username ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                                            <td>
                                                                <?php if ($doc->is_uploaded): ?>
                                                                    <a href="<?= base_url($doc->path) ?>" class="btn btn-info btn-sm btn-enhanced" target="_blank" title="View/Download File"><i class="fas fa-download"></i></a>
                                                                    <button type="button" class="btn btn-warning btn-sm btn-enhanced replace-btn ml-1" data-doc-type="<?= $doc->document_type ?>" data-sub-dossier-type="<?= $sub_dossier->sub_dossier_type ?>" title="Replace Document"><i class="fas fa-sync"></i></button>
                                                                    <button type="button" class="btn btn-danger btn-sm btn-enhanced delete-btn ml-1" data-id="<?= $doc->id ?>" data-doc-name="<?= htmlspecialchars($doc->name, ENT_QUOTES, 'UTF-8') ?>" title="Delete Document"><i class="fas fa-trash"></i></button>
                                                                <?php else: ?>
                                                                    <button type="button" class="btn btn-success btn-sm btn-enhanced upload-predefined-btn" data-doc-type="<?= $doc->document_type ?>" data-doc-name="<?= htmlspecialchars($doc->name, ENT_QUOTES, 'UTF-8') ?>" data-sub-dossier-type="<?= $sub_dossier->sub_dossier_type ?>" title="Upload Document"><i class="fas fa-upload"></i> Upload</button>
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
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Upload Additional Document to <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $active_tab)), ENT_QUOTES, 'UTF-8') ?></h6>
                                </div>
                                <div class="card-body">
                                    <?= form_open_multipart('dossiers/upload_file/' . $client->id, ['id' => 'uploadForm']) ?>
                                        <input type="hidden" name="sub_dossier_type" value="<?= $sub_dossier->sub_dossier_type ?>">
                                        <div class="form-group">
                                            <label for="document_name"><i class="fas fa-tag"></i> Document Name:</label>
                                            <input type="text" class="form-control" id="document_name" name="document_name" required placeholder="Enter a descriptive name for the document">
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-file-upload"></i> Select File:</label>
                                            <div class="drop-zone" id="dropZone">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <p class="mb-2"><strong>Drag & drop a file here</strong></p>
                                                <p class="text-muted">or click to browse files</p>
                                                <input type="file" id="userfile" name="userfile" style="display: none;">
                                                <div id="fileInfo" class="file-selected" style="display: none;">
                                                    <i class="fas fa-check-circle"></i>
                                                    <span id="fileName"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block btn-enhanced">
                                            <span class="loading-spinner" id="uploadSpinner"></span>
                                            <i class="fas fa-upload"></i> Upload Document
                                        </button>
                                    <?= form_close() ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

    <!-- Actions Modal -->
    <div class="modal fade" id="actionsModal" tabindex="-1" role="dialog" aria-labelledby="actionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="actionsModalLabel">
                        <i class="fas fa-cogs"></i> Gérer les Actions
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="actionsModalBody">
                    <!-- Content will be loaded here via AJAX -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Fermer
                    </button>
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
                        <span aria-hidden="true">×</span>
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
                        <span class="loading-spinner" id="statusSpinner"></span>
                        <i class="fas fa-check"></i> Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete File Confirmation Modal -->
    <div class="modal fade" id="deleteFileModal" tabindex="-1" role="dialog" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteFileModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirm File Deletion
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center"><strong>Are you sure you want to delete this file?</strong></p>
                    <p class="text-center text-muted">This action cannot be undone and the file will be permanently removed.</p>
                    <input type="hidden" id="fileToDeleteId">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-enhanced" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn btn-danger btn-enhanced" id="confirmDeleteFileBtn">
                        <i class="fas fa-trash"></i> Delete File
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Dossier Confirmation Modal -->
    <div class="modal fade" id="deleteDossierModal" tabindex="-1" role="dialog" aria-labelledby="deleteDossierModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteDossierModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirm Dossier Deletion
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-folder-minus text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center"><strong>Are you sure you want to delete this dossier?</strong></p>
                    <p class="text-center text-muted">This action will permanently delete the dossier, all associated files, and the corresponding folder on the server. This action cannot be undone.</p>
                    <p class="text-danger text-center">To confirm, please enter your current password:</p>
                    <div class="form-group">
                        <input type="password" class="form-control" id="adminPassword" placeholder="Your Password">
                    </div>
                    <input type="hidden" id="dossierToDeleteId" value="<?= $dossier->id ?>">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-enhanced" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn btn-danger btn-enhanced" id="confirmDeleteDossierBtn">
                        <i class="fas fa-trash"></i> Delete Dossier
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                    <input type="hidden" id="fileIdToDeletePredefined">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button class="btn btn-danger" id="confirmDeletePredefinedBtn">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Predefined Document Modal -->
    <div class="modal fade" id="uploadPredefinedDocumentModal" tabindex="-1" role="dialog" aria-labelledby="uploadPredefinedDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="uploadPredefinedDocumentModalLabel">
                        <i class="fas fa-upload"></i> Upload Document: <span id="uploadDocName"></span>
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Sélectionnez un fichier pour le document <strong id="uploadDocNameBody"></strong>:</p>
                    <input type="file" id="uploadPredefinedFileInput" class="form-control">
                    <input type="hidden" id="uploadPredefinedDocType">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button class="btn btn-success" id="confirmUploadPredefinedBtn">
                        <i class="fas fa-upload"></i> Télécharger
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

    <!-- Delete File Confirmation Modal -->
    <div class="modal fade" id="deleteFileModal" tabindex="-1" role="dialog" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteFileModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirm File Deletion
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center"><strong>Are you sure you want to delete this file?</strong></p>
                    <p class="text-center text-muted">This action cannot be undone and the file will be permanently removed.</p>
                    <input type="hidden" id="fileToDeleteId">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-enhanced" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn btn-danger btn-enhanced" id="confirmDeleteFileBtn">
                        <i class="fas fa-trash"></i> Delete File
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Dossier Confirmation Modal -->
    <div class="modal fade" id="deleteDossierModal" tabindex="-1" role="dialog" aria-labelledby="deleteDossierModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteDossierModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirm Dossier Deletion
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-folder-minus text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center"><strong>Are you sure you want to delete this dossier?</strong></p>
                    <p class="text-center text-muted">This action will permanently delete the dossier, all associated files, and the corresponding folder on the server. This action cannot be undone.</p>
                    <p class="text-danger text-center">To confirm, please enter your current password:</p>
                    <div class="form-group">
                        <input type="password" class="form-control" id="adminPasswordConfirm" placeholder="Your Password">
                    </div>
                    <input type="hidden" id="dossierToDeleteId" value="<?= $dossier->id ?>">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-enhanced" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn btn-danger btn-enhanced" id="confirmDeleteDossierBtn">
                        <i class="fas fa-trash"></i> Delete Dossier
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                    <input type="hidden" id="fileIdToDeletePredefined">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button class="btn btn-danger" id="confirmDeletePredefinedBtn">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Predefined Document Modal -->
    <div class="modal fade" id="uploadPredefinedDocumentModal" tabindex="-1" role="dialog" aria-labelledby="uploadPredefinedDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="uploadPredefinedDocumentModalLabel">
                        <i class="fas fa-upload"></i> Upload Document: <span id="uploadDocName"></span>
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Sélectionnez un fichier pour le document <strong id="uploadDocNameBody"></strong>:</p>
                    <input type="file" id="uploadPredefinedFileInput" class="form-control">
                    <input type="hidden" id="uploadPredefinedDocType">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button class="btn btn-success" id="confirmUploadPredefinedBtn">
                        <i class="fas fa-upload"></i> Télécharger
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

    <!-- Page level plugins -->
    <script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

    <!-- Page level custom scripts -->
    <script>
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

        $(document).ready(function() {
            // Initialize DataTables for each tab
            if ($('#dataTable').length) {
                $('#dataTable').DataTable({
                    "order": [[0, "desc"]]
                });
            }


            // --- Enhanced Drag and Drop File Upload for Additional Documents ---
            // --- Enhanced Drag and Drop File Upload for Dossier 1 (Requis) ---
            setupFileUpload('#dropZone', '#userfile', '#fileInfo', '#fileName', '#uploadForm', '#uploadSpinner');

            // --- Enhanced Drag and Drop File Upload for Dossier 2 (Supplémentaires) ---
            setupFileUpload('#dropZoneSupplementary', '#userfile_supplementary', '#fileInfoSupplementary', '#fileNameSupplementary', '#uploadFormSupplementary', '#uploadSpinnerSupplementary');

            // File size formatter
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                var k = 1024;
                var sizes = ['Bytes', 'KB', 'MB', 'GB'];
                var i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function setupFileUpload(dropZoneSelector, fileInputSelector, fileInfoSelector, fileNameSelector, uploadFormSelector, uploadSpinnerSelector) {
                var dropZone = $(dropZoneSelector);
                var fileInput = $(fileInputSelector);
                var fileInfo = $(fileInfoSelector);
                var fileName = $(fileNameSelector);

                dropZone.on('click', function(e) {
                    if (!$(e.target).is(fileInput)) {
                        fileInput.click();
                    }
                });

                fileInput.on('change', function() {
                    if (this.files.length > 0) {
                        var file = this.files[0];
                        fileName.text(file.name + ' (' + formatFileSize(file.size) + ')');
                        fileInfo.show();
                    } else {
                        fileName.text('');
                        fileInfo.hide();
                    }
                });

                dropZone.on('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).addClass('dragover');
                });

                dropZone.on('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).removeClass('dragover');
                });

                dropZone.on('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).removeClass('dragover');

                    var files = e.originalEvent.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.prop('files', files).trigger('change');
                    }
                });

                $(uploadFormSelector).on('submit', function(e) {
                    if (fileInput.get(0).files.length === 0) {
                        e.preventDefault();
                        showToast('Please select a file to upload.', 'error');
                        return;
                    }
                    $(uploadSpinnerSelector).show();
                    $(this).find('button[type="submit"]').prop('disabled', true);
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

            // Handle clicks on sub-dossier status badges (within card-header, not data-interactive)
            $(document).on('click', '.card-header .status-badge:not([data-interactive="true"])', function() {
                var subDossierId = $(this).data('sub-dossier-id');
                var currentStatus = $(this).data('current-status');
                
                $('#dossierToUpdateId').val(subDossierId);
                $('#updateType').val('sub_dossier'); // Explicitly set type for sub-dossier
                $('#newStatus').val(currentStatus);
                $('#statusUpdateModal').modal('show');
            });

            $('#confirmUpdateStatusBtn').on('click', function() {
                var idToUpdate = $('#dossierToUpdateId').val();
                var newStatus = $('#newStatus').val();
                var updateType = $('#updateType').val(); // Get the type of update
                var btn = $(this);

                $('#statusSpinner').show();
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
                            $(document.activeElement).blur(); // Blur the currently focused element to prevent aria-hidden issues
                            $('#statusUpdateModal').modal('hide');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            showToast('Failed to update status: ' + result.message, 'error');
                            console.error('Server reported error:', result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        showToast('An AJAX error occurred: ' + error, 'error');
                        console.error('AJAX error:', status, error, xhr);
                    },
                    complete: function() {
                        $('#statusSpinner').hide();
                        btn.prop('disabled', false);
                    }
                });
            });

            // --- Delete File (Additional Documents) ---
            $('#dataTable').on('click', '.delete-file-btn', function() {
                var fileId = $(this).data('id');
                $('#fileToDeleteId').val(fileId);
                $('#deleteFileModal').modal('show');
            });

            $('#confirmDeleteFileBtn').on('click', function() {
                var fileId = $('#fileToDeleteId').val();
                showToast('Deleting file...', 'info');
                setTimeout(function() {
                    window.location.href = '<?= base_url('dossiers/delete_document/') ?>' + fileId;
                }, 1000);
            });

            // --- Delete Dossier ---
            $('#confirmDeleteDossierBtn').on('click', function() {
                var dossierId = $('#dossierToDeleteId').val();
                var adminPassword = $('#adminPassword').val();

                var form = $('<form action="<?= base_url('dossiers/delete_dossier/') ?>' + dossierId + '" method="post"></form>');
                form.append('<input type="hidden" name="dossier_id" value="' + dossierId + '">');
                form.append('<input type="hidden" name="admin_password" value="' + adminPassword + '">');
                $('body').append(form);
                form.submit();
            });

            // --- Predefined Documents JavaScript ---
            // Handle upload button clicks for predefined documents
            $(document).on('click', '.upload-predefined-btn, .replace-btn', function() {
                var docType = $(this).data('doc-type');
                var subDossierType = $(this).data('sub-dossier-type');
                var $button = $(this);

                var $tempFileInput = $('<input type="file" style="display: none;">');
                $('body').append($tempFileInput);
                $tempFileInput.click();

                $tempFileInput.on('change', function() {
                    if (this.files.length > 0) {
                        var file = this.files[0];
                        var formData = new FormData();
                        formData.append('userfile', file);
                        formData.append('sub_dossier_type', subDossierType);
                        formData.append('document_type', docType);
                        formData.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash(); ?>');

                        $button.prop('disabled', true).html('<span class="loading-spinner" style="display:inline-block;"></span>');

                        $.ajax({
                            url: '<?= base_url('dossiers/upload_file/' . $client->id) ?>',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            success: function(result) {
                                if (result.status === 'success') {
                                    showToast(result.message, 'success');
                                    setTimeout(function() { location.reload(); }, 1500);
                                } else {
                                    showToast('Upload failed: ' + result.message, 'error');
                                    // Restore button on failure
                                    $button.prop('disabled', false).html('<i class="fas fa-upload"></i> Upload');
                                }
                            },
                            error: function() {
                                showToast('An AJAX error occurred.', 'error');
                                $button.prop('disabled', false).html('<i class="fas fa-upload"></i> Upload');
                            },
                            complete: function() {
                                $tempFileInput.remove();
                            }
                        });
                    } else {
                        $tempFileInput.remove();
                    }
                });
            });

            // The logic for replace button is now merged with upload-predefined-btn

            // --- Delete Document ---
            $(document).on('click', '.delete-btn', function() {
                var fileId = $(this).data('id');
                var docName = $(this).data('doc-name');
                $('#fileToDeleteId').val(fileId);
                // The modal doesn't show the name anymore, but we can keep this for future use.
                // $('#documentNameToDelete').text(docName);
                $('#deleteFileModal').modal('show');
            });
            
            // Handle delete confirmation
            $('#confirmDeleteFileBtn').on('click', function() {
                 var fileId = $('#fileToDeleteId').val();
                 window.location.href = '<?= base_url('dossiers/delete_document/') ?>' + fileId;
            });
        });
    </script>
    <!-- Page level custom scripts -->
    <!-- Removed datatables-demo.js as its content is already included or causing conflicts -->
    <script>
        $(document).ready(function() {
            // JavaScript for conditional display of "Externe" field
            // JavaScript for conditional display of "Description (Externe)" field
            // This targets the checkbox whose label contains "Externe"
            $(document).on('change', 'input[type="checkbox"][name="actions[]"]', function() {
                var $this = $(this);
                var actionName = $this.next('label').text().trim(); // Get the text of the label next to the checkbox

                if (actionName === 'Externe') { // Assuming "Externe" is the name of the action
                    if ($this.is(':checked')) {
                        $('#externalDescriptionContainer').show();
                    } else {
                        $('#externalDescriptionContainer').hide();
                        $('#external_description').val(''); // Clear the field when hidden
                    }
                }
            });

            // Also, ensure the field is shown/hidden correctly when the modal is loaded
            $('#actionsModal').on('show.bs.modal', function() {
                // Small delay to ensure partial content is loaded
                setTimeout(function() {
                    var $externCheckbox = $('input[type="checkbox"][name="actions[]"]').filter(function() {
                        return $(this).next('label').text().trim() === 'Externe';
                    });

                    if ($externCheckbox.length && $externCheckbox.is(':checked')) {
                        $('#externalDescriptionContainer').show();
                    } else {
                        $('#externalDescriptionContainer').hide();
                        $('#external_description').val('');
                    }
                }, 200); // Adjust delay if needed
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Load actions partial via AJAX when modal is shown
            $('#actionsModal').on('show.bs.modal', function (event) {
               var button = $(event.relatedTarget);
               var subDossierId = button.data('sub-dossier-id');
               var modal = $(this);
               modal.find('.modal-body').load('<?= base_url('dossiers/actions_partial/') ?>' + subDossierId, function(response, status, xhr) {
                   if (status == "error") {
                       showToast("Error: " + xhr.status + " " + xhr.statusText, 'error');
                   }
               });
            });

           $(document).on('submit', '#actionsForm', function(e) {
               e.preventDefault();
               var formData = $(this).serialize();
               $.ajax({
                   url: '<?= base_url('dossiers/save_actions') ?>',
                   type: 'POST',
                   data: formData,
                   dataType: 'json',
                   success: function(response) {
                       if (response.status === 'success') {
                           showToast(response.message, 'success');
                           $('#actionsModal').modal('hide');
                       } else {
                           showToast(response.message, 'error');
                       }
                   },
                   error: function() {
                       showToast('An error occurred while saving actions.', 'error');
                   }
               });
           });
        });
    </script>
</body>
</html>