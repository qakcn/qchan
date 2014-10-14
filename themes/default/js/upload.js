/* global variants */
var queueid=0,
queue = new Array(),
working = 0,
msgqueue = new Array();

/* Handler for URL upload */
function url_upload_handler() {
	var urls = url_list.value.split('\n');
	for (var url,i=0;i<urls.length;i++) {
		url = urls[i].trim();
		var work = {
			type: 'url',
			path: url,
			status: 'prepared',
			retry: 0
		};
		if(!isempty(url)) {
			if (isurl(url)) {
				work.qid = queueid++;
				show_thumbnail(work);
				upload(work);
			}else {
				work.status = 'failed'
				work.err = 'illegal_url';
			}
		}
	}
}

/* Handler for file upload */
function file_upload_handler(files) {
	for(var file,i=0;i<files.length;i++) {
		file=files[i];
		work = {
			type: 'file',
			path: file.name,
			status: 'prepared',
			fileobj: file
		};
		if(!/image\/(jpeg|png|gif|svg\+xml)/.test(file.type)) {
			work.status = 'failed';
			work.err = 'wrong_type';
		}else if(file.size > prop.size_limit) {
			work.status = 'failed';
			work.err = 'size_limit';
		}else {
			work.qid = queueid++;
			show_thumbnail(work);
			upload(work);
		}
	}
}

/* Submit from */
function normal_upload_handler() {
	$('#normal_form')[0].submit();
}

/* Start upload or put in the queue */
function upload(work) {
	if (working < prop.upload_count) {
		select_upload(work);
	} else {
		work.status = 'waiting';
		queue.push(work);
	}
}

/* Upload next item in the queue */
function upload_next() {
	working--;
	if(queue.length>0) {
		work = queue.shift();
		select_upload(work);
	}
}

function retry_upload(work) {
	working--;
	work.retry++;
	if(work.retry < 3) {
		upload(work);
	}else {
		work.status = 'failed';
		work.err = 'fail_retry';
	}
}

/* Choose upload method */
function select_upload(work) {
	work.status = 'uploading';
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

/* Upload URL item */
function url_upload(work) {
	var self = document.getElementById('q'+work.qid);
	if(!self) {
		var callself = function(){url_upload(work);};
		setTimeout(callself,100);
		return false;
	}

	var xhr = new XMLHttpRequest();
	
	xhr.open('POST', 'api.php?type=url&'+(new Date()).getTime(), true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	var sendData = 'qid='+work.qid+'&url='+encodeURIComponent(work.path);
	
	var retry = (function(work){
		return function(){
			retry_upload(work);
		}
	})(work);
	
	xhr.addEventListener('readystatechange', function(e){
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				var res=JSON.parse(xhr.responseText);
				after_upload(res);
				upload_next();
			}else if(xhr.status == 504 || xhr.status == 503) {
				retry();
			}
		}
	},false);

	xhr.upload.addEventListener('progress',function(e){
		if(e.lengthComputable) {
			var percentage = e.loaded/e.total;
			self.progress(percentage);
		}
	},false);
	xhr.upload.addEventListener('load',function(e){
		self.progress(1);
	},false);
	
	xhr.send(sendData);
}

/* Upload file item */
function file_upload(work) {
	var self = document.getElementById('q'+work.qid);
	if(!self) {
		var callself = function(){file_upload(work);};
		setTimeout(callself,100);
		return false;
	}
	var xhr = new XMLHttpRequest();
	var fd = new FormData();
	
	xhr.open('POST', 'api.php?type=file&'+(new Date()).getTime(), true);
	//xhr.setRequestHeader("Content-type", "multipart/form-data");
	
	var retry = (function(work){
		return function(){
			retry_upload(work);
		}
	})(work);
	
	xhr.addEventListener('readystatechange', function(e){
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				eval('var res = '+xhr.responseText);
				after_upload(res);
				upload_next();
			}else if(xhr.status == 504 || xhr.status == 503) {
				retry();
			}
		}
	},false);
	
	xhr.upload.addEventListener('progress',function(e){
		if(e.lengthComputable) {
			var percentage = e.loaded/e.total;
			self.progress(percentage);
		}
	},false);
	xhr.upload.addEventListener('load',function(e){
		self.progress(1);
	},false);
	
	fd.append('qid',work.qid);
	fd.append('files[]',work.fileobj);
	
	xhr.send(fd);
}

/* Check if valid URL */
function isurl(theurl) {
	return /^\s*https?:\/\/.+$/.test(theurl);
}

/* Check if empty URL */
function isempty(theurl) {
	return (/^\s*$/.test(theurl) || theurl=='');
}

function after_upload(res) {
	var qli = $('#q'+res.qid);
	var qimg = qli.children('.img');
	if(!qli[0]) {
		var callself = function(){after_upload(res);};
		setTimeout(callself,100);
		return false;
	}
	switch (res.status) {
		case 'success':
			qli.prop('work').status = 'success';
			qli.prop('work').name = res.name;
			qli.prop('work').path = res.path;
			qli.prop('work').thumb = res.thumb;
			if(res.thumb=='none') {
				qimg.css('background-image', 'url("'+res.path+'")');
			}else {
				qimg.css('background-image', 'url("'+res.thumb+'")');
			}
			break;
		case 'error':
			qli.prop('work').status = 'error';
			qli.prop('work').err = res.err;
			qli.prop('work').name = res.name;
			qli.prop('work').path = res.path;
			qli.prop('work').thumb = res.thumb;
			if(res.thumb=='none') {
				qimg.css('background-image', 'url("'+res.path+'")');
			}else {
				qimg.css('background-image', 'url("'+res.thumb+'")');
			}
			break;
		case 'failed':
				qli.prop('work').status = 'failed';
				qli.prop('work').err = res.err;
				qimg.css({
					backgroundImage: 'url('+prop.error_image+')',
					backgroundSize: '200px 200px'
				});
				qli.width('200px').height('200px').css({marginTop: 0, marginBottom: 0});
			break;
	}
	changeinfo(true);
}