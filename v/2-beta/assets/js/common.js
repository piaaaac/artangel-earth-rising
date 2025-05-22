// ----------------------------------------------------------------
// Variables
// ----------------------------------------------------------------

const hamburgers = document.querySelectorAll(".hamburger");
const trackArtist = document.getElementById("track-artist");
const trackTitle = document.getElementById("track-title");

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

function toggleTrackInfo(bool) {
  if (bool === undefined) {
    bool = document.body.dataset.trackInfo === "true" ? false : true;
  }
  closeAllPanels();
  document.body.dataset.trackInfo = bool;
}

function toggleAboutPanel(bool) {
  if (bool === undefined) {
    bool = document.body.dataset.aboutPanel === "true" ? false : true;
  }
  closeAllPanels();
  document.body.dataset.aboutPanel = bool;
}

function openTrack(trackData) {
  closeAllPanels();
  document.body.dataset.trackOpen = trackData.id;
  trackTitle.textContent = trackData.title;
  trackArtist.textContent = trackData.artist;
}

function closeAllPanels() {
  document.body.dataset.menuPanel = false;
  hamburgers.forEach((hamburger) => {
    hamburger.classList.toggle("is-active", false);
  });
  document.body.dataset.accessibilityPanel = false;
  document.body.dataset.trackInfo = false;
  document.body.dataset.aboutPanel = false;
}

function handleTrackClick(event, element) {
  event.preventDefault();
  var trackId = element.getAttribute("data-track-uuid");
  var track = tracks.find((t) => t.uuid === trackId);
  openTrack(track);
}

// ----------------------------------------------------------------
// Events
// ----------------------------------------------------------------
