<?php

return [

  [
    "pattern" => "(vol[0-9])/(:any)",
    "action"  => function ($vol, $slug) {
      // ignore route if it's the json representation
      if (Str::contains($slug, 'json')) {
        $this->next();
      }

      $uid = "null";
      if ($track = page("$vol/$slug")) {
        $uid = $track->uid();
      }
      $data = [
        "volumeUid" => $vol,
        "trackUid" => $uid,
      ];
      return page($vol)->render($data);
    }
  ],

  [
    'pattern' => 'sandbox',
    'action'  => function () {
      snippet("sandbox");
    }
  ],

];
