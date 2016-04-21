<?php  //сохранение в БД инфо об пользователе
header('Content-Type: text/html; charset=utf-8');//собираем всю информацию об защедщих на сайт
//отмечаем что пользователь все еще на сайте


	session_start();
	include_once('../../startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

	//сохранение объекта
	if(isset($_POST['id_ev']) AND isset($_POST['arrObjJson'])){	
		$id_ev = sprintf("%d",$_POST['id_ev']);
		$arrObjJson =  mysql_real_escape_string($_POST['arrObjJson']);
		
		if($id_ev){
			$query = "UPDATE Date_Of_Events  SET map_objects	 = '$arrObjJson' WHERE id =$id_ev";
			echo $query;
			$result = mysql_query($query) or die(mysql_error());
		}
		
	}
	


	//сохранение объекта
	if(isset($_POST['id_ev_get'])){	
		$id_ev_get = sprintf("%d",$_POST['id_ev_get']);
		
		if($id_ev_get){
			$query = "Select map_objects FROM Date_Of_Events  WHERE id =$id_ev_get";
			//echo $query;
			$result = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_assoc($result);
			echo $row[map_objects];
		}
		
	}
	
	
/*

			//$agent =  mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
			//$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
			//$agent =  mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);	
			
			$bt = '';
			if( isBot($bt) ){}
			
			
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


*/	


	
?>