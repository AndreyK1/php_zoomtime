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
	echo 'Вы не Админ!!!';
	die();
}


?>





<?

//удаление даты
if(isset($_GET['DelDate'])){
	
	$query = "DELETE FROM Date_Of_Events WHERE id = '".$_GET['DelDate']."' " ;
	$result = mysql_query ($query);
        if ($result == 'true') {echo "<p>Дата удалена!</p>";
				$id_theme = mysql_insert_id ();
		}
}

//редактирование даты
if(isset($_GET['EditDate'])){
	//вытаскиваем инфо о дате
	$query = "SELECT * FROM  Date_Of_Events WHERE id = '".$_GET['EditDate']."' ";
	$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
				$DateZn = mysql_fetch_assoc($result);		

		}

	echo "ДАТА: ".$DateZn['date_Beg']."-".$DateZn['date_End']." ".$DateZn['event']."<br />";
	
	
	
	//добавляем к дате тему
	if(isset($_GET['id_themeAdd'])){
			echo "добавляем тему к дате<br />";
			//добавляем 
			//$eventStr = str_replace("'", "\"", $eventStr);
			$t = "UPDATE Date_Of_Events
			SET	ids_Theme = CONCAT_WS('|',ids_Theme,'".$_GET['id_themeAdd']."') 
			WHERE id = '".$_GET['EditDate']."'";
			//die($eventStr);
			$result = mysql_query($t);			
			
			echo "добавляем дату к теме<br />";
			//добавляем 
			//$eventStr = str_replace("'", "\"", $eventStr);
			$t = "UPDATE Theme_of_Events
			SET	ids_events = CONCAT_WS('|',ids_events,'".$_GET['EditDate']."') 
			WHERE id = '".$_GET['id_themeAdd']."'";
			//die($eventStr);
			$result = mysql_query($t);
	
	
	}
	

	//удаляем тему у даты
	if(isset($_GET['id_themeDel'])){
			echo "удаляем тему у даты<br />";
			//удаляем 
			$arrTh = explode("|",$DateZn['ids_Theme']);
			$arrTh = array_diff($arrTh, array('',$_GET['id_themeDel']));
			$strTh = implode("|",$arrTh);
			
			//$eventStr = str_replace("'", "\"", $eventStr);
			$t = "UPDATE Date_Of_Events
			SET	ids_Theme = '".$strTh."' WHERE id = '".$_GET['EditDate']."'";
			//die($eventStr);
			$result = mysql_query($t);			

			echo "удаляем дату e темы<br />";
			//вытаскиваем инфо о теме
			$query = "SELECT * FROM  Theme_of_Events WHERE id = '".$_GET['id_themeDel']."' ";
			$result = mysql_query($query) or die(mysql_error());
				$n = mysql_num_rows($result);
				if($n >0){
						$ThemeZn = mysql_fetch_assoc($result);		

				}
			$arrTh = explode("|",$ThemeZn['ids_events']);
			$arrTh = array_diff($arrTh, array('','0',$_GET['EditDate']));
			$strTh = implode("|",$arrTh);
			
			//удаляем 
			//$eventStr = str_replace("'", "\"", $eventStr);
			$t = "UPDATE Theme_of_Events
			SET	ids_events =  '".$strTh."'  
			WHERE id = '".$_GET['id_themeDel']."'";
			//die($eventStr);
			$result = mysql_query($t);
	
	
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
				//if($row['id'] == $id_theme){  $theme = $row['Theme'];}
				
				//if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
			}
		}
?>
	<form id='AvtorForm1' method='post'  >

	<legend><b>Выберите тему для добавления к дате</b></legend>
		<input   onkeyup='ChangeTheme(this)' name='ThemeName'  /> <b>или выберите</b>
		<select id='selectMod'  name='ThemeNameCh' onChange='SelectTheme(this)' >
			<?foreach($arrTheme as $th){?>
			<option value='<?=$th['id']?>' ><?=$th['Theme']?></option>
			<?}?>
		</select>
		<br />
		<input type='submit' value='выбрать' />

<b>id темы последней добавленной темы: </b>
<input type='text' style='width:50px;' name='ThemeId'  value='<?=$_GET['id_themeAdd']?>' />
</form>	
<hr />
<?//темы, которые прикреплены к дате
$arrTh = explode("|",$DateZn['ids_Theme']);
//удаляем пустые
$arrTh = array_diff($arrTh, array(''));
//достаем все значения
if(count($arrTh)>0){
	$strTh = implode("','",$arrTh);
	$strTh = "'".$strTh."'";
	
	$query = "SELECT * FROM  Theme_of_Events WHERE id in (".$strTh.") ORDER BY Theme";
	$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			$arrThA = array();
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				$arrThA[] = $row;
				if($row['id'] == $id_theme){  $theme = $row['Theme'];}
				
				//if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
			}
		}
	
}
?>

<?if(count($arrThA)>0){?>

	<legend><b>Выберите тему для удаления у даты</b></legend>
		<select id='selectModDel'  onChange='SelectThemeDel(this)' >
			<option value='0' >выбираем</option>
			<?foreach($arrThA as $th){?>
			<option value='<?=$th['id']?>' ><?=$th['Theme']?></option>
			<?}?>
		</select>	

<?}?>		
	
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script>
		function SelectTheme(obj){//отправка формы с id автора
			//alert(obj.options[obj.selectedIndex].value);
			document.location.href = 'EditDate.php?EditDate=<?=$_GET['EditDate']?>&id_themeAdd='+obj.options[obj.selectedIndex].value;
		}
		
		function SelectThemeDel(obj){//отправка формы с id автора
			//alert(obj.options[obj.selectedIndex].value);
			document.location.href = 'EditDate.php?EditDate=<?=$_GET['EditDate']?>&id_themeDel='+obj.options[obj.selectedIndex].value;
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
	</script>	


<?
}

?>
