<?php

error_reporting(0);
define('API_KEY',"Token"); // توکن ربات (Robot token)
function HectorBot($method,$datas=[]){
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
//=================================================//Functions//
function Send($chat_id,$text,$parse_mode,$keyboard){
	HectorBot('sendMessage',[
	'chat_id'=>$chat_id,
	'text'=>$text,
	'parse_mode'=>$parse_mode,
	'reply_markup'=>$keyboard
	]);
}
function Edit($chatid,$message_id,$parsmde,$text,$keyboard){
    HectorBot('editmessagetext',[ 
    'chat_id'=>$chatid, 
    'message_id'=>$message_id,
    'text'=>$text,
    'parse_mode'=>$parsmde,
    'reply_markup'=>$keyboard
	]);
	}
	function ForwardMessage($kojashe,$azkoja,$kodomMSG)
{
    HectorBot('ForwardMessage',[
        'chat_id'=>$kojashe,
        'from_chat_id'=>$azkoja,
        'message_id'=>$kodomMSG
        ]);
}
function SendPhoto($chat_id, $photo, $caption, $messageid, $keyboard){
	HectorBot('SendPhoto',[
    'chat_id'=>$chat_id,
    'photo'=>$photo,
    'caption'=>$caption,
    'reply_to_message_id'=>$messageid,
    'reply_markup'=>$keyboard
     ]);
     }
     function save($filename,$TXTdata){
	$myfile = fopen($filename, "w") or die("Unable to open file!");
	fwrite($myfile, "$TXTdata");
	fclose($myfile);
	}
	function Delete($chat_id,$message_id)
{
	HectorBot('deletemessage',['chat_id'=>$chat_id,'message_id'=>$message_id]);
	}
//=================================================//Config//
$dev = "000000000";//ایدی عددی
$token = API_KEY;
$channel = "@shirazsocial";//ایدی چنل یا @ (id channel with @)
$BotId = "shirazsocial"; // بدون@ایدی بات (id bot without @)
$pic = "https://www.creativefabrica.com/wp-content/uploads/2019/02/Monogram-AP-Logo-Design-by-Greenlines-Studios-580x386.jpg";//لینک عکس
//=================================================//Variables//
$update = json_decode(file_get_contents("php://input"));
$message = $update->message;
$message_id = $update->message->message_id;
$data = isset($message->text)?$message->text:$update->callback_query->data;
$chat_id = isset($update->callback_query->message->chat->id)?$update->callback_query->message->chat->id:$update->message->chat->id;
$from_id = isset($update->callback_query->message->from->id)?$update->callback_query->message->from->id:$update->message->from->id;
$text = $update->message->text;
$state = file_get_contents("data/$chat_id/state.txt");
$mi = isset($update->callback_query->message->message_id)?$update->callback_query->message->message_id:null;
$first_n = $update->message->from->first_name;
$last_n = $update->message->from->last_name;
$first = $update->callback_query->from->first_name;
$last = $update->callback_query->from->last_name;
$usernamee = $update->message->from->username;
$username = $update->callback_query->from->username;
//=================================================//Lock Channel//
$truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=".$chat_id));
$channel_check = $truechannel->result->status;
//=================================================//System Variables//
$user = json_decode(file_get_contents("data/user.json"),true);
$step = json_decode(file_get_contents("data/$chat_id.json"),true);
$coins = $step["userinfo"]["$chat_id"]["coin"];
$invited = $step["userinfo"]["$chat_id"]["invite"];
$state = $step["userinfo"]["$chat_id"]["state"];
//=================================================//Buttons//

$menu = json_encode(['inline_keyboard'=>[
[['text'=>"💠 بــخش پنـل 💠","callback_data"=>"panels"]],
[['text'=>"زیرمجموعه گیری 🏞","callback_data"=>"banner"],['text'=>"👤 حساب من","callback_data"=>"account"]],
[['text'=>"راهنما 📌","callback_data"=>"help"],['text'=>"🛒 خرید امتیاز","callback_data"=>"buy"]],
[['text'=>"📨 ارسال نظر و ایده 📨","callback_data"=>"idea"]],
],'resize_keyboard'=>true]);
//
$panels = json_encode(['inline_keyboard'=>[
[['text'=>"🌟 پنل همه کاره اینستاگرام 🌟","callback_data"=>"hamekare-insta"]],
[['text'=>"پنل لایک اینستاگرم 🌍","callback_data"=>"like-insta"],['text'=>"🌏 پنل فالوور اینستاگرام","callback_data"=>"follow-insta"]],
[['text'=>"🔥 پنل فالوور و لایک اینستاگرم 🔥","callback_data"=>"followlike-insta"]],
[['text'=>"بازگشت ⬅️","callback_data"=>"back"]],
],'resize_keyboard'=>true]);
//
$panel = json_encode(['inline_keyboard'=>[
[['text'=>"📈 آمار","callback_data"=>"member"]],
[['text'=>"⏫ فروارد همگانی","callback_data"=>"forward"],['text'=>"⏫ ارسال همگانی","callback_data"=>"hamegani"]],
[['text'=>"➖ کم کردن امتیاز","callback_data"=>"kam"],['text'=>"➕ افزودن امتیاز","callback_data"=>"add"]],
[['text'=>"♾ امتیاز همگانی","callback_data"=>"allstate"]],
[['text'=>"بازگشت ⬅️","callback_data"=>"back"]],
],'resize_keyboard'=>true]);
//
$back = json_encode(['inline_keyboard'=>[
[['text'=>"بازگشت ⬅️","callback_data"=>"back"]],
],'resize_keyboard'=>true]);
//
$backp = json_encode(['inline_keyboard'=>[
[['text'=>"بازگشت","callback_data"=>"backp"]],
],'resize_keyboard'=>true]);
//
if(!in_array($chat_id,$user["listusers"]) == true) {
$user["listusers"][]="$chat_id";
$user = json_encode($user,128|256);
file_put_contents("data/user.json",$user);
}
//===========================
if($channel_check != 'member' && $channel_check != 'creator' && $channel_check != 'administrator'){
	HectorBot('sendmessage',['chat_id'=>$from_id,'text'=>"اوپس !!!
🎗شما در کانال ما عضو نیستید ، لطفا در کانال زیر عضو شوید و بعد ربات را دوباره /start کنید👇🏻

💠 @shirazsocial",'parse_mode'=>"HTML",'reply_to_message_id'=>$message_id,'reply_markup'=>json_encode(['inline_keyboard'=>[
        [['text'=>'🌐 عضویت','url'=>'https://t.me/shirazsocial']],
    ],])
]);
return false;
}
//===========================
elseif ($text =="/start"){
if(!file_exists("data/$chat_id.json")){
$step["userinfo"]["$chat_id"]["coin"]= "0";
$step["userinfo"]["$chat_id"]["state"]= "none";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Send($chat_id,"درود $first_n عزیز 👋🏻
به ربات «آلفـا پنـل» خوش اومدی 💐

🔹 با استفاده از این ربات میتونی به کلی پنل اینستا ، تلگرام و... دسترسی داشته باشی اونم رایگان !!

لطفا از طریق دکمه های منو اقدام فرمایید 👇🏻","HTML",$menu);
}else{
Send($chat_id,"درود $first_n عزیز 👋🏻
به ربات «آلفـا پنـل» خوش اومدی 💐

🔹 با استفاده از این ربات میتونی به کلی پنل اینستا ، تلگرام و... دسترسی داشته باشی اونم رایگان !!

لطفا از طریق دکمه های منو اقدام فرمایید 👇🏻","HTML",$menu);
}
}
//===========================
elseif(strpos($text , '/start ') !== false  ) {
$sdfg = str_replace("/start ","",$text);
if(in_array($chat_id, $user["listusers"])) {
Send($chat_id,"خودت میخوای زیرمجموعه خودت شی؟؟!","HTML",null);
}else 
{	
$inuser = json_decode(file_get_contents("data/$sdfg.json"),true);
$member = $inuser["userinfo"]["$sdfg"]["invite"];
$memberplus = $member + 1;
$members = $inuser["userinfo"]["$sdfg"]["coin"];
$memberpluss = $members + 1;
Send($sdfg,"🎉 کاربر $first_n$last_n با استفاده از لینک دعوتت وارد  شد

یه سکه بهت اضافه کردم :)

🎈 تعداد افرادی که دعوت کرده اید : $memberplus","HTML",$menu);
Send($chat_id,"درود $first_n عزیز 👋🏻
به ربات «آلفـا پنـل» خوش اومدی 💐

🔹 با استفاده از این ربات میتونی به کلی پنل اینستا ، تلگرام و... دسترسی داشته باشی اونم رایگان !!

لطفا از طریق دکمه های منو اقدام فرمایید 👇🏻","HTML",$menu);
$inuser["userinfo"]["$sdfg"]["invite"]="$memberplus";
$inuser["userinfo"]["$sdfg"]["coin"]="$memberpluss";
$inuser = json_encode($inuser,true);
file_put_contents("data/$sdfg.json",$inuser);
$step["userinfo"]["$chat_id"]["file"]="none";
$step["userinfo"]["$chat_id"]["coin"]="0";
$step["userinfo"]["$chat_id"]["invite"]="0";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);	
}}

if($data =="panels"){
	Edit($chat_id,$mi,"HTML","لطفا یکی از پنل های زیر را انتخاب کنید 👇🏻",$panels);
}
//===========================
if($data =="back"){
	Edit($chat_id,$mi,"HTML","به منوی اصلی برگشتی 👍🏻",$menu);
}
//===========================
if($data =="backp"){
	Edit($chat_id,$mi,"HTML","به پنل برگشتی 👍🏻",$panel);
}
//===========================
elseif($data =="idea"){
$step["userinfo"]["$chat_id"]["state"]="ideas";
    $step = json_encode($step,true);
    file_put_contents("data/$chat_id.json",$step);
Send($chat_id,"🎗دوست عزیز لطفا اگر ایده یا نظری برای آپدیت و یا تغییری برای بهبودی ربات دارید که فکر میکنید مفید است را ارسال کنید ...","HTML",$back);
}
if($state =="ideas" && $data != "back"){
	$step["userinfo"]["$chat_id"]["state"]="none";
    $step = json_encode($step,true);
    file_put_contents("data/$chat_id.json",$step);
Send($chat_id,"با تشکر از شما 🌹","HTML",$back);
Send($dev,"یه پیام از طرف کاربر @$username دارید🌱
___________________________
$text
___________________________","HTML",$back);
}
//===========================
if($data =="hamekare-insta"){
if($step["userinfo"]["$chat_id"]["coin"] >= 50){
$coinupp = $coins -50;
$step["userinfo"]["$chat_id"]["coin"] = $coinupp;
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Edit($chat_id,$mi,"HTML","تبریک میگم شما موفق به دسترسی به این پنل شدید 🥳

🌙پنل همه کاره ی اینستاگرام :
🌐 www.igtools.net",$back);
}else{
Edit($chat_id,$mi,"HTML","دوست عزیز شما قادر به دسترسی به این پنل نیستید‼️

📛 لطفا ابتدا امتیاز خود را برای این بخش جمع آوری کنید و سپس مجدد تلاش کنید ♻️

🚩 مقدار امتیاز این پنل : 50
🔅تعداد سکه های شما : $coins",$back);
}
}
//
if($data =="follow-insta"){
if($step["userinfo"]["$chat_id"]["coin"] >= 15){
$coinupp = $coins -15;
$step["userinfo"]["$chat_id"]["coin"] = $coinupp;
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Edit($chat_id,$mi,"HTML","تبریک میگم شما موفق به دسترسی به این پنل شدید 🥳

🌙پنل فالوور اینستاگرام :
🌐 www.mediahile.com - www.silvertakip.net",$back);
}else{
Edit($chat_id,$mi,"HTML","دوست عزیز شما قادر به دسترسی به این پنل نیستید‼️

📛 لطفا ابتدا امتیاز خود را برای این بخش جمع آوری کنید و سپس مجدد تلاش کنید ♻️

🚩 مقدار امتیاز این پنل : 15
🔅تعداد سکه های شما : $coins",$back);
}
}
//
if($data =="like-insta"){
if($step["userinfo"]["$chat_id"]["coin"] >= 15){
$coinupp = $coins -15;
$step["userinfo"]["$chat_id"]["coin"] = $coinupp;
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Edit($chat_id,$mi,"HTML","تبریک میگم شما موفق به دسترسی به این پنل شدید 🥳

🌙برنامه لایک اینستاگرام :
🌐 http://dl.wooda.ir/Leetgram.5.9.apk",$back);
}else{
Edit($chat_id,$mi,"HTML","دوست عزیز شما قادر به دسترسی به این پنل نیستید‼️

📛 لطفا ابتدا امتیاز خود را برای این بخش جمع آوری کنید و سپس مجدد تلاش کنید ♻️

🚩 مقدار امتیاز این پنل : 15
🔅تعداد سکه های شما : $coins",$back);
}
}
//
if($data =="followlike-insta"){
if($step["userinfo"]["$chat_id"]["coin"] >= 25){
$coinupp = $coins -25;
$step["userinfo"]["$chat_id"]["coin"] = $coinupp;
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Edit($chat_id,$mi,"HTML","تبریک میگم شما موفق به دسترسی به این پنل شدید 🥳

🌙پنل لایک و فالوور اینستاگرام :
🌐 www.insfollow.com",$back);
}else{
Edit($chat_id,$mi,"HTML","دوست عزیز شما قادر به دسترسی به این پنل نیستید‼️

📛 لطفا ابتدا امتیاز خود را برای این بخش جمع آوری کنید و سپس مجدد تلاش کنید ♻️

🚩 مقدار امتیاز این پنل : 25
🔅تعداد سکه های شما : $coins",$back);
}
}
//===========================
if($data == "account"){
HectorBot('editmessagetext', [
'chat_id'=>$chat_id,
'text' => "ℹ️ اطلاعات حساب کاربری شما :",
'message_id'=>$mi,
'disable_web_page_preview'=>true,
'parse_mode' => "HTML",
'reply_markup'=>json_encode(['inline_keyboard'=>[
[['text'=>'نام ▪️','callback_data'=>'amir'],['text'=>"$first",'callback_data'=>'amir']],
[['text'=>'ایدی ▫️','callback_data'=>'amir'],['text'=>"@$username",'callback_data'=>'amir']],
[['text'=>'ایدی عددی ▪️','callback_data'=>'amir'],['text'=>"$chat_id",'callback_data'=>'amir']],
[['text'=>'امتیاز ▫️','callback_data'=>'amir'],['text'=>"$coins",'callback_data'=>'amir']],
[['text'=>'زیرمجموعه ▪️','callback_data'=>'amir'],['text'=>"$invited",'callback_data'=>'amir']],
[['text'=>'بازگشت ⬅️','callback_data'=>'back']],
],])
]);
}
//===========================
if($data =="help"){
	Edit($chat_id,$mi,"HTML","به بخش راهنما خوش اومدی🌿

🌵خب همونجور که از اسم این ربات پیداست ، این یه ربات با کلی پنل هست.

❤️ از پنل لایک و فالو و ویو و... اینستاگرام بگیر تاااا پنل تلگرام و کلی چیزای دیگه 💜

🆓 جالب اینجاست که همه ی پنل ها توی ربات رایگان هستن و نیازی نیست بخاطر بدست آوردن اونها پولی پرداخت کنی و خیلی راحت میتونی با زیرمجموعه گیری اونهارو داشته باشی !",$menu);
}
//===========================
if($data =="buy"){
	Edit($chat_id,$mi,"HTML","بزودی ...",$back);
}
//===========================
if($data =="banner"){
SendPhoto($chat_id,"$pic","🛸 ربات آلفــا پنل رسید ...

💡یه ربات با کلی پنل های جور با جور برای انواع برنامه ها و کارا مثل اینستا و تلگرام و... اونم بصورت کاملا رایگان !!

💥همین حالا استارت کن تا تو هم پنل مفتی بگیری💥

🔗 T.me/$BotId?start=$chat_id","","");
Send($chat_id,"بنر بالا با لینک اختصاصی مخصوص شماست 🙄

با انتشار بنر بالا برای خود زیرمجموعه جمع کنید و از هر زیرمجموعه 1 امتیاز بگیرید 🌸","HTML",$back);
}
//===========================
elseif($text =="/panel" && $chat_id == $dev){
    Send($chat_id,"به پنل خوش اومدی","HTML",$panel);
}
//===========================
$memberbot = count($user["listusers"]);
if($data =="member" && $chat_id == $dev){
    Edit($chat_id,$mi,"HTML","👀 آمار ربات شما : $memberbot",$backp);
}
//===========================
if($data =="forward" && $chat_id == $dev){
$step["userinfo"]["$chat_id"]["state"]= "forward";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Edit($chat_id,$mi,"HTML","📩 لطفا پیام مورد نظر را بفرستید ...",null);
}
if($step["userinfo"]["$chat_id"]["state"] == "forward" && $data !="backp"){
foreach($user["listusers"] as $userpm){
ForwardMessage($userpm,$dev,$message_id);
}
$step["userinfo"]["$chat_id"]["state"]= "none";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Send($chat_id,"پیام شما با موفقیت فروارد شد ✅","HTML",$panel);
}
//===========================
if($data =="hamegani" && $chat_id == $dev){
$step["userinfo"]["$chat_id"]["state"]= "hamegani";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Edit($chat_id,$mi,"HTML","📩 لطفا پیام مورد نظر را بفرستید ...",null);
}
if($step["userinfo"]["$chat_id"]["state"] == "hamegani" && $data !="backp"){
foreach($user["listusers"] as $userpm){
Edit($userpm,$text,"HTML",null);
}
$step["userinfo"]["$chat_id"]["state"]= "none";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Send($chat_id,"پیام شما با موفقیت ارسال شد ✅","HTML",$panel);
}
$stepT = json_decode(file_get_contents("data/$text.json"),true);
//===========================
if($data =="kam" &&  $chat_id == $dev){
$step["userinfo"]["$chat_id"]["state"]="sharjn";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Send($chat_id,"❎ مقدار امتیاز رو به عدد لاتین وارد کنید ...","HTML",null);
}
if($state =="sharjn" && $data != "backp"){
$step["userinfo"]["$chat_id"]["state"]="sharj3n";
$step["userinfo"]["$chat_id"]["cha2s"]="$text";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Send($chat_id,"❎ ایدی عددی کاربر را وارد کنید ...","HTML",null);
}
if($state =="sharj3n" && $data != "backp"){
$njhaj = $step["userinfo"]["$chat_id"]["cha2s"];
$codsan = $coins -$njhaj;
$stepT["userinfo"]["$text"]["coin"]=$codsan;
$stepT["userinfo"]["$text"]["state"]="none";
$stepT = json_encode($stepT,true);
$step["userinfo"]["$chat_id"]["state"]="none";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
file_put_contents("data/$text.json",$stepT);
Send($chat_id,"با موفقیت کم شد ✅","HTML",$panel);
}
//===========================

if($data =="add" && $chat_id == $dev){
$step["userinfo"]["$chat_id"]["state"]="sharj";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Send($chat_id,"❎ مقدار امتیاز رو به عدد لاتین وارد کنید ...","HTML",null);
}
if($state =="sharj" && $data != "backp"){
$step["userinfo"]["$chat_id"]["state"]="sharj3";
$step["userinfo"]["$chat_id"]["cha2s"]="$text";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
Send($chat_id,"❎ ایدی عددی کاربر را وارد کنید ...","HTML",null);
}
if($state =="sharj3" && $data != "backp"){
$njhaj = $step["userinfo"]["$chat_id"]["cha2s"];
$codsan = $coins +$njhaj;
$stepT["userinfo"]["$text"]["coin"]=$codsan;
$stepT["userinfo"]["$text"]["state"]="none";
$stepT = json_encode($stepT,true);
$step["userinfo"]["$chat_id"]["state"]="none";
$step = json_encode($step,true);
file_put_contents("data/$chat_id.json",$step);
file_put_contents("data/$text.json",$stepT);
Send($chat_id,"با موفقیت اضافه شد ✅","HTML",$panel);
}
unlink("error_log");

?>
