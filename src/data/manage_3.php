<?php
header('Access-Control-Allow-Origin: *');
//manage_3

$dir1 = 'raw';
$dir2 = 'info';

//bepaal het upload-pad, relatief t.o.v. het PHP-pad i.v.m. server onafhankelijkheid____________________________________
$NowDir = getcwd();
$pathToImages = $NowDir . '/' . $dir1 . '/';
$pathToInfo = $NowDir . '/' . $dir2 . '/';

//read MD-files in <info> directory_____________________________________________________________________________________
$countInfo = 0;
$infos = [];
$infosOk = [];
$myDirectory = opendir($pathToInfo);
while($entryName = readdir($myDirectory)){
    $ext = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));
    if ($ext == 'md') {
        array_push($infos, pathinfo($entryName, PATHINFO_FILENAME));
        array_push($infosOk, 'NOK');
        $countInfo++;
    }
}

//read pictures in <raw> directory and determine new and already determined picture files_______________________________
$countPict = 0;
$countPictOk = 0;
$countPictNew = 0;
$raws = [];                                                                 //alle picture files
$rawsNew = [];                                                              //komen NIET voor in MD-files
$myDirectory = opendir($pathToImages);
while($entryName = readdir($myDirectory)){
    $ext = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));
    if ($ext == 'jpg') {
        $count = 0;
        $file = pathinfo($entryName, PATHINFO_FILENAME);
        while ($count < $countInfo) {
            if ($file == $infos[$count]) {
                array_push($raws, $file);
                $infosOk[$count] = 'OK';
                $count = $countInfo + 1;
                $countPictOk++;
            }
            $count++;
        }
        if ($count == $countInfo) {
            array_push($rawsNew, $file);                                    // new file
            $countPictNew++;
        }
        $countPict++;
    }
}

//erase not-represented md files and determine the item-numbers of the remaining________________________________________
$countOk = 0;
$infosN = [];
$infosI = [];
for ($count = 0; $count < $countInfo; $count++) {
    $del = $pathToInfo . $infos[$count] . '.md';
    if ($infosOk[$count] == 'NOK') {
        unlink($del);                                                       //erase: no corresponding picture file
    } else {
        $countOk++;
        array_push($infosN, $infos[$count]);
        $handle = fopen($del, 'r');                                         //read the item number, needed for sorting
        $contents = json_decode(fread($handle, filesize($del)));
        array_push($infosI, intval($contents->item));
        fclose($handle);
    }
}


//sort and renumber the array on item-number
if ($countOk > 0) {
    $count = 0;
    while ($count < ($countOk - 1)) {
        if ($count < 0) {
            $count = 0;
        }
        if ($infosI[$count] > $infosI[$count + 1]) {                             //swap de waarden
            $A = $infosI[$count];
            $infosI[$count] = $infosI[$count + 1];
            $infosI[$count + 1 ] = $A;
            $B = $infosN[$count];
            $infosN[$count] = $infosN[$count + 1];
            $infosN[$count + 1 ] = $B;
            $count--;
        } else {
            $count++;
        }
    }
}

//renumber de item-values of the MD-files in a sequence without holes
for ($count = 0; $count < $countOk; $count++) {
    $del = $pathToInfo . $infosN[$count] . '.md';
    $handle = fopen($del, 'r');
    $contents = json_decode(fread($handle, filesize($del)));
    fclose($handle);

    $handle = fopen($del, 'w');
    $contents->item = strval($count);
    $txtJson = json_encode($contents);
    fwrite($handle, $txtJson);
    fclose($handle);
}


//make new info-files for the new picture files
for ($count = 0; $count < $countPictNew; $count++) {
    $del = $pathToInfo . $rawsNew[$count] . '.md';
    $handle = fopen($del, 'w');
    $contents->pictname = $rawsNew[$count] . '.jpg';
    $contents->selected = '0';
    $contents->item = strval($countPictOk + $count);
    $contents->title1 = '';
    $contents->title2 = '';
    $contents->inhoud = '';
    $txtJson = json_encode($contents);
    fwrite($handle, $txtJson);
    fclose($handle);
    chmod($del, 0777);
}

//generate output for de redux-storage / (almost the) same in readMDtot1.php____________________________________________
//______________________________________________________________________________________________________________________//make new total inventarisation for output \ (almost the) same as in readMDtot.php_____________________________________
$SubDir = $pathToInfo;
$myDirectory = opendir($SubDir);

$count = 0;
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

$test = json_encode($ext);
echo json_encode($ext);

//______________________________________________________________________________________________________________________
//______________________________check bestaan van restrict/thumb directories en zet de chmod op <0777>__________________
if (!is_Dir($dir3)) {
    $success = mkdir($dir3, 0777);
};
//array_map('unlink', glob($dir3 . '/*.*'));
if (!is_Dir($dir4)) {
    $success = mkdir($dir4, 0777);
};
//array_map('unlink', glob($dir4 . '/*.*'));

return;
