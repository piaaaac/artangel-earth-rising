<?php

return [


  // --- Kirby

  "debug" => true,
  "whoops" => true,

  "routes" => require_once 'routes.php',

  "assets" => [
    "version" => "0.0.14",
  ],

  // "hooks" => require_once "hooks.php", // currently []

  "thumbs" => [
    "presets" => [
      "default" => ["width" => 800,   "quality" => 90],
      "full"    => ["width" => 1500,  "quality" => 90],
      "small"   => ["width" => 300,   "quality" => 20],
      "tiny"    => ["width" => 50,    "quality" => 20],
    ]
  ],

  // Kirby SEO plugin settings
  'tobimori.seo.canonicalBase' => "https://earth-rising.com",
  'tobimori.seo.lang' => 'en_GB',
  'tobimori.seo.robots' => [
    'active' => true,
    'content' => [
      'facebookexternalhit' =>  ['Disallow' => []],
      'Twitterbot' =>           ['Disallow' => []],
      'Googlebot' =>            ['Disallow' => ["/nogooglebot/"]],
    ]
  ],


];
