<?php
$data = [];

foreach (page("vol1")->children()->listed() as $track) {
  $item = $track->content()->toArray();
  $item["id"] = $track->id();
  $item["uid"] = $track->uid();
  $item["uuid"] = $track->uuid()->id();
  $item["url"] = $track->url();
  $data[] = $item;
}

$json = json_encode($data);
kill($json);






// $img = $p->files()->filterBy("template", "content-img")->first();
// kill($p->text());
