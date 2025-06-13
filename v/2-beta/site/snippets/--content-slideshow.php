<?php

/** 
 * @param p - Kirby page
 */
?>

<div data-content-type="slideshow">
  <h4><?= $p->title() ?></h4>
  <audio id="player" controls>
    <source src="/path/to/audio.mp3" type="audio/mp3" />
    <source src="/path/to/audio.ogg" type="audio/ogg" />
  </audio>
</div>