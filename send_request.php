<?php
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['success'=>false],JSON_UNESCAPED_UNICODE); exit; }
$c=require __DIR__.'/config.php'; $token=trim($c['bot_token']??''); $chat=trim((string)($c['chat_id']??''));
$name=trim($_POST['name']??''); $res=trim($_POST['residence']??''); $phone=trim($_POST['phone']??''); $service=trim($_POST['service']??''); $notes=trim($_POST['notes']??'');
if($name===''||$res===''||$phone===''||$service===''){http_response_code(400);echo json_encode(['success'=>false,'message'=>'missing'],JSON_UNESCAPED_UNICODE);exit;}
if($token===''||$token==='PUT_YOUR_BOT_TOKEN_HERE'||$chat===''){http_response_code(500);echo json_encode(['success'=>false,'message'=>'telegram config missing'],JSON_UNESCAPED_UNICODE);exit;}
$text="🔔 طلب خدمة جديد\n\n👤 الاسم: $name\n📍 مكان السكن: $res\n📞 رقم الهاتف: $phone\n🛠️ نوع الخدمة: $service\n📝 الملاحظات: ".($notes!==''?$notes:'لا يوجد');
$url="https://api.telegram.org/bot$token/sendMessage"; $post=['chat_id'=>$chat,'text'=>$text];
if(function_exists('curl_init')){$ch=curl_init($url);curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>http_build_query($post),CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>10,CURLOPT_TIMEOUT=>20,CURLOPT_SSL_VERIFYPEER=>true]);$response=curl_exec($ch);$err=curl_error($ch);curl_close($ch);if($response===false){http_response_code(502);echo json_encode(['success'=>false,'message'=>$err],JSON_UNESCAPED_UNICODE);exit;}}else{$ctx=stream_context_create(['http'=>['method'=>'POST','header'=>"Content-Type: application/x-www-form-urlencoded\r\n",'content'=>http_build_query($post),'timeout'=>20]]);$response=@file_get_contents($url,false,$ctx);if($response===false){http_response_code(502);echo json_encode(['success'=>false],JSON_UNESCAPED_UNICODE);exit;}}
$d=json_decode($response,true);if(!is_array($d)||empty($d['ok'])){http_response_code(502);echo json_encode(['success'=>false],JSON_UNESCAPED_UNICODE);exit;}echo json_encode(['success'=>true],JSON_UNESCAPED_UNICODE);
