$(function(){
	// Initialization
	minsize=Number($('input#thumb_size').attr('min'));
	maxsize=Number($('input#thumb_size').attr('max'));
	defaultsize=Number($('input#thumb_size').attr('data-default'));
	initsize=Number($('input#thumb_size').attr('data-init'));
	thumbsizerange=maxsize-minsize;
	$('input#thumb_size').val(initsize);
	$('input#normalthumbsize').val(initsize);
	if($('input#is_thumb').attr('data-init')=='yes') {
		$('input#is_thumb').prop('checked',true);
		$('input#normalisthumb').val('yes');
	}else if($('input#is_thumb').attr('data-init')=='no') {
		$('input#is_thumb').prop('checked',false);
		$('input#normalisthumb').val('no');
	}
	
	// Event handler for thumbnail size change
	$('input#thumb_size').on('change',function(){
		nowsize=$('input#thumb_size').val();
		if(!nowsize.match(/\d+/)) {
			$('input#thumb_size').val(defaultsize);
			$('input#normalthumbsize').val(defaultsize);
		}
		nowsize=Number($('input#thumb_size').val());
		if(nowsize>maxsize) {
			nowsize=maxsize;
			$('input#thumb_size').val(maxsize);
			$('input#normalthumbsize').val(maxsize);
		}else if(nowsize<minsize) {
			nowsize=minsize;
			$('input#thumb_size').val(minsize);
			$('input#normalthumbsize').val(minsize);
		}
		$('span.thumb_size_output').html(nowsize);
		$('input#normalthumbsize').val(nowsize);
		clickX=500*(nowsize-minsize)/thumbsizerange;
		$('div#tssliderbar').animate({width:clickX},200);
		
	});
	
	// Event handler for thumbnail switch change
	$('input#is_thumb').on('change',function(){
		if($('input#is_thumb').prop('checked')) {
			$('span.is_thumb_checked').html($('span.is_thumb_checked').attr('data-yes'));
			$('input#normalisthumb').val('yes');
		}else {
			$('span.is_thumb_checked').html($('span.is_thumb_checked').attr('data-no'));
			$('input#normalisthumb').val('no');
	}
	});
	
	// Method change
	$(document).on('click','div.collapsed',function(){
		if($('div.expanded').length > 0) {
			$('div.expanded').animate({width:'38px'},300,function(){
				$(this).addClass('collapsed');
			}).removeClass('expanded').children('div.userhandle').hide();
			$(this).animate({width:'916px'},300,function(){
				$(this).addClass('expanded').children('div.userhandle').fadeIn();
				if($(this).is('div#url')) {
					setCookie('lastest_upload_method','url',30);
				}else {
					setCookie('lastest_upload_method','normal',30);
				}
			}).removeClass('collapsed');
		}
	});
	
	// Settings switch
	$('div#seticon').on('click',function(){
		if($(this).hasClass('off')) {
			$('div#settings').animate({height:'300px',width:'700px'},300,function(){$('div#param').fadeIn();});
		}else {
			$('div#param').hide();
			$('div#settings').animate({height:'38px',width:'38px'},300);
		}
		$(this).toggleClass('off');
	});
	
	// Reset settings
	$('button#reset_settings').on('click',function(){
		if($('input#is_thumb').attr('data-default')=='yes') {
			$('input#is_thumb').prop('checked',true);
		}else if($('input#is_thumb').attr('data-default')=='no') {
			$('input#is_thumb').prop('checked',false);
		}
		$('input#thumb_size').val(defaultsize);
		$('input#thumb_size').trigger('change');
		$('input#is_thumb').trigger('change');
	});
	
	// User tips switch
	$('div#usertipsicon').on('click',function(){
		$('div#usertips').slideToggle(300);
	});
	
	// Thumbnail size slider event handler
	bardrag=false;
	$('div#tsslider').on('mousedown',function(e){
		clickX=e.clientX-$(this).offset().left-42;
		if(clickX<0) clickX=0;
		if(clickX>500) clickX=500;
		nowsize=Math.round(clickX/500*thumbsizerange)+minsize;
		$('div#tssliderbar').animate({width:clickX},200);
		$('input#thumb_size').val(nowsize).trigger('change.bar');
		$('input#normalthumbsize').val(nowsize);
		$('span.thumb_size_output').html(nowsize);
		bardrag=true;
	});
	$('div#tsslider').on('mousemove',function(e){
		if(bardrag) {
			clickX=e.clientX-$(this).offset().left-42;
			if(clickX<0) clickX=0;
			if(clickX>500) clickX=500;
			nowsize=Math.round(clickX/500*thumbsizerange)+minsize;
			$('div#tssliderbar').stop().width(clickX);
			$('input#thumb_size').val(nowsize).trigger('change.bar');
			$('input#normalthumbsize').val(nowsize);
			$('span.thumb_size_output').html(nowsize);
		}
	});
	$('div#tsslider').on('mouseup',function(){
		bardrag=false;
	});
	$('div#tsslider').on('mouseleave',function(){
		bardrag=false;
	});
	
	// Add one more file for normal upload
	$('button#normaladd').on('click',function(){
		nomorefiles=$(this).attr('data-nomore');
		remove=$(this).attr('data-remove');
		if(!$(this).hasClass('disabled')) {
			$('ul#filelist').append('<li><button class="normalremove" type="button">'+remove+'</button><input type="file" name="files[]" accept="image"></li>');
			if($('ul#filelist li').length >= 10) {
				$(this).toggleClass('disabled').html(nomorefiles);
			}
		}
	});
	
	// Remove one file for normal upload
	$(document).on('click', 'button.normalremove', function(e){
		morefiles=$('button#normaladd').attr('data-more');
		if($('button#normaladd').hasClass('disabled')) {
			$('button#normaladd').toggleClass('disabled').html(morefiles);
		}
		if($('ul#filelist li').length > 1) {
			$(e.currentTarget).parent('li').remove();
		}else if($('ul#filelist li').length == 1) {
			$(e.currentTarget).parent('li').children('input').val('');
		}
	});
	
	// Auto expansion for result
	$(document).on('mouseenter','input.url',function(){
		$(this).prev('label').hide();
	});
	$(document).on('mouseleave','input.url',function(){
		$(this).prev('label').show();
	});
	$(document).on('click','input.url',function(){
		$(this).select();
	});
	
	// Event for URL upload
	$('button#urlsubmit').on('click', function(){
		if($('ul#resultlist li.working').length > 0) {
			alert($('div#result').attr('data-err-chottomatte'));
		}else {
			url_upload($('textarea#urllist').val().split('\n'),'url');
		}
	});
	
	// Event for grab image though GFW
	$('button#urlgrab').on('click', function(){
		if($('ul#resultlist li.working').length > 0) {
			alert($('div#result').attr('data-err-chottomatte'));
		}else {
			url_upload($('textarea#urllist').val().split('\n'),'grab');
		}
	});
	
	// Event for clear URL textarea
	$('button#urlclear').on('click', function(){
		$('textarea#urllist').val('');
	});
	
	// Prevent default handle for drag and drop
	$(document).on('dragenter',function(e){
		e.stopPropagation();
		e.preventDefault();
	});
	$(document).on('dragover',function(e){
		e.stopPropagation();
		e.preventDefault();
	});
	
	// Event fot drag and drop upload
	$(document).on('drop', function(e){
		e.stopPropagation();
		e.preventDefault();
		if($('ul#resultlist li.working').length > 0) {
			alert($('div#result').attr('data-err-chottomatte'));
		}else {
			drop_upload(e.originalEvent.dataTransfer.files);
		}
	});
});

// Function to set cookie
function setCookie(c_name,value,expiredays) {
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+";expires=0";
	document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}