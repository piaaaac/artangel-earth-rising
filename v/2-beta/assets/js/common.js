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

if (initialTrackUid) {
  var track = tracks.find((t) => t.uid === initialTrackUid);
  console.log("Track passed via url:", track);
  openTrack(track);
}

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
  loadTrackContent(trackData.id);
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
  var trackUid = element.getAttribute("data-track-uid");
  var track = tracks.find((t) => t.uid === trackUid);
  console.log("Track clicked:", track);
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

function handleDotClick(event, element) {
  console.log("Dot clicked", element);
  if (element.classList.contains("starting-point")) {
    openNextTrack();
  }
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
function openPrevTrack() {
  let prevIndex = 0;
  const currentIndex = getCurrentIndex();
  if (currentIndex != -1) {
    prevIndex = currentIndex - 1;
  }
  if (prevIndex < 0) {
    clearInterval(interval);
    resetAlbum();
  } else {
    var track = tracks[prevIndex];
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
  setUrlHome();
}

// ------

// url

function setUrlHome() {
  const url = new URL(window.siteUrl);
  history.pushState({}, "", url);
}
function setUrlTrack(id) {
  const url = new URL(window.siteUrl + "/" + id);
  history.pushState({}, "", url);
}
addEventListener("popstate", (event) => {
  // Handle browser back/forward navigation
  console.log(event);
});

// ajax

function loadTrackContent(id) {
  setUrlTrack(id);
  var url = window.siteUrl + "/" + id + ".json";
  fetch(url)
    .then((response) => {
      return response.json();
    })
    .then((jsonData) => {
      // blurContent(true);
      setTimeout(() => {
        // bodyContent.textContent = ""
        handleReceivedTrackData(jsonData);
      }, 600);
    })
    .catch((err) => {
      console.log("Error fetching page:", err);
    });
}

function handleReceivedTrackData(json) {
  console.log(json);
}

// ----------------------------------------------------------------
// Events
// ----------------------------------------------------------------
