var queueid=0,
queue = new Array(),
working = 0,
msgqueue = new Array();

function url_upload_handler() {
	var urls = url_list.value.split('\n');
	for (var url,i=0;i<urls.length;i++) {
		url = urls[i];
		var work = {
			type: 'url',
			path: url,
			statu: 'prepared'
		};
		if(!isempty(url)) {
			if (isurl(url)) {
				work.qid = queueid++;
				show_thumbnail(work);
				upload(work);
			}else {
				work.statu = 'error'
				work.err = 'illegal_url';
				show_error(work);
			}
		}
	}
}

function file_upload_handler(files) {
	for(var file,i=0;i<files.length;i++) {
		file=files[i];
		work = {
			type: 'file',
			path: file.name,
			statu: 'prepared',
			fileobj: file
		};
		if(!/image\/(jpeg|png|gif|svg\+xml)/.test(file.type)) {
			work.statu = 'error';
			work.err = 'wrong_type';
			show_error(work);
		}else if(file.size > prop.size_limit) {
			work.statu = 'error';
			work.err = 'size_limit';
			show_error(work);
		}else {
			work.qid = queueid++;
			show_thumbnail(work);
			upload(work);
		}
	}
}

function normal_upload_handler() {
	normal_form.submit();
}

function upload(work) {
	if (working<3) {
		select_upload(work);
	} else {
		work.statu = 'waiting';
		queue.push(work);
	}
}

function upload_next() {
	working--;
	if(queue.length>0) {
		work = queue.shift();
		select_upload(work);
	}
}

function select_upload(work) {
	work.statu = 'uploading';
	switch (work.type) {
		case 'url':
			url_upload(work);
			break;
		case 'file':
			file_upload(work);
			break;
	}
	working++;
}

function url_upload(work) {
	
}

function file_upload(work) {
	
}

function isurl(theurl) {
	return /^\s*https?:\/\//.test(theurl);
}

function isempty(theurl) {
	return (/^\s*$/.test(theurl) || theurl=='');
}

function show_error(work) {
	var errli = document.createElement('li');
	addClass(errli,'error');
	addClass(errli,'hide');
	addClass(errli,'tiny');
	errli.innerHTML = '<h1>'+ui_msg.statu.error+ '</h1>'
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
	}
	msg_show(errli);
}

function show_thumbnail(work) {
	var thmli = document.createElement('li');
	var thmprg = document.createElement('div');
	var thmimg = document.createElement('div');
	var thmsel = document.createElement('div');
	var thmi = document.createElement('img');
	addClass(thmprg,'progress');
	addClass(thmimg,'img');
	addClass(thmsel,'select');
	thmli.id = 'q'+work.qid;
	thmsel.innerHTML = '<p>'+ui_msg.info.selected+'</p>';
	if(work.type=='url') {
		thmi.src = work.path;
		show_thumbnail_part_b(work,thmli,thmimg,thmprg,thmsel,thmi);
	}else if(work.type=='file') {
		var fr;
		if(fr = new FileReader ) {
			fr.onload = (function(work,thmli,thmimg,thmprg,thmsel,thmi){
				return function(e) {
					thmi.src = e.target.result;
					thmi.onload = (function(work,thmli,thmimg,thmprg,thmsel) {
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

function show_thumbnail_part_b(work,thmli,thmimg,thmprg,thmsel,thmi) {
	var width = thmi.naturalWidth;
	var height = thmi.naturalHeight;
	if(width==0 || height==0) {
		work.err='fail_load';
		show_error(work);
		width = height = 200;
		thmimg.style.backgroundImage = 'url(site-img/error.svg)';
	}else if(height>200) {
		width = width * 200/height;
		height = 200;
		thmimg.style.backgroundImage = 'url('+thmi.src+')';
	}else {
		thmimg.style.backgroundImage = 'url('+thmi.src+')';
	}
	thmimg.style.backgroundSize = width+'px '+height+'px';
	thmli.style.width = thmimg.style.width = thmprg.style.width = width+'px';
	thmli.style.height = thmimg.style.height = thmprg.style.height = height+'px';
	thmli.style.marginTop = thmli.marginBottom = (200 - height) / 2+'px';
	thmsel.style.paddingTop = (height - 30)+'px';
	thmli.appendChild(thmimg);
	thmimg.appendChild(thmprg);
	thmprg.appendChild(thmsel);
	thmli.work = work;
	thmli.oncontextmenu = toggleinfo();
	thmli.onclick = toggleinfo();
	thmli.progress = function(percent){
		var pos = thmprg.style.width.slice(0,-2) * percent;
		var anipos = function(px,thmprg){
			var nowpos = thmprg.style.backgroundPosition.slice (0,-9);
			nowpos = nowpos*1+2;
			thmprg.style.backgroundPosition = nowpos+'px center';
			if(nowpos<px) {
				var callself = function(){anipos(px,thmprg)};
				setTimeout(callself, 1);
			}
		}
		anipos(pos,thmprg);
	};
	result_zone.insertBefore(thmli, result_zone.lastElementChild);
}

function toggleinfo(mode) {
	return function(e) {
		e.preventDefault();
		if(e.type=='click') {
			var selected = document.getElementsByClassName('selected');
			var selectedcnt = selected.length;
			var ishide = hasClass(info_zone,'hide');
			while(selected.length>0) {
				var everyli=selected[0];
				var thisselected = (thisselected || everyli==this);
				removeClass(everyli,'selected');
				hide(everyli.children[0].children[0].children[0]);
			}
			if(selectedcnt>1 || !thisselected || ishide) {
				addClass(this,'selected');
				show(this.children[0].children[0].children[0]);
			}
		}else if(e.type=='contextmenu'){
			if(hasClass(this,'selected')) {
				removeClass(this,'selected');
				hide(this.children[0].children[0].children[0]);
			}else {
				addClass(this,'selected');
				show(this.children[0].children[0].children[0]);
			}
		}
		changeinfo();
	}
}

function changeinfo() {
	var ishide = hasClass(info_zone,'hide');
	var selected = document.getElementsByClassName('selected');
	ishide ? '' : (selected.length>0 ? hide_info_zone(true) : hide_info_zone(false));
	var namep = document.createElement('h1');
	var statup = document.createElement('p');
	if(selected.length==1) {
		var work = selected[0].work;
		switch(work.statu) {
			case 'prepared':
				namep.innerHTML = work.path;
				statup.innerHTML = ui_msg.statu.prepared;
				break;
			case 'waiting':
				namep.innerHTML = work.path;
				statup.innerHTML = ui_msg.statu.waiting;
				break
			case 'error':
				namep.innerHTML = work.path;
				statup.innerHTML = ui_msg.statu.error;
				break
			case 'uploading':
				namep.innerHTML = work.path;
				statup.innerHTML = ui_msg.statu.uploading;
				break;
			case 'success':
				namep.innerHTML = work.filename;
				statup.innerHTML = ui_msg.statu.success;
				break;
		}
	}else if(selected.length>1) {
		namep.innerHTML = selected.length + ui_msg.info.files_selected;
		for(var work,i=0;i<selected.length;i++) {
			work = selected[i].work;
		}
	}
	info_zone.appendChild(namep);
	info_zone.appendChild(statup);
	selected.length>0 ? (ishide ? show_info_zone() : setTimeout(show_info_zone, 210)) : '';
}