<?php

Kirby::plugin('my/plugin', [
  'siteMethods' => [

    'menuSmall' => function () {
      $languageSwitches = [];
      foreach(kirby()->languages() as $language) {
        if (kirby()->language() != $language) {
          $languageSwitches[] = [
            "languageLinkText" => html($language->name()),
            "languageLinkUrl" => page()->url($language->code()),
            "languageLinkHreflang" => $language->code(),
          ];
        }
      }
      ob_start(); 
      // --- php recording start ----------------------------------------------
      ?>

      <a class="menu-link inline">Gatto</a>
      <a class="menu-link inline">Privacy</a>
      <a class="menu-link inline">Instagram</a>
      <?php foreach ($languageSwitches as $l): ?>
        <a class="menu-link inline" href="<?= $l["languageLinkUrl"] ?>" hreflang="<?= $l["languageLinkHreflang"] ?>"><?= $l["languageLinkText"] ?></a>
      <?php endforeach ?>
    
      <?php 
      // --- php recording end ------------------------------------------------
      return ob_get_clean();
    }

  ]
]);

