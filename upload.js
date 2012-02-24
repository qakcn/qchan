ajaxid=0;

// Handle for url upload
function url_upload(urls,urltype) {
	var isthumb=$('input#normalisthumb').val();
	var thumbsize=$('input#normalthumbsize').val();
	for(var i=0,url;url=urls[i];i++,ajaxid++) {
		if(isurl(url)) {
			$('div#result').show();
			$(document).scrollTop($('div#result').offset().top);
			$('<li class="working" style="opacity: 0;" id="ajax-'+ ajaxid +'"><div class="errortitle">'+$('div#result').attr('data-upload-title')+'</div><div class="errorname"><em>'+$('<div />').text(url).html()+'</em></div><div class="errormsg">'+$('div#result').attr('data-upload-info')+'</div></li>').prependTo('ul#resultlist').animate({opacity:1});
			$.post('ajax.php',{
				type:urltype,
				addr:url,
				is_thumb:isthumb,
				thumb_size:thumbsize
			},(function(id){
				return function(rcv){
					$('li.working#ajax-'+id).animate({opacity:0}).replaceWith(rcv).animate({opacity:1});
				}
			})(ajaxid),'html');
		}
	}
	$('textarea#urllist').val('');
}

// Check if is URL or empty
function isurl(url) {
	var isit=/^\s*https?:\/\//.test(url);
	var isempty=/^\s*$/.test(url);
	if(!isit && !isempty) {
		$('div#result').show();
		$(document).scrollTop($('div#result').offset().top);
		$('<li class="imgfail" style="opacity: 0;"><div class="errortitle">'+$('div#result').attr('data-err-title')+'</div><div class="errorname"><em>'+$('<div />').text(url).html()+'</em></div><div class="errormsg">'+$('div#result').attr('data-err-noturl')+'</div></li>').prependTo('ul#resultlist').animate({opacity:1});
	}
	return isit;
}