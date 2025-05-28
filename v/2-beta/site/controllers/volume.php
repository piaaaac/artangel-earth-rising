<?php

/**
 * @param $volumeUid - from route pattern "(vol[0-9])/(:any)"
 * @param $trackUid - from route pattern "(vol[0-9])/(:any)"
 * */

return function ($page, $site, $kirby, $volumeUid, $trackUid) {
  return [
    "volumeUid" => $volumeUid,
    "trackUid" => $trackUid,
  ];
};
