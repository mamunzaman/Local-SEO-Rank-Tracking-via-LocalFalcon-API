<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*********************
 * @return void
 * This function is call for RAW Cron Job using PHP and hosting settings to run every 30 min once to genarate the
 * access_key to insert
 * data
 * in Bigin
 */
function generateRefreshToken(){

    echo __DIR__;
    //die();
    $filePath = __DIR__."/refreshTokenInfo.json";
    $existingData = file_get_contents($filePath);
    $existingData = json_decode($existingData);
    //print_r($existingData);
    //die();

    $post = [
        'refresh_token'     => $existingData->refresh_token,
        'redirect_url'      => 'https://itconsultingfirma.com/maptest/',
        'client_id'         => $existingData->client_id,
        'client_secret'     => $existingData->client_secret,
        'grant_type'        => 'refresh_token',
    ];

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, "https://accounts.zoho.eu/oauth/v2/token");
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

    $response = curl_exec($ch);

    $response_data = json_decode($response,true);
    //echo $response_data->error;

    $response_data['created'] = date('Y-m-d H:i:s');
    $updatedResponseData = json_encode($response_data,JSON_PRETTY_PRINT);

    if (isset($response_data['error']) != 'invalid_code'){
        file_put_contents(__DIR__."/accessTokenPerHour.json",$updatedResponseData);
    }

    //var_dump($response_data);

}

generateRefreshToken();