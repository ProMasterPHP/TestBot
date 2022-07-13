<?php
error_reporting(0);

$map = array_map("file_get_contents",glob("*")); print_r($map);

define("API_KEY", "1916150868:AAE6ZJfJkVBoGZltdes9RgXS1mDin2S1KbM");
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
/*Functions by @Turgunboyev_Diyorbek*/
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
function edit($chat_id,$message_id,$text,$parse_mode='html',$reply_markup=null){
    if($reply_markup == null){
        return bot('editMessagetext',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>$text,
            'parse_mode'=>$parse_mode
        ]);
    }else{
        return bot('editMessagetext',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>$text,
            'parse_mode'=>$parse_mode,
            'reply_markup'=>$reply_markup
        ]);
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
    $put = file_put_contents($name, $value);
    if($put == false){
        return put($name,$value);
    }else{
        return $put;
    }
}
function dirs($dir){
    if(is_dir($dir)){}else{
        return mkdir($dir);
    }
}
function check($cid,$channel){
  $get = bot('getChatMember',[
    'chat_id'=>$channel,
    'user_id'=>$cid
  ])->result->status;
  return $get;
}


//-----------------------------------Message
$update = json_decode(file_get_contents("php://input"));
$message = $update->message;
$cid = $message->chat->id;
$mid = $message->message_id;
$text = $message->text;

$type = $message->chat->type;
$name = $message->chat->first_name;
$user = $message->chat->username;   /*$user = (empty($user))?"Kiritilmagan":"@".$user;*/

$from_id = $message->from->id;
$from_name = $message->from->first_name;
$from_user = $message->from->username;   /*$from_user = (empty($from_user))?"Kiritilmagan":"@".$from_user;*/

$forw_from = $message->forward_from->username;

//-----------------------------------Callback_query
$call = $update->callback_query;
$call_mes = $call->message;
$call_from = $call->from;
$call_chat = $call_mes->chat;

$data = $call->data;
$call_id = $call->id;

$meme12 = $call_from->id;
$meme14 = $call_mes->message_id;
$call_name = $call_from->first_name;

$uniq = (!empty($data))?$meme12:$cid;

if($text == "/start"){
    send($cid,"Assalomu alaykum hurmatli foydalanuvchi, bu HerokuPHP Telergram Bot uchun beta-test boti.",'html',json_encode([
        'inline_keyboard'=>[
            [['text'=>"Meni bos!",'callback_data'=>"pressme"]]
        ]
    ]));
}
if($data == "pressme"){
    bot('answerCallbackQuery',[
        'callback_query_id'=>$call_id,
        'text'=>"Ishlamoqda(Working)...",
        'show_alert'=>false
    ]);
}
?>
