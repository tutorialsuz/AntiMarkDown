<?php
/**
 * @yuborilgan xabarlarni markdown da chiqarib beradi, @pcode uchun
 * @author ShaXzod Jomurodov <shah9409@gmail.com>
 * @contact https://t.me/idFox AND https://t.me/ads_buy
 * @date 8.09.2021 11:32
 */
  if (file_exists("app/sqldb.php")) {
    require("app/sqldb.php");
  } else {
    die('db not fount');
  }
  
  try {
    $sqldb = new Db('data/database.db');
  } catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
	die('db not connect');
  }

function lg($text, $chat_id = false){
global $telegram, $uid;
	if ($uid) {
		$fefefe = "#debug <a href='tg://user?id={$uid}'>$uid</a>: {$text}";
	} else {
		$fefefe = "#debug: {$text}";
	}
    $content = ['chat_id' => '368844346', 'text' => $fefefe, 'parse_mode' => 'html'];
    $telegram->sendMessage($content);
}

function addUser($uid, $vaqt) {
	if (checkUID($uid)) return false;
	global $sqldb;

	$row = $sqldb->query("INSERT INTO members ('user_id', 'reg_date', 'status') VALUES ($uid, '$vaqt', '1')");
	if(!$row){
	  	return false;
	} else {
		return true;
	}
}

function checkUID($uid) {
global $sqldb;

	$row = $sqldb->queryRow("SELECT * FROM members WHERE user_id = $uid");
	if(!$row){
	  return false;
	} else { 
		return true;
	}
}

?>