<?php
$aboutPage = page("about");
$creditsPage = page("credits");
$vol1Page = page("vol1");

// Prepare data to pass to js
$data = [];
foreach ($vol1Page->children()->listed() as $track) {
  $item = $track->content()->toArray();
  $item["id"] = $track->id();
  $item["uid"] = $track->uid();
  $item["uuid"] = $track->uuid()->id();
  $item["url"] = $track->url();
  $data[] = $item;
}
$json = json_encode($data);
?>

<?php snippet("header") ?>

<script>
  const tracks = <?= $json ?>;
  console.log(tracks);
</script>

<nav id="menu-panel">
  <?php snippet("tracklist", ["tracksPage" => $vol1Page]) ?>
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

<section id="track-info-dsk-l">
  <div class="content-wrapper"></div>
  <div role="button" aria-label="Toggle track info" class="vertical-bar" onclick="toggleTrackInfo()">
    <h2 id="track-artist"></h2>
  </div>
</section>
<section id="track-info-dsk-r">
  <div role="button" aria-label="Toggle track info" class="vertical-bar" onclick="toggleTrackInfo()">
    <h2 id="track-title"></h2>
  </div>
  <div class="content-wrapper"></div>
</section>

<main id="main-content"></main>

<?php snippet("nav") ?>

<?php snippet("footer") ?>