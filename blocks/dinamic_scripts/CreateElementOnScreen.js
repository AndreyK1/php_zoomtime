
function mousePageXY(e) //����������� ��������� ����
	{ var x = 0, y = 0;
		//alert("fghfgh121"+e);
		var coord=new Array();
		//if(typeof e == "undefined") e = window.event;
		  if (!e) e = window.event;
		  if (e.pageX || e.pageY)
		  { x = e.pageX;
			y = e.pageY;
		  }
		  else if (e.clientX || e.clientY)
		  { x = e.clientX + (document.documentElement.scrollLeft ||
		document.body.scrollLeft) - document.documentElement.clientLeft;
			y = e.clientY + (document.documentElement.scrollTop ||
		document.body.scrollTop) - document.documentElement.clientTop;
		  }
			
		coord[0]=x; coord[1]=y;	  
		  
		  //alert(coord);  
		  return coord;
}
		
		
		//�������� ���� ���� �� ������ (����� ���� � ��������������)
		function CreateElementOnScreen(width,event,options){//�������� �������� �� ������������ ����������� ��� ������ �� ������
			if(typeof options  == 'undefined'){ //������ ��������� ����������
				var options = new Array(); //���� �� �������� ������
			}			
			
			
			var el = document.createElement('div');  //������� ��� ��� �����
			el.innerHTML = "<span onclick='deleteList(this)' style='cursor:pointer; float:right; margin: -10px -5px 0 0;' > <span style='color:black;'>_</span><b>x</b><span style='color:black;'>_ </span></span><br />";  //���������� ����� ������
			el.style['padding'] = '10px';
			if(width !=''){
				el.style['width'] = width+'px';	
			}
			if(typeof options.maxHeight  != 'undefined'){ //������������ ������ �����
				el.style.maxHeight = options.maxHeight+'px';
			}
				//el.style['max-height'] = 120+'px';	
			
			el.style.overflow = "auto"; //overflow:auto;
				//el.style['overflow-y'] = "auto"; //overflow:auto;
			//}

			el.style['background'] = 'url(img/fon.png)';
//el.style['background'] = 'black';
			el.style['border'] = '1px solid white';
			el.style['color'] = 'white';
			
			if(typeof options.fixed  == 'undefined'){ 
				el.style.position = 'absolute';
			}else{
			//alert('here');
				el.style.position = 'fixed';//���� � ��� �������������, �� ������� � ���� 
				if(typeof options.left  == 'undefined'){ //���� � ��� ������ ��������, �� ������� � ���� 
					el.style['left'] = 20+"px";
					el.style['top'] = 20+"px";
				}else{
					el.style['left'] = options.left+"px";
					el.style['top'] = options.top+"px";
				}
			}	

			if(typeof options.IndexZ  == 'undefined'){ //���� � ��� �� ����� ���� , �� ������� � ���� 
				el.style.zIndex = '150';
			}else{
				el.style.zIndex = options.IndexZ;
			}	
			
			if(event !=''){
			//alert(event+"pp");
				var coord = mousePageXY(event);	//���������� ����
				//alert(coord['0']+"px")
				el.style['left'] = coord['0']+"px";
				el.style['top'] = coord['1']+"px";
			}
			el.maked = true;
			el.className = 'MakedBlocks';
			return el;
	}
	

	
	//��������� ������ ���� ���������� ���������
	var opt = new Array(); //����� ���� ��� ���� ��������
	b =  screen.width/2
	//alert(b);
	opt.left = b; opt.top = 0;//��������
	opt.IndexZ = 145; //IndexZ
	opt.fixed = 'fix'; //��������, ��� ��� ������������� ������
	var elem1 = CreateElementOnScreen('','',opt);	
	elem1.id = 'HidedIcons'; 
	elem1.innerHTML = "" ;  
	//document.getElementsByTagName('body')[0].appendChild(elem1);  //elemForm.style.display = 'none';	
	document.body.appendChild(elem1);
	var a = elem1.offsetWidth/2;
	//alert(a);
	elem1.style['left'] = (b-a)+"px";	
	elem1.style['padding'] = '2px';
	
	
	
	
	//�������� �������
	function HideElement(obj){
			obj.parentNode.parentNode.style.display = 'none';
			//var elem = document.getElementById('HidedIcons');
			if(obj.id == 'TegsH'){
				ShowTegsIcons();
				$.post('blocks/ajax.php',{NotShowTegs:1});
			}
			if(obj.parentNode.parentNode.id == 'ChatPaDiv'){
			//if(obj.id == 'ChatPaH'){
				ShowChatPaIcons();
				$.post('blocks/ajax.php',{NotShowChatPa:1});
			}
			if(obj.parentNode.parentNode.id == 'ChatDiv'){
			//if(obj.id == 'ChatH'){
				ShowChatIcons();
				$.post('blocks/ajax.php',{NotShowChat:1});
			}
			if(obj.parentNode.parentNode.id == 'EventDiv'){
			//if(obj.id == 'ChatH'){
				ShowEventIcons();
				$.post('blocks/ajax.php',{NotShowEvent:1});
			}
	};
	
	//�������� �������
	function ShowElem(obj){
			//obj.style.display = 'none';
			document.getElementById(obj).style.display = 'block';
			//elem.style.display = 'block';
			//������� ������� �� ������ ����
			var elem1 = document.getElementById(obj+'I');
			elem1.style.display = 'none';
			elem1.parentNode.removeChild(elem1);
			
			//������� ������� �� ����� �����
			var elem1 = document.getElementById(obj+'IPl');
			elem1.style.display = 'none';
			elem1.parentNode.removeChild(elem1);
			if(obj == 'TegsDiv'){
				$.post('blocks/ajax.php',{NotShowTegs:0});
			}
			if(obj == 'ChatPaDiv'){
				//�������� ��� �������� � ������ ������ ��������
				Getting_Messages();	setInterval(Getting_Messages,	 GGetL*1000);
				$.post('blocks/ajax.php',{NotShowChatPa:0});
			}
			if(obj == 'ChatDiv'){
				//�������� ��� � ������ ������
				Getting_Guest_List();  setInterval(Getting_Guest_List,	 GGetL*1000);
				$.post('blocks/ajax.php',{NotShowChat:0});
			}
			if(obj == 'EventDiv'){
				//�������� ������� �����
				Getting_About_Guest();  setInterval(Getting_About_Guest,	 GGetL*100);
				$.post('blocks/ajax.php',{NotShowEvent:0});
			}
	};
	
	
	//�������� ������� � HidedIcons
	function ShowTegsIcons(){
		document.getElementById('HidedIcons').innerHTML += "<b style='cursor:pointer;' onclick='ShowElem(\"TegsDiv\")' id='TegsDivI')'>|Tegs</b>";
		
		var opt = new Array(); //����� ����
		opt.IndexZ = 145; 
		var elem3 = CreateElementOnScreen('','',opt);	
		elem3.id='TegsDivIPl'; elem3.style['padding'] = '2px';
		elem3.innerHTML = "<span onclick='ShowElem(\"TegsDiv\")' style='cursor:pointer;'  > >Tegs< </span><br />";  //�������������� ��������
		document.getElementById('Thema_News').appendChild(elem3);  
	}
	function ShowChatPaIcons(){
		document.getElementById('HidedIcons').innerHTML += "<b style='cursor:pointer;' onclick='ShowElem(\"ChatPaDiv\")' id='ChatPaDivI')'>|ChatPa</b>";
		
		opt.left = 30; opt.top = 30;//��������
		opt.IndexZ = 140; //IndexZ
		opt.fixed = 'fix'; //��������, ��� ��� ������������� ������
		var elem3 = CreateElementOnScreen('','',opt);	
		elem3.id='ChatPaDivIPl'; elem3.style['padding'] = '2px';
		elem3.innerHTML = "<span onclick='ShowElem(\"ChatPaDiv\")' style='cursor:pointer;  '  > >ChatPa< </span><br />";  //�������������� ��������
		document.body.appendChild(elem3); 
	}
	function ShowChatIcons(){
		
		//if(document.getElementById('ChatDiv')){//���� ������ �������� ��� �� ������� �������� �������
			//GetChatDiv(); 
			//����� ��� �������� 
			//Getting_Guest_List();  setInterval(Getting_Guest_List,	 GGetL*1000);
		//}
		document.getElementById('HidedIcons').innerHTML += "<b style='cursor:pointer;' onclick='ShowElem(\"ChatDiv\")' id='ChatDivI')'>|Chat</b>";
		
		opt.left = 50; opt.top = 50;//��������
		opt.IndexZ = 140; //IndexZ
		opt.fixed = 'fix'; //��������, ��� ��� ������������� ������
		var elem3 = CreateElementOnScreen('','',opt);	
		elem3.id='ChatDivIPl'; elem3.style['padding'] = '2px';
		elem3.innerHTML = "<span onclick='ShowElem(\"ChatDiv\")' style='cursor:pointer;  '  > >Chat< </span><br />";  //�������������� ��������
		document.body.appendChild(elem3); 
	}
	function ShowEventIcons(){
		document.getElementById('HidedIcons').innerHTML += "<b style='cursor:pointer;' onclick='ShowElem(\"EventDiv\")' id='EventDivI')'>|Events</b>";
		
		opt.left = 70; opt.top = 70;//��������
		opt.IndexZ = 140; //IndexZ
		opt.fixed = 'fix'; //��������, ��� ��� ������������� ������
		var elem3 = CreateElementOnScreen('','',opt);	
		elem3.id='EventDivIPl'; elem3.style['padding'] = '2px';
		elem3.innerHTML = "<span onclick='ShowElem(\"EventDiv\")' style='cursor:pointer;  '  > >Events< </span><br />";  //�������������� ��������
		document.body.appendChild(elem3); 
	}	

