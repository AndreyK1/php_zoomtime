//alert('map')
	var myMap;
	var Polygons = [];  //массив полигонов
	var Polylines = [];  //массив линий
	var lastSavedPloyg = -1;
	var lastSavedPloyL = -1;
	var PoligKoord = [];
	var PoliLKoord = [];
	
	//var myPolygon;	

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
		addContextMenu(Polygons[Polygons.length-1],Polygons,Polygons.length-1)
		
		// Добавляем многоугольникИ на карту.
	//	myMap.geoObjects.add(myPolygon);
		myMap.geoObjects.add(Polygons[Polygons.length-1]);
}


function addContextMenu(obj,arr,elN){


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
				console.log('elN-'+elN)
		
			// Заполняем поля контекстного меню текущими значениями свойств метки.
				$('#menuYa input[name="icon_text"]').val(obj.properties.get('balloonContentHeader'));
				$('#menuYa input[name="hint_text"]').val(obj.properties.get('hintContent'));
				$('#menuYa input[name="balloon_text"]').val(obj.properties.get('balloonContentBody'));
				$('#menuYa input[name="color_text"]').val(obj.options.get('fillColor'));

				// При нажатии на кнопку "Сохранить" изменяем свойства метки
				// значениями, введенными в форме контекстного меню.
				$('#menuYa input[type="submit"]').click(function () {
					obj.properties.set({
						balloonContentHeader: $('input[name="icon_text"]').val(),
						hintContent: $('input[name="hint_text"]').val(),
						balloonContentBody: $('input[name="balloon_text"]').val()
					});
					
					obj.options.set({
						fillColor: $('input[name="color_text"]').val(),
						strokeColor: $('input[name="color_text"]').val(),

					});					
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
		addContextMenu(Polylines[Polylines.length-1],Polylines,Polylines.length-1)
		
		
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

//переписанная функция drawMapYandex
function drawMapYandex1()//(lat, lon, sk42)
{

		//CalcRegion1(pointsUnnamed.concat(pointsNamed));
		
	//	CoorUN = pointsUnnamed;
	//	CoorN = pointsNamed;
		document.getElementById('map-canvas').innerHTML = '111';
		ymaps.ready(initYa1);

	
}

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
				PoliLKoord.push(e.get('coords'));
				console.log(PoliLKoord)		
				GeoPolylineToMap(myMap,PoliLKoord,header,description,body,objColor,2);				
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