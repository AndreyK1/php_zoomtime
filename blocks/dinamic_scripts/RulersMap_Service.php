<?php  //добавление речи из новости в БД
header('Content-Type: text/html; charset=utf-8');//собираем всю информацию об защедщих на сайт
//отмечаем что пользователь все еще на сайте
	session_start();
	include_once('../../startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

	
//id_rul:id_rul,who:who,where:where,id_country:id_country,period:period

	$id_country =0;
	if(isset($_POST['id_country'])){
		$id_country = sprintf("%d",$_POST['id_country']);		
	}

	$id_rul =0;
	if(isset($_POST['id_rul'])){
		$id_rul = sprintf("%d",$_POST['id_rul']);		
	}

	$who =  mysql_real_escape_string($_POST['who']);
	$where =  mysql_real_escape_string($_POST['where']);	
	$period =  mysql_real_escape_string($_POST['period']);	


	if( $who=='' or $where==''){
		die('1 какя-то из POST переменных пустая!!!');
	}

	if($id_country){//значит ищем в стране по периоду
		if($period==''){
			die('2 какя-то из POST переменных пустая!!!');
		}
		$arrPeriod  = explode(" ", $period);
		$query = "SELECT id,name,titul,foto,date_begRule,date_endRule,date_born,date_die	 FROM  rulers_subrulers WHERE   id_country = $id_country AND isruler=1 AND 
			(
				(date_begRule<STR_TO_DATE('".$arrPeriod[0]."','%d-%m-%Y') AND date_endRule>STR_TO_DATE('".$arrPeriod[0]."','%d-%m-%Y'))   
				OR (date_begRule<STR_TO_DATE('".$arrPeriod[1]."','%d-%m-%Y') AND date_endRule>STR_TO_DATE('".$arrPeriod[1]."','%d-%m-%Y')) 
				OR  (date_begRule>STR_TO_DATE('".$arrPeriod[0]."','%d-%m-%Y')  AND date_endRule<STR_TO_DATE('".$arrPeriod[1]."','%d-%m-%Y') )
			) ORDER BY date_begRule";
			//echo $query;
			$result = mysql_query($query) or die(mysql_error());
			$n = mysql_num_rows($result);
			if($n >0){
				$arrWho = array();
				for ($i = 0; $i < $n; $i++)
				{
					$row = mysql_fetch_assoc($result);		
					$arrWho[] = $row;
				}

				echo json_encode(StructuringArrayPeriod($arrWho));
			}


	}

	function StructuringArrayPeriod($arr){
		$arrStr = array();
		for ($i=0; $i <count($arr) ; $i++) { 
			$arrIn = array();
			$arrIn[]=$arr[$i]['id'];
			$arrIn[]=$arr[$i]['name'];
			$arrIn[]=$arr[$i]['titul']." : ".$arr[$i]['date_begRule']." ".$arr[$i]['date_endRule'];
			$arrIn[]=$arr[$i]['date_born']." ".$arr[$i]['date_die'];
			$arrIn[]=$arr[$i]['foto'];
			$arrIn[]=array();
			if(count($arrStr)==0){
				$arrStr[] = $arrIn;
			}else{
				$arrStr[0] = insertInLastArray($arrStr[0],$arrIn);
			}
		}
		return $arrStr;
	}

	function insertInLastArray($arrOut,$arrIn){
		if(count($arrOut[count($arrOut)-1])>0){
			$arrOut[count($arrOut)-1][0] = insertInLastArray($arrOut[count($arrOut)-1][0],$arrIn);
		}else{ 
			$arrOut[count($arrOut)-1][] = $arrIn;
		}

		return $arrOut;
	}


	if($id_rul){//значит ищем по персоне
		if($who=='poddan'){//если ищем подданых то просто вытаскиваем их
			$query = "
			SELECT id,name,titul,foto,date_begRule,date_endRule,date_born,date_die FROM  rulers_subrulers WHERE id=$id_rul
			UNION
			SELECT id,name,titul,foto,date_begRule,date_endRule,date_born,date_die FROM  rulers_subrulers WHERE
			id_ruler=$id_rul";
		//echo $query;
			$result = mysql_query($query);// or die(mysql_error());
			$n = mysql_num_rows($result);
			if($n >0){
				$arrWho = array();
				for ($i = 0; $i < $n; $i++)
				{
					$row = mysql_fetch_assoc($result);		
					$arrWho[] = $row;
				}

				if(count($arrWho)<2){
					echo "[]";
				}else{
					echo json_encode(StructuringArrayPoddan($arrWho));
				}
			}

		}elseif($who=='rull'){
			//echo "shit";
			if($where =='first'){//список заканчивающийся этой персоной
				//достаем двух предыдущих правителей в стране
				$query = "SELECT  id,name,titul,foto,date_begRule,date_endRule,date_born,date_die FROM  rulers_subrulers WHERE isruler=1 AND id_country= (SELECT id_country FROM  rulers_subrulers WHERE id=$id_rul) AND date_begRule< (SELECT date_begRule FROM  rulers_subrulers WHERE id=$id_rul) ORDER BY 	date_begRule DESC LIMIT 2 ";
							//echo $query;
				
				$arrWho = array();
				$result = mysql_query($query) or die(mysql_error());
				$n = mysql_num_rows($result);
				if($n >0){
					
					for ($i = 0; $i < $n; $i++)
					{
						$row = mysql_fetch_assoc($result);		
						$arrWho[] = $row;
					}
				}

				$arrWho = array_reverse($arrWho);

				//достаем самого правителя
				$query ="SELECT id,name,titul,foto,date_begRule,date_endRule,date_born,date_die FROM  rulers_subrulers WHERE id=$id_rul"; 
				$result = mysql_query($query) or die(mysql_error());
				$n = mysql_num_rows($result);
				if($n >0){
					for ($i = 0; $i < $n; $i++)
					{
						$row = mysql_fetch_assoc($result);		
						$arrWho[] = $row;
					}
				}
				if(count($arrWho)<2){
					echo "[]";
				}else{
					echo json_encode(StructuringArrayPeriod($arrWho));
				}

			}elseif($where=='last'){//список начинающийся с этой персоны
				
				$arrWho = array();
				//достаем самого правителя
				$query ="SELECT id,name,titul,foto,date_begRule,date_endRule,date_born,date_die FROM  rulers_subrulers WHERE id=$id_rul"; 
				$result = mysql_query($query) or die(mysql_error());
				$n = mysql_num_rows($result);
				if($n >0){
					for ($i = 0; $i < $n; $i++)
					{
						$row = mysql_fetch_assoc($result);		
						$arrWho[] = $row;
					}
				}

				//достаем двух следующих правителей в стране
				$query = "SELECT  id,name,titul,foto,date_begRule,date_endRule,date_born,date_die FROM  rulers_subrulers WHERE isruler=1 AND id_country= (SELECT id_country FROM  rulers_subrulers WHERE id=$id_rul) AND date_begRule> (SELECT date_begRule FROM  rulers_subrulers WHERE id=$id_rul) ORDER BY 	date_begRule  LIMIT 2 ";
				$result = mysql_query($query) or die(mysql_error());
				$n = mysql_num_rows($result);
				if($n >0){
					
					for ($i = 0; $i < $n; $i++)
					{
						$row = mysql_fetch_assoc($result);		
						$arrWho[] = $row;
					}
				}
				
				if(count($arrWho)<2){
					echo "[]";
				}else{
					echo json_encode(StructuringArrayPeriod($arrWho));
				}
			}

		}elseif($who=='relatives'){
			if($where =='last'){//список заканчивающийся этой персоной
				$arrWho = array();
				//достаем самого правителя
				$query ="SELECT id,name,titul,foto,date_begRule,date_endRule,date_born,date_die FROM  rulers_subrulers WHERE id=$id_rul
						UNION
					SELECT id,name,titul,foto,date_begRule,date_endRule,date_born,date_die FROM  rulers_subrulers WHERE
					id_ancestor=$id_rul";

				$result = mysql_query($query) or die(mysql_error());
				$n = mysql_num_rows($result);
				if($n >0){
					for ($i = 0; $i < $n; $i++)
					{
						$row = mysql_fetch_assoc($result);		
						$arrWho[] = $row;
					}
				}

				if(count($arrWho)<2){
					echo "[]";
				}else{
					echo json_encode(StructuringArrayPoddan($arrWho));
				}
				//echo json_encode(StructuringArrayPeriod($arrWho));	

			}elseif($where =='first'){//список заканчивающийся этой персоной
					$query ="SELECT id,name,titul,foto,date_begRule,date_endRule,date_born,date_die FROM  rulers_subrulers WHERE id=(SELECT id_ancestor FROM  rulers_subrulers WHERE id=$id_rul)
					UNION
					SELECT id,name,titul,foto,date_begRule,date_endRule,date_born,date_die FROM  rulers_subrulers WHERE id_ancestor= (SELECT id_ancestor FROM  rulers_subrulers WHERE id=$id_rul) AND id_ancestor<>0";	

					$result = mysql_query($query) or die(mysql_error());
					$n = mysql_num_rows($result);
					if($n >0){
						for ($i = 0; $i < $n; $i++)
						{
							$row = mysql_fetch_assoc($result);		
							$arrWho[] = $row;
						}
					}

					if(count($arrWho)<2){
						echo "[]";
					}else{
						echo json_encode(StructuringArrayPoddan($arrWho));
					}	
			}
		}



	}

	function StructuringArrayPoddan($arr){
		$arrStr = array();
		for ($i=0; $i <count($arr) ; $i++) { 
			$arrIn = array();
			$arrIn[]=$arr[$i]['id'];
			$arrIn[]=$arr[$i]['name'];
			$arrIn[]=$arr[$i]['titul']." : ".$arr[$i]['date_begRule']." ".$arr[$i]['date_endRule'];
			$arrIn[]=$arr[$i]['date_born']." ".$arr[$i]['date_die'];
			$arrIn[]=$arr[$i]['foto'];
			$arrIn[]=array();
			if(count($arrStr)==0){
				$arrStr[] = $arrIn;
			}else{
				$arrStr[0][count($arrStr[0])-1][] = $arrIn;
			}
		}
		return $arrStr;
	}






die();

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