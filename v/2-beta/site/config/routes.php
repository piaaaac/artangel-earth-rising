<?php

return [

  [
    "pattern" => "vol1/(:any)",
    "page" => "home",
    "action"  => function ($slug) {
      // ignore route if it's the json representation
      if (Str::contains($slug, 'json')) {
        $this->next();
      }

      $uid = "null";
      if ($track = page("vol1/" . $slug)) {
        $uid = $track->uid();
      }
      $data = [
        "trackUid" => $uid,
      ];
      return page("home")->render($data);
    }
  ],

  [
    'pattern' => 'sandbox',
    'action'  => function () {
      snippet("sandbox");
    }
  ],

];
