<?php  //сохранение в БД инфо об пользователе
header('Content-Type: text/html; charset=utf-8');//собираем всю информацию об защедщих на сайт
//отмечаем что пользователь все еще на сайте


	session_start();
	include_once('../../startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

	//сохранение картигнки/карты к дате
	if(isset($_POST['id_ev']) AND isset($_POST['mapPict'])){	
		$id_ev = sprintf("%d",$_POST['id_ev']);
		$mapPict =  mysql_real_escape_string($_POST['mapPict']);
		$mapPict = str_replace('MapPicture/','',$mapPict);
		
		if($id_ev){
			$query = "UPDATE Date_Of_Events  SET mapPict = '$mapPict' WHERE id =$id_ev";
			echo $query;
			$result = mysql_query($query) or die(mysql_error());
		}
		
	}
	
	
	//очистка карты и  картигнки/карты у даты
	if(isset($_POST['id_ev']) AND isset($_POST['clearMap'])){	
		$id_ev = sprintf("%d",$_POST['id_ev']);
		//$mapPict =  mysql_real_escape_string($_POST['mapPict']);
		
		if($id_ev){
			$query = "UPDATE Date_Of_Events  SET mapPict = '' , map_objects = ''  WHERE id =$id_ev";
			echo $query;
			$result = mysql_query($query) or die(mysql_error());
		}
		
	}
	
/*

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
	*/
	

	
?>