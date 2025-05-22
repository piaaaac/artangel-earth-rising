// ----------------------------------------------------------------
// Variables
// ----------------------------------------------------------------

let interval;

const hamburgers = document.querySelectorAll(".hamburger");
const trackArtist = document.getElementById("track-artist");
const trackTitle = document.getElementById("track-title");
const trackInfoArtist = document.getElementById("track-info-artist");
const trackInfoScript = document.getElementById("track-info-script");
const colorCover = document.getElementById("color-cover");
const circle = document.querySelector("#circle");

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
  document.body.dataset.trackOpen = trackData.uuid;
  animateCircle(() => {
    trackTitle.textContent = trackData.title;
    trackArtist.textContent = trackData.artist;
    trackInfoArtist.innerHTML = trackData.infoartist;
    trackInfoScript.innerHTML = trackData.infoscript;
    colorCover.style.backgroundColor = trackData.uicolor;
  });
}

function closeTrack() {
  document.body.dataset.trackOpen = "";
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
  var trackIndex = tracks.indexOf(track);
  clearInterval(interval);
  openTrack(track);
}

function handleTitleClick(event) {
  event.preventDefault();
  closeAllPanels();
  closeTrack();
  resetAlbum();
}

function startTracklist() {
  interval = setInterval(openNextTrack, 5000);
}

function getCurrentIndex() {
  return tracks.findIndex((t) => t.uuid === document.body.dataset.trackOpen);
}

function openNextTrack() {
  let nextIndex = 0;
  const currentIndex = getCurrentIndex();
  if (currentIndex != -1) {
    nextIndex = currentIndex + 1;
  }
  if (nextIndex >= tracks.length) {
    clearInterval(interval);
    resetAlbum();
  } else {
    var track = tracks[nextIndex];
    openTrack(track);
  }
}

function animateCircle(callback) {
  circle.classList.add("zoom-out");
  setTimeout(function () {
    circle.classList.remove("starting-point", "zoom-out");
    circle.classList.add("zoom-in");
    if (typeof callback === "function") {
      callback();
    }
  }, 1000);
}

function resetAlbum() {
  circle.classList.remove("zoom-in", "zoom-out");
  circle.classList.add("starting-point", "forced-start");
  setTimeout(function () {
    circle.classList.remove("forced-start");
  }, 1000);
  document.body.dataset.trackOpen = "";
}

// ----------------------------------------------------------------
// Events
// ----------------------------------------------------------------
