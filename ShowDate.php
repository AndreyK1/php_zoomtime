<?header('Content-Type: text/html; charset=utf-8');
//работа с датами

//вход под дмином
session_start();

include_once('startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

?>

<?
//прооверяем, что мы админ
include('variables.php');
if($_SESSION['Guest_id']['id_user'] == $AdminID){ 
	 echo "Вы Админ продолжаем дальше<br /><br />";	
}else{
	//echo 'Вы не Админ!!!';
	//die();
}


?>




<?

//показ даты
if(isset($_GET['id_date'])){

	$id_date = sprintf("%d", $_GET['id_date']);
	
	$query = "SELECT * FROM Date_Of_Events WHERE id = '".$id_date."' " ;
	$result = mysql_query ($query);
        if ($result) {
//echo "<p>Дата есть!</p>";			
			//$id_theme = mysql_insert_id ();
			$row = mysql_fetch_assoc($result);	
			$rowN = 	$row;
			$event = $row['event'];
			
			$sobit = $row['date_Beg'].":".$row['date_End']."|||".$row['event']."<br /> ";
			
			
			//достаем темы
			if($row['ids_Theme'] != ''){
				$arrTh = explode("|",$row['ids_Theme']);
				$arrTh = array_diff($arrTh, array(''));
				if(count($arrTh) >0){
					$arrTh = implode("','",$arrTh);
					$arrTh = "'".$arrTh."'";
					//echo "strIn-".$strIn."<br><br>";
					$query = "SELECT * FROM  Theme_of_Events WHERE id in ($arrTh) ORDER BY id"  ;		
					$result = mysql_query($query) or die(mysql_error());
					$n = mysql_num_rows($result);
						$arrEv = array();
					if($n >0){
						for ($i = 0; $i < $n; $i++)
						{
							$row = mysql_fetch_assoc($result);	
							$arrEv[] = $row;
						}
						
					}
				}
				

				
			}	
				//проверяем по заглавным, есть ли похожие события
				  //первую букву в нижний регистр
				  $str = $event;
				  $char=mb_strtolower(substr($str,0,2),"utf-8"); // это первый символ
				  $str[0]=$char[0];
				  $str[1]=$char[1];
					
		//			$str = "нужно найти слова, таким образом в Армению или другим. Короче надо Франция найти Авиаций.";					
					
		//			echo "<br />".$str."<br />";
					
					//разбираем на слова
						//заменяем ограничивающие знаки
						$arrRep = array('.','!','?','"');
						$str = str_replace($arrRep,'', $str);
						
						$arrWords = explode(" ",$str);
					
			//	var_dump($arrWords);	echo "<br /><br />";
						
					//и ишем с слова С заглавной буквы
					$arrUpWords = array();
					for($i=0;$i<count($arrWords);$i++){
						if((bool)preg_match('/^[А-Я]{1}[а-я]{3,20}$/u',$arrWords[$i])){
							$n = mb_strlen($arrWords[$i],'UTF-8');
							$wordd = $arrWords[$i];
							if($n>6){
								//$wordd = $arrWords[$i];
				//				echo $wordd."<br />";
								$wordd = mb_substr($wordd,0,$n-2,'UTF-8');
								$wordd= $wordd; //."__";
								$arrUpWords[] = $wordd;
							}elseif($n>4){
								//$wordd = $arrWords[$i];
				//				echo $wordd."<br />";
								$wordd = mb_substr($wordd,0,$n-1,'UTF-8');
								$wordd= $wordd; //."_";
								$arrUpWords[] = $wordd;							
							}
						}
					}					
						
			//			var_dump($arrUpWords);
					
					$arrEvents = array();
					//пробегаемся по массиву и ищем похожие события
					if(count($arrUpWords)>0){
						foreach($arrUpWords as $w){
							$w = mb_strtoupper($w,'UTF-8');
							$query = "SELECT * FROM Date_Of_Events WHERE UPPER(event) LIKE '%$w%' LIMIT 3 ";
					//		echo $query."<br>";;
							$result = mysql_query($query);
							$n = mysql_num_rows($result);
							if($n >0){
								for ($i = 0; $i < $n; $i++)
								{
						//			echo "456 ";
									$row = mysql_fetch_assoc($result);	
						//			echo $row['id'];
									//global $arrEvents;
									if($row['id'] != $_GET['id_date']){
							//			echo "11111 ";
										$arrEvents[] = $row;
									}
									//var_dump($row);
								}
							}
						}
					
					}
						
						//$arrEvents = 
						array_unique($arrEvents);
				//		echo "<br><br>"; var_dump($arrEvents);					
					
					
		}
}
?>



<?
//var_dump($rowN);
	$MonthArr = array('','янв','фев','мар','апр','май','июн','июл','авг','сен','окт','ноя','дек');
	$ddB = explode(" ",$rowN['date_Beg']);
	$ddB = explode("-",$ddB[0]); $ddTiB = ''; if($ddB[2] !='00'){$ddTiB.=$ddB[2]." ";} if($ddB[1] !='00'){$ddTiB.=$MonthArr[(int)$ddB[1]]." ";} if($ddB[0] !='0000'){$ddTiB.=$ddB[0];}
	
	$ddE = explode(" ",$rowN['date_End']);
	$ddE = explode("-",$ddE[0]); $ddE1 = $ddE[2]."-".$ddE[1]."-".$ddE[0]; $ddTiE = ''; if($ddE[2] !='00'){$ddTiE.=$ddE[2]." ";} if($ddE[1] !='00'){$ddTiE.=$MonthArr[(int)$ddE[1]]." ";} if($ddE[0] !='0000'){$ddTiE.=$ddE[0];}
?>

<html>
<head>
<?if(isset($_GET['EvBy10'])){?>
		<title>Список событий и дат в мировой истории</title>
<?}else{?>
		<title><?=$ddTiB?>  - <?=$ddTiE?> : <?=$rowN['event']?></title>
<?}?> 
	
</head>	
<body style="position:relative; " >



<?	

	if(!isset($_GET['EvBy10'])){
		if($ddTiB !=''){ echo " <br />Дата начала начала события : <b>".$ddTiB."</b>"; }
		//echo "--".$ddTiE."--";
		if($ddTiE !=''){ echo " <br />Дата окончания события : <b>".$ddTiE."</b>"; }
		echo "<br />Событие :".$rowN['event']."<br /> ";
	}
//echo $rowN['date_Beg'].":".$rowN['date_End']."|||".$rowN['event']."<br /> ";


				//выводим темы
				if(count($arrEv)>0){
					echo "<br /><br /><b>Темы к которым относится событие:</b><br /><hr />";
					
					foreach($arrEv as $th){
					?>
					* <a href='index.php?id_theme=<?=$th['id']?>' target='blank' ><?=$th['Theme']?></a><br />
					<?
					}
				}

				
				if(count($arrEvents)>0){
						echo "<hr /><br /><p><b>Похожие события</b></p>";
						$k = 0;
						foreach($arrEvents as $e){						
						$k++; if( $k>8){ break;}
						?>
							<a href='ShowDate.php?id_date=<?=$e['id']?>'  ><?=$e['date_Beg']?> : <?=$e['date_End']?> - <?=$e['event']?></a><br />
						<?
						}
					}

//показ дат группами по 10
if(isset($_GET['EvBy10'])){
	$query = " SELECT id,date_Beg,date_End FROM Date_Of_Events ORDER BY date_Beg ";	
			$result = mysql_query($query);// or die(mysql_error());
			//return mysql_fetch_assoc($result);
			if (!$result)
				die(mysql_error().$query);
			
			// Извлечение из БД.
			$n = mysql_num_rows($result);
			$arr = array();
				
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				$arr[] = $row;
			}
		
		//var_dump($arr);
		//$UrlOfServer = 'http://ZoomTime.ru';
		$k=0;
		$dB='';
		$dE='';
		//$arrRet = array();

		echo "htghfgh";

	for($i=0; $i<count($arr); $i++){
			$k++; if($k>10){ $k=1; $lastDateEv = $arr[$i]['date_of_add']; }
			if($lastDateEv > $arr[$i]['date_of_add']){ $lastDateEv = $arr[$i]['date_of_add']; }	//находим последнюю дату
			if($k==1){ $dB=$arr[$i]['date_Beg']; $dE=$arr[$i]['date_Beg']; }//если это первый номер из пяти, то записываем его id (речи)	
				if($arr[$i]['date_Beg'] > $dE){
					$dE=$arr[$i]['date_Beg'];
				}			
			if($k==10){ //если это последний номер из пяти, то записываем его id (речи) и pfgbcsdftv ccskre
				//$dB=$arr[$i]['date_Beg'];

				$dB = explode(" ",$dB); $dB = $dB[0];
				$dE = explode(" ",$dE); $dE = $dE[0];
				
				if($dE < $dB){$dE = $dB;}
				$IdSBEnd = $dB."|".$dE;
				?>
				<a href='index.php?dBEnd=<?=$IdSBEnd?>'  >События и даты в период с <?=$dB?> по <?=$dE?></a><br />
				<?
				//echo $UrlOfServer.'/index.php?dBEnd='.$IdSBEnd."<br />"; //soobsh&id_soobsh=23
				//время берем последнее для темы
				//$time = explode(" ",$lastDateEv);
				//$arrRet[$i]['lastmod']	= $time['0'];
				//$arrRet[$i]['changefreq']	= 'daily';
				//$arrRet[$i]['priority']	= '0.8';
			}
		}		

/*
	for($i=0; $i<count($arr); $i++){
			$k++; if($k>10){ $k=1; $lastDateEv = $arr[$i]['date_of_add']; }
			if($lastDateEv > $arr[$i]['date_of_add']){ $lastDateEv = $arr[$i]['date_of_add']; }	//находим последнюю дату
			if($k==1){ $dB=$arr[$i]['date_Beg']; $dE=$arr[$i]['date_End']; }//если это первый номер из пяти, то записываем его id (речи)	
				if($arr[$i]['date_End'] > $dE){
					$dE=$arr[$i]['date_End'];
				}			
			if($k==10){ //если это последний номер из пяти, то записываем его id (речи) и pfgbcsdftv ccskre
				//$dB=$arr[$i]['date_Beg'];

				$dB = explode(" ",$dB); $dB = $dB[0];
				$dE = explode(" ",$dE); $dE = $dE[0];
				
				if($dE < $dB){$dE = $dB;}
				$IdSBEnd = $dB."|".$dE;
				?>
				<a href='index.php?dBEnd=<?=$IdSBEnd?>'  >События и даты в период с <?=$dB?> по <?=$dE?></a><br />
				<?
				//echo $UrlOfServer.'/index.php?dBEnd='.$IdSBEnd."<br />"; //soobsh&id_soobsh=23
				//время берем последнее для темы
				//$time = explode(" ",$lastDateEv);
				//$arrRet[$i]['lastmod']	= $time['0'];
				//$arrRet[$i]['changefreq']	= 'daily';
				//$arrRet[$i]['priority']	= '0.8';
			}
		}
*/			

	
}
?>
</body>
</html>