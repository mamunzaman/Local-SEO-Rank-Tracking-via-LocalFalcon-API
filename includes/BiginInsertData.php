<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//include 'global.data.php';
class BiginInsertData{
    private $userData;

    private $companyData;
    //private $biginAccessSettings = $_SERVER['DOCUMENT_ROOT'];
    private $accessFileName = "/accessTokenPerHour.json";

    public $currentComanyId;

    public $currentuserData;
    public $biginResponseStatus = NULL;

    private $biginContactResponseStatus = NULL;
    //$_SERVER['SERVER_NAME']. pathinfo($_SERVER['PHP_SELF'], 1)
    private $requestedFalconImageBaseUrl = 'https://itconsultingfirma.com/maptest';
    //private $requestedFalconImageBaseUrl = $_SERVER['SERVER_NAME']. pathinfo($_SERVER['PHP_SELF'], 1);

    public function biginInsertDataAPI($requestedData, $requestMode, $extraData=null){

        $accessFileUrl = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . "/bigin";
        $filePath = $accessFileUrl;
        $existingData = file_get_contents($filePath . $this->accessFileName);
        $existingData = json_decode($existingData);

        //echo 'Before: <pre>';
        //print_r($requestedData);
        //echo '<pre>';

        $curl = curl_init();

        /*********
         * REST API CURL CALL TO CREATE COMAPANY INFORMATION
         */
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.zohoapis.eu/bigin/v2/'.$requestMode,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $requestedData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Authorization:  Zoho-oauthtoken '. $existingData->access_token,
                'Content-type: applocation/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        $respond_information = json_decode($response);


        /*echo 'Before: <pre>';
        print_r($respond_information);
        echo '<pre>';*/
        Logger::log("BIGIN Return data (MODE): " . $requestMode . " < - > " .print_r($respond_information, true));

        $companyStatusApi = $respond_information->data[0]->code;

        switch($requestMode){
            case "Accounts":
                if(strtoupper($companyStatusApi) == "SUCCESS"){
                    $biginResponseStatus = "SUCCESS";
                    $this->biginResponseStatus = $biginResponseStatus;
                    //$get_company_id = $respond_information->data[0]->details->duplicate_record->owner->id;
                    $get_company_id = $respond_information->data[0]->details->id;

                    Logger::log("BIGIN COMPANY ACCOUNT DATA INSERTED: " .$get_company_id);
                    //$get_company_id = $respond_information['data'][0]['details']['id'];
                }else if(strtoupper($companyStatusApi) == "DUPLICATE_DATA"){
                    $biginResponseStatus = "DUPLICATE_DATA";
                    $this->biginResponseStatus = $biginResponseStatus;
                    //$get_company_id = $respond_information['data'][0]['details']['duplicate_record']['id'];
                    $get_company_id = $respond_information->data[0]->details->duplicate_record->id;
                    Logger::log("BIGIN DUPLICATE ACCOUNT DATA FOUND: " .$get_company_id);
                }
                $this->currentComanyId = $get_company_id;
                break;

            case "Contacts":
                if(strtoupper($companyStatusApi) == "SUCCESS"){
                    $biginResponseStatus = "SUCCESS";
                    $this->biginResponseStatus = $biginResponseStatus;
                    //$get_company_id = $respond_information->data[0]->details->duplicate_record->owner->id;
                    $get_company_id = $respond_information->data[0]->details->id;
                    $this->currentComanyId = $get_company_id;
                    Logger::log("BIGIN DATA INSERTED: " .$get_company_id);
                    //$get_company_id = $respond_information['data'][0]['details']['id'];
                }else if(strtoupper($companyStatusApi) == "DUPLICATE_DATA"){
                    $biginResponseStatus = "DUPLICATE_DATA";
                    $this->biginResponseStatus = $biginResponseStatus;
                    $get_company_id = $respond_information->data[0]->details->duplicate_record->id;
                    $this->currentComanyId = $get_company_id;
                    Logger::log("DUPLICATE DATA FOUND in BIGIN CRM: ");
                    //$get_company_id = $respond_information['data'][0]['details']['duplicate_record']['id'];
                    //$get_company_id = $respond_information->data[0]->details->duplicate_record->id;
                }
                //$this->currentComanyId = $get_company_id;
                break;

            default:
                break;
        }

        return $this->biginResponseStatus;

    }



    public function biginGetDataAPI($accountId, $requestMode, $requestType=NULL){

        $accessFileUrl = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . "/bigin";
        $filePath = $accessFileUrl;
        $existingData = file_get_contents($filePath . $this->accessFileName);
        $existingData = json_decode($existingData);

        //echo 'Before: <pre>';
        //print_r($requestedData);
        //echo '<pre>';

        $curl = curl_init();

        /*********
         * REST API CURL CALL TO CREATE COMAPANY INFORMATION
         */
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.zohoapis.eu/bigin/v2/".$requestMode,
            //CURLOPT_POST => 1,
            //CURLOPT_POSTFIELDS => $requestedData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $requestType,
            CURLOPT_HTTPHEADER => array(
                'Authorization:  Zoho-oauthtoken '. $existingData->access_token,
                'Content-type: applocation/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        $respond_information = json_decode($response);


        echo 'Before: <pre>';
        print_r($respond_information);
        echo '<pre>';

        $companyStatusApi = $respond_information->data[0]->code;

        switch($requestMode){
            case "Accounts":
                if(strtoupper($companyStatusApi) == "SUCCESS"){
                    $biginResponseStatus = "SUCCESS";
                    $this->biginResponseStatus = $biginResponseStatus;
                    //$get_company_id = $respond_information->data[0]->details->duplicate_record->owner->id;
                    $get_company_id = $respond_information->data[0]->details->id;

                    Logger::log("BIGIN COMPANY ACCOUNT DATA INSERTED: " .$get_company_id);
                    //$get_company_id = $respond_information['data'][0]['details']['id'];
                }else if(strtoupper($companyStatusApi) == "DUPLICATE_DATA"){
                    $biginResponseStatus = "DUPLICATE_DATA";
                    $this->biginResponseStatus = $biginResponseStatus;
                    //$get_company_id = $respond_information['data'][0]['details']['duplicate_record']['id'];
                    $get_company_id = $respond_information->data[0]->details->duplicate_record->id;
                    Logger::log("BIGIN DUPLICATE ACCOUNT DATA FOUND: " .$get_company_id);
                }
                $this->currentComanyId = $get_company_id;
                break;

            case "Contacts":
                if(strtoupper($companyStatusApi) == "SUCCESS"){
                    $biginResponseStatus = "SUCCESS";
                    $this->biginResponseStatus = $biginResponseStatus;
                    //$get_company_id = $respond_information->data[0]->details->duplicate_record->owner->id;
                    $get_company_id = $respond_information->data[0]->details->id;
                    $this->currentComanyId = $get_company_id;
                    Logger::log("BIGIN DATA INSERTED: " .$get_company_id);
                    //$get_company_id = $respond_information['data'][0]['details']['id'];
                }else if(strtoupper($companyStatusApi) == "DUPLICATE_DATA"){
                    $biginResponseStatus = "DUPLICATE_DATA";
                    $this->biginResponseStatus = $biginResponseStatus;
                    $get_company_id = $respond_information->data[0]->details->duplicate_record->id;
                    $this->currentComanyId = $get_company_id;
                    Logger::log("DUPLICATE DATA FOUND in BIGIN CRM: ");
                    //$get_company_id = $respond_information['data'][0]['details']['duplicate_record']['id'];
                    //$get_company_id = $respond_information->data[0]->details->duplicate_record->id;
                }
                //$this->currentComanyId = $get_company_id;
                break;

            default:
                break;
        }

        return $this->biginResponseStatus;

    }


    public function biginInsertCompanyStructure($requestedData){

        $convertedObjectData = json_decode(json_encode($requestedData), false);

        /*echo '<pre>';
        print_r($convertedObjectData);
        echo '</pre>';*/


        $requestedPersonalInfo = unserialize($convertedObjectData[0]->user_info);

        $convertedObjectData = json_decode(json_encode($requestedPersonalInfo));

        /*echo '<pre>';
        print_r($requestedPersonalInfo);
        echo '</pre>';*/


        $post_data_company = [
            'data' =>[
                [
                    "Account_Name"          => $convertedObjectData->mm_google_company_name,
                    "Phone"                 => $convertedObjectData->mm_phone_no,
                    "Website"               => $convertedObjectData->mm_web_url,
                    "Tag" =>[
                        [
                            "name"          => "GMB"
                        ],
                        [
                            "name"      => "Test"
                        ]
                    ],
                    "Billing_Street"        => $convertedObjectData->bigin_street_address,
                    "Billing_City"          => $convertedObjectData->bigin_city,
                    "Billing_State"         => $convertedObjectData->bigin_state,
                    "Billing_Country"       => $convertedObjectData->bigin_country,
                    "Billing_Zip"           => $convertedObjectData->bigin_postal_code,
                ]
            ]
        ];

        $this->companyData = json_encode($post_data_company);
        return $this->companyData;
        //print_r($convertedObjectData);
    }

    public function biginInsertUserStructure($requestedData, $requestedImageFileName){

        $convertedObjectData = (object) $requestedData;

        //print_r($convertedObjectData);
        //echo $convertedObjectData->mm_nachname;
        //die();
        $post_data = [
            'data' => [
                [
                    "Last_Name"         => $convertedObjectData->mm_nachname,
                    "First_Name"        => "",
                    "Email"             => $convertedObjectData->mm_email,
                    "Email_Opt_Out"     => true,  // true or false
                    "Account_Name"      =>  [
                        "id" => $this->currentComanyId
                    ],
                    "Phone"             =>  $convertedObjectData->mm_phone_no,
                    "Tag" => [
                        [
                            "name"      => "GMB"
                        ],
                        [
                            "name"      => "Test"
                        ]
                    ],
                    "Mailing_Street"        => $convertedObjectData->bigin_street_address,
                    "Mailing_City"          => $convertedObjectData->bigin_city,
                    "Mailing_State"         => $convertedObjectData->bigin_state,
                    "Mailing_Country"       => $convertedObjectData->bigin_country,
                    "Mailing_Zip"           => $convertedObjectData->bigin_postal_code,
                    //"Requested_Image_Url"   => $falcon_data['image'],
                    "Keyword_GMB"              => $convertedObjectData->mm_keyword,
                    "Requested_Image_Link"  =>  $this->requestedFalconImageBaseUrl. DIRECTORY_SEPARATOR .
                        $requestedImageFileName
                ],
                [
                    "Account_Name"      =>  $convertedObjectData->mm_google_company_name,
                ]
            ]
        ];

        //print_r($post_data);


        $this->currentuserData = json_encode($post_data);
        return $this->currentuserData;
        //print_r($requestedAllData);
    }

}