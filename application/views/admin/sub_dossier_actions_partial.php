<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
    </div>
    <div class="card-body">
        <form id="actionsForm">
            <input type="hidden" name="sub_dossier_id" value="<?= htmlspecialchars($sub_dossier_id, ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" id="externActionId" value="<?= htmlspecialchars($extern_action_id ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <?php foreach ($all_actions as $action): ?>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="action_<?= $action->id ?>" name="actions[]" value="<?= $action->id ?>" <?= in_array($action->id, $selected_actions ?? []) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="action_<?= $action->id ?>"><?= htmlspecialchars($action->name, ENT_QUOTES, 'UTF-8') ?></label>
                    </div>
                </div>
            <?php endforeach; ?>

<?php
    $extern_action_is_selected = false;
    if (isset($selected_actions) && isset($extern_action_id)) {
        if (in_array($extern_action_id, $selected_actions)) {
            $extern_action_is_selected = true;
        }
    }
    $initial_display_style = $extern_action_is_selected ? 'block' : 'none';
?>
            <div class="form-group" id="externalDescriptionContainer" style="display: <?= $initial_display_style ?>;">
                <label for="external_description">Description (Externe):</label>
                <textarea class="form-control" id="external_description" name="external_description" rows="3" placeholder="Enter description for external action"><?= isset($external_description) ? htmlspecialchars($external_description, ENT_QUOTES, 'UTF-8') : '' ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-enhanced">Enregistrer les Actions</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const externActionId = document.getElementById('externActionId').value;
    if (externActionId) {
        const externCheckbox = document.getElementById('action_' + externActionId);
        if (externCheckbox) {
            const externalDescriptionContainer = document.getElementById('externalDescriptionContainer');

            // Function to toggle visibility of the description container
            const toggleDescriptionVisibility = () => {
                if (externCheckbox.checked) {
                    externalDescriptionContainer.style.display = 'block';
                } else {
                    externalDescriptionContainer.style.display = 'none';
                }
            };

            // Initial check on page load
            toggleDescriptionVisibility();

            // Add event listener for changes
            externCheckbox.addEventListener('change', function() {
                toggleDescriptionVisibility(); // Toggle visibility based on checkbox state

                const subDossierId = document.querySelector('input[name="sub_dossier_id"]').value;
                const actionType = this.checked ? 'skip' : 'unskip'; // Determine action based on checkbox state
                
                fetch('<?= base_url("dossiers/update_dossier_3_status") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'sub_dossier_id=' + subDossierId + '&action_type=' + actionType
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // alert(data.message); // Commented out to avoid multiple alerts
                        // Optionally, refresh the page or update UI
                        // window.location.reload(); // Removed to prevent full page reload unless necessary
                    } else {
                        alert('Error: ' + data.message);
                        // Revert checkbox state if there was an error
                        this.checked = !this.checked;
                        toggleDescriptionVisibility(); // Re-toggle visibility if state reverted
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating Dossier 3 status.');
                    this.checked = !this.checked; // Revert checkbox state on error
                    toggleDescriptionVisibility(); // Re-toggle visibility if state reverted
                });
            });
        }
    }
});
</script>