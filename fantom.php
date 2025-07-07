<?php
$url = 'http://PhantomJScloud.com/api/browser/v2/ak-9dggr-pkw02-5j9kq-mjyt5-ees5t/';
$payload = file_get_contents ( 'request.json' );
$options = array(
    'http' => array(
        'header'  => "Content-type: application/json;charset=UTF-8\r\n",
        'method'  => 'POST',
        'content' => $payload
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { /* Handle error */ }
file_put_contents('content_100.png',$result);
?>