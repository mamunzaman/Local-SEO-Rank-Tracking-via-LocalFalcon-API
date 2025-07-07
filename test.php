<?php
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $protocol = 'https';
} else {
    $protocol = 'http';
} 

$currentUrl = $protocol.'://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
echo $currentUrl;