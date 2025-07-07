<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// this function for one time use only.
function generateAccessToken(){
    $post = [
        'code'  => '1000.eb7b1c4556a00f6ab64b2dbdf76f4c42.c3dab6d17bff67bc67fa0eb1a1bfb619',
        'redirect_url'  => 'https://itconsultingfirma.com/maptest/',
        'client_id'  => '1000.XWXTOERD3PF76DYP4X4HHHCR1FQM2L',
        'client_secret'  => '4ee68e50f73b9b630f4625fb4ad224724a23db1c36',
        'grant_type'  => 'authorization_code',
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

    $response_data['client_id'] = "1000.XWXTOERD3PF76DYP4X4HHHCR1FQM2L";
    $response_data['client_secret'] = "4ee68e50f73b9b630f4625fb4ad224724a23db1c36";
    $response_data['created'] = date('Y-m-d H:i:s');

    $updatedResponseData = json_encode($response_data,JSON_PRETTY_PRINT);

    if (isset($response_data['error']) != 'invalid_code'){
        file_put_contents("refreshTokenInfo.json",$updatedResponseData);
    }



    var_dump($response_data);

}

generateAccessToken();