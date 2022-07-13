<?php
error_reporting(0);

$channel = "TemaX_N1";//Kanal usernamesi @ siz yoziladi! 
$admin = ["1070846128","503177249"];
define('API_KEY',"1916150868:AAE6ZJfJkVBoGZltdes9RgXS1mDin2S1KbM");
define('TITLE',"Music Compressor",true);
echo TITLE;
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

function send($chat_id,$text,$parse = 'html',$reply_markup = null){
	if($reply_markup == null){
		return bot('sendMessage',[
			'chat_id'=>$chat_id,
			'text'=>$text,
			'parse_mode'=>$parse
		]);
	}else{
		return bot('sendMessage',[
			'chat_id'=>$chat_id,
			'text'=>$text,
			'parse_mode'=>$parse,
			'reply_markup'=>$reply_markup
		]);
	}
}
function forward($from,$to,$mid){
	bot('forwardMessage',[
        'chat_id'=> $to,
        'from_chat_id'=>$from,
        'message_id'=>$mid
    ]);
}
function remove($cid,$mid){
	send($cid,"Keyboard removed",'html',json_encode([
		'remove_keyboard'=>true
	]));
	for($i = 1;$i <= 4;$i++){
		if($i == 2){
			continue;
		}else{
		del($cid,$mid+$i);
	}
	}
}
function del($cid,$mid){
	bot('deleteMessage',[
		'chat_id'=>$cid,
		'message_id'=>$mid
	]);
}
function get($name){
	return file_get_contents($name);
}
function put($name,$value){
	return file_put_contents($name, $value);
}
function MB($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}
function mdir($name){
	if(is_dir($name)){
		return false;
	}else{
		return mkdir($name);
	}
}
function check($cid,$channel){
	$get = bot('getChatMember',[
		'chat_id'=>"@".$channel,
		'user_id'=>$cid
	])->result->status;
	$ar = ["administrator","creator","member"];
	if(in_array($get, $ar)==true){
		return true;
	}else{
		return false;
	}
}
mdir("files");
mdir("files/vendor");
mdir("files/music");

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$cid = $message->chat->id;
$type = $message->chat->type;
$mid = $message->message_id;
$name = $message->chat->first_name;
$user = $message->from->username;
$text = $message->text;

$call = $update->callback_query;
$data = $call->data;
$meme12 = $call->from->id;
$meme14 = $call->message->message_id;
$callname = $call->from->first_name;
$calluser = $call->from->username;

$photo = $message->photo;
$photo_id = $photo[1]->file_id;

$gif = $message->animation;
$video = $message->video;
$music = $message->audio;
$mus_id = $music->file_id;

$voice = $message->voice;
$sticker = $message->sticker;

$document = $message->document;
$doc_id = $document->file_id;

$chast = json_encode([
	'inline_keyboard'=>[
		[['text'=>"Yuqori sifat",'callback_data'=>"64"]],
		[['text'=>"O'rta sifat",'callback_data'=>"48"]],
		[['text'=>"Past sifat",'callback_data'=>"32"]]
	]
]);
if($message and check($cid,$channel)==true){
if($text == "/set"){
	send($cid,"Quyidagi menyudan kerakli sifatni tanlang.",'html',$chast);
}
if($text){
    $get = get("mem.json");
    $get = json_decode($get);
    if(in_array($cid,$get)==false){
        $get[]="$cid";
        put("mem.json",json_encode($get));
    }
}
if($text == "/stat"){
    $get = get("mem.json");
    $get = json_decode($get);
    $con = count($get);
    send($cid,"A'zolar soni: $con ta");
}
if($text == "/start"){
	if(mb_stripos(get("chast.json"), $cid)!==false){
	send($cid,"Bizning Compressor botimizga xush kelibsiz\n
		Bot haqida batafsil - /help",'html',null);
}else{
	send($cid,"Bizning Compressor botimizga xush kelibsiz\nBot haqida batafsil - /help\nQuyidagi menyudan kerakli sifatni tanlang.",'html',$chast);
}
}
if($text == "/help"){
	send($cid,"Music Compressor Botimizga xush kelibsiz!\n
Bizning botimiz orqali istalgan audio faylingizni kichraytirishingiz mumkin.\n
Buning uchun o'z audio fayli(musiqa)ngizni botimizga yuborishingiz kerak va botimiz siz yuborgan audio fayl(musiqa) hajmini bir necha barobar kichraytirib beradi.\n
/set - Musiqaning qayta ishlash sifatini o'zgartirish \n\n Creators: @OnlineWolf @ProMasterPHP",'html',null);
}
$mus = isset($music);
if($mus==true){
		$doc_id = $mus_id;
	$url = json_decode(get('https://api.telegram.org/bot'.API_KEY.'/getFile?file_id='.$doc_id),true);
$path=$url['result']['file_path'];
$file = 'https://api.telegram.org/file/bot'.API_KEY.'/'.$path;
$type = strtolower(strrchr($file,'.')); 
$type=str_replace('.','',$type);
$okey = put("files/vendor/$cid.$type",get($file));

$chist = get_object_vars(json_decode(get("chast.json")));
$chist = $chist[$cid];

if($okey){
$exec = exec("ffmpeg -i files/vendor/$cid.$type -b:a ".$chist."k files/music/$cid.mp3");
		$org = MB(filesize("files/vendor/$cid.$type"));
		$comp = MB(filesize("files/music/$cid.mp3"));

bot('sendAudio',[
	'chat_id'=>$cid,
	'audio'=>new CURLFile("files/music/$cid.mp3"),
	'caption'=>"🎵Musiqaning original hajmi - $org\n
	🎧Musiqaning qayta ishlangandan keyingi hajmi - $comp"
]);
unlink("files/vendor/$cid.$type");
unlink("files/music/$cid.mp3");
}else{
	send($cid,"Xatolik!");
}
}
}else{
	send($cid,"Botdan foydalanish uchun quyidagi kanalimizga a'zo bo'ling!
		<a href='https://t.me/$channel'>Telegram uchun Temalar</a>",'html',json_encode([
			'inline_keyboard'=>[
				[['text'=>"Telegram uchun Temalar",'url'=>"https://t.me/$channel"]]
			]
		]));
}

if($data == "32" or $data == "48" or $data == "64"){
	$get = get_object_vars(json_decode(get("chast.json")));
	$get[$meme12]=$data;
	put("chast.json",json_encode($get));
	del($meme12,$meme14);
	send($meme12,"Bajarildi✅");
}
if($text == "/send" and in_array($cid, $admin)==true){
send($cid,"Marhamat a'zolarga yuborilishi kerak bo'lgan xabarni menga jo'nating",'html',json_encode([
	'resize_keyboard'=>true,
	'keyboard'=>[
		[['text'=>"Orqaga"]]
	]
]));
put("admin.step","send");
}
$step = get("admin.step");
if($step == "send" and (($text != "Orqaga" and $text != "/send") or $photo or $voice or $audio or $document) and in_array($cid, $admin)==true){
	$azo = get("mem.json");
	$dec = json_decode($azo);
	if(is_object($dec)){
		$dec = get_object_vars($dec);
	}
	$con = count($dec);
	for($i = 0;$i < $dec;$i++){
		forward($cid,$dec[$i],$mid);
		$d = $i+1;
		if($d == $con){
			send($cid,"Barchaga xabar yuborildi");
			remove($cid,$mid);
			$un = unlink("admin.step");
			if($un == true){
				echo true;
			}else{
				send($cid,"Nosozlik! STEP fayli o'chirilmadi");
				$un = unlink("admin.step");
			}
		}
	}
	unlink("admin.step");
}elseif($step == "send" and $text == "Orqaga" and in_array($cid, $admin)==true){
	send($cid,"Xabar yuborish to'xtatildi");
	unlink("admin.step");
}
?>
