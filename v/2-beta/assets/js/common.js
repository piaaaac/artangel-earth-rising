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
    this.bindMediaSessionApiEvents();
  }

  bindMediaSessionApiEvents() {
    if ("mediaSession" in navigator) {
      navigator.mediaSession.setActionHandler("previoustrack", () => {
        this.openPrevTrack();
      });
      navigator.mediaSession.setActionHandler("nexttrack", () => {
        this.openNextTrack();
      });
      this.updateMediaSessionMetadata(null);
    }
  }

  updateMediaSessionMetadata(trackData) {
    if ("mediaSession" in navigator) {
      const title = trackData ? trackData.title : "Unknown Title";
      const artist = trackData ? trackData.artist : "Unknown Artist";
      navigator.mediaSession.metadata = new MediaMetadata({
        title: title,
        artist: artist,
        album: "~Earth Rising. Volume I",
        artwork: [
          {
            src: "https://via.assets.so/img.jpg?w=96&h=96&tc=blue&bg=#cecece&t=cover",
            sizes: "96x96",
            type: "image/jpeg",
          },
          {
            src: "https://via.assets.so/img.jpg?w=128&h=128&tc=blue&bg=#cecece&t=cover",
            sizes: "128x128",
            type: "image/jpeg",
          },
          {
            src: "https://via.assets.so/img.jpg?w=192&h=192&tc=blue&bg=#cecece&t=cover",
            sizes: "192x192",
            type: "image/jpeg",
          },
          {
            src: "https://via.assets.so/img.jpg?w=256&h=256&tc=blue&bg=#cecece&t=cover",
            sizes: "256x256",
            type: "image/jpeg",
          },
          {
            src: "https://via.assets.so/img.jpg?w=384&h=384&tc=blue&bg=#cecece&t=cover",
            sizes: "384x384",
            type: "image/jpeg",
          },
          {
            src: "https://via.assets.so/img.jpg?w=512&h=512&tc=blue&bg=#cecece&t=cover",
            sizes: "512x512",
            type: "image/jpeg",
          },
        ],
      });
    }
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
    this.updateMediaSessionMetadata(trackData);

    const that = this;
    const onAnimationDone = function () {
      that.pui.ctrl.loadNewTrack(trackData);
    };
    this.wui.updateTrackUI(trackData, onAnimationDone);
    // window.twinkler?.setTrackMode(trackData);
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
    // window.twinkler?.setHomeMode();
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
    this.tracklistLinks = document.querySelectorAll("#menu-panel a.track");

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
    this.trackTitle.textContent = "";
    this.trackArtist.textContent = "";
    this.circleTime.classList.add("clean");
    this.tracklistLinks.forEach((el) => el.classList.remove("active"));
    console.log("updateTrackUi ----------------------- BEFORE");

    const that = this;
    this.animateCircle(() => {
      console.log("updateTrackUi ----------------------- AFTER");
      this.trackTitle.textContent = trackData.title;
      this.trackArtist.textContent = trackData.artist;

      // this.trackInfoArtist.innerHTML = trackData.infoartist;
      // that.trackInfoArtistMob.innerHTML = trackData.infoartist;
      // that.trackInfoScript.innerHTML = trackData.infoscript;
      // that.trackInfoScriptMob.innerHTML = trackData.infoscript;

      this.trackInfoArtist.innerHTML = trackData.blocksLeft;
      that.trackInfoArtistMob.innerHTML = trackData.blocksLeft;
      that.trackInfoScript.innerHTML = trackData.blocksRight;
      that.trackInfoScriptMob.innerHTML = trackData.blocksRight;
      initNewAccordions();

      that.colorCover.style.backgroundColor = trackData.uiColor;
      that.colorStars(trackData.uiColor);
      document
        .querySelector(
          "#menu-panel a.track[data-track-id='" + trackData.id + "']"
        )
        ?.classList.add("active");

      if (typeof callback === "function") {
        callback();
      }
    });
  }

  toggleAccessibilityProperty(property, value) {
    // Possible values
    // accessHighContrast | accessTxtSize | accessAnimationsOff
    if (value === undefined) {
      value =
        document.documentElement.dataset[property] === "true" ? false : true;
    }
    document.documentElement.dataset[property] = value;
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
    document.body.dataset.trackInfo = false;
    document.body.dataset.aboutPanel = false;
    // document.body.dataset.accessibilityPanel = false;
    // document.body.dataset.artangelPanel = false;
  }

  colorStars(color) {
    document.documentElement.style.setProperty(
      "--stars-color",
      color ?? "unset"
    );
  }

  animateCircle(callback) {
    document.body.dataset.circleAnimating = "true";
    document.body.dataset.circleAnimationStage = "1";
    this.circleTimeAnimate.classList.remove(
      "state-size-small",
      "state-size-normal"
    );
    this.circleTimeAnimate.classList.add("state-size-large");
    const that = this;
    setTimeout(function () {
      document.body.dataset.circleAnimationStage = "2";
      that.circleTimeAnimate.classList.remove(
        "state-size-large",
        "state-size-normal"
      );
      that.circleTimeAnimate.classList.add("state-size-small");
      setTimeout(function () {
        document.body.dataset.circleAnimating = "false";
        document.body.dataset.circleAnimationStage = "";
        that.circleTimeAnimate.classList.remove(
          "state-size-small",
          "state-size-large"
        );
        that.circleTimeAnimate.classList.add("state-size-normal");
        if (typeof callback === "function") {
          callback();
        }
      }, 800);
    }, 1400);
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
    const that = this;
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
    this.selectors.fullscreenBtn.addEventListener("click", () => {
      this.ctrl.plyr.fullscreen.toggle();
    });
    this.main.addEventListener("mouseout", () => {
      this.togglePlayerControlsHidden(false);
    });
    this.main.addEventListener("mousemove", () => {
      that.showPlayerControls();
    });
    this.main.addEventListener("touchstart", () => {
      that.showPlayerControls();
    });
  }

  showPlayerControls() {
    this.togglePlayerControlsHidden(false);
    clearTimeout(this.timeout);
    this.timeout = setTimeout(() => {
      this.togglePlayerControlsHidden(true);
    }, 1600);
  }

  togglePlayerControlsHidden(bool) {
    this.main.dataset.playerControlsHidden = bool ? "true" : "false";
    document.body.dataset.videoHideUi = bool ? "true" : "false";
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
    setTimeout(() => {
      this.plyr.play();
    }, 1000);

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
// TwinklingStars Class
// ----------------------------------------------------------------------------

// class TwinklingStars {
//   constructor(canvas, color = "F1F3D7") {
//     this.canvas = canvas;
//     this.ctx = canvas.getContext("2d");
//     // this.width = window.innerWidth;
//     // this.height = window.innerHeight;
//     // this.canvas.width = this.width;
//     // this.canvas.height = this.height;
//     this.resizeCanvasToDisplaySize();
//     this.spawnProbability = 0.03;
//     this.minStarSize = 5;
//     this.maxStarSize = 12;
//     window.addEventListener("resize", () => this.resizeCanvasToDisplaySize());
//     this.stars = [];
//     this.twinkling = true;
//     this.area = null;
//     this.starImage = new Image();
//     this.setColor(color); // sets the SVG
//     this._animate = this._animate.bind(this);
//   }

//   // resize() {
//   //   this.width = window.innerWidth;
//   //   this.height = window.innerHeight;
//   //   this.canvas.width = this.width;
//   //   this.canvas.height = this.height;
//   // }

//   resizeCanvasToDisplaySize() {
//     const dpr = window.devicePixelRatio || 1;
//     const displayWidth = Math.floor(this.canvas.clientWidth * dpr);
//     const displayHeight = Math.floor(this.canvas.clientHeight * dpr);
//     if (
//       this.canvas.width !== displayWidth ||
//       this.canvas.height !== displayHeight
//     ) {
//       this.canvas.width = displayWidth;
//       this.canvas.height = displayHeight;
//       this.width = displayWidth;
//       this.height = displayHeight;
//     }
//   }

//   randomInRange(min, max) {
//     return Math.random() * (max - min) + min;
//   }

//   randomPositionInCircle(cx, cy, radius) {
//     const angle = Math.random() * 2 * Math.PI;
//     const r = radius * Math.sqrt(Math.random());
//     return {
//       x: cx + r * Math.cos(angle),
//       y: cy + r * Math.sin(angle),
//     };
//   }

//   spawnStar() {
//     let x, y;
//     if (this.area) {
//       const pos = this.randomPositionInCircle(
//         this.area.cx,
//         this.area.cy,
//         this.area.radius
//       );
//       x = pos.x;
//       y = pos.y;
//     } else {
//       x = Math.random() * this.width;
//       y = Math.random() * this.height;
//     }

//     const size = this.randomInRange(this.minStarSize, this.maxStarSize);
//     const life = 1500;
//     const createdAt = performance.now();

//     this.stars.push({ x, y, size, createdAt, life });
//   }

//   _drawStars(now) {
//     this.ctx.clearRect(0, 0, this.width, this.height);
//     this.stars = this.stars.filter((star) => now - star.createdAt < star.life);

//     for (const star of this.stars) {
//       const age = now - star.createdAt;
//       const opacity = this._getOpacity(age, star.life);
//       this.ctx.globalAlpha = opacity;
//       this.ctx.drawImage(
//         this.starImage,
//         star.x - star.size / 2,
//         star.y - star.size / 2,
//         star.size,
//         star.size
//       );
//     }
//     this.ctx.globalAlpha = 1;
//   }

//   setColor(hex) {
//     const svg = `
//       <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
//         <path d="M79 40C54.859 40 40 54.859 40 79C40 54.859 25.141 40 1 40C25.141 40 40 25.141 40 1C40 25.141 54.859 40 79 40Z" fill="
//         #${hex}" stroke="#${hex}" stroke-width="2" stroke-linejoin="round"/>
//       </svg>`;

//     this.starImage.src =
//       "data:image/svg+xml;charset=utf-8," + encodeURIComponent(svg);
//   }

//   _getOpacity(age, life) {
//     const effect = "4_ease_out_exponential";
//     let opacity = 1;

//     // 1. Linear fade-out
//     if (effect === "1_fade_out_linear") {
//       opacity = 1 - age / life;
//     }

//     // 2. Fade-in then fade-out (symmetric triangle)
//     if (effect === "2_fade_in_out_linear") {
//       const t = age / life;
//       opacity = t < 0.5 ? t * 2 : (1 - t) * 2;
//     }

//     // 3. Ease-in-out (using sine)
//     if (effect === "3_ease_in_out") {
//       const t = age / life;
//       opacity = Math.sin(t * Math.PI);
//     }

//     // 4. Exponential fade-out
//     if (effect === "4_ease_out_exponential") {
//       opacity = Math.pow(1 - age / life, 2);
//     }

//     // 5. Fade-in quickly, fade-out slowly
//     if (effect === "5_fade_in_out") {
//       const t = age / life;
//       opacity =
//         t < 0.2
//           ? t / 0.2 // fast fade-in
//           : 1 - (t - 0.2) / 0.8; // slow fade-out
//     }

//     // 6. Blinking effect (on-off)
//     if (effect === "6_blinking") {
//       opacity = age / life < 0.5 ? 1 : 0;
//     }

//     return opacity;
//   }

//   _animate(now) {
//     if (this.twinkling && Math.random() < this.spawnProbability) {
//       this.spawnStar();
//     }

//     this._drawStars(now);
//     requestAnimationFrame(this._animate);
//   }

//   start() {
//     this.twinkling = true;
//   }

//   stop() {
//     this.twinkling = false;
//   }

//   setArea(cx, cy, radius) {
//     this.area = { cx, cy, radius };
//   }

//   setProbability(probability) {
//     this.spawnProbability = probability;
//   }

//   clearArea() {
//     this.area = null;
//   }

//   run() {
//     requestAnimationFrame(this._animate);
//   }

//   setHomeMode() {
//     this.resizeCanvasToDisplaySize();
//     const x = this.width / 2;
//     const y = this.height / 2;
//     this.setArea(x, y, 80);
//     this.setProbability(0.02);
//     this.start();
//     this.run();
//   }

//   setTrackMode(trackData) {
//     this.clearArea();
//     this.stop();
//     if (trackData.trackType === "audio") {
//       this.setColor(trackData.uiColor);
//       this.setProbability(0.02);
//       this.start();
//     }
//   }
// }
