<?php 
set_time_limit(0);
 

$url = $_GET['url']; //afer ?
 //echo $url;
if(get_data($url, $html)){
  
 

    preg_match_all('#player\.peConfig\.xmlURL = "(.+?)";#', $html, $matches);
   
    $xml_link = $matches[1][0];
    echo $xml_link;
     echo "<pre>";
     print_r($matches[1][0]);
echo "</pre>";
    if(get_data($matches[1][0], $xml)){
        var_dump($xml);

        //TODO
        preg_match_all('#<!\[CDATA\[(.+?)\]\]>#', $xml, $matches2);
          #<source><!\[CDATA\[(.+?)\]\]></source>#is
    
     echo "<pre>";
     print_r($matches2[1][3]);
        
    }else{
        echo 'Khong the lay duoc xml';
    }
}else{
    echo 'Khong the lay du lieu trang n';
}
 

function get_data($link, &$data = ''){
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
         
	$data = curl_exec($ch); 
        
        $error = curl_error($ch);
        curl_close($ch);
        
        if(empty($error)){
            return true;
        } 
	 
	return false;
}   
