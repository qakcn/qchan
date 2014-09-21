$(function(){
	
$('#main_header').on('click',function(e){
	$('section#main').scrollTop(0);
});

// Open upload popup
$('#upload_button,#add').on('click',function(e){
	$('#upload_popup').show();
	if($('#upload_button').hasClass('close')) {
		$('#closepop').trigger('click');
	}else {
		$('#upload_button').addClass('close')
	}
});

// Upload popup event
window.method = 'normal';

// Change method
$('#url').on('click',function(e){
	$('#url').removeClass('noact');
	$('#normal').addClass('noact');
	$('#url_zone').show();
	$('#normal_zone').hide();
	window.method = 'url';
});
$('#normal').on('click',function(e){
	$('#url').addClass('noact');
	$('#normal').removeClass('noact');
	$('#url_zone').hide();
	$('#normal_zone').show();
	window.method = 'normal';
});

// Select files
$('#file_select').on('click',function(e) {
	$('#file_list').trigger('click');
});

// Mobile page show submit
$('#url_list').on('keyup',function(e){
	$('#submit').addClass('show');
});
$('#file_list').on('change',function(e){
	$('#submit').addClass('show');
	$('#file_review').html('');
	for(var file,i=0;i<this.files.length;i++) {
		file=this.files[i];
		$('#file_review').append('<p>'+file.name+'</p>');
	}
});

// Close popup
$('#closepop').on('click',function(e){
	$('#upload_popup').hide();
	$('#upload_button').removeClass('close')
});

// Submit
$('#submit').on('click',function(e){
	$('#closepop').trigger('click');
	$('#first_load').remove();
	$('#submit').removeClass('show');
	if(window.method == 'url') {
		var urls = $('#url_list').val().split('\n');
		url_upload_handler(urls);
		$('#url_list').val('');
	}else if(window.method=='normal') {
		if(FileReader && FormData) {
			var files = $('#file_list').attr('files');
			file_upload_handler(files);
			$('#file_review').html('');
			$('#file_list').val('');
		}else {
			normal_upload_handler();
		}
	}
});

/* Drag and drop event listener */
$(document).on('dragenter', function(e) {
	e.stopPropagation();
	e.preventDefault();
});
$(document).on('dragover', function(e) {
	e.stopPropagation();
	e.preventDefault();
});
$(document).on('drop', function(e) {
	e.stopPropagation();
	e.preventDefault();
	$('#closepop').trigger('click');
	$('#first_load').remove();
	var files = e.dataTransfer.files;
	if(FileReader && FormData) {
		file_upload_handler(files);
	}else {
		alert(ui_msg.info.undrop);
	}
});

});

/* Put thumbnail in the result zone */
function show_thumbnail(work) {
	var thmli = $('<li></li>').attr('id', 'q'+work.qid).prop('draggable',true); //list item
	var thmprg = $('<div></div>').addClass('progress'); //div for show progress
	var thmimg = $('<div></div>').addClass('img'); //div for show thumbnail
	var thmsel =$('<div></div>').addClass('select').html('<p>'+ui_msg.info.selected+'</p>'); //div for show selected box
	var thmi = $('<img>'); //img for get image width and height
	if(work.type=='url') {
		thmi.attr('src',work.path);
		var thmishow=function(work,thmli,thmimg,thmprg,thmsel) {
			return function(e) {
				show_thumbnail_part_b(work,thmli,thmimg,thmprg,thmsel,this);
			}
		};
		thmi.on('error',thmishow(work,thmli,thmimg,thmprg,thmsel)).on('load',thmishow(work,thmli,thmimg,thmprg,thmsel));

	}else if(work.type=='file') {
		var fr;
		if(fr = new FileReader ) {
			fr.onload = (function(work,thmli,thmimg,thmprg,thmsel,thmi){
				return function(e) {
					thmi.attr('src',e.target.result);
					var thmishow=function(work,thmli,thmimg,thmprg,thmsel) {
						return function(e) {
							show_thumbnail_part_b(work,thmli,thmimg,thmprg,thmsel,this);
						}
					};
					thmi.on('error',thmishow(work,thmli,thmimg,thmprg,thmsel)).on('load',thmishow(work,thmli,thmimg,thmprg,thmsel));
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
		thmimg.css('background-image','url('+prop.error_image+')');
	}else if(height_orig>height || width_orig>width) {
		var ratio_orig = width_orig/height_orig;
		if (width/height > ratio_orig) {
			width = height*ratio_orig;
		}else {
			height = width/ratio_orig;
		}
		thmimg.css('background-image','url("'+thmi.src+'")');
	}else {
		width = width_orig;
		height = height_orig;
		thmimg.css('background-image','url("'+thmi.src+'")');
	}
	thmimg.css('background-size',width+'px '+height+'px');
	thmprg.css('background-position', '0px center');
	thmli.width(width+'px').height(height+'px').append(thmimg.append(thmprg.append(thmsel))).prop('work',work).on('contextmenu', toggleinfo).on('click', toggleinfo);//.css('margin-top', (205 - height)/2+'px').css('margin-bottom', (205 - height)/2+'px')
	
	/* progress handler */
	thmli[0].progress = progress(thmprg);
	$('#result_zone').append(thmli);
}

function progress(thmprg) {
	return function(percent) {
		var pos = thmprg.width() * percent;
		thmprg.animate({'background-position': pos+'px center'});
	}
}

window.lastselected=null;
function toggleinfo(e) {
	e.preventDefault();
	if(e.type=='click') {
		if(e.shiftKey && window.lastselected) {
			var start=window.lastselected.id.slice(1);
			var end=this.id.slice(1);
			if(-start>-end) {
				
			}else {
				var c = start
				start = end;
				end = c;
			}
			for(var i=start;i <= end;i++) {
				$('#q'+i).addClass('selected');
			}
			window.lastselected = this;
		}else {
			if($(this).is('.selected') && $('.selected').length == 1) {
				$(this).removeClass('selected');
				window.lastselected = null;
			}else if($(this).is('.selected') && $('.selected').length != 1) {
				$('.selected').not(this).removeClass('selected');
				window.lastselected = this;
			}else {
				$('.selected').removeClass('selected');
				$(this).addClass('selected');
				window.lastselected = this;
			}
		}
	}else if(e.type=='contextmenu'){
		if($(this).hasClass('selected')) {
			window.lastselected=null;
			$(this).removeClass('selected');
		}else {
			window.lastselected=this;
			$(this).addClass('selected');
		}
	}
	changeinfo();
}

function changeinfo() {
	var selected = $('.selected');
	var info_zone = $('#info_zone');
	var main_zone = $('#main');
	info_zone.html('').addClass('hide');
	main_zone.removeClass('show');
	
	var namep = $('<h1></h1>').addClass('hide_mobile');
	var statusp = $('<p></p>').addClass('hide_mobile');
	var infop = $('<p></p>');

	if(selected.length>0) {
		setTimeout(function(){
			if(selected.length==1) {
				var work = selected[0].work;
				namep.html(work.path);
				statusp.html(ui_msg.status[work.status]);
				switch(work.status) {
					case 'error':
					case 'failed':
						infop.html(ui_msg.err_detail[work.error]);
						break;
					case 'success':
						namep.html(work.name);
						infop.addClass('result_info').html(single_format(work));
						break;
				}
			}else if(selected.length>1) {
				namep.html(selected.length + ui_msg.info.files_selected);
				var works = new Array();
				for(var work,i=0;i<selected.length;i++) {
					work = selected[i].work;
					if(work.status=='success') {
						works.push(work);
					}
				}
				if(works.length == 0) {
					statusp.html(ui_msg.status.all_failed);
				}else if(works.length == selected.length) {
					statusp.html(ui_msg.status.all_success);
					infop.addClass('result_info');
					multi_format(works,infop);
				}else if(works.length < selected.length) {
					statusp.html(ui_msg.status.part_success);
					infop.addClass('result_info');
					multi_format(works,infop);
				}
		
			}
			info_zone.removeClass('hide').append(namep).append(statusp).append(infop);
			main_zone.addClass('show');
		},200);
	}
}

function single_format(work) {
	var output = '<input type="text" id="orig-'+work.qid+'" value="'+work.path+'" onfocus="this.select()" readonly><label for="orig-'+work.qid+'">'+ui_msg.info.orig+'</label><br>'+
	'<input type="text" id="html-'+work.qid+'" value="&lt;img src=&quot;'+work.path+'&quot;&gt;" onfocus="this.select()" readonly><label for="html-'+work.qid+'">'+ui_msg.info.html+'</label><br>';
	if(work.thumb != 'none') {
		output += '<input type="text" id="htmlthm-'+work.qid+'" value="&lt;a href=&quot;'+work.path+'&quot; title=&quot;'+ui_msg.info.thumb_tips+'&quot;&gt;&lt;img src=&quot;'+work.thumb+'&quot;&gt;&lt;/a&gt;" onfocus="this.select()" readonly><label for="htmlthm-'+work.qid+'">'+ui_msg.info.html_with_thumb+'</label><br>';
	}
	output += '<input type="text" id="bbc-'+work.qid+'" value="[img]'+work.path+'[/img]" onfocus="this.select()" readonly><label for="bbc-'+work.qid+'">'+ui_msg.info.bbcode+'</label><br>';
	if(work.thumb != 'none') {
		output += '<input type="text" id="bbcthm-'+work.qid+'" value="[url='+work.path+'][img]'+work.thumb+'[/img][/url]" onfocus="this.select()" readonly><label for="bbcthm-'+work.qid+'">'+ui_msg.info.bbcode_with_thumb+'</label>';
	}
	return output;
}

function multi_format(works,infop) {
	var result_area = $('<textarea></textarea>');
	result_area.on('focus', function(){this.select()}).prop('t',{torig: '', thtml: '', thtmlthm: '', tbbcode: '', tbbcodethm: ''});
	var nothm_cnt = 0;
	for(var work,i=0;i<works.length;i++) {
		work = works[i];
		result_area.prop('t').torig += work.path + '\n';
		result_area.prop('t').thtml += '<img src="'+work.path+'">' + '\n';
		result_area.prop('t').tbbcode += '[img]'+work.path+'[/img]' + '\n';
		if(work.thumb!='none') {
			result_area.prop('t').thtmlthm += '<a href="'+work.path+'" title="'+ui_msg.info.thumb_tips+'"><img src="'+work.thumb+'"></a>' + '\n';
			result_area.prop('t').tbbcodethm += '[url='+work.path+'][img]'+work.thumb+'[/img][/url]' + '\n';
		}else {
			nothm_cnt++;
			result_area.prop('t').thtmlthm += '<img src="'+work.path+'">' + '\n';
			result_area.prop('t').tbbcodethm += '[img]'+work.path+'[/img]' + '\n';
		}
	}
	result_area.val(result_area.prop('t').torig);
	result_area.prop('readOnly', true);
	var lorig = $('<label></label>');
	var lhtml = $('<label></label>');
	var lbbcode = $('<label></label>');
	lorig.html(ui_msg.info.orig);
	lhtml.html(ui_msg.info.html);
	lbbcode.html(ui_msg.info.bbcode);
	result_area[0].selectedl=lorig;
	lorig.addClass('actived multi');
	lhtml.addClass('multi');
	lbbcode.addClass('multi');
	lorig.on('click', (function(ra) {
		return function() {
			result_area[0].selectedl.removeClass('actived');
			result_area[0].selectedl=$(this);
			$(this).addClass('actived');
			result_area.val(result_area.prop('t').torig);
		}
	})(result_area));
	lhtml.on('click', (function(ra) {
		return function() {
			result_area[0].selectedl.removeClass('actived');
			result_area[0].selectedl=$(this);
			$(this).addClass('actived');
			result_area.val(result_area.prop('t').thtml);
		}
	})(result_area));
	lbbcode.on('click', (function(ra) {
		return function() {
			result_area[0].selectedl.removeClass('actived');
			result_area[0].selectedl=$(this);
			$(this).addClass('actived');
			result_area.val(result_area.prop('t').tbbcode);
		}
	})(result_area));
	if(nothm_cnt == works.length) {
		result_area.prop('t').thtmlthm = undefined;
		result_area.prop('t').tbbcodethm = undefined;
	}else {
		var lhtmlthm = $('<label></label>');
		var lbbcodethm = $('<label></label>');
		lhtmlthm.html(ui_msg.info.html_with_thumb);
		lbbcodethm.html(ui_msg.info.bbcode_with_thumb);
		lhtmlthm.addClass('multi');
		lbbcodethm.addClass('multi');
		lhtmlthm.on('click', (function(ra) {
			return function() {
			result_area[0].selectedl.removeClass('actived');
			result_area[0].selectedl=$(this);
			$(this).addClass('actived');
			result_area.val(result_area.prop('t').thtmlthm);
			}
		})(result_area));
		lbbcodethm.on('click', (function(ra) {
			return function() {
			result_area[0].selectedl.removeClass('actived');
			result_area[0].selectedl=$(this);
			$(this).addClass('actived');
			result_area.val(result_area.prop('t').tbbcodethm);
			}
		})(result_area));
	}
	infop.append(result_area).append(lorig).append(lhtml);
	if(nothm_cnt < works.length) {
		infop.append(lhtmlthm);
	}
	infop.append(lbbcode);
	if(nothm_cnt < works.length) {
		infop.append(lbbcodethm);
	}
}