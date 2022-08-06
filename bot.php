<?php
flush();

error_reporting(0);
define('API_KEY','1725860844:A45EOCjQ889SFS'); //توکن
function bomb_Source($method,$datas=[]){
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

function SendMessage($chatid,$text,$parsmde,$disable_web_page_preview,$keyboard){
	bomb_Source('sendMessage',[
	'chat_id'=>$chatid,
	'text'=>$text,
	'parse_mode'=>$parsmde,
	'disable_web_page_preview'=>$disable_web_page_preview,
	'reply_markup'=>$keyboard
	]);
	}
function ForwardMessage($KojaShe,$AzKoja,$KodomMSG)
{
    bomb_Source('ForwardMessage',[
        'chat_id'=>$KojaShe,
        'from_chat_id'=>$AzKoja,
        'message_id'=>$KodomMSG
    ]);
}
function deleteFolder($path){
    if (is_dir($path) === true) {
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file)
            deleteFolder(realpath($path) . '/' . $file);
            
        return rmdir($path);
    } else if (is_file($path) === true)
        return unlink($path);
 
    return false;
}
function save($filename,$TXTdata){
	$myfile = fopen($filename, "w") or die("Unable to open file!");
	fwrite($myfile, "$TXTdata");
	fclose($myfile);
	}

//============(config)==========
$token = " "; // توکن ربات
$channel = "@shirazsocial"; // آیدی کانال همراه @
$bot_id = "XshirazsocialBOT"; // آیدی ربات بدون اتساین
$admin1 = " "; // آیدی عددی ادمین
$admin = ""; // آآیدی عددی ادمین دو
$Apii = ""; //api. فالور رو بزارید
//==============================
$update = json_decode(file_get_contents("php://input"));
$message = $update->message;
$from_id = $update->message->from->id;
$chat_id = $update->message->chat->id;
$text = $update->message->text;
$first_name = $message->from->first_name;
$last_name = $message->from->last_name;
$username = $message->from->username;
@mkdir("data/$from_id");
$username = $update->message->from->username;
$truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=".$from_id));
$tch = $truechannel->result->status;
$state = file_get_contents("data/$from_id/state.txt");
$message_id = $update->message->message_id;
$Members = file_get_contents("data/Member.txt");
$gold = file_get_contents("data/$from_id/gold.txt");
//==============start===========
$start =  json_encode(['keyboard'=>[
[['text'=>'🌿دریافتـ فالو🌿'],['text'=>'حـسآبـ کـاربری🍉']],
[['text'=>'دریافتـ لینکـ دعوتـ🌵']],
],'resize_keyboard'=>true]);
//==============================
$button_manage = json_encode(['keyboard'=>[
[['text'=>'امتیاز به کاربر❗️']],
[['text'=>'بلاک کاربر'],['text'=>'آنبلاک']],
[['text'=>'💬فوروارد'],['text'=>'🎈آمار']],
[['text'=>'بازگشت↩️']],
],'resize_keyboard'=>true]);
//==============================
$back = json_encode(['keyboard'=>[
[['text'=>'بازگشت↩️']],
],'resize_keyboard'=>true]);
//==============================
if(preg_match('/^\/([Ss]tart)(.*)/',$text)){
if(file_exists("Block_users/$from_id.txt")){
//
}else{
file_put_contents("data/$from_id/state.txt","none");
preg_match('/^\/([Ss]tart)(.*)/',$text,$match);
$match[2] = str_replace(" ","",$match[2]);
$match[2] = str_replace("\n","",$match[2]);
if($match[2] != $from_id){
if (strpos($Members , "$from_id") == false){
$joins = file_get_contents('data/'.$match[2]."/joins.txt");
$check_join = explode("\n",$joins);
if(!in_array($from_id,$check_join)){
$aaddd = file_get_contents('data/'.$match[2]."/gold.txt");
save('data/'.$match[2]."/gold.txt",$aaddd+50);
SendMessage($match[2],"یک نفر با لینک شما وارد ربات شد و شما 50 فالو دریافت کردید🚶","html","true");
}
$add2 = fopen("data/$match[2]/joins.txt","a");
fwrite($add2,"$from_id\n");
fclose($add2);
SendMessage($chat_id,"
سلام ! 

🔹 به ربات دریافت فالو رایگان خوش آمدید.
🔻شما برای عضویت در ربات 50 فالو رایگان دریافت کردید که میتوانید از بخش سفارش فالو , فالو رایگان دریافت کنید !

❇️ شما همچنین میتوانید با عضو کردن هر یک نفر به ربات 50 فالو دریافت کنید !

توجه کنید اگر ربات برای یکی از لینک های شما فالو نزد حتما اون پیج یا ایدی توسط سرور ما بلاک شده است و برای سایر لینک ها به درستی کار میکند
@$bot_id
","html","true",$start);
file_put_contents("data/$from_id/state.txt","none");
file_put_contents("data/$from_id/gold.txt","50");
}else{
file_put_contents("data/$from_id/state.txt","none");
SendMessage($chat_id,"
سلام ! 

🔹 به ربات دریافت فالو رایگان خوش آمدید.
🔻شما برای عضویت در ربات 50 فالو رایگان دریافت کردید که میتوانید از بخش سفارش فالو , فالو رایگان دریافت کنید !

❇️ شما همچنین میتوانید با عضو کردن هر یک نفر به ربات 50 فالو دریافت کنید !

توجه کنید اگر ربات برای یکی از لینک های شما فالو نزد حتما اون پیج یا ایدی توسط سرور ما بلاک شده است و برای سایر لینک ها به درستی کار میکند.
@$bot_id
","html","true",$start);
}
}
}
}
elseif($tch != 'member' && $tch != 'creator' && $tch != 'administrator'){
SendMessage($chat_id,"🔸برای حمایت از ما و همچنان از ربات ابتدا وارد کانال زیر شوید👇
🆔 $channel
🔹روی عبارت join بزنید سپس به ربات برگشته و گزینه
🔸 /start
🔹را ارسال کنید تا دکمه های ربات نمایش داده شوند.","html","true",$button_remov);
return false;
}	
if($text == "حـسآبـ کـاربری🍉"){
SendMessage($chat_id,"🔹امتیاز شما تا این لحظه $gold فالو است !","html","true");
} 
if($text == "🌿دریافتـ فالو🌿"){
file_put_contents("data/$from_id/state.txt","lik");
SendMessage($chat_id,"آیدی عددی آینستاگرام خود را وارد کنید!","html","true",$back);
}
if($state == "lik" && $text != "بازگشت↩️"){
if($gold > 99){
file_put_contents("data/$from_id/state.txt","like");
SendMessage($chat_id,"لطفا صبر نمایید ...","html","true",$back);
$tedad = "0";
file_get_contents("$Apii$text");
//===========
$kam = $gold - 100;
file_put_contents("data/$from_id/gold.txt","$kam");
file_put_contents("data/$from_id/state.txt","none");
SendMessage($chat_id,"سفارش شما انجام شد.","html","true",$back);
}else{
file_put_contents("data/$from_id/state.txt","none");
SendMessage($chat_id,"امتیاز کافی نیست🌒!
موجودی شما:  $gold فالو.","html","true",$back);
}
}
if($text == "بازگشت↩️"){
file_put_contents("data/$from_id/state.txt","none");
SendMessage($chat_id,"
سلام ! 

🔹 به ربات دریافت فالو رایگان خوش آمدید.
🔻شما برای عضویت در ربات 50 فالو رایگان دریافت کردید که میتوانید از بخش سفارش فالو , فالو رایگان دریافت کنید !

❇️ شما همچنین میتوانید با عضو کردن هر یک نفر به ربات 50 فالو دریافت کنید !

توجه کنید اگر ربات برای یکی از لینک های شما فالو نزد حتما اون پیج یا ایدی توسط سرور ما بلاک شده است و برای سایر لینک ها به درستی کار میکند
@$bot_id
","html","true",$start);
}
if($text =="دریافتـ لینکـ دعوتـ🌵"){
SendMessage($chat_id,"📩شما میتوانید با دعوت هر نفر  50 فالو دریافت کنید :
$gold امتیاز شما🍄","html","true",$start);
SendMessage($chat_id,"
سلام 😄

این ربات رو دیدی ؟
هر کسی رو به ربات دعوت کنی  50 فالو رایگان میده😱 

تازه جایزه عضویت به ربات هم 50 فالو رایگانه 🤤
پس منتظر چی هستی؟
همین الان وارد شو و پیج اینستاگرامت رو بترکون😺💥

https://t.me/$bot_id?start=$from_id
","html","true",$start);
}
//=========================================
 if($text == 'مدیر' and $from_id == $admin){
  SendMessage($chat_id,"به پنل مدیریت خوش اومدی","html","true",$button_manage);
  }
  elseif($text == '🎈آمار' and $from_id == $admin){
	$txtt = file_get_contents('data/Member.txt');
    $member_id = explode("\n",$txtt);
    $mmemcount = count($member_id) -1;
	SendMessage($chat_id,"کل کاربران: $mmemcount نفر","html","true");
	}
  elseif($text == '💬فوروارد' and $from_id == $admin){
	file_put_contents("data/".$from_id."/state.txt","s2a fwd");
	SendMessage($chat_id,"پیام مورد نظر را فوروارد کنید","html","true");
	}
	elseif($state == 's2a fwd'){
	file_put_contents("data/".$from_id."/state.txt","none");
	SendMessage($chat_id,"پیام شما در صف ارسال قرار گرفت.","html","true",$button_manage);
	$all_member = fopen( "data/Member.txt", 'r');
		while( !feof( $all_member)) {
 			$user = fgets( $all_member);
bomb_Source('ForwardMessage',[
 'chat_id'=>$user,
 'from_chat_id'=>$admin,
 'message_id'=>$message_id
 ]);
 }
}
 if($text == 'مدیریت' and $from_id == $admin1){
  SendMessage($chat_id,"به پنل مدیریت خوش اومدی","html","true",$button_manage);
  }
  elseif($text == '🎈آمار' and $from_id == $admin1){
	$txtt = file_get_contents('data/Member.txt');
    $member_id = explode("\n",$txtt);
    $mmemcount = count($member_id) -1;
	SendMessage($chat_id,"کل کاربران: $mmemcount نفر","html","true");
	}
  elseif($text == '💬فوروارد' and $from_id == $admin1){
	file_put_contents("data/".$from_id."/state.txt","s2a fwd");
	SendMessage($chat_id,"پیام مورد نظر را فوروارد کنید","html","true");
	}
	elseif($state == 's2a fwd'){
	file_put_contents("data/".$from_id."/state.txt","none");
	SendMessage($chat_id,"پیام شما در صف ارسال قرار گرفت.","html","true",$button_manage);
	$all_member = fopen( "data/Member.txt", 'r');
		while( !feof( $all_member)) {
 			$user = fgets( $all_member);
bomb_Source('ForwardMessage',[
 'chat_id'=>$user,
 'from_chat_id'=>$admin1,
 'message_id'=>$message_id
 ]);
 }
} 
 elseif($text == 'بلاک کاربر' and $from_id == $admin){
file_put_contents("data/".$from_id."/state.txt","Block");
SendMessage($chat_id,"آیدی عددی را ارسال کنید :","html","true");
}
elseif($text == 'آنبلاک' and $from_id == $admin){
file_put_contents("data/".$from_id."/state.txt","UnBlock");
SendMessage($chat_id,"آیدی عددی را ارسال کنید:","html","true",$back);
}
if($state =="Block"){
file_put_contents("data/$from_id/state.txt","none");
file_put_contents("Block_users/$text.txt","true");
SendMessage($chat_id,"بلاک شد !","html","true",$button_manage);
}
if($state =="UnBlock"){
file_put_contents("data/$from_id/state.txt","none");
unlink("Block_users/$text.txt");
SendMessage($chat_id,"بلاک شد !","html","true",$button_manage);
}
//=======================================
 elseif($text == 'بلاک کاربر' and $from_id == $admin1){
file_put_contents("data/".$from_id."/state.txt","Block");
SendMessage($chat_id,"آیدی عددی را ارسال کنید :","html","true");
}
elseif($text == 'آنبلاک' and $from_id == $admin1){
file_put_contents("data/".$from_id."/state.txt","UnBlock");
SendMessage($chat_id,"آیدی عددی را ارسال کنید:","html","true",$back);
}
if($state =="Block" && $text !="بازگشت↩️"){
file_put_contents("data/$from_id/state.txt","none");
file_put_contents("Block_users/$text.txt","true");
SendMessage($chat_id,"بلاک شد !","html","true",$button_manage);
}
if($state =="UnBlock" && $text !="بازگشت↩️"){
file_put_contents("data/$from_id/state.txt","none");
unlink("Block_users/$text.txt");
SendMessage($chat_id,"بلاک شد !","html","true",$button_manage);
}
//=========================================
if($text == "امتیاز به کاربر❗️" && $from_id == $admin){
file_put_contents("data/$from_id/state.txt","sek");
SendMessage($chat_id,"آیدی عددی کاربر را وارد کنید :","html","true",$back);
} 
if($state == "sek" && $text != "بازگشت↩️"){
file_put_contents("data/$from_id/state.txt","tedad_eh");
file_put_contents("data/$from_id/karba.txt","$text");
SendMessage($chat_id,"چقدر کم کنم ؟
دقت کن چون اگر بیشتر امتیازی که داره ازش کم کنم امتیازش منفی میشه :/","html","true",$back);
}
if($state =="tedad_eh" && $text != "بازگشت↩️"){
$karbar = file_get_contents("data/$from_id/karba.txt");
$bia = $gold -$text ;
file_put_contents("data/$karbar/gold.txt","$bia");
SendMessage($chat_id,"میزان $text سکه به کاربر اهدا شد !","html","true",$back);
}
//========================================
if($text == "امتیاز به کاربر❗️" && $from_id == $admin1){
file_put_contents("data/$from_id/state.txt","sek");
SendMessage($chat_id,"آیدی عددی کاربر را وارد کنید :","html","true",$back);
}
if($state == "sek" && $text != "بازگشت↩️"){
file_put_contents("data/$from_id/state.txt","tedad_eh");
file_put_contents("data/$from_id/karba.txt","$text");
SendMessage($chat_id,"چقدر کم کنم ؟
دقت کن چون اگر بیشتر امتیازی که داره ازش کم کنم امتیازش منفی میشه :/","html","true",$back);
}
if($state =="tedad_eh" && $text != "بازگشت↩️"){
$karbar = file_get_contents("data/$from_id/karba.txt");
$bia = $gold -$text ;
file_put_contents("data/$karbar/gold.txt","$bia");
SendMessage($chat_id,"میزان $text سکه به کاربر اهدا شد !","html","true",$back);
}
 $user = file_get_contents('data/Member.txt');
    $members = explode("\n",$user);
    if (!in_array($chat_id,$members)){
      $add_user = file_get_contents('data/Member.txt');
      $add_user .= $chat_id."\n";
     file_put_contents('data/Member.txt',$add_user);
    }
?>
