<?php

$p = page("home");

$allProjects = site()->index(true)->template("project");
// foreach ($allProjects as $project) {
// 	echo $project->title();
// }

$p = $allProjects->first();
$htmlDescription = kt($p->text()->value());
$json['htmlDescription'] = $htmlDescription;

echo json_encode($json);
kill($json);






// $img = $p->files()->filterBy("template", "content-img")->first();
// kill($p->text());

