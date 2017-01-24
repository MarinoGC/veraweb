<?php
//public.php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");


$body = file_get_contents("php://input");
$ext = json_decode($body);
$ext1 = json_decode($ext, true);

//___________________________________unit voor inventarisatie dataInfo: begin (met sorteer functie)
$NowDir = getcwd();
$FileDir = $NowDir . '/info/';
$inv = [];
$count = 0;

$myDirectory = opendir($FileDir);
while($entryName = readdir($myDirectory)) {
    if (substr("$entryName", 0, 1) != ".") {
        $ext = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));
        if ( $ext == 'md' ) {                                                                  // continue only if this is a md file
            $filename = $FileDir . $entryName;

            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);

            $cc = json_decode($contents, true);
            array_push($inv, $cc);

            $count++;
        }
    }
}
closedir($myDirectory);

$ci = 0;
while ($ci < ($count - 1)) {
    if ($ci < 0 ) {
        $ci = 0;
    }
    $s = $inv[$ci]['item'];
    $ciN = intval($s);
    $s = $inv[$ci + 1]['item'];
    $ciP = intval($s);
    if ($ciN > $ciP) {
        $A = $inv[$ci];
        $inv[$ci] = $inv[$ci + 1];
        $inv[$ci + 1] = $A;
        $ci--;
    } else {
        $ci++;
    }
}

$invJ = json_encode($inv);

$myFileName = $NowDir . '/DataInfo.md';
$myFile = fopen($myFileName, 'w') or die('unable to open file');
fwrite($myFile, $invJ);
fclose($myFile);
chmod($myFile, 0755);
//___________________________________unit voor inventarisatie dataInfo: eind

//___________________________________unit voor inventarisatie werkInfo: begin (met sorteer functie)
$NowDir = getcwd();
$FileDir = $NowDir . '/werk/';
$inv = [];

$myDirectory = opendir($FileDir);
while($entryName = readdir($myDirectory)) {
    if (substr("$entryName", 0, 1) != ".") {
        $ext = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));
        if ( $ext == 'md' ) {                                                                  // continue only if this is a md file
            $filename = $FileDir . $entryName;

            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);

            $cc = json_decode($contents, true);
            array_push($inv, $cc);

            $count++;
        }
    }
}
closedir($myDirectory);

$ci = 0;
while ($ci < ($count - 1)) {
    if ($ci < 0 ) {
        $ci = 0;
    }
    $s = $inv[$ci]['item'];
    $ciN = intval($s);
    $s = $inv[$ci + 1]['item'];
    $ciP = intval($s);
    if ($ciN > $ciP) {
        $A = $inv[$ci];
        $inv[$ci] = $inv[$ci + 1];
        $inv[$ci + 1] = $A;
        $ci--;
    } else {
        $ci++;
    }
}

$invJ = json_encode($inv);

$myFileName = $NowDir . '/WerkInfo.md';
$myFile = fopen($myFileName, 'w') or die('unable to open file');
fwrite($myFile, $invJ);
fclose($myFile);
chmod($myFile, 0777);
//___________________________________unit voor inventarisatie werkInfo: eind


$NowDir = getcwd();
$element = explode('/', $NowDir);
$lengte = sizeof($element);

$NewDir = '';
$test = $ext1['dataPad'];
for ($n = 0; $n < $lengte; $n++) {
    if ($element[$n] != $test) {
        $NewDir = $NewDir . $element[$n];
        if ($n < ($lengte - 1)) {
            $NewDir = $NewDir . '/';
        };
    }
}

if ($NowDir == $NewDir) {
    $ext3 = json_encode('directories the same, no action');
    echo $ext3;
    return;
}

delete_files($NewDir);

if (!is_dir($NewDir)) {
    mkdir($NewDir);
    chmod($NewDir, 0777);
}

cpy($NowDir, $NewDir);

$ext2 = (object) ['oldPad' => $NowDir, 'newPad' => $NewDir];
$ext3 = json_encode($ext2);
echo $ext3;

return;
//_____________________________________________________________________________________
function cpy($source, $dest) {
    if(is_dir($source)) {
        $dir_handle=opendir($source);
        while($file=readdir($dir_handle)){
            if($file!="." && $file!=".."){
                if(is_dir($source."/".$file)){
                    if(!is_dir($dest."/".$file)){
                        mkdir($dest."/".$file);
                        chmod($dest."/".$file, 0777);
                    }
                    cpy($source."/".$file, $dest."/".$file);
                } else {
                    copy($source."/".$file, $dest."/".$file);
                    chmod($dest."/".$file, 0777);
                }
            }
        }
        closedir($dir_handle);
    } else {
        copy($source, $dest);
        chmod($dest, 0777);
    }
}

function delete_files($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir")
                    delete_files($dir."/".$object);
                else unlink   ($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

