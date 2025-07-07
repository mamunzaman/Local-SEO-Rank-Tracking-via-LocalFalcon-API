<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include('ScreenshotMachine.php');

$customer_key = "34aa8b";
$secret_phrase = ""; //leave secret phrase empty, if not needed

$machine = new ScreenshotMachine($customer_key, $secret_phrase);

//mandatory parameter
$options['url'] = "https://itconsultingfirma.com/maptest/index.php";

// all next parameters are optional, see our website screenshot API guide for more details
$options['dimension'] = "800x700";  // or "1366xfull" for full length screenshot
$options['device'] = "desktop";
$options['format'] = "png";
$options['cacheLimit'] = "0";
$options['delay'] = "400";
$options['zoom'] = "100";
//$options['click'] = "click%3D.button-close";

$api_url = $machine->generate_screenshot_api_url($options);

//put link to your html code
echo '<img src="' . $api_url . '">' . PHP_EOL;

//or save screenshot as an image
$output_file = 'output.png';
file_put_contents($output_file, file_get_contents($api_url));
echo 'Screenshot saved as ' . $output_file . PHP_EOL;
?>