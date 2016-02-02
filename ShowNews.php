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

<html>
<head>

		<title>Список новостей России и всего мира в хронологии по неделями</title>

	
</head>	
<body style="position:relative; " >
<h2>Понедельная хронология новостей России и всего мира</h2>
<?
	//выводим список недель с новостями
	
		//находим самую ранюю дату события
		$query = "SELECT date_Beg FROM  Date_of_News  ORDER BY date_Beg LIMIT 1"  ;
		//echo 	$query;
		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_assoc($result);		
		$dateFirst = $row['date_Beg'];
		//echo "<br />Самая раняя дата - ".$dateFirst."<br />";
		
		//находим промежуток до первого воскресенья
		//$week=array(0=>"вс", "пн","вт","ср","чт","пт","сб");
		//$dateFirst = "2014-08-03";
		
		$d1 = explode(" ",$dateFirst);
		$d2 = explode("-",$d1[0]);
		//находим день недели
		//$week[date("w",mktime (0, 0, 0, $cur_month, $cur_day, $cur_year))];
		$weekd = date("w",mktime (0, 0, 0, $d2[1], $d2[2], $d2[0]));
		
		//echo "wd - ".$weekd;
		//сколько дней до конца недели
		$d_to_End = 8 - $weekd;
		
		//(isset($_GET['DateBeg']) AND isset($_GET['DateEnd'])){
		//находим следующий понедельник
		$time = new DateTime($dateFirst);
		$newtime = $time->modify('+'.$d_to_End.' day')->format('Y-m-d');
		//echo "<br />следующий понедельник - ".$newtime;
		//сегодняшняя дата 
		$toDay = new DateTime('now');	$toDay = $toDay->format('Y-m-d');
		//echo "<br />сегодня - ".$toDay;
		
		//вытаскиваем все следующие понедельники
		while($newtime < $toDay ){
			$timeBeg = $newtime;
			$time = new DateTime($newtime);

			$newtime = $time->modify('+1 week')->format('Y-m-d');
			
			//смотрим все новости внутри промежутка
			$newtimeForQuery= $time->modify('-1 day')->format('Y-m-d');
			$limit = 4; //ограничение кол-ва новостей
			$query = "SELECT * FROM  Date_of_News   
			WHERE date_Beg BETWEEN STR_TO_DATE('".$timeBeg." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".$newtimeForQuery." 23:59:59', '%Y-%m-%d %H:%i:%s') LIMIT ".$limit;
			//echo 	$query;
			$result = mysql_query($query) or die(mysql_error());
			$n = mysql_num_rows($result);
			//если за неделю есть хоть какие-то новости
			if($n >0){

				
				//$arrE = array();
				$newsStr ='';
				for ($i = 0; $i < $n; $i++)
				{
					if($i >$limit){ break;}
					$row = mysql_fetch_assoc($result);		
					//$arrE[] = $row;
					//обрезаем новость
					$ns = 35; //кол-во символов
					$news_tit = $row['event'];
					if (mb_strlen($news_tit,'UTF-8') > $ns)
						$news_tit =  mb_substr($news_tit, 0, $ns, 'UTF-8').'...';
					$newsStr .=	$news_tit;
					
					//echo $row['date_Beg']." ".$row['event']."<br>";
				}
				//делаем ссылку
				$b = explode(" ",$timeBeg); $e = explode(" ",$newtime);
				echo "<br /><a href='index.php?onlyNews=1&WeekFrom=".$b[0]."' >".$b[0]."-".$e[0]." | ".$newsStr;				
				
				//echo "<br><br>";
			}
			
		}
		
		
		//$time = new DateTime('now');
		//$newtime = $time->modify('-1 week')->format('d-m-Y');
		//$Date_b = $newtime;	
		//$Date_e = date("d-m-Y");
		


?>
</body>
</html>