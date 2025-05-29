<?php $ass = $kirby->url("assets"); ?>

<!-- Texts -->

<div id="top-txt">
  <div class="stars left only-dsk">
    <svg width="98px" height="26px" viewBox="0 0 98 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <path d="M12.4968451,25.6540895 C12.358154,18.0890274 7.65487468,13.5 0,13.5 L0,12.5 C7.77085763,12.5 12.5,7.77085763 12.5,0 L13.5,0 C13.5,7.77085763 18.2291424,12.5 26,12.5 L26,13.5 C18.2291424,13.5 13.5,18.2291424 13.5,26 L12.5,26 L12.4968451,25.6540895 Z" class="star star3" fill="#F1F3D7"></path>
      <path d="M48.4968451,25.6540895 C48.358154,18.0890274 43.6548747,13.5 36,13.5 L36,12.5 C43.7708576,12.5 48.5,7.77085763 48.5,0 L49.5,0 C49.5,7.77085763 54.2291424,12.5 62,12.5 L62,13.5 C54.2291424,13.5 49.5,18.2291424 49.5,26 L48.5,26 L48.4968451,25.6540895 Z" class="star star2" fill="#F1F3D7"></path>
      <path d="M84.4968451,25.6540895 C84.358154,18.0890274 79.6548747,13.5 72,13.5 L72,12.5 C79.7708576,12.5 84.5,7.77085763 84.5,0 L85.5,0 C85.5,7.77085763 90.2291424,12.5 98,12.5 L98,13.5 C90.2291424,13.5 85.5,18.2291424 85.5,26 L84.5,26 L84.4968451,25.6540895 Z" class="star star1" fill="#F1F3D7"></path>
    </svg>
  </div>
  <!-- <img class="only-dsk" src="<?= $ass ?>/images/stars-volume-1-r2l.svg" alt="Volume 1 Logo"> -->
  <a class="only-dsk" href="<?= $site->url() ?>" onclick="handleTitleClick(event);">
    <h1 class="mx-3">EARTH RISING</h1>
  </a>
  <!-- <img class="only-dsk" src="<?= $ass ?>/images/stars-volume-1-l2r.svg" alt="Volume 1 Logo"> -->
  <div class="stars right">
    <svg width="98px" height="26px" viewBox="0 0 98 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <path d="M12.4968451,25.6540895 C12.358154,18.0890274 7.65487468,13.5 0,13.5 L0,12.5 C7.77085763,12.5 12.5,7.77085763 12.5,0 L13.5,0 C13.5,7.77085763 18.2291424,12.5 26,12.5 L26,13.5 C18.2291424,13.5 13.5,18.2291424 13.5,26 L12.5,26 L12.4968451,25.6540895 Z" class="star star3" fill="#F1F3D7"></path>
      <path d="M48.4968451,25.6540895 C48.358154,18.0890274 43.6548747,13.5 36,13.5 L36,12.5 C43.7708576,12.5 48.5,7.77085763 48.5,0 L49.5,0 C49.5,7.77085763 54.2291424,12.5 62,12.5 L62,13.5 C54.2291424,13.5 49.5,18.2291424 49.5,26 L48.5,26 L48.4968451,25.6540895 Z" class="star star2" fill="#F1F3D7"></path>
      <path d="M84.4968451,25.6540895 C84.358154,18.0890274 79.6548747,13.5 72,13.5 L72,12.5 C79.7708576,12.5 84.5,7.77085763 84.5,0 L85.5,0 C85.5,7.77085763 90.2291424,12.5 98,12.5 L98,13.5 C90.2291424,13.5 85.5,18.2291424 85.5,26 L84.5,26 L84.4968451,25.6540895 Z" class="star star1" fill="#F1F3D7"></path>
    </svg>
  </div>
</div>

<div id="bottom-txt">
  <h2 class="only-dsk">Messages from the Pale Blue Dot</h2>
</div>

<div id="bottom-txt-about" role="button" onclick="toggleAboutPanel()">
  <h2 class="only-dsk">ABOUT & CREDITS</h2>
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
  <button onclick="toggleArtangelPanel()" style="position: relative; top: 4px;">
    <img src="<?= $ass ?>/images/icon-artangel.svg" alt="Artangel Icon">
  </button>
</nav>

<!-- Track info mobile -->

<section id="track-info-mob">
  <div id="track-info-artist-mob"></div>
  <div id="track-info-script-mob"></div>
</section>

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