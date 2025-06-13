<?php
$fieldId = "block-acc-" . pseudoRandomBytes();
?>

<div class="block accordion" id="<?= $fieldId ?>" data-initialized="false">

  <?php foreach ($block->items()->toStructure() as $item) : ?>
    <div class="accordion-item">
      <div class="accordion-header">
        <h3 class="accordion-title">
          <?= $item->itemHeader()->kt() ?>
        </h3>
        <button class="accordion-arrow"></button>
      </div>
      <div class="accordion-content">
        <div class="accordion-body">
          <?= $item->itemContent()->kt() ?>
        </div>
      </div>
    </div>
  <?php endforeach ?>

</div>

<script>
  // Initialize accordion when DOM is loaded
  document.addEventListener("DOMContentLoaded", () => {
    // var fieldId = "<?= $fieldId ?>";
    // const accordionElement = document.getElementById(fieldId);
    // const acc = new Accordion(accordionElement);
    // console.log(acc);
    initNewAccordions();
  });
</script>