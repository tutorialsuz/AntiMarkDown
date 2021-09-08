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

$telegram = new Telegram($bot_token);
$efede3 = $telegram->getData();

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

$chat_id = $efede3["message"]["chat"]["id"];


$entities = $efede3["message"]["entities"];
if (!$entities) {
    $entities = $efede3['message']['caption_entities'];
}


if ($efede3['edited_message']) {
    $chat_id = $efede3["edited_message"]["chat"]["id"];
   
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