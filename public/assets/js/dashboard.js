/* ======================================================
   ADMIN DASHBOARD – CONFIRM FIRST, THEN UPDATE
====================================================== */

document.addEventListener("DOMContentLoaded", () => {
  let pendingSelect = null;
  let previousValue = "";
  let confirmModal = new bootstrap.Modal(
    document.getElementById("statusConfirmModal")
  );

  /* ===============================
     STATUS CHANGE (INTERCEPT)
  =============================== */
  document.querySelectorAll(".status-select").forEach((select) => {
    // store initial value
    select.dataset.prev = select.value;

    select.addEventListener("change", () => {
      previousValue = select.dataset.prev;
      pendingSelect = select;

      const newStatus = select.value;

      document.getElementById("statusConfirmText").innerHTML = `
        Change status to <strong>${format(newStatus)}</strong>?
      `;

      confirmModal.show();
    });
  });

  /* ===============================
     CONFIRM BUTTON
  =============================== */
  document
    .getElementById("confirmStatusBtn")
    .addEventListener("click", async () => {
      if (!pendingSelect) return;

      const id = pendingSelect.dataset.id;
      const status = pendingSelect.value;

      pendingSelect.disabled = true;

      try {
        const res = await fetch(`${BASE_URL}/update-status`, {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `${CSRF_NAME}=${CSRF_HASH}&id=${id}&status=${status}`,
        });

        const data = await res.json();

        if (data.success) {
          showNotify(`Status updated to "${format(status)}"`);
          pulse(pendingSelect);

          pendingSelect.dataset.prev = status;
          window.CSRF_HASH = data.csrf;
        } else {
          showNotify("Failed to update status", true);
          pendingSelect.value = previousValue;
        }
      } catch {
        showNotify("Network error", true);
        pendingSelect.value = previousValue;
      }

      pendingSelect.disabled = false;
      pendingSelect = null;
      confirmModal.hide();
    });

  /* ===============================
     CANCEL → REVERT VALUE
  =============================== */
  document
    .getElementById("statusConfirmModal")
    .addEventListener("hidden.bs.modal", () => {
      if (pendingSelect) {
        pendingSelect.value = previousValue;
        pendingSelect = null;
      }
    });

  /* ===============================
     VIEW CANDIDATE DETAILS
  =============================== */
  document.querySelectorAll(".view-btn").forEach((btn) => {
    btn.addEventListener("click", async () => {
      const res = await fetch(`${BASE_URL}/admin/candidate/${btn.dataset.id}`);
      const data = await res.json();

      document.getElementById("modalBody").innerHTML = `
        <h6 class="fw-bold">Skills</h6>
        <ul class="list-group mb-3">
          ${data.skills
            .map((s) => `<li class="list-group-item">${s.skill}</li>`)
            .join("")}
        </ul>

        <h6 class="fw-bold">Education</h6>
        <ul class="list-group">
          ${data.edu
            .map(
              (e) => `
            <li class="list-group-item">
              ${e.qualification} – ${e.institution}
              <small class="text-muted">(${e.graduation_year})</small>
            </li>`
            )
            .join("")}
        </ul>
      `;

      new bootstrap.Modal(document.getElementById("candidateModal")).show();
    });
  });
});

/* ===============================
   NOTIFICATION
=============================== */
function showNotify(message, error = false) {
  const box = document.getElementById("notify");
  if (!box) return;

  box.innerHTML = `
    <div class="notify-card ${error ? "error" : ""}">
      <span>${error ? "❌" : "✅"}</span>
      <div>${message}</div>
    </div>
  `;

  box.classList.add("show");
  setTimeout(() => box.classList.remove("show"), 3200);
}

/* ===============================
   HELPERS
=============================== */
function format(text) {
  return text.replace("_", " ").replace(/\b\w/g, (l) => l.toUpperCase());
}

function pulse(el) {
  el.classList.add("pulse");
  setTimeout(() => el.classList.remove("pulse"), 500);
}
