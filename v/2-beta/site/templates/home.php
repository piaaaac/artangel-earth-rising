<?php
$aboutPage = page("about");
$creditsPage = page("credits");
$vol1Page = page("vol1");
?>

<?php snippet("header") ?>

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

<?php snippet("nav") ?>

<?php snippet("footer") ?>