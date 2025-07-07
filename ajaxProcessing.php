<?php
// avoid direct access
define('AJAX_REQUEST',
    isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
    && $_POST['randomPass']==$_SESSION['randomPass']);
if(!AJAX_REQUEST) {
    die();
}

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//echo __DIR__;
//include 'languages/TranslationTexts.php';

/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
*/




// Translation data ...
include 'languages/TranslationTexts.php';

// allow access....
include 'includes/LogRecordClass.php';
include 'includes/functions.php';
// allow access....
include 'includes/ClassDatabase.php';
//include 'includes/PhantomJsCloudClass.php';
//include 'includes/SendEmail.php';
include 'includes/FalconClass.php';
include 'includes/FlaconPlaceSaveClass.php';
include 'includes/CreatePhantomJsonClass.php';

/***** temp Use ****/
//include 'includes/TestInsertData.php';



// this is dynamic for executions
//exec('/usr/bin/php '.__DIR__.'/process_bg.php');



/***
 *
 *
 * require '../../BackgroundProcess.php';
 *
 * //Please don't use "true" argument in a production, This will fill your storage if you not clean all the logs.
 * $proc = new BackgroundProcess('php ./process.php', true);
 *
 * $pid = $proc->getProcessId();
 *
 * echo $proc->get_log_paths();
 *
 * echo "Process id: " . $pid . "\n";
 *
 *
 */




//die();



    parse_str($_POST['allFormData'], $allArrayOfFormData);

    $dataToSaveDb = array();
    $numItems = count($allArrayOfFormData);
    $x = 0;

/*********
 * set of data to check before request for API
 */
    $tempVal = "";
    $tempEmail = "";

    /************************************/

/**************************
 * make dynamic all data in structure form based on form in dynamic loop
 */
$company_info_status = false;
    foreach ($allArrayOfFormData as $key => $value):
        // this check if it's not the last in array to avoid submit button in array.
        if($key != "mm_google_formatted_address"){

            switch($key){
                case "mm_google_company_name":
                     if(strip_tags($value)){
                         $company_info_status = true;
                     }

                    break;

                default:
                    break;
            }


            if($key === "mm_email"){
                $tempEmail = strip_tags($value);
            }
            if(++$x !== $numItems) {
                $dataToSaveDb += [$key => strip_tags($value)];
                //echo $key;
            }
        }else{
            $tempVal = strip_tags($value);
        }


    endforeach;

    // added extra for later user
    $dataToSaveDb += ["address" => $tempVal ];


/**********
 * Compan Name check and update status.
 */

if(!$company_info_status){
    $jsonDataReturn = array(
        "message"   => "<span class='error-icon-common'></span>$compan_name_error",
        "status"    => false
    );
    echo json_encode($jsonDataReturn);
    die();
}


/************
 * check if this email already added more than 2 times in database
 */
$database_gateway = new DatabaseGateway();
/***************************
 * Find first the data requested with some conditions.
 */
//$sql = "SELECT * FROM $database_gateway->tableName WHERE email= :email AND status = :status";
$sql = "SELECT * FROM $database_gateway->tableName WHERE email= :email";

$data = [
    'email'     => $tempEmail,
];

$response = $database_gateway->query($sql, $data);
$count = count((array)$response);
// this is check if user requested more than 2 time or not.
 if($count >=1){
     $jsonDataReturn = array(
         "message"   => "<span class='error-icon-common'></span>$error_maximum_2",
         "status"    => false
     );
     echo json_encode($jsonDataReturn);
     die();
 }


    //die();
// this is check autocomplete is filled or not.
if($dataToSaveDb['mm_autocomplete']){
    // get requested FalconData from GoogleAddressSearch.
    // [NOTE: AFTER DEVELOPMENT THEN ACTIVATE THE DATA AND THEN IT WILL WORK]
    $returnFlacon = new LocalFalconGateWay();
    $responsPlaceFalconData = $returnFlacon->falconPlaceCurlSearch($dataToSaveDb['mm_autocomplete']);  // return only created file name.
    // created filename to track later to save data in database.
}else{

    $jsonDataReturn = array(
        "message"   => "<span class='error-icon-common'></span>$error_address_problem",
        "status"    => false
    );
    echo json_encode($jsonDataReturn);
    die();
}



    if(!$responsPlaceFalconData['success'])die();


    if($responsPlaceFalconData['success']){
        //print_r($dataToSaveDb);
        $performWork = new FalconPlaceSaveClass($responsPlaceFalconData['unique_id']);
        //print_r($performWork->falconPlaceSave($dataToSaveDb));
        $afterInsertData = $performWork->falconPlaceSave($dataToSaveDb);
    }

    if($afterInsertData){

         $tokenNumber = $performWork->tokenNumber;

         $print_Email_Location = '<a href="'.base_url().
             'optin-confirm.php/?token='.$tokenNumber.'"><strong>'
             .$click_opt_in_text.'</strong></a>';

         // this is after search in LocalFalcon API
        include 'SendEmailSearchResultSMTP.php';

    }



