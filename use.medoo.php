<?php 
    include './libsmedoo/medoo.php';
    
    include './libsmedoo/Curl/CaseInsensitiveArray.php';
    include './libsmedoo/Curl/Curl.php';
    include './libsmedoo/Curl/MultiCurl.php';
    
    include './libsmedoo/DiDom/Document.php';
    include './libsmedoo/DiDom/Element.php';
    include './libsmedoo/DiDom/Query.php';
    
    
    use Curl\Curl;
    use DiDom\Document;
    use DiDom\Element;
    use DiDom\Query;
    use Medoo\Medoo;
define('BASE_URL','https://dichtruyentop.com/truyen/');

    $database = new Medoo([
        'type' => 'mysql',
        'host' => 'localhost',
        'database' => 'manga',
        'username' => 'root',
        'password' => ''
    ]);
    
    
$store = array();
$store['store_name'] = 'Onepunch Man';
$store['store_link'] = 'https://dichtruyentop.com/truyen/425-onepunch-man/';
insert_store($store);

$chapter = array();
$chapter['chapter_name'] = 'Chap 202';
$chapter['chapter_date'] = '18/12/21';
$chapter['chapter_link'] = 'https://dichtruyentop.com/truyen/425-onepunch-man/c683842-chap-202/?t=1640356881';


insert_chapter(1,$chapter);





    function insert_store($store){
        $name = $store['store_name'];
        $link = $store['store_link'];
        
        $sql = "INSERT INTO store (store_name, store_link)".
            " SELECT '$name', '$link' FROM DUAL".
            " WHERE NOT EXISTS (SELECT * FROM store".
            " WHERE store_link = '$link') LIMIT 1";//sql commang /insert not repeat
        
        //$sql = "INSERT INTO store (store_name,store_link) VALUES ('$name','$link')";        
        //echo $sql;
        
        global $database;//default not use variable this
        
        $database->query($sql);
        
        $data = $database->query("SELECT * FROM store WHERE store_link = '$link'")->fetch();
        
        return $data;
    }
    
    function insert_chapter($store_id, $chapter){
        $chapter_name = $chapter['chapter_name'];
        $chapter_date = $chapter['chapter_date'];
        $chapter_link = $chapter['chapter_link'];
        
        
        $sql = "INSERT INTO chapter (chapter_name, chapter_date,chapter_link,store_id)".
            " SELECT '$chapter_name', '$chapter_date','$chapter_link',$store_id FROM DUAL".
            " WHERE NOT EXISTS (SELECT * FROM chapter".
            " WHERE chapter_link = '$chapter_link') LIMIT 1";
        
        global $database;
        
        $database->query($sql);
        
        $data = $database->query("SELECT * FROM chapter WHERE chapter_link = '$chapter_link'")->fetch();
        
        return $data;
    }
    
    
    function insert_image($chapter_id, $image){
        $image_link = $image['image_link'];
        $image_path = $image['image_path'];
        
        
        $sql = "INSERT INTO image (image_link, image_path,chapter_id)".
            " SELECT '$image_link', '$image_path',$chapter_id FROM DUAL".
            " WHERE NOT EXISTS (SELECT * FROM image".
            " WHERE image_link = '$image_link') LIMIT 1";
        
        global $database;
        
        $database->query($sql);
        
        $data = $database->query("SELECT * FROM image WHERE image_link = '$image_link'")->fetch();
        
        return $data;
    }
    
    


function get_data($url, &$content){
    $curl = new Curl();
    
    echo 'Start craw: ' .$url.PHP_EOL;
    
    $curl->setConnectTimeout(60);
    $curl->setTimeout(60);
    
    $curl->get($url);
    
    if(!$curl->error){
        $content = $curl->response;
        echo 'End craw: ' .$url.' Success !!!'.PHP_EOL;
    }else{
        echo 'End craw: ' .$url.' Failt!!!'.PHP_EOL;
    }
    
    $curl->close();
    
    return !$curl->error;
}

    

function download_file($url, $path){
    $curl = new Curl();
    
    echo 'Start download: ' .$url.PHP_EOL;
    
    $curl->setConnectTimeout(60);
    $curl->setTimeout(60);
    
    $re = $curl->download($url, $path);
    
    if($re){
        echo 'End download: ' .$url . ' Sucess !!!' .PHP_EOL;
    }else{
        echo 'End download: ' .$url . ' Failt !!!' .PHP_EOL;
    }
    
    $curl->close();
}
    
    function get_name($content){
    $name = ''; 
    $dom = new Document();
    $dom->load($content);
    $name = $dom->find('div[class=de_title comictitle]')[0]->text();
    return $name;
}
    

    
function save_all_chapter($store_id,$content){
    $dom = new Document();
    $dom->load($content);
    
    $item_chapters = $dom->find('div[class=table-row]');
    
    if(isset($item_chapters) && count($item_chapters) > 0){
        for($i = 0; $i < count($item_chapters);++$i){
            $item_chapter = $item_chapters[$i];
            
            echo $item_chapter->html();//get data tag and html
           // print_r($item_chapter);
           $date = $item_chapter->find('div[class=table-data]')[0]->text();
            $chapter_name = $item_chapter->find('a')[0]->text();
            $href = BASE_URL . $item_chapter->find('a')[0]->getAttribute('href');
          //  print_r($item_chapter);
            //echo $href.PHP_EOL;//get data tag and html
              




            $chapter = array();
            $chapter['chapter_name'] = $chapter_name;
            $chapter['chapter_date'] = $date; 
            $chapter['chapter_link'] = $href;
            
            $data = insert_chapter($store_id, $chapter);
            
            
            $chapter_id = $data['chapter_id'];
            
         //   save_all_image($chapter_id,$href);

            echo "Date: $date - $chapter_name - $href" . PHP_EOL;
        }
    }
}












 
    $url = 'https://dichtruyentop.com/truyen/15612-cuu-vi-ho-ly-ngoai-truyen/';
    if(get_data($url,$content))
    {
        $name= get_name($content);
        $store = array();
$store['store_name'] =$name;
$store['store_link'] = $url;
$data=  insert_store($store);
print_r($data); 

$store_id = $data['store_id'];
save_all_chapter($store_id,$content);

    }else
    {
        echo 'cannot get data for this page !!!';
    }
    
?>