<div id="rulers_div" style="position:fixed; left:3000px;top:100px; background:#ccc; z-index:200;  ">
	<div id="rullers_text" class="rotatable" style="position:absolute; left:100px;top:70px;">
		<b  style="background:green; border-top-left-radius:7px; border-top-right-radius:7px; cursor:pointer;">&nbsp;&nbsp;Правители&nbsp;&nbsp;</b>
	</div>
	<div id="rullers_x"  style="position:absolute; left:-27px;top:-13px;">
		<b  style="background:grey; border-radius:7px; cursor:pointer;">&nbsp;X&nbsp;</b>
	</div>
	
	<div id="rulers_div_inn" style="overflow:auto; max-width:900px; background:blue; display:block; border-top-left-radius:10px;
		position:relative;
		border-bottom-left-radius:15px; 
		border-top:5px solid green;
		border-left:5px solid green;
		border-bottom:5px solid green;
		min-height: 100px;
		max-height: 600px;
		">
		<div id="rulers_div_inn_contenner" style=" position:relative;"></div>

		<!--	
		dsffffffffff fffffffffffff fffffffffffff fffffffffffff dsffffffffff fffffffffffff fffffffffffff fffffffffffffdsffffffffff fffffffffffff fffffffffffff fffffffffffffdsffffffffff fffffffffffff fffffffffffff fffffffffffff
		dsffffffffff fffffffffffff fffffffffffff fffffffffffff dsffffffffff fffffffffffff fffffffffffff fffffffffffffdsffffffffff fffffffffffff fffffffffffff fffffffffffffdsffffffffff fffffffffffff fffffffffffff fffffffffffff
		dsffffffffff fffffffffffff fffffffffffff fffffffffffff dsffffffffff fffffffffffff fffffffffffff fffffffffffffdsffffffffff fffffffffffff fffffffffffff fffffffffffffdsffffffffff fffffffffffff fffffffffffff fffffffffffff 000000000  -->


		
	<!--		<svg id="svg_rulers" version="1.1" width="100" height="300" 
						
						xmlns="http://www.w3.org/2000/svg" 
						xmlns:xlink="http://www.w3.org/1999/xlink" 
						xmlns:ev="http://www.w3.org/2001/xml-events">
-->
						<!--trgtgrtgrtggggggggggg55555555555555555
								  <text x="10" y="50" font-size="30">My SVG</text>
								  <rect id="rec_1" x="30" y="50" width="120" height="50" style="fill-opacity: 0.7;  ";></rect>
								  
	<image x="20" y="120" width="80" height="80"
	     xlink:href="RulerPicture/png-0-0-background.png" />


								  <img src="RulerPicture/png-0-0-background.png" id="MapPictureDraw" />
								<rect x="30" y="140" width="120" height="50" style="fill:yellow; stroke-width:3; stroke: blue;"></rect>
								<rect height="100" style="fill: blue;" x="20" y="30" width="100"></rect><line x1="70" y1="40" x2="70" y2="540" fill="green" stroke="#006600"></line><line x1="120" y1="40" x2="120" y2="540" fill="green" stroke="#006600"></line>-->
		<!--	</svg>-->
		
	</div>
</div>



<script type="text/javascript">
	var sost = "show";
	//$('#rullers_x').hide();

	var fotoWidth = 100;
	var fotoHeight = 100;
	var rulerWindWidth = 900;

	

	$(document).ready(function() {
		//$('#rulers_div').css('left',$('body').width()-$('#rulers_div').width()/2-$('#rulers_div .rotatable').height()/2)
		$('#rulers_div').css('left',$('body').width())

		$('#rullers_text').css('left',-$('#rullers_text').width()/2-$('#rullers_text').height()/2+2)
		$('#rullers_text').css('top',45)

		//$('#rulers_div_inn').hide();

		$('#rullers_text,#rullers_x').click(function(){
		 	if(sost=='hide'){
				$('#rulers_div').animate({left: $('body').width()-rulerWindWidth}, 1000);
				$('#rullers_x').show(1000);
				sost='show';
		 	}else{
				$('#rulers_div').animate({left: $('body').width()}, 1000);
				sost='hide';
				$('#rullers_x').hide(1000);

		 	}
		 });
	});
</script>


<script type="text/javascript">
	//прорисовка правителей
var svgRul = document.getElementById("svg_rulers");//.getSVGDocument();

	function textP(x,y,fs,text,color,decor){
	 var NS="http://www.w3.org/2000/svg";
	 var SVGObj= document.createElementNS(NS,"text");
		SVGObj.setAttribute('x',x);
		SVGObj.setAttribute('y',y);
		SVGObj.setAttribute('font-size',fs);
		if(decor){
			SVGObj.setAttribute('style','text-decoration:underline;');
		}
		
		//SVGObj.setAttribute('cursor','pointer');
		SVGObj.textContent = text;
		SVGObj.setAttribute('fill',color);
		//SVGObj.setAttribute('title','fdsfds');
		//SVGObj.setAttribute('stroke',fill);
	 return SVGObj;
	}

	//добавление прямойгольников
	var rectP=function(x,y,h,w,fill){
	 var NS="http://www.w3.org/2000/svg";
	 var SVGObj= document.createElementNS(NS,"rect");
	 SVGObj.x.baseVal.value=x;
		SVGObj.y.baseVal.value=y;
	 SVGObj.width.baseVal.value=w;
	 SVGObj.height.baseVal.value=h;
	 SVGObj.setAttribute("height",h);
	// SVGObj.setAttribute('cursor','pointer');
	 SVGObj.style.fill=fill;
	 return SVGObj;
	}

	function lineP(x1,y1,x2,y2,fill){
	 var NS="http://www.w3.org/2000/svg";
	 var SVGObj= document.createElementNS(NS,"line");
		SVGObj.setAttribute('x1',x1);
		SVGObj.setAttribute('y1',y1);
		SVGObj.setAttribute('x2',x2);
		SVGObj.setAttribute('y2',y2);
		SVGObj.setAttribute('fill',fill);
		SVGObj.setAttribute('stroke',fill);
		SVGObj.setAttribute('stroke-width',3);
	 return SVGObj;
	}


	//создание круга
	function circleP(cx,cy,fill){
	 	var NS="http://www.w3.org/2000/svg";
	 	var SVGObj= document.createElementNS(NS,"circle");
		SVGObj.setAttribute('cx',cx);
		SVGObj.setAttribute('cy',cy);
		SVGObj.setAttribute('r',50);
		SVGObj.setAttribute('fill',fill);
		SVGObj.setAttribute('stroke','#006600');
	 	return SVGObj;
	}

	//создание картинки
	function imageP(cx,cy,id_f,path){
	 	var NS="http://www.w3.org/2000/svg";
	 	var SVGObj= document.createElementNS(NS,"image");
		SVGObj.setAttribute('x',cx);
		SVGObj.setAttribute('y',cy);
		//SVGObj.setAttribute('r',50);
		SVGObj.setAttribute('width',fotoWidth);
		SVGObj.setAttribute('height',fotoHeight);
	
		//SVGObj.setAttribute('href',"RulerPicture/png-0-0-background.png");
		SVGObj.setAttributeNS("http://www.w3.org/1999/xlink", "href", "RulerPicture/"+path);
		SVGObj.setAttribute('style', 'clip-path:url(#'+id_f+');');	
		//SVGObj.setAttribute('stroke','#006600');
	 	return SVGObj;
	}

	 //создание clippath для обрезки картинки по кругу 
	function defineclippath(PointNX,PointNY,id_f){
		var defs = document.createElementNS("http://www.w3.org/2000/svg", 'defs');
		var clippath = document.createElementNS("http://www.w3.org/2000/svg", 'clipPath');
		 clippath.setAttribute('id',id_f);
		 //clippath.appendChild(newShape());
		clippath.appendChild(circleP(PointNX+50,PointNY+50,'red'));
		 defs.appendChild(clippath);
		 return defs;
	}	


	var begPoint={x:50, y:50};

	function makeFoto(svgR,PointNX,PointNY,id_f,path){
		console.log('makeFoto - makeFoto -makeFoto');
		//console.log(PointN);		
		svgR.appendChild(defineclippath(PointNX,PointNY,id_f));
		//png-0-0-background.png"
		svgR.appendChild(imageP(PointNX,PointNY,id_f,path));		
	}






	var begPoint={x:50, y:50};
	var id_f = 'clippath';

	//makeFoto(begPoint,id_f);


		var arrRulers = 
		[
			11,'первый правитель','император1','07.2008-09.2011','png-0-0-background.png',
			[
				[
						15,'1 аследник первыого правителя','император1-1','07.2008-09.2011','jpg-0-0-background1.jpg',
						[
								[17,'наследник второго правителя','император1-1-1','07.2008-09.2011','png-0-0-background.png',[]],
								[26,'2 наследник второго правителя','император1-1-2','07.2008-09.2011','',[]],
								[27,'2 наследник второго правителя','император1-1-3','07.2008-09.2011','',[]]
						]
				],
				[
						21,'2 наследник первыого правителя','император1-2','07.2008-09.2011','jpg-0-0-background1.jpg',
						[
								[19,'наследник 2 наследник второго правителя','император1-2-1','07.2008-09.2011','png-0-0-background.png',[]],
								[29,'наследник 2 наследник второго правителя','император1-2-2','07.2008-09.2011','',[]]
						]
				]
			]
		];	

/*	var arrRulers = 
		[
			11,'первый правитель','император1','07.2008-09.2011','png-0-0-background.png',
			[
					[
						15,'1 аследник первыого правителя','император1','07.2008-09.2011','jpg-0-0-background1.jpg',
						[
								[17,'наследник второго правителя','император1','07.2008-09.2011','png-0-0-background.png',[]]
						]
					]
			]
		]	*/

	var curPoint={x:40, y:-70};
	var maxXPos = 20;
	var LastestRul = [];
	var LastestRulOb = [];
	var FirstRulOb =[];
	
	function clonePoint(point){
		return newPoint = {
			x:point.x,
			y:point.y
		}
	}

	//отрисовка правителей/знати по рекурсии
	function rekursDrowRuler(svgR,arr,curPoint_i){
		console.log('rekursDrowRuler - rekursDrowRuler -rekursDrowRuler');
		console.log(arr);
		

		/*curPoint.x = curPoint.x+70;
		curPoint.y = curPoint.y+70;*/

		var curPoint_in = clonePoint(curPoint_i)
		curPoint_in.y = curPoint_in.y+fotoHeight + 80;
		for(var i=0; i<arr.length; i++){


			//определяем насколько сдвинуть, в зависимости от того сколько наследников у предыдушего
			var x = 1; 
			if(arr[i-1]){
				if(arr[i-1][5].length >1){
					x= arr[i-1][5].length;
				}
			}
			/*if(arr[i+1]){
					console.log(arr[i+1][3]);
			}*/
			if(i!=0){curPoint_in.x = curPoint_in.x+x*250;}
			
			if(curPoint_in.x>maxXPos){maxXPos =parseInt(curPoint_in.x); }
			if(!svgR.getAttribute('maxXPos')){
				svgR.setAttribute('maxXPos', curPoint_in.x);
			}else if(curPoint_in.x>parseInt(svgR.getAttribute('maxXPos'))){
				svgR.setAttribute('maxXPos', curPoint_in.x);
			}

			if(!svgR.getAttribute('maxYPos')){
				svgR.setAttribute('maxYPos', curPoint_in.y);
			}else if(curPoint_in.y>parseInt(svgR.getAttribute('maxYPos'))){
				svgR.setAttribute('maxYPos', curPoint_in.y);
			}

			console.log(svgR.getAttribute('maxXPos'));
			console.log(svgR.getAttribute('maxYPos'));
				//svgR.setAttribute('width', '1600'))

			console.log('rekursDrowRuler i -'+i);
				console.log(arr[i])
			
			//инфа о личности
			svgR.appendChild(textP(curPoint_in.x-30,curPoint_in.y+10,'18',arr[i][1],'white'));
			svgR.appendChild(textP(curPoint_in.x-25,curPoint_in.y+23,'13',arr[i][2],'white'));
			svgR.appendChild(textP(curPoint_in.x,curPoint_in.y+33+fotoHeight,'11',arr[i][3],'#ccc'));

			//ссылка на показ подданых
			svgR.appendChild(rectP(curPoint_in.x-36,curPoint_in.y+30,17,60,'green'));
			var txtObj = textP(curPoint_in.x-33,curPoint_in.y+40,'14','подданые','red','underline');
			txtObj.setAttribute('cursor','pointer');
			(function(id,svg_id){ $(txtObj).click(function(e){alert("-ооо-"+id+svg_id); createNewSvg(e,id,this,svg_id,'poddan','midle',0); });})(arr[i][0],svgR.id);
			svgR.appendChild(txtObj);
			//ссылка на родственные связи
			svgR.appendChild(rectP(curPoint_in.x+83,curPoint_in.y+30,17,50,'green'));
			var txtObj = textP(curPoint_in.x+88,curPoint_in.y+40,'14','родня','red','underline');
			txtObj.setAttribute('cursor','pointer');
			(function(id,svg_id){ $(txtObj).click(function(e){alert("-ррррр-"+id+svg_id); createNewSvg(e,id,this,svg_id,'relatives','last',0); });})(arr[i][0],svgR.id);
			svgR.appendChild(txtObj);
			//makeFoto(curPoint,'clipPath'+arr[0]);
			var img = arr[i][4]; if(img==''){img='nofoto.jpg'}
			//var curPoint_in1 = clonePoint(curPoint_in)
			//curPoint_in1.y = curPoint_in.y+30;	
			makeFoto(svgR,curPoint_in.x,curPoint_in.y+25,'clippath-'+arr[i][0],img);

			//рисуем соединительные стрелки
			//function lineP(x1,y1,x2,y2,fill){
			svgR.appendChild(lineP(curPoint_i.x+fotoWidth/2,curPoint_i.y+fotoHeight+40,curPoint_in.x+fotoWidth/2,curPoint_in.y,'red'));
				
			//console.log(arr[3])
			if(arr[i][5] && arr[i][5].length >0){  //
				rekursDrowRuler(svgR,arr[i][5],curPoint_in);
			}else{
				arr[i].push(curPoint_in.x+'-'+curPoint_in.y);
				LastestRul.push(arr[i]);

				var arr3 =  LastestRulOb.pop();
				arr3.push(arr[i]);
				LastestRulOb.push(arr3);
			}


		}

	}

	function changeSvgSize(svgR){
		$(svgR).width(parseInt(svgR.getAttribute('maxXPos'))+400)
		$(svgR).height(parseInt(svgR.getAttribute('maxYPos'))+400)
	}
	



	function DrowLastRuler(svgR){
		//начальная ссылка
		var X = curPoint.x;
		var Y = curPoint.y+fotoHeight+20;
					console.log('curPoint 00000000000000000000000000000 curPoint arr[i+1][3].length------------')
			console.log(curPoint)
			svgR.appendChild(rectP(X+10,Y,20,90,'green'));
			var txtObj = textP(X+18,Y+10,'16','прогрузить','red','underline');
			txtObj.setAttribute('cursor','pointer');
			console.log('FirstRulOb FirstRulOb FirstRulOb FirstRulOb');
			console.log(FirstRulOb);
			(function(id,svg_id){ $(txtObj).click(function(e){alert("--"+id+svg_id); createNewSvg(e,id,this,svg_id,svgR.getAttribute('who'),'first',0); });})(FirstRulOb.pop()[0],svgR.id);
			svgR.appendChild(txtObj);
		

		var LastestRul = LastestRulOb.pop();

		//конечные ссылки
		for(var i=0; i<LastestRul.length; i++){
			
			var lastX = parseInt(LastestRul[i][6].split("-")[0]);
			var lastY = parseInt(LastestRul[i][6].split("-")[1]);
			console.log('lastX-'+lastX+' lastY-'+lastY)
			svgR.appendChild(lineP(lastX+fotoWidth/2,lastY+fotoHeight+40,lastX+fotoWidth/2,lastY+fotoHeight+80,'red'));
			//svgR.appendChild(lineP(100,100,1000,1000,'green'));
			svgR.appendChild(rectP(lastX+10,lastY+fotoHeight+80,20,90,'green'));
			var txtObj = textP(lastX+18,lastY+fotoHeight+90,'16','прогрузить','red','underline');
			txtObj.setAttribute('cursor','pointer');
			(function(id,svg_id){$(txtObj).click(function(e){ alert("--"+id+svg_id);  createNewSvg(e,id,this,svg_id,svgR.getAttribute('who'),'last',0); });})(LastestRul[i][0],svgR.id)
			svgR.appendChild(txtObj);
			
//rect=function(x,y,h,w,fill){
		}

		changeSvgSize(svgR);
	}

	var nextId=0;//общий номер увеличения id svg

	function createNewSvg(e,id,obj,svg_id,who,where,id_country){
		//who - какие свзяи ищем (rull - правителей, relatives - родственные, poddan - подданых)
		//where - где находится тот с кого искать (first,midle,last)
		

			alert('id -'+id+' svg_id-'+svg_id+' who-'+who+' where-'+where)
		//alert(goal);
		var opt = document.createElement('div');
		//opt.setAttribute('style', 'background:blue;');
		var di = document.createElement('div');
		di.setAttribute('style', 'position:relative;');
		
		var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
		svg.setAttribute('style', 'border: 3px solid green');
		svg.setAttribute('width', '1100');
		svg.setAttribute('who', who);
		if(!svg_id){
			//страны (в цикле)
				var arrCountrys = ['Россия','Великобритания','США','Cаудовская Аравия'];
				//var x = 230;
				var y = 7;
				console.log('arrCountrys arrCountrys arrCountrys arrCountrys')
				//svg.appendChild(textP(20,20,'23',whoText,'white'));
				for(var i=0; i<arrCountrys.length;i++){
					//console.log(i+'-'+x);
					var p = 10;
					//if(i){var leng = arrCountrys[i-1].length;}else{leng =}
					//x = x+30+arrCountrys[i].length*p;
					
					svg.appendChild(rectP(220,y,17,+arrCountrys[i].length*p+15,'green'));
					var txtObj = textP(220+5,y+13,'20',arrCountrys[i],'red','underline');
					txtObj.setAttribute('cursor','pointer');
					(function(id,svg_id){ $(txtObj).click(function(e){alert("-&&&&&&&&&&&&&&&-"+id+svg_id); createNewSvg(e,id,this,svg_id,'rull','midle',0); });})(0,'svg_rulers');
					svg.appendChild(txtObj);
					y= y+25
					//x = x+arrCountrys[i].length*p+50;
				}


			svg.setAttribute('id', 'svg_rulers');
			opt.setAttribute('style', 'background:blue;');
		}else{
			nextId++;
			svg.setAttribute('id', svg_id+nextId);
			opt.setAttribute('style', 'background:blue; opacity: 0.92');
		}
		svg.setAttribute('height', '700');
		//svg.setAttribute('color', 'green');
		svg.setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:xlink", "http://www.w3.org/1999/xlink");
		opt.appendChild(di);
		opt.appendChild(svg);

	//	$(svg).append(textP(15,15,'26','ddddddddddd','red'));

		var whoText = '';
		if(who=='rull'){
			whoText = 'Главы государства';
		}else if(who=='relatives'){
			whoText = 'Родственные связи';
		}else if(who=='poddan'){
			whoText = 'Подданые';
		}
		svg.appendChild(textP(20,20,'23',whoText,'white'));
	
		//вытаскиваем массив правителей/знати
		var arrRulers = getRullersHttp(id,who,where,id_country);

		var arr = [];
		LastestRulOb.push(arr);
		FirstRulOb.push(arrRulers)
		rekursDrowRuler(svg,[arrRulers],curPoint);
		DrowLastRuler(svg);

		$(opt).css('color','red');
			//opt.innerHTML = "bbbbbbbbbbfrrrrrrrrrrrr-"+id+'-'+obj.getBBox().x+'-'+obj.getBBox().y;
			
			if(obj){
				$(opt).css('position','absolute');
				$(opt).css('top',obj.getBBox().y);
				$(opt).css('left',obj.getBBox().x);
			}
			

			if(!svg_id){
				$('#rulers_div_inn').append(opt)				
			}else{
				$('#'+svg_id).prev().append(opt)				
			}

			changeSizeOfRulersMap();
	}


	//вытаскиваем массив правителей/знати
	function getRullersHttp(id_rul,who,where,id_country){
		//who - какие свзяи ищем (rull - правителей, relatives - родственные, poddan - подданых)
		//where - где находится тот с кого искать (first,midle,last)
		//id_country = 2;
		period = "10-10-1991 10-10-2003";

		var arr;
		$.ajax({
			async: false, 
			url: 'blocks/dinamic_scripts/RulersMap_Service.php',
		 	data: {id_rul:id_rul,who:who,where:where,id_country:id_country,period:period},
		  	type: "POST",
		  	success: function(data) {  arr = data; alert(arr);}
		  	,dataType: 'json'
		 })
		console.log("777777777777777777777777777777777777777777777");
		console.log(arr);

		if(!arr[0]){
			alert("связей не найдено!");
		}
		return arr[0];
		//return arrRulers;
	};

/*
	var arr = [];
	LastestRulOb.push(arr);
	FirstRulOb.push(arrRulers)
	rekursDrowRuler(svgRul,[arrRulers],curPoint);
	DrowLastRuler(svgRul);*/

	createNewSvg(null,0,null,null,'rull','midle',2)
		//who - какие свзяи ищем (rull - правителей, relatives - родственные, poddan - подданых)
		//where - где находится тот с кого искать (first,midle,last)

	
	function changeSizeOfRulersMap(){
		
		rulerWindWidth = parseInt(maxXPos) +200;
		//if(rulerWindWidth > 900){ rulerWindWidth= 900; 	}
		//alert('rulerWindWidth-'+rulerWindWidth)
		if(rulerWindWidth >  ($('body').width() - 300)){ rulerWindWidth= $('body').width() - 300; 	}
		if(rulerWindWidth < 380){rulerWindWidth = 380;}

		//alert('rulerWindWidth-'+rulerWindWidth)
	/*	$('#svg_rulers').width(rulerWindWidth+400)
		$('#svg_rulers').height(1700)*/
		//changeSvgSize(svgRul);
		
		//setTimeout(
		$(document).ready(function() {
			setTimeout(function(){	$('#rulers_div').animate({left: $('body').width()-rulerWindWidth}, 1000);
				var svg = document.getElementById('svg_rulers');
				//alert(svg.getAttribute('width'))
				//svg.setAttribute('width', rulerWindWidth+700);
				//alert('getAttribute-'+parseInt($(svg).css('width'))+" rulerWindWidth+300-"+(parseInt(rulerWindWidth)+300))
				if(parseInt($(svg).css('width')) < (parseInt(rulerWindWidth)+300)){
					$(svg).width((parseInt(rulerWindWidth)+300));
				}
				
				//alert(svg.getAttribute('width'))
			},300);


		});		
	}

	//changeSizeOfRulersMap();

	console.log('rekursDrowRulerENDDDDDD');
		console.log(curPoint);

	console.log('LastestRulENDDDDDD');
		console.log(LastestRul);

//createNewSvg()

</script>