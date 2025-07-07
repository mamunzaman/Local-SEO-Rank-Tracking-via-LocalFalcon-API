<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//defined( 'ABSPATH' ) || exit;
//include 'global.data.php';
//include 'functions.php';
class LocalFalconGateWay{
    public  $error    = '';

    public  $statusMessage    = '';

    public  $result;
    public $search;

    /*****************
     * @var string
     * example for [PLACE]  https://api.localfalcon.com/v1/places/?api_key=fdbb0655203292ae26df32405afd3797&query=
     */
    protected  $basicUrl = "https://api.localfalcon.com/v1";
    protected $api_key = "fdbb0655203292ae26df32405afd3797";
    protected $requestMethod = "places";
    public $placeData = "falconPlace";
    public $fullResutlFalcon = "falconMapData";
    public $dataSetUrl = "dataset";
    public $randomNumber;

    private $falconQuery = array();
    /*private $keywords;
    private $lat;
    private $lng;
    private $grid_size;
    private $radius;
    private $measurement;*/



    public function falconPlaceFinder($search){
        return $this->search = $search;
    }

    public function FalconGridSearchQuery($requestedData){

        foreach ($requestedData as $key => $value):
            // this check if it's not the last in array to avoid submit button in array.
            $this->falconQuery += [$key =>  $value ];
        endforeach;

        return $this->falconQuery;
    }



    public function falconPlaceCurlSearch($search){
        //die();
        $generate_id = RandomNumberGeneratorClass::generate();

        $this->randomNumber = $generate_id;
        // File genaration data to be used.
        $folderPath = $this->dataSetUrl;
        $this->placeData = $fileName = $this->placeData."_".$generate_id.".json";
        $fileHandler = new FileHandler($folderPath, $fileName);

        //$this->falconAPI();
        $encodedUrl = urlencode($search);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->basicUrl . DIRECTORY_SEPARATOR . $this->requestMethod . DIRECTORY_SEPARATOR . '?api_key='. $this->api_key . '&query=' . $encodedUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;
        $response = json_decode($response,true);

        if(!$response['success'])
            return $response;
        //$responsPlaceFalconData = json_decode($response, true);
        //print_r($response);

       //die();



        // this is added extra info to use later in Falcon respond data before save in file.
        $response['unique_id'] = $fileName;

        if ($fileHandler->checkAndCreateFile()) {
            //echo "File created successfully!";
            $returnRespondDataWithFile = json_encode($response, JSON_PRETTY_PRINT);

            $fp = fopen($folderPath."/".$fileName, 'w');
            fwrite($fp, $returnRespondDataWithFile);
            fclose($fp);

            $returnRespondDataWithFile = json_decode($returnRespondDataWithFile,true);

            return $returnRespondDataWithFile;    // return only json file name which is just created.
        } else {
            return false;   // return 0 because file is not exists.
        }



    }


    public function falconPlaceFullGridSearch($databaseRetriveData){

        //print_r($databaseRetriveData);
        //$this->placeData = $respondDataSingle->file_name;
        for($i=0;$i<count($databaseRetriveData);$i++){
            foreach ($databaseRetriveData as $respondDataSingle):

                // this make current filename that been tetrive from request query.
                $this->placeData = $respondDataSingle->file_name;
                $getSearchFileName              = $respondDataSingle->file_name;
                $requested_user_info            = unserialize($respondDataSingle->user_info);   // make StdClass
                $requested_falcon_place_return  = unserialize($respondDataSingle->falcon_place_return); // make StdClass

            endforeach;
        }
        //print_r($requested_user_info);
        // convert StdClass
        $requested_user_info = json_decode(json_encode($requested_user_info,true));

        // StdClass marge / append data
        $requested_user_info->falconDataSet = $requested_falcon_place_return;


        /*
         *
         * Parameters
            api_key
            REQUIRED
            Your Local Falcon API key.
            place_id
            REQUIRED
            The Google Place ID of the business to match against in results.
            keyword
            REQUIRED
            The desired search term or keyword.
            lat
            REQUIRED
            The center point latitude value.
            lng
            REQUIRED
            The center point longitude value.
            grid_size
            REQUIRED
            The size of your desired grid.
            Expects 3, 5, 7, 9, 11, 13, or 15.
            radius
            REQUIRED
            The radius of your grid from center point to outer most north/east/south/west point.
            Expects .1 to 100.
            measurement
            REQUIRED
            The measurement unit of your radius
         *  Expects mi for miles or km for kilometers.
         *
         */

        // falcone DataSet genarate Dynamically
        $returnStatusData = array();
        $falconRequestDataSet = array(
            'method'  => 'scan',
            'place_id'  => $requested_user_info->falconDataSet->place_id,
            'keywords'  => $requested_user_info->mm_keyword,
            'lat'  => $requested_user_info->falconDataSet->lat,
            'lng'  => $requested_user_info->falconDataSet->lng,
            'grid_size'  => 7,
            'radius'  => 10,
            'measurement'  => 'km',
            'file_name'     => $getSearchFileName
        );

        // Query Set Data.
        $genarateGlobalQueryData = $this->FalconGridSearchQuery($falconRequestDataSet);
        
        /*echo '<pre>';
            print_r($genarateGlobalQueryData);
        echo '</pre>';*/



        //echo $this->placeData;

        $currDataSetUrl = $this->dataSetUrl;
        $currPlaceData = $this->placeData;


        $checkFileFolder = new FileHandler($currDataSetUrl,$currPlaceData);
        $ifFileExist = $checkFileFolder->checkOnlyFolderFile();

        if($ifFileExist){
            $statusData = $this->FalconGridSearchRequest($genarateGlobalQueryData);
        }else{
            $statusData = 'Didnt found your request.';
        }


        return $statusData;
    }

    public function FalconGridSearchRequest($querySet){



        $method = $querySet['method'];
        $curr_place_id = $querySet['place_id'];
        $keywords = urlencode($querySet['keywords']);
        $lat = $querySet['lat'];
        $lng = $querySet['lng'];
        $grid_size = $querySet['grid_size'];
        $radius = $querySet['radius'];
        $measurement = $querySet['measurement'];

        // https://api.localfalcon.com/v1/scan/?api_key=fdbb0655203292ae26df32405afd3797&place_id=ChIJq6qqX2wosUcR3TtkEkbRz2c&keyword=exebition&lat=53.0898035&lng=8.8128935&grid_size=5&radius=10&measurement=km
        $encodedUrl = urlencode($keywords);
        $query_url = $this->basicUrl . DIRECTORY_SEPARATOR . $method . DIRECTORY_SEPARATOR . '?api_key='
            .$this->api_key . '&place_id=' . $curr_place_id .'&keyword='. $keywords . '&lat=' . $lat . '&lng='. $lng .
            '&grid_size='. $grid_size . '&radius='. $radius . '&measurement='. $measurement;

        //echo $query_url;
        Logger::log("FULL GRID SEARCH QUERY: " .$query_url);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $query_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);



        //echo $response;
        $response = json_decode($response,true);

        $jsonString = json_encode($response, JSON_PRETTY_PRINT);


        $currDataSetUrl = $this->dataSetUrl;
        $currPlaceData = $this->placeData;

        //echo $currDataSetUrl. DIRECTORY_SEPARATOR.$currPlaceData;
        //$fileData = file_get_contents($currDataSetUrl. DIRECTORY_SEPARATOR.$currPlaceData);
        //Logger::log("Before put FULL GRID SEARCH result " .$query_url);
        $fileData = file_put_contents($currDataSetUrl. DIRECTORY_SEPARATOR. $currPlaceData, $jsonString);
        $fileData = file_get_contents($currDataSetUrl. DIRECTORY_SEPARATOR. $currPlaceData);

        /*$currDataSetUrl = $this->dataSetUrl;
        $currPlaceData = $this->placeData;
        $fileData = file_get_contents($currDataSetUrl. DIRECTORY_SEPARATOR.$currPlaceData);*/
        //echo $fileData;

        //return $fileData = json_encode($fileData, JSON_PRETTY_PRINT);

        return $fileData;
    }



}