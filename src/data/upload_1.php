<?php
header('Access-Control-Allow-Origin: *');
//upload_1

$path = $_POST['url'];
$tags = $_POST['tags'];
$filename = $_FILES['file']['name'];
$filetype = $_FILES['file']['type'];
$filesize = $_FILES['file']['size'];
$filetmp = $_FILES['file']['tmp_name'];
$fileerror = $_FILES['file']['error'];

$extra = array(
    "name" => $filename,
    "type" => $filetype,
    "size" => $filesize,
    "error" => $fileerror,
    "path" => $path
);

$dir1 = 'raw';
//$dir2 = 'info';
//______________________________________________________________________________________________________________________
$delay = intval($tags[1]);
sleep($delay);

// bepaal het upload-pad, relatief t.o.v. het PHP-pad i.v.m. server onafhankelijkheid
$NowDir = getcwd();

//______________________________check bestaan van info/raw/restrict/thumb directories en zet de chmod op <0755>_________
if (!is_Dir($dir1)) {
    $success = mkdir($dir1, 0777);
};
//______________________________________________________________________________________________________________________
$pathToImages = $NowDir . '/' . $dir1 . '/';
$pathToInfo = $NowDir . '/' . $dir2 . '/';

//WORKAROUND: iPad noemt alle files <image.jpg>.
if (strtolower($filename) == "image.jpg") {             // Test of het de beruchte file naam is
    $filename = $filesize . $filename;                  // Maak de file "uniek" met de file size
}                                                       // Geen datum genomen. EXIF in $filetmp bevat geen creation date meer
// en huidige tijdstip van uploading geeft aanleiding tot dupliceren.
$full_filename = $NowDir . "/raw/" . $filename;

$uploadOk = 1;

// Check if file already exists
if (file_exists($full_filename)) {
    $value = $filename . " already exists";
    $uploadOk = 0;
}

// Check file size
if ($filesize > 5000000) {
    $value = $filename . " is too large (" . $filesize . " > 5000000). ";
    $uploadOk = 0;
}

// Allow certain file formats
$imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
//if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
if($imageFileType != "jpg") {
    $value = $filename . " Sorry, only JPG files are allowed";
    $uploadOk = 0;
}

// uploading
if ($uploadOk == 1) {
    if (move_uploaded_file($filetmp, $full_filename)) {
        $value = $filename . " uploaded";
        $Orentation = 1;
        $exif = exif_read_data($full_filename);
        if(!empty($exif['Orientation'])) {
            $Orientation = $exif['Orientation'];
            adjustPicOrientation($full_filename, $Orientation);
        };

    } else {
        $value = $filename . " error in uploading";
    }
}

echo '===========> zie je me?';
print_r($_FILES);
print "</pre>";

return;

//______________________________________________________________________________________________________________________
//______________________________________________________________________________________________________________________
function _mirrorImage ( $imgsrc) {
    $width = imagesx ( $imgsrc );
    $height = imagesy ( $imgsrc );

    $src_x = $width -1;
    $src_y = 0;
    $src_width = -$width;
    $src_height = $height;

    $imgdest = imagecreatetruecolor ( $width, $height );

    if ( imagecopyresampled ( $imgdest, $imgsrc, 0, 0, $src_x, $src_y, $width, $height, $src_width, $src_height ) )
    {
        return $imgdest;
    }
    return $imgsrc;
}

//______________________________________________________________________________________________________________________
function adjustPicOrientation($full_filename, $Orientation){
    if($Orientation != 1){
        $img = imagecreatefromjpeg($full_filename);
        $mirror = false;
        $deg    = 0;
        switch ($Orientation) {
            case 2:
                $mirror = true;
                break;
            case 3:
                $deg = 180;
                break;
            case 4:
                $deg = 180;
                $mirror = true;
                break;
            case 5:
                $deg = 270;
                $mirror = true;
                break;
            case 6:
                $deg = 270;
                break;
            case 7:
                $deg = 90;
                $mirror = true;
                break;
            case 8:
                $deg = 90;
                break;
        }
        if ($deg) $img = imagerotate($img, $deg, 0);
        if ($mirror) $img = _mirrorImage($img);
        imagejpeg($img, $full_filename, 95);
        imagedestroy($img);
    }
    return $full_filename;
}

?>
