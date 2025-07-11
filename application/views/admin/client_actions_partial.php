<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
    </div>
    <div class="card-body">
        <form id="actionsForm">
            <input type="hidden" name="client_id" value="<?= htmlspecialchars($client_id, ENT_QUOTES, 'UTF-8') ?>">
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="actionWebsite" name="actions[]" value="Site internet" <?= in_array('Site internet', $selected_actions ?? []) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="actionWebsite">Site internet</label>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="actionSystemeGestion" name="actions[]" value="Système de gestion" <?= in_array('Système de gestion', $selected_actions ?? []) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="actionSystemeGestion">Système de gestion</label>
                </div>
                <div id="systemeGestionOptions" style="display: <?= in_array('Système de gestion', $selected_actions ?? []) ? 'block' : 'none' ?>; margin-top: 10px; margin-left: 20px;">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="actionAHM" name="actions[]" value="AHM" <?= in_array('AHM', $selected_actions ?? []) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="actionAHM">AHM</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="actionAVM" name="actions[]" value="AVM" <?= in_array('AVM', $selected_actions ?? []) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="actionAVM">AVM</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="actionMarketing" name="actions[]" value="Stratégie marketing" <?= in_array('Stratégie marketing', $selected_actions ?? []) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="actionMarketing">Stratégie marketing</label>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="actionExternal" name="actions[]" value="Externe" <?= in_array('Externe', $selected_actions ?? []) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="actionExternal">Externe</label>
                </div>
                <div id="externalFieldContainer" style="display: <?= in_array('Externe', $selected_actions ?? []) ? 'block' : 'none' ?>; margin-top: 10px;">
                    <textarea class="form-control" id="externalField" name="external_text" rows="3" placeholder="Informations externes"><?= htmlspecialchars($external_text ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-enhanced">Enregistrer les Actions</button>
        </form>
    </div>
</div>
