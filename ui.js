// Initialzing variants
if(!main) {
	var file_list = document.getElementById('file_list'),
	file_select = document.getElementById('file_select'),
	url = document.getElementById('url'),
	normal = document.getElementById('normal'),
	url_zone = document.getElementById('url_zone'),
	normal_zone = document.getElementById('normal_zone'),
	add = document.getElementById('add'),
	upload = document.getElementById('upload'),
	upload_popup = document.getElementById('upload_popup'),
	closepop = document.getElementById('closepop'),
	url_list = document.getElementById('url_list'),
	submit = document.getElementById('submit'),
	first_load = document.getElementById('first_load'),
	main =  document.getElementById('main'),
	result_zone = document.getElementById('result_zone'),
	message_zone = document.getElementById('message_zone'),
	info_zone = document.getElementById('info_zone'),
	normal_form = document.getElementById('normal_form');
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

// Triger upload
submit.onclick = function(e) {
	hide(upload_popup);
	remove(first_load);
	if(method == 'url') {
		url_upload_handler();
		url_list.value = '';
	}else if(method=='normal') {
		if(new FileReader || new FormData) {
			var files = file_list.files;
			file_upload_handler(files);
		}else {
			normal_upload_handler();
		}
	}
};

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

function show(elm) {
	elm.style.display = 'block';
}

function hide(elm) {
	elm.style.display = 'none';
}

function remove(elm) {
	if(elm.parentElement) {
		elm.parentElement.removeChild(elm);
	}
}

function addClass(elm,classname) {
	return elm.classList.add(classname);
}
function removeClass(elm,classname) {
	return elm.classList.remove(classname);
}
function hasClass(elm,classname) {
	return elm.classList.contains(classname);
}

function show_info_zone() {
	removeClass(info_zone,'hide');
	addClass(main,'showinfo');
	addClass(message_zone,'showinfo');
}

function hide_info_zone(reshow) {
	addClass(info_zone,'hide');
	if(!reshow) {
		removeClass(main,'showinfo');
	}
	removeClass(message_zone,'showinfo');
	info_zone.innerHTML = '';
	
}

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