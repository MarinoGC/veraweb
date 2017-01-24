<?php
header('Access-Control-Allow-Origin: *');
//manage_4

$NowDir = getcwd();
$SubDir = $NowDir . '/info/';
$myDirectory = opendir($SubDir);				                      //lees de file namen in

$selMax = 6;

$count = 0;                                                           //de teller
$data = [];
$data1 = [];
$n = 0;
$m = 0;
$A = '';

$inhoud = [];
$item = [];
$pictName = [];
$selected = [];
$title1 = [];
$title2 = [];

$val0 = [];
$val1 = [];
$val2 = [];
$val3 = [];
$pict = [];

//de md-files inlezen en de item-waardes aanpassen t.b.v. sorteren
while($entryName = readdir($myDirectory)) {
    if (substr("$entryName", 0, 1) != ".") {
        $ext = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));
        if ( $ext == 'md' ) {                                                                  // continue only if this is a md file
            $filename = $SubDir . $entryName;

            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));

            fclose($handle);

            //geef een zwaar accent aan selectie, zodat die altijd achteraan komt
            $n = intval($cc['item']) + 2000 * intval($cc['selected']);
            array_push($val0, $n);

            $cc = json_decode($contents, true);
            array_push($data, $cc);

            $count++;
        }
    }
}
closedir($myDirectory);

//De elementen van data worden nu gesorteerd, het resultaat komt in $Data1.
//Bij de sortering word de hoge gewichtsfactor van <selectie> (gezet op 2000) meegenomen,
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

$res = json_encode($infoTot);

echo $res;
return;