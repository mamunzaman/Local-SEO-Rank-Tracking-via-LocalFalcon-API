<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class FalconPlaceSaveClass{

    public $returnStatus;

    public $jsonFileName;

    public $tokenNumber;

    function __construct($jsonFileName) {
        $this->jsonFileName = $jsonFileName;
    }
    

    public function generateRandomToken($length = 32) {
        // Generate random bytes
        $token = bin2hex(random_bytes($length / 2));
        return $this->tokenNumber = $token;
    }

    public function falconPlaceSave($userData){

        $returnStatusData = array();
        /* this class is responsible to request and save Place data in JSON format in a specific folder with (unique
        id) to later use and report generation.
        */
        $getFileData = new LocalFalconGateWay();
        // DataSet of data need to use
        $jsonReadPath = $getFileData->dataSetUrl;
        $jsonFileNamePlaceData = $this->jsonFileName; // file name will be dynamic when done then simple use


        
        // that class "$placeData" variable.
        $fileHandler = new FileHandler($jsonReadPath, $jsonFileNamePlaceData);

        // store all saved JSON DATA after retrive from *.json file type.
        $responsPlaceFalconData = $fileHandler->checkOnlyFolderFile();
        $responsPlaceFalconData = json_decode($responsPlaceFalconData);


        // here call the DatabaseClass to store data in Database Table.
        $database_gateway = new DatabaseGateway();
        $list_lat_lng = array();

        // used if more than one search result
        foreach ($responsPlaceFalconData->data->suggestions as $found_address):
            $list_lat_lng[] = array(
                "lat"   => htmlspecialchars(round($found_address->lat,5)),
                'lng'   => htmlspecialchars(round($found_address->lng,5))
            );
        endforeach;
        // simple store received Data From localFalcon
        $requestedData = serialize($responsPlaceFalconData->data->suggestions[0]);
        // simple store received Place_id From localFalcon
        $falcon_place_id = htmlspecialchars($responsPlaceFalconData->data->suggestions[0]->place_id);
        // only returned array of lat lng from Localfalcon
        $listlatlng = serialize($list_lat_lng);
        // search address data auto completed by GoogleMAP
        $autocomplete_address = htmlspecialchars($userData['mm_autocomplete']);

        $database_gateway = new DatabaseGateway();

        $sql = "SELECT * FROM $database_gateway->tableName WHERE email= :email AND count != :count";

        $data = [
            'email'     => htmlspecialchars($userData['mm_email']),
            "count"    => '2'   // this is the limit to request from a single E-Mail.
        ];

        $response = $database_gateway->query($sql, $data);
        $countData = count((array)$response);

        // this is make array to StdObjectClass.
        $response = json_decode(json_encode($response), FALSE);

        $check_if_not_2_email = true;
        $incrementCount =0;
        for($k=0;$k<$countData;$k++){
            if($response[$k]->count > 1){
                $check_if_not_2_email = false;
            }
        }


        if(($check_if_not_2_email== true) && ($countData < 2)){

            $data = [
                "email"                     => htmlspecialchars($userData['mm_email']),
                "user_info"                 => serialize($userData),
                "request_address"              => $autocomplete_address,
                "place_id"                  =>  $falcon_place_id,
                "falcon_place_return"       =>  $requestedData,
                "lat_lng"                   => $listlatlng,
                "file_name"                 => $this->jsonFileName,
                "token"                     => $this->generateRandomToken()
            ];

            $tableName = $database_gateway->tableName;
            $sql = "INSERT INTO $tableName 
                   (
                    email,
                    user_info,
                    request_address,
                    place_id,
                    falcon_place_return,
                    lat_lng,
                    file_name,
                    token
                   )
                   values
                   (
                    :email,
                    :user_info,
                    :request_address,
                    :place_id,
                    :falcon_place_return,
                    :lat_lng,
                    :file_name,
                    :token
                   )";
 

            $database_gateway->executeTransaction($sql, $data);
            if ($database_gateway->error == '') {
                $returnStatusData['database_status'] = 'Information have been updated.';
            } else {
                //return 'Error encountered ' . $database_gateway->error;
                $returnStatusData['database_status'] ='Error encountered ' . $database_gateway->error;
            }
            $returnStatusData['message_status'] = 'If you want you can request later another one.';
        }else{
            $returnStatusData['message_status'] = 'You are not allowed to request more than 2 time with the same E-Mail address.';
        }

        return $this->returnStatus = $returnStatusData;
        //echo $fileHandlerData;
        //eturn $getFileData->placeData;
    }
}