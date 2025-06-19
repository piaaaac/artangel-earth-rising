<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta http-equiv="content-language" content="en">

  <?php snippet('seo/head'); ?>
  <?php snippet("favicon") ?>
  <?php snippet("load-scripts") ?>

  <script>
    window.siteUrl = '<?= $site->url() ?>';
    window.currentPage = '<?= $page->uid() ?>';
    window.currentTemplate = '<?= $page->template() ?>';
  </script>

  <?php snippet("analytics/consent-banner") ?>
  <?php snippet("analytics/gtag") ?>
  <?php snippet("analytics/tag-mngr-head") ?>

  <link rel="preload" as="image" href="<?= $site->url() ?>/assets/images/test-bg-l.gif" />

</head>

<body
  data-template="<?= $page->template()->name() ?>"
  data-current-uid="<?= $page->uid() ?>"
  data-player-playing="false"
  data-track-open=""
  data-accessibility-panel="false"
  data-track-info="false"
  data-about-panel="false"
  data-menu-panel="false">

  <?php snippet("analytics/tag-mngr-body") ?>