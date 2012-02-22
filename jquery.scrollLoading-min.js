/*
 * jquery.scrollLoading.js
 * by zhangxinxu  http://www.zhangxinxu.com
 * 2010-11-19 v1.0
*/
(function(a){a.fn.scrollLoading=function(b){var c={attr:"data-url"};var d=a.extend({},c,b||{});d.cache=[];a(this).each(function(){var g=this.nodeName.toLowerCase(),f=a(this).attr(d.attr);if(!f){return}var h={obj:a(this),tag:g,url:f};d.cache.push(h)});var e=function(){var f=a(window).scrollTop(),g=f+a(window).height();a.each(d.cache,function(k,l){var m=l.obj,h=l.tag,j=l.url;if(m){post=m.position().top;posb=post+m.height();if((post>f&&post<g)||(posb>f&&posb<g)){if(h==="img"){m.attr("src",j)}else{m.load(j)}l.obj=null}}});return false};e();a(window).bind("scroll",e)}})(jQuery);