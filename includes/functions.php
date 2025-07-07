<?php

if (!function_exists('base_url')) {
    function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), -1, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];

            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf( $tmplt, $http, $hostname, $end );
        }
        else $base_url = 'http://localhost/';

        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }

        return $base_url;
    }
}


function url(){
    return sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['REQUEST_URI']
    );
}

/**
 * Method to find the distance between 2 locations from its coordinates.
 *
 * @param latitude1 LAT from point A
 * @param longitude1 LNG from point A
 * @param latitude2 LAT from point A
 * @param longitude2 LNG from point A
 *
 * @return Float Distance in Kilometers.
 */
/*function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Mi')
{
    $theta = $longitude1 - $longitude2;
    $distance = sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta));

    $distance = acos($distance);
    $distance = rad2deg($distance);
    $distance = $distance * 60 * 1.1515;

    switch ($unit) {
        case 'Mi':
            break;
        case 'Km' :
            $distance = $distance * 1.609344;
    }

    return (round($distance, 2));
}*/


class RandomNumberGeneratorClass {

    public static function generate() {
        // Get current time as a seed
        $seed = microtime();

        // Set the seed for random number generator
        //srand($seed);

        // Define characters to be used
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';

        // Initialize an empty string to store the random characters
        $randomString = '';

        // Generate 6 random characters
        for ($i = 0; $i < 6; $i++) {
            // Get a random index from the characters string
            $randomIndex = rand(0, strlen($characters) - 1);

            // Append the character at the random index to the random string
            $randomString .= $characters[$randomIndex];
        }

        // Return the random string
        return $randomString;
    }
}



class FileHandler {
    private $folderPath;
    private $fileName;

    public function __construct($folderPath, $fileName) {
        $this->folderPath = $folderPath;
        $this->fileName = $fileName;
    }

    public function checkAndCreateFile() {
        // Check if the folder exists, if not create it
        if (!file_exists($this->folderPath)) {
            mkdir($this->folderPath, 0777, true); // create folder recursively with full permissions
        }

        // Check if the file exists, if not create it
        $filePath = $this->folderPath . DIRECTORY_SEPARATOR . $this->fileName;
        if (!file_exists($filePath)) {
            $file = fopen($filePath, 'w'); // create file
            fclose($file);
            return true; // file created successfully
        } else {
            return false; // file already exists
        }
    }

    public function checkOnlyFolderFile(){


        $fileData = '';

        if (file_exists($this->folderPath)) {

            $filePath = $this->folderPath . DIRECTORY_SEPARATOR . $this->fileName;
            if (file_exists($filePath)) {
                $fileData = file_get_contents($filePath);
                //fclose($file);
                //return true; // file created successfully
            } else {
                //return false; // file already exists
            }
        }

        return $fileData;
    }
}


// Function to add watermark to an image
function addWatermark($originalImage, $watermarkImage, $outputImage) {
    $image_status = true;
    // Load the original image
    $image = imagecreatefrompng($originalImage);

    // Load the watermark image
    $watermark = imagecreatefrompng($watermarkImage);

    // Get the dimensions of the watermark image
    $watermarkWidth = imagesx($watermark);
    $watermarkHeight = imagesy($watermark);

    // Get the dimensions of the original image
    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);

    // Calculate the position to place the watermark (e.g., bottom right corner)
    $padding = 10; // Padding from the edges
    $positionX = $imageWidth - $watermarkWidth - $padding;
    $positionY = $imageHeight - $watermarkHeight - $padding;

    // Apply the watermark to the original image
    imagecopy($image, $watermark, $positionX, $positionY, 0, 0, $watermarkWidth, $watermarkHeight);

    // Save the modified image
    //imagejpeg($image, $outputImage);

    // Attempt to create a JPEG file
    if (imagejpeg($image, $outputImage)) {
        $image_status = true;
    } else {
        $image_status = false;
    }

    // Free up memory
    imagedestroy($image);
    imagedestroy($watermark);

    return $image_status;
}




function mm_dropdonw_select(){
    $get_html_language = getBrowserLanguage();
    $dropdown_select = mmItTranslate('dropdown_select', $get_html_language);

    $htmlReturn = '';
    $dropdown_select = explode(',', $dropdown_select);
    $z=0;
    foreach($dropdown_select as $singSelect):
        if($z==0){
            $htmlReturn .= '<option value="0">'.$singSelect.'</option>';
        }else{
            $htmlReturn .= '<option value="'.$singSelect.'">'.$singSelect.'</option>';
        }

    $z++;
    endforeach;

    return $htmlReturn;
}