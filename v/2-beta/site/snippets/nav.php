<?php $ass = $kirby->url("assets"); ?>

<!-- Texts -->

<div id="top-txt">
  <img class="only-dsk" src="<?= $ass ?>/images/stars-volume-1-r2l.svg" alt="Volume 1 Logo">
  <h1 class="mx-3">EARTH RISING</h1>
  <img class="only-dsk" src="<?= $ass ?>/images/stars-volume-1-l2r.svg" alt="Volume 1 Logo">
</div>

<div id="bottom-txt">
  <h2 class="only-dsk">Messages from the Pale Blue Dot</h2>
</div>

<!-- Buttons -->

<nav id="button-wrapper-top-left">
  <button onclick="toggleAccessibilityPanel()">
    <img src="<?= $ass ?>/images/icon-accessibility.svg" alt="Accessibility Icon">
  </button>
</nav>

<nav id="button-wrapper-top-right">
  <button type="button" style="position: relative; top: 2.5px;"
    class="hamburger hamburger--slider"
    onclick="toggleMenuPanel()"
    aria-label="Menu and Tracklist" aria-controls="navigation">
    <span class="hamburger-box">
      <span class="hamburger-inner"></span>
    </span>
  </button>
</nav>

<nav id="button-wrapper-bottom-left">
  <button type="button" style="position: relative; top: 2.5px;"
    class="hamburger hamburger--slider"
    onclick="toggleMenuPanel()"
    aria-label="Menu and Tracklist" aria-controls="navigation">
    <span class="hamburger-box">
      <span class="hamburger-inner"></span>
    </span>
  </button>
</nav>

<nav id="button-wrapper-bottom-right">
  <button onclick="toggleAboutPanel()" style="position: relative; top: 4px;">
    <img src="<?= $ass ?>/images/icon-artangel.svg" alt="Accessibility Icon">
  </button>
</nav>

<!-- Lines -->

<div id="ui-line-top"></div>
<div id="ui-line-bottom"></div>
<div id="ui-line-left"></div>
<div id="ui-line-right"></div>