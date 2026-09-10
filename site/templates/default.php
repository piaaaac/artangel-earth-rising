<?php
$ass = $kirby->url("assets");
?>

<?php snippet("header") ?>

<main>
  <div class="kt-container py-3">
    <?= $page->blocks()->toBlocks() ?>
  </div>
</main>

<script>
  const wui = new WebUI();
  const app = new App(wui, null, null);
</script>

<?php snippet("nav") ?>

<?php snippet("footer") ?>