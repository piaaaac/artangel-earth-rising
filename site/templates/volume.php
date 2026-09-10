<?php

/**
 * @param $volumeUid - from controller, from route
 * @param $trackUid - from controller, from route
 * */

// Prepare data to pass to js
$data = [];
$i = 0;
foreach ($page->children()->listed() as $key => $track) {
  $item = [];
  // $item = $track->content()->toArray();
  $item["title"] = $track->title()->value();
  $item["artist"] = $track->artist()->value();
  $item["uiColor"] = $track->uiColor()->value();
  $item["index"] = $i;
  $item["id"] = $track->id();
  $item["uid"] = $track->uid();
  $item["uuid"] = $track->uuid()->id();
  $item["url"] = $track->url();
  $item["infoartist"] = $track->infoArtist()->value();
  $item["infoscript"] = $track->infoScript()->value();
  $item["blocksLeft"] = $track->blocksLeft()->toBlocks()->toHtml();
  $item["blocksRight"] = $track->blocksRight()->toBlocks()->toHtml();
  $item["trackType"] = $track->trackType()->value();
  // $item["slideshowFilesUrls"] = [];

  // Prepare media-specific data for Plyr
  $item["media"] = [];
  $trackType = $track->trackType()->value();
  if ($trackType === "audio") {
    $item["media"]["type"] = "audio";
    $fileAudio = $track->typeAudioFile()->toFile();
    if ($fileAudio) {
      $item["media"]["audioFileUrl"] = $fileAudio->url();
    }
    // } elseif ($trackType === "slideshow") {
    //   $item["media"]["type"] = "audio";
    //   $fileAudio = $track->typeSlideshowAudioFile()->toFile();
    //   if ($fileAudio) {
    //     $item["media"]["audioFileUrl"] = $fileAudio->url();
    //   }
    //   foreach ($track->typeSlideshowImageFiles()->toFiles() as $image) {
    //     $item["slideshowFilesUrls"][] = $image->url();
    //   }
  } elseif ($trackType === "video") {
    $item["media"]["type"] = "video";
    $fileMp4 = $track->typeVideoSourceMp4()->toFile();
    if ($fileMp4) {
      $item["media"]["videoFileMp4Url"] = $fileMp4->url();
    }
    $fileWebm = $track->typeVideoSourceWebm()->toFile();
    if ($fileWebm) {
      $item["media"]["videoFileWebmUrl"] = $fileWebm->url();
    }
    $filePoster = $track->typeVideoPoster()->toFile();
    if ($filePoster) {
      $item["media"]["videoFilePosterUrl"] = $filePoster->url();
    }
  }
  $data[] = $item;
  $i++;
}
$json = json_encode($data);
?>

<?php snippet("header") ?>

<script>
  const tracks = <?= $json ?>;
  const initialTrackUid = "<?= $trackUid ?>";
  const currentVolume = "<?= $volumeUid ?>"; // e.g. "vol1", "vol2", etc.
  console.log("tracks", tracks);
  console.log("initialTrackUid", initialTrackUid);
  console.log("currentVolume", currentVolume);
</script>


<main id="main-content">
  <div id="backgrounds">
    <div id="bg-layer1"></div>
    <div id="bg-layer2"></div>
    <div id="color-cover"></div>
  </div>
  <div id="media-container"></div>
  <div id="circle-dot-wrapper">
    <div id="circle-dot" class="starting-point" onclick="app.handleDotClick(event, this);"></div>
  </div>
  <div id="circle-wrapper">
    <div id="circle-time" class="player-ui">
      <svg viewBox="0 0 100 100" preserveAspectRatio="xMidYMid meet">
        <defs>
          <path id="circlePath"
            d="M50,50
               m -40,0
               a 40,40 0 1,1 80,0
               a 40,40 0 1,1 -80,0" />
        </defs>

        <!-- Visible path -->
        <use href="#circlePath" />
        <rect class="one" x="74" y="15" width="24" height="33" />
        <rect class="two" x="74" y="53" width="24" height="31" />

        <!-- Centered text on circle -->
        <text text-anchor="middle" dominant-baseline="middle">
          <textPath href="#circlePath" startOffset="50%">
            <tspan id="time-current">00:00</tspan>
            <tspan>&nbsp;&nbsp;&nbsp;</tspan>
            <tspan id="time-duration">00:00</tspan>
          </textPath>
        </text>
      </svg>
    </div>
  </div>
  <!-- <div id="color-cover"></div> -->
  <div id="player-ui-buttons" class="player-ui">
    <button class="focusable" id="prev-track"><img class="icon-img" src="<?= $kirby->url("assets") ?>/images/icon-larr-double.svg" alt="Previous Track"></button>
    <button class="focusable" id="play-pause-button"></button>
    <button class="focusable" id="next-track"><img class="icon-img" src="<?= $kirby->url("assets") ?>/images/icon-rarr-double.svg" alt="Next Track"></button>
    <button class="focusable" id="fullscreen-button"><img class="icon-img" src="<?= $kirby->url("assets") ?>/images/icon-fullscreen.svg" alt="Toggle fullscreen"></button>
  </div>
</main>

<!-- <canvas id="twinkle-canvas"></canvas> -->


<?php snippet("nav") ?>

<script type="text/javascript" src="<?= $kirby->url("assets") ?>/lib/plyr-3.7.8/dist/plyr.js"></script>
<script>
  // Initialize objects
  const wui = new WebUI();
  const pc = new PlayerController("media-container");
  const pui = new PlayerUI(pc, {
    prevBtn: document.getElementById("prev-track"),
    playBtn: document.getElementById("play-pause-button"),
    nextBtn: document.getElementById("next-track"),
    fullscreenBtn: document.getElementById("fullscreen-button"),
  });
  const app = new App(wui, pui, tracks);

  // From url: route > controller > template volume.php
  if (initialTrackUid) {
    var track = tracks.find((t) => t.uid === initialTrackUid);
    var index = tracks.findIndex((t) => t.uid === initialTrackUid);
    console.log(`Track passed via url: ${index}`, track);
    app.openTrack(index);
  }
</script>

<script>
  // ----------------------------------------------------------------------------
  // // TwinklingStars
  // // Initialization
  // const canvas = document.getElementById('twinkle-canvas');
  // window.twinkler = new TwinklingStars(canvas);
  // window.twinkler.setHomeMode();

  // // UI controls
  // const toggleBtn = document.getElementById('toggle');
  // const areaToggle = document.getElementById('areaToggle');
  // ----------------------------------------------------------------------------
</script>

<?php snippet("footer") ?>