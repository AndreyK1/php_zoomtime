<?php  //добавление речи из новости в БД
header('Content-Type: text/html; charset=utf-8');//собираем всю информацию об защедщих на сайт
//отмечаем что пользователь все еще на сайте
	session_start();
	include_once('../../startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

	$name =  mysql_real_escape_string($_POST['proc']);	
	$id = sprintf("%d",$_POST['id']);
	//echo $name;


	if(($name =='') or ($id =='')){
		die('какая-то из POST переменных пустая!!!');
	}


	if($name =='del'){
		//удаляем запись
		$query = "DELETE FROM Date_Of_Events WHERE id = '".$id."' " ;
		$result = mysql_query ($query);
        if ($result) {echo "Дата удалена!";
				$id_theme = mysql_insert_id ();
		}else{
				echo "ОШИБА. Дата не удалена!";
		}
	}
	
	if($name =='edit'){
		//редактируем запись
			$dateBeg =  mysql_real_escape_string($_POST['dateBeg']);	
			$dateEnd =  mysql_real_escape_string($_POST['dateEnd']);	
			$bc_ac  =  mysql_real_escape_string($_POST['bc_ac']);
	
			
			$event = str_replace(array("\r\n", "\r", "\n"), '', $_POST['event']); 
			//$event = str_replace(array('"',"'"),'\"', $event); 
			$event = str_replace( "'", "`", $event);
			$event = str_replace('"','“',$event); 
			$event = str_replace('	',' ',$event); 
			
			$event =  mysql_real_escape_string($event);
			
			echo "dateBeg-".$dateBeg;
			
			if(($name =='') or ($id =='')){
				die('какая-то из POST 2 переменных пустая!!!');
			}	
					//преобразуем даты
					$tB = explode("-",$dateBeg); $dateBeg = $tB[2]."-".$tB[1]."-".$tB[0]." 00:00:00";	
					
					$tB = explode("-",$dateEnd); $dateEnd = $tB[2]."-".$tB[1]."-".$tB[0]." 00:00:00";	
					//$time = new DateTime($tB);
					//$dateBeg = $time->format('Y-m-d 00:00:00');	
					
					$tB = explode("-",$dateEnd); $tB = $tB[2]."-".$tB[1]."-".$tB[0];			
					//$time = new DateTime($tB);
					//$dateEnd = $time->format('Y-m-d 00:00:00');	
					
				/*		
					$time = DateTime::createFromFormat('d-m-Y', $dateBeg);
					//new DateTime($dateBeg);
					$dateBeg = $time->format('Y-m-d');	
					
					$time = new DateTime($dateEnd);
					$dateBeg = $time->format('Y-m-d');	
*/

				$t = "UPDATE Date_Of_Events
				SET	date_Beg = '".$dateBeg."', date_End = '".$dateEnd."', event= '".$event."', bс_ac='".$bc_ac."'  WHERE id = '".$id."' " ;
				//die($eventStr);
				echo $t;
				$result = mysql_query($t);
				if ($result) {
					echo "Дата отредактирована!";
				}else{
						echo "ОШИБА. Дата не отредактирована!";
				}
			
	}
?>