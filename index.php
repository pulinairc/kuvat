<!DOCTYPE html>

<meta charset="utf-8" />

<title>Random IRC Pics | www.pulina.fi</title>

<link rel="icon" href="urls.png" type="image/png" />
<link rel="shortcut icon" href="urls.png" type="image/x-icon" />
<link rel="bookmark icon" href="urls.png" type="image/x-icon" />
<link rel="stylesheet" href="newlinks.css" type="text/css" /> 
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="linkit.url.preview.js"></script>

<script>
$(document).ready(function() {
$('.refresh').click(function() {
    location.reload();
});
});
</script>

</head>
<body>
<div id="wrapper">
<?php
include('lataa_ircpic123.php');

function square_crop($src_image, $dest_image, $thumb_size = 150, $jpg_quality = 90) {
 
    // Get dimensions of existing image
    $image = getimagesize($src_image);
 
    // Check for valid dimensions
    if( $image[0] <= 0 || $image[1] <= 0 ) return false;
 
    // Determine format from MIME-Type
    $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));
 
    // Import image
    switch( $image['format'] ) {
        case 'jpg':
        case 'jpeg':
            $image_data = imagecreatefromjpeg($src_image);
        break;
        case 'png':
            $image_data = imagecreatefrompng($src_image);
        break;
        case 'gif':
            $image_data = imagecreatefromgif($src_image);
        break;
        default:
            // Unsupported format
            return false;
        break;
    }
 
    // Verify import
    if( $image_data == false ) return false;
 
    // Calculate measurements
    if( $image[0] & $image[1] ) {
        // For landscape images
        $x_offset = ($image[0] - $image[1]) / 2;
        $y_offset = 0;
        $square_size = $image[0] - ($x_offset * 2);
    } else {
        // For portrait and square images
        $x_offset = 0;
        $y_offset = ($image[1] - $image[0]) / 2;
        $square_size = $image[1] - ($y_offset * 2);
    }
 
    // Resize and crop
    $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
    if( imagecopyresampled(
        $canvas,
        $image_data,
        0,
        0,
        $x_offset,
        $y_offset,
        $thumb_size,
        $thumb_size,
        $square_size,
        $square_size
    )) {
 
        // Create thumbnail
        switch( strtolower(preg_replace('/^.*\./', '', $dest_image)) ) {
            case 'jpg':
            case 'jpeg':
                return imagejpeg($canvas, $dest_image, $jpg_quality);
            break;
            case 'png':
                return imagepng($canvas, $dest_image);
            break;
            case 'gif':
                return imagegif($canvas, $dest_image);
            break;
            default:
                // Unsupported format
                return false;
            break;
        }
 
    } else {
        return false;
    }
 
}


$path = "../ircpics/";
$filter = array(".", "..", "random_irc_pics.php");
$max_images = 20;

############################################################

$files = array();
$dir = opendir($path);
while (($file = readdir($dir)) !== false) {
    if (!in_array($file, $filter)) {
        $files[] = $file;
    }
}

for ($i = 0; $i < $max_images; $i++) {

$rand = rand(0, count($files) -1);

$image = $path .''. $files[$rand];
$thumb = $path .'thumbs/'.$files[$rand];

if (!file_exists($thumb)) {
square_crop($image, $thumb);
}

$gd = @imagecreatefromstring(file_get_contents($thumb));  
$gd2 = @imagecreatefromstring(file_get_contents($image));  
if ($gd === false or $gd2 === false) {
echo '<div class="imgbox"><img src="missing_image.png" alt="Missing image" class="missing" /></div>';
} else {
echo '<div class="imgbox"><a class="screenshot" rel="'. $image .'" href="'. $image .'"><img src="'. $thumb .'" alt="Image" /></a></div>'. "\n";
}

}

?>
</div>

<footer>
<a href="#" class="refresh"><img src="f5.png" alt="f5" title="Lataa uudet kuvat" /></a>
Random IRC Pics by <b>rolle</b> @ quakenet ~ <b><?php echo count($files); ?></b> files ~ <a href="https://www.pulina.fi">www.pulina.fi</a></footer>

</body>
</html>
