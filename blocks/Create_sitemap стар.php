<?						// команда для крона  10	6	*	*	*	cd /var/www/vhosts/probey.net/ZoomTime/blocks/ ; /usr/bin/php /var/www/vhosts/probey.net/ZoomTime/blocks/Create_sitemap.php																//адрес сайта				$ServerUrl = 'http://ZoomTime.ru';							//подключаем БД				include_once('../startup.php');						//include_once('../startup.php'); //если не подключено				startup();							/*				//достаем дату последней добавленной речи				$query =   "SELECT MAX(date) AS 'Max_Date'  FROM Speech_from_News"; 						$result = mysql_query($query);				$row = mysql_fetch_assoc($result);					$lsd = explode(' ',$row['Max_Date']);				$lsd = $lsd[0];			*/				$lsd = date('Y-m-d H:i:s');				$lsd = explode(" ",$lsd); 				$lsd = $lsd[0];				//var_dump($lsd);				//die();				// создаем новый xml документ				$dom = new DOMDocument('1.0', 'UTF-8');				$dom->formatOutput = true;					//создаем главные страницы					//date('Y-m-d H:i:s', time() - 60  * 20);							$pages = array(					array(							'url' => $ServerUrl.'/',							'changefreq' => 'daily',							'priority' => '1.00',							'lastmod' => $lsd						)					);										//Получаем все темы	//				$themes = getAllThemes();							//Получаем все события авторов (по 10 штук)					$events = getAllEventsPages();					$news = getAllNewsPages();					// и добавляем их в массив						//				$pages = array_merge($pages,$themes); 					$pages = array_merge($pages,$events);					$pages = array_merge($pages,$news); 															//вывод на экран количества страниц в sitemap					echo " Sitemap file was created. It contains ".sizeof($pages)." pages";					//var_dump(sizeof($pages));										$SITEMAP_NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';					$SITEMAP_NS_XSD = 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';					// ...and urlset (root) element					$urlSet = $dom->createElementNS($SITEMAP_NS, 'urlset');					$dom->appendChild($urlSet);					$urlSet->setAttributeNS('http://www.w3.org/2000/xmlns/' ,						'xmlns:xsi',						'http://www.w3.org/2001/XMLSchema-instance');					$urlSet->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance',						'schemaLocation',						$SITEMAP_NS . ' ' . $SITEMAP_NS_XSD);						//пробигаемся по страницам и закидываем их в ДОМ						foreach($pages as $page) {							$url = $page['url'];							// create url node for this page							$urlNode = $dom->createElementNS($SITEMAP_NS, 'url');							$urlSet->appendChild($urlNode);							// put url in 'loc' element							$urlNode->appendChild($dom->createElementNS(								$SITEMAP_NS,								'loc', $url));							$urlNode->appendChild(								$dom->createElementNS(									$SITEMAP_NS,									'changefreq',									$page['changefreq'])							);							$urlNode->appendChild(								$dom->createElementNS(									$SITEMAP_NS,									'priority',									$page['priority'])							);							$urlNode->appendChild(								$dom->createElementNS(									$SITEMAP_NS,									'lastmod',									$page['lastmod'])							);						}					$xml = $dom->saveXML();										//удаляем предыдуший файл					unlink('../sitemap.xml');					//сохраняем файл sitemap.xml на диск					file_put_contents('../sitemap.xml' , $xml);											//Получаем все темы,форумы,страницы из БД	function getAll($table){			if($table == 'Theme_of_Events'){					//$query = "SELECT max(date) as date,id_who FROM Speech_from_News GROUP BY id_who ";						$query = "SELECT * FROM Theme_of_Events";									}elseif($table == 'Date_Of_Events'){					$query = " SELECT * FROM Date_Of_Events ORDER BY date_Beg ";					 }			 			 			$result = mysql_query($query);// or die(mysql_error());			//return mysql_fetch_assoc($result);			if (!$result)				die(mysql_error().$query);						// Извлечение из БД.			$n = mysql_num_rows($result);			$arr = array();							for ($i = 0; $i < $n; $i++)			{				$row = mysql_fetch_assoc($result);						$arr[] = $row;			}			return $arr;	}				//функция, которая получает массив всех авторов речей с датой последжней речи для sitemap	function getAllThemes(){		$arr = getAll('Theme_of_Events');//return $arr;				//вытаскиваем переменную $numof из файла (сколько коментов на странице)		//include('../variables.php');	global $ServerUrl;		for($i=0; $i<count($arr); $i++){			$arr[$i]['url'] = $ServerUrl.'/index.php?id_theme='.$arr[$i]['id']; //soobsh&id_soobsh=23				 $time = explode(" ",$arr[$i]['date_last']);			$arr[$i]['lastmod']	= $time['0'];			$arr[$i]['changefreq']	= 'daily';			$arr[$i]['priority']	= '0.8';		}		return $arr;	}			//функция, которая берет все речи из базы и делает ссылки по 5 речей	function getAllEventsPages(){		$arr = getAll('Date_Of_Events');//return $arr;				//вытаскиваем переменную $numof из файла (сколько коментов на странице)		//include('../variables.php');		$k=0;		$dB='';		$dE='';		$arrRet = array();		for($i=0; $i<count($arr); $i++){						$k++; if($k>10){ $k=1; }		/*						if($k==1){ $dB=$arr[$i]['date_Beg']; $dE=$arr[$i]['date_End'];  $lastDateEv = $arr[$i]['date_of_add']; }//если это первый номер из пяти, то записываем его id (речи)							if($lastDateEv < $arr[$i]['date_of_add']){ $lastDateEv = $arr[$i]['date_of_add']; }	//находим последнюю дату				if($arr[$i]['date_End'] > $dE){					$dE=$arr[$i]['date_End'];				}					*/				if($k==1){ $dB=$arr[$i]['date_Beg']; $dE=$arr[$i]['date_Beg'];  $lastDateEv = $arr[$i]['date_of_add']; }//если это первый номер из пяти, то записываем его id (речи)							if($lastDateEv < $arr[$i]['date_of_add']){ $lastDateEv = $arr[$i]['date_of_add']; }	//находим последнюю дату				if($arr[$i]['date_Beg'] > $dE){					$dE=$arr[$i]['date_Beg'];				}					if($k==10){ //если это последний номер из пяти, то записываем его id (речи) и pfgbcsdftv ccskre				//$dB=$arr[$i]['date_Beg'];				$dB = explode(" ",$dB); $dB = $dB[0];				$dE = explode(" ",$dE); $dE = $dE[0];								if($dE < $dB){$dE = $dB;}				$IdSBEnd = $dB."|".$dE;			global $ServerUrl;												$arrRet[$i]['url'] = $ServerUrl.'/index.php?dBEnd='.$IdSBEnd; //soobsh&id_soobsh=23				//время берем последнее для темы				$time = explode(" ",$lastDateEv);				$arrRet[$i]['lastmod']	= $time['0'];				$arrRet[$i]['changefreq']	= 'daily';				$arrRet[$i]['priority']	= '0.8';			}		}		return $arrRet;	}			//функция, которая берет все новости из базы и разбивает их понедельно	function getAllNewsPages(){//return $arr;		$arrRet = array();		//вытаскиваем переменную $numof из файла (сколько коментов на странице)		//include('../variables.php');		//находим самую ранюю дату события		$query = "SELECT date_Beg FROM  Date_of_News  ORDER BY date_Beg LIMIT 1"  ;		//echo 	$query;		$result = mysql_query($query) or die(mysql_error());		$row = mysql_fetch_assoc($result);				$dateFirst = $row['date_Beg'];		//echo "<br />Самая раняя дата - ".$dateFirst."<br />";				//находим промежуток до первого воскресенья		//$week=array(0=>"вс", "пн","вт","ср","чт","пт","сб");		//$dateFirst = "2014-08-03";				$d1 = explode(" ",$dateFirst);		$d2 = explode("-",$d1[0]);		//находим день недели		//$week[date("w",mktime (0, 0, 0, $cur_month, $cur_day, $cur_year))];		$weekd = date("w",mktime (0, 0, 0, $d2[1], $d2[2], $d2[0]));				//echo "wd - ".$weekd;		//сколько дней до конца недели		$d_to_End = 8 - $weekd;				//(isset($_GET['DateBeg']) AND isset($_GET['DateEnd'])){		//находим следующий понедельник		$time = new DateTime($dateFirst);		$newtime = $time->modify('+'.$d_to_End.' day')->format('Y-m-d');		//echo "<br />следующий понедельник - ".$newtime;		//сегодняшняя дата 		$toDay = new DateTime('now');	$toDay = $toDay->format('Y-m-d');		//echo "<br />сегодня - ".$toDay;				//вытаскиваем все следующие понедельники		while($newtime < $toDay ){			$timeBeg = $newtime;			$time = new DateTime($newtime);			$newtime = $time->modify('+1 week')->format('Y-m-d');						//смотрим все новости внутри промежутка			$newtimeForQuery= $time->modify('-1 day')->format('Y-m-d');			$limit = 4; //ограничение кол-ва новостей			$query = "SELECT * FROM  Date_of_News   			WHERE date_Beg BETWEEN STR_TO_DATE('".$timeBeg." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('".$newtimeForQuery." 23:59:59', '%Y-%m-%d %H:%i:%s') LIMIT ".$limit;			//echo 	$query;			$result = mysql_query($query) or die(mysql_error());			$n = mysql_num_rows($result);			//если за неделю есть хоть какие-то новости			if($n >0){								//$arrE = array();				$newsStr ='';				for ($i = 0; $i < $n; $i++)				{					if($i >$limit){ break;}					$row = mysql_fetch_assoc($result);							//$arrE[] = $row;					//обрезаем новость					$ns = 35; //кол-во символов					$news_tit = $row['event'];					if (mb_strlen($news_tit,'UTF-8') > $ns)						$news_tit =  mb_substr($news_tit, 0, $ns, 'UTF-8').'...';					$newsStr .=	$news_tit;										//echo $row['date_Beg']." ".$row['event']."<br>";				}				//делаем ссылку				$b = explode(" ",$timeBeg); $e = explode(" ",$newtime);				//echo "<br /><a href='index.php?WeekFrom=".$b[0]."&onlyNews=1' >".$b[0]."-".$e[0]." | ".$newsStr;								global $ServerUrl;					$arrRet[$i]['url'] = $ServerUrl.'/index.php?onlyNews=1&amp;WeekFrom='.$b[0]; //soobsh&id_soobsh=23				//время берем последнее для темы				$time = explode(" ",$lastDateEv);				$arrRet[$i]['lastmod']	= $e[0];				$arrRet[$i]['changefreq']	= 'daily';				$arrRet[$i]['priority']	= '0.8';			}					}/*			global $ServerUrl;												$arrRet[$i]['url'] = $ServerUrl.'/index.php?dBEnd='.$IdSBEnd; //soobsh&id_soobsh=23				//время берем последнее для темы				$time = explode(" ",$lastDateEv);				$arrRet[$i]['lastmod']	= $time['0'];				$arrRet[$i]['changefreq']	= 'daily';				$arrRet[$i]['priority']	= '0.8';			}		*/		echo "---Новостей по неделям -".count($arrRet)."---<br />";				return $arrRet;	}					//$arr = getAllUsersPages();		//var_dump($arr);					?>