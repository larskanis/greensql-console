<?php

include_once './config.php';
global $cache_dir;

if (!isset($cache_dir) || strlen($cache_dir) == 0)
{
  include_once getcwd() . DIRECTORY_SEPARATOR .'config.php';
  global $cache_dir;
}

$news_url = "http://www.greensql.net/community-news-feed";
$twitts_url = "http://twitter.com/statuses/user_timeline/18915816.rss";

$twitts_data = get_page($twitts_url);
$news_data = get_page($news_url);
if (!$twitts_data && !$news_data)
{
 exit;
}
#print "parsing $cache_dir\n";

$news = parse_data($news_data);
$twitts = parse_data($twitts_data);
$twitts = preg_replace('/greensql\:/','',$twitts);

$file = $cache_dir . DIRECTORY_SEPARATOR . "news.txt";
#print "writing $file\n";
$fp = @fopen($file, "w");
if (!$fp)
{
  echo "failed to write to $file<BR>";
  exit;
}
@fprintf($fp, "%s", $news);
@fclose($fp);

$file = $cache_dir . DIRECTORY_SEPARATOR . "twitts.txt";
$fp2 = @fopen($file, "w");
if (!$fp2)
{
 echo "failed to write to $file<BR>";
 exit;
}

@fprintf($fp2, "%s", $twitts);
@fclose($fp2);

exit;

function parse_data($data)
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
 if (function_exists('parse_url'))
 {
  $u_info = parse_url($url);
  $host = $u_info['host'];
  $path = $u_info['path'];

  $request  = "GET $path HTTP/1.1\r\n";
  $request .= "Host: $host\r\n";
  $request .= "Connection: Close\r\n\r\n";

  $errno;
  $errstr;
  $fp = @fsockopen($host, 80, $errno, $errstr, 10);
  if (!$fp)
   return "";
  @stream_set_timeout($fp, 2);
  if (@fwrite($fp, $request) === false) 
  {
   @fclose($fp);
   return "";
  }
  $info = @stream_get_meta_data($fp);
  if ($info['timed_out']) 
  {
   @fclose($fp);
   return;
  }
  $result = '';
  while (!@feof($fp)) 
  {
   $result .= fgets($fp, 1024);
   $info = @stream_get_meta_data($fp);
   if ($info['timed_out']) 
   {
    @fclose($fp);
    return;
   }
  }
  @fclose($fp);
  $pos = @strpos($result, "\r\n\r\n");
  if($pos === false)
   return;
  $body = @substr($result, $pos + 4);
  return $body;
 }
}
?>
