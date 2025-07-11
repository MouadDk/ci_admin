<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$form_action = isset($client) ? 'clients/update/' . $client->id : 'clients/save';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($client) ? 'Edit Client' : 'Add New Client' ?></title>
    <!-- CSS Links -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
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
                    <h1 class="h3 mb-4 text-gray-800"><?= isset($client) ? 'Edit Client' : 'Add New Client' ?></h1>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Client Information</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (validation_errors()) : ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <?= validation_errors() ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        </div>
                                    <?php endif; ?>

                                    <?= form_open($form_action); ?>
                                        
                                        <?php if(isset($client)): ?>
                                            <input type="hidden" name="id" value="<?= $client->id ?>">
                                        <?php endif; ?>

                                        <h6 class="font-weight-bold">Client Details</h6>

                                        <div class="form-group">
                                            <label for="responsable">Responsable *</label>
                                            <input type="text" class="form-control" id="responsable" name="responsable" value="<?= set_value('responsable', isset($client) ? $client->responsable : '') ?>" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="etablissement">Etablissement *</label>
                                            <select class="form-control" id="etablissement" name="etablissement" required>
                                                <option value="" disabled selected>Select Type</option>
                                                <option value="Hotel" <?= set_select('etablissement', 'Hotel', isset($client) && $client->etablissement == 'Hotel') ?>>Hotel</option>
                                                <option value="Restaurant" <?= set_select('etablissement', 'Restaurant', isset($client) && $client->etablissement == 'Restaurant') ?>>Restaurant</option>
                                                <option value="Agence de voyage" <?= set_select('etablissement', 'Agence de voyage', isset($client) && $client->etablissement == 'Agence de voyage') ?>>Agence de voyage</option>
                                                <option value="Transport touristique" <?= set_select('etablissement', 'Transport touristique', isset($client) && $client->etablissement == 'Transport touristique') ?>>Transport touristique</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="nom_etablissement">Nom Etablissement *</label>
                                            <input type="text" class="form-control" id="nom_etablissement" name="nom_etablissement" value="<?= set_value('nom_etablissement', isset($client) ? $client->nom_etablissement : '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="ville">Ville *</label>
                                            <select class="form-control" id="ville" name="ville" required>
                                                <option value="" disabled selected>Select a City...</option>
                                                <?php
                                                $moroccan_cities = [
                                                    "Casablanca", "Rabat", "Fes", "Marrakech", "Tangier", "Agadir", "Meknes", "Oujda", "Kenitra", "Tetouan",
                                                    "Safi", "Mohammedia", "Khouribga", "Beni Mellal", "El Jadida", "Taza", "Nador", "Settat", "Larache", "Ksar El Kebir",
                                                    "Temara", "Sale", "Ouarzazate", "Errachidia", "Dakhla", "Laayoune", "Guelmim", "Tiznit", "Sidi Ifni", "Taroudant",
                                                    "Chefchaouen", "Al Hoceima", "Berkane", "Taourirt", "Guercif", "Midelt", "Azrou", "Ifrane", "Khenifra", "Sidi Slimane",
                                                    "Sidi Kacem", "Youssoufia", "Fquih Ben Salah", "Souk Sebt Oulad Nemma", "Tiflet", "Skhirat", "Bouznika", "Fnideq", "Martil", "M'diq"
                                                ];
                                                foreach ($moroccan_cities as $city) {
                                                    $selected = set_select('ville', $city, isset($client) && $client->ville == $city);
                                                    echo "<option value=\"{$city}\" {$selected}>{$city}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="region">Region</label>
                                            <input type="text" class="form-control" id="region" name="region" value="<?= set_value('region', isset($client) ? $client->region : '') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="telephone1">Telephone 1 *</label>
                                            <input type="text" class="form-control" id="telephone1" name="telephone1" value="<?= set_value('telephone1', isset($client) ? $client->telephone1 : '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="telephone2">Telephone 2</label>
                                            <input type="text" class="form-control" id="telephone2" name="telephone2" value="<?= set_value('telephone2', isset($client) ? $client->telephone2 : '') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="ice">ICE</label>
                                            <input type="text" class="form-control" id="ice" name="ice" value="<?= set_value('ice', isset($client) ? $client->ice : '') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="type">Type *</label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="Physique" <?= set_select('type', 'Physique', isset($client) && $client->type == 'Physique') ?>>Physique</option>
                                                <option value="Moral" <?= set_select('type', 'Moral', isset($client) && $client->type == 'Moral') ?>>Moral</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="provenance">Provenance *</label>
                                            <select class="form-control" id="provenance" name="provenance" required>
                                                <option value="" disabled selected>Select Provenance</option>
                                                <option value="Externe" <?= set_select('provenance', 'Externe', isset($client) && $client->provenance == 'Externe') ?>>Externe</option>
                                                <option value="FMS" <?= set_select('provenance', 'FMS', isset($client) && $client->provenance == 'FMS') ?>>FMS</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="referral_field" style="display: <?= (isset($client) && $client->provenance == 'Externe') ? 'block' : 'none' ?>;">
                                            <label for="referal">Referal</label>
                                            <input type="text" class="form-control" id="referal" name="referal" value="<?= set_value('referal', isset($client) ? $client->referal : '') ?>">
                                        </div>

                                        <h6 class="font-weight-bold">User Account Details</h6>
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
                                        
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Client</button>
                                        <a href="<?= base_url('clients') ?>" class="btn btn-secondary">Cancel</a>
                                    <?= form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer & Modals -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto"><div class="copyright text-center my-auto"><span>Copyright © Your Website 2024</span></div></div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5><button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer"><button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button><a class="btn btn-primary" href="<?= base_url('auth/logout') ?>">Logout</a></div>
            </div>
        </div>
    </div>
    <!-- JS Scripts -->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provenanceSelect = document.getElementById('provenance');
            const referralField = document.getElementById('referal_field');

            function toggleReferralField() {
                if (provenanceSelect.value === 'Externe') {
                    referralField.style.display = 'block';
                } else {
                    referralField.style.display = 'none';
                }
            }

            provenanceSelect.addEventListener('change', toggleReferralField);

            // Initial check on page load
            toggleReferralField();
        });
    </script>
</body>
</html>