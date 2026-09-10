<?php
$volumes = $site->children()->filterBy("intendedTemplate", "volume")->sortBy("title", "asc");
?>

<div class="volume-selection-wrapper">
  <?php foreach ($volumes as $volume):
    $status = $volume->status();
    $disabled = $status === "unlisted" ? " disabled" : "";
    $imageUrl = $volume->volumeImage()->isNotEmpty() ? $volume->volumeImage()->toFile()->url() : "";
    $number = "1";
    if (preg_match('/(\d+)/', $volume->uid(), $matches)) {
      $number = $matches[1];
    }
  ?>
    <div class="item<?= $disabled ?>">
      <div>
        <a href="<?= $volume->url() ?>">
          <img class="cover" src="<?= $imageUrl ?>" alt="<?= $volume->title() ?> cover image" />
        </a>
      </div>
      <div class="my-4">
        <a class="no-u" href="<?= $volume->url() ?>">
          <?= $volume->title()->upper() ?>
        </a>
      </div>
      <div>
        <a class="d-inline-block" href="<?= $volume->url() ?>">
          <svg class="stars" data-active-star="<?= $number ?>" width="98px" height="26px" viewBox="0 0 98 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <path d="M12.4968451,25.6540895 C12.358154,18.0890274 7.65487468,13.5 0,13.5 L0,12.5 C7.77085763,12.5 12.5,7.77085763 12.5,0 L13.5,0 C13.5,7.77085763 18.2291424,12.5 26,12.5 L26,13.5 C18.2291424,13.5 13.5,18.2291424 13.5,26 L12.5,26 L12.4968451,25.6540895 Z" class="star star1" fill="#F1F3D7"></path>
            <path d="M48.4968451,25.6540895 C48.358154,18.0890274 43.6548747,13.5 36,13.5 L36,12.5 C43.7708576,12.5 48.5,7.77085763 48.5,0 L49.5,0 C49.5,7.77085763 54.2291424,12.5 62,12.5 L62,13.5 C54.2291424,13.5 49.5,18.2291424 49.5,26 L48.5,26 L48.4968451,25.6540895 Z" class="star star2" fill="#F1F3D7"></path>
            <path d="M84.4968451,25.6540895 C84.358154,18.0890274 79.6548747,13.5 72,13.5 L72,12.5 C79.7708576,12.5 84.5,7.77085763 84.5,0 L85.5,0 C85.5,7.77085763 90.2291424,12.5 98,12.5 L98,13.5 C90.2291424,13.5 85.5,18.2291424 85.5,26 L84.5,26 L84.4968451,25.6540895 Z" class="star star3" fill="#F1F3D7"></path>
          </svg>
        </a>
      </div>
    </div>
  <?php endforeach ?>
</div>