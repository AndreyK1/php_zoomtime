<?  
	header('Content-Type: text/html; charset=utf-8');//собираем всю информацию об защедщих на сайт

	
session_start();

//echo $_SERVER['DOCUMENT_ROOT'];

if(isset($_GET['bot'])){ //переключение режима бот/пользователь
	$_SESSION['ISBot'] = $_GET['bot'];
	header("location:index.php");
}


echo "<!DOCTYPE html>";
include_once('startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

//прооверяем, что мы админ
include('variables.php');


?>

<?
$IsRedactor = 0;
if($_SESSION['Guest_id']['id_user'] == $AdminID){
		
$IsRedactor = 1; //являетсяли пользователь редактором карт
	
	 echo "Вы Админ продолжаем дальше<br />";	
	 ?>
		<br/><br/><br/><br/><br/><br/><a href='AddDates.php' >Добавление дат<a/><br />
		<a href='AddRulers.php' >Добавление правителей<a/><br />
		<a href='blocks/dinamic_scripts/Change_Event.php' >Перепись событий<a/><br />
		<a href='AddThemaForDates.php' >Добавление тем в группе дат<a/><br />
		<a href='index.php?bot=yes' >Режим бота<a/><br />
		<a href='index.php?bot=no' >Режим пользователя<a/><br />
		<a href='TitleNews_inBD.php' >Грабить новости<a/><br />
        <a href='index.php?exit=yes' >Выйти<a/><br />
		<br />
	 <?
}else{
//echo 'Вы не Админ!!!';
}



if(isset($_GET['exit'])){
    $_SESSION['Guest_id']['id_user'] = 0;
    $IsRedactor = 0;
}


$titleStr = '';

?>

<script> 
 var IsRedactor = <?=$IsRedactor?>;
 console.log('IsRedactor-'+IsRedactor);
 
 var CurrentEnentNum = 0;//текущий номер новости
 var arrObjToBD = {};//массив 
</script>



<?
/*
if(isset($_GET['ThisWeek'])){
	//значит показываем новости на этой неделе
	//ThisWeek&scale=10
	echo "Новости за неделю";
	$_SESSION['NewsUsage'] = 1;
	//$time = new DateTime('now');
	
	$time = new DateTime('10-11-2014');
	$Date_e = $time->format('d-m-Y');	
	
	$newtime = $time->modify('-1 week')->format('d-m-Y');
	$Date_b = $newtime;	
	//$Date_e = date("d-m-Y");
	
	$_SESSION['Date_b'] = $Date_b;
	$_SESSION['Date_e'] = $Date_e;
	echo "Новости за '11ne неделю с ".$_SESSION['Date_b']." по ".$_SESSION['Date_e'];
}
*/

/*
if(isset($_GET['ThisWeek'])){
	//значит показываем новости на этой неделе
	//ThisWeek&scale=10
	echo "Новости за неделю";
	$_SESSION['NewsUsage'] = 1;
	$time = new DateTime('now');
	$newtime = $time->modify('-1 week')->format('d-m-Y');
	$Date_b = $newtime;	
	$Date_e = date("d-m-Y");
	
	$_SESSION['Date_b'] = $Date_b;
	$_SESSION['Date_e'] = $Date_e;
}
*/



$onlyNews = 0; //только новости
//echo "onlyNews-".$_GET['onlyNews']."<br />";
if(isset($_SESSION['onlyNews'])){
		$onlyNews =  $_SESSION['onlyNews'];
}
if(isset($_GET['onlyNews'])){
	//echo "onlyNews-".$_GET['onlyNews'];
		if($_GET['onlyNews']=='1'){
	//		echo "ON";
			EraseSession();
			$onlyNews = $_SESSION['onlyNews']='1';
			//выставляем день от сегодня
			$time = new DateTime('now');
			$newtime = $time->modify('-1 week')->format('d-m-Y');
		
			$Date_b =$_SESSION['Date_b'] = $newtime;
			$Date_e = $_SESSION['Date_e'] = date("d-m-Y");
			
		}else{
		//	echo "NOON";
			$onlyNews = $_SESSION['onlyNews']='0';
		}
}
//echo "onlyNews-".$onlyNews;



if(isset($_GET['dBEnd'])){//если перешли по сайт мапу
	$dBEnd=  mysql_real_escape_string($_GET['dBEnd']);
	$dd = explode("|",$dBEnd);
	if(count($dd)>1){	
	}else{die("косяк с датами!");}

	//echo $_GET['dBEnd'];
	$MonthArr = array('','янв','фев','мар','апр','май','июн','июл','авг','сен','окт','ноя','дек');
	$ddB = explode("-",$dd[0]); $ddB1 = $ddB[2]."-".$ddB[1]."-".$ddB[0]; $ddTiB = ''; if($ddB[2] !='00'){$ddTiB.=$ddB[2]." ";} if($ddB[1] !='00'){$ddTiB.=$MonthArr[(int)$ddB[1]]." ";} if($ddB[0] !='0000'){$ddTiB.=$ddB[0];}
	$ddE = explode("-",$dd[1]); $ddE1 = $ddE[2]."-".$ddE[1]."-".$ddE[0]; $ddTiE = ''; if($ddE[2] !='00'){$ddTiE.=$ddE[2]." ";} if($ddE[1] !='00'){$ddTiE.=$MonthArr[(int)$ddE[1]]." ";} if($ddE[0] !='0000'){$ddTiE.=$ddE[0];}
	$Date_b =  $_SESSION['Date_b'] = $ddB1;
	$Date_e =  $_SESSION['Date_e'] = $ddE1;
	//echo "Date_b -".$Date_b." Date_e-".$Date_e;
	
	//делаем титульник
	if($ddTiB !=''){$ddTiB = "c ".$ddTiB." года"; }
	if($ddTiE !=''){$ddTiB .= " по ".$ddTiE." год"; }
	if($ddTiB !=''){ $titleStr = "Все основные даты и события в истории в период ".$ddTiB; }
	
		$_SESSION['id_theme'] = null;
	
}




if(isset($_GET['id_theme'])){//номер темы событий
	$onlyNews = $_SESSION['onlyNews']='0';
	$_GET['id_theme']=  mysql_real_escape_string($_GET['id_theme']);
	$_SESSION['id_theme'] = $_GET['id_theme'];	
	$_SESSION['KeyWords'] = '';
	
	
}

if(isset($_GET['WeekFrom'])){
	//значит показываем новости за неделе с указаной даты
	//ThisWeek&scale=10
	//echo "Новости за неделю с ";
	//$_SESSION['NewsUsage'] = 1;
	$_GET['WeekFrom']=  mysql_real_escape_string($_GET['WeekFrom']);
	$time = new DateTime($_GET['WeekFrom']);
	$timeN = $time->format('d-m-Y');
	$newtime = $time->modify('+1 week')->format('d-m-Y');
	$Date_b = $timeN;	
	$Date_e = $newtime;
	$_SESSION['Date_b'] = $Date_b;
	$_SESSION['Date_e'] = $Date_e;
	
	echo "Новости за неделю с ".$Date_b." по ".$Date_e."<br />";
}


if(isset($_POST['eraseFilter']) or isset($_GET['eraseFilter'])){
	EraseSession();
}

function EraseSession(){
	$_SESSION['eventN'] = null;
	$_SESSION['id_theme'] = null;
	
	//выставляем один день год вперед
	$time = new DateTime($_GET['WeekFrom']);
	//$timeN = $time->format('d-m-Y');
	$newtime = $time->modify('+1 year')->format('d-m-Y');
	$_SESSION['Date_b'] = $newtime;
	$_SESSION['Date_e'] = $newtime;
	$Date_b = $newtime;	
	$Date_e = $newtime;

	//$_SESSION['Date_b'] = null;
	//$_SESSION['Date_e'] = null;
	$_SESSION['KeyWords'] = null;
	$_SESSION['included'] = null;
	$_SESSION['DateNoInTheme'] = null;
	$_SESSION['onlyNews'] = '0';



}

$id_theme = ''; //номер темы
if(isset($_SESSION['id_theme'])){
		$id_theme =  $_SESSION['id_theme'];
}




/*
if(isset($_POST['eventN'])){
	$eventN =  mysql_real_escape_string($_POST['eventN']);
	$_SESSION['eventN'] = $eventN;
}
*/
//echo "eventN-".$eventN."<br/>";
/*
if($eventN !=''){
	$arrNEv = explode('|',$eventN);
	$arrNEv = array_diff($arrNEv, array(''));
	$strIn = '';
	$hArr = Array();
	foreach($arrNEv as $evt){
		$id_ev = sprintf("%d",$evt);//номер/ключ речи в новости
		if($id_ev){
			$hArr[] = $id_ev;//7
		}
	}
	//var_dump($hArr);
	if(count($hArr) >0){
		$strIn = implode("','",$hArr);
		$strIn = "'".$strIn."'";
		//echo "strIn-".$strIn."<br><br>";
	}

}*/

//дата начала и окончания
$Date_b = '';
$Date_e = '';
if(isset($_SESSION['Date_b']) AND isset($_SESSION['Date_e'])){
		$Date_b =  $_SESSION['Date_b'];
		$Date_e =  $_SESSION['Date_e'];
		
}

$KeyWords = '';
if(isset($_SESSION['KeyWords'])){
	$KeyWords = $_SESSION['KeyWords'];
}

$included = 0;
if($_POST['included']){
	$_SESSION['included'] = 1;
	$included = $_POST['included'];
	//echo "here";
}
//if(isset($_POST['included'])){


$DateNoInTheme = 0;
if($_POST['DateNoInTheme']){
	$_SESSION['DateNoInTheme'] = 1;
	$DateNoInTheme = $_POST['DateNoInTheme'];
	//echo "here";
}

if($_POST['postback']){ //если это постбак то обнуляем 
	if($_POST['included'] ==null){
		$_SESSION['included'] = 0;
		$included = 0;
		//echo "here1";
	}
	if($_POST['DateNoInTheme'] ==null){
		$_SESSION['DateNoInTheme'] = 0;
		$DateNoInTheme = 0;
		//echo "here1";
	}
	
}


//echo "_POST['included']-".$_POST['included']."<br/>";
//var_dump($_POST['included']);


if(isset($_SESSION['included'])){
	$included = $_SESSION['included'];
}

if(isset($_SESSION['DateNoInTheme'])){
	$DateNoInTheme = $_SESSION['DateNoInTheme'];
}

if(isset($_POST['DateBeg']) AND isset($_POST['DateEnd'])){
		$Date_b =  mysql_real_escape_string($_POST['DateBeg']);
		$Date_e =  mysql_real_escape_string($_POST['DateEnd']);
}elseif(isset($_GET['DateBeg']) AND isset($_GET['DateEnd'])){
		$Date_b =  mysql_real_escape_string($_GET['DateBeg']);
		$Date_e =  mysql_real_escape_string($_GET['DateEnd']);
}
//http://localhost/socset/index.php?DateBeg=25-03-2014&DateEnd=25-12-2014&scale=12

if(isset($_POST['KeyWords'])){

		$KeyWords =  mysql_real_escape_string($_POST['KeyWords']);
	
}

if(($Date_b =='') OR ($Date_e =='')){//если время так и не определенно, то выбираем сами
		$time = new DateTime('now');
	/*
		$newtime = $time->modify('-1 year')->format('d-m-Y');
		
		$Date_b = $newtime;
		$Date_e = date("d-m-Y");
	*/
	//ставим год вперед, чтобы ничего не показывалось
		$Date_b =  $time->modify('+1 year')->format('d-m-Y');
		$Date_e = $Date_b;		
	//	echo "<br />php Date_b-".$Date_b;
	//	echo "<br />php Date_e-".$Date_e;
		
}



	$whereDate = '';
	if(($Date_b !='') AND ($Date_e !='')){
		$_SESSION['Date_b'] = $Date_b;
		$_SESSION['Date_e'] = $Date_e;
		$arrBeg = explode("-",$Date_b); $DateBeg = $arrBeg[2]."-".$arrBeg[1]."-".$arrBeg[0];
		$arrEnd = explode("-",$Date_e); $DateEnd = $arrEnd['2']."-".$arrEnd['1']."-".$arrEnd['0'];
		//$whereDate = " AND date_Beg >= STR_TO_DATE('".$DateBeg." 00:00:00', '%Y-%m-%d %H:%i:%s')  AND date_End <= STR_TO_DATE('".$DateEnd." 23:59:59', '%Y-%m-%d %H:%i:%s')";
		$whereDate = " AND date_Beg >= STR_TO_DATE('".$DateBeg." 00:00:00', '%Y-%m-%d %H:%i:%s')  AND date_Beg <= STR_TO_DATE('".$DateEnd." 23:59:59', '%Y-%m-%d %H:%i:%s')  AND date_End <= STR_TO_DATE('".$DateEnd." 23:59:59', '%Y-%m-%d %H:%i:%s')";
		if($included){
			$_SESSION['included'] = 1;
			//echo "included-".$_POST['included'];
			$whereDate = " AND (date_Beg BETWEEN STR_TO_DATE('".$DateBeg." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".$DateEnd." 23:59:59', '%Y-%m-%d %H:%i:%s') OR
			date_End BETWEEN STR_TO_DATE('".$DateBeg." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".$DateEnd." 23:59:59', '%Y-%m-%d %H:%i:%s') OR
			 STR_TO_DATE('".$DateEnd." 23:59:59', '%Y-%m-%d %H:%i:%s') BETWEEN date_Beg AND date_End)";
		}
	}
	
	
	
	$whereKeyWords = '';
	if($KeyWords !=''){
	//echo "gggggggggggggggggggggggggggggggggggggggggggggggggggggg1".$KeyWords."<br />";	
		$_SESSION['KeyWords'] = $KeyWords;
		$whereKeyWords = " AND event LIKE '%".$KeyWords."%' ";
		//echo "gggggggggggggggggggggggggggggggggggggggggggggggggggggg";
	}
	
	
	$whereIdIn = '';
	if($strIn !=''){
	//echo "gggggggggggggggggggggggggggggggggggggggggggggggggggggg1".$KeyWords."<br />";	
		//$_SESSION['KeyWords'] = $KeyWords;
		$whereIdIn = " OR id in (".$strIn.") ";
		//echo "gggggggggggggggggggggggggggggggggggggggggggggggggggggg";
	}	
	
/*
echo "<br />Date_b-".$Date_b;
echo "<br />Date_e-".$Date_e;
echo "<br />";*/	/**/

$QueryTheme =''; //подключение новостей

//echo "<br>2Date_b -".$Date_b." Date_e-".$Date_e." id_theme".$id_theme;	

//какой столбец вытаскиваем
/*
$eventNeed = '';
		if($_SESSION['ISBot'] == 'no'){
			$eventNeed = 'event';
		}else{
			$eventNeed = 'event_changed';
		}
*/

	//вытаскиваем все страны
		$CountryArr = array();
		$query = "SELECT * FROM  countrys ";
		$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				$CountryArr[$row['id']] = $row['country'];
				//if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
		
			}
		}	
		
	//вытаскиваем все категории
		$CategoryArr = array();
		$query = "SELECT * FROM  category ";
		$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				$CategoryArr[$row['id']] = $row['category'];
				//if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
		
			}
		}	
	
		//var_dump($CountryArr);

$eventNeed = 'event, event_changed';		


//где ищем, в новостях или истории
$WhereSearch = 'Date_Of_Events'; $WhereSearchCountry = 'Date_vs_country';
if($onlyNews){
	$WhereSearch = 'Date_of_News'; $WhereSearchCountry = 'Date_vs_countryN';
}


if(($id_theme!='')){


//echo "rrrrrrrrrrrrrrrrrrrrr";

		//вытаскиваем все события по теме
		$query = "SELECT * FROM  Theme_of_Events WHERE id='$id_theme' ORDER BY id"  ;		
		$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			$row = mysql_fetch_assoc($result);	
			$ThemeGlob = $row['Theme'];
			$titleStr = "Все даты и события по теме: ".$row['Theme'];
			$textThema = $row['text'];
			$ids_events = explode("|",$row['ids_events']);
			if(count($ids_events)>0){
			$ids_events = array_diff($ids_events, array(''));
			$strTh = implode("','",$ids_events);	
			$strTh = "'".$strTh."'";
			}
			
			
			
				//если стоит галочка - даты по теме, то находим минимальную и макс
				if(!$DateNoInTheme){
					$query = "SELECT min(date_Beg) as Beg, max(date_Beg) as End1, max(date_End) as End2 FROM  $WhereSearch WHERE id in (".$strTh.") ORDER BY id"  ;		
					$result = mysql_query($query) or die(mysql_error());
					$row = mysql_fetch_assoc($result);	
					//echo "min(date_Beg)".$row['Beg']."<br />";
					//echo "max(date_End1)".$row['End1']."<br />";
					//echo "max(date_End2)".$row['End2']."<br />";
					//находи макс Енд
					if(strtotime($row['End1'])<= strtotime($row['End2']))
					{
						$resultEnd = $row['End2'];
					}else{
						$resultEnd = $row['End1'];
					}
		//			echo "resultEnd".$resultEnd."<br />";
					
					//преобразуем даты
					$time = new DateTime($row['Beg']);
					$_SESSION['Date_b'] = $Date_b = $time->format('d-m-Y');	
					
					$newtime = new DateTime($resultEnd);
					$_SESSION['Date_e'] = $Date_e = $newtime->modify('+1 month')->format('d-m-Y');
		//			echo "Date_b".$Date_b."<br />";
		//			echo "Date_e".$Date_e."<br />";				
				}

			
			//ISNULL(NULLIF(fieldname,''))

			
			//вытаскиваем сами даты
			$query = "SELECT id, date_Beg, date_End, $eventNeed, ids_Theme, date_of_add, ids_country, category, ISNULL(NULLIF(map_objects,'')) as map_objects, mapPict  FROM  $WhereSearch WHERE id in (".$strTh.") ORDER BY date_Beg"  ;
            //ISNULL(NULLIF(fieldname,''))  0 - если есть координаты,  1 - если пусто

			
			
		
		}else{
			echo "<br />Нет Событий в этой теме!!!<br />";
			$query = "SELECT id, date_Beg, date_End, $eventNeed, ids_Theme, date_of_add, ids_country, category, ISNULL(NULLIF(map_objects,'')) as map_objects, mapPict  FROM  $WhereSearch WHERE '1'='1' ".$whereDate." ".$whereKeyWords." ".$whereIdIn." ".$QueryNews." ORDER BY date_Beg"  ;
		}

}else{
	//вытаскиваем все события
	$query = "SELECT id, date_Beg, date_End, $eventNeed, ids_Theme, date_of_add, ids_country, category, ISNULL(NULLIF(map_objects,'')) as map_objects, mapPict  FROM  $WhereSearch WHERE '1'='1' ".$whereDate." ".$whereKeyWords." ".$whereIdIn." ".$QueryNews." ORDER BY date_Beg"  ;
}




	
//echo 	$query;
	$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			$arrEv = array();
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				if($_SESSION['ISBot'] == 'yes'){
					if($row['event_changed'] !=''){
						$row['event']="*".$row['event_changed'];
						/**/
					}

				}
						//вытаскиваем страны
						$ids_country = explode("|",$row['ids_country']);
						//удаляем повторяюшиеся
						$ids_country = array_unique($ids_country);	
						//удаляем пустые 
						$ids_country = array_diff($ids_country, array('','0'));					
						$row['country'] = array();
						//var_dump($ids_country);
						foreach($ids_country as $id_c){
							$row['country'][$id_c]=$CountryArr[$id_c];
						}				
					unset($row['event_changed']);				
				$arrEv[] = $row;
				//if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
			}
		}
		
	
	
//var_dump($arrEv);
	

	if($arrEv !=null){
			//die("<br /><br />хана");

	//	var_dump($arrEv);
		
		
		//пробегаемся по всем массивам и находим все даты начала и конца
		$arrOfDate = array();

		
		// var_dump($SpeechArr);
		foreach($arrEv as $event){
			//if($event['date_Beg'] !=){
				$arrOfDate[] =$event['date_Beg'];
				$arrOfDate[] =$event['date_End'];
			//}
			//echo "<hr />";
		}
		
		//удаляем повторяюшиеся даты
		$arrOfDate = array_unique($arrOfDate);	
		//удаляем пустые 
		$arrOfDate = array_diff($arrOfDate, array('0000-00-00 00:00:00'));
		
		//порядочиваем массив
		arsort($arrOfDate);
		
		//	echo "<br><br>arrOfDate";
		//	var_dump($arrOfDate);
		
		//находим минимальную и мкксимальную дату
		$maxDate = max($arrOfDate);
		$minDate = min($arrOfDate);
		

		
		//$minDate =  "2012-07-02 00:00:00";	
		//$maxDate =  "2014-09-07 00:00:00";	
				
			
//			echo "<br>maxDate-".$maxDate;
//			echo "<br>minDate-".$minDate;
		$Arr1 = explode(' ',$maxDate);
		$Arr2 = explode(' ',$minDate);

	
	}
	

	
	if(($Date_b !='') AND ($Date_e !='')){//если даты у нас заданы (то тогда ставим промежуток за последний год)
		$ss = explode('-',$Date_b);
		$ssss = explode('-',$Date_e);
	//	echo $Date_b."hhhhh<br/>";
		
		$ss1 = $ss[2]."-".$ss[1]."-".$ss[0];
		$ssss1 = $ssss[2]."-".$ssss[1]."-".$ssss[0];
		$ArrDMin = explode('-',$ss1);
	//	var_dump($ArrDMin);
		$ArrDMax = explode('-',$ssss1);		
/*	echo "<br><br>"; var_dump($ArrDMin);
			echo "<br />Date_b-".$Date_b;
		echo "<br />Date_e-".$Date_e;
*/	
	}else{
		//$ArrDMax = explode('-',$Arr1[0]);
		//$ArrDMin = explode('-',$Arr2[0]);
		
		/*	$ss = explode('-',$ArrDMin[0]);
			$ssss = explode('-',$ArrDMax[0]);
		
		$Date_b = $ss[0]."-".$ss[1]."-".$ss[2];
		$Date_e = $ssss[0]."-".$ssss[1]."-".$ssss[2];
		*/

		
	}


	
	//определяем сколько лет нужно
	$num_years = $ArrDMax[0]-$ArrDMin[0];
	/*echo "<br><br>minDate-".$minDate;	
	echo "<br>maxDate-".$maxDate;
	echo "<br>num_years-".$num_years;*/
/*	
	if($num_years>1000){ 	$_SESSION['Date_b'] = null; $_SESSION['id_theme'] = null;
		$_SESSION['Date_e'] = null;  
		
		?><a href='index.php?eraseFilter' >Очистить фильтр<a/><br />	<?
		//die("Слишком большой промежуток времени более 1000лет!");
		echo "Слишком большой промежуток времени более 1000лет!";
	}
*/
	//Маштаб

		$scale = 3;  //*10 пикселей в месяце
		if(isset($_GET['scale'])){ 
			$scale =sprintf("%d",$_GET['scale']);
			$scale =$_GET['scale'];
		}else{
			//определяем относительно количества лет
			if(($num_years>5) AND ($num_years<=10)){
				$scale = 2;
			}elseif(($num_years>10) AND ($num_years<=20)){
				$scale = 1;
			}elseif(($num_years>20) AND ($num_years<=30)){
				$scale = '0.5';
			}elseif(($num_years>30) AND ($num_years<=50)){
				$scale = '0.2';
			}elseif(($num_years>50) AND ($num_years<=300)){
				$scale = '0.1';
			}elseif($num_years>300){
				$scale = '0.05';
			}
		}
	
	
	?>
	
	
	
	
<html>
<head>
	<title><?if($titleStr !=''){ echo $titleStr; }else{ echo "Все важные даты и события мировой истории";}?></title>
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>	
<body style="position:relative; background-image: url(img/background.jpg);  background-size:cover;" >

<script type="text/javascript" src="js/jquery.js"></script><!-- подключаем -->
<script type="text/javascript" src="js/mapY.js"></script><!-- подключаем -->
<script type="text/javascript" src="https://api-maps.yandex.ru/2.1.14/?lang=ru_RU"></script>
<link rel="stylesheet" type="text/css" href="css/style.css"  />

<script type="text/javascript" src="blocks/dinamic_scripts/CreateElementOnScreen.js"></script><!--подключаем для создания плавающих окон -->	

<? include_once('rulers_map.php');?>
<script>



//при сочетании клавишь и выделенном тексте - создаем событие-дату в базе
document.addEventListener('keydown', function(event){//обработка сочетания клавишь
	   var txt = '';
    if (window.getSelection) {
        txt = window.getSelection();
    } else if (document.getSelection) {
        txt = document.getSelection();
    } else if (document.selection) {
        txt = document.selection.createRange().text;
    }
	//alert(txt);
	//alert(event.ctrlKey + "---"+event.keyCode);
	if(event.ctrlKey ){
		if(event.keyCode ==88){ //если вместе с ctrl была нажата x
			
				<?/*if($_SESSION['Guest_id']['id_user'] == $AdminID){ //если я админ	?>
					elemMenu.innerHTML += "<div id='idWh-"+id+"' onclick='ShowFotoMen(this)' style='text-decoration:underline; cursor:pointer;'>Прикрепить фото</div>";
				<?}*/?>
			
			//alert("ctrl+X----"+txt);
			require("blocks/dinamic_scripts/EventAddMenu.js", function(){ ShowEventAddMenu(txt); });
			//ShowEventAddMenu(txt);
		}
	}
});

function deletElem(obj){//удаляем элемент меню
//var elem = document.getElementById('CalendarDiv');
obj.parentNode.style.display = 'none';
obj.parentNode.parentNode.removeChild(obj.parentNode);
}
</script>	
	<!--<div id="mapYa"></div>-->
	
<script>
		//if(!scale){
			scale = 3
			var nun_years = <?=$num_years?>;
			//nun_years = <?=$num_years?>;
			
			if((nun_years>5) && (nun_years<=10)){
				scale = 2;
			}else if((nun_years>10) && (nun_years<=20)){
				scale = 1;
			}else if((nun_years>20) && (nun_years<=30)){
				scale = '0.5';
			}else if((nun_years>30) && (nun_years<=50)){
				scale = '0.2';
			}else if((nun_years>50) && (nun_years<=300)){
				scale = '0.1';
			}else if(nun_years>300){
				scale = '0.05';
			}			
			
			
			
			
		//}	
	function ChangeScale(zn){

		if(zn =='+'){
			scale = scale*2;
		}
		if(zn =='-'){
			if(scale<= 1){
				scale = scale/2;
			}else{
				scale = Math.ceil(scale/2);
			}
		}
		console.log('ChangeScale-'+scale)
	
	

 arrEv_Once = [];
 arrEv_OnceN = [];
 arrEv_Period = [];
 arrEv_PeriodN = [];
 scaleN = scale*1;				
 yearW = scaleN*365;
 kvW = Math.ceil(scaleN*365/4);
 monthW = Math.ceil(scaleN*365/12);
 svg = document.getElementById("svg_table");//.getSVGDocument();
yearB = <?=$ArrDMin[0]?>-1+1;
	
	



	SVGRender(arrEv);			
	}
</script>
	
	
	<?
//считаем кол-во дней от начала для закрашивания
$dayFromBeg = ($ArrDMin[1]-1)*30+$ArrDMin[2];
$dayFromEnd = ($ArrDMax[1]-1)*30+$ArrDMax[2];
//echo "dayFromBeg-".$dayFromBeg."<br>";
//echo "dayFromEnd-".$dayFromEnd."<br>";
$piksFromBeg = ceil($dayFromBeg*10*$scale/30);

$piksAllMonthEar = ceil(360*10*$scale/30)+3;
$year = $ArrDMax[0]-$ArrDMin[0];
$yearPiks = $year*$piksAllMonthEar;

$piksFromEnd = ceil($dayFromEnd*10*$scale/30)+$yearPiks;
$alPiks = ($year+1)*$piksAllMonthEar;
/*echo "piksFromBeg-".$piksFromBeg."<br>";
echo "piksFromEnd-".$piksFromEnd."<br>";
echo "piksAllMonthEar-".$piksAllMonthEar."<br>";
echo "alPiks-".$alPiks."<br>";*/	
	$DateForFormBeg = $ArrDMin[2]."-".$ArrDMin[1]."-".$ArrDMin[0];
	$DateForFormEnd = $ArrDMax[2]."-".$ArrDMax[1]."-".$ArrDMax[0];
	$DateForFormEnd = $ArrDMax[2]."-".$ArrDMax[1]."-".$ArrDMax[0];
	//$_POST['DateBeg']
	//$_POST['DateEnd']

	
	
	$theme = '';
	//вытаскиваем все темы новостей (если в дальнейшем будет висеть, то сделать при клике на ссылку)
	$query = "SELECT * FROM  Theme_of_Events ORDER BY Theme";
	$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			$arrTheme = array();
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				$arrTheme[] = $row;
				if($row['id'] == $_GET['id_theme']){  $theme = $row['Theme'];}
				
				//if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
			}
		}
	
	
	
	?>

	<!--<a class='vdiskus' href="index.php?c=soobsh" title="Обзор новостей в России и по всему миру " > Обзор новостей</a> |
	<a class='vdiskus' href="SpeechWind.php?c=soobsh" title="Высказывания, речи, диалоги, обращения известных людей и политиков, также историков, аналитиков и т.д." > Диалоги известных людей</a> |
	<a class='vdiskus' href="PersonsStruct.php?avtor=all" title="Известные люди и политики, а также историки, аналитики и т.д. в мировой структуре (иерархия)" > Иерархия известных людей</a> |
	-->
		
	
<!--	<div id="OuterSpeechFilterForm" style="position:relative;">-->
		<!--Форма фильтра -->
		<div id="SpeechFilterForm" style='padding:8px; position:fixed; top:-13px; z-index: 101; border-radius: 20px; margin-left:20px; '  >
			<br />

			<form  method='post' action='index.php' >
				<input type='hidden' value='1' name='eraseFilter'  />
				<input type='submit' value="Сбросить весь  фильтр" />
				&nbsp;&nbsp;&nbsp;<input type='text' size="7" / class="toHideFilterForm">&nbsp;<button class="toHideFilterForm" >Поиск</button>
			</form>



		&nbsp;&nbsp;<a class='vdiskus' href="index.php?eraseFilter=1" title="Перейти на главную страницу сети новостей России и всего мира"><< Главная страница</a> |
		<a class='vdiskus' href="ShowDate.php?EvBy10" title="события" > Список событий и дат </a> |
		<a class='vdiskus' href="ShowNews.php" title="новости" > Список новостей </a>&nbsp;&nbsp;		


			<div id="innerForm" style="display:none;">
				<form id='AvtorForm' method='post' action='index.php' >
					<input type='hidden' value='<?=$eventN?>' name='eventN' id='eventsIds' />

					<fieldset style='float:left;'>
					<legend><b>Фильтр</b></legend>
					<div>
						<div style='float:left;'>
							где искать<select  name='ffff' onChange='SelectWhere(this)'  ><!--style="display:none;"-->
									<option value='0' <?if(!$onlyNews){ echo "selected";}?> >История</option>
									<option value='1' <?if($onlyNews){ echo "selected";}?> >Новости</option>
									</select><br />
							<b>Диапазон дат</b><br />
							<input type='text'  style='width:70px;' value='<?=$DateForFormBeg?>' onclick='kalend(this,event);' name='DateBeg' id='DateBeg' /><b> начало</b>
							<br />
							<input type='text' style='width:70px;' value='<?=$DateForFormEnd?>' onclick='kalend(this,event);' name='DateEnd' id='DateEnd' /><b> окончание</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<br />
							<input type="checkbox" name="included" <?if($included){ echo "checked";}?> > <b title='иначе только события начинающиея и заканчивающиеся внутри диапазона'>включительно</b>
							<br />
							<input type='hidden' name='postback' value='1' >
						</div>
						<div style='float:left; ' >
							<b>Ключевые слова</b><br />
							<input type='text' value='<?=$KeyWords?>'  name='KeyWords' id='KeyWords' /> 
							<br />
						</div>
					</div>
					<div style="clear:both;"></div>
					<input type='submit' value='отобрать по фильтру' />
					
					</fieldset>
					
					<fieldset style='float:left;'>
					<legend><b><a href='pass.php' style='text-decoration:none; color:black;'>О<a/>тобрать по теме</b></legend>
						<input type="checkbox" name="DateNoInTheme" <?if($DateNoInTheme){ echo "checked";}?> onchange="document.getElementById('AvtorForm').submit(); return;" > <b title='не соспоставлять диапазон дат датами событий темы'>даты не по теме</b>
						<br />
						<input type='text'  style='width:270px;' value='<?=$theme?>' onkeyup='ShowThemeList(this);' name='ddd' id='DateBeg' /><!--<b> название</b>-->
							<div style="position:relative;">
								<div style="position:absolute;"><!---->
								<select id='selectMod'  name='avtorNameCh' onChange='SelectTheme(this)'  ><!--style="display:none;"-->
									<option value='' >выберите тему</option>
									<?foreach($arrTheme as $th){?>
										<option value='<?=$th['id']?>' ><?=$th['Theme']?></option>
									<?}?>
								</select>
								

							</div>
							<br />
							</div>


					</fieldset>
					<!--
					<fieldset style='float:left;'>
					<legend><b>Новостной обзор</b></legend>
						<input type="checkbox" name="NewsUsage" <?if($NewsUsage){ echo "checked";}?> onchange="document.getElementById('AvtorForm').submit(); return;" > <b title='выводить ли события новостей (в т.ч. заголовки новостей)'>включить новостной поиск</b>
					</fieldset>
					-->
					<div style="clear:both;"></div>
					
				</form>
			<br />
		</div>
		
		</div>



	<!--</div>-->
	<br /><br /><br />
	
	<script>//работа по выплыванию формы
	
	$(document).ready(function() {
		var needHide = false;


		
			$('#SpeechFilterForm').mouseenter(function(){
				needHide = false;
				$('#innerForm').show(700)
				$('.toHideFilterForm').hide(700)



		 		 //alert('enter');
		})
		$('#SpeechFilterForm').mouseleave(function(){
		 		 //alert('leave');
		 	needHide = true;	 
			setTimeout(function(){  
			 	if(needHide){	 
			 		 $('#innerForm').hide(700)
			 		 $('.toHideFilterForm').show(700)
			 	}
			},2000)

		})	


	});
	</script>

	<script>//добавление отдельных событий в таблицу

		function SelectWhere(obj){//искать по событиям истории или новостям
			// if (this.options.[this.selectedIndex].value == "3" ){}
			//alert('fff'+obj.options[obj.selectedIndex].value);
			//var avtIds = document.getElementById('eventsIds');
			//avtIds.value +='|'+obj.options[obj.selectedIndex].value;
			document.location.href = 'index.php?onlyNews='+obj.options[obj.selectedIndex].value;
			//document.getElementById('avtorNameId').value = '';//очишаем имя автора
			//document.getElementById('AvtorForm').submit();
		}

		
		function SelectTheme(obj){//добавление в форму id автора и отправка формы
			// if (this.options.[this.selectedIndex].value == "3" ){}
			//alert('fff'+obj.options[obj.selectedIndex].value);
			//var avtIds = document.getElementById('eventsIds');
			//avtIds.value +='|'+obj.options[obj.selectedIndex].value;
			document.location.href = 'index.php?id_theme='+obj.options[obj.selectedIndex].value;
			//document.getElementById('avtorNameId').value = '';//очишаем имя автора
			//document.getElementById('AvtorForm').submit();
		}
		
		function ShowThemeList(obj){//функция поиска в БД событий по буквам
			if (obj.value.length > 3){
				//вытаскиваем все  событие с похожим наименованием
				var arr;
				$.ajax({
				  async: false, 
				  url: 'blocks/dinamic_scripts/Find_Theme.php',
				  data: {name:obj.value},
				  type: "POST",
				  success: function(data) {  arr = data; },
				dataType: 'json'
				 })
		
				if(arr){
					var Sel = document.getElementById('selectMod');
					Sel.style.display = "block";
					Sel.innerHTML= '';
					Sel.multiple = true;
					for(var i=0; i<arr.length; i++){
						var arr1 = arr[i].split('|');
						//alert(arr1[0]+"---"+arr1[1]);
						var opt = document.createElement('option');
						opt.value = arr1[0];
						opt.innerHTML = arr1[1];
						Sel.appendChild(opt);
					}
				}
			}
		}

	</script>
	
	
	
	
	<link rel="stylesheet" type="text/css" href="blocks/calendarStyle.css"  /> <!--подключаем стили календарей -->
	
	 <script>
	 //скрипт работы с календарем
		var id_calend_call = ''; //id вызвавшего календарь элемента
		
		function require(url, callback){	//функция которая подгружает скрипт и запускает в нем функцию
					var script = document.createElement("script")		
				script.type = "text/javascript";
				if (script.readyState) { //IE
					script.onreadystatechange = function() {
						if (script.readyState == "loaded" 
						|| script.readyState == "complete") {
							script.onreadystatechange = null;
							callback();
						}
					};
				} else { //Others
					script.onload = function() {
						callback();
					};
				}
				script.src = url;
				document.getElementsByTagName("head")[0].appendChild(script);
			}	
		
		
		function kalend(obj,event){//функция, которая выводит календарь на экран
			id_calend_call = obj.id;
			if(document.getElementById('CalendarDiv')){ deleteCal();}//удаляем календарь если он уже вызван
			var opt = new Array(); //параметры
			opt.IndexZ = 40; //IndexZ
			//opt.fixed = 'fix'; //означает, что она зафиксирована экране
			var elemCal = CreateElementOnScreen(260,event,opt);
			elemCal.innerHTML = "<span onclick='deleteCal()' style='cursor:pointer; float:right; margin: -10px -5px 0 0;' > <span style='color:black;'>_</span><b>x</b><span style='color:black;'>_ </span></span><br />";  //закрывание этого списка
			elemCal.innerHTML += "<div id='calendar'></div>";
			elemCal.id += "CalendarDiv";
//			elemCal.style['background'] = 'url(../img/fon.png)';
			
			document.body.appendChild(elemCal);		
			var date = new Date();
			require("blocks/calendarN.js", function(){ calendar_show(date.getDate(),date.getMonth()+1,date.getFullYear()); });
		}
		
		function deleteCal(){//удаляем календарь
				var elem = document.getElementById('CalendarDiv');
				elem.style.display = 'none';
				elem.parentNode.removeChild(elem);
		}
		
		function InsertDate(obj){//вставляем дату из календаря в элемент его вызвавший
				var elem = document.getElementById(id_calend_call);
				elem.value = obj;
				deleteCal();
		}

	</script>
	
	
	
	<!--таблица для заполнений-->
		<? //насколько мы смешаем вправо весь график, чтобы пользователь видел нужную нам область
			$smeshenie = 0;
			if($piksFromBeg >300){
				$smeshenie = $piksFromBeg - 300;
			}
		?>	
	
	
	<div id='tableDiv' style='position:relative; display:none; left:-<?=$smeshenie?>px;' >

		<div style=' position:absolute; width:<?=$alPiks+300?>; height:100%; border:1px solid red;'>
			
			<!-- закрашивание лишнего -->
			<table style='height:100%;'>
				<tr style='height:100%;'>
					<td style='height:100%;'><div style='z-Index:5; position:relative; background:url(img/fon1.png); width:<?=($piksFromBeg-5)?>px; height:100%; float:left; border:1px solid yellow;'></div></td>
					<td style='height:100%;'><div style=' width:<?=($piksFromEnd-$piksFromBeg)?>px; height:100%; float:left; border:1px solid blue;'></div></td>
					<td style='height:100%;'><div style='z-Index:5; position:relative; background:url(img/fon1.png); width:<?=($alPiks-$piksFromEnd+5)?>px; height:100%; float:left; border:1px solid orange;'></div></td>
				</tr>
			</table>

		<!--	<div style='background:url(img/fon.png); width:<?=$piksFromBeg?>px; height:100%; float:left; border:1px solid yellow;'></div>
			<div style=' width:<?=($piksFromEnd-$piksFromBeg)?>px; height:100%; float:left; border:1px solid blue;'></div>
			<div style='background:url(img/fon.png); width:<?=($alPiks-$piksFromEnd-100)?>px; height:100%; float:left; border:1px solid orange;'></div>
			<div style="clear:both;"></div>-->
		</div>
		<?if($scale<1){$font_size=ceil($scale*40); if($font_size>14){$font_size=14;} }else{$font_size = 14;}  //echo $font_size; //размер шрифта года?>
		
		<table  border='0' cellspacing='0' id='table' style='text-align:center;'  ><!--bordercolor='blue'-->
					<tr id='years_tr'  >
						<?//года
						for($i=0;$i<$num_years+1;$i++){
							$nn = $ArrDMin[0] + $i;

							
							echo "<td class='year' style='font-size:".$font_size."px; border-bottom:1px solid red;' colSpan='4' >".$nn."г.</td>";	
							//echo "<td style='border-bottom:1px solid red;' colSpan='4' >д</td>";								
						}
						?>
					</tr>
					<?if($scale >=1){//если слишком маленький маштаб, то не выводим кварталы?>
						<tr id='kvartals_tr'>
							<?
							$kv = 'квартал';
							if($scale <3){$kv = 'кв.';}
							for($i=0;$i<$num_years+1;$i++){
								//кварталы
								for($j=1;$j<5;$j++){
									//$nn = $ArrDMin[0] + $i;
									echo "<td class='year' ><div style='width:".($scale*30)."px;'>".$j." ".$kv."</div></td>";			
								}
							}
							?>
						</tr>
					<?}?>
					<?if ($scale >3){?>
					<tr >
						<?  //year
						$MonthLet = Array('','январь','февраль','март','апрель','май','июнь','июль','август','сентябрь','октябрь','ноябрь','декабрь');
						for($i=0;$i<$num_years+1;$i++){
							//месяцы в кварталах
							$Mes = 1;
							for($j=1;$j<5;$j++){
								//$nn = $ArrDMin[0] + $i;
								echo "<td><div style='float:left; width:".($scale*10-1)."px; font-size:12px; border-right:1px solid blue;'>".$MonthLet[$Mes++]."</div>
								<div style='float:left; width:".($scale*10-1)."px; font-size:12px; border-right:1px solid blue;'>".$MonthLet[$Mes++]."</div>
								<div style='float:left; width:".($scale*10)."px; font-size:12px;'>".$MonthLet[$Mes++]."</div></td>";
								//echo "<td><div style='width:".($scale*30)."px;'>".$j." квартал</div></td>";			
							}
						}
						?>
					</tr>
					<?}?>

	</table>
	</div>
	
	
	
	
	<?
	
	
/*	

	
	*/
	
	//	var_dump($arrEv);
	
	//выводим список новостей за неделю под таблицей, а после, отрисовывания удаляем (для поисковиков)
//	if(isset($_GET['ThisWeek'])){
	//var_dump($arrEv);
		$MonthArr = array('','янв','фев','мар','апр','май','июн','июл','авг','сен','окт','ноя','дек');
		for($i=0; $i<count($arrEv); $i++ ){
			
			//преобразуем даты
			$dat = explode(" ",$arrEv[$i]['date_Beg']);
			$dat1 = explode(" ",$arrEv[$i]['date_End']);
			$datB = $dat[0];
			$datE = $dat1[0];
			$datBA = explode("-",$datB); $datB=''; if($datBA[2] !='00'){$datB.=$datBA[2]."&nbsp";} if($datBA[1] !='00'){$datB.=$MonthArr[(int)$datBA[1]]."&nbsp";} if($datBA[0] !='0000'){$datB.=$datBA[0];}
			$arrEv[$i]['dateB1'] = $datB;
			
			$arrEv[$i]['dateE1'] = '';
			if($datE != '0000-00-00'){
				$datBE = explode("-",$datE); $datE=''; if($datBE[2] !='00'){$datE.=$datBE[2]."&nbsp";} if($datBE[1] !='00'){$datE.=$MonthArr[(int)$datBE[1]]."&nbsp";} if($datBE[0] !='0000'){$datE.=$datBE[0];}
				//$date.=" - ".$datE;
				$arrEv[$i]['dateE1'] = $datE;
			}
			
			$arrEv[$i]['titl_nE'] = $arrEv[$i]['event'];//+"-"+$arrEv[$i]['event_changed'];
		/*	
		if($_SESSION['ISBot'] == 'no'){//если мы точно уверены, что не поисковой бот, то отдаем события как есть
			$arrEv[$i]['titl_nE'] = $arrEv[$i]['event'];
		}else{
			
			//делаем смещение (перетасовываем) названия новостей
			$arr_slov = explode(" ",$arrEv[$i]['event']); $nslov = count($arr_slov); 
			$nslov = floor($nslov/3);
			$titl_n1 = array();
			$titl_e1 = array();
			$titl_n2 = array();
			$titl_e2 = array();
			for($d=0; $d<count($arr_slov);$d++){ 
				if($d<$nslov){
					$titl_n1[] =$arr_slov[$d];
				}else{
					$titl_e1[] =$arr_slov[$d];
				}
				if($d<$nslov*2){
					$titl_n2[] =$arr_slov[$d];
				}else{
					$titl_e2[] =$arr_slov[$d];
				}				
			}
			$titl_n1 = implode(" ",$titl_n1); $titl_e1 = implode(" ",$titl_e1);
			 $titl_nT = $titl_e1." ".$titl_n1;
			$titl_n2 = implode(" ",$titl_n2); $titl_e2 = implode(" ",$titl_e2);
			 $titl_nE = $titl_e2." ".$titl_n2;
			$arrEv[$i]['titl_nE'] = $titl_nE;		
			*/
					/*
						$string = $arrEv[$i]['event'];
						
						//вырезаем все что в скобках
						// $string = "Строка (служебная информация)"; 
						 $string = preg_replace('/\(.*?\)/', '', $string); 
						 
						// $string = str_replace(array("	"), '33', $string);
						
						//заменяем все знаки препинания на точки			
						 $string = str_replace(array(",", ":", ";", "!", "?"), '.', $string);
						 

						 $string = trim($string);
						 
						 //разбиваем на предложения
						 $arrSent = explode(".", $string);
						 
						 
						$arrSent = array_diff($arrSent, array('',' ','  ','   '));
						// var_dump($arrSent);
						 //Обрезаем пробелы и ставим все с большой буквы
						 
						 
						 for($j=0; $j<count($arrSent); $j++){
							if(mb_strlen($arrSent[$j],"utf-8")>5){
								$arrSent[$j] = trim($arrSent[$j]);	
								try {//делаем прописную букву
									
									$char= mb_strtolower(mb_substr($arrSent[$j],0,2,"utf-8"),"utf-8"); // это первый символ
									$arrSent[$j][0]=$char[0];
									$arrSent[$j][1]=$char[1];
								}catch (Exception $e) {}

								
								//перемещиваем предложение
									$arr_slov = explode(" ",$arrSent[$j]); $nslov = count($arr_slov); 
									$nslov = floor($nslov/3);
									$titl_n1 = array();
									$titl_e1 = array();
									$titl_n2 = array();
									$titl_e2 = array();
									for($d=0; $d<count($arr_slov);$d++){ 
										//if($d<$nslov){
										//	$titl_n1[] =$arr_slov[$d];
										//}else{
										//	$titl_e1[] =$arr_slov[$d];
										//}
										if($d<$nslov*2){
											//$titl_n2[] =$arr_slov[$d];
											//если это последнее слово и оно предлог, то его не добавляем
											if($d >= ($nslov*2-1)){
													if(mb_strlen($arr_slov[$d],"utf-8")>3){
														$titl_n2[] =$arr_slov[$d];
													}
											}else{
												$titl_n2[] =$arr_slov[$d];
											}
										}else{
											$titl_e2[] =$arr_slov[$d];
										}				
									}
									//$titl_n1 = implode(" ",$titl_n1); $titl_e1 = implode(" ",$titl_e1);
									 //$titl_nT = $titl_e1." ".$titl_n1;
									$titl_n2 = implode(" ",$titl_n2); $titl_e2 = implode(" ",$titl_e2);
									 $titl_nE = $titl_e2." ".$titl_n2;
									$arrSent[$j] = $titl_nE;	
									
								
								try {//делаем заглавную букву
									$char= mb_strtoupper(mb_substr($arrSent[$j],0,2,"utf-8"),"utf-8"); // это первый символ
									$arrSent[$j][0]=$char[0];
									$arrSent[$j][1]=$char[1];
								}catch (Exception $e) {}
								//	echo $string;
							}
						 }
						 
						//var_dump($arrSent); echo "<br><br>";
						 
						 
						$str = '';
						 //в зависимости от остатка от деления составляем назад строку
						for($j=0; $j<count($arrSent); $j++){
							//if((($j%3)==0) or (($j%5)==0) or (($j%7)==0)){
							//заменяем некоторые символы
							$arrSent[$j] = str_replace(array("-"), '&mdash;', $arrSent[$j]);
							
							if((($j%2)==0) or ($j == 1)){
								 if($str !=''){$str = $arrSent[$j].". ".$str;}else{ $str = $arrSent[$j];}
							}else{
								$str .= ". ".$arrSent[$j];
							}
							//echo $str."<br>";
						}
						 $string =$str.".";
				
			 //$string = implode(". ", $arrSent);  $string.=".";
			 
			 
			 
			 //echo $string . "\n";
			
			//раньше менялось здесь для ботов, сейчас просто вытаскивается другое поле
			$arrEv[$i]['titl_nE'] = $arrEv[$i]['event_changed'];	
		}	
		*/	
			
			
			
		
		
		?>

		<?}
		//var_dump($ArrDMin);
		?>
		
        
        <?	if($arrEv !=null){?>
            <br/>
            <?if(isset($_GET['id_theme'])){?>
            <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$ThemeGlob?></h3>
            <?}else{?>	
            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>По заданым критериям найдены следующие события/даты</b>
            <?}?>
        <?}?>
		
		<?if(count($arrEv)>0){?>
	        <div style="padding:7px; border:5px solid green;  position:relative; border-radius: 15px; margin-left:20px; margin-right:20px;  ">
					<!-- шкала -->
					<!--<div style="z-index:100; position:fixed; top:100px; margin-left: 3%;" title='Маштаб'>-->
					<div style="z-index:100; position:absolute; top:-5px; margin-left: 2%;" title='Маштаб'>
						<br />
						<? 
							
							$sledScalePl = $scale*2;  
							 //echo $sledScalePl;
							$sledScalePl=str_replace(',','.',$sledScalePl); 
							// echo $sledScalePl;
							//echo $scale;
						?>
						<div style='border:3px solid purple; margin-bottom:5px; color:purple; font-size:35px; cursor:pointer;' >
								<span onclick="ChangeScale('+')" style='font-weight:bold; text-decoration:none; color:purple;' >&nbsp;+&nbsp;</span>
						</div>
						<? 
							if($scale <= 1){
								$sledScaleMin = $scale/2;
							}else{
								$sledScaleMin = ceil($scale/2); 
							}
							$sledScaleMin=str_replace(',','.',$sledScaleMin); 
							
						?>
						<div style='border:3px solid purple; color:purple; font-size:35px; cursor:pointer;'><?/*<a href='index.php?scale=<?=$sledScaleMin?>' style='font-weight:bold; text-decoration:none; color:green;' >&nbsp;&ndash;&nbsp;</a>*/?>
							<span onclick="ChangeScale('-')" style='font-weight:bold; text-decoration:none; color:purple;' >&nbsp;-&nbsp;</span>
						</div>
					</div>
			
			<div style="position:relative; overflow:auto;" id="div_svg">


				<svg id="svg_table" version="1.1" width="20" height="20" 
					<!--viewBox="0 0 1280 1024" baseProfile="full" -->
					xmlns="http://www.w3.org/2000/svg" 
					xmlns:xlink="http://www.w3.org/1999/xlink" 
					xmlns:ev="http://www.w3.org/2001/xml-events">
							<!--  <text x="10" y="50" font-size="30">My SVG</text>
							  <rect x="30" y="50" width="120" height="50" style="fill-opacity: 0.7; fill: red;"></rect>
							  
							<rect x="30" y="140" width="120" height="50" style="fill:yellow; stroke-width:3; stroke: blue;"></rect>
							<rect height="100" style="fill: blue;" x="20" y="30" width="100"></rect><line x1="70" y1="40" x2="70" y2="540" fill="green" stroke="#006600"></line><line x1="120" y1="40" x2="120" y2="540" fill="green" stroke="#006600"></line>-->
				</svg>	
			</div>
		</div>
	<?}?>		
		<script>
			function showk(){
				if($('#allias').css('display')=='none'){
					$('#allias').css('display','block')
				}else{
					$('#allias').css('display','none')
				}
			} //showEvent
		</script>
		
		<? if($IsRedactor){ ?>
			<div style='border:1px solid green; display:none;' id="mapObject-menu" >
				<table>
					<tr>
						<td style="border-right:1px solid green; padding:5px;">
							<b>Параметры</b><br />
							<select id="objColor" name="objColor">
							  <option  value="#00FF00">Зеленый</option>
							  <option  value="#0000FF">Синий</option>
							  <option  value="#FF0000">Красный</option>
							</select>  цвет обьекта на карте
							<br />
							<select id="objShape" name="objShape">
							  <option  value="Polygon">Полигон1</option>
							  <option  value="Polyline">Линия2</option>
							  <option  value="Arrow">Стрелка3</option>
							  <option  value="Placemark">Метка4</option>
							</select>  форма обьекта на карте
							<br />
							<input type="text" name="mapObjWeight" id="mapObjWeight" value="2" size="3" />  толщина
							<br />
						</td>
						<td style="border-right:1px solid green; padding:5px;">
							<b>Текст</b><br />
							<input type="text" name="mapObjHeader" id="mapObjHeader" value="без заголовка" />  текст заголовка обьекта на карте
							<br />
							<input type="text" name="mapObjBody" id="mapObjBody" value="без текста тела" />  текст тела обьекта на карте
							<br />
							<input type="text" name="mapObjDescription" id="mapObjDescription" value="без описания" />  текст hint'a/описания обьекта на карте					
						</td>
						<td style="padding:5px;" >
							<b style="text-decoration:underline; cursor:pointer; color:blue;" onclick="showk()">Псевдонимы</b><br />
							<div style="display:none;" id="allias">
							<input type="text" name="Polygon" class="objShapeCh" />  псевдоним "Полигонов" на карте (пр. Территории)
							<br />
							<input type="text" name="Polyline" class="objShapeCh" />  псевдоним "Линий" на карте (пр. Границы)
							<br />
							<input type="text" name="Arrow" class="objShapeCh" />  псевдоним "Стрелок" на карте (пр. Маршруты)
							<br />
							<input type="text" name="Placemark" class="objShapeCh" />  псевдоним "Меток" на карте (пр. Объекты)						
							</div>
						</td>

					</tr>
				</table>
			</div>
		<?}?>
		<div  id="map-menu" style='position:relative;'  ></div>
			<div id="divForNext" style='position:relative; display:block;'>
					<div id="divPrev" style='cursor:pointer; display:none; position:absolute; z-index:100; background-color:green; text-align:center; font-size:48px; opacity:0.7; font-weight:bold; color:red; padding:10px;'>&#9664;</div><div style='cursor:pointer; display:none; position:absolute; z-index:100; background-color:green; text-align:center; font-size:48px; opacity:0.7; font-weight:bold; color:red; padding:10px;  ' id="divNext">&#9654;</div>
			</div>		
		<? if($IsRedactor){ ?>

			<div id="map-helper" style='position:relative; display:none;'></div>
		<?}?>



		<? 
		$MapWeidth = 900;
		$MapHeight = 500;
		if(isset($_COOKIE['MapWeidth'])){
			$MapWeidth = $_COOKIE['MapWeidth'];
		}
		if(isset($_COOKIE['MapHeight'])){
			$MapHeight = $_COOKIE['MapHeight'];
		}
		?>

		<!--<div id='divForNext' style="position:relative;">
			<div style="position:abso;">next11111<><><>
			</div>	
		</div>-->
		<div  id="map-canvas" style="width:<?=$MapWeidth?>px; height:<?=$MapHeight?>px; display:none; border:5px solid green; border-radius: 15px; margin: 10px 20px 0 20px;" width="600" height="600" ></div>
		

		<? if($_SESSION['Guest_id']['id_user'] == $AdminID){ ?>
			<br />koord<input type="text" id="koordOnClick">
            <button type="button" id="ShowM" onclick="ShowMap(null);" >Рисовать на карте</button>
        <?}?>

		
		<? if($IsRedactor){ ?>
			<iframe  name="h_iframe" width="700" height="100" style="display: none;"></iframe><!-- фрейм для загрузки страницы  onchange="document.forms['img_upload'].submit();"  -->
			<div id="picoBody" style='border:1px solid green;'>
				<b id='DobFotoLable'>Добавление картинки/карты </b>
				<!-- locks/dinamic_scripts/loadPicture.php?path=MapPicture&size=150 -->
				<form  id="linkForm2" method="post" action="blocks/dinamic_scripts/loadPicture.php?path=MapPicture&size=3000"  name="img_upload" enctype="multipart/form-data" target="h_iframe">
					<div id="imageId">
						 
						  <img src="img/loadinfo1.gif" style="display:none;" />
					 </div>
					 <div id="image_upload_status"></div>	
					 <p><input id="showfiles1" type="file" name="userfileComment"  /></p>
					 <input id="srcFoto" type="text" name="srcFoto"   />
					 <input id="TextArea1" type="hidden" name="srcFoto"  />
				</form>
			
			<script>		
				$(document).ready(function() { 
					
					$('#showfiles1').change(function(){ 
						console.log('showfiles1 submit');
						if(CurrentEnentNum){
							console.log('showfiles1 CurrentEnentNum');
							document.getElementById('srcFoto').value = '';
								document.forms['linkForm2'].submit();
								

								
								timer = setInterval(function(){
									srcFoto = document.getElementById('srcFoto').value;
									document.getElementById('MapPictureDraw').src =  srcFoto;
									console.log('MapPictureDraw-'+srcFoto);
									
									if(srcFoto !=''){
										clearInterval(timer);
											//сохраняем к автору картинку
											//alert(srcFoto)
											if(srcFoto !=''){
														$.ajax({
													  async: false, 
													  url: 'blocks/dinamic_scripts/MapsChanges.php',
													  data: {mapPict:srcFoto,id_ev:CurrentEnentNum},
													  type: "POST",
													  success: function(data) {  arr = data;  alert(data)
													  }//,
														//dataType: 'json'
													})
												
											}
											//сохраняем и миниатюру
											document.forms['linkForm2'].action = "blocks/dinamic_scripts/loadPicture.php?path=MapPictureSmal&size=60"
											document.forms['linkForm2'].submit();
											
									}
								}, 300);
								
						}else{alert('Вы не выбрали дату!')}
					});
					
					
					
					
				});		
			</script>
			
			
			<table>
				<tr>		
					<td>
						<h3>Подстановка файла</h3>
						<b>Нахождение координат (пикселей) на картинке</b><br />
						<table>
							<tr>
								<td>
								Первая точка
								</td>
								<td>
								Вторая точка
								</td>
							</tr>
							<tr>
								<td>
								x1 <input type="text" name="X1koordMapPict" id="X1koordMapPict" size="5"  /> 
								</td>
								<td>
								x2 <input type="text" name="X2koordMapPict" id="X2koordMapPict" size="5"  /> 
								</td>
							</tr>
							<tr>
								<td>
								y1 <input type="text" name="Y1koordMapPict" id="Y1koordMapPict" size="5"  /> 
								</td>
								<td>
								y2 <input type="text" name="Y2koordMapPict" id="Y2koordMapPict" size="5"  /> 
								</td>
							</tr>	
						</table>
						<!--	
						<input type="text" name="XY1koordMapPict" id="XY1koordMapPict" value="" />  координата X1/Y1 (пикселей) на картинке первой привязной точки<br />
						<input type="text" name="XY2koordMapPict" id="XY2koordMapPict" value="" />  координата X2/Y2 (пикселей) на картинке второй привязной точки<br />
						-->
						<b>Нахождение координат (широта/долгота) на карте Yandex</b><br />
						<!--
						<input type="text" name="LongLat1koordMapYa" id="LongLat1koordMapYa" value="" />  координата X1/Y1 (широта/долгота) на карте Yandex первой привязной точки<br />
						<input type="text" name="LongLat2koordMapYa" id="LongLat2koordMapYa" value="" />  координата X2/Y2 (широта/долгота) на карте Yandex  второй привязной точки<br />					<br />
						-->
						<table>
							<tr>
								<td>
								Первая точка
								</td>
								<td>
								Вторая точка
								</td>
							</tr>
							<tr>
								<td>
								Lat1  <input type="text" name="la1koordMapPict" id="la1koordMapPict" size="5"  /> 
								</td>
								<td>
								Lat2  <input type="text" name="la2koordMapPict" id="la2koordMapPict" size="5"  /> 
								</td>							

							</tr>
							<tr>
								<td>
								Lon1 <input type="text" name="lo1koordMapPict" id="lo1koordMapPict" size="5"  /> 
								</td>
								<td>
								Lon2 <input type="text" name="lo2koordMapPict" id="lo2koordMapPict" size="5"  /> 
								</td>
							</tr>	
						</table>					
						<br />
					</td>
					<td>
		
					</td>
				</tr>
			</table>
			<button onclick="PlacePicture()">наложить</button>
			<br /><br />
			<button style="color:red;" onclick="ClearMap()">полностью очистить карту</button>
			
			</div>	
			<br />
			
			<div id="ImgOverMap">
			<!--<img src="img/themes/mapMo.jpg" id="MapPictureDraw" />-->
			<img src="" id="MapPictureDraw" />
			</div>			
			
			<script>
					
					var srcFoto = '';
					var xyP = 1;
					var LoLaP = 1;	
					var picwidth = 0;
					var picheight = 0;
				
					function illuminatePoints(){
						$('#X1koordMapPict').attr('style','border:1px solid black;')
						$('#Y1koordMapPict').attr('style','border:1px solid black;')
						$('#X2koordMapPict').attr('style','border:1px solid black;')
						$('#Y2koordMapPict').attr('style','border:1px solid black;')
						$('#menuYa input[name="X1_text"]').attr('style','border:1px solid black;')
						$('#menuYa input[name="Y1_text"]').attr('style','border:1px solid black;')
						$('#menuYa input[name="X2_text"]').attr('style','border:1px solid black;')
						$('#menuYa input[name="Y2_text"]').attr('style','border:1px solid black;')
						

						$('#lo1koordMapPict').attr('style','border:1px solid black;')
						$('#la1koordMapPict').attr('style','border:1px solid black;')
						$('#lo2koordMapPict').attr('style','border:1px solid black;')
						$('#la2koordMapPict').attr('style','border:1px solid black;')
						
						$('#X'+xyP+'koordMapPict').attr('style','border:1px solid red;')
						$('#Y'+xyP+'koordMapPict').attr('style','border:1px solid red;')
						
						$('#lo'+LoLaP+'koordMapPict').attr('style','border:1px solid red;')
						$('#la'+LoLaP+'koordMapPict').attr('style','border:1px solid red;')
						
						//$('#menuYa input[name="X2_text"]').val()
						$('#menuYa input[name="X'+LoLaP+'_text"]').attr('style','border:1px solid red;')
						$('#menuYa input[name="Y'+LoLaP+'_text"]').attr('style','border:1px solid red;')
						
					};
					
					
					//очищаем карту у даты
					function ClearMap(){
						var result = confirm("Вы уверены, что хотите удалить все элементы на карте?");
						if(!result){
							return false;
						}
						
						$.ajax({
						  async: false, 
						  url: 'blocks/dinamic_scripts/MapsChanges.php',
						  data: {clearMap:'',id_ev:CurrentEnentNum},
						  type: "POST",
						  success: function(data) {  arr = data;  alert(data)
						  }//,
							//dataType: 'json'
						})						
					}
					
					function PlacePicture(){//наложение картинки на карту
						//находим зависимость координат (пикселей картинуки к градусам на карте)
						var x1 = $('#X1koordMapPict').val();
						var x2 = $('#X2koordMapPict').val();
						var y1 = $('#Y1koordMapPict').val();
						var y2 = $('#Y2koordMapPict').val();				

						var xM1 = $('#la1koordMapPict').val();
						var xM2 = $('#la2koordMapPict').val();
						var yM1 = $('#lo1koordMapPict').val();
						var yM2 = $('#lo2koordMapPict').val();	
						
						//находим коэфициенты
						var kx = (x2-x1)/(xM2-xM1);
						var ky = -(y2-y1)/(yM2-yM1);
						//alert('kx-'+kx+'  ky-'+ky);
						console.log('kx-'+kx+'  ky-'+ky);
						
						//находим верхний угол на карте
						x1nm = parseFloat(xM1) - (x1/kx);  
						y1nm = parseFloat(yM1) + (y1/ky); 
						

						//находим yнижний угол на карте
						x2nm = x1nm + (parseInt(picwidth)/kx);  
						y2nm = y1nm - (parseInt(picheight)/ky);  					
					//	PlacePictureOnMap()
						console.log('x1nm-'+x1nm+'  y1nm-'+y1nm);
						console.log('x2nm-'+x2nm+'  y2nm-'+y2nm);
						PlacePictureOnMap([[y1nm,x1nm],[y2nm,x2nm]])
						SaveMapObjectsToBD()

					}
					

					illuminatePoints();			
				
				$(document).ready(function() { 
					
		
					

					
					$('#ImgOverMap').click(function(e){
							console.log('click')
							if(e.originalEvent.altKey){//если нажата alt
								var x = e.pageX - e.target.offsetLeft;
								var y = e.pageY - e.target.offsetTop;
								console.log('click xb -'+e.pageX+" xe-"+x)
								console.log('click yb -'+e.pageY+" ye-"+y)	
								//подставляем координаты
								$('#X'+xyP+'koordMapPict').val(x)
								$('#Y'+xyP+'koordMapPict').val(y)
								xyP++; if(xyP>2){xyP=1;}
								illuminatePoints();
							}
							
							//размеры картинки
							picwidth = $('#ImgOverMap img').css('width')
							picheight = $('#ImgOverMap img').css('height')
							console.log('picwidth -'+picwidth+" picheight-"+picheight)	

							console.log('altKey-'+e.originalEvent.altKey);
							/*
							originalEvent.ctrlKey; coordPos = e.get('coords');
							*/
							
							
						}
					)
					
				});

			</script>
		<?}?>

			<script>
				function ChangeDateTableSize(){
					var DateTableClass = "minDateTable";
					if($('#DateTable').hasClass('minDateTable')){
						$('#DateTable').removeClass('minDateTable')
						$('#DateTable').addClass('maxDateTable')
						var DateTableClass = "maxDateTable";
					}else{
						$('#DateTable').removeClass('maxDateTable')
						$('#DateTable').addClass('minDateTable')						
					}
					$.ajax({
					  async: true, 
					  url: 'blocks/dinamic_scripts/SessionCoockieOperations.php',
					  data: {DateTableClass:DateTableClass},
					  type: "POST",
					 // success: function(data) {   alert(data);  }//,
						//dataType: 'json'
					})
				}
			</script>		
		<? 

		$DateTableClass = "minDateTable";
		if(isset($_COOKIE['DateTableClass'])){
			if($_COOKIE['DateTableClass'] =="maxDateTable"){
				$DateTableClass = "maxDateTable";
			}
			
		}		
		?>

		

		<script type="text/javascript">
			$(document).ready(function() { 
					//alert($('#DateTable').css("width"));
					$('#mapList').css("width",$('#DateTable').css("width"))

			});


		</script>


		<? 
		//пробегаемся по событиям и находим с картами
		$arrMap = Array();
		$arrOtm = Array();
		for($i=0; $i<count($arrEv); $i++ ){			
			if(!$arrEv[$i]['map_objects']){
				$arrEv[$i]['id_obsh'] = $i;
				if($arrEv[$i]['mapPict'] !=''){
					$arrMap[]=$arrEv[$i];

				}else{
					$arrOtm[]=$arrEv[$i];
				}
			}
		}
/*
		echo "arrMap<br />";
		var_dump($arrMap);
		echo "<br /><br />arrOtm<br />";
		var_dump($arrOtm);*/



		?>

		
		<script type="text/javascript">
			//заносим их в js мвссив
			var arrEvMap = JSON.parse('<?=json_encode($arrMap)?>');	
			var arrEvOtm = JSON.parse('<?=json_encode($arrOtm)?>');
			//выделяем следующий элемент в массиве карт и долее передаем аргументы в функцию показа карты
			function PreShowOnGraph(id){
				//alert(id);
				if(map == 1){
					alert('map')

				}else if(map == 2){
					alert('marks')

				}

				var have = false;
				var next = null;
				var prev = null;

				//пробегаемя по массивам карт и отметок и если есть, то показываем стрелки
				have = true;
				for(var i=0; i<arrEvMap.length; i++){
					if(arrEvMap[i]['id'] ==id ){
						
						if(typeof(arrEvMap[i-1]) != "undefined"){
							$('#divPrev').css('display','block')
							$('#divPrev').attr('title',arrEvMap[i-1]['event']);
							(function(n){ $('#divPrev').click(function() { ShowOnGraph(arrEvMap[n-1]['id'], arrEvMap[n-1]['id_obsh']);} ) })(i)
						}
						if(typeof(arrEvMap[i+1]) != "undefined"){
							$('#divNext').css('display','block')
							$('#divNext').attr('title',arrEvMap[i+1]['event']);
							(function(n){ $('#divNext').click(function() { ShowOnGraph(arrEvMap[n+1]['id'], arrEvMap[n+1]['id_obsh']);} ) })(i)
						}
					}


				}
				for(var i=0; i<arrEvOtm.length; i++){
					if(arrEvOtm[i]['id'] ==id ){
						have = true;
						if(typeof(arrEvOtm[i-1]) != "undefined"){
							$('#divPrev').css('display','block')
							$('#divPrev').attr('title',arrEvOtm[i-1]['event']);
							(function(n){ $('#divPrev').click(function() { ShowOnGraph(arrEvOtm[n-1]['id'], arrEvOtm[n-1]['id_obsh']);} ) })(i)
						}
						if(typeof(arrEvOtm[i+1]) != "undefined"){
							$('#divNext').css('display','block')
							$('#divNext').attr('title',arrEvOtm[i+1]['event']);
							(function(n){ $('#divNext').click(function() { ShowOnGraph(arrEvOtm[n+1]['id'], arrEvOtm[n+1]['id_obsh']);} ) })(i)
						}
					}
				}				

				//отрисовка кнопок next prev
				if(have){
					var map = $('#map-canvas')
					var NextCont = $('#divForNext')
					NextCont.width(map.width()+'px');
					NextCont.height('40px');
					NextCont.css('top',map.height()-80)
					NextCont.css('left',map.width()/2)
					$('#divNext').css('left',100)
				}

			}

		</script>

		

		<div style="position:relative;">
			
			<?if(count($arrMap)>0 or count($arrOtm)>0){?>
				<div id="mapList" style="z-index:100;    position:absolute; top:-10px; left:30px;border:2px solid green;background:#fff; border-radius: 5px; ">
					<?if(count($arrMap)>0){?>
						<div style="float:left"><b>Карты&nbsp;</b></div>
						<div style="overflow:auto;">
							<div style="white-space: nowrap;">	
								<?for($i=0; $i<count($arrMap); $i++ ){
									echo "<img class='imgMap' id='imgMap-".$arrMap[$i]['id']."' style='cursor:pointer; margin:1px; height:27px; border-right:1px solid green;' title='".$arrMap[$i]['titl_nE']."' src='MapPictureSmal/".$arrMap[$i]['mapPict']."'  onclick='ShowOnGraph(".$arrMap[$i]['id'].",".$arrMap[$i]['id_obsh'].")' />";
								}?>
							</div>
						</div>
					<?}?>
					<?if(count($arrOtm)>0){?>
						<div style="float:left"><b>Отметки&nbsp;</b></div>
						<div style="overflow:auto;">
							<div style="white-space: nowrap;">	
								<?for($i=0; $i<count($arrOtm); $i++ ){
									echo "<img class='imgMap' id='imgMap-".$arrOtm[$i]['id']."' style='cursor:pointer; margin:1px; height:27px;' title='".$arrOtm[$i]['titl_nE']."'' src='MapPictureSmal/mapvector1.jpg'  onclick='ShowOnGraph(".$arrOtm[$i]['id'].",".$arrOtm[$i]['id_obsh'].")' />";
								}?>
							</div>
						</div>
					<?}?>
				</div>
			<?}?>


				<!--<div style="z-index:100; position:absolute; top:-5px; margin-left: 2%;" title='Маштаб'>-->
					<div title="растянуть/сжать" style='top:60px; margin-left: 4%; z-index:100; position:absolute; border:3px solid purple; margin-bottom:5px; color:purple; font-size:35px; cursor:pointer;' >
							<span onclick="ChangeDateTableSize()" style='font-weight:bold; text-decoration:none; color:purple;' >&nbsp;↕&nbsp;</span>
					</div>

			<?if(count($arrEv)>0){?>
				<div id="DateTable" style="background:#fff; padding:7px; border:5px solid green; overflow:auto; border-radius: 15px; margin: 10px 20px 0 20px;" class="<?=$DateTableClass?>">
				
					<?if(count($arrMap)>0){?> <br/><br/> <?}?>
					<?if(count($arrOtm)>0){?> <br/><br/> <?}?>

				<table  border='0'  cellspacing='0'   ><!--style='text-align:center;'-->
				<?
				
				for($i=0; $i<count($arrEv); $i++ ){		?>
					<!--<div id='<?=$arrEv[$i]['Url_ins']?>-DivNews' ><a href='index.php?c=soobsh&id_soobsh=<?=$arrEv[$i]['Url_ins']?>' title='<?=$titl_nT?>' ><?=$titl_nE?> :  <?=$dat[0]?></a></div>-->
					<tr <?if($i%2==0){?> style="background-color:#eee"<?}?>  >
						<?if($arrEv[$i]['dateE1'] != ''){ $title='Дата начала события';}else{ $title='Дата события';}?>
						<td   style='text-align:right; padding:3px;' title='<?=$title?>' >
							<?=$arrEv[$i]['dateB1']?>
						</td>
						
						
						<td style='text-align:right; padding:3px; position:relative; border-right:2px solid #999;'  <?if($arrEv[$i]['dateE1'] != ''){ echo " title='Дата окончания события' ";}?>  >
							<?=$arrEv[$i]['dateE1']?> 
						</td>	
						<td style=' padding:0px; margin:0px;'  >
								   <div style='position:relative;' >
								   <? if(!$arrEv[$i]['map_objects']){ 

									   if($arrEv[$i]['mapPict'] !=''){ 
											//echo "<b style='color:red; position:absolute; top:-5px; left-15px;'>mapIn</b>";
											echo "<img style='cursor:pointer; margin:0px; height:27px;' title='показать карту' src='MapPictureSmal/".$arrEv[$i]['mapPict']."'  onclick='ShowOnGraph(".$arrEv[$i]['id'].",".$i.")' />";
											echo "<b style='cursor:pointer; color:red; position:absolute; top:-7px; left:-18px;' onclick='ShowOnGraph(".$arrEv[$i]['id'].",".$i.")' >карта</b>";
									   }else{
											echo "<img style='cursor:pointer; margin:0px; height:27px;' title='показать карту' src='MapPictureSmal/mapvector1.jpg'  onclick='ShowOnGraph(".$arrEv[$i]['id'].",".$i.")' />";
									   }
									   
								   }?>
									</div>						   
						</td>
						<td style='text-align:left; padding:3px;' >
						<!--* <a href='ShowDate.php?id_date=<?=$arrEv[$i]['id']?>' target='blank' ><?=$date?> : <?=$arrEv[$i]['titl_nE']?></a>-->
						<? if(count($arrEv[$i]['country'])>1){ echo "<b style='color:red'>".count($arrEv[$i]['country'])."</b>";}?><select  name='co' title='страны связанные с событиями/датами' >
							<?foreach($arrEv[$i]['country'] as $ke=>$co){?>
							<option value='<?=$ke?>' ><?=$co?></option>
						<?}?>
						</select>
						<b title='категория события' style=' color:green; font-size:10px; '><?=$CategoryArr[$arrEv[$i]['category']]?></b>
						<?//var_dump($arrEv[$i]['country']);?>
						
						<span style='cursor:pointer;' id='date_tr_<?=$arrEv[$i]['id']?>' class='date_tr_cl' onclick="ShowOnGraph(<?=$arrEv[$i]['id']?>,<?=$i?>)"><?=$arrEv[$i]['titl_nE']?></span> <a title="посмотреть информацию о событии" href='ShowDate.php?id_date=<?=$arrEv[$i]['id']?>' target='blank' >-- > посмотреть</a>
						<?if($_SESSION['Guest_id']['id_user'] == $AdminID){ //если админ (выводим меню)?>
							|||<a href="EditDate.php?DelDate=<?=$arrEv[$i]['id']?>" target="blank" >удалить Дату </a> ||| <a href="EditDate.php?EditDate=<?=$arrEv[$i]['id']?>" target="blank" >редактировать Дату </a>
						<b onclick='ShowAdminMenu(this,event)' id='Dt-<?=$arrEv[$i]['id']?>' idd='country-<?=$arrEv[$i]['ids_country']?>+elsevar-?' >RED</b>
						<?}?>
		                <?
		                   /* if(!$arrEv[$i]['map_objects']){ echo "<b style='color:orange; position:relative;'>map";
		                       // mapPict
							   //echo $arrEv[$i]['mapPict'];
								   if($arrEv[$i]['mapPict'] !=''){ 
										//echo "<b style='color:red; position:absolute; top:-5px; left-15px;'>mapIn</b>";
										echo "<img style='cursor:pointer;  position:absolute; top:-18px; left:15px;' src='MapPictureSmal/".$arrEv[$i]['mapPict']."'  onclick='ShowOnGraph(".$arrEv[$i]['id'].")' />";
								   }
							   echo "</b>";
		                    }*/
		                ?>
						</td>
					</tr>
						
				<?}?>
				</table>
				</div>
			<?}?>
		</div>
	
	<?if(isset($textThema)){?>
		<div id="DateTable" style="padding:7px; border:5px solid green; overflow:auto; border-radius: 15px; margin: 10px 20px 20px 20px;" >
			<?=$textThema?>
		</div>
	<?}?>
	
	<script>//работа с меню карты
		

		
		
		
		var MapObjArr = {"Polygon":"Полигон","Polyline":"Линия","Arrow":"Стрелка","Placemark":"Метка","Rectangle":"Карта"};
		$(document).ready(function() { 
			//var MapObjArr = ["Полигон","Линия","Стрелка","Метка"];
			
			console.log('работа с меню карты')
			console.log($("#objShape option"))
			//for (var i=0; i<MapObjArr.length;i++){
			for (var key in MapObjArr) {
				//$("#objShape").find("option[value='Hot Fuzz']")
				console.log(key+MapObjArr[key])
				$("#objShape").find("option[value='"+key+"']").text(MapObjArr[key]);
				$(".objShapeCh[name='"+key+"']").val(MapObjArr[key]);
			}

			
			$(".objShapeCh").on('change',function(){
				for (var key in MapObjArr) {
					//$("#objShape").find("option[value='Hot Fuzz']")
					//console.log(key+MapObjArr[key])
					var v = $(".objShapeCh[name='"+key+"']").val();
					console.log(key+'-'+v)
					if(v != ''){
						MapObjArr[key] = v;
						$("#objShape").find("option[value='"+key+"']").text(v);
					}
				}				
			})
			
		
		});
	
	</script>
	
	
	<?if($_SESSION['Guest_id']['id_user'] == $AdminID){ //если админ (выводим меню)?>
			<script>//вывод меню админское
				var ids_country = '';//id автора речи
				function ShowAdminMenu(obj,event){//выводим меню к персоне
					//alert(obj.id+'ShowAdminMenu');
					var id = obj.id.split("-")[1]; 
					//alert(id);
					/*
					var varN = obj.getAttribute('idd');//разные переменные (между собой разделены +)
					varN = varN.split("+");
						var cy = varN[0].split("-");
						if(cy[0] == 'country'){
							ids_country = cy[1];
							//alert(cy[0]+" "+cy[1]);
						}
						*/
					//alert(id_news);
				//	IdDate = id;
					var opt = new Array(); //параметры
					opt.IndexZ = 4 //IndexZ
					//opt.fixed = 'fix'; //означает, что она зафиксирована экране
					var elemMenu = CreateElementOnScreen(260,event,opt);
					elemMenu.innerHTML = "<span onclick='deletElem(this)' id='delme' style='cursor:pointer; float:right; margin: -10px -5px 0 0;' > <span style='color:black;'>_</span><b>x</b><span style='color:black;'>_ </span></span><br />";  //закрывание этого списка
					elemMenu.innerHTML += "<div id='FP-"+id+"'  style='text-decoration:underline; cursor:pointer;'><span onclick='InsertFormChangeCategory(this)' >изменить категорию</span></div>";
					elemMenu.innerHTML += "<div id='FF-"+id+"'  style='text-decoration:underline; cursor:pointer;'><span onclick='InsertFormInsertCountry(this)' >добавить страну</span></div>";
					elemMenu.innerHTML += "<div id='FF-"+id+"'  style='text-decoration:underline; cursor:pointer;'><span onclick='InsertFormDelCountry(this)' >удалить страну</span></div>";
					document.body.appendChild(elemMenu);	
				}
				
				function deletElem(obj){//удаляем элемент меню
				//var elem = document.getElementById('CalendarDiv');
				obj.parentNode.style.display = 'none';
				obj.parentNode.parentNode.removeChild(obj.parentNode);
				}
				
				function InsertFormChangeCategory(obj){//форма изменить категорию
					//alert("InsertFormPodch ");
					id = obj.parentNode.id.split('-')[1];
					obj.parentNode.innerHTML = "<select id='"+id+"' onChange='ChangeCategory(this)' ><option value='' >выберите категорию</option><?foreach($CategoryArr as $k=>$th){?><option value='<?=$k?>' ><?=$th?></option><?}?></select>";
				}
				
				function ChangeCategory(obj){//изменить категорию
					//alert(obj.id+" "+obj.options[obj.selectedIndex].value);
					//alert("ffffgggg");
					//document.location.href = 'AddDates.php?id_theme='+obj.options[obj.selectedIndex].value;
					ChangeSomething('ChangeCategory',obj.id,obj.options[obj.selectedIndex].value);
				}
				
				function InsertFormDelCountry(obj){//форма удалить страну
					//alert("InsertFormPodch ");
					id = obj.parentNode.id.split('-')[1];//события
					obj.parentNode.innerHTML = "<select id='"+id+"' onChange='DelCountry(this)' ><option value='' >выберите страну для удаления</option><?foreach($CountryArr as $k=>$th){?><option value='<?=$k?>' ><?=$th?></option><?}?></select>";
				}
				
				function DelCountry(obj){//удалить страну
					ChangeSomething('DelCountry',obj.id,obj.options[obj.selectedIndex].value);
				}
				
				function InsertFormInsertCountry(obj){//форма добавить страну
					//alert("InsertFormPodch ");
					id = obj.parentNode.id.split('-')[1];//события
					obj.parentNode.innerHTML = "<select id='"+id+"' onChange='InsertCountry(this)' ><option value='' >выберите страну для добавления</option><?foreach($CountryArr as $k=>$th){?><option value='<?=$k?>' ><?=$th?></option><?}?></select>";
				}
				
				function InsertCountry(obj){//добавить страну
					ChangeSomething('InsertCountry',obj.id,obj.options[obj.selectedIndex].value);
				}				
				
				
				function ChangeSomething(name,v1,v2){//измения аяксом
						var arr;
						$.ajax({
						  async: false, 
						  url: 'blocks/dinamic_scripts/Change_Admin.php',
						  data: {name:name,val1:v1,val2:v2},
						  type: "POST",
						  success: function(data) {  arr = data; }//,
						//dataType: 'json'
						 })
						 //alert(arr);
				}				
				

			</script>
	<?}?>
	
	
	<script type="text/javascript">//постройка графика

	//alert('event');
	var ArrEvent = []; 
	var arrEv = JSON.parse('<?=json_encode($arrEv)?>');	
//	var scale = '<?=$scale?>'; //scale= parseInt(scale, 10);
		//кол-во лет и начальный год используется для всей таблицы
//	var nun_years = <?=$num_years?>;
	var year_begin = parseInt('<?=$ArrDMin[0]?>');

//если svg не работает то отрисовываем графики через табоицу (по старому)
function OldGraphic(){	
	//			alert('Svg not working');
				
				//id='tableDiv'
	document.getElementById('tableDiv').style.display = "block";

	$(document).ready(function() { 
		//передаем массив в js
		//var arrEv = jQuery.parseJSON('<?=json_encode($arrEv)?>');
	
		//alert(arrEv);
		
		if (arrEv != null){
			//ограничиваем кол-во дат
			var nd = 300;
			
			
	//	


			
			if (arrEv.length > nd){
				alert("Используйте фильтр для более точного поиска (например фильтр->Ключевые слова) \r\n т.к. по данным условиям нашлось "+arrEv.length+" событий/дат/новостей \r\n Вывод ограничен в размере "+nd);
			}
			for(var i=0; i<arrEv.length; i++){
				if (i >= nd){  break;  }
				//alert(arrEv[i]['date_Beg']+" - "+arrEv[i]['date_End']+" - "+arrEv[i]['event']);
				
				
				<?/*
				if($_SESSION['ISBot'] == 'no'){?>
					var event1 = arrEv[i]['event'];
					
				<?}else{?>
					var event1 = arrEv[i]['event_changed'];
					//alert(arrEv[i]['event_changed']);
				<?}*/
				?>
				var event1 = arrEv[i]['event'];
				
				//отрисовываем 
				if(arrEv[i]['date_End'] !='0000-00-00 00:00:00'){
					//alert(arrEv[i]['date_End']);
					
					
					DrawingForDates(arrEv[i]['date_Beg'],arrEv[i]['date_End'],event1);
				}else{
					//alert(arrEv[i]['event']);
					//alert(arrEv[i]['date_Beg']);
					DrawingForDatesSin(arrEv[i]['date_Beg'],event1,arrEv[i]['Url_ins']);
					
				}
			}
		}

	});
	



		//var Koef = 3

		var Month = 10*scale; //кол-во пикселей в месяце
		//var Kvart = 3*Month; 
		var KolDays = 3*Month;//дней в квартале
	//	alert('KolDays-'+KolDays);
	//	var KolDays = 90;
		//закрашивание элементов
			//полное
			var full = "<span style='border-left:"+KolDays+"px solid green; '></span>";
			//var full = "<span style='border-left-color:green; border-left-width:90px; border-style:solid; '></span>";
			//частичное
			function giveNoFullGr (day_beg, day_end){//частичное закрашивание квартала
				//alert(day_beg +"---"+day_end);
		
				
				if (day_end < day_beg){
					//day_end = day_beg+2;
	//				alert('Дата начала меньше даты окончания!');
					return "<span style='border-left:"+KolDays+"px solid white; '></span>";
				}else{
					
					var difer = day_end - day_beg;  if(difer <1){difer=3; } if(difer > KolDays){ difer = KolDays;} 
					if(day_beg <1){day_beg=0; }
					var difer2 = KolDays - (day_beg + difer); //if(difer2 <0){difer2=1; alert("uuu");}
					
					return "<span style='border-left:"+day_beg+"px solid white; ' id='sdf1' ></span>  <span id='sdf2' style='border-left:"+difer+"px solid green; '></span> <span id='sdf3' style='border-left:"+difer2+"px solid white; '></span>";
				}
			}
			
			//определение квартала
			function GetKvartal(month){
			var kvartal;
				if(month <4){ kvartal = 1; }
				if((month >3) && (month <7)){ kvartal = 2; }
				if((month >6) && (month <10)){ kvartal = 3; }
				if((month >9) && (month <13)){ kvartal = 4; }
				return kvartal;
			}
			
			function GetDay(kvartal,month,day){		//определяем номер дня в квартале (0-90) упростим	
				var month_begin = ((kvartal*3)-2); //месяц начала квартала
				month_begin = (parseInt(month, 10)) -  month_begin; //сколько полных месяцев прошло от начала квартала
				day_begin = month_begin*30 + parseInt(day, 10); if(day_begin > 90){day_begin= 90; }
				day_begin; //номер числа месяца от 1 до 30
				var  piks_begin = Math.ceil(day_begin*Month/30); //находим како это будет пиксель
			//	alert('Month='+Month+' |month='+month+' |day='+day+' |day_begin='+day_begin+' |piks_begin='+piks_begin)
				return piks_begin;
			//	return day_begin;
			}
			
			

			
			//alert(year_begin);
			
			//var Un_idP = 1;
			//закрашивание даты	(промежутка)
			function DrawingForDates(date1,date2,eventSh){
				
					//alert(date1 + date2);
				
				var arrN1 = date1.split(' ');
				var arrN2 = date2.split(' ');
				
				//парсим  даты
				var arr1 = arrN1[0].split('-');
				var arr2 = arrN2[0].split('-');
				 
				//определяем квартал
				var kvartal_begin= GetKvartal(arr1[1]);
				var kvartal_end= GetKvartal(arr2[1]);
				//alert(kvartal_begin +"---"+kvartal_end);
				

				//определяем номер дня в квартале (0-90) упростим
				var day_begin = GetDay(kvartal_begin,arr1[1],arr1[2]);
				var day_end = GetDay(kvartal_end,arr2[1],arr2[2]);
				//alert(day_begin +"---"+day_end);
				
				
				
				
				var MonthLet = new Array('','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
				var m1 = parseInt(arr1[1]);
				var m2 = parseInt(arr2[1])
				var dateSh1 = arr1[2]+" "+MonthLet[m1]+" "+arr1[0];
				var dateSh2 = arr2[2]+" "+MonthLet[m2]+" "+arr2[0];
				
				var n = 15; var strE = ''; if(eventSh.length > n){ strE = '...';}
				var eventSh_urez = eventSh.substr(0,n)+strE;

					var newRow = document.getElementById('table').insertRow();
					for(var i=0; i<nun_years+1; i++){
						for(var j=1; j<5; j++){
			//Un_idP++;
							var  newEl = newRow.insertCell();
							newEl.title = eventSh;
							newEl.style.position = "relative";
							
							<?if(($scale >=1)){ //в зависимости от масштаба выводим рамку квартала?>
								newEl.style.borderRightStyle ="solid";    //"1px solid green;";      //border-left:1px solid red;
								newEl.style.borderRightWidth  ="1px";  
								newEl.style.borderRightColor ="red"; 
							<?}else{?>
								if(j==4){//если год
									newEl.style.borderRightStyle ="solid";   
									newEl.style.borderRightWidth  ="1px";  
									newEl.style.borderRightColor ="red"; 							
								}
							
							<?}?>
								//окно подсказака
								var newWin = document.createElement('span');
								newWin.style.position = "absolute";
								newWin.style.top = "8px";
								newWin.style.fontSize = "12px";
								//newWin.style.left = "5px";
								newWin.style.zIndex = 30;
								newWin.style.whiteSpace = "nowrap";
								//newWin.id = Un_idP;
						
						var TypeOfDate = 0;	
							if((year_begin+i < arr1[0]) || (year_begin+i>arr2[0])){
							}else							
							//if((i==0) || (i == (nun_years))){
							if((year_begin+i==arr1[0]) || (year_begin+i == arr2[0])){
								//if((i==0) && (i==nun_years)){//если это год начала  и окончаения одновременно, то закрашиваем нужный квартал
								if((year_begin+i==arr1[0]) && (year_begin+i == arr2[0])){
									if(kvartal_begin == kvartal_end ){//если не в одном квартале
										if(j == kvartal_begin){
											newEl.innerHTML += giveNoFullGr(day_begin,day_end);
											TypeOfDate = 3;	
											//newRow = null; break;
										}
									}else{
											if((j>kvartal_begin) && (j<kvartal_end)){//проверяем 	
												newEl.innerHTML += full;
												//alert('here-'+i+'-'+j);	
											}else{
												if(j==kvartal_begin){
													newEl.innerHTML += giveNoFullGr(day_begin,KolDays);
													TypeOfDate = 1;
												}
												if(j==kvartal_end){
													newEl.innerHTML += giveNoFullGr(0,day_end);
													TypeOfDate = 2;
												}
											}								
									}
								}else{					
									if(year_begin+i==arr1[0]){
									//if(i==0){//если это год начала, то закрашиваем нужный квартал
										if(j >= kvartal_begin){//проверяем тот ли это квартал
											if(j > kvartal_begin){
												newEl.innerHTML += full;
											}else{//если дата прямо в этом квартале, то закрашиваем частично
											//alert('rrr'+kvartal_begin+"-"+j);
												newEl.innerHTML += giveNoFullGr(day_begin,KolDays);
												TypeOfDate = 1;
											}
										}
									}else{//если это год окончания, то закрашиваем нужный квартал
										if(kvartal_end >= j){//проверяем тот ли это квартал
											if(j<kvartal_end){	
													newEl.innerHTML += full;								
											}else{
												newEl.innerHTML += giveNoFullGr(0,day_end);
												TypeOfDate = 2;
											}
										}
									}
								}
							}else{
								newEl.innerHTML += full;
							}
							newEl.height = 10;

							if(TypeOfDate == 1){
								newWin.innerHTML = "&nbsp;<b title='"+dateSh1+" | "+eventSh+"' style='border:1px solid blue; color:black'>"+eventSh_urez+" (Начало)</b> <b style='position:relative; top:-10px; color:red; cursor:pointer;' onclick='this.parentNode.style.display=\"none\"; return false;' >х</b>";
							}else{
								if(TypeOfDate == 2){
									newWin.innerHTML = "&nbsp;<b title='"+dateSh2+" | "+eventSh+"' style='border:1px solid blue; color:black'>"+eventSh_urez+" (Конец)</b> <b style='position:relative; top:-10px; color:red; cursor:pointer;' onclick='this.parentNode.style.display=\"none\"; return false;' >х</b>";
								}else{								
									if(TypeOfDate == 3){
									newWin.innerHTML = "&nbsp;<b title='"+dateSh1+"-"+dateSh2+" | "+eventSh+"' style='white-space:nowrap; border:1px solid blue; color:black;'>"+eventSh_urez+"</b> <b style='position:relative; top:-10px; color:red; cursor:pointer;' onclick='this.parentNode.style.display=\"none\"; return false;' >х</b>";
									}
								}
							}		
							var el = newEl.firstChild;
							if(el){
									el = el.nextSibling;
									//alert("f-n");
									if(el && (TypeOfDate == 2)){
										el = el.nextSibling;
										el = el.nextSibling;
										//alert("f-k");
									}
							}
							newEl.insertBefore(newWin, el);
						}
					}
			}
		
			//закрашивание даты	(даты)
			var Un_id = 1;
			function DrawingForDatesSin(date,eventSh,IdNews){
				//alert("date-"+date +" year_begin"+year_begin);
				var arrN1 = date.split(' ');
				
				//парсим  даты
				var arr1 = arrN1[0].split('-');
 //alert(arr1[2]);
				//определяем квартал
				var kvartal= GetKvartal(arr1[1]);

				//определяем номер дня в квартале (0-90) упростим
				var day= GetDay(kvartal,arr1[1],arr1[2]);
				
				var MonthLet = new Array('','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
				var m = parseInt(arr1[1]);
				var dateSh = arr1[2]+" "+MonthLet[m]+" "+arr1[0];
				
				var n = 45; var strE = ''; if(eventSh.length > n){ strE = '...';}
				var eventSh_urez = eventSh.substr(0,n)+strE;
					//eventSh_urez.replace('"','\"');

				//создаем строку с единичными датами (не промежуток)
				var newRow = document.getElementById('rowSingleDates');
				if(newRow != null){
				//alert('jjj'+newRow);
					newRow;
				}else{
					newRow = document.getElementById('table').insertRow();
					newRow.id = 'rowSingleDates';
					for(var i=0; i<nun_years+1; i++){
						for(var j=1; j<5; j++){
							//заполняем ее ячейками td
							var  newEl = newRow.insertCell();
							newEl.id = 'el-'+i+"-"+j;
							newEl.style.position = "relative";
							
							<?if(($scale >=1)){ //в зависимости от масштаба выводим рамку квартала?>
								newEl.style.borderRightStyle ="solid";    //"1px solid green;";      //border-left:1px solid red;
								newEl.style.borderRightWidth  ="1px";  
								newEl.style.borderRightColor ="red"; 
							<?}else{?>
								if(j==4){//если год
									newEl.style.borderRightStyle ="solid";   
									newEl.style.borderRightWidth  ="1px";  
									newEl.style.borderRightColor ="red"; 							
								}
							
							<?}?>
							
							
							
							
						}
					}				
				}
				
				//alert('arr1[1]-'+arr1[1]+'  | kvartal-'+kvartal);
				
						
				//пробегаемся
				for(var i=0; i<nun_years+1; i++){
					for(var j=1; j<5; j++){
						//var  newEl = newRow.insertCell();
	Un_id++;
						if(year_begin+i == arr1[0]){
							
							//alert((year_begin+1) +" - - -"+arr1[0]);
							if(year_begin+i == arr1[0]){
								if(j == kvartal){
								
								YSmesh = 1;
								
									//окно подсказака (с датой)
									var newWin = document.createElement('span');
									newWin.style.position = "absolute";
									newWin.style.whiteSpace = "nowrap";
									//newWin.style.background = '#bbb';
									newWin.style.fontSize = "12px";
									newWin.style.zIndex = 30;
								//	newWin.style.top = "1"+YSmesh+"px";
									//newWin.style.left = "1"+j+"px";
									var href =''; var hrefEnd ='';
									if(IdNews !='')//если новость былапеределана есть id
									{
										href="<a href='index.php?c=soobsh&id_soobsh="+IdNews+"' target='_blank' >";
										hrefEnd="</a>";
									}
									newWin.innerHTML = "&nbsp;<b onmouseover='PaintTDOnFocus(this,\""+day+"\");' onmouseout='PaintTDOnFocusOut(this,\""+day+"\");'  title='"+dateSh+" | "+eventSh+"' style='white-space:nowrap; border:1px solid #aaa; color:black;'>"+href+eventSh_urez+hrefEnd+"</b> <b style='position:relative; top:-10px; color:red; cursor:pointer;' onclick='this.parentNode.style.display=\"none\"; return false;' >х</b>";									
																		
								
								
									var predTD = document.getElementById('el-'+i+"-"+j);
								//подсчитаем дивы
								YSmesh = predTD.getElementsByTagName('div').length;
								if((predTD.innerHTML !='') && (YSmesh==0)){YSmesh=1;}
								//alert(YSmesh);
								newWin.style.top = 10+YSmesh*10+"px";
								newWin.id = 	Un_id;							
									
									if(predTD.innerHTML !=''){//если уже есть дата
										//alert('predTD-'+predTD)
											//новое закрашивание (новый тд поверх этого)
											var newTD = document.createElement('div');
											newTD.style.position = "absolute";
											newTD.style.top = "0px";
											newTD.style.left = "0px";
											//newTD.style.zIndex = 30;
											//newTD.style.background = "none";
											//newTD.style.left
												var newTDIn = document.createElement('div');
												newTDIn.id = Un_id+"-TDIn";
												//newTDIn.innerHTML = "<span id='"+YSmesh+"-spPaint' >"+PaintTD(day,'green')+"</span>";
												newTDIn.innerHTML = PaintTD(day,Un_id);
												newTDIn.style.position = "relative";
											newTD.appendChild(newTDIn);
											document.getElementById('el-'+i+"-"+j).appendChild(newTD);
											//newEl.insertBefore(newTD, el);

											//el = el.nextSibling; 
											//el = el.nextSibling;
											
								
										//alert(arr1[2]);
									}else{
										
										predTD.innerHTML = PaintTD(day,Un_id);
										newTDIn = predTD;
									}
									
										var el = newTDIn.firstChild;
										el = el.nextSibling;
									newTDIn.insertBefore(newWin, el);
									
									//удаляем дивы с новостями для ксироманивя
									if(document.getElementById(IdNews+'-DivNews')){
										document.getElementById(IdNews+'-DivNews').innerHTML = '';
									}
									
							//	var el = newTD.firstChild;
							//	newTD.insertBefore(newWin, el);
									//alert('el-'+i+"-"+j);
									//newEl.innerHTML += giveNoFullGr(day_begin,day_end);
									//TypeOfDate = 3;	
									//newRow = null; break;
								}
							}
						
						}
					}
				}			

			}
			

			//закрашивание TD
			function PaintTD (day_beg,id){//частичное закрашивание квартала
				if(day_beg < 0){ day_beg=1;}
				difer = 3;
				var difer2 = KolDays - (day_beg + difer);
				return "<span style='border-left:"+day_beg+"px solid transparent; '></span><span id='"+id+"-colPa' style='border-left:"+difer+"px solid green; '></span><span style='border-left:"+difer2+"px solid transparent; '></span>";
			}

			//закрашивание при наведении
			function PaintTDOnFocus(obj,day){
				document.getElementById(obj.parentNode.id+'-colPa').style.borderLeft = "6px solid red";
			}
			function PaintTDOnFocusOut(obj,day){
				document.getElementById(obj.parentNode.id+'-colPa').style.borderLeft = "3px solid green";
			}
			
			
			
			var date1 = '<?=$minDate?>';
			var date2 = '<?=$maxDate?>';

}			
/*
	DrawingForDates(date1,date2,'Промежуток какой-то 1');
			DrawingForDates('2012-09-14 00:00:00','2012-11-08 00:00:00','Промежуток какой-то 2');
			DrawingForDates('2014-02-14 00:00:00','2014-28-09 00:00:00','Промежуток какой-то 3');
			//DrawingForDates('2014-05-14 00:00:00','2014-05-14 00:00:00','Промежуток какой-то 4');
			
			DrawingForDatesSin('2014-08-14 00:00:00','Хрень какая-то 4');
			//DrawingForDatesSin('2012-08-14 00:00:00','');
			
			DrawingForDatesSin('2013-01-11 00:00:00','Хрень какая-то 1');
		DrawingForDatesSin('2013-02-12 00:00:00','Хрень какая-то 2');
		DrawingForDatesSin('2013-03-13 00:00:00','Хрень какая-то 3');
		
		*/
		
	</script>
	

	

 
 <script>
 //если браузер поддерживает рисуем даты в SVG
	   // http://wbtech.ru/blog/svg-images-and-backgrounds/
var ttt = 'hhhh1'

	var ArrEvent = [];   
	//ArrEvent.push('gg')
	
	
//RecrArrEvent.push('gg')
//alert(arrEv)
var arrEv_Once = [];
var arrEv_OnceN = [];
var arrEv_Period = [];
var arrEv_PeriodN = [];
var scaleN = scale*1;				
var yearW = scaleN*365;
var kvW = Math.ceil(scaleN*365/4);
var monthW = Math.ceil(scaleN*365/12);
var svg = document.getElementById("svg_table");//.getSVGDocument();
yearB = <?=$ArrDMin[0]?>-1+1;

function GroupEventInLine(){
			//пробегаемся по событиям, одинарные групируем (и если одинаковая дата то тоже группируем)
		//var arrEv_Once = [];
		//var arrEv_Period = [];
		for(var i=0; i<arrEv.length; i++ ){
			if(arrEv[i]['date_End'] =='0000-00-00 00:00:00'){
				arrEv_Once.push(arrEv[i]);
			}else{
				arrEv_Period.push(arrEv[i]);
			}
		}
		
		//console.log('arrEv_Once'+JSON.stringify(arrEv_Once))
		
		//группируем события если дата одинаковая
		//var arrEv_OnceN = [];
		for(var i=0; i<arrEv_Once.length; i++ ){
			var arrDates = [];//список событий на дату
			var haveBeen = false;
			for(var j=0; j<arrEv_OnceN.length; j++ ){
				if(arrEv_Once[i]['date_Beg']==arrEv_OnceN[j]['date_Beg']){
					arrEv_OnceN[j]['arrDates'].push([arrEv_Once[i]['id'],arrEv_Once[i]['event']]);
					haveBeen = true;
				}
			}
			if(!haveBeen){
				arrEv_Once[i]['arrDates'] = []
				//arrEv_Once[i]['arrDates'].push(arrEv_Once[i]['id']+"/|/"+arrEv_Once[i]['event']);
				arrEv_Once[i]['arrDates'].push([arrEv_Once[i]['id'],arrEv_Once[i]['event']]);
				arrEv_OnceN.push(arrEv_Once[i]);
			}
		}
		
		//console.log('arrEv_OnceN'+JSON.stringify(arrEv_OnceN))		
		
		//проходимся по длительным событиям если даты пересекаются создаем новую линию, иначе добавляем к имеющейся
		//группируем события если дата одинаковая
		//var arrEv_PeriodN = [];
		var lineNum = 0;
		for(var i=0; i<arrEv_Period.length; i++ ){
			var haveBeenAdded = false;//был ли элемент добавлен на какую либо линию
			var Beg = toTime(arrEv_Period[i]['date_Beg']);
			var End = toTime(arrEv_Period[i]['date_End']);
			arrEv_Period[i]['arrDates'] = [];
			arrEv_Period[i]['arrDates'].push([arrEv_Period[i]['id'],arrEv_Period[i]['event']]);
			for(var j=0; j<arrEv_PeriodN.length; j++ ){//проходимся по линиям
				var haveBeenLine = false;
				for(var k=0; k<arrEv_PeriodN[j].length; k++ ){//проверяем на линии пересечение
					//if(i!=j){
						var Beg2 = toTime(arrEv_PeriodN[j][k]['date_Beg']);
						var End2 = toTime(arrEv_PeriodN[j][k]['date_End']);
						/*if((Beg<Beg2 && End<Beg2) || (Beg>End2 && End>End2)){
						}else{*/
						if((Beg>Beg2 && Beg<End2) || (End>Beg2 && End<End2)){
							//alert('haveBeenLine crosssssss');
						//	alert(arrEv_Period[i]['date_Beg']+' Beg-Beg2 '+arrEv_PeriodN[j][k]['date_Beg']+ "-------"+arrEv_Period[i]['date_End']+" End-End2 "+arrEv_PeriodN[j][k]['date_End']);
							haveBeenLine = true; //т.е на этой линии мы уже пересекемся с другим элементом
							break;
						}
						
						//alert(arrEv_Period[i]['date_Beg']+' Beg-Beg2 '+arrEv_PeriodN[j][k]['date_Beg']+ "-------"+arrEv_Period[i]['date_End']+" End-End2 "+arrEv_PeriodN[j][k]['date_End']);
							
							//arrEv_Period[i]['lineNum'] = lineNum;
							//lineNum++;
				}
				if(!haveBeenLine){//если на этой линии мы не пересеклись с другими событиями, добавляем событие в эту линию
					//alert('haveBeenLine-'+j)
					arrEv_PeriodN[j].push(arrEv_Period[i]);
					haveBeenAdded = true;
					break;
				}
			}
			if(!haveBeenAdded){
				//alert(lineNum)
				arrEv_PeriodN[lineNum] = [];
				arrEv_PeriodN[lineNum].push(arrEv_Period[i]);
				//alert(arrEv_PeriodN[lineNum])
				lineNum++;
			}
			
		}		
		
		//console.log('arrEv_PeriodN'+JSON.stringify(arrEv_PeriodN))
        console.log('arrEv_PeriodN') 
        console.log(arrEv_PeriodN)        
}



	function SVGRender(arrEv) {
			   //svg
			   while (svg.lastChild) {
					svg.removeChild(svg.lastChild);
				}
			   var supportsSVG = !!document.createElementNS && !!document.createElementNS("http://www.w3.org/2000/svg", "svg").createSVGRect;
			//После этой проверки легко найти все случаи использования SVG и заменить пути к векторным изображениям на пути к альтернативной растровой графике.
			if(supportsSVG){
				if(nun_years ==0){nun_years =1;}
				GroupEventInLine();
				console.log('--------------nun_years');
                console.log(nun_years);
				console.log(year_begin);
                console.log(yearB);
				//console.log(JSON.stringify(arrEv));
				var offset =0; //отступ в строках
				
				var lineCol = arrEv_PeriodN.length+2;
				var hh = (5+lineCol)*20+'px'
				
				
                for(var i=0; i<nun_years+2; i++ ){//рисуем года
					var l=line(10+yearW*i,10,10+yearW*i,hh,"orange")
					svg.appendChild(l);	
	//				var t=text(16+yearW*i,20,'10px',yearB+i)
                    t=text(16+yearW*i,20,'10px',year_begin+i)
					svg.appendChild(t);
				}
				
				if(scale>0.4){
					//alert('kv');
					offset++;
					for(var i=0; i<nun_years+1; i++ ){//рисуем кварталы
						/*var l=line(10+yearW*i,10,10+yearW*i,300,"yellow")
						svg.appendChild(l);	
						var t=text(16+yearW*i,20,'10px',yearB+i)
						svg.appendChild(t);*/
						var l=line(10+yearW*i+kvW,10+20*offset,10+yearW*i+kvW,hh,"yellow")
						var l1=line(10+yearW*i+kvW*2,10+20*offset,10+yearW*i+kvW*2,hh,"yellow")
						var l2=line(10+yearW*i+kvW*3,10+20*offset,10+yearW*i+kvW*3,hh,"yellow")
						//var l3=line(10+yearW*i+kvW*4,10+20*offset,10+yearW*i+kvW*4,hh,"yellow")
						svg.appendChild(l);	
						svg.appendChild(l1);	
						svg.appendChild(l2);	
						//svg.appendChild(l3);	
						var t=text(16+yearW*i+kvW*0,20+20*offset,'10px','1кв')
						var t1=text(16+yearW*i+kvW*1,20+20*offset,'10px','2кв')
						var t2=text(16+yearW*i+kvW*2,20+20*offset,'10px','3кв')
						var t3=text(16+yearW*i+kvW*3,20+20*offset,'10px','4кв')
						svg.appendChild(t);
						svg.appendChild(t1);
						svg.appendChild(t2);
						svg.appendChild(t3);
					}
				}
				
				if(scale>0.9){
					//alert('kv');
					offset++;
					var MonthLet = new Array('январь','февраль','март','апрель','май','июнь','июль','август','сентябрь','октябрь','ноябрь','декабрь');
					for(var i=0; i<nun_years+1; i++ ){//рисуем месяцы
						for(var j=0; j<12; j++ ){
							if(j!=0 && j!=3 && j!=6 && j!=9){
								var l=line(10+yearW*i+monthW*j,10+20*offset,10+yearW*i+monthW*j,hh,"#ccc")
								svg.appendChild(l);
							}
								var txt = MonthLet[j];
								if(scale<2){ txt = txt.substring(0,3);}
								//var l=line(10+yearW*i+monthW*j,10+20*offset,10+yearW*i+monthW*j,hh,"#ccc")
								var t=text(16+yearW*i+monthW*j,20+20*offset,'10px',txt)
								svg.appendChild(t);							
						}
						
						//MonthLet[m]
						//var l=line(10+yearW*i+kvW,10+20*offset,10+yearW*i+kvW,hh,"yellow")
						/*var l1=line(10+yearW*i+kvW*2,10+20*offset,10+yearW*i+kvW*2,hh,"yellow")
						var l2=line(10+yearW*i+kvW*3,10+20*offset,10+yearW*i+kvW*3,hh,"yellow")
						//var l3=line(10+yearW*i+kvW*4,10+20*offset,10+yearW*i+kvW*4,hh,"yellow")
						svg.appendChild(l);	
						svg.appendChild(l1);	
						svg.appendChild(l2);	
						//svg.appendChild(l3);	
						var t=text(16+yearW*i+kvW*0,20+20*offset,'10px','1кв')
						var t1=text(16+yearW*i+kvW*1,20+20*offset,'10px','2кв')
						var t2=text(16+yearW*i+kvW*2,20+20*offset,'10px','3кв')
						var t3=text(16+yearW*i+kvW*3,20+20*offset,'10px','4кв')
						svg.appendChild(t);
						svg.appendChild(t1);
						svg.appendChild(t2);
						svg.appendChild(t3);*/
					}
				}
				/*
				for(var i=0; i<arrEv.length; i++ ){
					AddEventToSvg(arrEv[i],i);
				}*/
				
				//document.getElementById('svg_table').style.display = "none";
				//SVGObj.setAttribute("height",h);
				svg.setAttribute("width",Math.ceil(yearW*nun_years*1.3)+'px');
				
				//var lineCol = 0;
				for(var i=0; i<arrEv_OnceN.length; i++ ){
					AddEventToSvg(arrEv_OnceN[i],offset)
				}
				for(var i=0; i<arrEv_PeriodN.length; i++ ){
					//AddEventToSvg(arrEv_PeriodN[i],i)
					for(var j=0; j<arrEv_PeriodN[i].length; j++ ){
						AddEventToSvg(arrEv_PeriodN[i][j],i+offset+1);
						//lineCol++;
					}
				}
				svg.setAttribute("height",(2+offset+lineCol)*20+'px');
				
				
			}else{
				document.getElementById('svg_table').style.display = "none";
				//alert('Svg not working');
				OldGraphic();
			}
	}

	
function toTime(date){
		 var arrD = date.split(" ")[0].split("-");
	 if(arrD[1] =='00'){arrD[1] = '01';} if(arrD[2] =='00'){arrD[2] = '01';}
	 thisday = new Date(arrD[1]+"/"+arrD[2]+"/"+arrD[0]);	
	return thisday.getTime();
}


function AddEventToSvg(event,line){
		var DateB = DateToPixel(event['date_Beg'])


	
	if(event['date_End']=='0000-00-00 00:00:00'){
		var DateE = DateB+2;
	}else{
		var DateE = DateToPixel(event['date_End']);
	}
	
	if(DateE==0){DateE=3;}
	
	dbY = event['date_Beg'].split("-")[0];
	deY = event['date_End'].split("-")[0];
	
	//находим промежуток лет
	difYear = 0;
	if(deY != "0000"){
		difYear = deY - dbY
	}
	
	//находим offset лет
	offsetYear = 0;
	//if(dbY != yearB){
	//	offsetYear = dbY - yearB 
 	if(dbY != year_begin){
		offsetYear = dbY - year_begin 
	}

	DateB = Math.ceil(DateB*scaleN);
	DateE = Math.ceil(DateE*scaleN);

	DateE = DateE + difYear*yearW;	
	//offsetEnd = DateE + difYear*yearW+offsetYear*yearW;
	
	offset = DateB+offsetYear*yearW;
	
	width  = DateE- DateB;
	if(width ==0){width = 1;}
	//width  = offsetEnd- offset;
	console.log("DateB-"+DateB+" DateE-"+DateE+" width-"+width+" difYear-"+difYear+" offsetYear-"+offsetYear)
	
	
	var r= rect(10+offset,30+20*line,19,width,"blue");
	if(event['arrDates'].length>1){
		var t=text(10+offset,30+20*line,'15px','n')
		svg.appendChild(t);		
	}
	//r.id = 'event_win_'+event['id'];
	//event['arrDates']
	//r.className = 'event_winbbb_'+event['id'];
	
	var idR = ''//event['arrDates']	
	for (var i = 0; i < event['arrDates'].length; i++) {
		idR += "event_win_"+event['arrDates'][i][0];
	}
	r.id =idR;
	
	ArrEvent.push(r);

//console.log("--------------event1-----------------");
//console.log(event['date_Beg'].split(" ")[0] +"   --- " +event['date_End'].split(" ")[0]);
var arrDb = event['date_Beg'].split(" ")[0].split("-");
var arrDe = event['date_End'].split(" ")[0].split("-");
var Datee = (arrDb[2]!="00"?arrDb[2]+".":"") + (arrDb[1]!="00"?arrDb[1]+".":"")+(arrDb[0]!="0000"?arrDb[0]:"")+(event['date_End'].split(" ")[0]=="0000-00-00"?"":"-")+(arrDe[2]!="00"?arrDe[2]+".":"") + (arrDe[1]!="00"?arrDe[1]+".":"")+(arrDe[0]!="0000"?arrDe[0]:"")+" | " ;//
//console.log(Datee);
	//выводим окно события
	(function(arrText){
		//r.onclick = function(){alert(text)}
		//r.addEventListener("mouseover",function(){alert(text)}) ;
		//r.addEventListener("mouseover",function(e){showEvent(e,arrText,id);} );
		r.addEventListener("mouseover",function(e){ 
				//убираем выделения
				for (var i = 0; i < ArrEvent.length; i++) {
					ArrEvent[i].style.fill='blue';
				}
				//выделяем наш элемент
				this.style['border'] = '2px solid red';
				this.style.fill='red';



			console.log("addEventListener");
			showEvent(e,arrText,event['id'],r,Datee);} 
		);
	})(event['arrDates']);
	svg.appendChild(r);
		
	//закрепляем окно события
	(function(id){
		//r.onclick = function(){alert(text)}
		//r.addEventListener("mouseover",function(){alert(text)}) ;
		r.addEventListener("click",function(e){showEventPerm(id);} );
	})(event['id']);
	svg.appendChild(r);				
}
	
//выводим временную рамку с текстом события	
//function showEvent(event,text,id){
function showEvent(event,arrText,id,th,Datee){
	
	console.log("showEvent")
	//- $("#div_svg").offset().top
	//alert('arrText-'+arrText)
	if (!event){
		event = window.event;

	}
	
	coord = {
		pageX : 0,
		pageY : 0
	}

	console.log(th)
	if(th !=0){
		//находим координаты элемента внутри блока div_svg, для дальнейшего добавления текста
			//var th = document.getElementById("event_win_"+id); //
			
			//console.log(th)
			//console.log("scrollLeft-"+$("#div_svg").scrollLeft()) 
		//	console.log($(th).offset().left - $("#div_svg").offset().top);
			coord.pageX = $(th).offset().left - $("#div_svg").offset().left  + $("#div_svg").scrollLeft()
			coord.pageY=  ($(th).offset().top - $("#div_svg").offset().top+7)
	
		//console.log("$(th).offset().left-"+$(th).offset().left);
		//console.log("$(#div_svg).offset().top-"+ $("#div_svg").offset().top+"$(th).offset().top-"+ $(th).offset().top) 
	}

		var x = document.getElementsByClassName("ev_lab_cl")
			for (var i = 0; i < x.length; i++) {
				//alert(x[i].innerHTML)
				//x[i].style['border'] = '0px solid black';
				hide(x[i]);
			}

			text1 = '';
			var classname = ''
			for (var i = 0; i < arrText.length; i++) {
				text1+="<span onclick='goEvent("+arrText[i][0]+")' style='cursor:pointer; text-decoration:underline; color:blue;'>показать...</span><br />"+Datee+arrText[i][1]+"<br />"
				
				classname += ' ev_lab_'+ arrText[i][0];
			}
	
	
			//obj=document.getElementById('ev_lab_'+id)
			obj=document.getElementsByClassName("ev_lab_"+arrText[0][0])
			
			objK=document.getElementById('ev_labKon_'+arrText[0][0])
			//alert("ev_lab_"+arrText[0][0]+'='+obj.length +'-'+objK)
			if((obj.length == 0) && (objK == null)){
				var el = document.createElement('div');  //создаем див для формы
				el.innerHTML = "<span onclick='hide(this.parentNode) ' style='cursor:pointer; float:right; margin: -10px -5px 0 0;' > <span style='color:black;'>_</span><b>x</b><span style='color:black;'>_ </span></span><br />";  //закрывание этого списка
				//el.innerHTML += "<span onclick='goEvent("+id+")' style='cursor:pointer; text-decoration:underline; color:blue;'>показать...</span><br />"+text
				el.innerHTML +=text1;
				el.style['padding'] = '10px';
				el.style.overflow = "auto"; //overflow:auto;
				el.style['border'] = '1px solid black';
				el.style['width'] = '300px';
				/*el.style['background-color'] = '#aaa';
				el.style['opacity'] = '0.5';*/
				el.style['background'] = 'rgba(180, 180, 180, 0.4)';
				//background: rgba(180, 180, 180, 0.4);
				//el.style['color'] = 'white';	
				el.style.position = 'absolute';
		//el.style.position = 'fixed';
				//el.style.position = 'fixed';
					el.style['left'] = coord.pageX+"px";
					el.style['top'] = coord.pageY+"px";
					//alert(event.pageX +"--"+event.pageY)
				//	el.id = 'ev_lab_'+id
					el.className = 'ev_lab_cl '+classname;
					//el.zIndex = 10000000;

//				document.body.appendChild(el)
		document.getElementById("div_svg").appendChild(el)
			}
			//el.addEventListener("onclick",function(e){showEvent(e,text,id);}
			
			//goEvent(id);


			//alert('lll');
			/*location.href = "#";
			location.href = "#date_tr_"+id;*/
			
			/*$("html,body").animate({scrollTop: $(elll).offset().top-200,
				scrollLeft: 1000
			}, 1000);*/
}

//закрепляем временную рамку с текстом события	
function showEventPerm(id){
		//obj=document.getElementById('ev_lab_'+id)
		obj=document.getElementsByClassName("ev_lab_"+id)
			//alert(obj)
		if(obj[0] != null){
			obj[0].id = 'ev_labKon_'+id
			//obj[0].className = 'ev_lab_cl1'
			obj[0].className = 'ev_lab_cl1'+ obj[0].className.split('ev_lab_cl')[1] 
			obj[0].style['border'] = '3px solid green';
		}
}

function goEvent(id){
				CurrentEnentNum = id;
				
				console.log('goEvent');
				var x = document.getElementsByClassName("date_tr_cl")
			var i
			for (i = 0; i < x.length; i++) {
				x[i].style['border'] = '0px solid black';
			}
			
			var elll = document.getElementById('date_tr_'+id);
			elll.style['border'] = '5px solid red';

			//alert("DateTable")
			

			
			$("html,body").animate({scrollTop: $(elll).offset().top-200,
				scrollLeft: $(elll).offset().left-300
			}, 500);

			$("body #DateTable").animate({scrollTop:0,scrollLeft:0},0);
			$("body #DateTable").animate({scrollTop: $(elll).offset().top-$("#DateTable").offset().top,
				scrollLeft: $(elll).offset().left-300
			}, 500);			
			
			console.log("1-"+$(elll).offset().top+"  2-"+$("#DateTable").offset().top+" 3-"+$(elll).offset().top)

			/*
					$("html,body #div_svg").animate({scrollTop: $(elll).offset().top-200,
				scrollLeft: $(elll).offset().left-300
			}, 100);	
			*/
			//DateTable

			GetMapObjAndShow()
			
			
}

function GetMapObjAndShow(){
	
			//получаем массив объектов на карте
		$.ajax({
		  async: false, 
		  url: 'blocks/dinamic_scripts/saveMapObj.php',
		  data: {id_ev_get:CurrentEnentNum},
		  type: "POST",
		  success: function(data) {  arr = data; 
			//alert(data)
			console.log(data);
			//alert(data)
            if(data){
				ShowMap(data);
			}else{
					maoObjectsFromBD = null;
                    $('#map-canvas').css('display','none');
					$('#mapObject-menu').css('display','none');
					$('#map-helper').css('display','none');
                    $('#map-menu').html('');
					$('#ShowM').css('display','block') 	
			}
			
		  },
			dataType: 'json'
		})			

}

//преобразуем в пиксили даты
function DateToPixel(date){
	 //Количество миллисекунд в одном дне
	msPerDay = 24*60*60*1000;
	console.log("date"+date)
	 //Высчитываем количество дней
	 var arrD = date.split(" ")[0].split("-");
	 if(arrD[1] =='00'){arrD[1] = '01';} if(arrD[2] =='00'){arrD[2] = '01';}
	 thisday = new Date(arrD[1]+"/"+arrD[2]+"/"+arrD[0]);
	 console.log("thisday"+arrD[2]+"/"+arrD[1]+"/"+arrD[0])
	 begday = new Date("01/01/"+arrD[0]);
	 daysLeft = Math.round((thisday.getTime() - begday.getTime())/msPerDay);
	 console.log("daysLeft"+daysLeft)
	 return daysLeft;
}

//удаляем текст события
function hide(obj){
	/*obj.parentNode.style['display']='none';
	obj.parentNode.parentNode.removeChild(obj.parentNode);*/
	obj.style['display']='none';
	obj.parentNode.removeChild(obj);
}

//при клике на таблице показываем событие в графике
function ShowOnGraph(id,n){
	
	//убираем стрелки след карты
	$('#divNext').css('display','none')
	$('#divPrev').css('display','none')

	PreShowOnGraph(id)

	//у всех карт убираем рамку и добавляем только выбранной
	$('.imgMap').removeClass('imgMapIlum');
	$('#imgMap-'+id).addClass('imgMapIlum');
	//imgMapIlum
	
	
	CurrentEnentNum = id;
	//ShowMap(CurrentEnentNum);
	//getElementsByC.style.fill='blue';

	//убираем выделения
	var idN = ''
	for (var i = 0; i < ArrEvent.length; i++) {
		ArrEvent[i].style.fill='blue';
		//проверяем есть ли в id наш элемент
		if(ArrEvent[i].id.indexOf('event_win_'+id) + 1) {
			idN = ArrEvent[i].id;
		}
	}	
	
 console.log("ShowOnGraph idN"+idN)
	/*var elll = document.getElementById('event_win_'+id);
	//elll.style['border'] = '2px solid red';
	elll.style.fill='red';

	location.href = "#";
	
	location.href = "#event_win_"+id;*/
	if(idN != ''){
		var elll = document.getElementById(idN);
		elll.style['border'] = '2px solid red';
		elll.style.fill='red';
//				$("html,body").animate({scrollTop: $(elll).offset().top-200,
			$("html,body").animate({scrollTop: $("html,body #div_svg").offset().top ,scrollLeft:0},300);
			$("html,body #div_svg").animate({scrollTop: 0,scrollLeft:0},0);
		
		//console.log("ShowOnGraph  scrollTop"+$(elll).offset().top-200 + " -scrollLeft- " +$(elll).offset().left-300)
console.log($(elll).offset().top-200);

		$("html,body #div_svg").animate({scrollTop: $(elll).offset().top-200,
				scrollLeft: $(elll).offset().left-300
			}, 100);	
				
				
			//var elll = document.getElementById(idN);
				/*var evEm={
					pageX : $(elll).offset().left,
					pageY: $(elll).offset().top - $("#div_svg").offset().top
				}*/
				
				console.log($("#div_svg"));

				console.log("--------------event1-----------------");
				console.log(arrEv[n]);
				console.log(arrEv[n]['date_Beg'].split(" ")[0] +"   --- " +arrEv[n]['date_End'].split(" ")[0]);
				var arrDb = arrEv[n]['date_Beg'].split(" ")[0].split("-");
				var arrDe = arrEv[n]['date_End'].split(" ")[0].split("-");
				var Datee = (arrDb[2]!="00"?arrDb[2]+".":"") + (arrDb[1]!="00"?arrDb[1]+".":"")+(arrDb[0]!="0000"?arrDb[0]:"")+(arrEv[n]['date_End'].split(" ")[0]=="0000-00-00"?"":"-")+(arrDe[2]!="00"?arrDe[2]+".":"") + (arrDe[1]!="00"?arrDe[1]+".":"")+(arrDe[0]!="0000"?arrDe[0]:"")+" | " ;//
				console.log(Datee);

				//console.log(arrEv);
				showEvent(null,[[id,arrEv[n]['event']]],id,elll,Datee)
				//setTimeout(function(){$('#'+idN).mouseover();},2000);
				
				/*ent.pageX+"px";
					el.style['top'] = event.pageY+"px";*/
				
				//alert($('#'+idN).innerHTML)
				//$('#'+idN).mouseover();
				//console.log($('#'+idN));
				//$(elll).mouseover();
				console.log('showEventPerm(id)-'+id+"-- "+'#'+idN);
				// showEvent(event,arrText)
	}

	GetMapObjAndShow()
	//$("html,body").animate({scrollTop: $(elll).offset().top-200}, 1000);
}

	//добавление прямойгольников
	var rect=function(x,y,h,w,fill){
	 var NS="http://www.w3.org/2000/svg";
	 var SVGObj= document.createElementNS(NS,"rect");
	 SVGObj.x.baseVal.value=x;
		SVGObj.y.baseVal.value=y;
	 SVGObj.width.baseVal.value=w;
	 SVGObj.height.baseVal.value=h;
	 SVGObj.setAttribute("height",h);
	 SVGObj.setAttribute('cursor','pointer');
	 SVGObj.style.fill=fill;
	 return SVGObj;
	}

	var circle=function(cx,cy,fill){
	 var NS="http://www.w3.org/2000/svg";
	 var SVGObj= document.createElementNS(NS,"circle");
		SVGObj.setAttribute('cx',cx);
		SVGObj.setAttribute('cy',cy);
		SVGObj.setAttribute('r',50);
		SVGObj.setAttribute('fill',fill);
		SVGObj.setAttribute('stroke','#006600');
	 return SVGObj;
	}

	var line=function(x1,y1,x2,y2,fill){
	 var NS="http://www.w3.org/2000/svg";
	 var SVGObj= document.createElementNS(NS,"line");
		SVGObj.setAttribute('x1',x1);
		SVGObj.setAttribute('y1',y1);
		SVGObj.setAttribute('x2',x2);
		SVGObj.setAttribute('y2',y2);
		SVGObj.setAttribute('fill',fill);
		SVGObj.setAttribute('stroke',fill);
	 return SVGObj;
	}

	var text=function(x,y,fs,text){
	 var NS="http://www.w3.org/2000/svg";
	 var SVGObj= document.createElementNS(NS,"text");
		SVGObj.setAttribute('x',x);
		SVGObj.setAttribute('y',y);
		SVGObj.setAttribute('font-size',fs);
		//SVGObj.setAttribute('cursor','pointer');
		SVGObj.textContent = text;
		//SVGObj.setAttribute('fill',fill);
		//SVGObj.setAttribute('stroke',fill);
	 return SVGObj;
	}

		SVGRender(arrEv);		

 </script>


	
	
	
	<?	if($arrEv ==null){ echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='color:red;'>Ничего не найдено. Измените параметры фильтра.</b><br />"; 
        //выводим темы с картинками (по приоритету/или по джате создания)
        //print_r($arrTheme);
        	//вытаскиваем все темы новостей (если в дальнейшем будет висеть, то сделать при клике на ссылку)
            //$query = "SELECT * FROM  Theme_of_Events ORDER BY Theme";
            //ISNULL(NULLIF(map_objects,'')) as map_objects
            $query = "SELECT * FROM  Theme_of_Events WHERE img <>'' ORDER BY prior ";
            
                        
            $result = mysql_query($query) or die(mysql_error());
                $n = mysql_num_rows($result);
            if($n >0){
                $arrTh = array();
                for ($i = 0; $i < $n; $i++)
                {
                    $row = mysql_fetch_assoc($result);		
                    $arrTh[] = $row;
                    if($row['id'] == $_GET['id_theme']){  $theme = $row['Theme'];}
                    
                    //if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
                }
            }
        
            //print_r($arrTh);
            
            if(count($arrTh)>0){
            ?>
            <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Последние темы</h3>
            <div style="padding:7px; border:5px solid green; overflow:auto; border-radius: 15px; margin: 10px 20px 20px 20px;" >    
                <ul class="foto_albom foto50Text">
						<? foreach ($arrTh as $theme):?>
							<li>
								 <!--width="10px" height="10px"-->
								 <!--<span style="cursor:pointer;" title="<?=$theme['Theme']?>" onclick="GotoTheme('th-<?=$theme['id']?>')">-->
								 <a href='index.php?id_theme=<?=$theme['id']?>' title='<?=$theme['Theme']?>' >	
								 	<div class="foto_50" style="border-radius: 15px; overflow:hidden;"><img style="width:250px;"  src="img/themes/<?=$theme['img']?>" /></div><br />
								 	<?=$theme['Theme']?>
								 </a>
								 <!--</span>-->
							</li>	
						<?	endforeach; ?>

                 </ul>
             </div>        
            <script>
				function GotoTheme(th_id){
					location.href = 'index.php?id_theme='+th_id.split('-')[1];
				}
			</script>
			
			
			<?
            }
    
    
    
    
    }?>

	<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='//www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t14.6;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border='0' width='88' height='31'><\/a>")
//--></script><!--/LiveInternet-->


<?
//идентификация поисковых ботов
//  1. в сессию заносится uid посетителя и значение isBot (имя бота если есть)
//  2. заетм через 15 сек аяксом передается на сервер (isBot, и движение мыши). на сервере меняется сессия c параметрами (файл About_Visitor)
//  3. каждый раз при посещении страницы (если есть есть уже ссесия увеличивается посещение)

/*
$_SERVER['HTTP_USER_AGENT'] = "sdfsdfsd rambler asdad";
$_SESSION['uid_for_bot'] = null;
$_SESSION['ISBot'] =null;
echo $_SESSION['uid_for_bot'];
*/
if(!isset($_SESSION['uid_for_bot']) or ($_SESSION['uid_for_bot'] == null)){//если еще нету инфы о защедщем
		
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
		
	//if( !isBot() )$hits=$hits+1;// накручиваем счетчик если запрос не от бота
	// или так
	$bname = '';
	if( isBot($bname) ){} // echo 'На сайте сейчас '.$bname;}else{ echo 'На сайте сейчас НЕБОТ'; }		
		
	
	//генерируем uid посетителя
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
	$code = "";
	$clen = strlen($chars) - 1;  

	while (strlen($code) < 10) 
        $code .= $chars[mt_rand(0, $clen)];  
		
	//echo "<br> -code-".$code;
	//заносится в сессию и в БД
	$agent =  mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
	$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
	$agent =  mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);	
	$date = date('Y-m-d H:i:s');
	$query = "INSERT INTO Bot_info  (uid_for_bot,Bot_name,user_agent,mouse_move,mouse_move_latet,ip,IsBot,date,visits) VALUES ('$code','$bname','$agent',0,'','$ip','','$date',1)";	
		//$query = "INSERT INTO Date_Of_Events  (date_Beg,date_End,event) VALUES (STR_TO_DATE('15-8-1990 00:00:00', '%d-%m-%Y %H:%i:%s'),STR_TO_DATE('00-00-0000 00:00:00', '%d-%m-%Y %H:%i:%s'),'Начал писать события1111')";
		//echo $query;
		$result = mysql_query($query) or die(mysql_error());
		
		$_SESSION['uid_for_bot'] = $code;
	
}else{ //если уже зареган в базе, то просто меняем чиcло визитов
			$t = "UPDATE Bot_info
			SET	visits = visits+1 WHERE uid_for_bot = '".$_SESSION['uid_for_bot']."'";
			//die($eventStr);
			$result = mysql_query($t);
}

//echo "<br >mouse_move -".$_SESSION['mouse_move'];

?>
<script>
<?//определяем что это бот по движениям мыши или скрола?>
	var i=1;
	document.body.onmousemove  = function(){ i++; }
	//document.body.onmousedown = function(){alert(i);}

	//записываем в БД
	function saveInfo(){
		var arr;
		$.ajax({
		  async: false, 
		  url: 'blocks/dinamic_scripts/About_Visitor.php',
		  data: {bt:'bt',mm:i},
		  type: "POST",
		  success: function(data) {  arr = data; }//,
		//dataType: 'json'
		 })

		if(arr){
			//alert(arr);
		}	
	}
  
	<?//?>
  
	<?if(!isset($_SESSION['ISBot']) or ($_SESSION['ISBot'] =='') or ($_SESSION['ISBot'] ==null)){  //делаем занос и проверку в сессию?>
		<?//через 7 сек записываем бот ли это в БД?>
		setTimeout(function() {saveInfo()}, 15000);
	<?}?>
	
	//если во время записи сессии мышка не двигалась а затем пошевелилась, то меняем это шевеление в базе, для инфы
	<?if($_SESSION['mouse_move'] == '1'){?>
		//alert('dddd');
		var m=1;
		var rerait = false;
		document.body.onmousemove  = function(){ 
				m++;
				if((m >10) && (!rerait) ){
				//alert(m);
				//alert('dddd');
				var arr;
				$.ajax({
				  async: false, 
				  url: 'blocks/dinamic_scripts/About_Visitor.php',
				  data: {mm:m},
				  type: "POST",
				  success: function(data) {  arr = data; }//,
				//dataType: 'json'
				 })

				if(arr){
					//alert(arr);
				}
				rerait= true;
			}
		}
	<?}?>
	
</script>
<?=$_SESSION['ISBot']?>
	
</body>
</html>