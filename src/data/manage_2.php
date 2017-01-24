<?php
//manage_2
header("Access-Control-Allow-Origin: *");

//lees de gegevens in uit het kladblok

$NowDir = getcwd();
$del = $NowDir . '/kladblok.md';

$handle = fopen($del, 'r');
$contents = json_decode(fread($handle, filesize($del)));
fclose($handle);

$raws = $contents-> pictFiles;
$lengte = intval($contents-> lengte);
$lengteStr = strval($lengte);
$counterValue = intval($contents-> counter);
$counterValueStr = strval($counterValue);

//________________________________________________________________________________________________
//________________________________________________________________________________________________
$dir1 = 'raw';
$dir2 = 'info';
$dir3 = 'restrict';
$dir4 = 'thumb';
$restrictSize = '640';
$thumbSize = '120';

$filename = $raws[$counterValue];
$fileId = $counterValueStr;
$length = $lengteStr;

$pathToImages = $NowDir . '/' . $dir1 . '/';
$pathToRestrict = $NowDir . '/' . $dir3 . '/';
$pathToThumb = $NowDir . '/' . $dir4 . '/';

$fileLarge = $pathToImages . $filename . '.jpg';
$fileThumb = $pathToThumb . $filename . '.jpg';
$fileRestrict = $pathToRestrict . $filename . '.jpg';

//________________________________________________________________________________________________
$img = imagecreatefromjpeg( $fileLarge );                                                       // load image and get image size

if (!$img) {                                                                                    // beeld laat zich niet verkleinen, gooi het weg
    unlink($fileLarge);
    unlink($fileInfo);
}
    $width = imagesx( $img );                                                                       // start met de oude waarden
    $height = imagesy( $img );

    $new_height = $thumbSize;                                                                       // calculate thumbnail size
    $new_width = floor( $width * ( $thumbSize / $height ) );
    $tmp_img = imagecreatetruecolor( $new_width, $new_height );                                     // create a new temporary image
    imagecopyresampled( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );     // copy and resize old image into new image
    imagejpeg( $tmp_img, $fileThumb );                                                              // save thumbnail into a file
    imagedestroy($tmp_img);                                                                         // destroy image after use
    chmod($fileThumb, 0777);                                                                        // maak read/write

    $new_width = $width;                                                                            // limit the picture size
    $new_height = $height;
    if ( $height > $restrictSize )                                                                  // verklein de beelden, indien nodig
    {
        $new_height = $restrictSize;
        $new_width = floor( $width * ( $restrictSize / $height ) );
    }
    $tmp_img = imagecreatetruecolor( $new_width, $new_height );                                     // create a new temporary image
    imagecopyresampled( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );     // copy and resize old image into new image
    imagejpeg( $tmp_img, $fileRestrict );                                                           // save restricted into a file
    imagedestroy($tmp_img);                                                                         // destroy image after use
    chmod($fileRestrict, 0777);                                                                     // maak read/write

imagedestroy($img);                                                                             // destroy image after use

//________________________________________________________________________________________________
//________________________________________________________________________________________________
if ($counterValue < $lengte) {
//schrijf de gegevens naar het kladblok
    $counterValue++;
    $counterValueStr = strval($counterValue);
    $ext = [$raws[$counterValue - 1], $counterValueStr, $lengteStr];

    $handle = fopen($del, 'w');
    $contents-> pictFiles = $raws;
    $contents-> lengte = $lengteStr;
    $contents-> counter = $counterValueStr;
    $txtJson = json_encode($contents);
    fwrite($handle, $txtJson);
    fclose($handle);
    chmod($del, 0777);

    echo json_encode($ext);
}

return;
//________________________________________________________________________________________________
//________________________________________________________________________________________________
