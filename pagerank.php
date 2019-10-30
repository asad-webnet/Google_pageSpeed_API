<?php

error_reporting(0);

ini_set('max_execution_time', 300);

$curl = curl_init();

$urlSpeedResults = array();

$urlArray = array(
    'https://jobcull.com',
    'https://jsoneditoronline.org',

);

$urlSpeedResults[] = array("Page Name","First ContentFul Name" , "Speed Index" , "Interactive ","Estimated Latency","Cpu Idle");

foreach ($urlArray as $url)
{
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$url",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "cache_control: no_cache"
        )
      ));
      $response = curl_exec($curl);
	  

	  
      $response = json_decode("[" . $response . "]",TRUE);
      // print_r($response);
      $results=$response[0];
      $results=$results['lighthouseResult']['audits'];



      $first_contentful_paint=$results['first-contentful-paint']['displayValue'];
      $speed_index=$results['speed-index']['displayValue'];
      $interactive=$results['interactive']['displayValue'];
      $first_meaningful_paint=$results['first-meaningful-paint']['displayValue'];
      $estimated_input_latency=$results['estimated-input-latency']['displayValue'];
      $first_cpu_idle=$results['first-cpu-idle']['displayValue'];


      $urlSpeedResults[] = array($url,$first_contentful_paint , $speed_index , $interactive,$estimated_input_latency,$first_cpu_idle);
    
}


// print_r($urlSpeedResults);
echo "\xef\xbb\xbf";
header("Content-type: text/csv; charset=utf-8; encoding=utf-8");
header('Content-Disposition: attachment; filename="url_data.csv"');
echo "\xef\xbb\xbf";
$fp = fopen('php://output', 'wb');
foreach ($urlSpeedResults as $url ) 
{
  fputcsv($fp, $url);
}

fclose($fp);
?>