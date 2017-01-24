<?php
//public.php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");


$body = file_get_contents("php://input");
$ext = json_decode($body);
$ext1 = json_decode($ext, true);

$tekst = $ext1['tekst'];
$info = $ext1['info'];

$myfile = fopen("WerkInfo.md", "w") or die("Unable to open file!");
fwrite($myfile, json_encode($tekst));
fclose($myfile);

$myfile = fopen("DataInfo.md", "w") or die("Unable to open file!");
fwrite($myfile, json_encode($info));
fclose($myfile);

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

