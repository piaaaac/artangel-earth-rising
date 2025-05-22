<?php $ass = $kirby->url("assets"); ?>

<!-- Texts -->

<div id="top-txt">
  <img class="only-dsk" src="<?= $ass ?>/images/stars-volume-1-r2l.svg" alt="Volume 1 Logo">
  <a href="<?= $site->url() ?>" onclick="handleTitleClick(event);">
    <h1 class="mx-3">EARTH RISING</h1>
  </a>
  <img class="only-dsk" src="<?= $ass ?>/images/stars-volume-1-l2r.svg" alt="Volume 1 Logo">
</div>

<div id="bottom-txt">
  <h2 class="only-dsk">Messages from the Pale Blue Dot</h2>
</div>

<!-- Buttons -->

<nav class="button-wrapper" id="button-wrapper-top-left">
  <button onclick="toggleAccessibilityPanel()">
    <img src="<?= $ass ?>/images/icon-accessibility.svg" alt="Accessibility Icon">
  </button>
</nav>

<nav class="button-wrapper" id="button-wrapper-top-right">
  <button type="button" style="position: relative; top: 2.5px;"
    class="hamburger hamburger--slider"
    onclick="toggleMenuPanel()"
    aria-label="Menu and Tracklist" aria-controls="navigation">
    <span class="hamburger-box">
      <span class="hamburger-inner"></span>
    </span>
  </button>
</nav>

<nav class="button-wrapper" id="button-wrapper-bottom-left">
  <button type="button" style="position: relative; top: 2.5px;"
    class="hamburger hamburger--slider"
    onclick="toggleMenuPanel()"
    aria-label="Menu and Tracklist" aria-controls="navigation">
    <span class="hamburger-box">
      <span class="hamburger-inner"></span>
    </span>
  </button>
</nav>

<nav class="button-wrapper" id="button-wrapper-bottom-right">
  <button onclick="toggleAboutPanel()" style="position: relative; top: 4px;">
    <img src="<?= $ass ?>/images/icon-artangel.svg" alt="Accessibility Icon">
  </button>
</nav>

<!-- Track info on the sides -->

<section id="track-info-dsk-l">
  <div class="content-wrapper">
    <div id="track-info-artist"></div>
  </div>
  <div class="vertical-bar" role="button" aria-label="Toggle track info" onclick="toggleTrackInfo()">
    <h2 id="track-artist"></h2>
  </div>
  <span class="button-wrapper"><img src="<?= $ass ?>/images/icon-rarr.svg" /></span>
</section>
<section id="track-info-dsk-r">
  <div class="vertical-bar" role="button" aria-label="Toggle track info" onclick="toggleTrackInfo()">
    <h2 id="track-title"></h2>
  </div>
  <div class="content-wrapper">
    <div id="track-info-script"></div>
  </div>
  <span class="button-wrapper"><img src="<?= $ass ?>/images/icon-larr.svg" /></span>
</section>

<!-- Lines -->

<div id="ui-line-top"></div>
<div id="ui-line-bottom"></div>
<div id="ui-line-left"></div>
<div id="ui-line-right"></div>