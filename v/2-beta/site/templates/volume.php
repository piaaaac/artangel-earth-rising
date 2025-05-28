<?php

/**
 * @param $volumeUid - from controller, from route
 * @param $trackUid - from controller, from route
 * */

$aboutPage = page("about");
$creditsPage = page("credits");

// Prepare data to pass to js
$data = [];
$i = 0;
foreach ($page->children()->listed() as $key => $track) {
  $item = $track->content()->toArray();
  $item["index"] = $i;
  $item["id"] = $track->id();
  $item["uid"] = $track->uid();
  $item["uuid"] = $track->uuid()->id();
  $item["url"] = $track->url();
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

<nav id="menu-panel">
  <?php snippet("tracklist", ["tracksPage" => $page]) ?>
  <div class="line"></div>
</nav>

<nav id="accessibility-panel">
  <ul>
    <li class="my-4"><a href="#" class="font-serif-l">High contrast OFF</a></li>
    <li class="my-4"><a href="#" class="font-serif-l">Text size + -</a></li>
    <li class="my-4"><a href="#" class="font-serif-l">Animations ON</a></li>
  </ul>
</nav>

<nav id="about-panel">
  <div class="about-content"><?= $aboutPage->text()->kt() ?></div>
  <div class="credits-content"><?= $creditsPage->text()->kt() ?></div>
  <div class="line"></div>
</nav>

<main id="main-content">
  <div id="background"></div>
  <!-- <div id="circle" class="starting-point" onclick="startTracklist();"></div> -->
  <div id="circle" class="starting-point" onclick="handleDotClick(event, this);"></div>
  <div id="color-cover"></div>
  <div id="player-ui">
    <button id="prev-track" onclick="openPrevTrack()">PREV TRACK</button>
    <button id="play-pause-button">PLAY/PAUSE</button>
    <button id="next-track" onclick="openNextTrack()">NEXT TRACK</button>
  </div>
</main>

<?php snippet("nav") ?>

<?php snippet("footer") ?>