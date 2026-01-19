/* ======================================================
   JOB APPLICATION – STEPPER (TRULY FIXED)
====================================================== */

document.addEventListener("DOMContentLoaded", () => {

  let currentStep = 0;

  /* ---------- DOM ---------- */
  const steps = Array.from(document.querySelectorAll(".step"));
  const stepItems = Array.from(document.querySelectorAll(".step-item"));
  const stepLines = Array.from(document.querySelectorAll(".step-line"));

  const nextBtn = document.getElementById("nextBtn");
  const prevBtn = document.getElementById("prevBtn");
  const submitBtn = document.getElementById("submitBtn");
  const form = document.querySelector("form");
  const addQualificationBtn = document.getElementById("addQualification");

  if (!steps.length || !stepItems.length) return;

  const LAST_STEP = steps.length - 1;

  /* ======================================================
     RENDER (SINGLE SOURCE OF TRUTH)
  ====================================================== */
  function render() {

    /* ---- STEP CONTENT ---- */
    steps.forEach((step, i) => {
      step.classList.toggle("active", i === currentStep);
    });

    /* ---- STEPPER ---- */
    stepItems.forEach((item, i) => {
      item.classList.remove("active", "completed");

      if (i < currentStep) {
        item.classList.add("completed");
      } else if (i === currentStep) {
        item.classList.add("active");
      }
    });

    stepLines.forEach((line, i) => {
      line.classList.toggle("completed", i < currentStep);
    });

    /* ---- BUTTONS ---- */
    if (prevBtn) prevBtn.style.display = currentStep === 0 ? "none" : "inline-block";

    if (currentStep === LAST_STEP) {
      nextBtn.classList.add("d-none");
      submitBtn.classList.remove("d-none");
    } else {
      nextBtn.classList.remove("d-none");
      submitBtn.classList.add("d-none");
    }
  }

  /* ======================================================
     VALIDATION
  ====================================================== */
  function validateStep() {
    const fields = steps[currentStep].querySelectorAll(
      "input[required], textarea[required], select[required]"
    );

    let valid = true;

    fields.forEach(field => {
      field.classList.remove("is-invalid", "shake");

      if (
        (field.type === "checkbox" && !field.checked) ||
        (field.type !== "checkbox" && !field.value.trim())
      ) {
        valid = false;
        field.classList.add("is-invalid", "shake");
        setTimeout(() => field.classList.remove("shake"), 350);
      }
    });

    return valid;
  }

  /* ======================================================
     NAVIGATION
  ====================================================== */
  nextBtn.addEventListener("click", () => {
    if (!validateStep()) return;
    if (currentStep < LAST_STEP) {
      currentStep++;
      render();
    }
  });

  prevBtn.addEventListener("click", () => {
    if (currentStep > 0) {
      currentStep--;
      render();
    }
  });

  /* ======================================================
     DYNAMIC QUALIFICATIONS
  ====================================================== */
  if (addQualificationBtn) {
    addQualificationBtn.addEventListener("click", () => {
      const container = document.getElementById("qualificationContainer");
      const template = container.querySelector(".qualification");
      if (!template) return;

      const clone = template.cloneNode(true);
      clone.querySelectorAll("input").forEach(i => i.value = "");
      container.appendChild(clone);
    });
  }

  document.addEventListener("click", e => {
    if (e.target.classList.contains("remove")) {
      const blocks = document.querySelectorAll(".qualification");
      if (blocks.length > 1) {
        e.target.closest(".qualification")?.remove();
      }
    }
  });

  /* ======================================================
     RESUME PREVIEW
  ====================================================== */
  const resumeInput = document.querySelector('input[type="file"]');
  if (resumeInput) {
    const preview = document.createElement("div");
    preview.className = "mt-2 text-success small";
    resumeInput.after(preview);

    resumeInput.addEventListener("change", () => {
      const file = resumeInput.files[0];
      preview.innerHTML = file
        ? `📄 <strong>${file.name}</strong> (${Math.round(file.size / 1024)} KB)`
        : "";
    });
  }

  /* ======================================================
     FINAL SUBMIT GUARD
  ====================================================== */
  form.addEventListener("submit", e => {
    if (!validateStep()) {
      e.preventDefault();
    }
  });

  /* INIT */
  render();
});
