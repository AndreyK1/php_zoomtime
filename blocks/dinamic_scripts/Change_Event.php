<?php  //изменение событий под поисковики
header('Content-Type: text/html; charset=utf-8');//собираем всю информацию об защедщих на сайт


//die("пока перепись временно остановлена");  //пока перепись временно остановлена
	
	
	
	
	session_start();
	include_once('../../startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

	$arrEv = array();
	//вытаскиваем все события без измененого для ботов события (по 300)
	
	
	//только дл яномеров в пределах
	$where = ' AND id >13173 ';
	
	$query = "SELECT id,event,ids_country FROM  Date_Of_Events WHERE event_changed ='' $where ORDER BY id LIMIT 1000";
	$result = mysql_query($query) or die(mysql_error());
		$n = mysql_num_rows($result);
		if($n >0){
			
			for ($i = 0; $i < $n; $i++)
			{
				$row = mysql_fetch_assoc($result);		
				$arrEv[] = $row;
			}
		}
		
		if(count($arrEv) >0){
		
			//вытаскиваем все страны
			$arrCountry = array();
			$query = "SELECT * FROM  countrys ";
			$result = mysql_query($query) or die(mysql_error());
				$n = mysql_num_rows($result);
				if($n >0){
					
					for ($i = 0; $i < $n; $i++)
					{
						$row = mysql_fetch_assoc($result);		
						$arrCountry[$row['id']] = $row['country'];
					}
				}			
				
		//	var_dump($arrCountry);	
		
			//изменяем и сохраняем в БД
		//	var_dump($arrEv);
			
			
			foreach($arrEv as $ev){
				$string = $ev['event'];
				$id = $ev['id'];
				
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
				 
				 //пробегаемся по предложениям
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
								/*if($d<$nslov){
									$titl_n1[] =$arr_slov[$d];
								}else{
									$titl_e1[] =$arr_slov[$d];
								}*/
								if($d<$nslov*2){
									//$titl_n2[] =$arr_slov[$d];
									//если это последнее слово и оно предлог, то его не добавляем
									if($d >= ($nslov*2-2)){
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
							/*$titl_n1 = implode(" ",$titl_n1); $titl_e1 = implode(" ",$titl_e1);
							 $titl_nT = $titl_e1." ".$titl_n1;*/
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
				
				
				//страна к которой прикреплена дата
				$strana = '';
				$arrayOfInserts = array(); //массив вставок между предложениями
				if((int)$ev['ids_country'] !=0){ 
					$strana= $arrCountry[(int)$ev['ids_country']];
					$arrayOfInserts = array( $strana,
											"Дата",
											"(".$strana."-история)",
											"События",
											"см. ".$strana,
											"смотри ".$strana,
											"Историческая хроника",
											$strana."-хроника",
											"Происшествия",
											$strana." в событиях",
											$strana." в датах",
											"История",
											"Произошло в ".$strana,
											"Случилось в ".$strana,
											"Историческая справка",
											"Из истории ".$strana,
											"В истории ".$strana,
											"События ".$strana											
					);
				}else{
					$arrayOfInserts = array( "Дата",
											"События",
											"Историческая хроника",
											"Происшествия"
					);				
				
				}
				
				
				
				
				
				
				
				//составляем окончательную строку
				$str = '';
				 //в зависимости от остатка от деления составляем назад строку
				for($j=0; $j<count($arrSent); $j++){
					//заменяем некоторые символы
					//echo "111-".$j."-".$ev['id'];
					//$arrSent[$j] = str_replace(array("-"), '&mdash;', $arrSent[$j]);
					if((($j%2)==0) or ($j == 1)){
						if($str !=''){$str = $arrSent[$j].". ".$arrayOfInserts[array_rand($arrayOfInserts)].". ".$str;}else{ $str = $arrSent[$j];}
					}else{
						
						$str .= ". ".$arrayOfInserts[array_rand($arrayOfInserts)].". ".$arrSent[$j];
					}
					
					
					//echo $str."<br>";
				}
				
				if(count($arrSent) ==1){ $str.= ". ".$arrayOfInserts[array_rand($arrayOfInserts)].".";}
				
				 $string =$str;
				
				/*
				echo "<hr />".$ev['event']."-<br />";
				echo $string."-<br />";
				echo (int)$ev['ids_country']."-<br />";
				echo $strana."-<br />";
				*/
				
				
				//сохраняем переделанное 
				$t = "UPDATE Date_Of_Events
				SET	event_changed = '".$string."' WHERE id = '".$id."' " ;
				//die($eventStr);
				echo $t;
				$result = mysql_query($t);
				if ($result) {
					echo "Дата отредактирована!";
				}else{
						echo "ОШИБА. Дата не отредактирована!";
				}	
			
			}
			

		}
	
	



?>