<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'EWm9SZDIJt1u8BkGVRIUbt22VaSCN7kLzdXZvH2f6EPw1LLLFBWPNXS+OfKiFej4mv0qT5maAl4pCUd+KFkNOQuacwvZ7lZE/V/A6WA4MjZ3y/lNmxjP49cHjjEax0HJTj+2K2CDZOlE92j98hYlZgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
		$text = $event['message']['text'];
		
	   	if($text == "ชื่ออะไร" || $text == "ชื่ออะไรคะ" || $text == "ชื่ออะไรครับ" || $text == "ชื่อ" || $text == "ชื่อไร"){
			$reply_message = 'ชื่อของฉัน คือ Hathaikan';
		}
	   	else if($text == "สถานการณ์โควิดวันนี้" || $text == "covid19" || $text == "covid-19" || $text == "Covid-19"){
		   	$url = 'https://covid19.th-stat.com/api/open/today';
		   	$ch = curl_init($url);
		   	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		   	curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
		   	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
		   	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		   	$result = curl_exec($ch);
		   	curl_close($ch);   
		   
		   	$obj = json_decode($result);
		   
		   	//$reply_message = $result;
		   	$reply_message = 'ติดเชื้อสะสม '. $obj->{'Confirmed'} . ' คน '. ' รักษาหายแล้ว '. $obj->{'Recovered'} . ' คน';
			//$reply_message = '<br>\r\n รักษาหายแล้ว '. $obj->{'Recovered'} . 'คน';
	   	}
	   	else if($text == "ชื่อผู้พัฒนาระบบ" || $text == "ชื่อผู้พัฒนา" || $text == "ผู้พัฒนาระบบ" || $text == "ผู้พัฒนา" || $text == "ชื่อผู้พัฒนาระบบคือใคร"|| $text == "ชื่อผู้พัฒนาคือใคร"|| $text == "ผู้พัฒนาระบบคื่อใคร"|| $text == "ผู้พัฒนาคือใคร"|| $text == "วันเกิด" ){
			$reply_message = 'ชื่อผู้พัฒนา คือ นางสาวหทัยกาญจน์ หิรัญนาค'. 'วันเกิด : วันศุกร์ที่ 16 กรกฎาคม 2542';
		}
	   	else if($text == "CDMA" || $text == "cdma" ){
			$reply_message = '+1,-3,-1,-1';
		}
	   	else if($text == "วันเกิดบอท" || $text == "บอทเกิดวันที่" ){
			$reply_message = 'วันเกิด : วันเสาร์ที่ 22 สิงหาคม 2563';
		}
	   	//else if(){
		//	$reply_message = 'วันเกิด : วันเสาร์ที่ 22 สิงหาคม 2563';
		//}
	   	else {
			$reply_message = '('.$text.') ได้รับข้อความเรียบร้อย!!  แต่ขออภัยค่ะ BOT ไม่เข้าใจข้อความที่คุณส่งมา '; 
		}
		//$reply_message = '('.$text.') ได้รับข้อความเรียบร้อย!!';   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>
