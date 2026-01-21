<?php /* =========================
   ADMIN MODALS (REUSABLE)
========================= */ ?>

<!-- CANDIDATE DETAILS MODAL -->
<div class="modal fade" id="candidateModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Candidate Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="modalBody">
                <!-- Loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- STATUS CONFIRMATION MODAL -->
<div class="modal fade" id="statusConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p id="statusConfirmText" class="mb-1"></p>
                <small class="text-muted">
                    📧 An email notification will be sent to the candidate.
                </small>
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button class="btn btn-primary" id="confirmStatusBtn">
                    Confirm
                </button>
            </div>

        </div>
    </div>
</div>
<!-- STATUS HISTORY MODAL -->
 <div class="modal fade" id="statusHistoryModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Status Change History</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>From</th>
              <th>To</th>
              <th>Changed By</th>
              <th>Role</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody id="statusHistoryBody">
            <tr>
              <td colspan="5" class="text-center text-muted">
                Loading...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- NOTIFICATION CONTAINER -->
<div id="notify" class="notify-container"></div>
