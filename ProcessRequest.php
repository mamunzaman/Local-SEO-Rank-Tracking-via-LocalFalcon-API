<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
*/


include 'includes/LogRecordClass.php';
include 'includes/functions.php';

// Translation access file ......
include 'languages/TranslationTexts.php';
// allow access....
include 'includes/ClassDatabase.php';
//include 'includes/PhantomJsCloudClass.php';
//include 'includes/SendEmail.php';
include 'includes/FalconClass.php';
include 'includes/BiginInsertData.php';
include 'includes/FlaconPlaceSaveClass.php';
include 'includes/CreatePhantomJsonClass.php';

//sleep(2); // Simulate some work being done in the background
Logger::log("Start Logger ProcessRequest.php");




//$userDataID = 267; // param1
//$requestedLang = 'en';
$userDataID = $argv[1]; // param1
$requestedLang = $argv[2]; // param2 - language
$webDomainAddress = $argv[3];   // param3 - domain Info
//$param2 = $argv[2]; // param2

// Final E-Mail Message Translations.
$phone = mmItTranslate('phone', $requestedLang);
$final_thansks = mmItTranslate('final_thansks', $requestedLang);
$final_message = mmItTranslate('final_message', $requestedLang);



$final_mail_subject = mmItTranslate('final_mail_subject', $requestedLang);
$sender_thanks = mmItTranslate('sender_thanks', $requestedLang);
$sender_position = mmItTranslate('sender_position', $requestedLang);
$sender_organization = mmItTranslate('sender_organization', $requestedLang);

$error_not_mail_txt = mmItTranslate('error_not_mail_txt', $requestedLang);



$database_gateway = new DatabaseGateway();

/***************************
 * Find first the data requested with some conditions.
 */
$sql = "SELECT * FROM $database_gateway->tableName WHERE id= :id AND status = :status";

$data = [
    'id'     => $userDataID,
    "status"    => '0',
];

$response = $database_gateway->query($sql, $data);
$count = count((array)$response);

//make StdClass
$responseData = json_decode(json_encode($response,true));

/*echo '<pre>';
print_r($responseData);
echo '</pre>';*/
Logger::log("Generated user falcon file name: " .$responseData[0]->file_name);


if( ($count > 0) && ($database_gateway->error == '')){
//requestProcess.php
    // loop returned StdClass to get the data from database before call the FullGridSearch in localFalcon.
    for($i=0;$i<count($responseData);$i++){
        foreach ($responseData as $respondDataSingle):
            $request_status = $respondDataSingle->status;
        endforeach;
    }

    //die();
    if($request_status==0){
        // FalconClass.php request to search full LocalFalcon data and replace in same JSON file.
        $requestFlaconGrid = new LocalFalconGateWay();
        $requestFlaconGrid = $requestFlaconGrid->falconPlaceFullGridSearch($responseData);
        Logger::log("falconPlaceFullGridSearch() found and genarate data.");
    }else{
        Logger::log("falconPlaceFullGridSearch() if not allowed You can not request more");
    }

    // print_r($requestFlaconGrid);
    /*echo '<pre>';
    print_r(json_decode($requestFlaconGrid));
    echo '</pre>';*/



    /*
    //// this is update the status of your request and stop to request redundent.
    $sql = "update $database_gateway->tableName set
            status = :status
           ";
    $data = [
        'status' => '1'
    ];
    $response = $database_gateway->executeTransaction($sql, $data);


    if (($database_gateway->error == '')  ) {
        echo 'Data been updated';
    }
    */
}else{
    Logger::log("DatabaseGateway() You can not request more 2");
}

//$createJsonMapFile = new FileHandler("flaconrequest", "");


$findTheRequestBasicData = unserialize($responseData[0]->falcon_place_return);

$centerLatPoint = number_format($findTheRequestBasicData->lat,5);
$centerLngPoint = number_format($findTheRequestBasicData->lng,5);
/*echo '<pre>';
print_r($responseData);
echo '</pre>';
*/
Logger::log("Find Requested Basic Data: " . print_r($findTheRequestBasicData, true));
//die();

$makeUniqueObject = new RandomNumberGeneratorClass();


$makeUniqueId = $makeUniqueObject->generate();

// this is for PhantomJS RequestFile for JSON Genaration
$jsonFIleName = "phantom_" .  $makeUniqueId . "_" .$responseData[0]->file_name;
$tokenId = $responseData[0]->token;

// Example usage:
$base_directory = 'phantom_request';
$file_manager = new JSONFileManager($base_directory,$jsonFIleName);


$getDatSetUrlObject = new LocalFalconGateWay();
$dataSetUrl = $getDatSetUrlObject->dataSetUrl;

Logger::log("Find Requested Basic Data: " . print_r($responseData, true));
//die();
$setQueryRequestForPhantom =
    "path=" . $dataSetUrl . "&falcon_file_name=". $responseData[0]->file_name.
    "&lat=" .$centerLatPoint . "&lng=" . $centerLngPoint . "&requested_json=" . $jsonFIleName;


$data = [
    "url" => $webDomainAddress . "genarate_map.php/?".$setQueryRequestForPhantom,
    "clearCache" => true,
    "renderType" => "png",
    "zoomFactor" => "1",
    "resourceWait" => 15000,
    "resourceTimeout" => 35000,
    "maxWait" => 35000,
    "ioWait" => 2000,
    "waitInterval" => 1000,
    "overseerScript" => "await page.waitForNavigation('domcontentloaded'); page.done()",
    "renderSettings"    => [
        "quality"=> 100,
        "viewport"=>[
            "height"=> 670,
            "width"=> 640
        ]
    ]
];


if ($file_manager->saveDataIfNotExists($data)) {
    echo "Data saved successfully.\n";
    echo $file_manager->file_name;
} else {
    echo "Data already exists.\n";
    echo $file_manager->file_name;
}

Logger::log("Find Requested Basic Data: " . print_r($data, true));
//die();


$requestedPhantonJsonFileName = $file_manager->file_name;
$webAPIUrl = "https://PhantomJScloud.com/api/browser/v2";
$phantomAPIKey = "ak-9dggr-pkw02-5j9kq-mjyt5-ees5t";
$url = $webAPIUrl . DIRECTORY_SEPARATOR . $phantomAPIKey. DIRECTORY_SEPARATOR;
echo $base_directory . DIRECTORY_SEPARATOR . $requestedPhantonJsonFileName;
//die();
// vcall the JSON data file
$payload = file_get_contents ( $base_directory . DIRECTORY_SEPARATOR . $requestedPhantonJsonFileName );
Logger::log("PHANTOMJS JSON REQUEST URL: " .$base_directory . DIRECTORY_SEPARATOR . $requestedPhantonJsonFileName);
$options = array(
    'http' => array(
        'header'  => "Content-type: application/json;charset=UTF-8\r\n",
        'method'  => 'POST',
        'content' => $payload
    )
);


/******
 * remove regular expression
 */
$file_name1 = $file_manager->file_name;
$file_name_without_extension = preg_replace('/\.json$/', '', $file_name1) . '.png';
//$file_name_without_extension; // Output: example



$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { /* Handle error */ }
file_put_contents("generated_image". DIRECTORY_SEPARATOR .$file_name_without_extension ,$result);


/*********
 * Extra add for watermark and delete main generated PhantomJS Image.
 */

$originalImage = "generated_image". DIRECTORY_SEPARATOR . $file_name_without_extension;
$watermarkImage =  $webDomainAddress . 'assets/images/watermark-logo.png';
$outputImage = $file_name_without_extension;

$statusWaterMark = addWatermark($originalImage, $watermarkImage, $outputImage);
Logger::log("Generated Image has been deleted successfully: " .$statusWaterMark);

if($statusWaterMark){
    $deletePhantomJS = $file_name_without_extension;
    // Check if the file exists before attempting to delete it
    if (file_exists($deletePhantomJS)) {
        // Attempt to delete the file
        if (unlink($deletePhantomJS)) {
            Logger::log("Generated Image has been deleted successfully: " .$statusWaterMark);
        } else {
            Logger::log("Generated Image did not delete: " .$statusWaterMark);
        }
    } else {
        Logger::log("Image did not exist: " .$statusWaterMark);
    }

}

/*********** ends watermark and PhantomJS genareted Image Delete. **********/




/***************************
 * Find first the data requested with some conditions.
 */
$sql = "SELECT * FROM $database_gateway->tableName WHERE id= :id AND status = :status";

$data = [
    'id'     => $userDataID,
    "status"    => '0',
];

$response = $database_gateway->query($sql, $data);
$count = count((array)$response);

//make StdClass
$responseData = json_decode(json_encode($response,true));

$logMessageArray = print_r($responseData, true);

Logger::log("Again check if requested user exists: " .$logMessageArray);


if( ($count > 0) && ($database_gateway->error == '')){

    //$genaratedImageNamePhantomJS = $responseData[0]->genareted_image;
    $genaratedImageNamePhantomJS = "generated_image". DIRECTORY_SEPARATOR .$file_name_without_extension;

    $sql = "update $database_gateway->tableName set
            genareted_image = :genareted_image 
            where id = :id
           ";
    $data = [
        'genareted_image' => $genaratedImageNamePhantomJS,  // came from
        // upper area where generate image as PNG from PhantomJS
        'id'  => $userDataID,
    ];
    $response = $database_gateway->executeTransaction($sql, $data);

    if ($database_gateway->error == '' ) {
        Logger::log("Update the genarated Image column after PhantomJS execution: " .$genaratedImageNamePhantomJS);
    }


}



//$folder_path = dirname($_SERVER['REQUEST_URI']);

?>


<?php

// this check if user updated the generatedPhoto from PhantomJS
if ($database_gateway->error == '' ) {

    $userRequestedData = unserialize($responseData[0]->user_info);

    //print_r($userRequestedData);
    $requestedAddress = $userRequestedData['mm_autocomplete'];
    $mm_email = $userRequestedData['mm_email'];
    $mm_keyword = $userRequestedData['mm_keyword'];
    $mm_web_url = $userRequestedData['mm_web_url'];
    $mm_nachname = $userRequestedData['mm_nachname'];



    // this is after search in LocalFalcon API
    include 'SendResultEmailSearchResultSMTP.php';

    $mail->isHTML(true);                                  // Set email format to HTML

    /*$mail->AddEmbeddedImage('https://itconsultingfirma.com/maptest/generated_image/'
        .$responseData[0]->genareted_image, 'localfalcon_ranking_info');*/
    $mail->Subject = $final_mail_subject;
    $mail->AddEmbeddedImage(dirname(__FILE__).'/generated_image/'.$file_name_without_extension, "my-attach-falcon", "localfalcon_img.png");
    $mail->Body    = $final_thansks. '&nbsp;' . $mm_nachname . "
                <br/><br/>".$final_thansks;
    $mail->Body    .= "<br/><br/>
                    ".$final_message."<br/> <br/> 
                    <strong>Address:</strong> $requestedAddress<br/>
                    <strong>Keyword search:</strong> $mm_keyword <br/>
                    ----------------------------------------------------------------------------------
                    <br/>
                    <img alt='Ranking Data Visualization' src='cid:my-attach-falcon' width='300px'>
                    <br/>
                    ----------------------------------------------------------------------------------
                    <br/><br/><br/> 
                    ".$sender_thanks.",<br/><br/>  
                    <b>Md Mamunzzaman</b><br/>
                    ".$sender_position."<br/>
                    ".$sender_organization."<br/>
                    ".$phone."+49 152 028 15822<br/>
                    it.consulting.mamun@web.de";


    $output = '';
    if(!$mail->send()) {
        $output = '';
        $output .= $error_not_mail_txt . $mail->ErrorInfo;
    } else {
        //$output = 'Message has been sent';

        $sql = "update $database_gateway->tableName set
            status = :status WHERE id= :id
           ";
        $data = [
            'id'    => $responseData[0]->id,
            'status' => '1'
        ];
        $response = $database_gateway->executeTransaction($sql, $data);
        Logger::log("Updated Status");

        Logger::log("Message has been sent");



        $filePath_check = $genaratedImageNamePhantomJS;
        $foundFileStatus = false;
        if (file_exists($filePath_check)) {
            //echo "File exists!";
            $foundFileStatus = true;
            Logger::log("File exists!: FOUND FILE: ". $foundFileStatus);
        } else {
            $foundFileStatus = false;
            Logger::log("File exists!: NOT FOUND FILE: ". $foundFileStatus);
            //echo "File does not exist.";
        }


        if($foundFileStatus){
            //print_r($responseData);
            $requestedPersonalInfo = $userRequestedData;

            $biginDataInsertRequest = new BiginInsertData();

            // this function to set Company Information Structure
            $biginCompanyDataSet = $biginDataInsertRequest->biginInsertCompanyStructure($responseData);

            // Call Bigin API to post comapny Info
            $biginDataInsertRequest->biginInsertDataAPI($biginCompanyDataSet, "Accounts");

           /* echo '<pre>';
            echo $biginDataInsertRequest->biginResponseStatus;
            echo '</pre>';*/
            Logger::log("BIGIN Add Compan Information: " .$biginDataInsertRequest->biginResponseStatus);


            if($biginDataInsertRequest->biginResponseStatus != "DUPLICATE_DATA"){
                echo $biginDataInsertRequest->currentComanyId;
                // structure set of data before API call
                $biginCompanyDataSet = $biginDataInsertRequest->biginInsertUserStructure($requestedPersonalInfo,
                    $genaratedImageNamePhantomJS);

                // After set the user data structure then call API to Insert Contact.
                $currenAddedUserData = $biginDataInsertRequest->biginInsertDataAPI($biginCompanyDataSet,"Contacts",
                    $responseData);
                //echo $biginDataInsertRequest->biginResponseStatus;

                Logger::log("BIGIN Add Contact Information if no Company exists: " .$biginDataInsertRequest->biginResponseStatus);
            }else{
                echo $biginDataInsertRequest->currentComanyId;
                // structure set of data before API call
                $biginCompanyDataSet = $biginDataInsertRequest->biginInsertUserStructure($requestedPersonalInfo,
                    $genaratedImageNamePhantomJS);

                // After set the user data structure then call API to Insert Contact.
                $currenAddedUserData = $biginDataInsertRequest->biginInsertDataAPI($biginCompanyDataSet,"Contacts",
                    $responseData);
                //echo $biginDataInsertRequest->biginResponseStatus;
                Logger::log("BIGIN Add Contact Information if Already Company exists: " .$biginDataInsertRequest->biginResponseStatus);
            }

        }

    }


    /*$sql = "update $database_gateway->tableName set
            status = :status WHERE id= :id
           ";
    $data = [
        'id'    => $responseData[0]->id,
        'status' => '1'
    ];
    $response = $database_gateway->executeTransaction($sql, $data);*/


    if (($database_gateway->error == '')  ) {
        //echo 'Data been updated';
        Logger::log("Database Status updated and changes to 1");
    }


}
//echo biginResponseStatus;
//die();


?>

<?php
//
/**************
 * process to send data to Bigin CRM
 */
//file_put_contents('file.txt', $output);