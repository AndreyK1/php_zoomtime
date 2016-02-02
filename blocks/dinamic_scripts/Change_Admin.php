<?php  //добавление речи из новости в БД
header('Content-Type: text/html; charset=utf-8');//собираем всю информацию об защедщих на сайт
//отмечаем что пользователь все еще на сайте
	session_start();
	include_once('../../startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

	$name =  mysql_real_escape_string($_POST['name']);	
	$val1 = sprintf("%d",$_POST['val1']);
	$val2 = sprintf("%d",$_POST['val2']);
	//echo $name;


	if(($name =='') or ($val1 =='') or ($val2 =='')){
		die('какая-то из POST переменных пустая!!!');
	}


	
	if($name =='ChangeCategory'){
		//изменить категорию
		//echo "меняем категорию для события $val1 на значение $val2";
		
				$t = "UPDATE Date_Of_Events
				SET	category = '".$val2."'  WHERE id = '".$val1."' " ;
				//die($eventStr);
				//echo $t;
				$result = mysql_query($t);
        if ($result) {echo "Категория изменена!";
				$id_theme = mysql_insert_id ();
		}else{
				//echo "ОШИБА. Категория не изменена!";
		}
	}	


	if($name =='DelCountry'){
		//elfkbnm cnhfye
		//echo "DelCountry для события $val1 на значение $val2";
		
		//удаляем страну из соотношение дата страна
			$query = "DELETE FROM Date_vs_country WHERE id_date ='$val1' AND id_country='$val2'";
			$result = mysql_query($query);		
			
			//вытаскиваем ids_country
			$query = "SELECT ids_country FROM Date_Of_Events WHERE id=$val1";
			$result = mysql_query($query) or die(mysql_error());
			$n = mysql_num_rows($result);
			if($n >0){
				$row = mysql_fetch_assoc($result);
				//вытаскиваем страны
				$ids_country = explode("|",$row['ids_country']);
				//удаляем повторяюшиеся
				$ids_country = array_unique($ids_country);	
				//удаляем пустые и требуемый
				$ids_country = array_diff($ids_country, array('','0',$val2));
				
				//собираем назад 
				$ids_country = implode("|",$ids_country);
				$t = "UPDATE Date_Of_Events
				SET	ids_country = '$ids_country'
				WHERE id = '".$val1."'";
				//die($eventStr);
				$result = mysql_query($t);	
			}

	}	
	
	
	if($name =='InsertCountry'){
		//изменить категорию
		//echo "InsertCountry для события $val1 на значение $val2";
			//добавляем страну в соотношение дата страна
			$query = "INSERT INTO Date_vs_country  (id_date,id_country) VALUES ('$val1','$val2')";
			$result = mysql_query($query);	
			if($result){ //если вставилось, то добавляем страну в конце
				//echo "<br />вставляем новую страну и тему<br />";
				$t = "UPDATE Date_Of_Events
				SET	ids_country = CONCAT_WS('|',ids_country,'".$val2."') 
				WHERE id = '".$val1."'";
				//die($eventStr);
				$result = mysql_query($t);		
			}else{$id_date=0;}	
		
	}
	
	

?>