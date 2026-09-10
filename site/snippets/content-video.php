<?php

/** 
 * @param p - Kirby page
 */
?>

<div data-content-type="video">
  <h4><?= $p->title() ?></h4>
  <video id="player" playsinline controls data-poster="/path/to/poster.jpg">
    <source src="/path/to/video.mp4" type="video/mp4" />
    <source src="/path/to/video.webm" type="video/webm" />

    <!-- Captions are optional -->
    <track kind="captions" label="English captions" src="/path/to/captions.vtt" srclang="en" default />
  </video>
</div>