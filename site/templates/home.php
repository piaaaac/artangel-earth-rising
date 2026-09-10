<?php
$ass = $kirby->url("assets");
?>

<?php snippet("header") ?>

<main>
  <?php snippet("volumes-selection") ?>
</main>

<script>
  const wui = new WebUI();
  const app = new App(wui, null, null);
</script>

<?php snippet("nav") ?>

<?php snippet("footer") ?>