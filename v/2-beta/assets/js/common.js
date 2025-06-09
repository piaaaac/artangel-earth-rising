/**
 * @param tracks comes from php (site/templates/volume.php)
 */

// ----------------------------------------------------------------------------
// Architecture
//
// Functions at top level:
// handle the website UI (panels like about, accessibility, track-infos etc.)
//
// Media Player: 2 classes
// - PlayerController: manages media loading, playback, and events.
// - PlayerUI: handles player UI interactions, and UI updates.
//
// 🔁 Communication Patterns between PlayerController and PlayerUI:
// UI → Player: through direct method calls (controller.next()).
// Player → UI: via an event system (controller.on('trackChange', cb)).
// ----------------------------------------------------------------------------

// TODO NEXT
// - fix classes/data-attributes for showing/hiding the player, and transition between tracks (circle aniimations)
// - refactor

// ----------------------------------------------------------------------------
// Event listeners or global handlers
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
// Utility functions
// ----------------------------------------------------------------------------

function formatTime(seconds) {
  if (isNaN(seconds)) {
    return "00:00";
  }
  const minutes = Math.floor(seconds / 60);
  const secs = Math.floor(seconds % 60);
  return `${String(minutes).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
}

// --- url

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

// --- ajax

function loadTrackContentAjax(id) {
  console.log(
    "Loading content with ajax isn't really doing much atm, since most/everything is stored in the tracks array. Keeping it for future use, but for now just returning false"
  );
  return false;
  var url = window.siteUrl + "/" + id + ".json";
  fetch(url)
    .then((response) => {
      return response.json();
    })
    .then((jsonData) => {
      // blurContent(true);
      setTimeout(() => {
        // bodyContent.textContent = ""
        // handleReceivedTrackData(jsonData);
        console.log("handleReceivedTrackData(jsonData) to be implemented");
      }, 600);
    })
    .catch((err) => {
      console.log("Error fetching page:", err);
    });
}

// ----------------------------------------------------------------------------
// Class App
// ----------------------------------------------------------------------------
// Main application logic
// - Manages the state of the application, including the current track and playback status.
// ----------------------------------------------------------------------------

class App {
  constructor(wui, pui, tracks) {
    this.wui = wui;
    this.pui = pui;
    this.state = {
      tracks: tracks,
      currentTrackIndex: null,
      playing: false,
    };
    this.wui.bindApp(this);
    this.pui.bindApp(this);
  }

  getAppState(property) {
    if (property) {
      return this.state[property];
    }
    console.error("getAppState called without property");
  }

  setAppState(property, value) {
    if (property && value !== undefined) {
      this.state[property] = value;
    } else {
      console.error("setAppState called without property or value");
    }
  }

  openTrack(index) {
    this.state.currentTrackIndex = index;
    const trackData = this.state.tracks[index];
    setUrlTrack(trackData.id);

    const that = this;
    const onAnimationDone = function () {
      that.pui.ctrl.loadNewTrack(trackData);
    };
    this.wui.updateTrackUI(trackData, onAnimationDone);
  }

  openNextTrack() {
    let curr = this.state.currentTrackIndex;
    if (curr === null) {
      curr = -1;
    }
    const newIndex = curr + 1;
    if (newIndex >= this.state.tracks.length) {
      this.resetAlbum();
      return;
    }
    this.openTrack(newIndex);
  }

  openPrevTrack() {
    let newIndex = this.state.currentTrackIndex - 1;
    if (newIndex < 0) {
      this.resetAlbum();
      return;
    }
    this.openTrack(newIndex);
  }

  closeTrack() {
    this.state.currentTrackIndex = null;
    this.wui.updateTrackUI(false);
  }

  resetAlbum() {
    this.pui.toggleTrackPlay(false);
    this.wui.resetPlayerUI();
    setUrlHome();
    this.closeTrack();
  }

  findTrackIndexBy(property, value) {
    return this.state.tracks.findIndex((t) => t[property] === value);
  }

  // --- Events

  handleTrackClick(event, element) {
    event.preventDefault();
    var uuid = element.getAttribute("data-track-uuid");
    // var trackData = this.state.tracks.find((t) => t.uuid === uuid);
    // console.log("Track clicked:", trackData);
    const newIndex = this.findTrackIndexBy("uuid", uuid);
    this.openTrack(newIndex);
  }

  handleDotClick(event, element) {
    if (element.classList.contains("starting-point")) {
      this.openNextTrack();
    }
  }
}

// ----------------------------------------------------------------------------
// Class WebUI
// ----------------------------------------------------------------------------
// - handle the website UI (panels like about, accessibility, track-infos etc.)
// ----------------------------------------------------------------------------

class WebUI {
  constructor() {
    this.hamburgers = document.querySelectorAll(".hamburger");
    this.trackArtist = document.getElementById("track-artist");
    this.trackTitle = document.getElementById("track-title");
    this.trackInfoArtist = document.getElementById("track-info-artist");
    this.trackInfoScript = document.getElementById("track-info-script");
    this.trackInfoArtistMob = document.getElementById("track-info-artist-mob");
    this.trackInfoScriptMob = document.getElementById("track-info-script-mob");
    this.colorCover = document.getElementById("color-cover");
    this.circleDot = document.querySelector("#circle-dot");
    this.circleTime = document.querySelector("#circle-time");
    this.circleTimeAnimate = document.querySelector("#circle-wrapper");
    this.main = document.querySelector("main");

    this.parentApp = null;
  }

  // Passing false, null or undefined, the track UI is closed
  updateTrackUI(trackData, callback) {
    if (!trackData) {
      document.body.dataset.trackOpen == "";
      return;
    }
    document.body.dataset.trackOpen = trackData.id;
    this.closeAllPanels();
    this.colorStars(null);
    this.trackTitle.textContent = trackData.title;
    this.trackArtist.textContent = trackData.artist;
    this.trackInfoArtist.innerHTML = trackData.infoartist;
    this.circleTime.classList.add("clean");
    console.log("updateTrackUi ----------------------- BEFORE");

    const that = this;
    this.animateCircle(() => {
      console.log("updateTrackUi ----------------------- AFTER");
      that.trackInfoArtistMob.innerHTML = trackData.infoartist;
      that.trackInfoScript.innerHTML = trackData.infoscript;
      that.trackInfoScriptMob.innerHTML = trackData.infoscript;
      that.colorCover.style.backgroundColor = trackData.uicolor;
      that.colorStars(trackData.uicolor);
      if (typeof callback === "function") {
        callback();
      }
    });
  }

  resetPlayerUI() {
    document.body.dataset.trackOpen = "";
    this.colorStars(null);
  }

  handleTitleClick(event) {
    event.preventDefault();
    this.closeAllPanels();
    this.parentApp.resetAlbum();
  }

  toggleMenuPanel(bool) {
    if (bool === undefined) {
      bool = document.body.dataset.menuPanel === "true" ? false : true;
    }
    this.closeAllPanels();
    document.body.dataset.menuPanel = bool;
    this.hamburgers.forEach((hamburger) => {
      hamburger.classList.toggle("is-active", bool);
    });
  }

  toggleAccessibilityPanel(bool) {
    if (bool === undefined) {
      bool = document.body.dataset.accessibilityPanel === "true" ? false : true;
    }
    this.closeAllPanels();
    document.body.dataset.accessibilityPanel = bool;
  }

  toggleArtangelPanel(bool) {
    if (bool === undefined) {
      bool = document.body.dataset.artangelPanel === "true" ? false : true;
    }
    this.closeAllPanels();
    document.body.dataset.artangelPanel = bool;
  }

  toggleTrackInfo(bool) {
    if (bool === undefined) {
      bool = document.body.dataset.trackInfo === "true" ? false : true;
    }
    this.closeAllPanels();
    document.body.dataset.trackInfo = bool;
  }

  toggleAboutPanel(bool) {
    if (bool === undefined) {
      bool = document.body.dataset.aboutPanel === "true" ? false : true;
    }
    this.closeAllPanels();
    document.body.dataset.aboutPanel = bool;
  }

  closeAllPanels() {
    document.body.dataset.menuPanel = false;
    this.hamburgers.forEach((hamburger) => {
      hamburger.classList.toggle("is-active", false);
    });
    document.body.dataset.accessibilityPanel = false;
    document.body.dataset.artangelPanel = false;
    document.body.dataset.trackInfo = false;
    document.body.dataset.aboutPanel = false;
  }

  colorStars(color) {
    document.documentElement.style.setProperty(
      "--stars-color",
      color ?? "unset"
    );
  }

  animateCircle(callback) {
    this.circleTimeAnimate.classList.remove(
      "state-size-small",
      "state-size-normal"
    );
    this.circleTimeAnimate.classList.add("state-size-large");
    const that = this;
    setTimeout(function () {
      that.circleTimeAnimate.classList.remove(
        "state-size-large",
        "state-size-normal"
      );
      that.circleTimeAnimate.classList.add("state-size-small");
      setTimeout(function () {
        that.circleTimeAnimate.classList.remove(
          "state-size-small",
          "state-size-large"
        );
        that.circleTimeAnimate.classList.add("state-size-normal");
        if (typeof callback === "function") {
          callback();
        }
      }, 1000);
    }, 1000);
  }

  bindApp(app) {
    this.parentApp = app;
  }
}

// ----------------------------------------------------------------------------
// Class PlayerUI
//
// - Handles all DOM/UI concerns: buttons, events, feedback, metadata display, etc.
// - Communicates with PlayerController
// ----------------------------------------------------------------------------

class PlayerUI {
  constructor(controller, uiSelectors) {
    this.circleTimeSeek = document.querySelector("#time-current");
    this.circleTimeDuration = document.querySelector("#time-duration");
    this.circleTime = document.querySelector("#circle-time");
    this.mediaContainer = document.querySelector("#media-container");
    this.main = document.querySelector("main");

    this.ctrl = controller;
    this.selectors = uiSelectors;
    this.parentApp = null;
    this.timeout = null;

    this.bindUIEvents();
    this.bindControllerEvents();
  }

  toggleTrackPlay(bool) {
    let nextPlayState = true;
    if (this.ctrl.plyr) {
      nextPlayState = this.ctrl.plyr.playing ? false : true;
    }
    if (bool !== undefined) {
      nextPlayState = bool;
    }
    // circleTime.classList.toggle("paused", !nextPlayState);
    // document.body.dataset.playerPaused = !nextPlayState;
    document.body.dataset.playerPlaying = nextPlayState ? "true" : "false";
    if (nextPlayState) {
      this.ctrl.plyr?.play();
    } else {
      this.ctrl.plyr?.pause();
    }
  }

  setTrackSeek(s) {
    this.circleTimeSeek.textContent = formatTime(s);
  }

  setTrackDuration(s) {
    this.circleTimeDuration.textContent = formatTime(s);
  }

  bindApp(app) {
    this.parentApp = app;
  }

  // Interactions PlayerUI → Controller
  bindUIEvents() {
    this.selectors.playBtn.addEventListener("click", () => {
      this.toggleTrackPlay();
    });
    this.selectors.nextBtn.addEventListener("click", () => {
      this.toggleTrackPlay(false);
      this.parentApp.openNextTrack();
    });
    this.selectors.prevBtn.addEventListener("click", () => {
      this.toggleTrackPlay(false);
      this.parentApp.openPrevTrack();
    });
    this.main.addEventListener("mouseout", (event) => {
      // this.main.dataset.hidePlayerControls = "true";
      this.hidePlayerControls(true);
    });
    this.main.addEventListener("mousemove", (event) => {
      // console.log("mousemove on main", event);
      // this.main.dataset.hidePlayerControls = "false";
      this.hidePlayerControls(false);
      clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        // this.main.dataset.hidePlayerControls = "true";
        this.hidePlayerControls(true);
      }, 1600);
    });
  }

  hidePlayerControls(bool) {
    this.main.dataset.hidePlayerControls = bool ? "true" : "false";
    this.mediaContainer.classList.toggle("darken", !bool);
  }

  // Interactions Controller → PlayerUI
  bindControllerEvents() {
    this.ctrl.on("ended", () => {
      console.log("controller event - track ended");
      this.parentApp.openNextTrack();
    });

    this.ctrl.on("waiting", () => {
      document.body.dataset.playerLoading = "true";
    });
    this.ctrl.on("stalled", () => {
      document.body.dataset.playerLoading = "true";
    });
    this.ctrl.on("canplay", () => {
      document.body.dataset.playerLoading = "false";
    });

    this.ctrl.on("timeupdate", ({ currentTime }) => {
      // console.log("controller event - timeupdate", currentTime);
      this.setTrackSeek(currentTime);
    });

    this.ctrl.on("loadedmetadata", ({ duration }) => {
      // console.log("controller event - loadedmetadata", duration);
      this.setTrackDuration(duration);
      this.setTrackSeek(0);
      this.circleTime.classList.remove("clean");
    });

    this.ctrl.on("playing", () => {
      console.log("controller event - playing");
      document.body.dataset.playerPlaying = "true";
      this.parentApp.setAppState("playing", true);
    });

    this.ctrl.on("pause", () => {
      console.log("controller event - pause");
      document.body.dataset.playerPlaying = "false";
      this.parentApp.setAppState("playing", false);
    });
  }

  updateTrackInfo(track) {
    // Update UI with title, duration, thumbnail, etc.
  }
}

// ----------------------------------------------------------------------------
// Class PlayerController
// ----------------------------------------------------------------------------
// - Core logic for loading media.
// - Manages playback, media switching, and player lifecycle.
// - Wraps Plyr or native HTML5.
// ----------------------------------------------------------------------------

class PlayerController {
  constructor(containerId) {
    this.mediaContainer = document.getElementById(containerId);
    this.events = {}; // 🔹 event name → array of callbacks
    this.plyr = null;
  }

  loadNewTrack(track /* callback */) {
    // Remove old player
    this.plyr?.destroy();
    this.mediaContainer.innerHTML = "";

    // Create new media element (video or audio)
    const mediaEl = document.createElement(track.media.type);
    mediaEl.setAttribute("id", "player");
    mediaEl.setAttribute("controls", "");
    mediaEl.setAttribute("playsinline", "");

    if (track.media.videoFilePosterUrl && track.media.type === "video") {
      mediaEl.setAttribute("poster", track.media.videoFilePosterUrl);
    }

    const source = document.createElement("source");
    let src =
      track.media.type === "video"
        ? track.media.videoFileMp4Url
        : track.media.audioFileUrl;
    source.setAttribute("src", src);
    source.setAttribute("type", `${track.media.type}/${src.split(".").pop()}`);
    mediaEl.appendChild(source);

    if (track.media.type === "video") {
      const source2 = document.createElement("source");
      source2.setAttribute("src", track.media.videoFileWebmUrl);
      source2.setAttribute("type", "video/webm");
      mediaEl.appendChild(source2);
    }

    this.mediaContainer.appendChild(mediaEl);

    // Reinitialize Plyr
    const plyrOptions = {
      common: {
        keyboard: { global: true },
      },
      audio: {
        controls: [],
      },
      video: {
        controls: [],
      },
    };
    const options = Object.assign(
      {},
      plyrOptions.common,
      plyrOptions[track.media.type]
    );
    this.plyr = new Plyr("#player", options);
    console.log("Plyr initialized:", this.plyr);
    this.exposePlyrEvents();
    this.bindDebugEvents();
    this.plyr.play();

    document.body.dataset.trackType = track.trackType;
    // callback();
  }

  // Register callbacks associated to events
  on(eventName, callback) {
    if (!this.events[eventName]) {
      this.events[eventName] = [];
    }
    this.events[eventName].push(callback);
  }

  // Execute all registered callbacks for a given event
  emit(eventName, payload) {
    const listeners = this.events[eventName];
    if (listeners) {
      listeners.forEach((cb) => cb(payload));
    }
  }

  bindDebugEvents() {
    var eventNames = [
      "progress",
      "playing",
      "play",
      "pause",
      "timeupdate",
      "volumechange",
      "seeking",
      "seeked",
      "ratechange",
      "ended",
      "enterfullscreen",
      "exitfullscreen",
      "captionsenabled",
      "captionsdisabled",
      "languagechange",
      "controlshidden",
      "controlsshown",
      "ready",
      "loadstart",
      "loadeddata",
      "loadedmetadata",
      "qualitychange",
      "canplay",
      "canplaythrough",
      "stalled",
      "waiting",
      "emptied",
      "cuechange",
      "error",
    ];
    eventNames.forEach((eventName) => {
      this.plyr.on(eventName, (event) => {
        console.log("debug: event: " + eventName);
      });
    });
  }

  exposePlyrEvents = () => {
    this.plyr.on("loadedmetadata", (event) => {
      this.emit("loadedmetadata", { duration: this.plyr.duration });
    });

    // .....
    this.plyr.on("waiting", (event) => {
      this.emit("waiting", event);
    });
    this.plyr.on("stalled", (event) => {
      this.emit("stalled", event);
    });
    this.plyr.on("canplay", (event) => {
      this.emit("canplay", event);
    });
    // .....

    this.plyr.on("timeupdate", (event) => {
      this.emit("timeupdate", { currentTime: this.plyr.currentTime });
    });
    this.plyr.on("playing", (event) => {
      this.emit("playing", event);
    });
    this.plyr.on("pause", (event) => {
      this.emit("pause", event);
    });
    this.plyr.on("ended", (event) => {
      this.emit("ended", event);
    });
  };
}

// ----------------------------------------------------------------------------
// Execution start
// ----------------------------------------------------------------------------

// Initialize objects
const wui = new WebUI();
const pc = new PlayerController("media-container");
const pui = new PlayerUI(pc, {
  prevBtn: document.getElementById("prev-track"),
  playBtn: document.getElementById("play-pause-button"),
  nextBtn: document.getElementById("next-track"),
});
const app = new App(wui, pui, tracks);

// From url: route > controller > template volume.php
if (initialTrackUid) {
  var track = tracks.find((t) => t.uid === initialTrackUid);
  var index = tracks.findIndex((t) => t.uid === initialTrackUid);
  console.log(`Track passed via url: ${index}`, track);
  app.openTrack(index);
}
