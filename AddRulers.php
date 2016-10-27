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


	$id_country='';	
	$id_ruler ='';	
	$id_lord='';
	$InCountry = 1;
	$id_lordToEd = 0;

	$country='';
	$ruler='';
	$lord='';
	$foto = '';

		if(isset($_POST['id_lordToEd'])){
				$id_lordToEd = $_SESSION['id_lordToEd'] = $_POST['id_lordToEd'];
		}


	//если был запрос на редактирование знати, то все значения выбираем из базы, и ставим id согласно значению из табл (если Id нету значит происходит добавление нового)
	if(isset($_GET['id_lordToEd'])){
		if($_GET['id_lordToEd'] !=0){
			$query = "SELECT * FROM  rulers_subrulers WHERE id  = '".$_GET['id_lordToEd']."' ";
			echo $query;
			$result = mysql_query($query) or die(mysql_error());
			$n = mysql_num_rows($result);
			if($n >0){
				$row = mysql_fetch_assoc($result);
				//$lordToEd = $row['country'];
				$id_lordToEd = $_SESSION['id_lordToEd'] = $row['id'];
				$_SESSION['id_country'] = $row['id_country'];

				$id_country=$_SESSION['id_country'] = $row['id_country'];
				$_SESSION['id_ruler'] = $row['id_ruler'];
				$_SESSION['id_lord'] = $row['id_lord'];
				if($row['isruler']){
					$_SESSION['New_ruler'] = $row['name'];
				}else{
					$_SESSION['New_lord'] = $row['name'];
				}
				$_SESSION['Titul']= $row['titul'];
				$arr1 = explode("-",$row['date_begRule']);
				$_SESSION['Date_begRul'] = $arr1[2]."-".$arr1[1]."-".$arr1[0];
				$arr1 = explode("-",$row['date_endRule']);
				$_SESSION['Date_endRul'] = $arr1[2]."-".$arr1[1]."-".$arr1[0];
				$arr1 = explode("-",$row['date_born']);
				$_SESSION['Date_born'] = $arr1[2]."-".$arr1[1]."-".$arr1[0];
				$arr1 = explode("-",$row['date_die']);
				$_SESSION['Date_die'] = $arr1[2]."-".$arr1[1]."-".$arr1[0];
				//$foto = "RulerPicture/".$row['foto'];
				 echo "<script>document.location.href = 'AddRulers.php'</script>";

			}				
		}

	}

	
	if(isset($_SESSION['id_lordToEd'])){
		$id_lordToEd = $_SESSION['id_lordToEd'];
	}	


	if(isset($_SESSION['id_country'])){
		$id_country = $_SESSION['id_country'];
	}
	if(isset($_SESSION['id_ruler'])){
		$id_ruler = $_SESSION['id_ruler'];
	}
	if(isset($_SESSION['id_lord'])){
		$id_lord = $_SESSION['id_lord'];
	}
	if(isset($_SESSION['InCountry'])){
		$InCountry = $_SESSION['InCountry'];
	}



	if(isset($_POST['New_ruler'])){
		$_SESSION['New_ruler'] = $_POST['New_ruler'];
	}
	if(isset($_POST['New_lord'])){
		$_SESSION['New_lord'] = $_POST['New_lord'];
	}
	if(isset($_POST['Titul'])){
		$_SESSION['Titul'] = $_POST['Titul'];
	}
	if(isset($_POST['Date_begRul'])){
		$_SESSION['Date_begRul'] = $_POST['Date_begRul'];
	}
	if(isset($_POST['Date_endRul'])){
		$_SESSION['Date_endRul'] = $_POST['Date_endRul'];
	}
	if(isset($_POST['Date_born'])){
		$_SESSION['Date_born'] = $_POST['Date_born'];
	}
	if(isset($_POST['Date_die'])){
		$_SESSION['Date_die'] = $_POST['Date_die'];
	}


	if(isset($_SESSION['New_ruler'])){
		$_POST['New_ruler'] = $_SESSION['New_ruler'];
	}
	if(isset($_SESSION['New_lord'])){
		$_POST['New_lord'] = $_SESSION['New_lord'];
	}
	if(isset($_SESSION['Titul'])){
		$_POST['Titul'] = $_SESSION['Titul'];
	}
	if(isset($_SESSION['Date_begRul'])){
		$_POST['Date_begRul'] = $_SESSION['Date_begRul'];
	}
	if(isset($_SESSION['Date_endRul'])){
		$_POST['Date_endRul'] = $_SESSION['Date_endRul'];
	}
	if(isset($_SESSION['Date_born'])){
		$_POST['Date_born'] = $_SESSION['Date_born'];
	}
	if(isset($_SESSION['Date_die'])){
		$_POST['Date_die'] = $_SESSION['Date_die'];
	}







	
	if(isset($_GET['id_country'])){
		echo "+id_country+";
		$id_country = $_GET['id_country'];
		$_SESSION['id_country'] = $id_country;
		//echo "<script>document.location.href = 'AddRulers.php'</script>";
	}
	if(isset($_GET['id_ruler'])){
		echo "+id_ruler+";
		$id_ruler = $_GET['id_ruler'];
		$_SESSION['id_ruler'] = $id_ruler;
		//echo "<script>document.location.href = 'AddRulers.php'</script>";
	}
	if(isset($_GET['id_lord'])){
		echo "+id_country+";
		$id_lord = $_GET['id_lord'];
		$_SESSION['id_lord'] = $id_lord;
		//echo "<script>document.location.href = 'AddRulers.php'</script>";
	}
	if(isset($_GET['InCountry'])){
		$InCountry = $_GET['InCountry'];
		$_SESSION['InCountry'] = $InCountry;
		//echo "<script>document.location.href = 'AddRulers.php'</script>";
	}

	if($id_country != ''){
		$query = "SELECT * FROM  countrys WHERE id  = '$id_country' ";
		$result = mysql_query($query) or die(mysql_error());
			$n = mysql_num_rows($result);
			if($n >0){
				$row = mysql_fetch_assoc($result);
				$country = $row['country'];			
			}	
	}

	if($id_ruler != ''){
		$query = "SELECT * FROM  rulers_subrulers WHERE id  = '$id_ruler' ";
		$result = mysql_query($query) or die(mysql_error());
			$n = mysql_num_rows($result);
			if($n >0){
				$row = mysql_fetch_assoc($result);
				$ruler = $row['name'];			
			}	
	}
	
	if($id_lord != ''){
		$query = "SELECT * FROM  rulers_subrulers WHERE id  = '$id_lord' ";
		$result = mysql_query($query) or die(mysql_error());
			$n = mysql_num_rows($result);
			if($n >0){
				$row = mysql_fetch_assoc($result);
				$lord = $row['name'];			
			}	
	}

	
	$Where = "";
		if($InCountry){
			$Where = " AND id_country=$id_country";
	}	

	//вытаскиваем всю знать страны 
	if($id_country != ''){
		//вытаскиваем всех правителей страны 
		$query = "SELECT * FROM  rulers_subrulers WHERE 1=1 $Where AND isruler=1 ORDER BY name ";
		$result = mysql_query($query) or die(mysql_error());
			$n = mysql_num_rows($result);
			if($n >0){
				$arrRulers = array();
				for ($i = 0; $i < $n; $i++)
				{
					$row = mysql_fetch_assoc($result);		
					$arrRulers[] = $row;
				}
			}		
		}
	
	if($id_country != ''){

		$query = "SELECT * FROM  rulers_subrulers WHERE 1=1 $Where ORDER BY name ";
		$result = mysql_query($query) or die(mysql_error());
			$n = mysql_num_rows($result);
			if($n >0){
				$arrLords = array();
				for ($i = 0; $i < $n; $i++)
				{
					$row = mysql_fetch_assoc($result);		
					$arrLords[] = $row;
				}
			}		
		}



	//очистка значений
	if(isset($_POST['LordName'])){
		if($_POST['LordName'] =='0'){
			 echo "+LordName+";
			 $_SESSION['id_lord'] ='';
			 $id_lord='';
			 echo "<script>document.location.href = 'AddRulers.php'</script>";
		}
	}
	if(isset($_POST['RulerName'])){
		if($_POST['RulerName'] =='0'){
			echo "+RulerName+";
			 $_SESSION['id_ruler'] = '';
			 $id_ruler='';
			  echo "<script>document.location.href = 'AddRulers.php'</script>";
		}
	}


?>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
		function SelectCountry(obj){//отправка формы с id страны
			//alert(obj.options[obj.selectedIndex].value);
			document.location.href = 'AddRulers.php?id_country='+obj.options[obj.selectedIndex].value;
		}

		function SelectRuler(obj){//отправка формы с id правителя страны
			//alert(obj.options[obj.selectedIndex].value);
			document.location.href = 'AddRulers.php?id_ruler='+obj.options[obj.selectedIndex].value;
		}

		function Selectlord(obj){//отправка формы с id родителя
			//alert(obj.options[obj.selectedIndex].value);
			document.location.href = 'AddRulers.php?id_lord='+obj.options[obj.selectedIndex].value;
		}

		
		function SelectlordToEd(obj){//отправка с id знати для дальнейшего редактирования
			//alert(obj.options[obj.selectedIndex].value);
			document.location.href = 'AddRulers.php?id_lordToEd='+obj.options[obj.selectedIndex].value;
		}


		function SetInCountry(obj){//отправка формы с указанием нужно ли искать родителя только в стране
			//alert(obj.options[obj.selectedIndex].value);
			var InCountry = '<?=$InCountry?>';
			console.log("InCountry-"+InCountry);
			//console.log(obj);
			document.location.href = 'AddRulers.php?InCountry='+(InCountry==1?0:1);
		}



		var id_countr = '<?=$id_country?>';
		var InCountry = '<?=$InCountry?>';
		if(InCountry==0){id_countr=0;}


		function ChangeRuler(obj){//функция поиска в БД правителей по буквам
			if (obj.value.length > 2){
			//alert("rrrr");
				//вытаскиваем всех авторов у которых один из имен начинается на эти буквы
				var arr;
				$.ajax({
				  async: false, 
				  url: 'blocks/dinamic_scripts/Find_Ruler.php',
				  data: {name:obj.value,id_country:id_countr,isRuler:1},
				  type: "POST",
				  success: function(data) {  arr = data; },
				dataType: 'json'
				 })
		
		//alert(arr);
				if(arr){
					var Sel = document.getElementById('selectModR');
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

		
		function ChangeLord(obj){//функция поиска в БД знати по буквам
			if (obj.value.length > 2){
			//alert("rrrr");
				//вытаскиваем всех авторов у которых один из имен начинается на эти буквы
				var arr;
				$.ajax({
				  async: false, 
				  url: 'blocks/dinamic_scripts/Find_Ruler.php',
				  data: {name:obj.value,id_country:id_countr},
				  type: "POST",
				  success: function(data) {  arr = data; },
				dataType: 'json'
				 })
		
		//alert(arr);
				if(arr){
					var Sel = document.getElementById('selectModL');
					var Sel1 = document.getElementById('selectModLtE');
					Sel.innerHTML= '';
					Sel1.innerHTML= '';
					Sel.multiple = true;
					Sel1.multiple = true;
					for(var i=0; i<arr.length; i++){
						var arr1 = arr[i].split('|');
						//alert(arr1[0]+"---"+arr1[1]);
						var opt = document.createElement('option');
						var opt1 = document.createElement('option');
						opt.value = arr1[0];
						opt.innerHTML = arr1[1];
						opt1.value = arr1[0];
						opt1.innerHTML = arr1[1];
						Sel.appendChild(opt);
						Sel1.appendChild(opt1);
					}
				}
			}
		}


</script>




<form id='AvtorForm' method='post'  >
	
	<input   value='<?=$id_lordToEd?>' name='id_lordToEd'  /><?if($id_lordToEd ==0){echo " <b style='color:red;'>ДОБАВЛЕНИЕ</b> нового!!!"; }else{echo "<b style='color:red;'>РЕДАКТИРОВАНИЕ</b>!!!";}?><br/> 
	<legend><b>Выберите страну</b>
		<input   value='<?=$country?>' name='CountryName'  disabled="true" />
		<select id='selectModC'  name='CountryNameCh' onChange='SelectCountry(this)' >
			<option value='' >выберите страну</option>
			<?foreach($arrCountry as $th){?>
				<option value='<?=$th['id']?>' ><?=$th['country']?></option>
			<?}?>
		</select>
		<!--<input type='submit' value='выбрать' />-->
	</legend>



<?if($id_country==''){ die("обязательно сначала выберите страну");}else{?>
		<br/>	
		!!! Чтобы очистить правителя или родителя введите - 0  !!!<br/>
		<input type="checkbox" name="inCountry" onChange='SetInCountry(this)' <?if($InCountry){?> checked<?}?>/>внутри страны выбирать правителя/родителя?<br />	
		<legend><b>Выберите родителя</b>
		<input   value='<?=$lord?>' name='LordName'  onkeyup='ChangeLord(this)' />
  
		<select id='selectModL'  name='lordNameCh' onChange='Selectlord(this)' >
			<option value='' >выберите родителя</option>
			<?foreach($arrLords as $th){?>
				<option value='<?=$th['id']?>' ><?=$th['name']?></option>
			<?}?>
		</select><br />
		Титул:<input   value='<?=$_POST['Titul']?>' name='Titul'  />
		<br /><br/>
	

	<b>Добавить нового Правителя к стране: </b><input value='<?=$_POST['New_ruler']?>' name='New_ruler' type='text' style='width:400px;' value='' /><br />
	ИЛИ
	<br /><b>Добавить новую Знать к правителю: </b><input value='<?=$_POST['New_lord']?>' name='New_lord' type='text' style='width:300px;' value='' />
		<b>Выберите правителя</b>
		<input   value='<?=$ruler?>' name='RulerName' onkeyup='ChangeRuler(this)' />
		<select id='selectModR'  name='RulerNameCh' onChange='SelectRuler(this)' >
			<option value='' >выберите правителя</option>
			<?foreach($arrRulers as $th){?>
				<option value='<?=$th['id']?>' ><?=$th['name']?></option>
			<?}?>
		</select>
		<!--<input type='submit' value='выбрать' />-->

		<br/><br/><br/>Даты вводятся вида 17-01-1982<br/>
		Время правления/управления делами:<input   value='<?=$_POST['Date_begRul']?>' name='Date_begRul' />-<input value='<?=$_POST['Date_endRul']?>'  value='' name='Date_endRul' /><br/>
		Время жизни:<input   value='<?=$_POST['Date_born']?>' name='Date_born' />-<input   value='<?=$_POST['Date_die']?>' name='Date_die' /><br/>
<?}?>
<br /><br />




	<input type='submit' value='<?if($id_lordToEd ==0){echo "добавить"; }else{echo "изменить";}?>' />



	<br><br><br>ИЛИ Редактировать<input   value='' name='LordToEdit'  onkeyup='ChangeLord(this)' />
 		<select id='selectModLtE'  name='lordNameChE' onChange='SelectlordToEd(this)' >
			<option value='' >выберите кого править</option>
			<?foreach($arrLords as $th){?>
				<option value='<?=$th['id']?>' ><?=$th['name']?></option>
			<?}?>
		</select><br />

</form>	

			<iframe  name="h_iframe" width="700" height="100" style="display: none;"></iframe><!-- фрейм для загрузки страницы -->
			<div id="picoBody" style='border:1px solid green;'>
				<b id='DobFotoLable'>Добавление картинки/карты </b>
				<!-- locks/dinamic_scripts/loadPicture.php?path=MapPicture&size=150 -->
				<form  id="linkForm2" method="post" action="blocks/dinamic_scripts/loadPicture.php?path=RulerPicture&size=500"  name="img_upload" enctype="multipart/form-data" target="h_iframe">
					<div id="imageId">
						 
						  <img src="img/loadinfo1.gif" style="display:none;" />
					 </div>
					 <div id="image_upload_status"></div>	
					 <p><input id="showfiles1" type="file" name="userfileComment"  /></p>
					 <input id="srcFoto" type="text" name="srcFoto"   />
					 <input id="TextArea1" type="hidden" name="srcFoto"  />
				</form>
				<?=$foto?>
				<img src="<?=$foto?>" id="MapPictureDraw" />
			</div>
			<script>		
				$(document).ready(function() { 
					
					$('#showfiles1').change(function(){ 
						console.log('showfiles1 submit');
						var CurrentEnentNum = <?=$id_lordToEd?>;
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
													  url: 'blocks/dinamic_scripts/RulerChanges.php',
													  data: {rulPict:srcFoto,id_ev:CurrentEnentNum},
													  type: "POST",
													  success: function(data) {  arr = data;  alert(data)
													  }//,
														//dataType: 'json'
													})
												
											}
											//сохраняем и миниатюру
											/*document.forms['linkForm2'].action = "blocks/dinamic_scripts/loadPicture.php?path=MapPictureSmal&size=60"
											document.forms['linkForm2'].submit();*/
											
									}
								}, 300);
								
						}else{alert('Вы не выбрали правителя/знать!')}
					});
					
					
					
					
				});		
			</script>
<?


//определяем правитель ли
$NameR = "";
$isRuler = 0;
if($_POST['New_ruler']!=''){
	$NameR = $_POST['New_ruler'];
	$isRuler = 1;
}

if($_POST['New_lord']!=''){
	$NameR = $_POST['New_lord'];
}

//разбираемся с датами
//0000-00-00
if($_POST['Date_begRul'] ==''){
	$_POST['Date_begRul'] = "00-00-0000";
}
if($_POST['Date_endRul'] ==''){
	$_POST['Date_endRul'] = "00-00-0000";
}
if($_POST['Date_born'] ==''){
	$_POST['Date_born'] = "00-00-0000";
}
if($_POST['Date_die'] ==''){
	$_POST['Date_die'] = "00-00-0000";
}
if($id_ruler ==''){
	$id_ruler = 0;
}
if($id_lord ==''){
	$id_lord = 0;
}
echo "<br><br><br>вывод с формы-------------<br />";
echo "id_country-".$id_country;
echo "<br />NameR-".$NameR;
echo "<br />isRuler-".$isRuler;
echo "<br />titul-".$_POST['Titul'];
echo "<br />id_country-".$id_country;
echo "<br />id_ruler-".$id_ruler;
//echo "<br />id_preruler-".$id_preruler;
echo "<br />id_ancestor-".$id_lord;

echo "<br />Date_begRul-".$_POST['Date_begRul'];
echo "<br />Date_endRul-".$_POST['Date_endRul'];
echo "<br />Date_born-".$_POST['Date_born'];
echo "<br />Date_die-".$_POST['Date_die'];


if(($NameR !='') AND ($id_country !='') AND ($_POST['Titul'] !='') ){
			
			if(!$id_lordToEd){	//создаем нового
				$query = "INSERT INTO rulers_subrulers  (isruler,name,titul,id_country,id_ruler,id_preruler,id_ancestor,date_begRule,date_endRule,date_born,date_die)
				 VALUES ($isRuler,'$NameR','".$_POST['Titul']."',$id_country,$id_ruler,0,$id_lord,
				 STR_TO_DATE('".$_POST['Date_begRul']."','%d-%m-%Y'),
				  STR_TO_DATE('".$_POST['Date_endRul']."','%d-%m-%Y'),
				   STR_TO_DATE('".$_POST['Date_born']."','%d-%m-%Y'),
				    STR_TO_DATE('".$_POST['Date_die']."','%d-%m-%Y'))";
				 
				echo "<br/><br/>".$query."<br/><br/>";
				$result = mysql_query($query) or die(mysql_error());
				echo "<h1>Добавлено!!!!</h1>!!!";
			
			}else{//редактируем
				$query = "UPDATE rulers_subrulers SET
				  isruler = $isRuler,
				  name = '$NameR',
				  titul = '".$_POST['Titul']."',
				  id_country = $id_country,
				  id_ruler = $id_ruler,
				  id_ancestor = $id_lord,
				  date_begRule = STR_TO_DATE('".$_POST['Date_begRul']."','%d-%m-%Y'),
				  date_endRule = STR_TO_DATE('".$_POST['Date_endRul']."','%d-%m-%Y'),
				  date_born = STR_TO_DATE('".$_POST['Date_born']."','%d-%m-%Y'),
				  date_die = STR_TO_DATE('".$_POST['Date_die']."','%d-%m-%Y')
			WHERE id = $id_lordToEd";
				echo "<br/><br/>".$query."<br/><br/>";
				$result = mysql_query($query) or die(mysql_error());
				echo "<h1>Изменено!!!!</h1>!!!";

			}
}else{
	echo "<h1>НЕ ВСЕ ПАРАМЕТРЫ УКАЗАНЫ!!!!!</h1>";
}


	/*$id_country='';	
	$id_ruler ='';	
	$id_lord='';
	$InCountry = 1;

	$country='';
	$ruler='';
	$lord='';*/
?>