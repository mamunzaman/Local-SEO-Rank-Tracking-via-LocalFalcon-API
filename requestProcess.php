<?php
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

}else{
    echo 'You can not request more';
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