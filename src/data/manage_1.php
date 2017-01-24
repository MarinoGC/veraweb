<?php
header('Access-Control-Allow-Origin: *');
//manage_1

//bepaal het upload-pad, relatief t.o.v. het PHP-pad i.v.m. server onafhankelijkheid____________________________________
$NowDir = getcwd();
$dir1 = 'raw';
$dir2 = 'info';
$dir3 = 'restrict';
$dir4 = 'thumb';

$pathToImages = $NowDir . '/' . $dir1 . '/';

//read raw-files in <raw> directory_____________________________________________________________________________________
$countInfo = 0;
$raws = [];
$myDirectory = opendir($pathToImages);
while($entryName = readdir($myDirectory)){
    $ext = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));
    if ($ext == 'jpg') {
        array_push($raws, pathinfo($entryName, PATHINFO_FILENAME));
        $countInfo++;
    }
}

//______________________________check bestaan van restrict/thumb directories en zet de chmod op <0777>__________________
if (!is_Dir($dir3)) {
    $success = mkdir($dir3, 0777);
};
array_map('unlink', glob($dir3 . '/*.*'));
if (!is_Dir($dir4)) {
    $success = mkdir($dir4, 0777);
};
array_map('unlink', glob($dir4 . '/*.*'));


//________________maak een kladblok met gegevens voor manage_2.php, omdat sommige servers moeilijk doen over post-requests

$del = $NowDir . '/kladblok.md';
$handle = fopen($del, 'w');
$contents-> pictFiles = $raws;
$contents-> lengte = $countInfo;
$contents-> counter = '0';
$txtJson = json_encode($contents);
fwrite($handle, $txtJson);
fclose($handle);
chmod($del, 0777);

//_______________________________________________________________________________
$ext = (object) ['pictures' => $raws, 'lengte' => $countInfo, 'dataDir' => $NowDir, 'rawDir' => $pathToImages];
echo json_encode($ext);
return;
