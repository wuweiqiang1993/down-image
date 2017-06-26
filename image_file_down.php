#!G:\phpStudy\php\php-7.0.12-nts\php.exe -q
<?php
    function deleteAll($path) {
        $op = dir($path);
        while(false != ($item = $op->read())) {
            if($item == '.' || $item == '..') {
                continue;
            }
            if(is_dir($op->path.'/'.$item)) {
                deleteAll($op->path.'/'.$item);
                rmdir($op->path.'/'.$item);
            } else {
                unlink($op->path.'/'.$item);
            }
        
        }   
    }
    if(file_exists('./new_mzitu1/')){
        deleteAll('./new_mzitu1/');
    } 
    function getContent($url, $method = 'GET', $postData = array()) {
        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.1.2) Gecko/20120829 Firefox/3.5.2 GTB5');
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        $content = curl_exec($curl);
        curl_close($curl);
        return $content;
    }
    $STDOUT = fopen('php://stdout', 'w');
    for ($pageno = 1 ; $pageno < 147; $pageno ++) {
        if(!file_exists('./new_mzitu'.$pageno.'/')){
            mkdir('./new_mzitu'.$pageno.'/');
        }
        $check = getContent('http://www.mzitu.com/page/'.$pageno);
        preg_match_all('/<li><a\shref=\"(.*?)\"\starget=\"_blank\"><img/',$check,$matches);
        foreach ($matches[1] as $url) {
            //echo $url;
            for ($i=1; $i < 100; $i++) { 
                $content = getContent($url.'/'.$i);
                if($content===FALSE){
                    break;
                }
                preg_match_all('/<img\ssrc=\"(.*?)\"/',$content,$matche);
                //preg_match_all('/class=\"joke-main-img\" src=\"(.*?)\"/',$content,$matches);
                foreach ($matche[1] as $img_url) {
                    //echo $url.'<br>';
                    $img = getContent($img_url);
                    if($img===FALSE){
                        continue;
                    }
                    file_put_contents('./new_mzitu'.$pageno.'/'.basename($img_url),$img);
                    fwrite($STDOUT,basename($img_url)." Done\n");
                }
            }
        }
    }
    fwrite($STDOUT,"All Done\n");
    fclose($STDOUT);
