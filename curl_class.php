<?php
include './libs/Curl/CaseInsensitiveArray.php';
include './libs/Curl/Curl.php';
include './libs/Curl/MultiCurl.php';

use \Curl\Curl;

$curl = new Curl();
$curl->setOpt(CURLOPT_ENCODING,'');
$curl->get('https://quantrimang.com/nhung-may-chu-proxy-an-danh-mien-phi-tot-nhat-145100');


if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage;
}
else {
    echo $curl->response;
}

$curl->close();

?>