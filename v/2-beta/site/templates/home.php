<?php
$aboutPage = page("about");
$creditsPage = page("credits");
$vol1Page = page("vol1");

// Prepare data to pass to js
$data = [];
$i = 0;
foreach ($vol1Page->children()->listed() as $key => $track) {
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

<main id="main-content">
  <div id="background"></div>
  <!-- <div id="circle" class="starting-point" onclick="startTracklist();"></div> -->
  <div id="circle" class="starting-point" onclick="openNextTrack();"></div>
  <div id="color-cover"></div>
</main>

<?php snippet("nav") ?>

<?php snippet("footer") ?>