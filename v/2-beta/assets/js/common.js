// ----------------------------------------------------------------------------
// Helper variables
// ----------------------------------------------------------------------------

const hamburgers = document.querySelectorAll(".hamburger");
const trackArtist = document.getElementById("track-artist");
const trackTitle = document.getElementById("track-title");
const trackInfoArtist = document.getElementById("track-info-artist");
const trackInfoScript = document.getElementById("track-info-script");
const trackInfoArtistMob = document.getElementById("track-info-artist-mob");
const trackInfoScriptMob = document.getElementById("track-info-script-mob");
const colorCover = document.getElementById("color-cover");
const circle = document.querySelector("#circle");
const circleTime = document.querySelector("#circle-time");
const circleTimeAnimate = document.querySelector("#circle-time");
const circleTimeSeek = document.querySelector("#time-current");
const circleTimeDuration = document.querySelector("#time-duration");

// ----------------------------------------------------------------------------
// Functions
// ----------------------------------------------------------------------------

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

function toggleArtangelPanel(bool) {
  if (bool === undefined) {
    bool = document.body.dataset.artangelPanel === "true" ? false : true;
  }
  closeAllPanels();
  document.body.dataset.artangelPanel = bool;
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
  loadTrackContent(trackData.id);
  colorStars(null);
  trackTitle.textContent = trackData.title;
  trackArtist.textContent = trackData.artist;
  trackInfoArtist.innerHTML = trackData.infoartist;
  circleTime.classList.add("clean");
  animateCircle(() => {
    trackInfoArtistMob.innerHTML = trackData.infoartist;
    trackInfoScript.innerHTML = trackData.infoscript;
    trackInfoScriptMob.innerHTML = trackData.infoscript;
    colorCover.style.backgroundColor = trackData.uicolor;
    colorStars(trackData.uicolor);
    // setTimeout(() => toggleTrackPlay(true), 1000);
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
  document.body.dataset.artangelPanel = false;
  document.body.dataset.trackInfo = false;
  document.body.dataset.aboutPanel = false;
}

function handleTrackClick(event, element) {
  event.preventDefault();
  var trackUid = element.getAttribute("data-track-uid");
  var track = tracks.find((t) => t.uid === trackUid);
  console.log("Track clicked:", track);
  var trackIndex = tracks.indexOf(track);
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

// function handlePrevTrackClick() {
//   toggleTrackPlay(false);
//   openPrevTrack();
// }
// function handleNextTrackClick() {
//   toggleTrackPlay(false);
//   openNextTrack();
// }

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
    resetAlbum();
  } else {
    var track = tracks[prevIndex];
    openTrack(track);
  }
}

function animateCircle(callback) {
  circleTimeAnimate.classList.add("zoom-out");
  setTimeout(function () {
    circleTimeAnimate.classList.remove("starting-point", "zoom-out");
    circleTimeAnimate.classList.add("zoom-in");
    if (typeof callback === "function") {
      callback();
    }
  }, 1000);
}

function resetAlbum() {
  circleTimeAnimate.classList.remove("zoom-in", "zoom-out");
  circleTimeAnimate.classList.add("starting-point", "forced-start");
  setTimeout(function () {
    circleTimeAnimate.classList.remove("forced-start");
  }, 1000);
  document.body.dataset.trackOpen = "";
  setUrlHome();
  colorStars(null);
}

function colorStars(color) {
  document.documentElement.style.setProperty("--stars-color", color ?? "unset");
}

function formatTime(seconds) {
  if (isNaN(seconds)) {
    return "00:00";
  }
  const minutes = Math.floor(seconds / 60);
  const secs = Math.floor(seconds % 60);
  return `${String(minutes).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
}

// ------

// url

function setUrlHome() {
  const url = new URL(window.siteUrl + "/" + currentVolume);
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

// ----------------------------------------------------------------------------
// Class PlayerController
// ----------------------------------------------------------------------------
// - Core logic for loading media.
// - Manages playback, playlist, media switching, and player lifecycle.
// - Wraps Plyr or native HTML5.
// ----------------------------------------------------------------------------

class PlayerController {
  constructor(containerId, tracks) {
    this.mediaContainer = document.getElementById(containerId);
    this.tracks = tracks;
    this.events = {}; // 🔹 event name → array of callbacks
    this.plyr = null;
  }

  // Keep track of callbacks associated to events
  on(eventName, callback) {
    if (!this.events[eventName]) {
      this.events[eventName] = [];
    }
    this.events[eventName].push(callback);
  }

  // Execute all callbacks for a given event
  emit(eventName, payload) {
    const listeners = this.events[eventName];
    if (listeners) {
      listeners.forEach((cb) => cb(payload));
    }
  }

  next() {
    // Implement next track logic here if needed
  }

  loadNewTrack(track) {
    // prepare variables
    let mediaType = track.trackType === "video" ? "video" : "audio";

    // Remove old player
    this.plyr?.destroy();
    this.mediaContainer.innerHTML = "";

    // Create new media element (video or audio)
    const media = document.createElement(mediaType);
    media.setAttribute("id", "player");
    media.setAttribute("controls", "");
    media.setAttribute("playsinline", "");

    if (track.videoFilePosterUrl && mediaType === "video") {
      media.setAttribute("poster", videoFilePosterUrl);
    }

    const source = document.createElement("source");
    let src =
      mediaType === "video" ? track.typeVideoSourceMp4 : track.audioFileUrl;
    source.setAttribute("src", src);
    source.setAttribute("type", `${mediaType}/${src.split(".").pop()}`);
    media.appendChild(source);

    if (mediaType === "video") {
      const source2 = document.createElement("source");
      source2.setAttribute("src", track.typeVideoSourceWebm);
      source2.setAttribute("type", "video/webm");
      media.appendChild(source2);
    }

    this.mediaContainer.appendChild(media);

    // Reinitialize Plyr
    this.plyr = new Plyr("#player");
    console.log("Plyr initialized:", this.plyr);
    this.exposePlyrEvents();
  }

  exposePlyrEvents = () => {
    this.plyr.on("ended", (event) => {
      this.emit("ended", event);
    });
    this.plyr.on("timeupdate", (event) => {
      this.emit("timeupdate", { currentTime: this.plyr.currentTime });
    });
    this.plyr.on("loadedmetadata", (event) => {
      this.emit("loadedmetadata", { duration: this.plyr.duration });
    });
  };
}

// ----------------------------------------------------------------------------
// Class PlayerUI
//
// - Handles all DOM/UI concerns: buttons, events, feedback, metadata display, etc.
// - Communicates with PlayerController
// ----------------------------------------------------------------------------

class PlayerUI {
  constructor(controller, uiSelectors) {
    this.controller = controller;
    this.selectors = uiSelectors;
    this.bindUIEvents();
    this.bindControllerEvents();
  }

  toggleTrackPlay(bool) {
    let paused = document.body.dataset.playerPaused === "true" ? true : false;
    let nextPlayState = paused;
    if (bool !== undefined) {
      nextPlayState = bool;
    }
    circleTime.classList.toggle("paused", !nextPlayState);
    circleTime.classList.remove("clean");
    document.body.dataset.playerPaused = !nextPlayState;
    if (nextPlayState) {
      this.controller.plyr.play();
    } else {
      this.controller.plyr.pause();
    }
  }

  setTrackSeek(s) {
    circleTimeSeek.textContent = formatTime(s);
  }

  setTrackDuration(s) {
    circleTimeDuration.textContent = formatTime(s);
  }

  bindUIEvents() {
    this.selectors.playBtn.addEventListener("click", () => {
      this.controller.plyr.togglePlay();
      console.log("plyr", this.controller.plyr);
    });
    this.selectors.nextBtn.addEventListener("click", () => {
      this.toggleTrackPlay(false);
      openNextTrack();
      this.controller.next();
    });
    this.selectors.prevBtn.addEventListener("click", () => {
      this.toggleTrackPlay(false);
      openPrevTrack();
      this.controller.prev();
    });
  }

  bindControllerEvents() {
    this.controller.on("ended", () => {
      console.log("controller event - ended");
      // const nextIndex = (currentIndex + 1) % playlist.length;
      // loadTrack(nextIndex);
    });

    // Update UI on play/pause
    this.controller.on("timeupdate", ({ currentTime }) => {
      console.log("controller event - timeupdate", currentTime);
      setTrackSeek(currentTime);
    });

    this.controller.on("loadedmetadata", ({ duration }) => {
      console.log("controller event - loadedmetadata", duration);
      setTrackDuration(duration);
    });
  }

  updateTrackInfo(track) {
    // Update UI with title, duration, thumbnail, etc.
  }
}

// ----------------------------------------------------------------------------
// 🔁 Communication Patterns between PlayerController and PlayerUI
// UI → Player: through direct method calls (controller.next()).
// Player → UI: via an event system (controller.on('trackChange', cb)).
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Execution start
// ----------------------------------------------------------------------------

let cp;

// From url: route > controller > template volume.php
if (initialTrackUid) {
  var track = tracks.find((t) => t.uid === initialTrackUid);
  console.log("Track passed via url:", track);
  openTrack(track);
}

const controller = new PlayerController("media-container", tracks);

const ui = new PlayerUI(controller, {
  prevBtn: document.getElementById("prev-track"),
  playBtn: document.getElementById("play-pause-button"),
  nextBtn: document.getElementById("next-track"),
});
