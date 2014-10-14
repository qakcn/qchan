$(function(){
	scrollLoading();
	
	$('#main').on('scroll',scrollLoading);
	
	$('#main_header').on('click',function(e){
		$('section#main').scrollTop(0);
	});
	
	$('#next_page').on('click', function(){
		window.location.href = $('#page_select').data('url')+(parseInt($('#page_select').val())+1);
	});
	$('#page_select').on('change', function(){
		window.location.href = $('#page_select').data('url')+$('#page_select').val();
	});
	$('#prev_page').on('click', function(){
		window.location.href = $('#page_select').data('url')+(parseInt($('#page_select').val())-1);
	});
	
	$('#view').on('click', function(){
		var selected = $('.selected');
		for(var i=0;i<selected.length;i++) {
			var work = selected.eq(i).prop('work');
			window.open(work.path);
		}
	});
	
	$('#delete').on('click', function(){
		var files=$('.selected');
	
		var xhr = new XMLHttpRequest();
	
		xhr.open('POST', 'api.php?action=delete&'+(new Date()).getTime(), true);
		//xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
		var sendData = '';
	
		var works=new Array;
		for(var i=0;i<files.length; i++) {
			works.push(files.eq(i).prop('work'));
		}
	
		var sendData = JSON.stringify(works);
	
		xhr.addEventListener('readystatechange', function(e){
			if(xhr.readyState == 4) {
				if(xhr.status == 200) {
					var res=JSON.parse(xhr.responseText);
					for(id in res) {
						if(res[id] == 'deleted' || res[id] == 'thumbdelfail') {
							$('#'+id).remove();
							setTimeout(changeinfo, 100);
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
	});
});

function movelongend(e){
	if(!$(this).hasClass('long')) return false;
	var direction = $(this).data('direction');
	var thmimg = $(this).children('.img');
	var position = window.getComputedStyle(thmimg[0]).backgroundPosition;
	if(direction == 'ltr') {
		if(position.match(/^(.+)(%|px) .+$/)[1]*1==100) $(this).data('direction', 'rtl');
		thmimg.css('background-position', position);
	}else if(direction == 'rtl') {
		if(position.match(/^(.+)(%|px) .+$/)[1]*1==0) $(this).data('direction', 'ltr');
		thmimg.css('background-position', position);
	}else if(direction == 'ttb') {
		if(position.match(/^.+ (.+)(%|px)$/)[1]*1==100) $(this).data('direction', 'btt');
		thmimg.css('background-position', position);
	}else if(direction == 'btt') {
		if(position.match(/^.+ (.+)(%|px)$/)[1]*1==0) $(this).data('direction', 'ttb');
		thmimg.css('background-position', position);
	}
}
function movelongstart(e){
	if(!$(this).hasClass('long')) return false;
	var direction = $(this).data('direction');
	var thmimg = $(this).children('.img');
	var position = window.getComputedStyle(thmimg[0]).backgroundPosition;
	var duration=0;
	
	thmimg.css('transition-timing-function', 'linear'); //Fuck Chrome
	if(direction == 'ltr') {
		duration = (100 - position.match(/^(.+)(%|px) .+$/)[1]) * 0.03;
		thmimg.css('transition-duration',duration+'s').css('background-position', '100% 0');
	}else if(direction == 'rtl') {
		duration = (position.match(/^(.+)(%|px) .+$/)[1]*1) * 0.03;
		thmimg.css('transition-duration',duration+'s').css('background-position', '0 0');
	}else if(direction == 'ttb') {
		duration = (100 - position.match(/^.+ (.+)(%|px)$/)[1]) * 0.03;
		thmimg.css('transition-duration',duration+'s').css('background-position', '0 100%');
	}else if(direction == 'btt') {
		duration = (position.match(/^.+ (.+)(%|px)$/)[1]*1) * 0.03;
		thmimg.css('transition-duration',duration+'s').css('background-position', '0 0');
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
				$('#n'+i).addClass('selected');
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
	info_zone.addClass('hide');
	main_zone.removeClass('show');
	
	var namep = $('#namep');

	if(selected.length>0) {
		setTimeout(function(){
			if(selected.length==1) {
				var work = selected[0].work;
				namep.html(work.name);
			}else if(selected.length>1) {
				namep.html(selected.length + files_selected);
			}
			info_zone.removeClass('hide');
			main_zone.addClass('show');
		},100);
	}
}

function scrollLoading(){
	var scrollmax,unloadimgs;
	var main = $('#main')[0];
	if(main.scrollTopMax) {
		scrollmax = main.scrollTopMax
	}else {
		scrollmax = main.scrollHeight - main.clientHeight;
	}
	unloadimgs = $('.scroll-load');
	for(var i=0;i<unloadimgs.length;i++) {
		var thisimg = unloadimgs.eq(i);
		if(thisimg[0].offsetTop - main.scrollTop < main.clientHeight - 20 && thisimg[0].offsetTop > main.scrollTop - 180) {
			thisimg.removeClass('scroll-load').children().css('background-image', 'url("' + thisimg.data('thumb') + '")');
		}
	}
}