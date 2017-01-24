<?PHP
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");
//wisMoot

$selMax = 6;

//__________________________________________de files in de een na laatste moot wissen______________________________________

$body = file_get_contents("php://input");
$ext = json_decode($body);
$length = sizeof($ext);

$NowDir = getcwd();
$dir1 = $NowDir . '/raw/';
$dir2 = $NowDir . '/info/';
$dir3 = $NowDir . '/restrict/';
$dir4 = $NowDir . '/thumb/';

for ($n = 0; $n < $length; $n++) {
    $file = $ext[$n];
    unlink($dir1 . $file);
    unlink($dir3 . $file);
    unlink($dir4 . $file);
    $filemd = substr($file, 0, -3) . 'md';
    unlink($dir2 . $filemd);
}
unlink($nowDir . 'kladblok.md');
//__________________________________________de overige files weer netjes maken_____________________________

$SubDir = $NowDir . '/info/';
$myDirectory = opendir($SubDir);				                      //lees de file namen in

$count = 0;                                                           //de teller
$data = [];
$data1 = [];
$n = 0;
$nn = 0;
$sel = 0;
$m = 0;
$A = '';

$val0 = [];
$val1 = [];
$val2 = [];
$val3 = [];
$pict = [];

$ccOld = '0';

//de md-files inlezen en de item-waardes aanpassen t.b.v. sorteren
while($entryName = readdir($myDirectory)) {
    if (substr("$entryName", 0, 1) != ".") {
        $ext = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));
        if ( $ext == 'md' ) {                                                                  // continue only if this is a md file
            $filename = $SubDir . $entryName;

            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));

            fclose($handle);

            $cc = json_decode($contents, true);

            $n = intval($cc['item']);
            if (($cc['selected'] < '0') || ($cc['selected'] > strval($selMax - 1))) {
                $cc['selected'] = $ccOld;
            } else {
                $ccOld = $cc['selected'];
            }
            $sel = intval($cc['selected']);

// breng een duidelijke scheiding aam op het doelgebied, maar kleiner dan de moot-scheiding

            $nn = $n + $sel * 4000;

            array_push($data, $cc);
            array_push($val0, $nn);

            $count++;
        }
    }
}
closedir($myDirectory);

//De elementen van data worden nu gesorteerd, het resultaat komt in $Data1.
//Bij de sortering word de hoge gewichtsfactor van <selectie> (gezet op 4000) meegenomen,
//maar is niet zichtbaar in het eindresultaat. De item-nummers zijn opeenvolgend.
$val1 = $val0;
for ($n = 0; $n < $count; $n++) {
    $val2[$n] = $n;
}

//sorteren ($val1), en bijhouden waar het oorspronkelijke object stond ($val2)
if ($count > 0) {
    $n = 0;
    while ($n < ($count - 1)) {
        if ($val1[$n] > $val1[$n + 1]) {
            $A = $val1[$n];
            $val1[$n] = $val1[$n + 1];
            $val1[$n + 1] = $A;
            $m = $val2[$n];
            $val2[$n] = $val2[$n + 1];
            $val2[$n + 1] = $m;
            $n = -1;
        }
        $n++;
    }
}

//de data in de juiste volgorde op de array pushen met de juiste (nieuwe) item-waarde
for ($n = 0; $n < $count; $n++) {
    $el = $data[$val2[$n]];
    $el['item'] = strval($n);
    array_push($data1, $el);
}

//de md-files updaten
for ($n = 0; $n < $count; $n++) {
    $path_parts = pathinfo($SubDir . $data1[$n]['pictname']);
    $filename = $path_parts['dirname'] . '/' . $path_parts['filename'] . '.md';
    $handle = fopen($filename, "w");
    $txtJson = json_encode($data1[$n]);
    fwrite($handle, $txtJson);
    fclose($handle);
    chmod($filename, 0777);
}

//maak de files aan voor de verschillende selecties
$infoTot = [];
for ($sel = 0; $sel < $selMax; $sel++) {
    $inhoud = [];
    $item = [];
    $pictName = [];
    $selected = [];
    $title1 = [];
    $title2 = [];
    for ($n = 0; $n < $count; $n++) {
        $A = $data1[$n]['selected'];
        if ( intval($A == $sel)) {
            array_push($pictName, $data1[$n]['pictname']);
            array_push($inhoud, $data1[$n]['inhoud']);
            array_push($item, $data1[$n]['item']);
            array_push($selected, $data1[$n]['selected']);
            array_push($title1, $data1[$n]['title1']);
            array_push($title2, $data1[$n]['title2']);
        }
    }
    $ext = (object) ['inhoud' => $inhoud, 'item' => $item, 'pictName' => $pictName, 'selected' => $selected, 'title1' => $title1, 'title2' => $title2];
    array_push($infoTot, $ext);
}

// maak een laatste file aan voor alle selectiesfor ($sel = 0; $sel < $selMax; $sel++) {
$inhoud = [];
$item = [];
$pictName = [];
$selected = [];
$title1 = [];
$title2 = [];
for ($n = 0; $n < $count; $n++) {
    array_push($pictName, $data1[$n]['pictname']);
    array_push($inhoud, $data1[$n]['inhoud']);
    array_push($item, $data1[$n]['item']);
    array_push($selected, $data1[$n]['selected']);
    array_push($title1, $data1[$n]['title1']);
    array_push($title2, $data1[$n]['title2']);
}
$ext = (object) ['inhoud' => $inhoud, 'item' => $item, 'pictName' => $pictName, 'selected' => $selected, 'title1' => $title1, 'title2' => $title2];
array_push($infoTot, $ext);

$res = json_encode($infoTot);

echo $res;
return;
