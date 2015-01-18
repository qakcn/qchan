var qchan=function(){
	
	// private properties
	var that = {};
	var upload_count = 3;
	
	// static properties
	qchan.prototype.queue = qchan.prototype.queue || [];
	qchan.prototype.working = qchan.prototype.working || 0;
	qchan.prototype.queueid = qchan.prototype.queueid || 0;
	qchan.prototype.before = qchan.prototype.before || function(){};
	qchan.prototype.after = qchan.prototype.after || function(){};
	qchan.prototype.progress = qchan.prototype.progress || function(){};
	
	// private methods
	var getQID = function() {
		return qchan.prototype.queueid++;
	};
	
	var getWorking = function() {
		return qchan.prototype.working;
	};
	
	var incWorking = function() {
		qchan.prototype.working++;
	};
	
	var decWorking = function() {
		qchan.prototype.working--;
	};
	
	var pushQueue = function(work) {
		qchan.prototype.queue.push(work);
	};
	var getQueueLength = function() {
		return qchan.prototype.queue.length;
	};
	var shiftQueue = function() {
		return qchan.prototype.queue.shift();
	}
	
	var isurl = function(theurl) {
		return /^\s*https?:\/\/.+$/.test(theurl);
	}

	var isempty = function(theurl) {
		return (/^\s*$/.test(theurl) || theurl=='');
	}
	
	var callBefore = function(work) {
		qchan.prototype.before(work);
	};
	var callAfter = function(res) {
		qchan.prototype.after(res);
	};
	var callProgress = function(work, range) {
		qchan.prototype.progress(work, range);
	};
	
	
	var retry_upload = function(work) {
		decWorking();
		work.retry++;
		if(work.retry < 3) {
			upload(work);
		}else {
			work.status = 'failed';
			work.err = 'fail_retry';
		}
	};
	
	var upload_next = function() {
		decWorking();
		if(getQueueLength() > 0) {
			upload(shiftQueue());
		}
	};
	
	var upload = function(work) {
		var xhr = new XMLHttpRequest();
		
		if(getWorking() >= upload_count) {
			work.status = 'waiting';
			pushQueue(work);
		}else {
			work.status = 'uploading';
			switch (work.type) {
			case 'url':
				xhr.open('POST', 'api.php?type=url&'+(new Date()).getTime(), true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				var sendData = 'qid='+work.qid+'&url='+encodeURIComponent(work.path);
				break;
			case 'file':
				var sendData = new FormData();
				xhr.open('POST', 'api.php?type=file&'+(new Date()).getTime(), true);
				sendData.append('qid',work.qid);
				sendData.append('files[]',work.fileobj);
				break;
			}
			incWorking();
			
			var retry_callback = (function(work){
				return function(){
					retry_upload(work);
				}
			})(work);
			var progress_callback = (function(work){
				return function(range){
					callProgress(work, range);
				}
			})(work);
		
			xhr.addEventListener('readystatechange', function(e){
				if(xhr.readyState == 4) {
					if(xhr.status == 200) {
						var res=JSON.parse(xhr.responseText);
						callAfter(res);
						upload_next();
					}else if(xhr.status == 504 || xhr.status == 503) {
						retry_callback();
					}
				}
			},false);
		
			xhr.upload.addEventListener('progress',function(e){
				if(e.lengthComputable) {
					var range = e.loaded/e.total;
					progress_callback(range);
				}
			},false);
			xhr.upload.addEventListener('load',function(e){
				progress_callback(1);
			},false);
			
			xhr.send(sendData);
		}
	};
	
	that.isSupport = !!window.FormData;
	
	that.setBefore = function(before) {
		qchan.prototype.before = before;
	};
	
	that.setAfter = function(after) {
		qchan.prototype.after = after;
	};
	
	that.setProgress = function(progress) {
		qchan.prototype.progress = progress;
	};
	
	// public method
	that.url_upload = function(urls) {
		for (var url,i=0;i<urls.length;i++) {
			url = urls[i].trim();
			var work = {
				qid: getQID(),
				type: 'url',
				path: url,
				retry: 0
			};
			if(!isempty(url) || !isurl(url)) {
				work.status = 'failed';
				work.err = 'illegal_url';
			}else {
				work.status = 'prepared';
			}
			callBefore(work);
			upload(work);
		}
	};
	
	that.file_upload = function(filelist) {
		for(var file,i=0;i<filelist.length;i++) {
			file=filelist[i];
			var work = {
				qid: getQID(),
				type: 'file',
				path: file.name,
				fileobj: file
			};
			if(!/image\/(jpeg|png|gif|svg\+xml)/.test(file.type)) {
				work.status = 'failed';
				work.err = 'wrong_type';
			}else if(file.size > prop.size_limit) {
				work.status = 'failed';
				work.err = 'size_limit';
			}else {
				work.status = 'prepared';
			}
			callBefore(work);
			upload(work);
		}
	};
	
	that.form_upload = function(formele) {
		formele.method = 'post';
		formele.action = 'index.php';
		formele.submit();
	}
	
	return that;
};