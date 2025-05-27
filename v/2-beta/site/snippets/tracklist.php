<?php

/** 
 * @param tracksPage Kirby page
 */
?>

<ul class="content-wrapper">
  <?php foreach ($tracksPage->children()->listed() as $track): ?>
    <li>
      <a class="track custom-hover"
        style="--hover-color: <?= $track->uiColor()->value() ?>;"
        href="<?= $track->url() ?>"
        data-track-uuid="<?= $track->uuid()->id() ?>"
        data-track-uid="<?= $track->uid() ?>"
        data-track-id="<?= $track->id() ?>"
        onclick="handleTrackClick(event, this);">
        <span class="left"><?= $track->artist() ?></span>
        <span class="right"><?= $track->title() ?></span>
      </a>
    </li>
  <?php endforeach ?>
</ul>