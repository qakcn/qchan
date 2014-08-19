// Initialzing variants
if(!main) {
	var file_list = document.getElementById('file_list'),
	file_select = document.getElementById('file_select'),
	url = document.getElementById('url'),
	normal = document.getElementById('normal'),
	url_zone = document.getElementById('url_zone'),
	normal_zone = document.getElementById('normal_zone'),
	upload = document.getElementById('upload'),
	upload_popup = document.getElementById('upload_popup'),
	closepop = document.getElementById('closepop'),
	url_list = document.getElementById('url_list'),
	submit = document.getElementById('submit'),
	main =  document.getElementById('main'),
	result_zone = document.getElementById('result_zone'),
	message_zone = document.getElementById('message_zone'),
	info_zone = document.getElementById('info_zone'),
	normal_form = document.getElementById('normal_form');
	file_review = document.getElementById('file_review');
}
if(!first_load) {
	var first_load = document.getElementById('first_load'),
	add = document.getElementById('add');
}

// Click button#file_select as click input#file_list
file_select.addEventListener('click', function(e) {
	file_list.click();
});

// Change upload method
var method = 'normal';
url.onclick = function(e) {
	this.className = 'none';
	normal.className = 'noact';
	hide(normal_zone);
	show(url_zone);
	method = 'url';
};
normal.onclick = function(e) {
	this.className = 'none';
	url.className = 'noact';
	show(normal_zone);
	hide(url_zone);
	method = 'normal';
};

// Show upload pop-up
show_popup = function(e) {upload_popup.style.display = 'block';};
add.onclick = show_popup;
upload.onclick = show_popup;

// Close upload pop-up and clean input and textarea
closepop.onclick = function(e) {
	hide(upload_popup);
	url_list.value = '';
	file_list.value = '';
};

// Show file list after file chose */
file_list.onchange=function(e){
	file_review.innerHTML = '';
	for(var file,i=0;i<this.files.length;i++) {
		file=this.files[i];
		var reviewp=document.createElement('p');
		reviewp.innerHTML = file.name;
		file_review.appendChild(reviewp);
	}
};

// Triger upload
submit.onclick = function(e) {
	hide(upload_popup);
	remove(first_load);
	if(method == 'url') {
		url_upload_handler();
		url_list.value = '';
	}else if(method=='normal') {
		if(new FileReader && new FormData) {
			var files = file_list.files;
			file_upload_handler(files);
			file_review.innerHTML = '';
			file_list.value = '';
		}else {
			normal_upload_handler();
		}
	}
};

/* Drag and drop event listener */
document.ondragenter = function(e) {
	e.stopPropagation();
	e.preventDefault();
};
document.ondragover = function(e) {
	e.stopPropagation();
	e.preventDefault();
};
document.ondrop = function(e) {
	e.stopPropagation();
	e.preventDefault();
	hide(upload_popup);
	remove(first_load);
	if(new FileReader || new FormData) {
		var files = e.dataTransfer.files;
		file_upload_handler(files);
	}else {
		alert(ui_msg.undrop);
	}
};

lastselected=null;

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
	info_zone.innerHTML = '';
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

/* Show error message */
function show_error(work) {
	var errli = document.createElement('li');
	addClass(errli,'error');
	addClass(errli,'hide');
	addClass(errli,'tiny');
	if(work.status == 'error') {
		errli.innerHTML = '<h1>'+ui_msg.status.error+ '</h1>';
	}else if(work.status == 'failed') {
		errli.innerHTML = '<h1>'+ui_msg.status.failed+ '</h1>';
	}
	var path = work.path.length > 30 ? work.path.slice(0,30)+'...' : work.path;
	switch(work.err) {
		case 'illegal_url':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.illegal_url+'</p>';
			break;
		case 'fail_load':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.fail_load+'</p>';
			break;
		case 'size_limit':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.size_limit+'</p>';
			break;
		case 'wrong_type':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.wrong_type+'</p>';
			break;
		case 'no_file':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.no_file+'</p>';
			break;
		case 'write_prohibited':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.write_prohibited+'</p>';
			break;
		case 'fail_duplicate':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.fail_duplicate+'</p>';
			break;
		case 'php_upload_size_limit':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.php_upload_size_limit+'</p>';
			break;
		case 'part_upload':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.part_upload+'</p>';
			break;
		case 'no_tmp':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.no_tmp+'</p>';
			break;
		case 'fail_retry':
			errli.innerHTML += '<p><em>\''+path+'\'</em>'+ui_msg.err.fail_retry+'</p>';
			break;
	}
	msg_show(errli);
}

/* Put thumbnail in the result zone */
function show_thumbnail(work) {
	var thmli = document.createElement('li'); //list item
	var thmprg = document.createElement('div'); //div for show progress
	var thmimg = document.createElement('div'); //div for show thumbnail
	var thmsel = document.createElement('div'); //div for show selected box
	var thmi = document.createElement('img'); //img for get image width and height
	addClass(thmprg,'progress');
	addClass(thmimg,'img');
	addClass(thmsel,'select');
	thmli.id = 'q'+work.qid;
	thmli.draggable = true;
	thmsel.innerHTML = '<p>'+ui_msg.info.selected+'</p>';
	if(work.type=='url') {
		thmi.src = work.path;
		thmi.onerror = thmi.onload = (function(work,thmli,thmimg,thmprg,thmsel) {
			return function(e) {
				show_thumbnail_part_b(work,thmli,thmimg,thmprg,thmsel,this);
			}
		})(work,thmli,thmimg,thmprg,thmsel);

	}else if(work.type=='file') {
		var fr;
		if(fr = new FileReader ) {
			fr.onload = (function(work,thmli,thmimg,thmprg,thmsel,thmi){
				return function(e) {
					thmi.src = e.target.result;
					thmi.onerror = thmi.onload = (function(work,thmli,thmimg,thmprg,thmsel) {
						return function(e) {
							show_thumbnail_part_b(work,thmli,thmimg,thmprg,thmsel,this);
						}
					})(work,thmli,thmimg,thmprg,thmsel);
				}
			})(work,thmli,thmimg,thmprg,thmsel,thmi);
			fr.readAsDataURL(work.fileobj);
		}
	}
}

/* Put thumbnail in the result zone part B, in order to execute after proper load */
function show_thumbnail_part_b(work,thmli,thmimg,thmprg,thmsel,thmi) {
	var width_orig = thmi.naturalWidth;
	var height_orig = thmi.naturalHeight;
	var width = 1000,height=200;
	if(width_orig==0 || height_orig==0) {
		work.status = 'error';
		work.err='fail_load';
		show_error(work);
		width = height = 200;
		thmimg.style.backgroundImage = 'url(site-img/error.svg)';
	}else if(height_orig>height || width_orig>width) {
		var ratio_orig = width_orig/height_orig;
		if (width/height > ratio_orig) {
			width = height*ratio_orig;
		}else {
			height = width/ratio_orig;
		}
		thmimg.style.backgroundImage = 'url("'+thmi.src+'")';
	}else {
		width = width_orig;
		height = height_orig;
		thmimg.style.backgroundImage = 'url("'+thmi.src+'")';
	}
	thmimg.style.backgroundSize = width+'px '+height+'px';
	thmli.style.width = thmimg.style.width = thmprg.style.width = width+'px';
	thmli.style.height = thmimg.style.height = thmprg.style.height = height+'px';
	thmli.style.marginTop = (205 - height)+'px';
	thmsel.style.paddingTop = (height - 30)+'px';
	thmprg.style.backgroundPosition = '0px center';
	thmli.appendChild(thmimg);
	thmimg.appendChild(thmprg);
	thmprg.appendChild(thmsel);
	thmli.work = work;
	thmli.oncontextmenu = toggleinfo();
	thmli.onclick = toggleinfo();
	
	/* progress handler */
	thmli.progress = progress(thmprg);
	result_zone.insertBefore(thmli, result_zone.lastElementChild);
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
	info_zone.innerHTML = '';
	selected.length>0 ? show_info_zone() : hide_info_zone();
	var namep = document.createElement('h1');
	var statusp = document.createElement('p');
	var infop = document.createElement('p');
	
	var flyin = function(elm) {
		addClass(elm,'new');
		var move = function(){removeClass(elm,'new');};
		setTimeout(move,50);
	}
	flyin(namep);
	flyin(statusp);
	flyin(infop);
	if(selected.length==1) {
		var work = selected[0].work;
		switch(work.status) {
			case 'prepared':
				namep.innerHTML = work.path;
				statusp.innerHTML = ui_msg.status.prepared;
				break;
			case 'waiting':
				namep.innerHTML = work.path;
				statusp.innerHTML = ui_msg.status.waiting;
				break
			case 'error':
				namep.innerHTML = work.path;
				statusp.innerHTML = ui_msg.status.error;
				switch(work.err) {
					case 'fail_duplicate':
						infop.innerHTML = ui_msg.err_detail.fail_duplicate;
						break;
					case 'fail_load':
						infop.innerHTML = ui_msg.err_detail.fail_load;
				}
				break;
			case 'failed':
				namep.innerHTML = work.path;
				statusp.innerHTML = ui_msg.status.failed;
				switch(work.err) {
					case 'no_file':
						infop.innerHTML = ui_msg.err_detail.no_file;
						break;
					case 'size_limit':
						infop.innerHTML = ui_msg.err_detail.size_limit;
						break;
					case 'write_prohibited':
						infop.innerHTML = ui_msg.err_detail.write_prohibited;
						break;
					case 'php_upload_size_limit':
						infop.innerHTML = ui_msg.err_detail.php_upload_size_limit;
						break;
					case 'part_upload':
						infop.innerHTML = ui_msg.err_detail.part_upload;
						break;
					case 'no_tmp':
						infop.innerHTML = ui_msg.err_detail.no_tmp;
						break;
					case 'fail_retry':
						infop.innerHTML = ui_msg.err_detail.fail_retry;
						break;
					case 'wrong_type':
						infop.innerHTML = ui_msg.err_detail.wrong_type;
						break;
				}
				break;
			case 'uploading':
				namep.innerHTML = work.path;
				statusp.innerHTML = ui_msg.status.uploading;
				break;
			case 'success':
				namep.innerHTML = work.name;
				statusp.innerHTML = ui_msg.status.success;
				addClass(infop, 'result_info');
				infop.innerHTML = single_format(work);
				break;
		}
	}else if(selected.length>1) {
		namep.innerHTML = selected.length + ui_msg.info.files_selected;
		var works = new Array();
		for(var work,i=0;i<selected.length;i++) {
			work = selected[i].work;
			if(work.status=='success') {
				works.push(work);
			}
		}
		if(works.length == 0) {
			statusp.innerHTML = ui_msg.status.all_failed;
		}else if(works.length == selected.length) {
			statusp.innerHTML = ui_msg.status.all_success;
			addClass(infop, 'result_info');
			multi_format(works,infop);
		}else if(works.length < selected.length) {
			statusp.innerHTML = ui_msg.status.part_success;
			addClass(infop, 'result_info');
			multi_format(works,infop);
		}
		
	}
	info_zone.appendChild(namep);
	info_zone.appendChild(statusp);
	info_zone.appendChild(infop);
}

function single_format(work) {
	var output = '<input type="text" id="orig-'+work.qid+'" value="'+work.path+'" onclick="this.select()" readonly><label for="orig-'+work.qid+'">'+ui_msg.info.orig+'</label><br>'+
	'<input type="text" id="html-'+work.qid+'" value="&lt;img src=&quot;'+work.path+'&quot;&gt;" onclick="this.select()" readonly><label for="html-'+work.qid+'">'+ui_msg.info.html+'</label><br>';
	if(work.thumb != 'none') {
		output += '<input type="text" id="htmlthm-'+work.qid+'" value="&lt;a href=&quot;'+work.path+'&quot; title=&quot;'+ui_msg.info.thumb_tips+'&quot;&gt;&lt;img src=&quot;'+work.thumb+'&quot;&gt;&lt;/a&gt;" onclick="this.select()" readonly><label for="htmlthm-'+work.qid+'">'+ui_msg.info.html_with_thumb+'</label><br>';
	}
	output += '<input type="text" id="bbc-'+work.qid+'" value="[img]'+work.path+'[/img]" onclick="this.select()" readonly><label for="bbc-'+work.qid+'">'+ui_msg.info.bbcode+'</label><br>';
	if(work.thumb != 'none') {
		output += '<input type="text" id="bbcthm-'+work.qid+'" value="[url='+work.path+'][img]'+work.thumb+'[/img][/url]" onclick="this.select()" readonly><label for="bbcthm-'+work.qid+'">'+ui_msg.info.bbcode_with_thumb+'</label>';
	}
	return output;
}

function multi_format(works,infop) {
	var result_area = document.createElement('textarea');
	result_area.onclick = function(){this.select()};
	result_area.torig = result_area.thtml = result_area.thtmlthm = result_area.tbbcode = result_area.tbbcodethm = '';
	var nothm_cnt = 0;
	for(var work,i=0;i<works.length;i++) {
		work = works[i];
		result_area.torig += work.path + '\n';
		result_area.thtml += '<img src="'+work.path+'">' + '\n';
		result_area.tbbcode += '[img]'+work.path+'[/img]' + '\n';
		if(work.thumb!='none') {
			result_area.thtmlthm += '<a href="'+work.path+'" title="'+ui_msg.info.thumb_tips+'"><img src="'+work.thumb+'"></a>' + '\n';
			result_area.tbbcodethm += '[url='+work.path+'][img]'+work.thumb+'[/img][/url]' + '\n';
		}else {
			nothm_cnt++;
			result_area.thtmlthm += '<img src="'+work.path+'">' + '\n';
			result_area.tbbcodethm += '[img]'+work.path+'[/img]' + '\n';
		}
	}
	result_area.value = result_area.torig;
	result_area.readOnly = true;
	var lorig = document.createElement('label');
	var lhtml = document.createElement('label');
	var lbbcode = document.createElement('label');
	lorig.innerHTML = ui_msg.info.orig;
	lhtml.innerHTML = ui_msg.info.html;
	lbbcode.innerHTML = ui_msg.info.bbcode;
	result_area.selectedl = lorig;
	addClass(lorig, 'actived');
	addClass(lorig, 'multi');
	addClass(lhtml, 'multi');
	addClass(lbbcode, 'multi');
	lorig.onclick = (function(ra) {
		return function() {
			removeClass(result_area.selectedl, 'actived');
			result_area.selectedl=this;
			addClass(this, 'actived');
			result_area.value = result_area.torig;
		}
	})(result_area);
	lhtml.onclick = (function(ra) {
		return function() {
			removeClass(result_area.selectedl, 'actived');
			result_area.selectedl=this;
			addClass(this, 'actived');
			result_area.value = result_area.thtml;
		}
	})(result_area);
	lbbcode.onclick = (function(ra) {
		return function() {
			removeClass(result_area.selectedl, 'actived');
			result_area.selectedl=this;
			addClass(this, 'actived');
			result_area.value = result_area.tbbcode;
		}
	})(result_area);
	if(nothm_cnt == works.length) {
		result_area.thtmlthm = undefined;
		result_area.tbbcodethm = undefined;
	}else {
		var lhtmlthm = document.createElement('label');
		var lbbcodethm = document.createElement('label');
		lhtmlthm.innerHTML = ui_msg.info.html_with_thumb;
		lbbcodethm.innerHTML = ui_msg.info.bbcode_with_thumb;
		addClass(lhtmlthm, 'multi');
		addClass(lbbcodethm, 'multi');
		lhtmlthm.onclick = (function(ra) {
			return function() {
				removeClass(result_area.selectedl, 'actived');
				result_area.selectedl=this;
				addClass(this, 'actived');
				result_area.value = result_area.thtmlthm;
			}
		})(result_area);
		lbbcodethm.onclick = (function(ra) {
			return function() {
				removeClass(result_area.selectedl, 'actived');
				result_area.selectedl=this;
				addClass(this, 'actived');
				result_area.value = result_area.tbbcodethm;
			}
		})(result_area);
	}
	infop.appendChild(result_area);
	infop.appendChild(lorig);
	infop.appendChild(lhtml);
	if(nothm_cnt < works.length) {
	infop.appendChild(lhtmlthm);
	}
	infop.appendChild(lbbcode);
	if(nothm_cnt < works.length) {
		infop.appendChild(lbbcodethm);
	}
}