// ----------------------------------------------------------------
// Variables
// ----------------------------------------------------------------

const hamburgers = document.querySelectorAll(".hamburger");

// ----------------------------------------------------------------
// Execution start
// ----------------------------------------------------------------

// ----------------------------------------------------------------
// Functions
// ----------------------------------------------------------------

function toggleMenuPanel(bool) {
  if (bool === undefined) {
    bool = document.body.dataset.menuPanel === "true" ? false : true;
  }
  closeAllPanels();
  document.body.dataset.menuPanel = bool;
  hamburgers.forEach((hamburger) => {
    hamburger.classList.toggle("is-active", bool);
  });
}

function toggleAccessibilityPanel(bool) {
  if (bool === undefined) {
    bool = document.body.dataset.accessibilityPanel === "true" ? false : true;
  }
  closeAllPanels();
  document.body.dataset.accessibilityPanel = bool;
}

function toggleWorkInfo(bool) {
  if (bool === undefined) {
    bool = document.body.dataset.workInfo === "true" ? false : true;
  }
  closeAllPanels();
  document.body.dataset.workInfo = bool;
}

function toggleAboutPanel(bool) {
  if (bool === undefined) {
    bool = document.body.dataset.aboutPanel === "true" ? false : true;
  }
  closeAllPanels();
  document.body.dataset.aboutPanel = bool;
}

function closeAllPanels() {
  document.body.dataset.menuPanel = false;
  hamburgers.forEach((hamburger) => {
    hamburger.classList.toggle("is-active", false);
  });
  document.body.dataset.accessibilityPanel = false;
  document.body.dataset.workInfo = false;
  document.body.dataset.aboutPanel = false;
}

// ----------------------------------------------------------------
// Events
// ----------------------------------------------------------------
