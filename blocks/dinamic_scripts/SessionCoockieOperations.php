<?php  //сохранение в БД инфо об пользователе
header('Content-Type: text/html; charset=utf-8');//собираем всю информацию об защедщих на сайт
//отмечаем что пользователь все еще на сайте


	session_start();
	include_once('../../startup.php');

//очистка карты и  картигнки/карты у даты
if(isset($_POST['par'])){

	$expire = time() + 3600 * 24 * 100;
	$value = sprintf("%d",$_POST['value']);
	if($_POST['par'] == 'MapWeidth'){	
   		setcookie('MapWeidth',$value, $expire,'/');
   		//echo "MapWeidth";
	}elseif($_POST['par'] == 'MapHeight'){
		setcookie('MapHeight',$value, $expire,'/');
		//echo "MapHeight";
	}
	
}