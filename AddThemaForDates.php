<?header('Content-Type: text/html; charset=utf-8');
//работа с датами (добавление темы для группы дат)

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



$theme = '';
$id_themeAdd = '';
if(isset($_GET['id_themeAdd'])){
	$id_themeAdd = $_GET['id_themeAdd'];
}
if(isset($_POST['id_themeAdd'])){
	$id_themeAdd = $_POST['id_themeAdd'];
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
				if($row['id'] == $id_themeAdd){  $theme = $row['Theme'];}
				
				//if($row['id'] ==$SpeechArr[0]['id_avtor']){ $titleStr = $row['Who'];	}
			}
		}
?>
	<form id='AvtorForm1' method='post'  >

	<legend><b>Выберите тему для добавления к дате</b></legend>
		<input   onkeyup='ChangeTheme(this)' name='ThemeName' value='<?=$theme?>'  /> <b>или выберите</b>
		<select id='selectMod'  name='ThemeNameCh' onChange='SelectTheme(this)'  >
			<?foreach($arrTheme as $th){?>
			<option value='<?=$th['id']?>' ><?=$th['Theme']?></option>
			<?}?>
		</select>
		<br />
		<input type='submit' value='выбрать' />

<b>id темы последней добавленной темы: </b>
<input type='text' style='width:50px;' name='id_themeAdd'  value='<?=$id_themeAdd?>' />
</form>	
<br />

	<script type="text/javascript" src="js/jquery.js"></script>
	<script>
		function SelectTheme(obj){//отправка формы с id автора
			//alert(obj.options[obj.selectedIndex].value);
			document.location.href = 'AddThemaForDates.php?id_themeAdd='+obj.options[obj.selectedIndex].value;
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




		<form id='AvtorForm' method='post'  >
			<p>Диапазон id дат (через тире)</p>
			<input type='text'   name='ids' /><br />
			<input type='submit' value='добавить к теме' />
			
		</form>
<?


if($id_themeAdd == ''){
	die("Необходимо выбрать тему");
}


 if(isset($_POST['ids'])){
	$ids =  mysql_real_escape_string($_POST['ids']);
	
	echo $ids;	
	$idBeg = '';
	$idEnd = '';
	
	$arrD = explode("-",$ids);	
	if(count($arrD)>1){
		$idBeg = sprintf("%d", $arrD[0]);
		$idEnd = sprintf("%d", $arrD[1]);
			$where =" WHERE id >=".$idBeg." AND id <=".$idEnd ; //для добавления
		$query = "SELECT id FROM Date_Of_Events WHERE id >=".$idBeg." AND id <=".$idEnd." " ;	
	}else{
		$idBeg = sprintf("%d", $ids);	
		$where =" WHERE id ='".$idBeg."' " ;	
		$query = "SELECT id FROM Date_Of_Events WHERE id ='".$idBeg."' " ;	
	}


	
	//Вытаскиваем все id в этом диапазоне
	
	$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			$arrId = array();
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				$arrId[] = $row['id'];
			}
		}
		
		var_dump($arrId);
		if(count($arrId) >0){
			//добавляем к теме даты
			$str = implode("|",$arrId);
			echo $str;
			
			$t = "UPDATE Theme_of_Events
			SET	ids_events = CONCAT_WS('|',ids_events,'".$str."') 
			WHERE id = '".$id_themeAdd."'";
			//die($eventStr);
			$result = mysql_query($t);
			
			//добавляем к датам тему
			$t = "UPDATE Date_Of_Events
			SET	ids_Theme = CONCAT_WS('|',ids_Theme,'".$id_themeAdd."') ".$where ;
			//die($eventStr);
			$result = mysql_query($t);				
			
			
		}
		
 }




?>