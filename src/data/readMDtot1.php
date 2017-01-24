<?php
header('Access-Control-Allow-Origin: *');
//readMDtot

$NowDir = getcwd();
$SubDir = $NowDir . '/info/';
$myDirectory = opendir($SubDir);				                      //lees de file namen in

$count = 0;                                                           //de teller
$data = [];
$data1 = [];

$i = 0;
$id = 0;
$idN = 0;
$idMax = 0;

$inhoud = [];
$item = [];
$pictName = [];
$selected = [];
$title1 = [];
$title2 = [];

while($entryName = readdir($myDirectory)) {
    if (substr("$entryName", 0, 1) != ".") {
        $ext = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));
        if ( $ext == 'md' ) {                                                                  // continue only if this is a md file
            $filename = $SubDir . $entryName;

            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);

            $cc = json_decode($contents, true);

            $idN = (int)$cc["item"];
            if ($idN > $idMax) {
                $idMax = $idN;
            }

            $placed = ( $count == 0 );
            $idN = (int)$cc["item"];

            array_push($data, $cc);
            $count++;
        }
    }
}
closedir($myDirectory);

for ($id = 0; $id <= $idMax; $id++) {
    for ($i = 0; $i <= $idMax; $i++) {
        $idN = (int)$data[$i]["item"];
        if ($idN == $id) {
            $cc = $data[$i];
            array_push($data1, $cc);
//            $i = $count;
        }
    }
}


for ($id = 0; $id <= $idMax; $id++) {
    array_push($pictName, $data1[$id][pictname]);
    array_push($inhoud, $data1[$id][inhoud]);
    array_push($item, $data1[$id][item]);
    array_push($selected, $data1[$id][selected]);
    array_push($title1, $data1[$id][title1]);
    array_push($title2, $data1[$id][title2]);
}

$ext = (object) ['inhoud' => $inhoud, 'item' => $item, 'pictName' => $pictName, 'selected' => $selected, 'title1' => $title1, 'title2' => $title2];
$tus = json_encode($ext);

echo $tus;
?>
