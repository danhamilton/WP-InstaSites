;

/* Weather */
;(function(i){i.fn.weatherfeed=function(o,h,t){var h=i.extend({unit:"c",image:!0,country:!1,highlow:!0,wind:!0,humidity:!1,visibility:!1,sunrise:!1,sunset:!1,forecast:!1,link:!0,showerror:!0,linktarget:"_self",woeid:!1},h),p="odd";return this.each(function(m,q){var k=i(q);k.hasClass("weatherFeed")||k.addClass("weatherFeed");if(!i.isArray(o))return!1;var l=o.length;10<l&&(l=10);for(var j="",m=0;m<l;m++)""!=j&&(j+=","),j+="'"+o[m]+"'";now=new Date;l="http://query.yahooapis.com/v1/public/yql?q="+encodeURIComponent("select * from weather.forecast where "+
(h.woeid?"woeid":"location")+" in ("+j+") and u='"+h.unit+"'")+"&rnd="+now.getFullYear()+now.getMonth()+now.getDay()+now.getHours()+"&format=json&callback=?";i.ajax({type:"GET",url:l,dataType:"json",success:function(f){if(f.query){if(0<f.query.results.channel.length)for(var c=f.query.results.channel.length,e=0;e<c;e++)u(q,f.query.results.channel[e],h);else u(q,f.query.results.channel,h);i.isFunction(t)&&t.call(this,k)}else h.showerror&&k.html("<p>Weather information unavailable</p>")},error:function(){h.showerror&&
k.html("<p>Weather request failed</p>")}});var u=function(f,c,e){var f=i(f),a=c.wind.direction;348.75<=a&&360>=a&&(a="N");0<=a&&11.25>a&&(a="N");11.25<=a&&33.75>a&&(a="NNE");33.75<=a&&56.25>a&&(a="NE");56.25<=a&&78.75>a&&(a="ENE");78.75<=a&&101.25>a&&(a="E");101.25<=a&&123.75>a&&(a="ESE");123.75<=a&&146.25>a&&(a="SE");146.25<=a&&168.75>a&&(a="SSE");168.75<=a&&191.25>a&&(a="S");191.25<=a&&213.75>a&&(a="SSW");213.75<=a&&236.25>a&&(a="SW");236.25<=a&&258.75>a&&(a="WSW");258.75<=a&&281.25>a&&(a="W");
281.25<=a&&303.75>a&&(a="WNW");303.75<=a&&326.25>a&&(a="NW");326.25<=a&&348.75>a&&(a="NNW");var g=c.item.forecast[0];wpd=c.item.pubDate;n=wpd.indexOf(":");tpb=s(wpd.substr(n-2,8));tsr=s(c.astronomy.sunrise);tss=s(c.astronomy.sunset);daynight=tpb>tsr&&tpb<tss?"day":"night";var b='<div class="weatherItem '+p+" "+daynight+'"';e.image&&(b+=' style="background-image: url(http://l.yimg.com/a/i/us/nws/weather/gr/'+c.item.condition.code+daynight.substring(0,1)+'.png); background-repeat: no-repeat;"');b=b+
">"+('<div class="weatherCity">'+c.location.city+"</div>");e.country&&(b+='<div class="weatherCountry">'+c.location.country+"</div>");b+='<div class="weatherTemp">'+c.item.condition.temp+"&deg;</div>";b+='<div class="weatherDesc">'+c.item.condition.text+"</div>";e.highlow&&(b+='<div class="weatherRange">High: '+g.high+"&deg; Low: "+g.low+"&deg;</div>");e.wind&&(b+='<div class="weatherWind">Wind: '+a+" "+c.wind.speed+c.units.speed+"</div>");e.humidity&&(b+='<div class="weatherHumidity">Humidity: '+
c.atmosphere.humidity+"</div>");e.visibility&&(b+='<div class="weatherVisibility">Visibility: '+c.atmosphere.visibility+"</div>");e.sunrise&&(b+='<div class="weatherSunrise">Sunrise: '+c.astronomy.sunrise+"</div>");e.sunset&&(b+='<div class="weatherSunset">Sunset: '+c.astronomy.sunset+"</div>");if(e.forecast){b+='<div class="weatherForecast">';a=c.item.forecast;for(g=0;g<a.length;g++)b+='<div class="weatherForecastItem" style="background-image: url(http://l.yimg.com/a/i/us/nws/weather/gr/'+a[g].code+
's.png); background-repeat: no-repeat;">',b+='<div class="weatherForecastDay">'+a[g].day+"</div>",b+='<div class="weatherForecastDate">'+a[g].date+"</div>",b+='<div class="weatherForecastText">'+a[g].text+"</div>",b+='<div class="weatherForecastRange">High: '+a[g].high+" Low: "+a[g].low+"</div>",b+="</div>";b+="</div>"}e.link&&(b+='<div class="weatherLink"><a href="'+c.link+'" target="'+e.linktarget+'" title="Read full forecast">Full forecast</a></div>');p="odd"==p?"even":"odd";f.append(b+"</div>")},
s=function(f){d=new Date;return r=new Date(d.toDateString()+" "+f)}})}})(jQuery);

/* jTruncate */
;(function($){$.fn.jTruncate=function(h){var i={length:300,minTrail:20,moreText:"more",lessText:"less",ellipsisText:"...",moreAni:"",lessAni:""};var h=$.extend(i,h);return this.each(function(){obj=$(this);var a=obj.html();if(a.length>h.length+h.minTrail){var b=a.indexOf(' ',h.length);if(b!=-1){var b=a.indexOf(' ',h.length);var c=a.substring(0,b);var d=a.substring(b,a.length-1);obj.html(c+'<span class="truncate_ellipsis">'+h.ellipsisText+'</span>'+'<span class="truncate_more">'+d+'</span>');obj.find('.truncate_more').css("display","none");obj.append('<div class="clearboth">'+'<a href="#" class="truncate_more_link">'+h.moreText+'</a>'+'</div>');var e=$('.truncate_more_link',obj);var f=$('.truncate_more',obj);var g=$('.truncate_ellipsis',obj);e.click(function(){if(e.text()==h.moreText){f.show(h.moreAni);e.text(h.lessText);g.css("display","none")}else{f.hide(h.lessAni);e.text(h.moreText);g.css("display","inline")}return false})}}})}})(jQuery);

/* raty */
;(function(b){var a={init:function(c){return this.each(function(){a.destroy.call(this);this.opt=b.extend(true,{},b.fn.raty.defaults,c);var e=b(this),g=["number","readOnly","score","scoreName"];a._callback.call(this,g);if(this.opt.precision){a._adjustPrecision.call(this);}this.opt.number=a._between(this.opt.number,0,this.opt.numberMax);this.opt.path=this.opt.path||"";if(this.opt.path&&this.opt.path.slice(this.opt.path.length-1,this.opt.path.length)!=="/"){this.opt.path+="/";}this.stars=a._createStars.call(this);this.score=a._createScore.call(this);a._apply.call(this,this.opt.score);var f=this.opt.space?4:0,d=this.opt.width||(this.opt.number*this.opt.size+this.opt.number*f);if(this.opt.cancel){this.cancel=a._createCancel.call(this);d+=(this.opt.size+f);}if(this.opt.readOnly){a._lock.call(this);}else{e.css("cursor","pointer");a._binds.call(this);}if(this.opt.width!==false){e.css("width",d);}a._target.call(this,this.opt.score);e.data({settings:this.opt,raty:true});});},_adjustPrecision:function(){this.opt.targetType="score";this.opt.half=true;},_apply:function(c){if(c&&c>0){c=a._between(c,0,this.opt.number);this.score.val(c);}a._fill.call(this,c);if(c){a._roundStars.call(this,c);}},_between:function(e,d,c){return Math.min(Math.max(parseFloat(e),d),c);},_binds:function(){if(this.cancel){a._bindCancel.call(this);}a._bindClick.call(this);a._bindOut.call(this);a._bindOver.call(this);},_bindCancel:function(){a._bindClickCancel.call(this);a._bindOutCancel.call(this);a._bindOverCancel.call(this);},_bindClick:function(){var c=this,d=b(c);c.stars.on("click.raty",function(e){c.score.val((c.opt.half||c.opt.precision)?d.data("score"):this.alt);if(c.opt.click){c.opt.click.call(c,parseFloat(c.score.val()),e);}});},_bindClickCancel:function(){var c=this;c.cancel.on("click.raty",function(d){c.score.removeAttr("value");if(c.opt.click){c.opt.click.call(c,null,d);}});},_bindOut:function(){var c=this;b(this).on("mouseleave.raty",function(d){var e=parseFloat(c.score.val())||undefined;a._apply.call(c,e);a._target.call(c,e,d);if(c.opt.mouseout){c.opt.mouseout.call(c,e,d);}});},_bindOutCancel:function(){var c=this;c.cancel.on("mouseleave.raty",function(d){b(this).attr("src",c.opt.path+c.opt.cancelOff);if(c.opt.mouseout){c.opt.mouseout.call(c,c.score.val()||null,d);}});},_bindOverCancel:function(){var c=this;c.cancel.on("mouseover.raty",function(d){b(this).attr("src",c.opt.path+c.opt.cancelOn);c.stars.attr("src",c.opt.path+c.opt.starOff);a._target.call(c,null,d);if(c.opt.mouseover){c.opt.mouseover.call(c,null);}});},_bindOver:function(){var c=this,d=b(c),e=c.opt.half?"mousemove.raty":"mouseover.raty";c.stars.on(e,function(g){var h=parseInt(this.alt,10);if(c.opt.half){var f=parseFloat((g.pageX-b(this).offset().left)/c.opt.size),j=(f>0.5)?1:0.5;h=h-1+j;a._fill.call(c,h);if(c.opt.precision){h=h-j+f;}a._roundStars.call(c,h);d.data("score",h);}else{a._fill.call(c,h);}a._target.call(c,h,g);if(c.opt.mouseover){c.opt.mouseover.call(c,h,g);}});},_callback:function(c){for(i in c){if(typeof this.opt[c[i]]==="function"){this.opt[c[i]]=this.opt[c[i]].call(this);}}},_createCancel:function(){var e=b(this),c=this.opt.path+this.opt.cancelOff,d=b("<img />",{src:c,alt:"x",title:this.opt.cancelHint,"class":"raty-cancel"});if(this.opt.cancelPlace=="left"){e.prepend("&#160;").prepend(d);}else{e.append("&#160;").append(d);}return d;},_createScore:function(){return b("<input />",{type:"hidden",name:this.opt.scoreName}).appendTo(this);},_createStars:function(){var e=b(this);for(var c=1;c<=this.opt.number;c++){var f=a._getHint.call(this,c),d=(this.opt.score&&this.opt.score>=c)?"starOn":"starOff";d=this.opt.path+this.opt[d];b("<img />",{src:d,alt:c,title:f}).appendTo(this);if(this.opt.space){e.append((c<this.opt.number)?"&#160;":"");}}return e.children("img");},_error:function(c){b(this).html(c);b.error(c);},_fill:function(d){var m=this,e=0;for(var f=1;f<=m.stars.length;f++){var g=m.stars.eq(f-1),l=m.opt.single?(f==d):(f<=d);if(m.opt.iconRange&&m.opt.iconRange.length>e){var j=m.opt.iconRange[e],h=j.on||m.opt.starOn,c=j.off||m.opt.starOff,k=l?h:c;if(f<=j.range){g.attr("src",m.opt.path+k);}if(f==j.range){e++;}}else{var k=l?"starOn":"starOff";g.attr("src",this.opt.path+this.opt[k]);}}},_getHint:function(d){var c=this.opt.hints[d-1];return(c==="")?"":(c||d);},_lock:function(){var d=parseInt(this.score.val(),10),c=d?a._getHint.call(this,d):this.opt.noRatedMsg;b(this).data("readonly",true).css("cursor","").attr("title",c);this.score.attr("readonly","readonly");this.stars.attr("title",c);if(this.cancel){this.cancel.hide();}},_roundStars:function(e){var d=(e-Math.floor(e)).toFixed(2);if(d>this.opt.round.down){var c="starOn";if(this.opt.halfShow&&d<this.opt.round.up){c="starHalf";}else{if(d<this.opt.round.full){c="starOff";}}this.stars.eq(Math.ceil(e)-1).attr("src",this.opt.path+this.opt[c]);}},_target:function(f,d){if(this.opt.target){var e=b(this.opt.target);if(e.length===0){a._error.call(this,"Target selector invalid or missing!");}if(this.opt.targetFormat.indexOf("{score}")<0){a._error.call(this,'Template "{score}" missing!');}var c=d&&d.type=="mouseover";if(f===undefined){f=this.opt.targetText;}else{if(f===null){f=c?this.opt.cancelHint:this.opt.targetText;}else{if(this.opt.targetType=="hint"){f=a._getHint.call(this,Math.ceil(f));}else{if(this.opt.precision){f=parseFloat(f).toFixed(1);}}if(!c&&!this.opt.targetKeep){f=this.opt.targetText;}}}if(f){f=this.opt.targetFormat.toString().replace("{score}",f);}if(e.is(":input")){e.val(f);}else{e.html(f);}}},_unlock:function(){b(this).data("readonly",false).css("cursor","pointer").removeAttr("title");this.score.removeAttr("readonly","readonly");for(var c=0;c<this.opt.number;c++){this.stars.eq(c).attr("title",a._getHint.call(this,c+1));}if(this.cancel){this.cancel.css("display","");}},cancel:function(c){return this.each(function(){if(b(this).data("readonly")!==true){a[c?"click":"score"].call(this,null);this.score.removeAttr("value");}});},click:function(c){return b(this).each(function(){if(b(this).data("readonly")!==true){a._apply.call(this,c);if(!this.opt.click){a._error.call(this,'You must add the "click: function(score, evt) { }" callback.');}this.opt.click.call(this,c,{type:"click"});a._target.call(this,c);}});},destroy:function(){return b(this).each(function(){var d=b(this),c=d.data("raw");if(c){d.off(".raty").empty().css({cursor:c.style.cursor,width:c.style.width}).removeData("readonly");}else{d.data("raw",d.clone()[0]);}});},getScore:function(){var d=[],c;b(this).each(function(){c=this.score.val();d.push(c?parseFloat(c):undefined);});return(d.length>1)?d:d[0];},readOnly:function(c){return this.each(function(){var d=b(this);if(d.data("readonly")!==c){if(c){d.off(".raty").children("img").off(".raty");a._lock.call(this);}else{a._binds.call(this);a._unlock.call(this);}d.data("readonly",c);}});},reload:function(){return a.set.call(this,{});},score:function(){return arguments.length?a.setScore.apply(this,arguments):a.getScore.call(this);},set:function(c){return this.each(function(){var e=b(this),f=e.data("settings"),d=b.extend({},f,c);e.raty(d);});},setScore:function(c){return b(this).each(function(){if(b(this).data("readonly")!==true){a._apply.call(this,c);a._target.call(this,c);}});}};b.fn.raty=function(c){if(a[c]){return a[c].apply(this,Array.prototype.slice.call(arguments,1));}else{if(typeof c==="object"||!c){return a.init.apply(this,arguments);}else{b.error("Method "+c+" does not exist!");}}};b.fn.raty.defaults={cancel:false,cancelHint:"Cancel this rating!",cancelOff:"//bapi.s3.amazonaws.com/lib/raty/cancel-off.png",cancelOn:"//bapi.s3.amazonaws.com/lib/raty/cancel-on.png",cancelPlace:"left",click:undefined,half:false,halfShow:true,hints:["bad","poor","regular","good","gorgeous"],iconRange:undefined,mouseout:undefined,mouseover:undefined,noRatedMsg:"Not rated yet!",number:5,numberMax:20,path:"",precision:false,readOnly:false,round:{down:0.25,full:0.6,up:0.76},score:undefined,scoreName:"score",single:false,size:16,space:true,starHalf:"//bapi.s3.amazonaws.com/lib/raty/star-half.png",starOff:"//bapi.s3.amazonaws.com/lib/raty/star-off.png",starOn:"//bapi.s3.amazonaws.com/lib/raty/star-on.png",target:undefined,targetFormat:"{score}",targetKeep:false,targetText:"",targetType:"hint",width:undefined};})(jQuery);

/* BlockUI */
;(function(){"use strict";function e(e){function a(i,a){var l,h;var m=i==window;var g=a&&a.message!==undefined?a.message:undefined;a=e.extend({},e.blockUI.defaults,a||{});if(a.ignoreIfBlocked&&e(i).data("blockUI.isBlocked"))return;a.overlayCSS=e.extend({},e.blockUI.defaults.overlayCSS,a.overlayCSS||{});l=e.extend({},e.blockUI.defaults.css,a.css||{});if(a.onOverlayClick)a.overlayCSS.cursor="pointer";h=e.extend({},e.blockUI.defaults.themedCSS,a.themedCSS||{});g=g===undefined?a.message:g;if(m&&o)f(window,{fadeOut:0});if(g&&typeof g!="string"&&(g.parentNode||g.jquery)){var y=g.jquery?g[0]:g;var b={};e(i).data("blockUI.history",b);b.el=y;b.parent=y.parentNode;b.display=y.style.display;b.position=y.style.position;if(b.parent)b.parent.removeChild(y)}e(i).data("blockUI.onUnblock",a.onUnblock);var w=a.baseZ;var E,S,x,T;if(n||a.forceIframe)E=e('<iframe class="blockUI" style="z-index:'+w++ +';display:none;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="'+a.iframeSrc+'"></iframe>');else E=e('<div class="blockUI" style="display:none"></div>');if(a.theme)S=e('<div class="blockUI blockOverlay ui-widget-overlay" style="z-index:'+w++ +';display:none"></div>');else S=e('<div class="blockUI blockOverlay" style="z-index:'+w++ +';display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>');if(a.theme&&m){T='<div class="blockUI '+a.blockMsgClass+' blockPage ui-dialog ui-widget ui-corner-all" style="z-index:'+(w+10)+';display:none;position:fixed">';if(a.title){T+='<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(a.title||" ")+"</div>"}T+='<div class="ui-widget-content ui-dialog-content"></div>';T+="</div>"}else if(a.theme){T='<div class="blockUI '+a.blockMsgClass+' blockElement ui-dialog ui-widget ui-corner-all" style="z-index:'+(w+10)+';display:none;position:absolute">';if(a.title){T+='<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(a.title||" ")+"</div>"}T+='<div class="ui-widget-content ui-dialog-content"></div>';T+="</div>"}else if(m){T='<div class="blockUI '+a.blockMsgClass+' blockPage" style="z-index:'+(w+10)+';display:none;position:fixed"></div>'}else{T='<div class="blockUI '+a.blockMsgClass+' blockElement" style="z-index:'+(w+10)+';display:none;position:absolute"></div>'}x=e(T);if(g){if(a.theme){x.css(h);x.addClass("ui-widget-content")}else x.css(l)}if(!a.theme)S.css(a.overlayCSS);S.css("position",m?"fixed":"absolute");if(n||a.forceIframe)E.css("opacity",0);var N=[E,S,x],C=m?e("body"):e(i);e.each(N,function(){this.appendTo(C)});if(a.theme&&a.draggable&&e.fn.draggable){x.draggable({handle:".ui-dialog-titlebar",cancel:"li"})}var k=s&&(!e.support.boxModel||e("object,embed",m?null:i).length>0);if(r||k){if(m&&a.allowBodyStretch&&e.support.boxModel)e("html,body").css("height","100%");if((r||!e.support.boxModel)&&!m){var L=v(i,"borderTopWidth"),A=v(i,"borderLeftWidth");var O=L?"(0 - "+L+")":0;var M=A?"(0 - "+A+")":0}e.each(N,function(e,t){var n=t[0].style;n.position="absolute";if(e<2){if(m)n.setExpression("height","Math.max(document.body.scrollHeight, document.body.offsetHeight) - (jQuery.support.boxModel?0:"+a.quirksmodeOffsetHack+') + "px"');else n.setExpression("height",'this.parentNode.offsetHeight + "px"');if(m)n.setExpression("width",'jQuery.support.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"');else n.setExpression("width",'this.parentNode.offsetWidth + "px"');if(M)n.setExpression("left",M);if(O)n.setExpression("top",O)}else if(a.centerY){if(m)n.setExpression("top",'(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"');n.marginTop=0}else if(!a.centerY&&m){var r=a.css&&a.css.top?parseInt(a.css.top,10):0;var i="((document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "+r+') + "px"';n.setExpression("top",i)}})}if(g){if(a.theme)x.find(".ui-widget-content").append(g);else x.append(g);if(g.jquery||g.nodeType)e(g).show()}if((n||a.forceIframe)&&a.showOverlay)E.show();if(a.fadeIn){var _=a.onBlock?a.onBlock:t;var D=a.showOverlay&&!g?_:t;var P=g?_:t;if(a.showOverlay)S._fadeIn(a.fadeIn,D);if(g)x._fadeIn(a.fadeIn,P)}else{if(a.showOverlay)S.show();if(g)x.show();if(a.onBlock)a.onBlock()}c(1,i,a);if(m){o=x[0];u=e(":input:enabled:visible",o);if(a.focusInput)setTimeout(p,20)}else d(x[0],a.centerX,a.centerY);if(a.timeout){var H=setTimeout(function(){if(m)e.unblockUI(a);else e(i).unblock(a)},a.timeout);e(i).data("blockUI.timeout",H)}}function f(t,n){var r=t==window;var i=e(t);var s=i.data("blockUI.history");var a=i.data("blockUI.timeout");if(a){clearTimeout(a);i.removeData("blockUI.timeout")}n=e.extend({},e.blockUI.defaults,n||{});c(0,t,n);if(n.onUnblock===null){n.onUnblock=i.data("blockUI.onUnblock");i.removeData("blockUI.onUnblock")}var f;if(r)f=e("body").children().filter(".blockUI").add("body > .blockUI");else f=i.find(">.blockUI");if(n.cursorReset){if(f.length>1)f[1].style.cursor=n.cursorReset;if(f.length>2)f[2].style.cursor=n.cursorReset}if(r)o=u=null;if(n.fadeOut){f.fadeOut(n.fadeOut);setTimeout(function(){l(f,s,n,t)},n.fadeOut)}else l(f,s,n,t)}function l(t,n,r,i){var s=e(i);t.each(function(e,t){if(this.parentNode)this.parentNode.removeChild(this)});if(n&&n.el){n.el.style.display=n.display;n.el.style.position=n.position;if(n.parent)n.parent.appendChild(n.el);s.removeData("blockUI.history")}if(s.data("blockUI.static")){s.css("position","static")}if(typeof r.onUnblock=="function")r.onUnblock(i,r);var o=e(document.body),u=o.width(),a=o[0].style.width;o.width(u-1).width(u);o[0].style.width=a}function c(t,n,r){var i=n==window,s=e(n);if(!t&&(i&&!o||!i&&!s.data("blockUI.isBlocked")))return;s.data("blockUI.isBlocked",t);if(!r.bindEvents||t&&!r.showOverlay)return;var u="mousedown mouseup keydown keypress keyup touchstart touchend touchmove";if(t)e(document).bind(u,r,h);else e(document).unbind(u,h)}function h(t){if(t.keyCode&&t.keyCode==9){if(o&&t.data.constrainTabKey){var n=u;var r=!t.shiftKey&&t.target===n[n.length-1];var i=t.shiftKey&&t.target===n[0];if(r||i){setTimeout(function(){p(i)},10);return false}}}var s=t.data;var a=e(t.target);if(a.hasClass("blockOverlay")&&s.onOverlayClick)s.onOverlayClick();if(a.parents("div."+s.blockMsgClass).length>0)return true;return a.parents().children().filter("div.blockUI").length===0}function p(e){if(!u)return;var t=u[e===true?u.length-1:0];if(t)t.focus()}function d(e,t,n){var r=e.parentNode,i=e.style;var s=(r.offsetWidth-e.offsetWidth)/2-v(r,"borderLeftWidth");var o=(r.offsetHeight-e.offsetHeight)/2-v(r,"borderTopWidth");if(t)i.left=s>0?s+"px":"0";if(n)i.top=o>0?o+"px":"0"}function v(t,n){return parseInt(e.css(t,n),10)||0}e.fn._fadeIn=e.fn.fadeIn;var t=e.noop||function(){};var n=/MSIE/.test(navigator.userAgent);var r=/MSIE 6.0/.test(navigator.userAgent)&&!/MSIE 8.0/.test(navigator.userAgent);var i=document.documentMode||0;var s=e.isFunction(document.createElement("div").style.setExpression);e.blockUI=function(e){a(window,e)};e.unblockUI=function(e){f(window,e)};e.growlUI=function(t,n,r,i){var s=e('<div class="growlUI"></div>');if(t)s.append("<h1>"+t+"</h1>");if(n)s.append("<h2>"+n+"</h2>");if(r===undefined)r=3e3;e.blockUI({message:s,fadeIn:700,fadeOut:1e3,centerY:false,timeout:r,showOverlay:false,onUnblock:i,css:e.blockUI.defaults.growlCSS})};e.fn.block=function(t){var n=e.extend({},e.blockUI.defaults,t||{});this.each(function(){var t=e(this);if(n.ignoreIfBlocked&&t.data("blockUI.isBlocked"))return;t.unblock({fadeOut:0})});return this.each(function(){if(e.css(this,"position")=="static"){this.style.position="relative";e(this).data("blockUI.static",true)}this.style.zoom=1;a(this,t)})};e.fn.unblock=function(e){return this.each(function(){f(this,e)})};e.blockUI.version=2.57;e.blockUI.defaults={message:"<h1>Please wait...</h1>",title:null,draggable:true,theme:false,css:{padding:0,margin:0,width:"30%",top:"40%",left:"35%",textAlign:"center",color:"#000",border:"3px solid #aaa",backgroundColor:"#fff",cursor:"wait"},themedCSS:{width:"30%",top:"40%",left:"35%"},overlayCSS:{backgroundColor:"#000",opacity:.6,cursor:"wait"},cursorReset:"default",growlCSS:{width:"350px",top:"10px",left:"",right:"10px",border:"none",padding:"5px",opacity:.6,cursor:"default",color:"#fff",backgroundColor:"#000","-webkit-border-radius":"10px","-moz-border-radius":"10px","border-radius":"10px"},iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank",forceIframe:false,baseZ:1e3,centerX:true,centerY:true,allowBodyStretch:true,bindEvents:true,constrainTabKey:true,fadeIn:200,fadeOut:400,timeout:0,showOverlay:true,focusInput:true,onBlock:null,onUnblock:null,onOverlayClick:null,quirksmodeOffsetHack:4,blockMsgClass:"blockMsg",ignoreIfBlocked:false};var o=null;var u=[]}if(typeof define==="function"&&define.amd&&define.amd.jQuery){define(["jquery"],e)}else{e(jQuery)}})();

/* Watermark */
;(function(n,t,i){var g="TEXTAREA",d="function",nt="password",c="maxLength",v="type",r="",u=!0,rt="placeholder",h=!1,tt="watermark",s=tt,o="watermarkClass",w="watermarkFocus",a="watermarkSubmit",b="watermarkMaxLength",e="watermarkPassword",f="watermarkText",l=/\r/g,ft=/^(button|checkbox|hidden|image|radio|range|reset|submit)$/i,it="input:data("+s+"),textarea:data("+s+")",p=":watermarkable",k=["Page_ClientValidate"],y=h,ut=rt in document.createElement("input");n.watermark=n.watermark||{version:"3.1.4",runOnce:u,options:{className:tt,useNative:u,hideBeforeUnload:u},hide:function(t){n(t).filter(it).each(function(){n.watermark._hide(n(this))})},_hide:function(n,i){var a=n[0],w=(a.value||r).replace(l,r),h=n.data(f)||r,p=n.data(b)||0,y=n.data(o),s,u;h.length&&w==h&&(a.value=r,n.data(e)&&(n.attr(v)||r)==="text"&&(s=n.data(e)||[],u=n.parent()||[],s.length&&u.length&&(u[0].removeChild(n[0]),u[0].appendChild(s[0]),n=s)),p&&(n.attr(c,p),n.removeData(b)),i&&(n.attr("autocomplete","off"),t.setTimeout(function(){n.select()},1))),y&&n.removeClass(y)},show:function(t){n(t).filter(it).each(function(){n.watermark._show(n(this))})},_show:function(t){var p=t[0],g=(p.value||r).replace(l,r),i=t.data(f)||r,k=t.attr(v)||r,d=t.data(o),h,s,a;g.length!=0&&g!=i||t.data(w)?n.watermark._hide(t):(y=u,t.data(e)&&k===nt&&(h=t.data(e)||[],s=t.parent()||[],h.length&&s.length&&(s[0].removeChild(t[0]),s[0].appendChild(h[0]),t=h,t.attr(c,i.length),p=t[0])),(k==="text"||k==="search")&&(a=t.attr(c)||0,a>0&&i.length>a&&(t.data(b,a),t.attr(c,i.length))),d&&t.addClass(d),p.value=i)},hideAll:function(){y&&(n.watermark.hide(p),y=h)},showAll:function(){n.watermark.show(p)}},n.fn.watermark=n.fn.watermark||function(i,y){var tt="string";if(!this.length)return this;var k=h,b=typeof i==tt;return b&&(i=i.replace(l,r)),typeof y=="object"?(k=typeof y.className==tt,y=n.extend({},n.watermark.options,y)):typeof y==tt?(k=u,y=n.extend({},n.watermark.options,{className:y})):y=n.watermark.options,typeof y.useNative!=d&&(y.useNative=y.useNative?function(){return u}:function(){return h}),this.each(function(){var et="dragleave",ot="dragenter",ft=this,h=n(ft),st,d,tt,it;if(h.is(p)){if(h.data(s))(b||k)&&(n.watermark._hide(h),b&&h.data(f,i),k&&h.data(o,y.className));else{if(ut&&y.useNative.call(ft,h)&&(h.attr("tagName")||r)!==g){b&&h.attr(rt,i);return}h.data(f,b?i:r),h.data(o,y.className),h.data(s,1),(h.attr(v)||r)===nt?(st=h.wrap("<span>").parent(),d=n(st.html().replace(/type=["']?password["']?/i,'type="text"')),d.data(f,h.data(f)),d.data(o,h.data(o)),d.data(s,1),d.attr(c,i.length),d.focus(function(){n.watermark._hide(d,u)}).bind(ot,function(){n.watermark._hide(d)}).bind("dragend",function(){t.setTimeout(function(){d.blur()},1)}),h.blur(function(){n.watermark._show(h)}).bind(et,function(){n.watermark._show(h)}),d.data(e,h),h.data(e,d)):h.focus(function(){h.data(w,1),n.watermark._hide(h,u)}).blur(function(){h.data(w,0),n.watermark._show(h)}).bind(ot,function(){n.watermark._hide(h)}).bind(et,function(){n.watermark._show(h)}).bind("dragend",function(){t.setTimeout(function(){n.watermark._show(h)},1)}).bind("drop",function(n){var i=h[0],t=n.originalEvent.dataTransfer.getData("Text");(i.value||r).replace(l,r).replace(t,r)===h.data(f)&&(i.value=t),h.focus()}),ft.form&&(tt=ft.form,it=n(tt),it.data(a)||(it.submit(n.watermark.hideAll),tt.submit?(it.data(a,tt.submit),tt.submit=function(t,i){return function(){var r=i.data(a);n.watermark.hideAll(),r.apply?r.apply(t,Array.prototype.slice.call(arguments)):r()}}(tt,it)):(it.data(a,1),tt.submit=function(t){return function(){n.watermark.hideAll(),delete t.submit,t.submit()}}(tt))))}n.watermark._show(h)}})},n.watermark.runOnce&&(n.watermark.runOnce=h,n.extend(n.expr[":"],{data:n.expr.createPseudo?n.expr.createPseudo(function(t){return function(i){return!!n.data(i,t)}}):function(t,i,r){return!!n.data(t,r[3])},watermarkable:function(n){var t,i=n.nodeName;return i===g?u:i!=="INPUT"?h:(t=n.getAttribute(v),!t||!ft.test(t))}}),function(t){n.fn.val=function(){var u=this,e=Array.prototype.slice.call(arguments),o;return u.length?e.length?(t.apply(u,e),n.watermark.show(u),u):u.data(s)?(o=(u[0].value||r).replace(l,r),o===(u.data(f)||r)?r:o):t.apply(u):e.length?u:i}}(n.fn.val),k.length&&n(function(){for(var u,r,i=k.length-1;i>=0;i--)u=k[i],r=t[u],typeof r==d&&(t[u]=function(t){return function(){return n.watermark.hideAll(),t.apply(null,Array.prototype.slice.call(arguments))}}(r))}),n(t).bind("beforeunload",function(){n.watermark.options.hideBeforeUnload&&n.watermark.hideAll()}))})(jQuery,window);

/* CC Validation */
;(function(){var $,__indexOf=[].indexOf||function(item){for(var i=0,l=this.length;i<l;i++){if(i in this&&this[i]===item){return i}}return -1};$=jQuery;$.fn.validateCreditCard=function(callback,options){var card,card_type,card_types,get_card_type,is_valid_length,is_valid_luhn,normalize,validate,validate_number,_i,_len,_ref,_ref1;card_types=[{name:"amex",pattern:/^3[47]/,valid_length:[15]},{name:"diners_club_carte_blanche",pattern:/^30[0-5]/,valid_length:[14]},{name:"diners_club_international",pattern:/^36/,valid_length:[14]},{name:"jcb",pattern:/^35(2[89]|[3-8][0-9])/,valid_length:[16]},{name:"laser",pattern:/^(6304|670[69]|6771)/,valid_length:[16,17,18,19]},{name:"visa_electron",pattern:/^(4026|417500|4508|4844|491(3|7))/,valid_length:[16]},{name:"visa",pattern:/^4/,valid_length:[16]},{name:"mastercard",pattern:/^5[1-5]/,valid_length:[16]},{name:"maestro",pattern:/^(5018|5020|5038|6304|6759|676[1-3])/,valid_length:[12,13,14,15,16,17,18,19]},{name:"discover",pattern:/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/,valid_length:[16]}];if(options==null){options={}}if((_ref=options.accept)==null){options.accept=(function(){var _i,_len,_results;_results=[];for(_i=0,_len=card_types.length;_i<_len;_i++){card=card_types[_i];_results.push(card.name)}return _results})()}_ref1=options.accept;for(_i=0,_len=_ref1.length;_i<_len;_i++){card_type=_ref1[_i];if(__indexOf.call((function(){var _j,_len1,_results;_results=[];for(_j=0,_len1=card_types.length;_j<_len1;_j++){card=card_types[_j];_results.push(card.name)}return _results})(),card_type)<0){throw"Credit card type '"+card_type+"' is not supported"}}get_card_type=function(number){var _j,_len1,_ref2;_ref2=(function(){var _k,_len1,_ref2,_results;_results=[];for(_k=0,_len1=card_types.length;_k<_len1;_k++){card=card_types[_k];if(_ref2=card.name,__indexOf.call(options.accept,_ref2)>=0){_results.push(card)}}return _results})();for(_j=0,_len1=_ref2.length;_j<_len1;_j++){card_type=_ref2[_j];if(number.match(card_type.pattern)){return card_type}}return null};is_valid_luhn=function(number){var digit,n,sum,_j,_len1,_ref2;sum=0;_ref2=number.split("").reverse();for(n=_j=0,_len1=_ref2.length;_j<_len1;n=++_j){digit=_ref2[n];digit=+digit;if(n%2){digit*=2;if(digit<10){sum+=digit}else{sum+=digit-9}}else{sum+=digit}}return sum%10===0};is_valid_length=function(number,card_type){var _ref2;return _ref2=number.length,__indexOf.call(card_type.valid_length,_ref2)>=0};validate_number=function(number){var length_valid,luhn_valid;card_type=get_card_type(number);luhn_valid=false;length_valid=false;if(card_type!=null){luhn_valid=is_valid_luhn(number);length_valid=is_valid_length(number,card_type)}return callback({card_type:card_type,luhn_valid:luhn_valid,length_valid:length_valid})};validate=function(){var number;number=normalize($(this).val());return validate_number(number)};normalize=function(number){return number.replace(/[ -]/g,"")};this.bind("input",function(){$(this).unbind("keyup");return validate.call(this)});this.bind("keyup",function(){return validate.call(this)});if(this.length!==0){validate.call(this)}return this}}).call(this);

/* Marker Manager */
function MarkerManager(map,opt_opts){var me=this;me.map_=map;me.mapZoom_=map.getZoom();me.projectionHelper_=new ProjectionHelperOverlay(map);google.maps.event.addListener(me.projectionHelper_,"ready",function(){me.projection_=this.getProjection();me.initialize(map,opt_opts)})}MarkerManager.prototype.initialize=function(map,opt_opts){var me=this;opt_opts=opt_opts||{};me.tileSize_=MarkerManager.DEFAULT_TILE_SIZE_;var mapTypes=map.mapTypes;var mapMaxZoom=1;for(var sType in mapTypes){if(typeof map.mapTypes.get(sType)==="object"&&typeof map.mapTypes.get(sType).maxZoom==="number"){var mapTypeMaxZoom=map.mapTypes.get(sType).maxZoom;if(mapTypeMaxZoom>mapMaxZoom){mapMaxZoom=mapTypeMaxZoom}}}me.maxZoom_=opt_opts.maxZoom||19;me.trackMarkers_=opt_opts.trackMarkers;me.show_=opt_opts.show||true;var padding;if(typeof opt_opts.borderPadding==="number"){padding=opt_opts.borderPadding}else{padding=MarkerManager.DEFAULT_BORDER_PADDING_}me.swPadding_=new google.maps.Size(-padding,padding);me.nePadding_=new google.maps.Size(padding,-padding);me.borderPadding_=padding;me.gridWidth_={};me.grid_={};me.grid_[me.maxZoom_]={};me.numMarkers_={};me.numMarkers_[me.maxZoom_]=0;google.maps.event.addListener(map,"dragend",function(){me.onMapMoveEnd_()});google.maps.event.addListener(map,"zoom_changed",function(){me.onMapMoveEnd_()});me.removeOverlay_=function(marker){marker.setMap(null);me.shownMarkers_--};me.addOverlay_=function(marker){if(me.show_){marker.setMap(me.map_);me.shownMarkers_++}};me.resetManager_();me.shownMarkers_=0;me.shownBounds_=me.getMapGridBounds_();google.maps.event.trigger(me,"loaded")};MarkerManager.DEFAULT_TILE_SIZE_=1024;MarkerManager.DEFAULT_BORDER_PADDING_=100;MarkerManager.MERCATOR_ZOOM_LEVEL_ZERO_RANGE=256;MarkerManager.prototype.resetManager_=function(){var mapWidth=MarkerManager.MERCATOR_ZOOM_LEVEL_ZERO_RANGE;for(var zoom=0;zoom<=this.maxZoom_;++zoom){this.grid_[zoom]={};this.numMarkers_[zoom]=0;this.gridWidth_[zoom]=Math.ceil(mapWidth/this.tileSize_);mapWidth<<=1}};MarkerManager.prototype.clearMarkers=function(){this.processAll_(this.shownBounds_,this.removeOverlay_);this.resetManager_()};MarkerManager.prototype.getTilePoint_=function(latlng,zoom,padding){var pixelPoint=this.projectionHelper_.LatLngToPixel(latlng,zoom);var point=new google.maps.Point(Math.floor((pixelPoint.x+padding.width)/this.tileSize_),Math.floor((pixelPoint.y+padding.height)/this.tileSize_));return point};MarkerManager.prototype.addMarkerBatch_=function(marker,minZoom,maxZoom){var me=this;var mPoint=marker.getPosition();marker.MarkerManager_minZoom=minZoom;if(this.trackMarkers_){google.maps.event.addListener(marker,"changed",function(a,b,c){me.onMarkerMoved_(a,b,c)})}var gridPoint=this.getTilePoint_(mPoint,maxZoom,new google.maps.Size(0,0,0,0));for(var zoom=maxZoom;zoom>=minZoom;zoom--){var cell=this.getGridCellCreate_(gridPoint.x,gridPoint.y,zoom);cell.push(marker);gridPoint.x=gridPoint.x>>1;gridPoint.y=gridPoint.y>>1}};MarkerManager.prototype.isGridPointVisible_=function(point){var vertical=this.shownBounds_.minY<=point.y&&point.y<=this.shownBounds_.maxY;var minX=this.shownBounds_.minX;var horizontal=minX<=point.x&&point.x<=this.shownBounds_.maxX;if(!horizontal&&minX<0){var width=this.gridWidth_[this.shownBounds_.z];horizontal=minX+width<=point.x&&point.x<=width-1}return vertical&&horizontal};MarkerManager.prototype.onMarkerMoved_=function(marker,oldPoint,newPoint){var zoom=this.maxZoom_;var changed=false;var oldGrid=this.getTilePoint_(oldPoint,zoom,new google.maps.Size(0,0,0,0));var newGrid=this.getTilePoint_(newPoint,zoom,new google.maps.Size(0,0,0,0));while(zoom>=0&&(oldGrid.x!==newGrid.x||oldGrid.y!==newGrid.y)){var cell=this.getGridCellNoCreate_(oldGrid.x,oldGrid.y,zoom);if(cell){if(this.removeFromArray_(cell,marker)){this.getGridCellCreate_(newGrid.x,newGrid.y,zoom).push(marker)}}if(zoom===this.mapZoom_){if(this.isGridPointVisible_(oldGrid)){if(!this.isGridPointVisible_(newGrid)){this.removeOverlay_(marker);changed=true}}else{if(this.isGridPointVisible_(newGrid)){this.addOverlay_(marker);changed=true}}}oldGrid.x=oldGrid.x>>1;oldGrid.y=oldGrid.y>>1;newGrid.x=newGrid.x>>1;newGrid.y=newGrid.y>>1;--zoom}if(changed){this.notifyListeners_()}};MarkerManager.prototype.removeMarker=function(marker){var zoom=this.maxZoom_;var changed=false;var point=marker.getPosition();var grid=this.getTilePoint_(point,zoom,new google.maps.Size(0,0,0,0));while(zoom>=0){var cell=this.getGridCellNoCreate_(grid.x,grid.y,zoom);if(cell){this.removeFromArray_(cell,marker)}if(zoom===this.mapZoom_){if(this.isGridPointVisible_(grid)){this.removeOverlay_(marker);changed=true}}grid.x=grid.x>>1;grid.y=grid.y>>1;--zoom}if(changed){this.notifyListeners_()}this.numMarkers_[marker.MarkerManager_minZoom]--};MarkerManager.prototype.addMarkers=function(markers,minZoom,opt_maxZoom){var maxZoom=this.getOptMaxZoom_(opt_maxZoom);for(var i=markers.length-1;i>=0;i--){this.addMarkerBatch_(markers[i],minZoom,maxZoom)}this.numMarkers_[minZoom]+=markers.length};MarkerManager.prototype.getOptMaxZoom_=function(opt_maxZoom){return opt_maxZoom||this.maxZoom_};MarkerManager.prototype.getMarkerCount=function(zoom){var total=0;for(var z=0;z<=zoom;z++){total+=this.numMarkers_[z]}return total};MarkerManager.prototype.getMarker=function(lat,lng,zoom){var mPoint=new google.maps.LatLng(lat,lng);var gridPoint=this.getTilePoint_(mPoint,zoom,new google.maps.Size(0,0,0,0));var marker=new google.maps.Marker({position:mPoint});var cellArray=this.getGridCellNoCreate_(gridPoint.x,gridPoint.y,zoom);if(cellArray!==undefined){for(var i=0;i<cellArray.length;i++){if(lat===cellArray[i].getLatLng().lat()&&lng===cellArray[i].getLatLng().lng()){marker=cellArray[i]}}}return marker};MarkerManager.prototype.addMarker=function(marker,minZoom,opt_maxZoom){var maxZoom=this.getOptMaxZoom_(opt_maxZoom);this.addMarkerBatch_(marker,minZoom,maxZoom);var gridPoint=this.getTilePoint_(marker.getPosition(),this.mapZoom_,new google.maps.Size(0,0,0,0));if(this.isGridPointVisible_(gridPoint)&&minZoom<=this.shownBounds_.z&&this.shownBounds_.z<=maxZoom){this.addOverlay_(marker);this.notifyListeners_()}this.numMarkers_[minZoom]++};function GridBounds(bounds){this.minX=Math.min(bounds[0].x,bounds[1].x);this.maxX=Math.max(bounds[0].x,bounds[1].x);this.minY=Math.min(bounds[0].y,bounds[1].y);this.maxY=Math.max(bounds[0].y,bounds[1].y)}GridBounds.prototype.equals=function(gridBounds){if(this.maxX===gridBounds.maxX&&this.maxY===gridBounds.maxY&&this.minX===gridBounds.minX&&this.minY===gridBounds.minY){return true}else{return false}};GridBounds.prototype.containsPoint=function(point){var outer=this;return(outer.minX<=point.x&&outer.maxX>=point.x&&outer.minY<=point.y&&outer.maxY>=point.y)};MarkerManager.prototype.getGridCellCreate_=function(x,y,z){var grid=this.grid_[z];if(x<0){x+=this.gridWidth_[z]}var gridCol=grid[x];if(!gridCol){gridCol=grid[x]=[];return(gridCol[y]=[])}var gridCell=gridCol[y];if(!gridCell){return(gridCol[y]=[])}return gridCell};MarkerManager.prototype.getGridCellNoCreate_=function(x,y,z){var grid=this.grid_[z];if(x<0){x+=this.gridWidth_[z]}var gridCol=grid[x];return gridCol?gridCol[y]:undefined};MarkerManager.prototype.getGridBounds_=function(bounds,zoom,swPadding,nePadding){zoom=Math.min(zoom,this.maxZoom_);var bl=bounds.getSouthWest();var tr=bounds.getNorthEast();var sw=this.getTilePoint_(bl,zoom,swPadding);var ne=this.getTilePoint_(tr,zoom,nePadding);var gw=this.gridWidth_[zoom];if(tr.lng()<bl.lng()||ne.x<sw.x){sw.x-=gw}if(ne.x-sw.x+1>=gw){sw.x=0;ne.x=gw-1}var gridBounds=new GridBounds([sw,ne]);gridBounds.z=zoom;return gridBounds};MarkerManager.prototype.getMapGridBounds_=function(){return this.getGridBounds_(this.map_.getBounds(),this.mapZoom_,this.swPadding_,this.nePadding_)};MarkerManager.prototype.onMapMoveEnd_=function(){this.objectSetTimeout_(this,this.updateMarkers_,0)};MarkerManager.prototype.objectSetTimeout_=function(object,command,milliseconds){return window.setTimeout(function(){command.call(object)},milliseconds)};MarkerManager.prototype.visible=function(){return this.show_?true:false};MarkerManager.prototype.isHidden=function(){return !this.show_};MarkerManager.prototype.show=function(){this.show_=true;this.refresh()};MarkerManager.prototype.hide=function(){this.show_=false;this.refresh()};MarkerManager.prototype.toggle=function(){this.show_=!this.show_;this.refresh()};MarkerManager.prototype.refresh=function(){if(this.shownMarkers_>0){this.processAll_(this.shownBounds_,this.removeOverlay_)}if(this.show_){this.processAll_(this.shownBounds_,this.addOverlay_)}this.notifyListeners_()};MarkerManager.prototype.updateMarkers_=function(){this.mapZoom_=this.map_.getZoom();var newBounds=this.getMapGridBounds_();if(newBounds.equals(this.shownBounds_)&&newBounds.z===this.shownBounds_.z){return}if(newBounds.z!==this.shownBounds_.z){this.processAll_(this.shownBounds_,this.removeOverlay_);if(this.show_){this.processAll_(newBounds,this.addOverlay_)}}else{this.rectangleDiff_(this.shownBounds_,newBounds,this.removeCellMarkers_);if(this.show_){this.rectangleDiff_(newBounds,this.shownBounds_,this.addCellMarkers_)}}this.shownBounds_=newBounds;this.notifyListeners_()};MarkerManager.prototype.notifyListeners_=function(){google.maps.event.trigger(this,"changed",this.shownBounds_,this.shownMarkers_)};MarkerManager.prototype.processAll_=function(bounds,callback){for(var x=bounds.minX;x<=bounds.maxX;x++){for(var y=bounds.minY;y<=bounds.maxY;y++){this.processCellMarkers_(x,y,bounds.z,callback)}}};MarkerManager.prototype.processCellMarkers_=function(x,y,z,callback){var cell=this.getGridCellNoCreate_(x,y,z);if(cell){for(var i=cell.length-1;i>=0;i--){callback(cell[i])}}};MarkerManager.prototype.removeCellMarkers_=function(x,y,z){this.processCellMarkers_(x,y,z,this.removeOverlay_)};MarkerManager.prototype.addCellMarkers_=function(x,y,z){this.processCellMarkers_(x,y,z,this.addOverlay_)};MarkerManager.prototype.rectangleDiff_=function(bounds1,bounds2,callback){var me=this;me.rectangleDiffCoords_(bounds1,bounds2,function(x,y){callback.apply(me,[x,y,bounds1.z])})};MarkerManager.prototype.rectangleDiffCoords_=function(bounds1,bounds2,callback){var minX1=bounds1.minX;var minY1=bounds1.minY;var maxX1=bounds1.maxX;var maxY1=bounds1.maxY;var minX2=bounds2.minX;var minY2=bounds2.minY;var maxX2=bounds2.maxX;var maxY2=bounds2.maxY;var x,y;for(x=minX1;x<=maxX1;x++){for(y=minY1;y<=maxY1&&y<minY2;y++){callback(x,y)}for(y=Math.max(maxY2+1,minY1);y<=maxY1;y++){callback(x,y)}}for(y=Math.max(minY1,minY2);y<=Math.min(maxY1,maxY2);y++){for(x=Math.min(maxX1+1,minX2)-1;x>=minX1;x--){callback(x,y)}for(x=Math.max(minX1,maxX2+1);x<=maxX1;x++){callback(x,y)}}};MarkerManager.prototype.removeFromArray_=function(array,value,opt_notype){var shift=0;for(var i=0;i<array.length;++i){if(array[i]===value||(opt_notype&&array[i]===value)){array.splice(i--,1);shift++}}return shift};function ProjectionHelperOverlay(map){this.setMap(map);var TILEFACTOR=8;var TILESIDE=1<<TILEFACTOR;var RADIUS=7;this._map=map;this._zoom=-1;this._X0=this._Y0=this._X1=this._Y1=-1}ProjectionHelperOverlay.prototype=new google.maps.OverlayView();ProjectionHelperOverlay.prototype.LngToX_=function(lng){return(1+lng/180)};ProjectionHelperOverlay.prototype.LatToY_=function(lat){var sinofphi=Math.sin(lat*Math.PI/180);return(1-0.5/Math.PI*Math.log((1+sinofphi)/(1-sinofphi)))};ProjectionHelperOverlay.prototype.LatLngToPixel=function(latlng,zoom){var map=this._map;var div=this.getProjection().fromLatLngToDivPixel(latlng);var abs={x:~~(0.5+this.LngToX_(latlng.lng())*(2<<(zoom+6))),y:~~(0.5+this.LatToY_(latlng.lat())*(2<<(zoom+6)))};return abs};ProjectionHelperOverlay.prototype.draw=function(){if(!this.ready){this.ready=true;google.maps.event.trigger(this,"ready")}};

/* StyledMarkers */
var StyledIconTypes={};var StyledMarker,StyledIcon;(function(){var bu_="http://chart.apis.google.com/chart?chst=";var gm_=google.maps;var gp_=gm_.Point;var ge_=gm_.event;var gmi_=gm_.MarkerImage;StyledMarker=function(styledMarkerOptions){var me=this;var ci=me.styleIcon=styledMarkerOptions.styleIcon;me.bindTo("icon",ci);me.bindTo("shadow",ci);me.bindTo("shape",ci);me.setOptions(styledMarkerOptions)};StyledMarker.prototype=new gm_.Marker();StyledIcon=function(styledIconType,styledIconOptions,styleClass){var k;var me=this;var i_="icon";var sw_="shadow";var s_="shape";var a_=[];function gs_(){var image_=document.createElement("img");var simage_=document.createElement("img");ge_.addDomListenerOnce(simage_,"load",function(){var w=simage_.width,h=simage_.height;me.set(sw_,new gmi_(styledIconType.getShadowURL(me),null,null,styledIconType.getShadowAnchor(me,w,h)));simage=null});ge_.addDomListenerOnce(image_,"load",function(){var w=image_.width,h=image_.height;me.set(i_,new gmi_(styledIconType.getURL(me),null,null,styledIconType.getAnchor(me,w,h)));me.set(s_,styledIconType.getShape(me,w,h));image_=null});image_.src=styledIconType.getURL(me);simage_.src=styledIconType.getShadowURL(me)}me.as_=function(v){a_.push(v);for(k in styledIconOptions){v.set(k,styledIconOptions[k])}};if(styledIconType!==StyledIconTypes.CLASS){for(k in styledIconType.defaults){me.set(k,styledIconType.defaults[k])}me.setValues(styledIconOptions);me.set(i_,styledIconType.getURL(me));me.set(sw_,styledIconType.getShadowURL(me));if(styleClass){styleClass.as_(me)}gs_();me.changed=function(k){if(k!==i_&&k!==s_&&k!==sw_){gs_()}}}else{me.setValues(styledIconOptions);me.changed=function(v){styledIconOptions[v]=me.get(v);for(k=0;k<a_.length;k++){a_[k].set(v,me.get(v))}};if(styleClass){styleClass.as_(me)}}};StyledIcon.prototype=new gm_.MVCObject();StyledIconTypes.CLASS={};StyledIconTypes.MARKER={defaults:{text:"",color:"00ff00",fore:"000000",starcolor:null},getURL:function(props){var _url;var starcolor_=props.get("starcolor");var text_=props.get("text");var color_=props.get("color").replace(/#/,"");var fore_=props.get("fore").replace(/#/,"");if(starcolor_){_url=bu_+"d_map_xpin_letter&chld=pin_star|"}else{_url=bu_+"d_map_pin_letter&chld="}if(text_){text_=text_.substr(0,2)}_url+=text_+"|";_url+=color_+"|";_url+=fore_;if(starcolor_){_url+="|"+starcolor_.replace(/#/,"")}return _url},getShadowURL:function(props){if(props.get("starcolor")){return bu_+"d_map_xpin_shadow&chld=pin_star"}else{return bu_+"d_map_pin_shadow"}},getAnchor:function(props,width,height){return new gp_(width/2,height)},getShadowAnchor:function(props,width,height){return new gp_(width/4,height)},getShape:function(props,width,height){var _iconmap={};_iconmap.coord=[width/2,height,(7/16)*width,(5/8)*height,(5/16)*width,(7/16)*height,(7/32)*width,(5/16)*height,(5/16)*width,(1/8)*height,(1/2)*width,0,(11/16)*width,(1/8)*height,(25/32)*width,(5/16)*height,(11/16)*width,(7/16)*height,(9/16)*width,(5/8)*height];for(var i=0;i<_iconmap.coord.length;i++){_iconmap.coord[i]=Math.round(_iconmap.coord[i])}_iconmap.type="poly";return _iconmap}};StyledIconTypes.BUBBLE={defaults:{text:"",color:"00ff00",fore:"000000"},getURL:function(props){var _url=bu_+"d_bubble_text_small&chld=bb|";_url+=props.get("text")+"|";_url+=props.get("color").replace(/#/,"")+"|";_url+=props.get("fore").replace(/#/,"");return _url},getShadowURL:function(props){return bu_+"d_bubble_text_small_shadow&chld=bb|"+props.get("text")},getAnchor:function(props,width,height){return new google.maps.Point(0,42)},getShadowAnchor:function(props,width,height){return new google.maps.Point(0,44)},getShape:function(props,width,height){var _iconmap={};_iconmap.coord=[0,44,13,26,13,6,17,1,width-4,1,width,6,width,21,width-4,26,21,26];_iconmap.type="poly";return _iconmap}}})();

/* jquery.meta */
(function($){$.extend({metadata:{defaults:{type:"class",name:"metadata",cre:/({.*})/,single:"metadata"},setType:function(type,name){this.defaults.type=type;this.defaults.name=name},get:function(elem,opts){var settings=$.extend({},this.defaults,opts);if(!settings.single.length){settings.single="metadata"}var data=$.data(elem,settings.single);if(data){return data}data="{}";var getData=function(data){if(typeof data!="string"){return data}if(data.indexOf("{")<0){data=eval("("+data+")")}};var getObject=function(data){if(typeof data!="string"){return data}data=eval("("+data+")");return data};if(settings.type=="html5"){var object={};$(elem.attributes).each(function(){var name=this.nodeName;if(name.match(/^data-/)){name=name.replace(/^data-/,"")}else{return true}object[name]=getObject(this.nodeValue)})}else{if(settings.type=="class"){var m=settings.cre.exec(elem.className);if(m){data=m[1]}}else{if(settings.type=="elem"){if(!elem.getElementsByTagName){return}var e=elem.getElementsByTagName(settings.name);if(e.length){data=$.trim(e[0].innerHTML)}}else{if(elem.getAttribute!=undefined){var attr=elem.getAttribute(settings.name);if(attr){data=attr}}}}object=getObject(data.indexOf("{")<0?"{"+data+"}":data)}$.data(elem,settings.single,object);return object}}});$.fn.metadata=function(opts){return $.metadata.get(this[0],opts)}})(jQuery);

/* jquery.jmapping */
(function($){$.jMapping=function(map_elm,options){var settings,gmarkers,mapped,map,markerManager,places,bounds,jMapper,info_windows;map_elm=(typeof map_elm=="string")?$(map_elm).get(0):map_elm;if(!($(map_elm).data("jMapping"))){settings=$.extend(true,{},$.jMapping.defaults);$.extend(true,settings,options);gmarkers={};info_windows=[];var init=function(doUpdate){var info_window_selector,min_zoom,zoom_level;info_window_selector=[settings.side_bar_selector,settings.location_selector,settings.info_window_selector].join(" ");$(info_window_selector).hide();places=getPlaces();bounds=getBounds(doUpdate);if(doUpdate){gmarkers={};info_windows=[];markerManager.clearMarkers();google.maps.event.trigger(map,"resize");map.fitBounds(bounds);if(settings.force_zoom_level){map.setZoom(settings.force_zoom_level)}}else{map=createMap();markerManager=new MarkerManager(map)}places.each(function(){var marker=createMarker(this);if(!(settings.link_selector===false)){setupLink(this)}$(document).trigger("markerCreated.jMapping",[marker])});if(doUpdate){updateMarkerManager()}else{google.maps.event.addListener(markerManager,"loaded",function(){updateMarkerManager();if(settings.default_zoom_level){map.setZoom(settings.default_zoom_level)}})}if(!(settings.link_selector===false)&&!doUpdate){attachMapsEventToLinks()}};var createMap=function(){if(settings.map_config){map=new google.maps.Map(map_elm,settings.map_config)}else{map=new google.maps.Map(map_elm,{navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL},mapTypeControl:false,mapTypeId:google.maps.MapTypeId.ROADMAP,zoom:9})}map.fitBounds(bounds);if(settings.force_zoom_level){map.setZoom(settings.force_zoom_level)}return map};var getPlaces=function(){return $(settings.side_bar_selector+" "+settings.location_selector)};var getPlacesData=function(doUpdate){return places.map(function(){if(doUpdate){$(this).data("metadata",false)}return $(this).metadata(settings.metadata_options)})};var getBounds=function(doUpdate){var places_data=getPlacesData(doUpdate),newBounds,initialPoint;if(places_data.length){initialPoint=$.jMapping.makeGLatLng(places_data[0].point)}else{initialPoint=$.jMapping.makeGLatLng(settings.default_point)}newBounds=new google.maps.LatLngBounds(initialPoint,initialPoint);for(var i=1,len=places_data.length;i<len;i++){newBounds.extend($.jMapping.makeGLatLng(places_data[i].point))}return newBounds};var setupLink=function(place_elm){var $place_elm=$(place_elm),location_data=$place_elm.metadata(settings.metadata_options),link=$place_elm.find(settings.link_selector);link.attr("href",("#"+location_data.id))};var chooseIconOptions=function(category){if(settings.category_icon_options){if($.isFunction(settings.category_icon_options)){return settings.category_icon_options(category)}else{return settings.category_icon_options[category]||settings.category_icon_options["default"]}}else{return{}}};var createMarker=function(place_elm){var $place_elm=$(place_elm),place_data,point,marker,$info_window_elm,info_window;place_data=$place_elm.metadata(settings.metadata_options);point=$.jMapping.makeGLatLng(place_data.point);if(settings.category_icon_options){icon_options=chooseIconOptions(place_data.category);if((typeof icon_options==="string")||(icon_options instanceof google.maps.MarkerImage)){marker=new google.maps.Marker({icon:icon_options,position:point,map:map})}else{marker=new StyledMarker({styleIcon:new StyledIcon(StyledIconTypes.MARKER,icon_options),position:point,map:map})}}else{marker=new google.maps.Marker({position:point,map:map})}$info_window_elm=$place_elm.find(settings.info_window_selector);if($info_window_elm.length>0){info_window=new google.maps.InfoWindow({content:$info_window_elm.html(),maxWidth:settings.info_window_max_width});info_windows.push(info_window);google.maps.event.addListener(marker,"click",function(){$.each(info_windows,function(index,iwindow){if(info_window!=iwindow){iwindow.close()}});info_window.open(map,marker)})}gmarkers[parseInt(place_data.id,10)]=marker;return marker};var updateMarkerManager=function(){if(settings.always_show_markers===true){min_zoom=0}else{zoom_level=map.getZoom();min_zoom=(zoom_level<7)?0:(zoom_level-7)}markerManager.addMarkers(gmarkersArray(),min_zoom);markerManager.refresh();if(settings.force_zoom_level){map.setZoom(settings.force_zoom_level)}};var attachMapsEventToLinks=function(){var location_link_selector=[settings.side_bar_selector,settings.location_selector,settings.link_selector].join(" ");$(location_link_selector).live("click",function(e){e.preventDefault();var marker_index=parseInt($(this).attr("href").split("#")[1],10);google.maps.event.trigger(gmarkers[marker_index],"click")})};var gmarkersArray=function(){var marker_arr=[];$.each(gmarkers,function(key,value){marker_arr.push(value)});return marker_arr};if($(document).trigger("beforeMapping.jMapping",[settings])!=false){init();mapped=true}else{mapped=false}jMapper={gmarkers:gmarkers,settings:settings,mapped:mapped,map:map,markerManager:markerManager,gmarkersArray:gmarkersArray,getBounds:getBounds,getPlacesData:getPlacesData,getPlaces:getPlaces,update:function(){if($(document).trigger("beforeUpdate.jMapping",[this])!=false){init(true);this.map=map;this.gmarkers=gmarkers;this.markerManager=markerManager;$(document).trigger("afterUpdate.jMapping",[this])}}};$(document).trigger("afterMapping.jMapping",[jMapper]);return jMapper}else{return $(map_elm).data("jMapping")}};$.extend($.jMapping,{defaults:{side_bar_selector:"#map-side-bar:first",location_selector:".map-location",link_selector:"a.map-link",info_window_selector:".info-box",info_window_max_width:425,default_point:{lat:0,lng:0},metadata_options:{type:"attr",name:"data-jmapping"}},makeGLatLng:function(place_point){return new google.maps.LatLng(place_point.lat,place_point.lng)}});$.fn.jMapping=function(options){if((options=="update")&&$(this[0]).data("jMapping")){$(this[0]).data("jMapping").update()}else{if(options=="update"){options={}}$(this[0]).data("jMapping",$.jMapping(this[0],options))}return this}})(jQuery);

/* Bookt API */
var BAPI = BAPI || {};
BAPI.UI = BAPI.UI || {};

(function(context) {

context.maps = {};

/*
	Group: Initialization
*/
context.init = function(options) {
	BAPI.log("BAPI.UI initializing.");
	if (typeof(options)==="undefined" || options===null) { options = {} };	
	context.inithelpers.applyentityadvisor(options);
	context.inithelpers.setupsummarywidgets(options);
	context.inithelpers.setupsearchformwidgets(options);
	context.inithelpers.setupinquiryformwidgets(options);
	context.inithelpers.setuppopupinquiryformwidgets(options);
	context.inithelpers.setupavailcalendarwidgets(options);
	context.inithelpers.setuprateblockwidgets(options);
	context.inithelpers.applyflexsliders(options);
	context.inithelpers.applytruncate(options);	
	context.inithelpers.setupmapwidgets(options);	
	context.inithelpers.applymovemes(options);		
}

context.inithelpers = {	
	setupsummarywidgets: function(options) {
		$.each($('.bapi-summary'), function (i, item) {
			var ctl = $(item);		
			var dologging = (ctl.attr('data-log') == '1');
			var entity = ctl.attr('data-entity');
			var templatename = ctl.attr('data-templatename');
			var applyfixers = parseInt(ctl.attr('data-applyfixers'));
			var usemylist = parseInt(ctl.attr('data-usemylist'));
			var searchoptions = null;
			try { searchoptions = $.parseJSON(ctl.attr('data-searchoptions')); } catch(err) {}
			var selector = '#' + ctl.attr('id');
			BAPI.log("Creating summary widget for " + selector);
			context.createSummaryWidget(selector, { 
					"searchoptions": searchoptions, 
					"entity": entity, 
					"template": BAPI.templates.get(templatename), 
					"log": dologging, 
					"applyfixers": applyfixers,
					"usemylist": usemylist
					}
			);
		});
	},
	setupsearchformwidgets: function(options) {
		$.each($('.bapi-search'), function (i, item) {
			var ctl = $(item);		
			var dologging = (ctl.attr('data-log') == '1');
			var templatename = ctl.attr('data-templatename');
			var searchurl = ctl.attr('data-searchurl');
			var selector = '#' + ctl.attr('id');
			BAPI.log("Creating search widget for " + selector);
			context.createSearchWidget(selector, { "searchurl": searchurl, "template": BAPI.templates.get(templatename), "log": dologging });		
		});	
	},
	setuprateblockwidgets: function(options) {
		$.each($('.bapi-rateblock'), function (i, item) {
			var ctl = $(item);		
			var dologging = (ctl.attr('data-log') == '1');
			var templatename = ctl.attr('data-templatename');
			var selector = '#' + ctl.attr('id');
			BAPI.log("Creating rate block widget for " + selector);
			context.createRateBlockWidget(selector, { "template": BAPI.templates.get(templatename), "log": dologging });		
		});	
	},
	setupinquiryformwidgets: function(options) {
		$.each($('.bapi-inquiryform'), function (i, item) {
			var ctl = $(item);		
			var dologging = (ctl.attr('data-log') == '1');
			var templatename = ctl.attr('data-templatename');
			var pkid = ctl.attr('data-propid');
			var selector = '#' + ctl.attr('id');
			var hasdates = false;
			BAPI.log("Creating inquiry form for " + selector);
			context.createInquiryForm(selector, { "pkid": pkid, "template": BAPI.templates.get(templatename), "hasdatesoninquiryform": hasdates, "log": dologging });		
		});	
	},
	setuppopupinquiryformwidgets: function(options) {
		$('.bapi-inquirypopup').live("click", function() {
			BAPI.log(this);
			var ctl = $(this);	
			var pkid = ctl.attr("data-pkid");
			if (pkid!==null && pkid!='') {
				BAPI.get(pkid, BAPI.entities.property, { "avail": 1 }, function(data) {
					var selector = '#' + ctl.attr('id');
					var options = {};
					try { options = $.parseJSON(ctl.attr('data-options')); } catch(err) {}
					BAPI.log("Creating availability calendar for " + selector);	
					context.createAvailabilityWidget(selector, data, options);
				});		
			}
		});		
	},
	setupavailcalendarwidgets: function(options) {
		$.each($('.bapi-availcalendar'), function (i, item) {
			var ctl = $(item);	
			var pkid = ctl.attr("data-pkid");
			if (pkid!==null && pkid!='') {
				BAPI.get(pkid, BAPI.entities.property, { "avail": 1 }, function(data) {
					var selector = '#' + ctl.attr('id');
					var options = {};
					try { options = $.parseJSON(ctl.attr('data-options')); } catch(err) {}
					BAPI.log("Creating availability calendar for " + selector);	
					context.createAvailabilityWidget(selector, data, options);
				});		
			}
		});	
	},
	applyflexsliders: function(options) {
		$.each($('.bapi-flexslider'), function (i, item) {
			var ctl = $(item);		
			var options = null;
			try { options = $.parseJSON(ctl.attr('data-options')); } catch(err) {}
			var selector = '#' + ctl.attr('id');
			//BAPI.log("Applying flexslider to " + selector);
			if (selector === null) { BAPI.log("--> Error, options for flexslider could not be parsed correctly.  Check JSON format."); }
			else { ctl.flexslider(options); }
		});			
	}, 
	applytruncate: function(options) {
		$.each($('.bapi-truncate'), function (i, item) {
			var ctl = $(item);		
			var selector = '#' + ctl.attr('id');
			var len = parseInt(ctl.attr('data-trunclen'));
			//BAPI.log("Applying jTruncate to " + selector + ", len=" + len);
			ctl.jTruncate({ length: len, moreText: BAPI.textdata.more, lessText: BAPI.textdata.less });		
		});	
	},
	setupmapwidgets: function(options) {
		$.each($('.bapi-map'), function (i, item) {
			var ctl = $(item);		
			var selector = '#' + ctl.attr('id');
			var lsel = ctl.attr('data-refresh-selector');
			var lselevent = ctl.attr('data-refresh-selector-event');
			var locsel = ctl.attr('data-loc-selector');
			if (locsel===null || locsel=='') { locsel = '.map-location'; }
			var linksel = ctl.attr('data-link-selector');
			if (linksel===null || linksel=='') { linksel = '.map-item'; }
			var caticons = null;
			try { caticons = $.parseJSON(ctl.attr('data-category-icons'));}
			catch(err) {}
			BAPI.log("Creating map widget for " + selector + ', location selector=' + locsel + ', link selector=' + linksel);
			ctl.jMapping({
				//side_bar_selector: '#map-locations:first',
				location_selector: locsel,
				link_selector: linksel,
				info_window_selector: '.info-html',
				category_icon_options: caticons,
				map_config: {
					navigationControlOptions: {
					style: google.maps.NavigationControlStyle.DEFAULT,
					streetViewControl: false
				  },
				  mapTypeId: google.maps.MapTypeId.HYBRID,
				  zoom: 7
				}
			});
			
			if (typeof(lsel)!=="undefined" && lsel!==null && lsel!='') {
				$(lsel).on(lselevent, function() {
					BAPI.log("Refresh selector clicked");
					ctl.jMapping('update');
				});
			}
		});	
	},
	applymovemes: function(options) {
		$.each($('.bapi-moveme'), function (i, item) {
			var ctl = $(item);		
			var fromsel = ctl.attr('data-from');
			var tosel = ctl.attr('data-to');
			var method = ctl.attr('data-method');
			if (method===null || method=='') { method = 'prepend' }
			BAPI.log("Moving DOM object from " + fromsel + " to " + tosel + ", method=" + method);
			if (method==="prepend") { $(fromsel).prepend($(tosel)); }
			else { $(fromsel).appendTo($(tosel)); }		
		});	
	},
	applyentityadvisor: function(options) {
		$.each($('.bapi-entityadvisor'), function (i, item) {
			var ctl = $(item);		
			var pkid = ctl.attr('data-pkid');
			var entity = ctl.attr('data-entity');
			BAPI.log("Setting entity advisor to entity=" + entity + ", pkid=" + pkid);
			BAPI.session().mylisttracker.add(pkid, entity);
			BAPI.savesession();
		});
	}
}

context.rowfix = function(selector, wraprows) {
	var divs = $(selector);
	if (divs!==null && divs.length > 0) {
		for(var i = 0; i < divs.length; i+=wraprows) { divs.slice(i, i+wraprows).wrapAll("<div class='row-fluid'></div>"); }					
	}
}
/* 
	Group: Search Widgets 
*/
context.createRateBlockWidget = function (targetid, options) {
	var cur = BAPI.session().mylisttracker.current();
	if (typeof(cur)==="undefined" || cur===null || !(cur.ID>0) || cur.entity!=BAPI.entities.property) {
		return;
	}
	options.dataselector = "quicksearch";
	$(targetid).block({ message: "<img src='" + loadingImgUrl + "' />" });
	BAPI.datamanager.get(BAPI.entities.property, cur.ID, function(p) {
		$(targetid).unblock();
		var data = {};
		data.result = [p];
		data.site = BAPI.site;
		data.config = BAPI.config();
		data.textdata = BAPI.textdata;
		data.session = BAPI.session();
		if (options.log) { BAPI.log("--createSearchWidget.res--"); BAPI.log(data); }
		$(targetid).html(Mustache.render(options.template, data));
		
		context.createDatePicker('.datepickercheckin', { "property": p, "checkoutID": '.datepickercheckout' });
		context.createDatePicker('.datepickercheckout', { "property": p });		
		
		// handle simple get quote
		$(".bapi-getquote").on("click", function () {
			var reqdata = saveFormToSession(this, options);
			BAPI.datamanager.clear(BAPI.entities.property, cur.ID);
			context.createRateBlockWidget(targetid, options);			
		});
		
		$(".bapi-inquire").on("click", function() {
			context.createInquiryForm("#modal-inquiry-form");
			$("#modal-inquiry").dialog();
		});
		
		$(".bapi-booknow").on("click", function() {
			var reqdata = saveFormToSession(this, options);
			BAPI.log(BAPI.session().searchparams);
			var url = "/makebooking?redir=1&keyid=" + cur.ID + 
						"&checkin=" + BAPI.session().searchparams.checkin +
						"&checkout=" + BAPI.session().searchparams.checkout +
						"&adults=" + BAPI.session().searchparams.adults.min +
						"&children=" + BAPI.session().searchparams.children.min +
						"&rooms=" + BAPI.session().searchparams.rooms.min;
			window.location.href = url;
		});
	});	
}

context.createSearchWidget = function (targetid, options, doSearchCallback) {
	options = initOptions(options, 3, 'tmpl-search-rateblock');
	if (typeof (options.dataselector) === "undefined") { options.dataselector = "quicksearch"; }		
	context.loading.ctlshow(targetid);
	
	// do some pre-processing on the object to bind
	var res = {};
	res.result = [ options.property ];	
	res.site = options.site;
	res.config = options.config;
	res.textdata = options.textdata;
	if (options.log) { BAPI.log("--createSearchWidget.res--"); BAPI.log(res); }
	$(targetid).html(Mustache.render(options.template, res));			
	
	// see if there is some quote info to display
	var p = options.property;
	
	// load the session to the form
	var s = BAPI.session().searchparams;
	loadFormFromSession(BAPI.session().searchparams);		
	
	// setup date pickers
	context.createDatePicker('.datepickercheckin', { "property": p, "checkoutID": '.datepickercheckout' });
	context.createDatePicker('.datepickercheckout', { "property": p });		
	
	// handle user clicking Search
	$(".quicksearch-dosearch").on("click", function() {
		$(targetid).block({ message: "<img src='" + loadingImgUrl + "' />" });
		var reqdata = saveFormToSession(this, options);
		if (doSearchCallback) { doSearchCallback(); }
		if (typeof(options.searchurl)!= "undefined" && options.searchurl!='') {
			window.location.href = options.searchurl;			
		}
		else {
			$(targetid).unblock();
		}
	});
	
	// handle user clicking Clear
	$(".quicksearch-doclear").on("click", function() {
		BAPI.clearsession();
		if (doSearchCallback) { doSearchCallback(); }
		$('.' + options.dataselector).val('');		
	});
	
	$(".quicksearch-doadvanced").on("click", function() {
		$(targetid).block({ message: "<img src='" + loadingImgUrl + "' />" });
		var reqdata = saveFormToSession(this, options);
		$(targetid).unblock();
	});	
}

/*
	Group: Summary
*/
context.createSummaryWidget = function (targetid, options, callback) {
	options = initOptions(options, 10, 'tmpl-base-summary');
	if (options.log) { BAPI.log("--options--"); BAPI.log(options); }
	var ids=[], alldata=[];
	context.loading.show();
	
	if (options.entity == BAPI.entities.property) {
		options.searchoptions = $.extend({}, options.searchoptions, BAPI.session().searchparams);
	}
	
	if (options.usemylist) {
		ids = [];
		$.each(BAPI.session().mylist, function (index, item) {
			ids.push(parseInt(item.ID));			
		});
		doSearch(targetid, ids, options.entity, options, alldata, callback); 
	}
	else {
		BAPI.search(options.entity, options.searchoptions, function (data) { 
			if (options.log) { BAPI.log("--search result--"); BAPI.log(data); }
			ids = data.result; 
			doSearch(targetid, ids, options.entity, options, alldata, callback); 
		});	
	}
}

/* 
	Group: Properties 
*/
context.createAvailabilityWidget = function (targetid, data, options) {
    if (typeof (options) === "undefined" || options === null) { options = new Object(); }
	if (typeof (options.availcalendarmonths) === "undefined" || options.availcalendarmonths === null) { options.availcalendarmonths = 6; }
	if (typeof (options.minbookingdays) === "undefined" || options.minbookingdays === null) { options.minbookingdays = 0; }
	if (typeof (options.maxbookingdays) === "undefined" || options.maxbookingdays === null) { options.maxbookingdays = 365; }
	if (typeof (options.languageISO) === "undefined" || options.languageISO === null) { options.languageISO = ''; }
	if (typeof(options.numinrow)==="undefined" || options.numinrow===null || options.numinrows<=0) { options.numinrow = 1; }
	options.numberOfMonths = [ Math.ceil(options.availcalendarmonths / options.numinrow), options.numinrow ];
	var p = data.result[0];		
	if (options.languageISO=='en' && options.language!='en-AU' && options.language!='en-GB' && options.language!='en-NZ') {
		$.datepicker.setDefaults( $.datepicker.regional[''] );
	}
	else {
		$.datepicker.setDefaults( $.datepicker.regional[options.languageISO] );
	}	
	
	$(targetid).datepicker({
		numberOfMonths: options.numberOfMonths,
		minDate: options.minbookingdays,
		maxDate: "+" + options.maxbookingdays + "D",
		createButton: false,
		beforeShowDay: function (date) {
			var taken = false;
			$.each(p.ContextData.Availability, function (index, item) {
				if (date >= BAPI.utils.jsondate(item.CheckIn) && date < BAPI.utils.jsondate(item.CheckOut) - 1)
					taken = true;
			});
			if (!taken) {
				return [true, "avail", ''];
			}
			else {
				return [false, 'unavail', ''];
			}
		}
	})
}

context.createSimilarPropertiesWidget = function (targetid, pid, options) {
	options = initOptions(options, 3, 'tmpl-featuredproperties');
	context.loading.ctlshow(targetid);
	var poptions = { 
		"checkin": options.checkin, 
		"checkout": options.checkout, 
		"similarto": pid, 
		"pagesize": options.pagesize, 
		"seo": 1 
	};
	BAPI.get(pid, BAPI.entities.property, poptions, function(data) {
		data.config = BAPI.config();
		data.textdata = BAPI.textdata;
		$(targetid).html(Mustache.to_html(options.template, data));
	});	
}

context.createFeaturedPropertiesWidget = function (targetid, options) {
	options = initOptions(options, 3, 'tmpl-featuredproperties-horiz');
	context.loading.ctlshow(targetid);
	BAPI.search(BAPI.entities.property, { sort: "random" }, function (data) {                        
		var pids = data.result;
		BAPI.get(pids, BAPI.entities.property, { pagesize: options.pagesize, seo: true }, function (res) {			
			res.textdata = options.textdata;
			$(targetid).html(Mustache.render(options.template, res));
			$('.fpstar1').raty({ readOnly: true, score: 1 });
			$('.fpstar2').raty({ readOnly: true, score: 2 });
			$('.fpstar3').raty({ readOnly: true, score: 3 });
			$('.fpstar5').raty({ readOnly: true, score: 4 });
			$('.fpstar5').raty({ readOnly: true, score: 5 });
		});
	});
}

/* Lead Request */
context.createInquiryForm = function (targetid, options) {	
	options = initOptions(options, 1, 'tmpl-leadrequestform-propertyinquiry');
	if (typeof (options.submitbuttonselector) === "undefined" || options.submitbuttonselector == null) { options.submitbuttonselector = 'doleadrequest'; }	
	if (typeof (options.responseurl) === "undefined" || options.responseurl == null) { options.responseurl = '' }
	
	if (options.dologging==1) { BAPI.log("--- Inquiry Form---"); BAPI.log("-> Options"); BAPI.log(options); }
	context.loading.ctlshow(targetid);
	var data = { "config": options.config, "site": options.site, "textdata": options.textdata }	
	$(targetid).html(Mustache.render(options.template, data));
	$('.specialform').hide(); // hide the spam control
	
	var processing = false;	
	$(".doleadrequest").on("click", function () { 		
		BAPI.log("Processing lead request");
		if (processing) { return; } // already in here
		
		$.validity.start();
		$('.required').require();
		var result = $.validity.end();
		if (!result.valid) { processing = false; alert('Please fill out all required fields.'); return; }
		
		$(targetid).block({ message: "<img src='" + loadingImgUrl + "' />" });
		processing = true; // make sure we do not reenter				
		
		var cur = BAPI.session().mylisttracker.current();
		var pkid = (cur===null) ? null : cur.ID;
		var selname = $(this).attr('data-field-selector');
		var reqdata = { "pid": pkid, "checkin": options.checkin, "checkout": options.checkout };
		reqdata = $.extend({}, reqdata, BAPI.session().searchparams);
		$('.' + selname).each(function() {
			var k = $(this).attr('data-field');
			var v = $(this).val();
			if (k == 'special') {
				if (v.length > 0) {
					window.location.href = options.responseurl + '?special=1';
					return; // special textbox has a value, not a real person
				}
			}
			else if (k != null && k.length>0) {		
				reqdata[k] = v;
			}
		});
		if (options.dologging==1) { BAPI.log("-> Request Data"); BAPI.log(reqdata); }
		BAPI.createevent(reqdata, function(edata) {				
			if (options.dologging==1) { BAPI.log("-> Response Data"); BAPI.log(edata); }
			if (options.responseurl == '') {				
				$(targetid).unblock();
				alert('Your request has been submitted.');
				$('.' + selname).val('');
			}
			else { window.location.href = options.responseurl + '?personid=' + edata.result.Lead.ID; }			
		});
	});
}

/*
	Group: DatePickers
*/
context.createDatePicker = function (targetid, options) {
	if (typeof (options) === "undefined" || options == null) { options = new Object(); }
	if (typeof (options.datepicker) === "undefined") { options.datepicker = {}; }
	if (typeof (options.datepicker.showOn) === "undefined") { options.datepicker.showOn = 'both'; }
	//if (typeof (options.datepicker.buttonImage) === "undefined") { options.datepicker.buttonImage = '//booktplatform.s3.amazonaws.com/App_SharedStyles/images/checkInBtn.png'; }
	//if (typeof (options.datepicker.buttonImageOnly) === "undefined") { options.datepicker.buttonImageOnly = true; }
	if (typeof (options.datepicker.numberOfMonths) === "undefined") { options.datepicker.numberOfMonths = 2; }
	if (typeof (options.datepicker.minDate) === "undefined") { options.datepicker.minDate = BAPI.config().minbookingdays; }
	if (typeof (options.datepicker.maxDate) === "undefined") { options.datepicker.maxDate = "+" + BAPI.config().maxbookingdays + "D"; }            
	if (typeof (options.minlos) === "undefined") { options.minlos = BAPI.config().minlos; }
	if (typeof (options.languageISO) === "undefined") { options.languageISO = BAPI.defaultOptions.languageISO; }	
	if (typeof (options.property) === "undefined") { options.property = null; }	
	
	if (options.languageISO=='en' && options.language!='en-AU' && options.language!='en-GB' && options.language!='en-NZ') {
		$.datepicker.setDefaults( $.datepicker.regional[''] );
	}
	else {
		$.datepicker.setDefaults( $.datepicker.regional[options.languageISO] );
	}	
	var p = options.property;
	options.datepicker.beforeShowDay = function (date) {
		if (p===null || p.ContextData===null || p.ContextData.Availability===null) {
			return [true, "avail"];
		}
		var tdate = moment(date);
		var bavail = true;
		$.each(p.ContextData.Availability, function (index, item) {	
			var cin = moment(item.CheckIn);
			var cout = moment(item.CheckOut);
			if ((tdate.isSame(cin) || tdate.isAfter(cin)) && tdate.isBefore(cout)) {				
				bavail = false;				
			}
		});				
		if (bavail) { return [true, "avail", "Available"]; }
		else { return [false, "unavail", "Unavailable"]; }		
	}
		
	if (!(typeof (options.checkoutID) === "undefined")) {
		options.datepicker.onSelect = function(dateText, inst) {
		BAPI.log('--datepicker onSelect--');
			var df = BAPI.defaultOptions.dateFormatBAPI;
			var dpcheckout = $(options.checkoutID);
			var dpcheckin = $(this);
			var mind = moment(BAPI.config().minbookingdate);
			var maxd = moment(BAPI.config().maxbookingdate);
			
			var selcheckin = dpcheckin.datepicker('getDate');
			var selcheckout = dpcheckout.datepicker('getDate');
			var checkin = moment(selcheckin);	
			var checkout = (selcheckout===null||selcheckout=='') ? moment(0) : moment(selcheckout);
			BAPI.log('->checkin=' + checkin.format(df) + ', checkout=' + checkout.format(df));
			if (checkout.isBefore(checkin)) {			
				checkout = checkin.add('days', BAPI.config().minlos);
				//dpcheckout.datepicker('option', 'minDate', checkin.toDate());
				dpcheckout.datepicker('setDate', checkout.toDate());
				BAPI.log('->setting checkout to ' + checkout.format(df) + ', min date set to ' + checkin.format(df));
			}			
		}
	}
	
	var trigger = $('<span>', { "class": "halflings calendar cal-icon-trigger" });
	trigger.append("<i>");	
	$(targetid).after(trigger);	
	
	options.datepicker.buttonImage = null;
	options.datepicker.buttonImageOnly = false;	
	options.datepicker.showOn = 'focus';
	$(targetid).datepicker(options.datepicker);
	
	trigger.click(function() {
		BAPI.log("datepicker trigger");
		$(targetid).datepicker("show");
	});
}

/*
	Group: Booking
*/
context.createMakeBookingWidget = function (targetid, options) {
	if (typeof (options.dataselector) === "undefined") { options.dataselector = "bookingfield"; }
	context.loading.ctlshow(options.targetids.statement);

	// check if we need to redirect
	var u = $.url(window.location.href);
	var redir = u.param("redir");
	var propid = u.param('keyid');
	var sp = BAPI.session().searchparams;
	if (redir == "1") {
		// first time getting to the page, get values from querystring, svae to session and then redirect
		var checkin = $.url(window.location.href).param('checkin');
		var checkout = $.url(window.location.href).param('checkout');
		var adults = $.url(window.location.href).param('adults');
		var children = $.url(window.location.href).param('children');		
		var df = BAPI.defaultOptions.dateFormatBAPI;
		var dfParse = BAPI.defaultOptions.dateFormatMoment();
		if (typeof (checkin) !== "undefined" && checkin !== null) {  
			try { sp.checkin = moment(checkin, df).format(df); sp.scheckin=moment(sp.checkin, df).format(dfParse); } catch(err){}
		}
		if (typeof (checkout) !== "undefined" && checkout !== null) { 
			try { sp.checkout = moment(checkout, df).format(df); sp.scheckout=moment(sp.checkout, df).format(dfParse); } catch(err){}
		}
		if (typeof (adults) !== "undefined" && adults != null) { sp.adults.min = adults; }
		if (typeof (children) !== "undefined" && children != null) { sp.children.min = children; }		
		BAPI.savesession(); // save the session
		window.location.href = window.location.pathname + '?keyid=' + propid; // redirect to the same page minus the qs params
		return;
	}
		
	// render the master form layout
	
	// cleanup session
	if (sp.adults.min===null || sp.adults.min=='null') { sp.adults.min = 2; }
	if (sp.children.min===null || sp.children.min=='null') { sp.children.min = 0; }	
	BAPI.log(sp);
	
	
	var propoptions = { avail: 1, seo: 1 }
	propoptions = $.extend({}, propoptions, BAPI.session().searchparams);
	BAPI.get(propid, BAPI.entities.property, propoptions, function (data) {		
		data.site = BAPI.site;
		data.config = BAPI.config();
		data.textdata = BAPI.textdata;	
		data.session = BAPI.session();		
		$(targetid).html(Mustache.render(options.mastertemplate, data));	
		$(options.targetids.stayinfo).html(Mustache.render(options.templates.stayinfo, data));
		$(options.targetids.statement).html(Mustache.render(options.templates.statement, data));
		$(options.targetids.renter).html(Mustache.render(options.templates.renter, data));
		$(options.targetids.creditcard).html(Mustache.render(options.templates.creditcard, data));
		BAPI.log(data);
		$('.specialform').hide(); // hide the spam control
		
		// setup date pickers
		context.createDatePicker('.datepickercheckin', { "property": data.result[0], "checkoutID": '.datepickercheckout' });
		context.createDatePicker('.datepickercheckout', { "property": data.result[0] });	
		
		// handle simple get quote
		$(".bapi-getquote").on("click", function () {
			BAPI.log("--getting formdata--");
			BAPI.log(options);
			var reqdata = getFormData(options.dataselector);			
			reqdata.pid = propid;
			reqdata.quoteonly = 1;
			// do fixup for the checkin/checkout			
			BAPI.log("-- outputint form data--");
			BAPI.log(reqdata);
			BAPI.session().searchparams.scheckin = reqdata.scheckin;
			BAPI.session().searchparams.scheckout = reqdata.scheckout;
			BAPI.savesession();
			reqdata.checkin = BAPI.session().searchparams.checkin;
			reqdata.checkout = BAPI.session().searchparams.checkout;
			
			$(options.targetids.stayinfo).block({ message: "<img src='" + loadingImgUrl + "' />" });
			BAPI.get(propid, BAPI.entities.property, reqdata, function (sdata) {			
				sdata.site = BAPI.site;
				sdata.config = BAPI.config();
				sdata.textdata = BAPI.textdata;	
				sdata.session = BAPI.session();				
				$(options.targetids.statement).html(Mustache.render(options.templates.statement, sdata));
				$(options.targetids.stayinfo).unblock();									
			});		
		});
		
		var processing = false;	
		$(".makebooking").on("click", function () { 		
			BAPI.log("make booking");
			if (processing) { return; } // already in here
			
			$.validity.start();
			$('.required').require();
			var result = $.validity.end();
			if (!result.valid) {
				//processing = false; alert('Please fill out all required fields.'); return;
			}
			
			$(targetid).block({ message: "<img src='" + loadingImgUrl + "' />" });
			processing = true; // make sure we do not reenter				
			
			var reqdata = getFormData(options.dataselector);			
			reqdata.pid = propid;						
			if (typeof(reqdata.special)!=="undefined" && reqdata.special!==null && reqdata.special!='') {
				window.location.href = options.responseurl + '?special=1';
				processing = false;
				return; // special textbox has a value, not a real person
			}
			// do fixup for the checkin/checkout
			BAPI.session().searchparams.scheckin = reqdata.scheckin;
			BAPI.session().searchparams.scheckout = reqdata.scheckout;
			BAPI.savesession();
			reqdata.checkin = BAPI.session().searchparams.checkin;
			reqdata.checkout = BAPI.session().searchparams.checkout;
			
			BAPI.log(reqdata);			
			BAPI.save(BAPI.entities.booking, reqdata, function(bres) {
				$(targetid).unblock();
				processing = false;
				if (!bres.result.IsValid) {
					alert(bres.result.ValidationMessage);
				}
			});			
		});		
	});					
}


/*
	Group: Misc
*/

/* Weather Widget */
context.createWeatherWidget = function (id, locid, options) {
     $(id).weatherfeed(locid, options);
}

/* Site Search Widget */
context.createSiteSearchWidget = function (id, options) {
	var c = $(id);
	c.append($('<input>', { id: 'sitesearchtxt', type: 'text', "class": 'input-search-watermark' }));
	c.append($('<input>', { type: 'button', "class": 'hdr-search-btn sitesearch' }));
	$(".sitesearch").on("click", function () {
		BAPI.log('/search/?q=' + $('#sitesearchtxt').val());
		window.location.href = '/search/?q=' + $('#sitesearchtxt').val();
	});
}

/* Currency Selector Widget */
context.createCurrencySelectorWidget = function (id, options) {
	var c = $(id);
	
	var wrapper = { "session": BAPI.session(), "config": BAPI.config() }
	var template = BAPI.templates.get('tmpl-currencyselector');
	var html = Mustache.render(template, wrapper);
	c.html(html);
	$('.dropdown-toggle').dropdown();
	$(".changecurrency").on("click", function () {                
		var newcurrency = $(this).attr('data-currency');
		$('#currencypopup').dialog("close");
		BAPI.session().currency = newcurrency;
		BAPI.savesession();
		document.location.reload(true);
	});
}

/* Loading indicator */
var loadingImgUrl = '//booktplatform.s3.amazonaws.com/App_SharedStyles/CCImages/loading.gif';
context.loading = {
	getLoadingImgUrl: function() { return loadingImgUrl; },
	setLoadingImgUrl: function(s) { loadingImgUrl = s; },
	ctlshow: function(id) {
		$(id).html("<img src='" + loadingImgUrl + "' alt='loading' />");
	},
	show: function(s, options) {
		if (typeof (s) === "undefined" || s == null) {
			s = "Loading...";
		}
		if (typeof (options) === "undefined" || options == null) { options = new Object(); }			
		var c = $('#bapiloader');
		if (c.length == 0) {			
			c = $(document.body).append('<span id="bapiloader" style="z-index:9999999; left:50%; padding-left:5px; padding-right:5px;font-size:small; background-color:#CF4342; color:#fff; top: 0; position:fixed">Loading</span>');
			c = $('#bapiloader');			
		}		
		c.text(s);		
		c.show();
	},
	hide: function() {
		$('#bapiloader').hide();
	}	
}

// private functions
function initOptions(options, initpagesize, inittemplatename) {
	if (typeof (options) === "undefined" || options == null) { options = new Object(); }	
	if (typeof (options.textdata) === "undefined" || options.textdata === null) { options.textdata = BAPI.textdata; }
	if (typeof (options.pagesize) === "undefined" || options.pagesize === null) { options.pagesize = initpagesize; }
	if (typeof (options.template) === "undefined" || options.template === null) { options.template = BAPI.templates.get(inittemplatename); }
	if (typeof (options.site) === "undefined" || options.site === null) { options.site = BAPI.site; }
	if (typeof (options.config) === "undefined" || options.config === null) { options.config = BAPI.config(); }
	if (typeof (options.searchoptions) === "undefined" || options.searchoptions == null) { options.searchoptions = {}; }
	if (typeof (options.searchoptions.seo) === "undefined" || options.searchoptions.seo == null) { options.searchoptions.seo = true; }	
	if (typeof (options.searchoptions.pagesize) === "undefined" || options.searchoptions.pagesize == null) { options.searchoptions.pagesize = initpagesize; }
	options.searchoptions.page = 1; // start at the first page
	return options;
}

function doSearch(targetid, ids, entity, options, alldata, callback) {
	//BAPI.log("Showing page: " + options.searchoptions.page);
	BAPI.get(ids, entity, options.searchoptions, function (data) {
		context.loading.hide(); // hide any loading indicator
		$.each(data.result, function (index, item) { alldata.push(item); }); // update the alldata array
		if (options.log) { BAPI.log("--data result--"); BAPI.log(data); }
		// package up the data to bind to the mustache template
		data.result = alldata;
		data.totalcount = ids.length;
		data.isfirstpage = (options.searchoptions.page == 1);
		data.islastpage = (options.searchoptions.page*options.searchoptions.pagesize) > data.totalcount;		
		data.curpage = options.searchoptions.page - 1;
		data.config = BAPI.config(); 						
		data.textdata = options.textdata;
		var html = Mustache.render(options.template, data); // do the mustache call
		$(targetid).html(html); // update the target				
				
		// apply rowfix
		var rowfixselector = $(targetid).attr('data-rowfixselector');
		var rowfixcount = parseInt($(targetid).attr('data-rowfixcount'));
		if (typeof(rowfixselector)!=="undefined" && rowfixselector!='' && rowfixcount>0) {
			rowfixselector = decodeURIComponent(rowfixselector)
			BAPI.log("Applying row fix to " + rowfixselector + " on every " + rowfixcount + " row.");
			context.rowfix(rowfixselector, rowfixcount);
		}
		
		if (options.applyfixers==1) {
			BAPI.log("Applying fixers.");
			context.inithelpers.applytruncate();	
			context.inithelpers.applyflexsliders(options);
		}
		
		if (callback) { callback(data); }
		$(".showmore").on("click", function () { 				
			options.searchoptions.page++; 
			$(this).block({ message: "<img src='" + loadingImgUrl + "' />" });
			doSearch(targetid, ids, entity, options, alldata, callback); 
		});
		
		$('.changeview').on('click', function() {
			context.loading.show();
			var newtemplatename = $(this).attr('data-template');
			options.template = BAPI.templates.get(newtemplatename);
			BAPI.log("Changing view to use " + newtemplatename);
			doSearch(targetid, ids, entity, options, alldata, callback);
		});
	});
}

function getFormData(selname) {
	var reqdata = {};
	$('.' + selname).each(function() {
		var k = $(this).attr('data-field');
		if (k != null && k.length>0) {		
			reqdata[k] = $(this).val();
		}
	});
	return reqdata;
}

function loadFormFromSession(s) {
	$('.sessioncheckin').val(s.scheckin);
	$('.sessioncheckout').val(s.scheckout);
	$('.sessionlos').val(s.los);
	$('.sessionadultsmin').val(s.adults.min);
	$('.sessionchildrenmin').val(s.children.min);
	$('.sessioncategory').val(s.category);
	$('.sessiondevid').val(s.dev);
	$('.sessionsleepsmin').val(s.sleeps.min);
	$('.sessionbedsmin').val(s.beds.min);
	$('.sessionbathsmin').val(s.baths.min);
	$('.sessionlocation').val(s.location);
	$('.sessionratemin').val(s.rate.min);
	$('.sessionroomsmin').val(s.rooms.min);
	$('.sessionheadline').val(s.headline);
	$('.sessionaltid').val(s.altid);
}

function saveFormToSession(ctl, options) {
	var reqdata = {};
	var sp = BAPI.session().searchparams;
	var dfparse = BAPI.defaultOptions.dateFormatMoment();
	var df = BAPI.defaultOptions.dateFormatBAPI;
	$('.' + options.dataselector).each(function () {			
		var k = $(this).attr('data-field');
		var v = $(this).attr('data-value');
		if (v == null | v == '') v = $(this).val();
		if (k != null && k.length > 0) { 
			if (k=="checkin") {					
				sp.scheckin = v; // need to ensure that the display search param gets set
				v = (v===null || v=='') ? null : moment(v, dfparse).format(df);								
			}
			else if  (k=="checkout") {
				sp.scheckout = v; // need to ensure that the display search param gets set
				v = (v===null || v=='') ? null : moment(v, dfparse).format(df);				
			}
			reqdata[k] = v;
			sp[k] = v;
		}
	});	
	BAPI.savesession();
	return reqdata;
}

function setRows(findThis,wrapthis,howManyWrap){
	var flag=false;
	if($(findThis).length > 0){	
	  var timer = setInterval(function(){
		  var found = $(wrapthis).length;
		  if(found>0){flag=true;}		  
		  initRows(wrapthis,howManyWrap);		  	  	 
			  if(found==0 && flag){
			  	clearInterval(timer);
			  }
		  		  
	  }, 200);
	}
}	

function initRows(wrapthis,howManyWrap) {	
	var divs = $(wrapthis);
	for(var i = 0; i < divs.length; i+=howManyWrap) {
		divs.slice(i, i+howManyWrap).wrapAll("<div class='row-fluid'></div>");
	}			
}

/* 
	this function sets a timer that calls another function until it sets the rows if it is needed or the flexslider if there is one, pages that use this are:
	Attractions, Property Finders, Specials, Gallery View, List View 
*/
function setRows(findThis,wrapthis,needFlex,needWrapRows,howManyWrap){
	if($(findThis).length > 0){	
		var timer = setInterval(function(){		  
			if ($(".showmore").length > 0) {
				initRows(wrapthis,needFlex,needWrapRows,howManyWrap);
			}
			if ($(".nomore").length > 0) {
				initRows(wrapthis,needFlex,needWrapRows,howManyWrap);
				clearInterval(timer);
			}
		}, 200);
	}
}	
	

})(BAPI.UI); 

