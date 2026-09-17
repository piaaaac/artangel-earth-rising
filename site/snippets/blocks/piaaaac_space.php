<?php

// Height

$h = $block->spaceSize()->value();
if ($block->spaceSize()->value() === "custom") {
  $h = $block->customSize()->value();
}
?>

<div id="<?= $block->id() ?>"
  class="block my-0"
  style="height: <?= $h ?>"
  data-block-type="<?= $block->type() ?>"></div>