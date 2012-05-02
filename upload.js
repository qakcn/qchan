ajaxid=0;

// Handler for url upload
function url_upload(urls,urltype) {
	var isthumb=$('input#normalisthumb').val();
	var thumbsize=$('input#normalthumbsize').val();
	for(var i=0,url;i<urls.length;i++,ajaxid++) {
		url=urls[i];
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
		}else if(!isempty(url)) {
				$('div#result').show();
				$(document).scrollTop($('div#result').offset().top);
				$('<li class="imgfail" style="opacity: 0;"><div class="errortitle">'+$('div#result').attr('data-err-title')+'</div><div class="errorname"><em>'+$('<div />').text(url).html()+'</em></div><div class="errormsg">'+$('div#result').attr('data-err-noturl')+'</div></li>').prependTo('ul#resultlist').animate({opacity:1});
		}
	}
	$('textarea#urllist').val('');
}

//Handler for drag and drop upload
function drop_upload(flist) {
	var isthumb=$('input#normalisthumb').val();
	var thumbsize=$('input#normalthumbsize').val();
	var errtitle=$('div#result').attr('data-err-title');
	var errname=$('div#result').attr('data-err-name');
	var errtype=$('div#result').attr('data-err-notype');
	var errtoobig=$('div#result').attr('data-err-toobig');
	for(var i=0, f;i<flist.length;i++,ajaxid++) {
		f=flist[i]
		$('div#result').show();
		$(document).scrollTop($('div#result').offset().top);
		if(!f.type.match(/image\/(jpeg|png|gif|svg\+xml)/)) {
			// File type wrong
			$('<li class="imgfail" style="opacity: 0;"><div class="errortitle">'+errtitle+'</div><div class="errorname">'+errname+'<em>'+$('<div />').text(f.name).html()+'</em></div><div class="errormsg">'+errtype+'</div>').prependTo('ul#resultlist').animate({opacity:1});
			continue;
		}else	if(f.size > $('#dropbox').attr('data-sizelimit')) {
			// File too large
			$('<li class="imgfail" style="opacity: 0;"><div class="errortitle">'+errtitle+'</div><div class="errorname">'+errname+'<em>'+$('<div />').text(f.name).html()+'</em></div><div class="errormsg">'+errtoobig+'</div>').prependTo('ul#resultlist').animate({opacity:1});
			continue;
		}else if(i>19){
			alert($('div#result').attr('data-err-toomany'));
			break;
		}else {
			$('<li class="working" style="opacity: 0;" id="ajax-'+ ajaxid +'"><div class="errortitle">'+errtitle+'</div><div class="errorname">'+errname+'<em>'+$('<div />').text(f.name).html()+'</em></div><div class="errormsg">'+$('div#result').attr('data-upload-info')+'</div></li>').prependTo('ul#resultlist').animate({opacity:1});
			
			var reader = new FileReader();			
			reader.onload = (function(theFile,theNum,theSize,isThumb) {
				return function(e) {
					$.post('ajax.php',{
						type:'drop',
						name:theFile.name,
						is_thumb:isThumb,
						thumb_size:theSize,
						file:e.target.result
					},(function(id){
						return function(rcv){
							$('li.working#ajax-'+id).animate({opacity:0}).replaceWith(rcv).animate({opacity:1});
						}
					})(theNum),'html');
				}
			})(f,ajaxid,thumbsize,isthumb);
			reader.readAsDataURL(f);
		}
	}
}

// Check if is URL or empty
function isurl(theurl) {
	return /^\s*https?:\/\//.test(theurl);
}

function isempty(theurl) {
	return /^\s*$/.test(theurl);
}