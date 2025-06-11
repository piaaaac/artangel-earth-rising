<?php

// ---------------------------------------------
// Die and inspect variable
// ---------------------------------------------
// 
function kill($var, $continue = false)
{
  $msg = "<pre>" . print_r($var, true) . "</pre>";
  if (isset($continue) && $continue === true) {
    echo $msg;
  } else {
    die($msg);
  }
}


// ---------------------------------------------
// Map from a range to another
// via https://stackoverflow.com/a/7743244/2501713
// ---------------------------------------------
//
function map($value, $fromLow, $fromHigh, $toLow, $toHigh)
{
  $fromRange = $fromHigh - $fromLow;
  $toRange = $toHigh - $toLow;
  $scaleFactor = $toRange / $fromRange;

  // Re-zero the value within the from range
  $tmpValue = $value - $fromLow;
  // Rescale the value to the to range
  $tmpValue *= $scaleFactor;
  // Re-zero back to the to range
  return $tmpValue + $toLow;
}


// ---------------------------------------------
// Random string
// via https://stackoverflow.com/a/4356295
// ---------------------------------------------
//
function randomString($length = 10, $insertAlso = "", $prob = 0.1)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ     ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[random_int(0, $charactersLength - 1)];
    if ($insertAlso !== "" && rand(0, 1000) / 1000 < $prob) {
      $randomString .= $insertAlso;
    }
  }
  return $randomString;
}



// https://stackoverflow.com/a/13733588
function pseudoRandomBytes($length = 10)
{
  $bytes = random_bytes($length);
  return bin2hex($bytes);
}
