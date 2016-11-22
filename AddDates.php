<?header('Content-Type: text/html; charset=utf-8');
//вход под дмином
session_start();

include_once('startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

//прооверяем, что мы админ
include('variables.php');
if($_SESSION['Guest_id']['id_user'] == $AdminID){ 
	// echo "Вы Админ продолжаем дальше<br /><br />";	
}else{
	echo 'Вы не Админ!!!';
	//die();
}


$id_theme = '';
$theme = '';

$id_country='';
$country='';

if(isset($_POST['New_theme'])){
	$theme = $_POST['New_theme'];
}

if(isset($_GET['id_theme'])){
	$id_theme = $_GET['id_theme'];
	$_SESSION['id_theme'] = $id_theme;
}




if(isset($_GET['id_country'])){
	$id_country = $_GET['id_country'];
	$_SESSION['id_country'] = $id_country;
}


$id_category = 1; //по умолчанию история
if(isset($_GET['id_category'])){
	$id_category = $_GET['id_category'];
	$_SESSION['id_category'] = $id_category;
}


if(isset($_SESSION['id_theme'])){
	$id_theme = $_SESSION['id_theme'];
}

if(isset($_SESSION['id_country'])){
	$id_country = $_SESSION['id_country'];
}

if(isset($_SESSION['id_category'])){
	$id_category = $_SESSION['id_category'];
}

if($id_country != ''){
	//вытаскиваем все страны новостей (если в дальнейшем будет висеть, то сделать при клике на ссылку)
	

	//$query = "SELECT * FROM  countrys WHERE id  = '$id_country' ";
	$query = "SELECT * FROM  countrys WHERE id  in ($id_country) ";
	$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			//$row = mysql_fetch_assoc($result);
			//$country = $row['country'];			
			while($row = mysql_fetch_assoc($result)){
				$country .=','.$row['country'];
			}

		}	

}


//добавление новой темы к датам
if(isset($_POST['New_theme'])){
	
	$query = "INSERT INTO Theme_of_Events  (Theme) VALUES ('".$_POST['New_theme']."')";
	$result = mysql_query ($query);
        if ($result == 'true') {//echo "<p>Новая тема успешно добавлена!</p>";
				$id_theme = mysql_insert_id ();
				header("location:AddDates.php?id_theme=$id_theme");
		}
}

//добавление новой страны к датам
if(isset($_POST['New_country'])){
	
	$query = "INSERT INTO countrys (country) VALUES ('".$_POST['New_country']."')";
	$result = mysql_query ($query);
        if ($result == 'true') {//echo "<p>Новая тема успешно добавлена!</p>";
				$id_country = mysql_insert_id ();
				header("location:AddDates.php?id_country=$id_country");
		}
}







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
				if($row['id'] == $id_theme){  $theme = $row['Theme'];}
				
				//if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
			}
		}
		
		

	//вытаскиваем все страны новостей (если в дальнейшем будет висеть, то сделать при клике на ссылку)
	$query = "SELECT * FROM  countrys ORDER BY country ";
	$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			$arrCountry = array();
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				$arrCountry[] = $row;
				//if($row['id'] == $id_theme){  $theme = $row['Theme'];}
				
				//if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
			}
		}

		
		//вытаскиваем все категории новостей (если в дальнейшем будет висеть, то сделать при клике на ссылку)
		$query = "SELECT * FROM  category ORDER BY category ";
		$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			$arrСategory = array();
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				$arrСategory[] = $row;
				if($row['id'] == $id_category){  $category = $row['category'];}
				
				//if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
			}
		}
		

?>
<script type="text/javascript" src="js/jquery.js"></script>
<form id='AvtorForm' method='post'  >
	<b>Добавить новую тему к датам: </b><input name='New_theme' type='text' style='width:700px;' value='<?=$theme?>' /><br />
	<input type='submit' value='добавить' />
</form>	

<form id='AvtorForm' method='post'  >
	<b>Добавить новую страну к датам: </b><input name='New_country' type='text' style='width:700px;' value='<?=$country?>' /><br />
	<input type='submit' value='добавить' />
</form>	
Выбранная категория: <b><?=$category?></b><br /><br /><br />

<form id='AvtorForm1' method='post'  >

	<legend><b>Выберите тему дата</b></legend>
		<input   onkeyup='ChangeTheme(this)' name='ThemeName'  /> <b>или выберите</b>
		<select id='selectMod'  name='ThemeNameCh' onChange='SelectTheme(this)' >
			<option value='' >выберите тему</option>
			<?foreach($arrTheme as $th){?>
			<option value='<?=$th['id']?>' ><?=$th['Theme']?></option>
			<?}?>
		</select>
		<br />
		<input type='submit' value='выбрать' />

<b>id темы: </b>
<input type='text' style='width:50px;' name='ThemeId'  value='<?=$id_theme?>' />
<br />	
<br />	
	
	<legend><b>Выберите страну</b>
		<input   onkeyup='ChangeCountry(this)' name='CountryName'   /> <b>или выберите</b>
		<select id='selectModC'  name='CountryNameCh' onChange='SelectCountry(this)' >
			<option value='' >выберите страну</option>
			<?foreach($arrCountry as $th){?>
			<option value='<?=$th['id']?>' ><?=$th['country']?></option>
			<?}?>
		</select>
		<input type='submit' value='выбрать' />
</legend>

			<legend><b>Выберите категорию</b>
		<select id='selectModCa'  name='CategoryNameCh' onChange='SelectCategory(this)' >
			<option value='' >выберите категорию</option>
			<?foreach($arrСategory as $th){?>
			<option value='<?=$th['id']?>' ><?=$th['category']?></option>
			<?}?>
		</select>
		<input type='submit' value='выбрать' />
		</legend>
		
<br />	
<br />
	
		<script>
		function SelectTheme(obj){//отправка формы с id автора
			//alert(obj.options[obj.selectedIndex].value);
			//alert("ffffgggg");
			document.location.href = 'AddDates.php?id_theme='+obj.options[obj.selectedIndex].value;
		}
		 
		function ChangeTheme(obj){//функция поиска в БД авторов по буквам
			if (obj.value.length > 2){
			//alert("rrrr");
				//вытаскиваем всех авторов у которых один из имен начинается на эти буквы
				var arr;
				$.ajax({
				  async: false, 
				  url: 'blocks/dinamic_scripts/Find_Theme.php',
				  data: {name:obj.value},
				  type: "POST",
				  success: function(data) {  arr = data; },
				dataType: 'json'
				 })
		
		//alert(arr);
				if(arr){
					var Sel = document.getElementById('selectMod');
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
		
		<?//$id_country='';?>
		
		function SelectCountry(obj){//отправка формы с id автора
			//alert(obj.options[obj.selectedIndex].value);
			document.location.href = 'AddDates.php?id_country='+'<? if($id_country==''){echo '';}else{echo $id_country.',';} ?>'+obj.options[obj.selectedIndex].value;
		}
		 
		function ChangeCountry(obj){//функция поиска в БД авторов по буквам
			if (obj.value.length > 2){
			//alert("rrrr");
				//вытаскиваем всех авторов у которых один из имен начинается на эти буквы
				var arr;
				$.ajax({
				  async: false, 
				  url: 'blocks/dinamic_scripts/Find_Country.php',
				  data: {name:obj.value},
				  type: "POST",
				  success: function(data) {  arr = data; },
				dataType: 'json'
				 })
		
		//alert(arr);
				if(arr){
					var Sel = document.getElementById('selectModC');
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
		
		
		function SelectCategory(obj){//отправка формы с id автора
			//alert(obj.options[obj.selectedIndex].value);
			document.location.href = 'AddDates.php?id_category='+obj.options[obj.selectedIndex].value;
		}
		
	</script>	
	
	

<?if(($id_theme == '') or ($id_country=='')){ die("обязательно сначала выберите тему и страну");}else{?>
	
	<br /><b>Парсинг дат: </b><br />
	<!--<b>Ltttttt (обязательно)</b><input type='text' style='width:50px;' value='<?=$_POST['delimeterDa']?>' /><br />
	
	------<b>Делиметер м/д датой и событием: </b><input type='text' style='width:50px;' value='<?=$_POST['delimeterDE']?>' /><br />-->

	<textarea COLS='90' ROWS='15' name='DatesArea'  ><?=$_POST['DatesArea']?></textarea><br />
	<input type='submit' value='ок' />
</form>

<?}?>






<?





//парсинг и добавление дат
if(isset($_POST['DatesArea'])){
	//echo $_POST['DatesArea'];
	
	//разбиваем на строки
	$arrTr = explode("\n",$_POST['DatesArea']);
	
	//
	$MonthArr = array('янв','фев','мар','апр','май','июн','июл','авг','сен','окт','ноя','дек');
	
	//массив и с датами и событиями
	$AllInfoDateArr = array();

	//var_dump($arrTr);
	$DateArr = array();
	
	
	foreach($arrTr as $tr){

		//заполняемые по ходу значения
		$nextNum = '';//составное число
		$nextBuk = '';//составное слово
		
		//массивы чиссел и слов
		$arrWords = array();
		$arrNumbers = array();
		
		
		
		//слово на котором кончается дата - начинается событие
		$stopWord = '';
		
		
		//все что до слова на котором кончается дата - начинается событие (менее 2-ух символов)
		$BeforeStopWord = '';	
		
		
		$dateBeg = '';
		
		//находим даты 
		/*$arrD = explode($_POST['delimeterDE'],$tr);
		if(count($arrD) <2){ continue;}
		*/
		//берем строку и посимвольно вычисляем дату
		for($i=0; $i<mb_strlen($tr,'UTF-8'); $i++){
			$simbol = mb_substr($tr, $i, 1,'UTF-8');
			//echo "-S".$simbol."S-";
			//если следующий символ цифра
			//то добавляем символ в строку цифр , и очишаем строку букв
			//иначе добавляем символ в строку букв , и очишаем строку цифр
			if(is_numeric($simbol)){
				//echo "-".$simbol;
				$nextNum.=$simbol;
				if(mb_strlen(trim($nextBuk),'UTF-8')>2){
					echo "H";
					
					echo $nextBuk."<br />";
					
					//проверяем, если слово не относится к месяцу, то обрываем дальнейший поиск даты в строке
					$obrWord = mb_strtolower(mb_substr($nextBuk, 0, 3,'UTF-8'), 'UTF-8'); 
					//echo $obrWord."---=";
					if(in_array($obrWord,$MonthArr) or ($obrWord == 'мая')) {
						//echo "hhhhhh";
						if(($obrWord != 'мар') AND ($obrWord == 'мая')){ $arrWords[] ='май'; }else{
							$arrWords[] = $obrWord;
						}
					}else{
						$stopWord = $nextBuk;
						break;
					}
				}
				$nextBuk='';				
			}else{//если это не цифра
				//if(preg_match('/[^а-яА-Я\s]+/msi',$simbol)){//если это русская буква
				//if(!preg_match('/^[а-яА-Я]{4,20}+$/u',$simbol)){//если это русская буква
				//if(preg_match('|^[а-я]+$|',$simbol)){//если это русская буква
				//$simbol =  mb_strtolower($simbol, 'UTF-8');
				if(preg_match('/^[а-яА-Я]+$/u',$simbol)){//если это русская буква
					//echo "-!".$simbol."!-";
					$nextBuk.=$simbol;
				}else{
					//echo "-F".$simbol."F-";
					if(mb_strlen(trim($nextBuk),'UTF-8')>2){//ограничиваем 3-мя символами (месяц)
					//	echo $nextBuk."<br />";

							//проверяем, если слово не относится к месяцу, то обрываем дальнейший поиск даты в строке
							$obrWord = mb_strtolower(mb_substr($nextBuk, 0, 3,'UTF-8'), 'UTF-8'); 
							//echo $obrWord."---=";
							if(in_array($obrWord,$MonthArr) or ($obrWord == 'мая')) {
								//echo "hhhhhh";
								if(($obrWord != 'мар') AND ($obrWord == 'мая')){ $arrWords[] ='май'; }else{
									$arrWords[] = $obrWord;
								}
							}else{
								$stopWord = $nextBuk;
								break;
							}
					}else{
						if(trim($nextBuk) !=''){$BeforeStopWord .= $nextBuk." ";}
					}
					//echo "G";
					$nextBuk='';
				}
				
				if(mb_strlen(trim($nextNum),'UTF-8')>0){
				//	echo $nextNum."<br />";
					$arrNumbers[] = $nextNum;
				}
				$nextNum='';
			}
		}
		
		
		
	/*	
		var_dump($arrNumbers);
		echo "<br />";
		var_dump($arrWords);
		echo "stopWord-".$stopWord."<br />";
	*/	
	
	
		//строим дату
		echo $BeforeStopWord."-----".$stopWord;
		if($stopWord != ''){
			if(count($arrNumbers)>0){
				//echo "---Есть ДАТА----";
					//достаем событие
				$event = $BeforeStopWord.stristr ($tr, $stopWord); 			
				
				
				$arr = array();
				$arr['arrNumbers'] = $arrNumbers;
				$arr['arrWords'] = $arrWords;
				$arr['event'] = $event;
				$arr['allevent'] = $tr;
				//запихиваем в общий массив				
				$AllInfoDateArr[] = $arr;

//--- если 	count($arrNumbers)>3 или болеее двух членов с 4-мя цифрами, то здесь промежуток дат		
				
				
			}
		}
		
		

	//echo "1g - ".$tr."<br/>";
		$DateArr1 = array();
		$DateArr1['date_Beg'] = 1;
		$DateArr1['date_End'] = 2;
		$DateArr1['event'] = $tr;
		$DateArr[]=$DateArr1;
	}
	
	

//составляем окончательный массив событий
if(count($AllInfoDateArr)>0){
	//находим какой из членов по счету год
	//находим какой из членов по счету месяц
	
	//массив какой предположительно из членов ялвяется месяцем, годом или датой (для вычисления средне арифмитического)
	$year = array();
	$month = array();
	$day = array();
	$yearE = array();
	$monthE = array();
	$dayE = array();
	

	
	
	for($i=0; $i<count($AllInfoDateArr); $i++){
		//массив даты
		$arrOfDates=array();
		$arrOfDatesEnd=array();
		
		//определеено в члене массива
		$Nyear = '-1';
		$Nmonth = '-1';
		$Nday = '-1';
		$NyearE = '-1';
		$NmonthE = '-1';
		$NdayE = '-1';			
		
		
		//массив уже использованных (определенных) цифр в масиве arrNumbers
	//	$arrI_in_arrNumbers = array();			
		
		
		foreach($AllInfoDateArr[$i]['arrWords'] as $wo){
			$m = array_search($wo, $MonthArr);

			if(!isset($arrOfDates['month'])){
				$arrOfDates['month'] = $m+1;
			}else{
				$arrOfDatesEnd['month'] = $m+1;
			}
		}	
		
		
	
		for($j=0; $j<count($AllInfoDateArr[$i]['arrNumbers']); $j++){
			if(mb_strlen($AllInfoDateArr[$i]['arrNumbers'][$j])>2){
				if(!isset($arrOfDates['year'])){//что это первый год в этой дате
					$Nyear = $j;				
					if(count($AllInfoDateArr[$i]['arrNumbers'])>1){ //если дата не состоит только из года
						$year[] = $j;
					}
					$arrOfDates['year'] = $AllInfoDateArr[$i]['arrNumbers'][$j];

				}elseif(!isset($arrOfDatesEnd['year'])){//что это первый год в этой дате
					$NyearE = $j;
					$yearE[] = $j;
					$arrOfDatesEnd['year'] = $AllInfoDateArr[$i]['arrNumbers'][$j];
				}
				
			}elseif((mb_strlen($AllInfoDateArr[$i]['arrNumbers'][$j])<3)){  //if(mb_strlen($AllInfoDateArr[$i]['arrNumbers'][$j]<3)){//если это дата или месяц
				//echo "tttttt".$i;
				//проверяем есть ли месяц в массиве
				if(count($AllInfoDateArr[$i]['arrWords']) >0){
					if($AllInfoDateArr[$i]['arrNumbers'][$j] <32){//проверяем что дата
						if(!isset($arrOfDates['day'])){//что это первый год в этой дате
							$Nday = $j;	
							//$day[] = $j;
							$arrOfDates['day'] = $AllInfoDateArr[$i]['arrNumbers'][$j];
						}elseif(!isset($arrOfDatesEnd['day'])){//что это первый год в этой дате
							$NdayE = $j;	
							//$dayE[] = $j;
							$arrOfDatesEnd['day'] = $AllInfoDateArr[$i]['arrNumbers'][$j];
						}
						
					}
					//месяц вытаскиваем из масиива arrWords
/*
					foreach($AllInfoDateArr[$i]['arrWords'] as $wo){
						$m = array_search($wo, $MonthArr);
						//$m = array_search($AllInfoDateArr[$i]['arrWords'][0], $MonthArr);
						//echo count($AllInfoDateArr[$i]['arrWords'])."monthmonthmonthmonthmonthmonthmonth----".$m."-----";					
						

						if(!isset($arrOfDates['month'])){
							$arrOfDates['month'] = $m+1;
						}else{
							$arrOfDatesEnd['month'] = $m+1;
						}
							
					
					}*/

					
					
				}else{//если нет то проверяем, какой из них менее 12
					if($AllInfoDateArr[$i]['arrNumbers'][$j] > 12){	
						echo "-cj/2-".(count($AllInfoDateArr[$i]['arrNumbers'])/2)."-cj/2-j-".$j."-j-";
				//		if(!isset($arrOfDates['day']) AND ($j<=count($AllInfoDateArr[$i]['arrNumbers'])/2)){//что это первый год в этой дате (или дата меньше половины цифр)
						if((!isset($arrOfDates['day']) AND (($j<=count($AllInfoDateArr[$i]['arrNumbers'])/2 AND count($AllInfoDateArr[$i]['arrNumbers'])>3)))
							OR
						(!isset($arrOfDates['day']) AND (count($AllInfoDateArr[$i]['arrNumbers'])<4))){//что это первый год в этой дате (или дата меньше половины цифр при кол-ве цифр более 3)
							$Nday = $j;
							$day[] = $j;
							$arrOfDates['day'] = $AllInfoDateArr[$i]['arrNumbers'][$j];
						}elseif(!isset($arrOfDatesEnd['day'])){//что это первый год в этой дате
							$NdayE = $j;
							$dayE[] = $j;
							$arrOfDatesEnd['day'] = $AllInfoDateArr[$i]['arrNumbers'][$j];
						}
						
						
					}
				}
				
			}
			

		
		}
		
		
		
			//if((isset($arrOfDatesEnd['month']) or isset($arrOfDatesEnd['day'])) AND (!isset($arrOfDatesEnd['year']))){ //для случая типа 02.07-08.18 1990 Ввавыа Члены
			//}
		
			
			//проверяем по наличию дат какие неизвестны
			if(!isset($arrOfDates['month']) AND ($Nday != '-1')){
						//		echo "Nday-".$Nday."---NmonthE".$NmonthE."---Nyear".$Nyear;	
				for($j=0; $j<count($AllInfoDateArr[$i]['arrNumbers']); $j++){
					if($j ==3){break;}

					if(($j != $Nday) AND ($j != $NdayE) AND ($j != $NmonthE) AND ($j != $Nyear) AND (mb_strlen($AllInfoDateArr[$i]['arrNumbers'][$j])<3) ){
						if($AllInfoDateArr[$i]['arrNumbers'][$j] <13){
							$arrOfDates['month'] = $AllInfoDateArr[$i]['arrNumbers'][$j];
							$Nmonth = $j ;
						}
					}
				} 
			}
			
			

			
			if(!isset($arrOfDatesEnd['month']) AND ($NdayE != '-1')){
				for($j=2; $j<count($AllInfoDateArr[$i]['arrNumbers']); $j++){
					if(($j != $NdayE) AND ($j != $NyearE) AND (mb_strlen($AllInfoDateArr[$i]['arrNumbers'][$j])<3) ){
						if($arrOfDatesEnd['month'] <13){
							$arrOfDatesEnd['month'] = $AllInfoDateArr[$i]['arrNumbers'][$j];
						}
					}
				} 
			}

		
		$AllInfoDateArr[$i]['arrOfDates'] = $arrOfDates;
		$AllInfoDateArr[$i]['arrOfDatesEnd'] = $arrOfDatesEnd;
		
		
		/*if(mb_strlen($arr['arrNumbers'][0]) > 3){
			$year =1;
		}*/
		/*
		echo "<br/><br/>arrNumbers-".$i."-<br/>"; var_dump($AllInfoDateArr[$i]['arrNumbers']);
		echo "<br/>arrWords-<br/>"; var_dump($AllInfoDateArr[$i]['arrWords']);			
		echo "<br/>arrOfDates-<br/>";  var_dump($arrOfDates);
		echo "<br/>arrOfDatesEnd-<br/>";  var_dump($arrOfDatesEnd);
		echo "<br/>-".$AllInfoDateArr[$i]['allevent']."-<br/>";
		*/
	}
}else{
 die("даты не найдены!");
}


echo "<br/><br/>year-<br/>"; var_dump($year);
echo "<br/><br/>month-<br/>"; var_dump($month);
echo "<br/><br/>day-<br/>"; var_dump($day);
echo "<br/><br/>yearE-<br/>"; var_dump($yearE);
echo "<br/><br/>monthE-<br/>"; var_dump($monthE);
echo "<br/><br/>dayE-<br/>"; var_dump($dayE);
/**/
//находим среднеарифметическое дня
$srDay = round(array_sum($day)/count($day));
echo "<br/>srDay-".$srDay."<br/>";

if(count($dayE)>0){
$srDayE = round(array_sum($dayE)/count($dayE));
	//echo "<br/>srDayE-".$srDayE;
}


$srYear = round(array_sum($year)/count($year));
	echo "<br/>srYear-".$srYear."<br/>";

if(count($yearE)>0){
	$srYearE = round(array_sum($yearE)/count($yearE));
	//echo "<br/>srYearE-".$srYearE;
}

$srMonth = -1;
for($i=0; $i<5; $i++){
	if(($i != $srDay) AND ($i != $srYear)){
		$srMonth = $i;
		break;
	}
}
	echo "<br/>srMonth-".$srMonth."<br/>";

$srMonthE = -1;
for($i=1; $i<7; $i++){
	if(($i != $srDay) AND ($i != $srYear) AND ($i != $srMonth) AND ($i != $srDayE) AND ($i != $srYearE)){
		$srMonthE = $i;
		break;
	}
}
	//echo "<br/>srMonthE-".$srMonthE;



//пробегаемся по массиву и окончательно формируем дату и месяц
//var_dump($AllInfoDateArr);
for($i=0; $i<count($AllInfoDateArr); $i++){
	$date = '';	
	$day = $month = $year = '';
	if(isset($AllInfoDateArr[$i]['arrOfDates']['year'])){//если не определился год, то и нечего ловить далее
		$year = $AllInfoDateArr[$i]['arrOfDates']['year'];
		if(!isset($AllInfoDateArr[$i]['arrOfDates']['day'])){//тогда берем его по среднеарифметическому
			$d = $AllInfoDateArr[$i]['arrNumbers'][$srDay];
			if((mb_strlen($d) <3)){
				$day =  $d;
			}			
		}else{
			$day = $AllInfoDateArr[$i]['arrOfDates']['day'];
		}
		
		if(!isset($AllInfoDateArr[$i]['arrOfDates']['month'])){//тогда берем его по среднеарифметическому
			$m = $AllInfoDateArr[$i]['arrNumbers'][$srMonth];
			if((mb_strlen($m) <3)){
				$month = $m;
			}			
		}else{
			$month = $AllInfoDateArr[$i]['arrOfDates']['month'];
		}
		
		

		
		if($day ==''){$day = '00';}
		if($month ==''){$month = '00';}
		
		$date= $day."-".$month."-".$year;
		$AllInfoDateArr[$i]['date'] = $date;
	}else{
		echo "<br /><h1 style='color:red;'>!!!косяк!!!</h1>"; var_dump($AllInfoDateArr[$i]);
	
	}
	
	
	//дату окончания
	$dateE = '';	
	$dayE = $monthE = $yearE = '';
	if((isset($AllInfoDateArr[$i]['arrOfDatesEnd']['year'])) or (isset($AllInfoDateArr[$i]['arrOfDatesEnd']['month']))){//если не определился год, то и нечего ловить далее
		if(isset($AllInfoDateArr[$i]['arrOfDatesEnd']['year'])){
			$yearE = $AllInfoDateArr[$i]['arrOfDatesEnd']['year'];
		}
		if(!isset($AllInfoDateArr[$i]['arrOfDatesEnd']['day'])){//тогда берем его по среднеарифметическому
			$d = $AllInfoDateArr[$i]['arrNumbers'][$srDayE];
			if((mb_strlen($d) <3)){
				$dayE =  $d;
			}			
		}else{
			$dayE = $AllInfoDateArr[$i]['arrOfDatesEnd']['day'];
		}
		
		if(!isset($AllInfoDateArr[$i]['arrOfDatesEnd']['month'])){//тогда берем его по среднеарифметическому
			$m = $AllInfoDateArr[$i]['arrNumbers'][$srMonthE];
			if((mb_strlen($m) <3)){
				$monthE = $m;
			}			
		}else{
			$monthE = $AllInfoDateArr[$i]['arrOfDatesEnd']['month'];
		}
		
		if($dayE ==''){$dayE = '00';}
		if($monthE ==''){$monthE = '00';}
		if($yearE ==''){ $yearE = $AllInfoDateArr[$i]['arrOfDates']['year']; } //значит год прописан один раз

		$dateE= $dayE."-".$monthE."-".$yearE;
		$AllInfoDateArr[$i]['dateE'] = $dateE;
	}	
	
}


/*
echo "<br />AllInfoDateArr-";
var_dump($AllInfoDateArr);
*/
	
	
	//if(count($DateArr) >0){

	//}
//}	
	

	
	/*
	
	$query = "INSERT INTO Date_Of_Events  (date_Beg,date_End,event) VALUES (STR_TO_DATE('15-0-1990 00:00:00', '%d-%m-%Y %H:%i:%s'),STR_TO_DATE('00-00-0000 00:00:00', '%d-%m-%Y %H:%i:%s'),'777Начал писать события1111')";	
	//$query = "INSERT INTO Date_Of_Events  (date_Beg,date_End,event) VALUES (STR_TO_DATE('15-8-1990 00:00:00', '%d-%m-%Y %H:%i:%s'),STR_TO_DATE('00-00-0000 00:00:00', '%d-%m-%Y %H:%i:%s'),'Начал писать события1111')";
	echo $query;
	$result = mysql_query($query) or die(mysql_error());
*/
	
	$j=0;

	
	var_dump($AllInfoDateArr);
	
	$lsd = date('Y-m-d H:i:s');
	//сохраняем в БД
	for($i=0; $i<count($AllInfoDateArr); $i++){
		echo '<br>'.$i.'-AllInfoDateArr'.$AllInfoDateArr[$i]['dateE'];
			$j++;
	//		if($j>2){ break;}
			if(!isset($AllInfoDateArr[$i]['dateE'])){ $AllInfoDateArr[$i]['dateE']='00-00-0000'; }
			$event = str_replace(array("\r\n", "\r", "\n"), '', $AllInfoDateArr[$i]['event']); 
			//$event = str_replace(array('"',"'"),'\"', $event); 
			$event = str_replace( "'", "`", $event);
			$event = str_replace('"','“',$event);
			$event = str_replace('	',' ',$event); 			

			$id_countryInsert = str_replace(',','|', $id_country);
			
			$needAddtoTheme = true;
			if(isset($AllInfoDateArr[$i]['date'])){
			
				$query = "INSERT INTO Date_Of_Events  (date_Beg,date_End,event,ids_Theme,date_of_add,ids_country,category) VALUES (STR_TO_DATE('".$AllInfoDateArr[$i]['date']." 00:00:00', '%d-%m-%Y %H:%i:%s'),STR_TO_DATE('".$AllInfoDateArr[$i]['dateE']." 00:00:00', '%d-%m-%Y %H:%i:%s'),'".$event."','".$id_theme."','$lsd','$id_countryInsert','$id_category')";	
				//$query = "INSERT INTO Date_Of_Events  (date_Beg,date_End,event) VALUES (STR_TO_DATE('15-8-1990 00:00:00', '%d-%m-%Y %H:%i:%s'),STR_TO_DATE('00-00-0000 00:00:00', '%d-%m-%Y %H:%i:%s'),'Начал писать события1111')";
				//echo $query;
				$result = mysql_query($query);// or die(mysql_error());
				$id_date = mysql_insert_id ();
				//echo "id_dateЬ".$id_date;
				$AllInfoDateArr[$i]['id'] = $id_date;

				

				
				if(!$result){
						$needAddtoTheme = false; 	
						echo "<h1 style='color:red;'>!!!косякD!!!</h1>".mysql_error();
						//если дубликат, то пытаемся к этой дате добавить другую страну
						
						//ищем id даты
						$query = "SELECT id FROM Date_Of_Events WHERE date_Beg = STR_TO_DATE('".$AllInfoDateArr[$i]['date']." 00:00:00', '%d-%m-%Y %H:%i:%s') AND event = '".$event."'";
						$result = mysql_query($query)  or die(mysql_error());
							$n = mysql_num_rows($result);
							//echo "id_daterrrrrrrr";
							if($n >0){
								$row = mysql_fetch_assoc($result);
								$id_date=0;
								$id_date = $row['id'];
								echo "FFFFid_date".$id_date;								

								if($id_date>0){
								$arrDatess = explode(',', $id_country);
									for($m=0; $m<count($arrDatess); $m++){
										if($arrDatess[$m]!=''){
											//вставяляем соотношение дата страна только
											echo "<br />id_daterrrrrrrr-".$arrDatess[$m];
											echo "<br />id_daterrrrrrrr-".$id_date;
											$query = "INSERT INTO Date_vs_country  (id_date,id_country) VALUES ('$id_date',	'".$arrDatess[$m]."')";
											$result = mysql_query($query);	
											echo $query ;
											echo mysql_error();

											if($result){ //если вставилось, то добавляем страну в конце
												echo "<br />вставляем новую страну и тему".$id_date."<br />";
												$t = "UPDATE Date_Of_Events
												SET	ids_country = CONCAT_WS('|',ids_country,'".$arrDatess[$m]."') , ids_Theme = CONCAT_WS('|',ids_Theme,'".$id_theme."')
												WHERE id = '".$id_date."'";
												//die($eventStr);
												$result = mysql_query($t);
													echo mysql_error();	
											}else{
												//$id_date=0;
											}
										}										
									}
								}
								
							}
							
				}else{
					//вставяляем соотношение дата страна только, что созданной даты
					/*$query = "INSERT INTO Date_vs_country  (id_date,id_country) VALUES ('$id_date',	'$id_country')";
					$result = mysql_query($query);	*/
								$arrDatess = explode(',', $id_country);
								for($m=0; $m<count($arrDatess); $m++){
									if($arrDatess[$m]!=''){
										$query = "INSERT INTO Date_vs_country  (id_date,id_country) VALUES ('$id_date',	'".$arrDatess[$m]."')";
										$result = mysql_query($query);	
									}
								}
				}
				

				
				
				
				if($id_theme != ''){//добавляем к теме данное событие
					
					//if($id_date != 0){
					if($needAddtoTheme){
						echo "добавляем к теме";
						//добавляем 
						//$eventStr = str_replace("'", "\"", $eventStr);
						$t = "UPDATE Theme_of_Events
						SET	ids_events = CONCAT_WS('|',ids_events,'$id_date'), date_last = '$lsd' 
						WHERE id = '$id_theme'";
						//die($eventStr);
						$result = mysql_query($t);
					}
				}else{
					echo "не добавляем к теме".$id_theme;
				}
				
			}


			
			//	$arrBeg = explode("-",$_POST['DateBeg']); $DateBeg = $arrBeg[2]."-".$arrBeg[1]."-".$arrBeg[0];
			//	$arrEnd = explode("-",$_POST['DateEnd']); $DateEnd = $arrEnd['2']."-".$arrEnd['1']."-".$arrEnd['0'];
			//	$whereDate = " AND date BETWEEN STR_TO_DATE('".$DateBeg." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".$DateEnd." 23:59:59', '%Y-%m-%d %H:%i:%s')";
			

	}


	
	
			echo "<table bordercolor='blue' border='1' cellspacing='0' id='table' style='text-align:center;'>";
			//foreach($DateArr as $da){
		foreach($AllInfoDateArr as $da){
			$style ='';
			if($da['id'] == 0){ $style = " style='color:red;' "; }
				if($da['date'] !=''){	
					echo "<tr id='tr-".$da['id']."' >";
						echo "<td><input size='8' type='text' id='date-".$da['id']."' value='".$da['date']."'</td>";
						echo "<td><input size='8' type='text' id='dateE-".$da['id']."' value='".$da['dateE']."'</td>";
						echo "<td><input size='50' type='text' id='event-".$da['id']."' value='".$da['event']."'</td>";
						echo "<td><input size='1' type='text' id='bc_ac-".$da['id']."' value=''</td>";
						echo "<td><b onclick='delString(this)' id='Del-".$da['id']."'>D</b>-<b onclick='editString(this)' id='Ed-".$da['id']."' >E</b></td>";
						echo "<td $style >".$da['allevent']."</td>";
						
					echo "</tr>";
				}

			
		}
		echo "</table>";
}
?>

<script>//удаление и редактирование дат в базе
		function delString(obj){//удаление дат в базе
			var id = obj.id.split('-')[1];
			//alert(id);
			if (id != 0){
			//alert("rrrr");
				//вытаскиваем всех авторов у которых один из имен начинается на эти буквы
				var arr;
				$.ajax({
				  async: false, 
				  url: 'blocks/dinamic_scripts/Edit_Del_Date.php',
				  data: {proc:"del",id:id},
				  type: "POST",
				  success: function(data) {  arr = data; }//,
				//dataType: 'json'
				 })
		
				alert(arr);

			}else{ alert("id = 0"); }			
			
			
			
			//alert(obj.options[obj.selectedIndex].value);
			//document.location.href = 'AddDates.php?id_theme='+obj.options[obj.selectedIndex].value;
		}
		
		function editString(obj){//редактирование дат в базе
			var id = obj.id.split('-')[1];
			
			var dateBeg = document.getElementById('date-'+id).value;
			var dateEnd = document.getElementById('dateE-'+id).value;
			var event = document.getElementById('event-'+id).value;
			var bc_ac = document.getElementById('bc_ac-'+id).value;
			
			alert("id-"+id+" dateBeg-"+dateBeg+" dateEnd-"+dateEnd+" event-"+event);
			if (id != 0){
			//alert("rrrr");
				//вытаскиваем всех авторов у которых один из имен начинается на эти буквы
				var arr;
				$.ajax({
				  async: false, 
				  url: 'blocks/dinamic_scripts/Edit_Del_Date.php',
				  data: {proc:"edit",id:id,dateBeg:dateBeg,dateEnd:dateEnd,event:event,bc_ac:bc_ac},
				  type: "POST",
				  success: function(data) {  arr = data; }//,
				//dataType: 'json'
				 })
		
				alert(arr);

			}else{ alert("id = 0"); }			
			
			
			
			//alert(obj.options[obj.selectedIndex].value);
			//document.location.href = 'AddDates.php?id_theme='+obj.options[obj.selectedIndex].value;
		}
		
		
</script>
