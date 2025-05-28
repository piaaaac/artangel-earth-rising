<?php

/**
 * @param $trackUid - from route pattern "(vol[0-9])/(:any)"
 * */

return function ($page, $site, $kirby, $trackUid) {
  return [
    "trackUid" => $trackUid,
  ];
};
