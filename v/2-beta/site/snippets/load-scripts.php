<?= css(['assets/css/bootstrap-custom.css?v=' . option('assets.version')]) ?>
<?= css(['assets/css/index.css?v=' . option('assets.version')]) ?>
<?= js(['assets/js/functions-polyfills.js?v=' . option('assets.version')]) ?>
<?= js(['assets/js/blocks.js?v=' . option('assets.version')]) ?>

<link rel="stylesheet" href="<?= $kirby->url("assets") ?>/lib/plyr-3.7.8/dist/plyr.css" />