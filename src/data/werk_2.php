<?php
//werk_2.php ,een bewerkt md-file wegschrijven en daarna alle weer inlezen zoals in prepare_1.php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

$body = file_get_contents("php://input");
$ext = json_decode($body);
$ext1 = json_decode($ext, true);

//_____________________________________schrijf de veranderde gegevens van de desbetreffende file weg
$content1 = $ext1['content1'];
$content2 = $ext1['content2'];
$veld = $ext1['veldNu'];
$pag = $ext1['pagNu'];
$veldenM = intval($ext1['velden']);
$pagM = intval($ext1['pag']);

$sub = (object)['content1' => $content1, 'content2' => $content2, 'veld' => $veld, 'pag' => $pag];
$veldL = str_pad($veld,2,"0",STR_PAD_LEFT);

$NowDir = getcwd();

$FileDir = $NowDir . '/werk/';
$FileName = $FileDir . $pag . $veldL . '.md';

unlink($FileName);
$myFile = fopen($FileName, 'w') or die('unable to open file');
fwrite($myFile, json_encode($sub));
fclose($myFile);
chmod($myFile, 0777);

//____________________________________lees alle werkfiles in en lever ze uit
$count = 0;
$data = [];
$idN = 0;
$val0 = [];

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
            $iVeld = intval($cc['veld']);
            $iPag = intval($cc['pag']);
            $idN = $iVeld + $iPag * $veldenM;

            array_push($data, $cc);
            array_push($val0, $idN);

            $count++;
        }
    }
}
closedir($myDirectory);


//sorteren op de $val0-waarde
if ($count > 0) {
    $n = 0;
    while ($n < ($count - 1)) {
        if ($val0[$n] > $val0[$n + 1]) {
            $A = $val0[$n];
            $val0[$n] = $val0[$n + 1];
            $val0[$n + 1] = $A;

            $m = $data[$n];
            $data[$n] = $data[$n + 1];
            $data[$n + 1] = $m;

            $n = -1;
        }
        $n++;
    }
}
//ontbrekende md files aanmaken
$count = 0;
$ext1 = [];
for ($i = 0; $i < $pagM; $i++) {
    $ext = [];
    $iS = strval($i);
    for ($j = 0; $j < $veldenM; $j++) {
        $jS = str_pad(strval($j),2,"0",STR_PAD_LEFT);
        $idN = $j + $i * $veldenM;
        if ($val0[$count] === $idN) {
            $count++;
            $sub = (object)['content1' => $data[$idN]['content1'], 'content2' => $data[$idN]['content2'], 'veld' => $jS, 'pag' => $iS];
            array_push($ext, $sub);
        }  else {
            $empty = (object)['content1' => '', 'content2' => '', 'veld' => $jS, 'pag' => $iS];
            array_push($ext, $empty);
            $empty1 = json_encode($empty);
            $myFileName = $FileDir . $iS . $jS . '.md';
            $myFile = fopen($myFileName, 'w') or die('unable to open file');
            fwrite($myFile, $empty1);
            fclose($myFile);
            chmod($myFile, 0777);
        }
    }
    array_push($ext1,$ext);
}

$ext2 = json_encode($ext1);
echo $ext2;
return;
