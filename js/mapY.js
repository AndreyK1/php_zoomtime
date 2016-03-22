//alert('map')
	var myMap;
	var Polygons = [];  //массив полигонов
	var Polylines = [];  //массив линий
	var Arrows = [];  //массив стрелок
	var Placemarks = [];  //массив стрелок
	var lastSavedPloyg = -1;
	var lastSavedPloyL = -1;
	var lastSaveArrow = -1;
	var lastSavedPlacem = -1;
	var PoligKoord = [];
	var PoliLKoord = [];
	var ArrowKoord = [];
	var PlacemarkKoord = [];


	
	//var myPolygon;	

	
function GeoArrowToMap(myMap,pathCoords,header,description,body,color,Weight){//отдаем яндексу контуры
	if( Arrows[Arrows.length-1] && ((Arrows.length-1)!=lastSaveArrow)){ console.log(Arrows[Arrows.length-1]); myMap.geoObjects.remove(Arrows[Arrows.length-1]);  Arrows.pop();} 
	//https://tech.yandex.ru/maps/jsbox/2.1/arrow
	ymaps.modules.require(['geoObject.Arrow'], function (Arrow) {
						//var arrow = new Arrow([[57.733835, 38.788227], [55.833835, 35.688227]], null, {
						var arrow = new Arrow(pathCoords, 
						{
							 // Содержимое балуна.
								balloonContentHeader: header,
								balloonContentBody : body,
								// Содержимое хинта.
								hintContent: header+" "+description
						 }
						,{						
							fillColor: color,//'#00FF00',
							// // Цвет обводки.
							 strokeColor: color,//'#00FF00',
							
							geodesic: true,
							strokeWidth: 2*Weight,
							//opacity: 0.5,
							fillOpacity :0.1,
							strokeOpacity: 0.4,
							strokeStyle: 'shortdash'
						});
						Arrows.push(arrow
						);
						
						//myMap.geoObjects.add(Arrows[Arrows.length-1]);


			
			//редактирование
			Arrows[Arrows.length-1].events.add('click', function (e) {
				//console.log(e.get('domEvent').originalEvent)
				
				//инфа
				console.log('Arrows - '+(Arrows.length-1)+'  click ')
				console.log(Arrows);
				for(var i=0; i<Arrows.length; i++){
					console.log( Arrows[i].geometry.getCoordinates())
				}
				
				if(e.get('domEvent').originalEvent.ctrlKey){//если нажата клавиша .ctrl то редактируем полигон
					e.get('target').editor.startEditing();
				}else{
					e.get('target').editor.stopEditing();
				}
				
			});
			
			//контекстное меню
			addContextMenu(Arrows[Arrows.length-1],Arrows,"Arrow")
			
			// Добавляем многоугольникИ на карту.
		//	myMap.geoObjects.add(myPolygon);
			myMap.geoObjects.add(Arrows[Arrows.length-1]);
	});
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
				hintContent: header+" "+description
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
         strokeWidth: 2*Weight,//,
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
			}else{
				e.get('target').editor.stopEditing();
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
				hintContent: header+" "+description
         
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
			}else{
				e.get('target').editor.stopEditing();
			}
			
		});
		
		//контекстное меню
		addContextMenu(Placemarks[Placemarks.length-1],Placemarks,"Placemark")
		
		// Добавляем многоугольникИ на карту.
	//	myMap.geoObjects.add(myPolygon);
		myMap.geoObjects.add(Placemarks[Placemarks.length-1]);
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
                    <ul id="menu_list">\
                        <li>Название: <br /> <input type="text" name="icon_text" /></li>\
                        <li>Подсказка: <br /> <input type="text" name="hint_text" /></li>\
                        <li>Балун: <br /> <input type="text" name="balloon_text" /></li>\
						<li>Цвет: <br /> <input type="text" name="color_text" /></li>\
                    </ul>\
                <div align="center"><input type="submit" value="Сохранить" /></div>\
				<div align="center"><button type="button" value="Удалить объект">Удалить объект</button></div>\
                </div>';

            // Размещаем контекстное меню на странице
            $('body').append(menuContent);
			
			// Задаем позицию меню.
            $('#menuYa').css({
                left: e.get('position')[0],
                top: e.get('position')[1]
            });
			console.log('menuYa position - '+e.get('position')[0] +" - "+e.get('position')[1]);
			//	console.log('elN-'+elN)
		
			// Заполняем поля контекстного меню текущими значениями свойств метки.
				$('#menuYa input[name="icon_text"]').val(obj.properties.get('balloonContentHeader'));
				$('#menuYa input[name="hint_text"]').val(obj.properties.get('hintContent'));
				$('#menuYa input[name="balloon_text"]').val(obj.properties.get('balloonContentBody'));
				if(objN != "Placemark"){
					$('#menuYa input[name="color_text"]').val(obj.options.get('fillColor'));
				}else{
					$('#menuYa input[name="color_text"]').val(obj.options.get('iconColor'));
				}
				// При нажатии на кнопку "Сохранить" изменяем свойства метки
				// значениями, введенными в форме контекстного меню.
				$('#menuYa input[type="submit"]').click(function () {
					obj.properties.set({
						balloonContentHeader: $('input[name="icon_text"]').val(),
						hintContent: $('input[name="hint_text"]').val(),
						balloonContentBody: $('input[name="balloon_text"]').val()
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
					hintContent: header+" "+description
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
			 strokeWidth: 2*Weight,//,
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
			}
			
		});
		
		
		//контекстное меню
		addContextMenu(Polylines[Polylines.length-1],Polylines,"Polyline")
		
		
		// Добавляем многоугольникИ на карту.
	//	myMap.geoObjects.add(myPolygon);
		myMap.geoObjects.add(Polylines[Polylines.length-1]);
}


function ShowMap(){
	//if(Coords){
		drawMapYandex1();
		//drawMapYandex1(Coords[0],Coords[1]);
		//$('html,body').animate({ scrollTop: $('#map-canvas').offset().top }, { duration: 'slow', easing: 'swing'});
		//alert('ShowYa1')
	//}
}

/*
//переписанная функция drawMapYandex
function drawMapYandex1()//(lat, lon, sk42)
{

		//CalcRegion1(pointsUnnamed.concat(pointsNamed));
		
	//	CoorUN = pointsUnnamed;
	//	CoorN = pointsNamed;
		document.getElementById('map-canvas').innerHTML = '111';
		ymaps.ready(initYa1);

	
}
*/
//переписанная функция initYa
function initYa1() 
{
	ourLAT = 55.75;
	ourLON = 37.61;
	
	

	
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
	

	
	//событие при шелчке на карте
	myMap.events.add('click', function (e) {
		//alert('Событие на карте'+e.get('coords')); // Возникнет при щелчке на карте, но не на маркере.
		console.log('bbbbbb1');
		console.log(e);
		//доступ к событию дом
		console.log(e.get('domEvent').originalEvent)
		console.log(e.get('domEvent').originalEvent.ctrlKey)
		
		//цвет
		var objColor = '#00FF00'
		objColor = document.getElementById('objColor').value;
		console.log('objColor - '+objColor);
		
		//форма 
		var objShape = 'Polygon'
		objShape = document.getElementById('objShape').value;
		console.log('objShape - '+objShape);
		
		var header = document.getElementById('mapObjHeader').value;
		var body = document.getElementById('mapObjBody').value;
		var description = document.getElementById('mapObjDescription').value;
		
		if(e.get('domEvent').originalEvent.ctrlKey){//если нажата клавиша .ctrl то рисуем полигон

			if(objShape == 'Polygon'){
				PoligKoord.push(e.get('coords'));
				console.log(PoligKoord)
				//GeoPolygonToMap(myMap,PoligKoord,lic_name,PART_name,stat_otv,objColor,Weight);	
				//if(PoligKoord.length>1){
					//if(myPolygon){myMap.removeOverlay(myPolygon);}
					//myPolygon = null;
					GeoPolygonToMap(myMap,[PoligKoord],header,description,body,objColor,2);
				//}				
			}
			if(objShape == 'Polyline'){
				//if(PoliLKoord.length>1){ PoliLKoord.pop();}
				PoliLKoord.push(e.get('coords'));
				
				console.log(PoliLKoord)		
				GeoPolylineToMap(myMap,PoliLKoord,header,description,body,objColor,2);				
			}			
			if(objShape == 'Arrow'){
				ArrowKoord.push(e.get('coords'));
				console.log(ArrowKoord)		
				GeoArrowToMap(myMap,ArrowKoord,header,description,body,objColor,2);			
			}
			if(objShape == 'Placemark'){
				//PlacemarkKoord.push(e.get('coords'));
				PlacemarkKoord = e.get('coords');
				console.log(PlacemarkKoord)		
				GeoPlacemarkToMap(myMap,PlacemarkKoord,header,description,body,objColor,2);			
			}		
		
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
				console.log('Placemarks');
				console.log(Placemarks);
				
				/*for(var)
var group = new YMaps.GeoObjectCollection();
            group.add();	*/			

				// Создание управляющего элемента "Путеводитель по офисам"
				//myMap.addControl(new OfficeNavigator(Placemarks));
			//	myMap.controls.add(new ymaps.control.ZoomControl());
				//OfficeNavigator();
				//myMap.addControl(new ymaps.TypeControl()) ;
				//myMap.controls.add(new OfficeNavigator(Placemarks));
				myMap.panTo(/*[
					[ 55.751574, 37.573856 ],
					[ 43.134091, 131.928478 ]
				]*/  Placemarks[0].geometry.getCoordinates() ).then(function () {
					//alert('Прилетели!');
					console.log('fghfghfghfgh');
					Placemarks[0].balloon.open();
					//Placemarks[0].Events.BalloonOpen
				}, function (err) {
				 alert('Произошла ошибка ' + err);
				}, this);
				/*, {
					flying: 1,
					callback: function () {
						myMap.openBalloon(map.getCenter(), "Hello");
						//Placemarks[0].Events.BalloonOpen
												//.geometry.getCoordinates();
						console.log('fghfghfghfgh');
						
					}
				});*/
				
				
			}			
		}
		
	});
	

	
	prepareYa(myMap);
	
		myMap.setType('yandex#hybrid', {
 		checkZoomRange: true
		}).then(function () {
		 // Тип карты был установен с допустимым уровнем масштабирования.
		}, this);
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











//переписанная функция drawMapYandex
function drawMapYandex1()//(lat, lon, sk42)
{

		//CalcRegion1(pointsUnnamed.concat(pointsNamed));
		
	//	CoorUN = pointsUnnamed;
	//	CoorN = pointsNamed;
		document.getElementById('map-canvas').innerHTML = '111';
		ymaps.ready(initYa1);

	ArrowInitialization();
		
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