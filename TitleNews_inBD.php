<?header('Content-Type: text/html; charset=utf-8');
//работа с датами

//сохраняем заголовки новостей в БД как события

//вход под дмином
session_start();

include_once('startup.php');
	// Установка параметров, подключение к БД, запуск сессии.
	startup();

?>

<?

include('variables.php');
?>

Вытаскиваем последние титлы новостей в базу

<br /><a style='color:orange;' href="index.php">В АДМИНКУ</a><br /><br />
<?
	// подключаем библиотеку simple_html_dom
	require_once 'simple_html_dom.php'; 

	//парсинг RSS в Массивы
	function ParseRSSInMassiv($url,$from){
		$aqrrOfNewsIn = Array();	
		if($url !=''){
		//echo $url;
			/*$xml = xml_parser_create();     //создаёт XML-разборщик
			xml_parser_set_option($xml, XML_OPTION_SKIP_WHITE, 1);  //устанавливает опции XML-разборщика
			xml_parse_into_struct($xml, file_get_contents($url), $element, $index); //разбирает XML-данные в структуру массива, все передается в массив $index
			xml_parser_free($xml);  //освобождает XML-разборщик
			*/
		
			//достаем текст
			//$data = file_get_html($url);
			$str = file_get_html($url);
			//echo $str;
			$str = str_replace('<![CDATA[','', $str);
			$str = str_replace(']]>','', $str);
			$data = str_get_html($str);
			if($data->innertext!=''){// and count($data->find('title'))){
				$text ='';
				$k = 0;			
				foreach($data->find('item') as $a){
					//$this->remove_noise("'<![CDATA[(.*?)]]>'is", true);

					//"<b>".$a->plaintext;
					//echo "<br />11-".$a->plaintext."-11<br />";
						//$data->find('#article_full_text');
						//$b = $a->innertext;
						if($_GET['from']=='itar-tass'){//отсеиваем не политику 
							$pol=false;
							foreach ($a->find('category') as $c){
								//echo "<br />+0-".$c->innertext."-0";
								if($c->innertext == 'Политика'){ $pol=true;}
								if($c->innertext == 'Кризис на Украине'){ $pol=true;}
								if($c->innertext == 'Международная панорама'){ $pol=true;}
							}
							//echo "<br />";
							if(!$pol){ continue;}
							$pol = false;		
											
						}
			//ВАЖНО!!!!					
						$k++;
						global $maxRSSNews;
						if($k>$maxRSSNews){ break;} //ограничиваем число до семи последних новостей
						
						foreach ($a->find('title') as $c){
							$arr['title']=$c->innertext;
							//echo "<br />+1-".$c->innertext."-1<br />";
						}
						foreach ($a->find('guid') as $c){
							$arr['link']=$c->innertext;
							//echo "<br />+2-".$c->plaintext."-2<br />";
						}
						foreach ($a->find('description') as $c){
							$arr['description']=$c->innertext;
							//echo "<br />+3-".$c->plaintext."-3<br />";
						}
						foreach ($a->find('pubDate') as $c){
							$d1 = strtotime($c->innertext); // переводит из строки в дату
							$arr['pubDate'] = $pubDate = date("Y-m-d H:i:s", $d1); 
							//echo "<br />+4-".$c->plaintext."-4<br />";
						}
						foreach ($a->find('ENCLOSURE') as $c){
							$arr['img']=$c->url;
							//echo "<br />+5-<img src='".$c->url."' />-5<br />";
						}
						//echo "<br /><br />";
						$arr['fromN']= $from;
						
						
						$aqrrOfNewsIn[] = $arr;
				}
			}
			$data->clear(); // очишаем
			unset($data);
			//var_dump($aqrrOfNewsIn);
			
		}
		return $aqrrOfNewsIn;
	}


	
	
	//парсинг в Массивы новостей из источников поочереди
	function ParseInMassivFrom($from){	
		//создаем массив, куда будем складывать новости
		$aqrrOfNews = Array();
		
		//массив новостей которых еще нет в базе
		$aqrrOfNewsNeed = Array();
		
		//проверяем с какого сайта забираем
		$url ='';
		if($from=='ria'){
			echo "Забираем РИА новости в БД<br />";
			$url ='http://ria.ru/export/rss2/politics/index.xml';
		}elseif($from=='itar-tass'){
			echo "Забираем ИТАР-ТАСС новости в БД<br />";
			$url ='http://itar-tass.com/rss/v2.xml';
		}
		
		
		
		//парсим RSS в Массивы
		$aqrrOfNews = ParseRSSInMassiv($url,$from);
		return $aqrrOfNews;
	}

	
	//массив новостей которых еще нет в базе
$arr1 = ParseInMassivFrom('ria');
$arr2 = ParseInMassivFrom('itar-tass');
$aqrrOfChosenNews = array_merge($arr1,$arr2); 

var_dump($aqrrOfChosenNews);


//сохраняем новости в БД
		for($i=0; $i<count($aqrrOfChosenNews); $i++){
			//if(($last =='') or ($last < $aqrrOfNews[$i]['pubDate'])){
				
				//записываем новость в БД
				echo "\n<br />записываем в бд новость из ".$aqrrOfChosenNews[$i]['fromN']." - ".$aqrrOfChosenNews[$i]['title']."<br />";
				
				//вытаскиваем текс новости с сайта
				//$aqrrOfChosenNews[$i]['plainText'] = grabbNewsToBD($aqrrOfChosenNews[$i]['link']);
				//$t = grabbNewsToBD($aqrrOfChosenNews[$i]['link'],$aqrrOfChosenNews[$i]['fromN']);
				$aqrrOfChosenNews[$i]['plainText'] = $t['plaintext'];
				$aqrrOfChosenNews[$i]['innertext'] = $t['innertext'];
				
				$event =  mysql_real_escape_string($aqrrOfChosenNews[$i]['title']);
				
							$event = str_replace(array("\r\n", "\r", "\n"), '', $event); 
							//$event = str_replace(array('"',"'"),'\"', $event); 
							$event = str_replace( "'", "`", $event);
							$event = str_replace('"','“',$event);
							$event = str_replace('	',' ',$event); 	
				
				
				
				
				$aqrrOfChosenNews[$i]['link'] =  mysql_real_escape_string($aqrrOfChosenNews[$i]['link']);
				$aqrrOfChosenNews[$i]['pubDate'] =  mysql_real_escape_string($aqrrOfChosenNews[$i]['pubDate']);
			//	$aqrrOfChosenNews[$i]['img'] =  mysql_real_escape_string($aqrrOfChosenNews[$i]['img']);
			//	$aqrrOfChosenNews[$i]['description'] =  mysql_real_escape_string($aqrrOfChosenNews[$i]['description']);
			//	$aqrrOfChosenNews[$i]['plainText'] =  mysql_real_escape_string($aqrrOfChosenNews[$i]['plainText']);
			//	$aqrrOfChosenNews[$i]['innertext'] =  mysql_real_escape_string($aqrrOfChosenNews[$i]['innertext']);

			//	$query = "INSERT INTO $db_news.News_foreign (title,fromN,link,description,pubDate,img,plainText,tegText,speech) VALUES ('".$aqrrOfNews[$i]['title']."','".$aqrrOfNews[$i]['fromN']."','".$aqrrOfNews[$i]['link']."','".$aqrrOfNews[$i]['description']."','".$aqrrOfNews[$i]['pubDate']."','".$aqrrOfNews[$i]['img']."','".$aqrrOfNews[$i]['plainText']."','".$aqrrOfNews[$i]['innertext']."','')";
				//echo $query;
			//	$result = mysql_query($query);
			$lsd = date('Y-m-d H:i:s');
			$id_country = '3';  //Россия по умолчанию
			$id_category = '7';  //Новости по умолчанию
			
				$query = "INSERT INTO Date_of_News  (date_Beg,date_End,event,ids_Theme,date_of_add,ids_country,category,Url_outs) VALUES ('".$aqrrOfChosenNews[$i]['pubDate']."','0000-00-00 00:00:00','".$event."','','$lsd','$id_country','$id_category','".$aqrrOfChosenNews[$i]['link']."')";	
				//$query = "INSERT INTO Date_Of_Events  (date_Beg,date_End,event) VALUES (STR_TO_DATE('15-8-1990 00:00:00', '%d-%m-%Y %H:%i:%s'),STR_TO_DATE('00-00-0000 00:00:00', '%d-%m-%Y %H:%i:%s'),'Начал писать события1111')";
				echo $query;
				$result = mysql_query($query);// or die(mysql_error());
				$id_date = mysql_insert_id ();
				//echo "id_dateЬ".$id_date;
				//$AllInfoDateArr[$i]['id'] = $id_date;
				

				
				if($result){ 			
					//вставяляем соотношение дата страна только, что созданной даты
					$query = "INSERT INTO Date_vs_countryN  (id_date,id_country) VALUES ('$id_date',	'$id_country')";
					$result = mysql_query($query);	
				}else{
					echo "<h1 style='color:red;'>!!!косяк!!!</h1>".mysql_error();
				}

		}