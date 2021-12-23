<?php 
set_time_limit(0);//If you don't do it for 10s, the file will die
//setup before default is 60s
 //0 is disable timeout
    $url= "https://zingmp3.vn/";
    echo get_data($url);


function get_data($link,$proxy=null,$proxy_type=null){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$link);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//1: turn off mode auto return
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36');//fake Browser into my pc use
    curl_setopt($ch,CURLOPT_REFERER,"https://www.google.com.vn/");//avoid others check 
    curl_setopt($ch,CURLOPT_TIMEOUT,10);//timeout handle
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);//connect to server
    curl_setopt($ch,CURLOPT_ENCODING,"");//accept all encoding (zing)
   
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);//redirect to page others
    curl_setopt($ch,CURLOPT_MAXREDIRS,5);//If you go through 5 websites but don't return data, then stop    
 //proxy
  
     
    //curl_setopt($ch,CURLOPT_PROXYTYPE,CURLPROXY_HTTP);//proxy list free
    if(isset($proxy)){
        curl_setopt($ch,CURLOPT_PROXY,"222.74.65.87:38051");
    }
    if(isset($proxy_type)){
        curl_setopt($ch,CURLOPT_PROXYTYPE,CURLPROXY_HTTP);//proxy list free
    }
    $data =  curl_exec($ch);
    curl_close($ch);
return $data;
    //echo htmlspecialchars($data) ;//convert to String
}
  


function check_proxy($proxy)
{
    $waitTimeoutInSeconds = 1;
    $proxy_split= explode(':',$proxy);
    $ip = $proxy_split[0];
    $port = $proxy_split[1];

    $result = false;

    if($fp= fsockopen($ip,$port,$errCode,$errStr,$waitTimeoutInSeconds)){
        $result= true; 
        fclose($fp);
    }
    
}

?>