<?php /* 
Sandy Yu <sandy@artangel.org.uk> May 21, 2025, 3:15 PM
Would it be possible to also add the following:

<!-- Google tag (gtag.js) --> <script async src="https://www.googletagmanager.com/gtag/js?id=G-HFG0X77R6E"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-HFG0X77R6E'); </script>

So sorry for the last second ask! There are a whole load of other steps to configure the GTM one 
(which we'll need down the line but for now this is more than enough!)
*/ ?>


<!-- Google tag (gtag.js) -->

<script async src="https://www.googletagmanager.com/gtag/js?id=G-HFG0X77R6E"></script>
<script>
  window.dataLayer = window.dataLayer || [];

  function gtag() {
    dataLayer.push(arguments);
  }
  gtag('js', new Date());
  gtag('config', 'G-HFG0X77R6E');
</script>