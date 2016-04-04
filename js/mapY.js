//alert('map')
	var myMap;


function ClearAllMappObjects(){
	 Polygons = [];  //массив полигонов
	 Polylines = [];  //массив линий
	 Arrows = [];  //массив стрелок
	 Placemarks = [];  //массив стрелок
	 lastSavedPloyg = -1;
	 lastSavedPloyL = -1;
	 lastSaveArrow = -1;
	 lastSavedPlacem = -1;
	 PoligKoord = [];
	 PoliLKoord = [];
	 ArrowKoord = [];
	 PlacemarkKoord = [];	
}	

	ClearAllMappObjects()
	//var myPolygon;	

	
function GeoArrowToMap(myMap,pathCoords,header,description,body,color,Weight,needSavetoArr){//отдаем яндексу контуры
	if( Arrows[Arrows.length-1] && ((Arrows.length-1)!=lastSaveArrow)){ console.log(Arrows[Arrows.length-1]); myMap.geoObjects.remove(Arrows[Arrows.length-1]);  Arrows.pop();} 
	//https://tech.yandex.ru/maps/jsbox/2.1/arrow
	var arrow=null;
							Arrows.push(arrow);  var n= Arrows.length-1;
	(function(na){ ymaps.modules.require(['geoObject.Arrow'], function (Arrow) {
						//var arrow = new Arrow([[57.733835, 38.788227], [55.833835, 35.688227]], null, {
		//				var arrow = new Arrow(pathCoords, 
		//Arrows[Arrows.length-1]= new Arrow(pathCoords,
		Arrows[na]= new Arrow(pathCoords,
						{
							 // Содержимое балуна.
								balloonContentHeader: header,
								balloonContentBody : body,
								// Содержимое хинта.
								hintContent: description
						 }
						,{						
							fillColor: color,//'#00FF00',
							// // Цвет обводки.
							 strokeColor: color,//'#00FF00',
							
							geodesic: true,
							strokeWidth: Weight,
							//opacity: 0.5,
							fillOpacity :0.1,
							strokeOpacity: 0.4,
							strokeStyle: 'shortdash'
						});
		//				Arrows.push(arrow);
						
						//myMap.geoObjects.add(Arrows[Arrows.length-1]);


			
			//редактирование
			//Arrows[Arrows.length-1].events.add('click', function (e) {
				Arrows[na].events.add('click', function (e) {
				//console.log(e.get('domEvent').originalEvent)
				
				//инфа
				console.log('Arrows - '+(Arrows.length-1)+'  click ')
				console.log(Arrows);
				for(var i=0; i<Arrows.length; i++){
					console.log( Arrows[i].geometry.getCoordinates())
				}
				
				if(e.get('domEvent').originalEvent.ctrlKey){//если нажата клавиша .ctrl то редактируем полигон
					e.get('target').editor.startEditing();
					startEditingHelp()
				}else{
					e.get('target').editor.stopEditing();
					SaveMapObjectsToBD()
					stopEditingHelp()
				}
				
			});
			
			//контекстное меню
		//	addContextMenu(Arrows[Arrows.length-1],Arrows,"Arrow")
		addContextMenu(Arrows[na],Arrows,"Arrow")
			
			// Добавляем многоугольникИ на карту.
		//	myMap.geoObjects.add(myPolygon);
		//	myMap.geoObjects.add(Arrows[Arrows.length-1]);
		myMap.geoObjects.add(Arrows[na]);
	}).then(function(data){if(needSavetoArr) {
		//добавляем в массив
		//alert('here');
		//lastSaveArrow = Arrows.length-1;
			console.log(data)
					ArrowKoord = [];
					console.log(Arrows);
				//	Arrows[Arrows.length-1].options.set({
					Arrows[na].options.set({
						fillOpacity: 0.5,
						strokeOpacity:0.9
					});
                    
                    DrowMapObjectList()
		
	}});
	})(n);
}
	
	
	
function GeoPolygonToMap(myMap,pathCoords,header,description,body,color,Weight){//отдаем яндексу контуры

	//if(myPolygon){ console.log(myPolygon); myMap.removeOverlay(myPolygon);} 
	//	if(myPolygon){ console.log(myPolygon); myMap.geoObjects.remove(myPolygon);} 
	if( Polygons[Polygons.length-1] && ((Polygons.length-1)!=lastSavedPloyg)){ console.log(Polygons[Polygons.length-1]); myMap.geoObjects.remove(Polygons[Polygons.length-1]);  Polygons.pop();} 
		//var myPolygon = new ymaps.GeoObject({
			//myPolygon = null;
	//		myPolygon = new ymaps.GeoObject({
		Polygons.push(new ymaps.GeoObject({
        // // Описываем геометрию геообъекта.
			geometry: {
            // // Тип геометрии - "Многоугольник".
            type: "Polygon",
            // // Указываем координаты вершин многоугольника.
            coordinates: 
                // // Координаты вершин контура.

				 pathCoords//,
            // // Задаем правило заливки внутренних контуров по алгоритму "nonZero".
             //fillRule: "nonZero"
         },
        // // Описываем свойства геообъекта.
         properties:{
             // Содержимое балуна.
          		balloonContentHeader: header,
				balloonContentBody : body,
				// Содержимое хинта.
				hintContent: description
         }
		}, {
        // // Описываем опции геообъекта.
        // // Цвет заливки.
         fillColor: color,//'#00FF00',
        // // Цвет обводки.
         strokeColor: color,//'#00FF00',
        // // Общая прозрачность (как для заливки, так и для обводки).
        //opacity: 0.35,
	//	 fillOpacity :0.35,
		fillOpacity :0.1,
        // // Ширина обводки.
         strokeWidth: Weight,//,
        // // Стиль обводки.
    //    strokeOpacity: 0.9
			strokeOpacity: 0.4
		 //visible: false
		})
		);
		
		//редактирование
		Polygons[Polygons.length-1].events.add('click', function (e) {
			//console.log(e.get('domEvent').originalEvent)
			
			//инфа
			console.log('Polygons - '+(Polygons.length-1)+'  click ')
			console.log(Polygons);
			for(var i=0; i<Polygons.length; i++){
				console.log( Polygons[i].geometry.getCoordinates())
			}
			
			if(e.get('domEvent').originalEvent.ctrlKey){//если нажата клавиша .ctrl то редактируем полигон
				e.get('target').editor.startEditing();
				startEditingHelp()
			}else{
				e.get('target').editor.stopEditing();
				SaveMapObjectsToBD()
				stopEditingHelp()
			}
			
		});
		
		//контекстное меню
		addContextMenu(Polygons[Polygons.length-1],Polygons,"Polygon")
		
		
		// Добавляем многоугольникИ на карту.
	//	myMap.geoObjects.add(myPolygon);
		myMap.geoObjects.add(Polygons[Polygons.length-1]);
}


function GeoPlacemarkToMap(myMap,pathCoords,header,description,body,color,Weight){//отдаем яндексу контуры

		console.log('Placemarks[Placemarks.length-1]' + Placemarks[Placemarks.length-1]+ "-" +lastSavedPlacem);
	//if(myPolygon){ console.log(myPolygon); myMap.removeOverlay(myPolygon);} 
	//	if(myPolygon){ console.log(myPolygon); myMap.geoObjects.remove(myPolygon);} 
	if( Placemarks[Placemarks.length-1] && ((Placemarks.length-1)!=lastSavedPlacem)){ console.log('remove Placemarks'); console.log(Placemarks[Placemarks.length-1]); myMap.geoObjects.remove(Placemarks[Placemarks.length-1]);   Placemarks.pop();} 
		//var myPolygon = new ymaps.GeoObject({
			//myPolygon = null;
	//		myPolygon = new ymaps.GeoObject({
		Placemarks.push(//new ymaps.GeoObject({
			new ymaps.Placemark(pathCoords, 

        // // Описываем свойства геообъекта.
{
             // Содержимое балуна.
          		balloonContentHeader: header,
				balloonContentBody : body,
				// Содержимое хинта.
				//hintContent: header+" "+description
				hintContent: description
		}, {
			iconColor: color
		 //visible: false
		})
		);
		
		//редактирование
		Placemarks[Placemarks.length-1].events.add('click', function (e) {
			//console.log(e.get('domEvent').originalEvent)
			
			//инфа
			console.log('Placemarks - '+(Placemarks.length-1)+'  click ')
			console.log(Placemarks);
			for(var i=0; i<Placemarks.length; i++){
				console.log( Placemarks[i].geometry.getCoordinates())
			}
			
			if(e.get('domEvent').originalEvent.ctrlKey){//если нажата клавиша .ctrl то редактируем полигон
				e.get('target').editor.startEditing();
				startEditingHelp()
			}else{
				e.get('target').editor.stopEditing();
				SaveMapObjectsToBD()
				stopEditingHelp()
			}
			
		});
		
		//контекстное меню
		addContextMenu(Placemarks[Placemarks.length-1],Placemarks,"Placemark")
		
		// Добавляем многоугольникИ на карту.
	//	myMap.geoObjects.add(myPolygon);
		myMap.geoObjects.add(Placemarks[Placemarks.length-1]);
}


function removeMenu(){
		// Удаляем контекстное меню.
	$('#menuYa').remove();
}

function addContextMenu(obj,arr,objN){


			//контекстное меню
		//https://tech.yandex.ru/maps/jsbox/2.0/geoobject_contextmenu
		//Polygons[Polygons.length-1].events.add('contextmenu', function (e) {
		obj.events.add('contextmenu', function (e) {
			
		console.log('contextmenu')
		console.log(arr)
		for(var i=0; i<arr.length; i++){
			console.log( arr[i].geometry.getCoordinates())
			if(arr[i] == obj){
				console.log('elNNNNN-'+i)
			}
		}
			
			
        // Если меню метки уже отображено, то убираем его.
        if ($('#menuYa').css('display') == 'block') {
            $('#menuYa').remove();
        } else {
            // HTML-содержимое контекстного меню.
            var menuContent =
                '<div id="menuYa">\
				<div style="float:right;"><b onclick="removeMenu();" style="cursor:pointer;">x</b>&nbsp;&nbsp;</div>\
				<ul id="menu_list">\
                        <li>Заголовок: <br /> <input type="text" name="header_text" /></li>\
                        <li>Тело: <br /> <input type="text" name="body_text" /></li>\
                        <li>hint/описание: <br /> <input type="text" name="hint_text" /></li>\
						<li>Цвет: <br /> <input type="text" name="color_text" /></li>\
						<li>Толщина: <br /> <input type="text" name="Width_text" /></li>\
                    </ul>\
                <div align="center"><input type="submit" value="Сохранить" /></div>\
				<div align="center"><button type="button" value="Удалить объект">Удалить объект</button></div>\
                </div>';

            // Размещаем контекстное меню на странице
            $('body').append(menuContent);
			
			// Задаем позицию меню.
            $('#menuYa').css({
                left: e.get('position')[0],
                top: e.get('position')[1],
				zIndex: 101
            });
			console.log('menuYa position - '+e.get('position')[0] +" - "+e.get('position')[1]);
			//	console.log('elN-'+elN)
		
			// Заполняем поля контекстного меню текущими значениями свойств метки.  
				$('#menuYa input[name="header_text"]').val(obj.properties.get('balloonContentHeader'));
				$('#menuYa input[name="body_text"]').val(obj.properties.get('balloonContentBody'));
				$('#menuYa input[name="hint_text"]').val(obj.properties.get('hintContent'));
				
				$('#menuYa input[name="Width_text"]').val(obj.options.get('strokeWidth'));
				
				if(objN != "Placemark"){
					
					$('#menuYa input[name="color_text"]').val(obj.options.get('fillColor'));
				}else{
					$('#menuYa input[name="color_text"]').val(obj.options.get('iconColor'));
				}
				// При нажатии на кнопку "Сохранить" изменяем свойства метки
				// значениями, введенными в форме контекстного меню.
				$('#menuYa input[type="submit"]').click(function () {
					obj.properties.set({
						balloonContentHeader: $('input[name="header_text"]').val(),
						hintContent: $('input[name="hint_text"]').val(),
						balloonContentBody: $('input[name="body_text"]').val()
					});
					
					obj.options.set({
						strokeWidth:$('input[name="Width_text"]').val()
					});
					
					if(objN != "Placemark"){
						obj.options.set({
							fillColor: $('input[name="color_text"]').val(),
							strokeColor: $('input[name="color_text"]').val()
						});							
					}else{
						obj.options.set({
							iconColor: $('input[name="color_text"]').val()
						});							
					}
				
					// Удаляем контекстное меню.
					$('#menuYa').remove();
					DrowMapObjectList()
					SaveMapObjectsToBD()
				});			

				// удаление объекта с карты .
				$('#menuYa button').click(function () {
					// Удаляем контекстное меню.
					$('#menuYa').remove();	
					console.log('Удаляем geoObjects ')
					myMap.geoObjects.remove(obj);  
					console.log(arr)
					/*
					for(var i=0; i<arr.length; i++){
						console.log( arr[i].geometry.getCoordinates())
					}
					arr.splice(elN,1);	
					arr.length = 0;*/
					for(var i=0; i<arr.length; i++){
						if(arr[i] == obj){
							//console.log( arr[i].geometry.getCoordinates())
							arr.splice(i,1);	
						}
					}
					
					//пересчет массива
					if(objN == "Polygon"){
						lastSavedPloyg = arr.length-1;
					}
					if(objN == "Polyline"){
						lastSavedPloyL = arr.length-1;
					}
					if(objN == "Arrow"){
						lastSaveArrow = arr.length-1;
					}
					if(objN == "Placemark"){
						lastSavedPlacem = arr.length-1;
					}
					DrowMapObjectList()
					SaveMapObjectsToBD()
					
				});				
		
		}
		
		});
		
}

function GeoPolylineToMap(myMap,pathCoords,header,description,body,color,Weight){//отдаем яндексу контуры

	//if(myPolygon){ console.log(myPolygon); myMap.removeOverlay(myPolygon);} 
	//	if(myPolygon){ console.log(myPolygon); myMap.geoObjects.remove(myPolygon);} 
	if( Polylines[Polylines.length-1] && ((Polylines.length-1)!=lastSavedPloyL)){ console.log(Polylines[Polylines.length-1]); myMap.geoObjects.remove(Polylines[Polylines.length-1]);  Polylines.pop();} 
	
		//var myPolygon = new ymaps.GeoObject({
			//myPolygon = null;
	//		myPolygon = new ymaps.GeoObject({
		Polylines.push(
			
			new ymaps.Polyline(pathCoords,
		// new PolylineWithArrows(pathCoords,
			{
					balloonContentHeader: header,
					balloonContentBody : body,
					// Содержимое хинта.
					hintContent: description
			},
			{
			// // Описываем опции геообъекта.
			// // Цвет заливки.
			 fillColor: color,//'#00FF00',
			// // Цвет обводки.
			 strokeColor: color,//'#00FF00',
			// // Общая прозрачность (как для заливки, так и для обводки).
			//opacity: 0.35,
			 fillOpacity :0.1,
			// // Ширина обводки.
			 strokeWidth: Weight,//,
			// // Стиль обводки.
			 strokeOpacity: 0.4
			 //visible: false
			}
			)
		);
		
		//редактирование
		Polylines[Polylines.length-1].events.add('click', function (e) {
			//console.log(e.get('domEvent').originalEvent)
			
			//инфа
			console.log('Polylines - '+(Polylines.length-1)+'  click ')
			console.log(Polylines);
			for(var i=0; i<Polylines.length; i++){
				console.log( Polylines[i].geometry.getCoordinates())
			}
			
			if(e.get('domEvent').originalEvent.ctrlKey){//если нажата клавиша .ctrl то редактируем полигон
				e.get('target').editor.startEditing();
				
			}else{
				e.get('target').editor.stopEditing();
				SaveMapObjectsToBD()
			}
			
		});
		
		
		//контекстное меню
		addContextMenu(Polylines[Polylines.length-1],Polylines,"Polyline")
		
		
		// Добавляем многоугольникИ на карту.
	//	myMap.geoObjects.add(myPolygon);
		myMap.geoObjects.add(Polylines[Polylines.length-1]);
}


function startEditingHelp(){
	BottomHelper('*Для завершения изменения размеров объекта кликните нан него мышью',18,'orange','white',true)
}

function stopEditingHelp(){
	BottomHelper('*Для изменения свойств объекта, кликните на нем правой кнопкой мыши.<br /> *Для изменения размеров объекта, зажмите ctrl и кликните на объекте ',16,'green','white',true)
}

//объекты карты из БД
var maoObjectsFromBD 

function ShowMap(maoObj){
	ClearAllMappObjects()
	if(maoObj){
		maoObjectsFromBD = maoObj
		//рисуем на карте объекты
	}else{
		maoObjectsFromBD = null	
		
	}
    console.log('maoObjectsFromBD');
    console.log(maoObjectsFromBD);
	//if(Coords){
		//показываем карту
		$('#map-canvas').css('display','block');
		
		//показываем только если у нас права редактора
		 if(IsRedactor){ 
			//alert('IsRedactor');
			$('#mapObject-menu').css('display','block');
			$('#map-helper').css('display','block');
		}
		drawMapYandex1();
		//drawMapYandex1(Coords[0],Coords[1]);
		//$('html,body').animate({ scrollTop: $('#map-canvas').offset().top }, { duration: 'slow', easing: 'swing'});
		//alert('ShowYa1')
	//}
}

//переписанная функция initYa
function initYa1() 
{
	/*
    ourLAT = 55.75;
	ourLON = 37.61;
	
	*/
	

	
	Lic_Name = 'карта ел'
	//var myMap;//, myPlacemark;
	    myMap = new ymaps.Map('map-canvas',
		{
			// При инициализации карты обязательно нужно указать
			// её центр и коэффициент масштабирования.
			center: [ourLAT, ourLON],  // Moscow [55.75,37.61]
			//center: [55.75,37.61],
			zoom: 8,
			type: 'yandex#satellite'//
		}
	);
	
    prepareYa(myMap);
    
	if(maoObjectsFromBD){//рисуем объекты из БД
		if(maoObjectsFromBD.Polygon){
			/*console.log('Polygon frob BD');
			console.log(maoObjectsFromBD.Polygon);
			console.log(Polygons);*/
		   for(var key in maoObjectsFromBD.Polygon)
			{
				var obj = maoObjectsFromBD.Polygon[key]
				GeoPolygonToMap(myMap,obj['Coordinates'],obj['balloonContentHeader'],obj['hintContent'],obj['balloonContentBody'],obj['fillColor'],obj['strokeWidth']);
					lastSavedPloyg = Polygons.length-1;
					PoligKoord = [];
					//меняем прозрачность
					//console.log(Polygons);
					Polygons[Polygons.length-1].options.set({
						fillOpacity: 0.35,
						strokeOpacity:0.9
					});		
			}

		}
		if(maoObjectsFromBD.Arrow){
			/*console.log('Arrow frob BD');
			console.log(maoObjectsFromBD.Arrow);
			console.log(Arrows);*/
		   var i = 1;
		   for(var key in maoObjectsFromBD.Arrow)
			{
				i++;
				var obj = maoObjectsFromBD.Arrow[key]
				
				//setTimeout(function() { //alert('0.5 секунды')
				GeoArrowToMap(myMap,obj['Coordinates'],obj['balloonContentHeader'],obj['hintContent'],obj['balloonContentBody'],obj['fillColor'],obj['strokeWidth'],true);
					/*lastSaveArrow = Arrows.length-1;
					ArrowKoord = [];
					console.log(Arrows);
					Arrows[Arrows.length-1].options.set({
						fillOpacity: 0.5,
						strokeOpacity:0.9
					});	 */
				//}, 1500*i)		
			}			
		}
		if(maoObjectsFromBD.Placemark){
			/*console.log('Placemark frob BD');
			console.log(maoObjectsFromBD.Placemark);*/
		   for(var key in maoObjectsFromBD.Placemark)
			{			
				var obj = maoObjectsFromBD.Placemark[key]
				GeoPlacemarkToMap(myMap,obj['Coordinates'],obj['balloonContentHeader'],obj['hintContent'],obj['balloonContentBody'],obj['iconColor'],obj['strokeWidth']);
					lastSavedPlacem = Placemarks.length-1;
					PlacemarkKoord = [];
						Placemarks[Placemarks.length-1].options.set({
						fillOpacity: 0.5,
						strokeOpacity:0.9
					});			
			
			}
		}
		if(maoObjectsFromBD.Polyline){
			/*console.log('Polyline frob BD');
			console.log(maoObjectsFromBD.Polyline);*/
		     for(var key in maoObjectsFromBD.Polyline)
			{	
				var obj = maoObjectsFromBD.Polyline[key]
				GeoPolylineToMap(myMap,obj['Coordinates'],obj['balloonContentHeader'],obj['hintContent'],obj['balloonContentBody'],obj['fillColor'],obj['strokeWidth']);	
					lastSavedPloyL = Polylines.length-1;
					PoliLKoord = [];
						Polylines[Polylines.length-1].options.set({
						fillOpacity: 0.35,
						strokeOpacity:0.9
					});					
			}		
		}
		
		DrowMapObjectList()
	}
	
	if(IsRedactor){ //если мы можем редактировать, то  вешаем события по рисованию карты
		//событие при шелчке на карте
		myMap.events.add('click', function (e) {
			//alert('Событие на карте'+e.get('coords')); // Возникнет при щелчке на карте, но не на маркере.
			/*console.log('bbbbbb1');
			console.log(e);*/
			//доступ к событию дом
			/*console.log(e.get('domEvent').originalEvent)
			console.log(e.get('domEvent').originalEvent.ctrlKey)*/
			
			//цвет
			var objColor = '#00FF00'
			objColor = document.getElementById('objColor').value;
			//console.log('objColor - '+objColor);
			
			//форма 
			var objShape = 'Polygon'
			objShape = document.getElementById('objShape').value;
			//console.log('objShape - '+objShape);
			
			var header = document.getElementById('mapObjHeader').value;
			var body = document.getElementById('mapObjBody').value;
			var description = document.getElementById('mapObjDescription').value;
			var Weight = document.getElementById('mapObjWeight').value; if(Weight <1){Weight=1;}
			
			if(e.get('domEvent').originalEvent.ctrlKey){//если нажата клавиша .ctrl то рисуем полигон
				

				var msg = '*Для нанесения следующей координаты обьекта не отжимайте ctrl перед кликом.<br /> *Чтобы закончить прорисовку этого объекта отожмите ctrl и кликните на карте';
				if(objShape == 'Polygon'){
					
					PoligKoord.push(e.get('coords'));
					//console.log(PoligKoord)
					//GeoPolygonToMap(myMap,PoligKoord,lic_name,PART_name,stat_otv,objColor,Weight);	
					//if(PoligKoord.length>1){
						//if(myPolygon){myMap.removeOverlay(myPolygon);}
						//myPolygon = null;
						GeoPolygonToMap(myMap,[PoligKoord],header,description,body,objColor,Weight);
					//}				
				}
				if(objShape == 'Polyline'){
					//if(PoliLKoord.length>1){ PoliLKoord.pop();}
					PoliLKoord.push(e.get('coords'));
					
					//console.log(PoliLKoord)		
					GeoPolylineToMap(myMap,PoliLKoord,header,description,body,objColor,Weight);				
				}			
				if(objShape == 'Arrow'){
					ArrowKoord.push(e.get('coords'));
					//console.log(ArrowKoord)		
					GeoArrowToMap(myMap,ArrowKoord,header,description,body,objColor,Weight,false);			
				}
				if(objShape == 'Placemark'){
										msg = '*Для изменения расположения метки на карте не отжимая ctrl кликните на новое место.<br /> *Чтобы зафиксировать метку отожмите ctrl и кликните на карте';
					//PlacemarkKoord.push(e.get('coords'));
					PlacemarkKoord = e.get('coords');
					//console.log(PlacemarkKoord)		
					GeoPlacemarkToMap(myMap,PlacemarkKoord,header,description,body,objColor,Weight);			
				}

				BottomHelper(msg,16,'yellow','black',true)
			
			}else{//сохраняем объект в массив и создаем новый
				if(objShape == 'Polygon'){
					lastSavedPloyg = Polygons.length-1;
					PoligKoord = [];
					//меняем прозрачность
					Polygons[Polygons.length-1].options.set({
						fillOpacity: 0.35,
						strokeOpacity:0.9
					});		
				}
				if(objShape == 'Polyline'){
					lastSavedPloyL = Polylines.length-1;
					PoliLKoord = [];
						Polylines[Polylines.length-1].options.set({
						fillOpacity: 0.35,
						strokeOpacity:0.9
					});	
				}	
				if(objShape == 'Arrow'){
					lastSaveArrow = Arrows.length-1;
					ArrowKoord = [];
						Arrows[Arrows.length-1].options.set({
						fillOpacity: 0.5,
						strokeOpacity:0.9
					});	
				}
				if(objShape == 'Placemark'){
					lastSavedPlacem = Placemarks.length-1;
					PlacemarkKoord = [];
						Placemarks[Placemarks.length-1].options.set({
						fillOpacity: 0.5,
						strokeOpacity:0.9
					});	
					/*console.log('Placemarks');
					console.log(Placemarks);*/

				}

				stopEditingHelp()
				DrowMapObjectList()
				//сохранение в БД
				SaveMapObjectsToBD()
			}
			
		});
	}
	
	if(maoObjectsFromBD){
    if(northWest[0] != southEast[0])
	{
		/*if(BrowserIE_OLD)
		{
			myMap.setBounds([pointMin, pointMax]);// for IExplorer < 11 and old browsers
		}
		else
		{*/
			 myMap.setBounds([pointMin, pointMax], // for others..
			 {
				 checkZoomRange: true
			 }).then(function () {
				//Действие было успешно завершено.
				} , function (err) {
				//Не удалось показать заданный регион
				myMap.zoomRange.get(myMap.getCenter()).
				then(function (zoomRange) {
   				 if(myMap.getZoom() > zoomRange[1])
					myMap.setZoom(zoomRange[1]);
				});

			}, this);
		//}
	}	
    }
	
	//prepareYa(myMap);
	
		myMap.setType('yandex#hybrid', {
 		checkZoomRange: true
		}).then(function () {
		 // Тип карты был установен с допустимым уровнем масштабирования.
		}, this);
}


//сохранение в БД
function SaveMapObjectsToBD(){
	arrObjToBD = {};//обнуляем
	
	//console.log('SaveMapObjectsToBD')
	if(Placemarks.length >0){
		GetJSONMapObjects(Placemarks,'Placemark')
	}	
	if(Polygons.length >0){
		GetJSONMapObjects(Polygons,'Polygon')
	}
	if(Polylines.length >0){
		GetJSONMapObjects(Polylines,'Polyline')
	}
	if(Arrows.length >0){
		GetJSONMapObjects(Arrows,'Arrow')
	}
	
	
	//console.log(arrObjToBD);
	var arrObjJson = JSON.stringify(arrObjToBD);
	//console.log('arrObjJson-'+arrObjJson)
	//сохраняем в БД
	$.ajax({
	  async: false, 
	  url: 'blocks/dinamic_scripts/saveMapObj.php',
	  data: {arrObjJson:arrObjJson,id_ev:CurrentEnentNum},
	  type: "POST",
	  success: function(data) {  arr = data;  alert(data)
	  }//,
		//dataType: 'json'
	})
	
}


function GetJSONMapObjects(arr,objN){
	var newArr = {};
	for(var i=0; i<arr.length;i++){
		var arr1 = {};
		arr1['Coordinates']=arr[i].geometry.getCoordinates()
		arr1['strokeWidth']=arr[i].options.get('strokeWidth')
		
		if(objN != "Placemark"){
			arr1['fillColor']=arr[i].options.get('fillColor')
		}else{
			arr1['iconColor']=arr[i].options.get('iconColor')
		}

		arr1['balloonContentHeader']=arr[i].properties.get('balloonContentHeader');
		arr1['balloonContentBody']=arr[i].properties.get('balloonContentBody');
		arr1['hintContent']=arr[i].properties.get('hintContent');
		newArr[i] = arr1;
	}
	arrObjToBD[objN] = newArr;
	/*console.log('arrObjToBD');
	console.log(arrObjToBD);*/
}

//перерисовка меню обьектов на карте
function DrowMapObjectList(){
	//console.log('DrowMapObjectList2')
						document.getElementById('map-menu').innerHTML = '';
					var maWidth = $('#map-canvas').width()
					//console.log('masp width -'+maWidth);
					menu = $('<ul class="menuMapObj" style="left:'+(maWidth-50)+'px;"/>');
						
						
						if(Placemarks.length >0){
							// Контейнер для подменю.
							submenuPlaceml = $('<li ><i>+</i><b style="text-decoration:underline; cursor:pointer; color:blue;">'+MapObjArr['Placemark']+'</b></li>');
							//submenuPlaceml.click(function(){ alert('bbbbb')});
							submenuPlaceml.addClass('subM')
							submenuPlacem = $('<ul class="submenu"/>');
							menu.append(submenuPlaceml)
							submenuPlaceml.append(submenuPlacem)						
								// Перебираем все метки.
								
								for (var i = 0; i < Placemarks.length; i++) {
									AddMenuItem(i,Placemarks,submenuPlacem,'Placemark')
								}
						}
	
						if(Polygons.length >0){
							// Контейнер для подменю.
							submenuPolygl = $('<li><i>+</i><b style="text-decoration:underline; cursor:pointer; color:blue;">'+MapObjArr['Polygon']+'</b></li>');
							submenuPolygl.addClass('subM')
							submenuPolyg = $('<ul class="submenu"/>');
							menu.append(submenuPolygl)
							submenuPolygl.append(submenuPolyg)						
								// Перебираем все метки.
								
								for (var i = 0; i < Polygons.length; i++) {
									AddMenuItem(i,Polygons,submenuPolyg,'Polygon')
								}
						}

						if(Polylines.length >0){
							// Контейнер для подменю.
							submenuPolyll = $('<li><i>+</i><b style="text-decoration:underline; cursor:pointer; color:blue;">'+MapObjArr['Polyline']+'</b></li>');
							submenuPolyll.addClass('subM')
							submenuPolyl = $('<ul class="submenu"/>');
							menu.append(submenuPolyll)
							submenuPolyll.append(submenuPolyl)						
								// Перебираем все метки.
								
								for (var i = 0; i < Polylines.length; i++) {
									AddMenuItem(i,Polylines,submenuPolyl,'Polyline')
								}
						}

						if(Arrows.length >0){
							// Контейнер для подменю.
							submenuArrowl = $('<li><i>+</i><b style="text-decoration:underline; cursor:pointer; color:blue;" >'+MapObjArr['Arrow']+'</b></li>');
							submenuArrowl.addClass('subM')
							submenuArrow = $('<ul class="submenu"/>');
							menu.append(submenuArrowl)
							submenuArrowl.append(submenuArrow)						
								// Перебираем все метки.
								
								for (var i = 0; i < Arrows.length; i++) {
									AddMenuItem(i,Arrows,submenuArrow,'Arrow')
								}
						}						
						menu.appendTo($('#map-menu'));
						
						
						
						//спрятать показать подменю
						//$('.subM').click(function(){ alert(this.html())});
						//menu.find('.subM').click(function(){ console.log( $(this).find('.submenu').hide())});
						$(menu.find('.subM').find('.submenu')).hide();
						$(menu.find('.subM').find('.submenu')[0]).show();  $(menu.find('.subM').find('i')[0]).html('-');
				//		$(menu.find('.subM')).find('b').toggle(function(){  $(this).parent().find('.submenu').show()},function(){  $(this).parent().find('.submenu').hide()});
					$(menu.find('.subM')).find('b').click(function(){  
						//alert($(this).parent().find('i').html())
						if($(this).parent().find('i').html() == '+'){
							$(this).parent().find('.submenu').show();
							$(this).parent().find('i').html('-');
						}else{
							$(this).parent().find('.submenu').hide();
							$(this).parent().find('i').html('+');							
						}
					
					});
					//$(menu.find('.subM')).find('b').click(function(){alert($(this).html())})
					//	console.log( $(menu.find('.subM')[0]).html());
						
}

function AddMenuItem(x,itemArr,submenu,kind){
								// Добавляем подменю.
						//	(function(x){	
			//					var menuItem = $('<li><b>' + Placemarks[x].properties.get('hintContent') + '</b></li>')
						var content = 'без названия';
						if(!itemArr[x]){
							return;
						}
						
						if(itemArr[x] && itemArr[x].properties.get('hintContent') !='' && itemArr[x].properties.get('hintContent') !=' '){
							//console.log('content-'+itemArr[x].properties.get('hintContent')+'-');
							content = itemArr[x].properties.get('hintContent');
						}
						
						var menuItem = $('<li><span style="text-decoration:underline; cursor:pointer; color:blue;">' + content + '</span></li>')
					//			submenuPlacem.append(menuItem)
									submenu.append(menuItem)
									
									var point;
									if(kind =='Placemark'){
										point = itemArr[x].geometry.getCoordinates()
									}else if(kind =='Polygon'){
										point = itemArr[x].geometry.getCoordinates()[0][0]
									}
									else{
										point = itemArr[x].geometry.getCoordinates()[0]
									}
									
									menuItem.click(function () {
						
											myMap.panTo(
					//						Placemarks[x].geometry.getCoordinates()
											
											point
											).then(function () {
					//							Placemarks[x].balloon.open();
												itemArr[x].balloon.open();
											}, function (err) {
											 alert('Произошла ошибка ' + err);
											}, this);	
									});
							//	})(i);
	//BottomHelper('Для активизации',26,'yellow','black',true)
}

function BottomHelper(text,size,color,fontcolor,visible){

	if(!visible){
		helperCont.html('');
		return;
	}
	
	var map = $('#map-canvas')
	var helperCont = $('#map-helper')
	helperCont.html('');
	
	
	helperBody = $('<div class="helperBody" />');
	
	//menu.appendTo($('#map-menu'));
	helperBody.appendTo(helperCont);
	
	//console.log('BottomHelper'+map.width() );
	//var map = $('#map-canvas').width()	
	
	helperBody.width(map.width()+'px');
	helperBody.height('40px');
	helperBody.css('position','absolute')
	helperBody.css('top',map.height()-40)
	helperBody.css('z-index','100')
	helperBody.css('background-color',color)
	helperBody.css('text-align','center')
	helperBody.css('font-size',size+'px')
	helperBody.css('color',fontcolor)
	helperBody.css('opacity',0.7)
	helperBody.css('font-weight','bold')
	helperBody.html(text)
	//map-helper
	
}



function prepareYa(myMap){
	//var myMap;//, myPlacemark;
	// Создание экземпляра карты и его привязка к контейнеру с
    // заданным id ("map-canvas").

	   
	while(myMap.controls.get(0) != null)
	{
		  myMap.controls.remove(myMap.controls.get(0));
	}
	
	
	// Создаем геообъект с типом геометрии "Точка".в геометрическом центре участка
    myCenterMark = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [ourLAT, ourLON]
            },
            // Свойства.
            properties: {
                // hint метки. 
                hintContent: Lic_Name 
            }
        }, {
            // Опции.
             preset: 'islands#circleDotIcon',
            // Метку нельзя перемещать.
            draggable: false,
			iconColor: '#ffd700' // Gold
        });

		myCenterMark.events.add("click", function (event)
			{
				if(!myMap.balloon.isOpen())
				{
					var coords = event.get('coords');
				    myMap.balloon.open(coords, {
						contentHeader: Name(),
						contentBody: StatOtv(),
						contentFooter:''
					});
				}
				else
				{
					myMap.balloon.close();
				}
			
			});
	myMap.geoObjects.add(myCenterMark);
	myCenterMark.options.set("visible", false);
  
	
	// rulerData = new ymaps.data.Manager({
		// content : '<div class="ruler-button"><img src="./img/button_plus.png"></div>',
		// title : 'ruuuler',
		// size  : 'large'
	// });
	//if(!BrowserIE_OLD)
	//{
		rulerControl = new ymaps.control.RulerControl({// data :  rulerData,
														options :{ 
														position :{
																top : 'auto',
																bottom : 30,
																left : 10,
																right : 'auto'
															},	 
														scaleLine : true,
														visible : true
													}
											//		,state :	{
											//				enabled : true
											//				size = 'auto'
											//				}
															} );												
		//rulerControl.state.set('size', 'large'); large, small, auto, medium
		myMap.controls.add(rulerControl);
	//}
			
	myMap.behaviors.disable('default');	
	//if(!BrowserIE_OLD)
	//{
		myMap.behaviors.enable('drag');
		myMap.behaviors.disable('scrollZoom');//масштабирование колесом мыши
		
		myMap.controls.add('typeSelector');
		myMap.controls.add('searchControl');
		myMap.controls.add('fullscreenControl');
	//}
//	else
	//{
//		myMap.behaviors.enable('default');
	//	myMap.behaviors.enable('drag');
	//	myMap.behaviors.enable('scrollZoom');//масштабирование колесом мыши
//	}
	
	// Создадим пользовательский макет ползунка масштаба.
    ZoomLayout = ymaps.templateLayoutFactory.createClass("<div>" +
                "<div id='zoom-in' class='zoom-button'><img src='./img/button_plus.png'></div>"+
				"<br><br><br><br>" +
                "<div id='zoom-out' class='zoom-button'><img src='./img/button_minus.png'></div>" +
            "</div>", {

            // Переопределяем методы макета, чтобы выполнять дополнительные действия
            // при построении и очистке макета.
            build: function () {
                // Вызываем родительский метод build.
                ZoomLayout.superclass.build.call(this);

                // Привязываем функции-обработчики к контексту и сохраняем ссылки
                // на них, чтобы потом отписаться от событий.
                this.zoomInCallback = ymaps.util.bind(this.zoomIn, this);
                this.zoomOutCallback = ymaps.util.bind(this.zoomOut, this);

                // Начинаем слушать клики на кнопках макета.
                $('#zoom-in').bind('click', this.zoomInCallback);
                $('#zoom-out').bind('click', this.zoomOutCallback);
            },

            clear: function () {
                // Снимаем обработчики кликов.
                $('#zoom-in').unbind('click', this.zoomInCallback);
                $('#zoom-out').unbind('click', this.zoomOutCallback);

                // Вызываем родительский метод clear.
                ZoomLayout.superclass.clear.call(this);
            },

            zoomIn: function () {
                var mapx = this.getData().control.getMap();
				if(mapx.getZoom() + 1 > 5)
				{
					myCenterMark.options.set("visible", false);
					//mapx.geoObjects.remove(myCenterMark);
					//for(var i = 0; i < map.geoObjects.length; i++)
					//{
					//	mapx.geoObjects.get(i).options.set("visible", true);
					//}
				}
                // Генерируем событие, в ответ на которое
                // элемент управления изменит коэффициент масштабирования карты.
                this.events.fire('zoomchange', {
                    oldZoom: mapx.getZoom(),
                    newZoom: mapx.getZoom() + 1
                });
            },

            zoomOut: function () {
                var map = this.getData().control.getMap();
				if(map.getZoom() - 1 < 6)
				{
					myCenterMark.options.set("visible", true);
				}
				this.events.fire('zoomchange', {
                    oldZoom: map.getZoom(),
                    newZoom: map.getZoom() - 1
                });
            }
        });
    zoomControl = new ymaps.control.ZoomControl({ options: { layout: ZoomLayout } });

	myMap.controls.add(zoomControl);
	
	//Lic_Name = Name() + "  " + StatOtv();	
	
}


var ArrowInitializated = false;
//переписанная функция drawMapYandex
function drawMapYandex1()//(lat, lon, sk42)
{

		//CalcRegion1(pointsUnnamed.concat(pointsNamed));
		if(maoObjectsFromBD){
           CalcRegion1(maoObjectsFromBD) 
           $('#ShowM').css('display','none') 
$('#ShowM').hide()           
        }else{
            ourLAT = 55.75;
            ourLON = 37.61;
            // document.getElementById('ShowM')  
//            $('#ShowM').css('display','block')    
  $('#ShowM').show()           
        }
        
	//	CoorUN = pointsUnnamed;
	//	CoorN = pointsNamed;
		document.getElementById('map-canvas').innerHTML = '';
		ymaps.ready(initYa1);

	
	if(!ArrowInitializated){
		ArrowInitializated = true;
		ArrowInitialization();
	}
	
	BottomHelper('*Для нанесения объектов на карту, выберите параметры объекта (над картой), и зажав клавишу ctrl кликами мыши наносите объект на карту',16,'blue','white',true)
		
}	
	

	


//модуль (внешний) для рисования стрелок	
function ArrowInitialization(){
/*
 * Класс, позволяющий создавать стрелку на карте.
 * Является хелпером к созданию полилинии, у которой задан специальный оверлей.
 * При использовании модулей в реальном проекте рекомендуем размещать их в отдельных файлах.
 */
ymaps.modules.define("geoObject.Arrow", [
    'Polyline',
    'overlay.Arrow',
    'util.extend'
], function (provide, Polyline, ArrowOverlay, extend) {
    /**
     * @param {Number[][] | Object | ILineStringGeometry} geometry Геометрия ломаной.
     * @param {Object} properties Данные ломаной.
     * @param {Object} options Опции ломаной.
     * Поддерживается тот же набор опций, что и в классе ymaps.Polyline.
     * @param {Number} [options.arrowAngle=20] Угол в градусах между основной линией и линиями стрелки.
     * @param {Number} [options.arrowMinLength=3] Минимальная длина стрелки. Если длина стрелки меньше минимального значения, стрелка не рисуется.
     * @param {Number} [options.arrowMaxLength=20] Максимальная длина стрелки.
     */
    var Arrow = function (geometry, properties, options) {
        return new Polyline(geometry, properties, extend({}, options, {
            lineStringOverlay: ArrowOverlay
        }));
    };
    provide(Arrow);
});
	

/*
 * Класс, реализующий интерфейс IOverlay.
 * Получает на вход пиксельную геометрию линии и добавляет стрелку на конце линии.
 */
ymaps.modules.define("overlay.Arrow", [
    'overlay.Polygon',
    'util.extend',
    'event.Manager',
    'option.Manager',
    'Event',
    'geometry.pixel.Polygon'
], function (provide, PolygonOverlay, extend, EventManager, OptionManager, Event, PolygonGeometry) {
    var domEvents = [
            'click',
            'contextmenu',
            'dblclick',
            'mousedown',
            'mouseenter',
            'mouseleave',
            'mousemove',
            'mouseup',
            'multitouchend',
            'multitouchmove',
            'multitouchstart',
            'wheel'
        ],

        /**
         * @param {geometry.pixel.Polyline} pixelGeometry Пиксельная геометрия линии.
         * @param {Object} data Данные оверлея.
         * @param {Object} options Опции оверлея.
         */
        ArrowOverlay = function (pixelGeometry, data, options) {
            // Поля .events и .options обязательные для IOverlay.
            this.events = new EventManager();
            this.options = new OptionManager(options);
            this._map = null;
            this._data = data;
            this._geometry = pixelGeometry;
            this._overlay = null;
        };

    ArrowOverlay.prototype = extend(ArrowOverlay.prototype, {
        // Реализовываем все методы и события, которые требует интерфейс IOverlay.
        getData: function () {
            return this._data;
        },

        setData: function (data) {
            if (this._data != data) {
                var oldData = this._data;
                this._data = data;
                this.events.fire('datachange', {
                    oldData: oldData,
                    newData: data
                });
            }
        },

        getMap: function () {
            return this._map;
        },

        setMap: function (map) {
            if (this._map != map) {
                var oldMap = this._map;
                if (!map) {
                    this._onRemoveFromMap();
                }
                this._map = map;
                if (map) {
                    this._onAddToMap();
                }
                this.events.fire('mapchange', {
                    oldMap: oldMap,
                    newMap: map
                });
            }
        },

        setGeometry: function (geometry) {
            if (this._geometry != geometry) {
                var oldGeometry = geometry;
                this._geometry = geometry;
                if (this.getMap() && geometry) {
                    this._rebuild();
                }
                this.events.fire('geometrychange', {
                    oldGeometry: oldGeometry,
                    newGeometry: geometry
                });
            }
        },

        getGeometry: function () {
            return this._geometry;
        },

        getShape: function () {
            return null;
        },

        isEmpty: function () {
            return false;
        },

        _rebuild: function () {
            this._onRemoveFromMap();
            this._onAddToMap();
        },

        _onAddToMap: function () {
            // Военная хитрость - чтобы в прозрачной ломаной хорошо отрисовывались самопересечения,
            // мы рисуем вместо линии многоугольник.
            // Каждый контур многоугольника будет отвечать за часть линии.
            this._overlay = new PolygonOverlay(new PolygonGeometry(this._createArrowContours()));
            this._startOverlayListening();
            // Эта строчка свяжет два менеджера опций.
            // Опции, заданные в родительском менеджере,
            // будут распространяться и на дочерний.
            this._overlay.options.setParent(this.options);
            this._overlay.setMap(this.getMap());
        },

        _onRemoveFromMap: function () {
            this._overlay.setMap(null);
            this._overlay.options.setParent(null);
            this._stopOverlayListening();
        },

        _startOverlayListening: function () {
            this._overlay.events.add(domEvents, this._onDomEvent, this);
        },

        _stopOverlayListening: function () {
            this._overlay.events.remove(domEvents, this._onDomEvent, this);
        },

        _onDomEvent: function (e) {
            // Мы слушаем события от дочернего служебного оверлея
            // и прокидываем их на внешнем классе.
            // Это делается для того, чтобы в событии было корректно определено
            // поле target.
            this.events.fire(e.get('type'), new Event({
                target: this
            // Свяжем исходное событие с текущим, чтобы все поля данных дочернего события
            // были доступны в производном событии.
            }, e));
        },

        _createArrowContours: function () {
            var contours = [],
                mainLineCoordinates = this.getGeometry().getCoordinates(),
                arrowLength = calculateArrowLength(
                    mainLineCoordinates,
                    this.options.get('arrowMinLength', 3),
                    this.options.get('arrowMaxLength', 20)
                );
            contours.push(getContourFromLineCoordinates(mainLineCoordinates));
            // Будем рисовать стрелку только если длина линии не меньше длины стрелки.
            if (arrowLength > 0) {
                // Создадим еще 2 контура для стрелочек.
                var lastTwoCoordinates = [
                        mainLineCoordinates[mainLineCoordinates.length - 2],
                        mainLineCoordinates[mainLineCoordinates.length - 1]
                    ],
                // Для удобства расчетов повернем стрелку так, чтобы она была направлена вдоль оси y,
                // а потом развернем результаты обратно.
                    rotationAngle = getRotationAngle(lastTwoCoordinates[0], lastTwoCoordinates[1]),
                    rotatedCoordinates = rotate(lastTwoCoordinates, rotationAngle),

                    arrowAngle = this.options.get('arrowAngle', 20) / 180 * Math.PI,
                    arrowBeginningCoordinates = getArrowsBeginningCoordinates(
                        rotatedCoordinates,
                        arrowLength,
                        arrowAngle
                    ),
                    firstArrowCoordinates = rotate([
                        arrowBeginningCoordinates[0],
                        rotatedCoordinates[1]
                    ], -rotationAngle),
                    secondArrowCoordinates = rotate([
                        arrowBeginningCoordinates[1],
                        rotatedCoordinates[1]
                    ], -rotationAngle);

                contours.push(getContourFromLineCoordinates(firstArrowCoordinates));
                contours.push(getContourFromLineCoordinates(secondArrowCoordinates));
            }
            return contours;
        }
    });

    function getArrowsBeginningCoordinates (coordinates, arrowLength, arrowAngle) {
        var p1 = coordinates[0],
            p2 = coordinates[1],
            dx = arrowLength * Math.sin(arrowAngle),
            y = p2[1] - arrowLength * Math.cos(arrowAngle);
        return [[p1[0] - dx, y], [p1[0] + dx, y]];
    }

    function rotate (coordinates, angle) {
        var rotatedCoordinates = [];
        for (var i = 0, l = coordinates.length, x, y; i < l; i++) {
            x = coordinates[i][0];
            y = coordinates[i][1];
            rotatedCoordinates.push([
                x * Math.cos(angle) - y * Math.sin(angle),
                x * Math.sin(angle) + y * Math.cos(angle)
            ]);
        }
        return rotatedCoordinates;
    }

    function getRotationAngle (p1, p2) {
        return Math.PI / 2 - Math.atan2(p2[1] - p1[1], p2[0] - p1[0]);
    }

    function getContourFromLineCoordinates (coords) {
        var contour = coords.slice();
        for (var i = coords.length - 2; i > -1; i--) {
            contour.push(coords[i]);
        }
        return contour;
    }

    function calculateArrowLength (coords, minLength, maxLength) {
        var linePixelLength = 0;
        for (var i = 1, l = coords.length; i < l; i++) {
            linePixelLength += getVectorLength(
                coords[i][0] - coords[i - 1][0],
                coords[i][1] - coords[i - 1][1]
            );
            if (linePixelLength / 3 > maxLength) {
                return maxLength;
            }
        }
        var finalArrowLength = linePixelLength / 3;
        return finalArrowLength < minLength ? 0 : finalArrowLength;
    }

    function getVectorLength (x, y) {
        return Math.sqrt(x * x + y * y);
    }

    provide(ArrowOverlay);
}); 
}



// Управляющий элемент "Путеводитель по офисам", реализиует интерфейс YMaps.IControl
//https://tech.yandex.ru/maps/doc/jsapi/1.x/articles/tasks/overlays-docpage/
function OfficeNavigator (offices) {
	console.log('OfficeNavigator');
	// Добавление на карту
	this.onAddToMap = function (map, position) {
			this.container = ymaps.jQuery("<ul></ul>")
			this.map = map;
			this.position = position || new ymaps.ControlPosition(ymaps.ControlPosition.TOP_RIGHT, new ymaps.Size(10, 10));

			// Выставление необходимых CSS-свойств
			this.container.css({
				position: "absolute",
				zIndex: ymaps.ZIndex.CONTROL,
				background: '#fff',
				listStyle: 'none',
				padding: '10px',
				margin: 0
			});
			
			// Формирование списка офисов
	//		this._generateList();
			
			// Применение позиции к управляющему элементу
	//		this.position.apply(this.container);
			
			// Добавление на карту
	//		this.container.appendTo(this.map.getContainer());
	}

	// Удаление с карты
	this.onRemoveFromMap = function () {
		this.container.remove();
		this.container = this.map = null;
	};

	// Пока "летим" игнорируем клики по ссылкам
	this.isFlying = 0;

	// Формирование списка офисов
	this._generateList = function () {
		var _this = this;
		
		// Для каждого объекта вызываем функцию-обработчик
		//offices.forEach(function (obj) {
		/*
		for(var i=0; i<offices.length; i++){
			// Создание ссылки на объект
			//var li = ymaps.jQuery("<li><a href=\"#\">" + obj.name + "</a></li>"),
			var li = ymaps.jQuery("<li><a href=\"#\">" + offices[i].properties.get('hintContent') + "</a></li>"),
				a = li.find("a"); 
			
			// Создание обработчика щелчка по ссылке
			li.bind("click", function () {
				if (!_this.isFlying) {
					_this.isFlying = 1;
					//_this.map.panTo(obj.getGeoPoint(), {
					_this.map.panTo(offices[i].getGeoPoint(), {	
						flying: 1,
						callback: function () {
							obj.openBalloon();
							_this.isFlying = 0;
						}
					});
				}
				return false;
			});
			
			// Слушатели событий на открытие и закрытие балуна у объекта
			ymaps.Events.observe( offices[i],  offices[i].Events.BalloonOpen, function () {
				a.css("text-decoration", "none");
			});
			
			ymaps.Events.observe( offices[i],  offices[i].Events.BalloonClose, function () {
				a.css("text-decoration", "");
			});
			
			// Добавление ссылки на объект в общий список
			li.appendTo(_this.container);
		}//);
		*/
	};
}


//расчет крайних координат (для фрейма)
function CalcRegion1(mapObjects){
	//перебираем все участки и находим точки/границу зоны отображения
    ourLAT = 55.75;
    ourLON = 37.61;  
    console.log('------------------CalcRegion1-points-');
    console.log(mapObjects);
    
    var minLat = 200.0, maxLat = -200.0, minLong = 200.0, maxLong = -200.0;

    var points = [];
    
    //находим все координаты и в массив их
    for(var key in mapObjects){
        console.log(key)
       console.log(mapObjects[key])
        for(var key1 in mapObjects[key]){//для каждого полигона       
            if(key == 'Placemark'){
                points.push(mapObjects[key][key1]['Coordinates'])
            }else{

                    if(key == 'Polygon'){
                        for(var i=0; i<mapObjects[key][key1]['Coordinates'][0].length; i++){                       
                              points.push(mapObjects[key][key1]['Coordinates'][0][i])
                        }                    
                    }
                    if((key == 'Polyline') || (key == 'Arrow')){
                        for(var i=0; i<mapObjects[key][key1]['Coordinates'].length; i++){
                            console.log('Polyline - Arrow')
                            points.push(mapObjects[key][key1]['Coordinates'][i])
                        }
                    }                    
            }
        }     
    }
     console.log('points')
     console.log(points)
 

    if(points.length > 0)
	{	
		
       
        //['coords'] - named points
		for(var i=0; i<points.length; i++)
		{
					if(points[i][0] >  maxLat) 
						maxLat = points[i][0];
					if(points[i][0] <  minLat) 
						minLat = points[i][0];
					if(points[i][1] >  maxLong) 
						maxLong = points[i][1];
					if(points[i][1] <  minLong)
						minLong = points[i][1];
		}
       
		
	//	console.log('maxLat-'+maxLat+'-minLat-'+minLat+'-maxLong-'+maxLong+'-minLong-'+minLong);
		
	}
	
	if(maxLat == minLat)
	{
		ourLAT = maxLat;
		ourLON = maxLong;
		maxLat = maxLat + 0.05;
		minLat = minLat - 0.05;
		maxLong = maxLong + 0.05;
		minLong = minLong - 0.05;
	}
	else
	{
		ourLAT = 0.5 * (maxLat + minLat);//myPoints[0];
		ourLON = 0.5 * (maxLong + minLong);//myPoints[1];
	}
	northWest = [maxLat , minLong ];
	southEast = [minLat , maxLong ];
	northEast = [minLat, maxLong];
	southWest = [maxLat, minLong];
	pointMin = [minLat , minLong];
	pointMax = [maxLat , maxLong];

}