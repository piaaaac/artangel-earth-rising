// ----------------------------------------------------------------
// Variables
// ----------------------------------------------------------------

const hamburgers = document.querySelectorAll(".hamburger");
const trackArtist = document.getElementById("track-artist");
const trackTitle = document.getElementById("track-title");
const trackInfoArtist = document.getElementById("track-info-artist");
const trackInfoScript = document.getElementById("track-info-script");
const colorCover = document.getElementById("color-cover");

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
  document.body.dataset.trackOpenIndex = Number(trackData.index);
  trackTitle.textContent = trackData.title;
  trackArtist.textContent = trackData.artist;
  trackInfoArtist.innerHTML = trackData.infoartist;
  trackInfoScript.innerHTML = trackData.infoscript;
  colorCover.style.backgroundColor = trackData.uicolor;
}

function closeTrack() {
  document.body.dataset.trackOpen = "";
  document.body.dataset.trackOpenIndex = "";
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
  openTrack(track);
}

function handleTitleClick(event) {
  event.preventDefault();
  closeAllPanels();
  closeTrack();
}

function startTracklist() {
  document.querySelector('#circle').classList.add('zoom-out');
  var track = tracks[0];
  openTrack(track);

  setTimeout(function() {
    document.querySelector('#circle').classList.remove('starting-point','zoom-out');
    document.querySelector('#circle').classList.add('zoom-in');

    tempAutoplay(goToNextTrack(), 5000, tracks.length);
  },1000)
}

function goToNextTrack() {
  document.querySelector('#circle').classList.add('zoom-out');
  console.log(document.body.dataset.trackOpenIndex);

  if (document.body.dataset.trackOpenIndex === undefined) {
    var track = tracks[0];
    openTrack(track);
  } else {
    var nextTrack = Number(document.body.dataset.trackOpenIndex) + 1;
    openTrack(tracks[nextTrack]);
  }

  setTimeout(function() {
    document.querySelector('#circle').classList.remove('starting-point','zoom-out');
    document.querySelector('#circle').classList.add('zoom-in');
  }, 1000)
}

function tempAutoplay(callback, interval, repeatTimes) {
  let repeated = 0;
  const intervalTask = setInterval(doTask, interval)

  function doTask() {
    if ( repeated < repeatTimes ) {
      goToNextTrack()
      repeated += 1
    } else {
      clearInterval(intervalTask)
    }
  }
}

// ----------------------------------------------------------------
// Events
// ----------------------------------------------------------------
