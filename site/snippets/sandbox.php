<video controls playsinline width="640" height="360">
  <source src="http://er-beta.test/media/pages/vol1/inner-lithophony-feat-cristian-heyne/eec1fe93f1-1750080179/riffo-safari-fix.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>

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
