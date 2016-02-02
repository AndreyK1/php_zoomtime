<?header('Content-Type: text/html; charset=utf-8');
//вход под дмином
session_start();

die();


include_once('startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();
	
	
	//скрипт, который всем датам создает соответствие по странам
	for($i=1; $i<9600; $i++){
		$query = "SELECT ids_country FROM Date_Of_Events WHERE id=$i";
		$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
			if($n >0){
				$row = mysql_fetch_assoc($result);
				$ids_country = $row['ids_country'];
				echo "<br/>".$id_date;
				
				//
				if($ids_country>0){
					$query = "INSERT INTO Date_vs_country  (id_date,id_country) VALUES ('$i',	'$ids_country')";
					$result = mysql_query($query);		
				}
			}
	}



?>