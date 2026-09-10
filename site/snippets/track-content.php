<?php

/** 
 * @param track - Kirby page
 */

// kill($track->trackType());

if ($track->trackType()->value() === "slideshow") {
  return snippet("content-slideshow", ["p" => $track]);
} elseif ($track->trackType()->value() === "audio") {
  return snippet("content-audio", ["p" => $track]);
} elseif ($track->trackType()->value() === "video") {
  return snippet("content-video", ["p" => $track]);
}
