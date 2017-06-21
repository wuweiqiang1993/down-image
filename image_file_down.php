<?php
    set_time_limit(0);
    ini_set('user_agent', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)'); 
    /*
    ** 获取网址图片
    **
    */
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
    for ($pageno = 1 ; $pageno < 147; $pageno ++) {
        if(!file_exists('./new_mzitu'.$pageno.'/')){
            mkdir('./new_mzitu'.$pageno.'/');//按网站分页分存放目录
        }
        $check = getContent('http://www.mzitu.com/page/'.$pageno);
        preg_match_all('/<li><a\shref=\"(.*?)\"\starget=\"_blank\"><img/',$check,$matches);//获得内容页链接
        foreach ($matches[1] as $url) {
            //echo $url;
            for ($i=1; $i < 100; $i++) { 
                $content = getContent($url.'/'.$i);
                if($content===FALSE){
                    break;
                }
                preg_match_all('/<img\ssrc=\"(.*?)\"/',$content,$matche);//获得图片链接
                foreach ($matche[1] as $img_url) {
                    //echo $url.'<br>';
                    $img = getContent($img_url);
                    if($img===FALSE){
                        continue;
                    }
                    file_put_contents('./new_mzitu'.$pageno.'/'.basename($img_url),$img);
                    //sleep(1);
                }
            }
        }
    }
