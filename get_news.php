<?php

unset($cache_dir);
include_once 'config.php';
global $cache_dir;
$news_url = "http://www.greensql.net/community-news-feed";

$data = get_page($news_url);
if (!$data)
{
  exit;
}
$news = parse_news($data);

$file = $cache_dir . DIRECTORY_SEPARATOR . "news.txt";
#print "writing $file\n";
$fp = @fopen($file, "w");
if (!$fp)
{
  exit;
}
@fprintf($fp, $news);
@fclose($fp);

exit;

function parse_news($data)
{
  $data = preg_replace("/[\r\n]/", " ", $data);
  // parse item, title, link, pubDate
  $data = preg_replace("/^.*?<item>/", "<item>", $data);
  $news = split("<item", $data);
  $matches = array();

  $title = "";
  $link = "";
  $date = "";

  // date format
  // Tue, 25 Nov 2008 21:13:53 +0000
  $format = "%a, %d %b %Y %H:%M:%S +";
  $res = array();
  $list = "";

  foreach ($news as $info)
  {
    preg_match("/<title>([^<]*)</i", $info, $matches);
    $title = $matches[1];
    preg_match("/<link>([^<]*)</i", $info, $matches);
    $link = $matches[1];
    preg_match("/<pubDate>([^<]*)</i", $info, $matches);
    $date = $matches[1];
 
    if (!$title)
      continue;

    $res = strptime($date, $format);
    $print_data = sprintf("%04d-%02d-%02d %02d:%02d:%02d", 
               $res['tm_year']+1900, $res['tm_mon']+1, $res['tm_mday'],
               $res['tm_hour'], $res['tm_min'], $res['tm_sec']);
    $list .= "$print_data|$title|$link\n";
    # print_r(strptime($date, $format));
    # print "line: $info\n";
  }
  return $list; 
}

function get_page($url)
{
  $data = "";
  // try using curl
  if (function_exists('curl_init'))
  {
    $timeout = 60;
    $curl = curl_init();
    // set URL and other appropriate options
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
  }
  if (ini_get('allow_url_fopen'))
  {
    $data = file_get_contents($url);
    return $data;
  }

}
?>
