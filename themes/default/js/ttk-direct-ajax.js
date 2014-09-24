/* 贴图库直接上传的JS文件 */

function ttk_get_token(method,callback) {
	var xhr = new XMLHttpRequest();
	
	xhr.open('GET', 'api.php?gettoken='+method, true);
	
	xhr.addEventListener('readystatechange', (function(callback){
		return function(e) {
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					var res=JSON.parse(xhr.responseText);
					callback(res.token);
				}
			}
		}
	})(callback),false);
	
	xhr.send();
}

function set_pic_size(width_orig, height_orig) {
    height = 200;
    width = 1000;
    re={};
    if(height_orig <= height && width_orig <= width) {
        re.width = width_orig;
        re.height = height_orig;
    }else{
        ratio_orig = width_orig/height_orig;
        if (width/height > ratio_orig) {
            width = height*ratio_orig;
        }else {
            height = width/ratio_orig;
        }
        re.width = width;
		re.height = height;
    }
    return re;
}

function ttk_upload(work,self,method) {
	ttk_get_token(method, (function(work,self){
		return function(ttktoken){
			var xhr = new XMLHttpRequest();
			var fd = new FormData();

			xhr.open('POST', 'http://up.tietuku.com/', true);
			//xhr.setRequestHeader("Content-type", "multipart/form-data");

			xhr.addEventListener('readystatechange', (function(qid){
				return function(e) {
					if(xhr.readyState == 4) {
						if(xhr.status == 200) {
							var res=JSON.parse(xhr.responseText);
							var pic_size=set_pic_size(res.width, res.height);
							if(!res.code)
								fin= {
									'qid': qid,
									'status': 'success',
									'path': res.linkurl,
									'thumb': res.t_url,
									'name': res.name,
									'width': pic_size.width,
									'height': pic_size.height
								};
							after_upload(fin);
							upload_next();
						}
					}
				}
			})(work.qid),false);

			xhr.upload.addEventListener('progress',function(e){
				if(e.lengthComputable) {
					var percentage = e.loaded/e.total;
					self.progress(percentage);
				}
			},false);
			xhr.upload.addEventListener('load',function(e){
				self.progress(1);
			},false);

			fd.append('Token',ttktoken);
			if(method == 'file') {
				fd.append('file',work.fileobj);
			}else if(method == 'url') {
				fd.append('fileurl',work.path);
			}

			xhr.send(fd);
		}
	})(work,self));
}

orig_url_upload=url_upload;
url_upload=function(work) {
	var self = document.getElementById('q'+work.qid);
	if(!self) {
		var callself = function(){url_upload(work);};
		setTimeout(callself,100);
		return false;
	}else {
		ttk_upload(work,self,'url');
	}
}

orig_file_upload=file_upload;
file_upload=function(work) {
	var self = document.getElementById('q'+work.qid);
	if(!self) {
		var callself = function(){file_upload(work);};
		setTimeout(callself,100);
		return false;
	}else {
		ttk_upload(work,self,'file');
	}
}
