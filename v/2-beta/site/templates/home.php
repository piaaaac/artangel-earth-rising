<?php
$ass = $kirby->url("assets");
?>

<?php snippet("header") ?>

<nav id="menu-panel">
  <ul>
    <li><a href="#">Track</a></li>
    <li><a href="#">Track</a></li>
    <li><a href="#">Track</a></li>
    <li><a href="#">Track</a></li>
    <li><a href="#">Track</a></li>
    <li><a href="#">Track</a></li>
    <li><a href="#">Track</a></li>
    <li><a href="#">Track</a></li>
    <li><a href="#">Track</a></li>
  </ul>
</nav>

<nav id="accessibility-panel">
  <ul>
    <li><a href="#">Access function</a></li>
    <li><a href="#">Access function</a></li>
    <li><a href="#">Access function</a></li>
  </ul>
</nav>

<nav id="about-panel">
  <h1>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit corporis, temporibus, optio dolorem ad quasi quam suscipit magni ullam eos ab deleniti accusamus a, aliquam minus in at fuga nisi.</h1>
  <h1>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit corporis, temporibus, optio dolorem ad quasi quam suscipit magni ullam eos ab deleniti accusamus a, aliquam minus in at fuga nisi.</h1>
  <h1>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit corporis, temporibus, optio dolorem ad quasi quam suscipit magni ullam eos ab deleniti accusamus a, aliquam minus in at fuga nisi.</h1>
  <h1>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit corporis, temporibus, optio dolorem ad quasi quam suscipit magni ullam eos ab deleniti accusamus a, aliquam minus in at fuga nisi.</h1>
</nav>

<div id="ui-line-top"></div>
<div id="ui-line-bottom"></div>
<div id="ui-line-left"></div>
<div id="ui-line-right"></div>
<button id="button-top-left" onclick="toggleAccessibilityPanel()">(•)</button>
<button id="button-top-right" type="button"
  class="hamburger hamburger--slider"
  onclick="toggleMenuPanel()"
  aria-label="Menu and Tracklist" aria-controls="navigation">
  <span class="hamburger-box">
    <span class="hamburger-inner"></span>
  </span>
</button>
<button id="button-bottom-left" type="button"
  class="hamburger hamburger--slider"
  onclick="toggleMenuPanel()"
  aria-label="Menu and Tracklist" aria-controls="navigation">
  <span class="hamburger-box">
    <span class="hamburger-inner"></span>
  </span>
</button>
<button id="button-bottom-right" onclick="toggleAboutPanel()">A</button>
<?php snippet("footer") ?>