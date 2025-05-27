<?php

/**
 * @param $trackUid - from route pattern "vol1/(:any)"
 * */

return function ($page, $site, $kirby, $trackUid) {
  return [
    "trackUid" => $trackUid,
  ];
};
