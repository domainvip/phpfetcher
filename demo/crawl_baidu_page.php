<?php
//下面两行使得这个项目被下载下来后本文件能直接运行
$demo_include_path = dirname(__FILE__) . '/../';
set_include_path(get_include_path() . PATH_SEPARATOR . $demo_include_path);

require_once('phpfetcher.php');
class mycrawler extends Phpfetcher_Crawler_Default {
    public function handlePage($page) {
        //打印处当前页面的title
        $res = $page->sel('//h2/a');
        for ($i = 0; $i < count($res); ++$i) {
           // echo $res[$i]->plaintext;
            //echo "\n";
              $res[$i]->getAttribute('href');
            $post_data[$i] = 'url='.$res[$i]->getAttribute('href').'&search_words=test';
            $posturl = "http://dev.laidoulaile.com/site/add";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $posturl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch,CURLOPT_HEADER,0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data[$i]);
              $output = curl_exec($ch);
            if ($output === FALSE) {
                echo "cURL Error: " . curl_error($ch);}
            curl_close($ch);
            print_r($output);
            sleep(1);
            //echo $post_data[0];
        }


    }
}

$crawler = new mycrawler();
$arrJobs = array(
    //任务的名字随便起，这里把名字叫qqnews
    //the key is the name of a job, here names it qqnews
    'qqnews' => array( 
        'start_page' => 'http://www.rebeccaminkoff.com/shoes/new-arrivals/', //起始网页
        'link_rules' => array(
            /*
             * 所有在这里列出的正则规则，只要能匹配到超链接，那么那条爬虫就会爬到那条超链接
             * Regex rules are listed here, the crawler will follow any hyperlinks once the regex matches
             */
        ),
        //爬虫从开始页面算起，最多爬取的深度，设置为1表示只爬取起始页面
        //Crawler's max following depth, 1 stands for only crawl the start page
        'max_depth' => 1, 
        
    ) ,   
);

//$crawler->setFetchJobs($arrJobs)->run(); //这一行的效果和下面两行的效果一样
$crawler->setFetchJobs($arrJobs);
$crawler->run();
