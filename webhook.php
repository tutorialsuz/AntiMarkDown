<?php
/**
 * @yuborilgan xabarlarni markdown da chiqarib beradi, @pcode uchun
 * @author ShaXzod Jomurodov <shah9409@gmail.com>
 * @contact https://t.me/idFox AND https://t.me/ads_buy
 * @date 8.09.2021 11:32
 */

//sozlash
include 'Telegram.php';
include 'app/bot.class.php';
include 'app/config.php';
include 'app/entity_by_ads_buy.php';

$time1 = date("r", time());
$time2 = explode(', ', $time1);
$vaqt = str_replace(' +0500', "", $time2[1]);

$telegram = new Telegram($bot_token);
$efede3 = $telegram->getData();

// $efede3 = json_decode(file_get_contents('file.json'), true);

if(!$efede3) {
    $url = "https://api.telegram.org/bot{$bot_token}/setWebhook?url=https://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}";

    @file_get_contents($url);
    die("webhook o'rnatildi manimcha )");
}

//basic
$text = $efede3["message"]["text"];
if (!$text) {
    $text = $efede3["message"]["caption"];
}
$foto = $efede3["message"]["photo"];
$msg = $efede3["message"]["message_id"];
$sana = $efede3["message"]["date"];
$chat_id = $efede3["message"]["chat"]["id"];
$fileclass = $efede3["message"]["document"]["file_name"];
$file_id = $efede3["message"]["document"]["file_id"];
$documentmsg = $efede3["message"]["document"];


$entities = $efede3["message"]["entities"];
if (!$entities) {
    $entities = $efede3['message']['caption_entities'];
}
// chat
$cfname = $efede3['message']['chat']['first_name'];
$cid = $efede3["message"]["chat"]["id"];
$clast_name = $efede3['message']['chat']['last_name'];
$turi = $efede3["message"]["chat"]["type"];
$username = $efede3['message']['chat']['username'];
$cusername = $efede3['message']['chat']['username'];
$ctitle = $efede3['message']['chat']['title'];

//user info
$ufname = $efede3['message']['from']['first_name'];
$uname = $efede3['message']['from']['last_name'];
$ulogin = $efede3['message']['from']['username'];
$uid = $efede3['message']['from']['id'];
$user_id = $efede3['message']['from']['id'];

//reply info
$sreply = $efede3['message']['reply_to_message']['text'];
$reply_markup = $efede3['message']['reply_markup'];
$forward_from_chat = $efede3['message']['forward_from_chat'];
$forward_from_is_bot = $efede3['message']['forward_from']['is_bot'];

//via_bot info
$via_fname = $efede3['message']['via_bot']['first_name'];
$via_bot = $efede3['message']['via_bot']['is_bot'];
$via_login = $efede3['message']['via_bot']['username'];
$via_id = $efede3['message']['via_bot']['id'];

//new_chat_participant info
$nfname = $efede3['message']['new_chat_participant']['first_name'];
$nbot = $efede3['message']['new_chat_participant']['is_bot'];
$nlogin = $efede3['message']['new_chat_participant']['username'];
$nid = $efede3['message']['new_chat_participant']['id'];

//my_chat_member new
$my_chat_member = $efede3['my_chat_member'];
$new_title = $efede3['my_chat_member']['chat']['title'];
$new_username = $efede3['my_chat_member']['chat']['username'];
$new_id = $efede3['my_chat_member']['chat']['id'];
$new_type = $efede3['my_chat_member']['chat']['type'];

$new_chat_member = $efede3['my_chat_member']['new_chat_member'];

if (!$uid) {
    $uid = $Callback_FromID;
}

if ($efede3['edited_message']) {
    $msg = $efede3["edited_message"]["message_id"];
    $sana = $efede3["edited_message"]["date"];
    $chat_id = $efede3["edited_message"]["chat"]["id"];
    $uid = $efede3['edited_message']['from']['id'];
    $user_id = $efede3['edited_message']['from']['id'];
    $ufname = $efede3['edited_message']['from']['first_name'];
    $turi = $efede3["edited_message"]["chat"]["type"];

    $text = $efede3["edited_message"]["text"];
    if (!$text) {
        $text = $efede3['edited_message']['caption'];
    }

    $entities = $efede3['edited_message']['entities'];
    if (!$entities) {
        $entities = $efede3['edited_message']['caption_entities'];
    }
}

if ($text == "/start") {

    $content = ['chat_id' => $chat_id, 'text' => "Markdown yoki html da qilingan matnni menga forward qiling."];
    $telegram->sendMessage($content);
    exit;
}

if ($entities) {
    $qism = ads_buy::parse($text,$entities);

    $content = ['chat_id' => $chat_id, 'text' => $qism];
    $telegram->sendMessage($content);
}