<?php
//spyPict.php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");


$body = file_get_contents("php://input");
$ext = json_decode($body);
$ext1 = json_decode($ext, true);
$ext2 = $ext1['moot'];
$pad = $ext1['pad'];

$NowDir = getcwd();
$pict = [];
$pictTot = [];
$tot = sizeof($ext1['moot']['pictName']);

$widthTot = [];
$heightTot = [];
$sizeTot = [];
$srcTot = [];

for ($i = 0; $i < $tot; $i++) {
    $name = $ext2['pictName'][$i];
    array_push($pict, $name);

    $pictTus = [];
    $pictTus1 = [];
    $width = [];
    $height = [];
    $size = [];
    $src = [];
    array_push($pictTus1, $pad . 'raw/' . $name);
    array_push($pictTus1, $pad . 'restrict/' . $name);
    array_push($pictTus1, $pad . 'thumb/' . $name);
    array_push($pictTus, $NowDir . '/raw/' . $name);
    array_push($pictTus, $NowDir . '/restrict/' . $name);
    array_push($pictTus, $NowDir . '/thumb/' . $name);
    for ($j = 0; $j < 3; $j++) {
        $img = imagecreatefromjpeg($pictTus[$j]);
        array_push($width, imagesx($img));
        array_push($height, imagesy($img));
        array_push($size, filesize($pictTus[$j]));
//        array_push($src, '<img src="' . $pictTus1[$j] . '" style="height:' . $height[$j] .'px;">');
        array_push($src, '<img src="' . $pictTus1[$j] . '" height="auto">');
    }
    array_push($widthTot, $width);
    array_push($heightTot, $height);
    array_push($sizeTot, $size);
    array_push($srcTot, $src);
}
$ext = (object) ['width' => $widthTot, 'height' => $heightTot, 'size' => $sizeTot, 'src' => $srcTot];
$ext1 = json_encode($ext);
echo $ext1;
return;
