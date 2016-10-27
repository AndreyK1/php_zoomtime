<?php  //добавление речи из новости в БД
header('Content-Type: text/html; charset=utf-8');//собираем всю информацию об защедщих на сайт
//отмечаем что пользователь все еще на сайте
	session_start();
	include_once('../../startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

	$name =  mysql_real_escape_string($_POST['name']);	
	//echo $name;
	
	
	$id_country =0;
	if(isset($_POST['id_country'])){
		$id_country = sprintf("%d",$_POST['id_country']);		
	}

	$isRuler =0;
	if(isset($_POST['isRuler'])){
		$isRuler = sprintf("%d",$_POST['isRuler']);		
	}


	if($name ==''){
		die('какя-то из POST переменных пустая!!!');
	}


	$Wcount ='';
	if($id_country){ $Wcount = "AND id_country=$id_country";}
	$WisRuler ='';
	if($isRuler){ $WisRuler = "AND isruler=1";}
	//вытаскиваем всех авторов
	//$query = "SELECT * FROM  countrys WHERE country LIKE '$name%' OR country LIKE '% $name%'  ORDER BY country";
	$query = "SELECT * FROM  rulers_subrulers WHERE   name LIKE '$name%'  $Wcount $WisRuler ORDER BY name";
	$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			$arrWho = array();
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				$arrWho[] = $row;
			}
		}
	
	//var_dump($arrWho);
	

		if($arrWho){
			//создаем  json строку
			//$js = "";
			for($i=0;$i<count($arrWho);$i++){
				$arrWho[$i] = $arrWho[$i]['id']."|".$arrWho[$i]['name'];
				$arrWho[$i] = '"'.$arrWho[$i].'"';
			}
			$js = implode(",",$arrWho);
			$js = "[".$js."]";
			echo $js;
		}


?>