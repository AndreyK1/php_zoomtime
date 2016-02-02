<?php  //сохранение в БД инфо об пользователе
header('Content-Type: text/html; charset=utf-8');//собираем всю информацию об защедщих на сайт
//отмечаем что пользователь все еще на сайте
	session_start();
	include_once('../../startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

	
	
	function isBot(&$botname = ''){
	/* Эта функция будет проверять, является ли посетитель роботом поисковой системы */
	  $bots = array(
		'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
		'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
		'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
		'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
		'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
		'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
		'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
		'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
		'Nigma.ru','bing.com','dotnetdotcom'
	  );
	  foreach($bots as $bot)
		if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false){
		  $botname = $bot;
		  return true;
		}
	  return false;
	}	
	
	
	
	
	
	//если мы впервые меняем сесию, для определения бот или нет
	if(isset($_POST['bt'])){
			//$agent =  mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
			//$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
			//$agent =  mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);	
			
			$bt = '';
			if( isBot($bt) ){}
			
			$bt =  mysql_real_escape_string($bt);
			$mm =  mysql_real_escape_string($_POST['mm']);		
			//$id = sprintf("%d",$_POST['id']);
			//$date = date('Y-m-d H:i:s');
			//пока делаем однозначно
			if(($mm >1) AND ($bt =='')){ //если было шевеление мышкой и бота не обнаружено
				$IsBot =  $_SESSION['ISBot'] = "no";
			}else{
				$IsBot =  $_SESSION['ISBot'] = "yes";
			}
			
			$_SESSION['mouse_move'] = $mm;
			
			echo "  mm-".$mm."  ip-".$ip;
			
			//$query = "INSERT INTO Bot_info  (Bot_name,user_agent,mouse_move,ip,IsBot,date) VALUES ('$bt','$agent','$mm','$ip','$IsBot','$date')";	
				//$result = mysql_query($query) or die(mysql_error());	
					$t = "UPDATE Bot_info
					SET	mouse_move	 = '$mm', IsBot = '$IsBot'  WHERE uid_for_bot = '".$_SESSION['uid_for_bot']."'";
					//die($eventStr);
					$result = mysql_query($t);
				//$query = "INSERT INTO Date_Of_Events  (date_Beg,date_End,event) VALUES (STR_TO_DATE('15-8-1990 00:00:00', '%d-%m-%Y %H:%i:%s'),STR_TO_DATE('00-00-0000 00:00:00', '%d-%m-%Y %H:%i:%s'),'Начал писать события1111')";
						//echo $query;
						
			

		/*
			if(($name =='') or ($id =='')){
				die('какая-то из POST переменных пустая!!!');
			}
		*/
	}
	
	
	
	//если мы повторно меняем сесию (т.е. сначала мышка не двигалась, а потом задвигалась)
	if(!isset($_POST['bt'])){
		$mm =  mysql_real_escape_string($_POST['mm']);	
					$_SESSION['mouse_move'] = $mm;
					$zn = $mm." | ".date('Y-m-d H:i:s');
					$t = "UPDATE Bot_info
					SET	mouse_move_latet	 = '$zn' WHERE uid_for_bot = '".$_SESSION['uid_for_bot']."'";
					//die($eventStr);
					$result = mysql_query($t);
					echo "  mm-".$mm;
	}


	


	
?>