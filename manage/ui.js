// Initialzing variants
if(!main) {
	var main = document.getElementById('main'),
	result_zone = document.getElementById('result_zone'),
	message_zone = document.getElementById('message_zone'),
	info_zone = document.getElementById('info_zone'),
	delete_image = document.getElementById('delete_image'),
	view_image = document.getElementById('view_image'),
	next_page = document.getElementById('next_page'),
	prev_page = document.getElementById('prev_page'),
	page_select = document.getElementById('page_select');
}

scrollLoading();

next_page.onclick = function(){
	window.location.href = page_select.dataset.url+(parseInt(page_select.value)+1);
};
page_select.onchange = function(){
	window.location.href = page_select.dataset.url+page_select.value;
};
prev_page.onclick = function(){
	window.location.href = page_select.dataset.url+(parseInt(page_select.value)-1);
};

delete_image.onclick = function(){
	var files=document.getElementsByClassName('selected');
	files=Array.prototype.slice.call(files);
	
	var xhr = new XMLHttpRequest();
	
	xhr.open('POST', 'api.php?action=delete&'+(new Date()).getTime(), true);
	//xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	var sendData = '';
	
	var works=new Array;
	for(var i=0;i<files.length; i++) {
		works[i] = files[i].work;
	}
	
	var sendData = JSON.stringify(works);
	
	xhr.addEventListener('readystatechange', function(e){
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				var res=JSON.parse(xhr.responseText);
				for(id in res) {
					if(res[id] == 'deleted' || res[id] == 'thumbdelfail') {
						remove(document.getElementById(id));
						changeinfo();
					}
				}
			}
		}
	},false);
/*
	xhr.upload.addEventListener('progress',function(e){

	},false);
	xhr.upload.addEventListener('load',function(e){

	},false);
	*/
	xhr.send(sendData);
}

view_image.onclick = function(){
	var selected = document.getElementsByClassName('selected');
	for(var i=0;i<selected.length;i++) {
		var work = selected[i].work;
		window.open(work.path);
	}
};

main.addEventListener('scroll',scrollLoading);


lastselected=null;

function scrollLoading(){
	var scrollmax,unloadimgs;
	if(main.scrollTopMax) {
		scrollmax = main.scrollTopMax
	}else {
		scrollmax = main.scrollHeight - main.clientHeight;
	}
	unloadimgs = document.getElementsByClassName('scroll-load');
	unloadimgs = Array.prototype.slice.call(unloadimgs);
	for(var i=0;i<unloadimgs.length;i++) {
		var thisimg = unloadimgs[i];
		if(thisimg.offsetTop - main.scrollTop < main.clientHeight - 20 && thisimg.offsetTop > main.scrollTop - 180) {
			thisimg.children[0].style.backgroundImage = 'url("' + thisimg.dataset.thumb + '")';
			removeClass(thisimg,'scroll-load');
		}
	}
}

/* show an element through CSS display */
function show(elm) {
	elm.style.display = 'block';
}

/* hide an element through CSS display */
function hide(elm) {
	elm.style.display = 'none';
}

/* remove an element */
function remove(elm) {
	if(elm.parentElement) {
		elm.parentElement.removeChild(elm);
	}
}

/* add a class to element */
function addClass(elm,classname) {
	return elm.classList.add(classname);
}

/* remove a class from element */
function removeClass(elm,classname) {
	return elm.classList.remove(classname);
}
/* check if element has a class */
function hasClass(elm,classname) {
	return elm.classList.contains(classname);
}

/* show info zone */
function show_info_zone() {
	removeClass(info_zone,'hide');
	addClass(main,'showinfo');
	addClass(message_zone,'showinfo');
}

/* hide info zone */
function hide_info_zone() {
	addClass(info_zone,'hide');
	removeClass(main,'showinfo');
	removeClass(message_zone,'showinfo');
}

/* show a message in the message zone */
function msg_show(msgli) {
	var capacity = (window.innerHeight - 290) / 132 - 1;
	capacity = capacity>0 ? capacity : 0;
	if(message_zone.children.length > capacity) {
		var callself = function(){msg_show(msgli);};
		setTimeout(callself, 200);
	}else {
		message_zone.appendChild(msgli);
		var fadein = function(){removeClass(msgli,'hide');removeClass(msgli,'tiny');setTimeout(fadeout, 5000);};
		var fadeout = function(){addClass(msgli,'hide');setTimeout(kickout, 400);};
		var kickout = function(){remove(msgli);};
		setTimeout(fadein, 200);
	}
}

function progress(thmprg) {
	return function(percent) {
		var pos = thmprg.style.width.match(/([\.\d]+)px/).pop() * percent;
		var anipos = function(px,thmprg){
			var nowpos = thmprg.style.backgroundPosition.match(/([\d\.]+)px.*/).pop();
			nowpos = nowpos*1+pos/50;
			thmprg.style.backgroundPosition = nowpos+'px center';
			if(nowpos<px) {
				var callself = function(){anipos(px,thmprg)};
				setTimeout(callself, 1);
			}
		}
		anipos(pos,thmprg);
	}
}

function openimage() {
		window.open(this.work.path);
		return false;
}

/* Show info zone when selecting files*/
function toggleinfo() {
	return function(e) {
		e.preventDefault();
		if(e.type=='click') {
			if(e.shiftKey && lastselected) {
				var start=lastselected.id.slice(1);
				var end=this.id.slice(1);
				if(-start>-end) {
					start = lastselected;
					end = this;
				}else {
					start = this;
					end = lastselected;
				}
				for(var i=start;i != end.nextElementSibling;i=i.nextElementSibling) {
					addClass(i,'selected');
				}
				lastselected = this;
			}else {
				var selected = document.getElementsByClassName('selected');
				var selectedcnt = selected.length;
				var ishide = hasClass(info_zone,'hide');
				while(selected.length>0) {
					var everyli=selected[0];
					var thisselected = (thisselected || everyli==this);
					lastselected=null;
					removeClass(everyli,'selected');
				}
				if(selectedcnt>1 || !thisselected || ishide) {
					lastselected=this;
					addClass(this,'selected');
				}
			}
		}else if(e.type=='contextmenu'){
			if(hasClass(this,'selected')) {
				lastselected=null;
				removeClass(this,'selected');
			}else {
				lastselected=this;
				addClass(this,'selected');
			}
		}
		changeinfo();
	}
}

/* Change info zone content when showing info zone*/
function changeinfo() {
	var selected = document.getElementsByClassName('selected');
	var ishide = hasClass(info_zone,'hide');
	selected.length>0 ? show_info_zone() : hide_info_zone();
	var namep = document.getElementById('namep');
	var buttonp = document.getElementById('buttonp');
	
	var flyin = function(elm) {
		addClass(elm,'new');
		var move = function(){removeClass(elm,'new');};
		setTimeout(move,50);
	}
	flyin(namep);
	flyin(buttonp);
	
	if(selected.length==1) {
		var work = selected[0].work;
		namep.innerHTML = work.name;
	}else if(selected.length>1) {
		namep.innerHTML = selected.length + ui_msg.info.files_selected;
		var works = new Array();
		for(var work,i=0;i<selected.length;i++) {
			work = selected[i].work;
			works.push(work);
		}
	}
}