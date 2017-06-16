/*
  Copyright (c) 2017 Magebird (http://www.Magebird.com)
 @license    http://www.magebird.com/licence
 Any form of ditribution, sell, transfer forbidden see licence above 
*/
mbPopupParams.requestMethod=2==mbPopupParams.requestType?"POST":"GET";
var mb_popup={showStatsGlobal:!1,serverTime:"",defaultDomain:"",clientTime:"",statsUrl:"",showPopupsUrl:"",cursorPositions:"",lastScrollTop:"",showDialogWin:function(a){if(!a.removed){2==a.showingFrequency&&mb_popup.setPopupIdsCookie(null,a);mb_popup.hasOverlay(a)&&(mb_popup.checkIfMobile()&&jQuery("body").css({position:"relative",height:"auto"}),jQuery("html").attr("data-height")||(jQuery("html").height()==jQuery(window).height()?jQuery("html").attr({"data-height":"100%"}):jQuery("html").attr({"data-height":"auto"})));
a.makeStats&&mb_popup.gaTracking(a,"Popup showed");if(6==a.showWhen)mb_popup.showeffectHandler(a),a.startTime=(new Date).getTime(),mb_popup.dialogCloseHandler(a);else{var b=!1,c=jQuery(".mbdialog.popupid"+a.popupId).find("img"),d=0;c.each(function(){var a=new Image;a.src=this.src;a.onload=function(){d++;d==c.length&&(b=!0)}});var e=0,f=setInterval(function(){e++;if(50===e||(b||0==c.length)&&(-1==a.content.indexOf("cssLoadedChecker")||"none"===jQuery(".mbdialog.popupid"+a.popupId+" .cssLoadedChecker.moctod-dribegam").css("display")&&
"none"===jQuery(".mbdialog.popupid"+a.popupId+" .cssLoadedChecker2.moctod-dribegam").css("display")))mb_popup.showeffectHandler(a),a.startTime=(new Date).getTime(),mb_popup.dialogCloseHandler(a),clearInterval(f)},20)}mb_popup.clickInsideDialogHandler(a)}},closeDialog:function(a,b){a.completedAction||(a.completedAction=2);b="undefined"!==typeof b?b:!1;1==a.closeEffect?(jQuery(".mbdialog.popupid"+a.popupId).hide(),jQuery(".dialogBg.popupid"+a.popupId).hide(),mb_popup.closeDialogCallback(a,b)):(jQuery(".mbdialog.popupid"+
a.popupId).fadeOut(600),mb_popup.hasOverlay(a)?jQuery(".dialogBg.popupid"+a.popupId).fadeOut(600,function(){jQuery(".mbdialog.popupid"+a.popupId).hide();jQuery(".dialogBg.popupid"+a.popupId).hide();mb_popup.closeDialogCallback(a,b)}):mb_popup.closeDialogCallback(a,b))},closeDialogCallback:function(a,b){4!=a.showWhen&&5!=a.showWhen||2==a.showingFrequency||1==a.showingFrequency||(a.dialogActive=!1);var c=jQuery(".popupid"+a.popupId+" .dialogBody iframe").attr("src");c&&-1!=c.indexOf("youtube")&&(jQuery(".popupid"+
a.popupId+" .dialogBody iframe").attr("src",""),c=c.replace("autoplay=1","autoplay=0"),jQuery(".popupid"+a.popupId+" .dialogBody iframe").attr("src",c));mb_popup.checkIfMobile()&&mb_popup.hasOverlay(a)&&jQuery(window).scrollTop(mb_popup.lastScrollTop);mb_popup.hasOverlay(a)&&0==jQuery(".dialogBg:visible").length&&(jQuery("html").css({overflow:"auto",height:jQuery("html").attr("data-height")}),mb_popup.checkIfMobile()&&jQuery("body").css({height:jQuery("html").attr("data-height")}));setTimeout(function(){var c=
"0";b||(mb_popup.setPopupIdsCookie("closePopup",a),c="1");a.makeStats&&(mb_popup.gaTracking(a,"Popup closed without action"),a.makeStats=!1,seconds=(new Date).getTime()-a.startTime,jQuery.ajax({type:mbPopupParams.requestMethod,url:mb_popup.statsUrl,data:"time="+seconds+"&closed="+c+"&popupId="+a.popupId}))},40)},showHandler:function(a){if(6==a.showWhen){var b=0;-1!=navigator.userAgent.indexOf("Edge")&&(b=50);var c=!1;jQuery(document).mouseout(function(d){var e=d.relatedTarget||d.toElement;1<d.pageY&&
(c=!0);if((!e||"HTML"==e.nodeName||0>d.pageY-jQuery(window).scrollTop())&&!1==a.dialogActive&&d.pageY-b-0<jQuery(window).scrollTop()&&c)return mb_popup.showDialogWin(a),!1})}else if(0<a.scrollPx&&3==a.showWhen)jQuery(document).scroll(function(){jQuery(this).scrollTop()>a.scrollPx&&!1==a.dialogActive&&mb_popup.showDialogWin(a)});else if(4==a.showWhen)if(jQuery(a.selector).css("cursor","pointer"),jQuery.isFunction(jQuery.fn.on))jQuery("body").on("click",a.selector,function(b){if(!1==a.dialogActive)return b.preventDefault(),
mb_popup.clickInsideAnotherHandler(this),mb_popup.showDialogWin(a),!1;"none"==jQuery(".popupid"+a.popupId).css("display")&&jQuery(".popupid"+a.popupId).show()});else jQuery(a.selector).live("click",function(b){if(!1==a.dialogActive)return b.preventDefault(),mb_popup.showDialogWin(a),!1;"none"==jQuery(".popupid"+a.popupId).css("display")&&jQuery(".popupid"+a.popupId).show()});else if(5==a.showWhen)jQuery.isFunction(jQuery.fn.on)?(jQuery("body").on({mouseenter:function(b){mb_popup.inhover(a,b)},mouseleave:function(){mb_popup.unhover(a)}},
a.selector),jQuery("body").on("touchstart",a.selector,function(b){!1==a.dialogActive&&(b.preventDefault(),mb_popup.showDialogWin(a));"none"==jQuery(".popupid"+a.popupId).css("display")&&jQuery(".popupid"+a.popupId).show()})):(jQuery(a.selector).hover(function(b){mb_popup.inhover(a,b)},function(){mb_popup.unhover(a)}),jQuery(a.selector).live("touchstart",function(b){!1==a.dialogActive&&(b.preventDefault(),mb_popup.showDialogWin(a));"none"==jQuery(".popupid"+a.popupId).css("display")&&jQuery(".popupid"+
a.popupId).show()}));else if(0<a.secondsDelay&&2==a.showWhen)setTimeout(function(){mb_popup.showDialogWin(a)},1E3*a.secondsDelay);else if(7==a.showWhen)var d=!1,e=setInterval(function(){parseInt(mb_popup.getPopupCookie("totalTime"))>a.totalSecondsDelay&&!d&&(clearInterval(e),d=!0,mb_popup.showDialogWin(a))},1E3);else if(8==a.showWhen){if(mb_popup.getPopupCookie("cartAddedTime"))var f=!1,g,h=setInterval(function(){g=parseInt((new Date).getTime()/1E3)-(mb_popup.getPopupCookie("cartAddedTime")-(mb_popup.serverLocalTime-
parseInt(mb_popup.clientTime/1E3)));g>a.cartSecondsDelay&&!f&&(clearInterval(h),f=!0,mb_popup.showDialogWin(a))},1E3)}else mb_popup.showDialogWin(a)},inhover:function(a,b){!1==a.dialogActive&&(b.preventDefault(),mb_popup.showDialogWin(a));setTimeout(function(){"none"==jQuery(".popupid"+a.popupId).css("display")&&jQuery(".popupid"+a.popupId).show()},100)},unhover:function(a){1!=a.closeOnOut||mb_popup.hasOverlay(a)||setTimeout(function(){jQuery(".mbdialog:hover").length?jQuery(".mbdialog").mouseleave(function(){setTimeout(function(){jQuery(a.selector+
":hover").length||jQuery(".popupid"+a.popupId).hide()},10)}):jQuery(".popupid"+a.popupId).hide()},300)},showeffectHandler:function(a){a.dialogActive=!0;mb_popup.checkIfMobile()&&mb_popup.hasOverlay(a)&&(mb_popup.lastScrollTop=jQuery(window).scrollTop(),setTimeout(function(){jQuery("html, body").scrollTop(0)},300));mb_popup.checkIfMobile()&&mb_popup.hasOverlay(a)&&jQuery(window).height()>jQuery("body").height()&&jQuery(".dialogBg").height(jQuery(window).height());jQuery(".dialogBg.popupid"+a.popupId).show();
switch(a.appearType){case "7":mb_popup.rotateZoomShow(a);break;case "6":mb_popup.elasticShow(a);break;case "4":mb_popup.slideupShow(a);break;case "3":mb_popup.slidedownShow(a);break;case "2":setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).fadeIn(1E3)},100);break;default:6==a.showWhen?jQuery(".mbdialog.popupid"+a.popupId).css({display:"block"}):setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).css({display:"block"})},50)}if(mb_popup.hasOverlay(a))if(mb_popup.checkIfMobile())jQuery(".dialogBg").css({"overflow-y":"auto"});
else if(1!=a.appearType&&2!=a.appearType)jQuery("html").css({overflow:"hidden",height:"100%"});else{jQuery("html").css({overflow:"auto"});jQuery(".dialogBg").css({"overflow-y":"hidden"});var b=0,c=0,d=setInterval(function(){b++;60<b?clearInterval(d):(c=jQuery(".popupid"+a.popupId+" .dialogBody").offset().top-jQuery(window).scrollTop()+jQuery(".popupid"+a.popupId+" .dialogBody").outerHeight(!0),jQuery(window).height()<c&&(jQuery("html").css({overflow:"hidden",height:"100%"}),jQuery(".dialogBg").css({"overflow-y":"auto"})))},
50)}mb_popup.cartStats(a)},keyupHandler:function(a){jQuery(document).keyup(function(b){27==b.keyCode&&mb_popup.closeDialog(a)})},onbeforeunloadHandler:function(){window.onunload=window.onbeforeunload=function(){var a=!1;return function(){if(!a){a=!0;var b={},c;for(c in mb_popups)mb_popups.hasOwnProperty(c)&&mb_popups[c].dialogActive&&jQuery(".mbdialog.popupid"+mb_popups[c].popupId).is(":visible")&&mb_popups[c].makeStats&&(mb_popup.hasOverlay(mb_popups[c])||!mb_popup.hasOverlay(mb_popups[c])&&1!=mb_popups[c].showWhen)&&
(seconds=(new Date).getTime()-mb_popups[c].startTime,b[mb_popups[c].popupId]=seconds,mb_popups[c].makeStats=!1,mb_popup.gaTracking(mb_popups[c],"Window closed/left while popup still opened"));a:{for(var d in b)if(b.hasOwnProperty(d)){c=!1;break a}c=!0}c||(c=Math.random().toString(36).substring(2,10),mb_popup.setPopupCookie("lastPageviewId",c,10),"true"==mbPopupParams.ajaxAsync?(jQuery.ajax({type:mbPopupParams.requestMethod,url:mb_popup.statsUrl,data:"windowClosed=1&lastPageviewId="+c+"&popupIds="+
JSON.stringify(b),async:!0}),b=decodeURIComponent(mbPopupParams.rootUrl)+"skin/frontend/base/default/css/magebird_popup/style_v148.css",jQuery.ajax({type:mbPopupParams.requestMethod,url:b,async:!1})):jQuery.ajax({type:mbPopupParams.requestMethod,url:mb_popup.statsUrl,data:"windowClosed=1&lastPageviewId="+c+"&popupIds="+JSON.stringify(b),async:!1}))}}}()},dialogCloseHandler:function(a){0<a.closeTimeout&&setTimeout(function(){mb_popup.closeDialog(a,!0)},1E3*a.closeTimeout);jQuery.isFunction(jQuery.fn.on)?
(jQuery("body").on("click",".popupid"+a.popupId+" .dialogCloseCustom",function(b){b.preventDefault?b.preventDefault():b.returnValue=!1;mb_popup.closeDialog(a)}),jQuery("body").on("click",".dialogBg.popupid"+a.popupId,function(b){0!=a.closeOnOverlay&&!jQuery(b.target).closest(".mbdialog.popupid"+a.popupId).length&&jQuery(".mbdialog.popupid"+a.popupId).is(":visible")&&mb_popup.closeDialog(a)})):(jQuery(".popupid"+a.popupId+" .dialogCloseCustom").live("click",function(b){b.preventDefault?b.preventDefault():
b.returnValue=!1;mb_popup.closeDialog(a)}),jQuery(".dialogBg.popupid"+a.popupId).live("click",function(b){0!=a.closeOnOverlay&&!jQuery(b.target).closest(".mbdialog.popupid"+a.popupId).length&&jQuery(".mbdialog.popupid"+a.popupId).is(":visible")&&mb_popup.closeDialog(a)}))},clickInsideDialogHandler:function(a){if(jQuery.isFunction(jQuery.fn.on))jQuery("body").on("click",function(b){jQuery(b.target).closest(".mbdialog.popupid"+a.popupId).length&&jQuery(".mbdialog.popupid"+a.popupId).is(":visible")&&
!jQuery(b.target).closest(".mbdialog.popupid"+a.popupId+" .dialogCloseCustom").length&&!jQuery(b.target).closest(".mbdialog.popupid"+a.popupId+" .dialogClose").length&&mb_popup.clickInsideDialog(a,b)});else jQuery("body").live("click",function(b){jQuery(b.target).closest(".mbdialog.popupid"+a.popupId).length&&jQuery(".mbdialog.popupid"+a.popupId).is(":visible")&&!jQuery(b.target).closest(".mbdialog.popupid"+a.popupId+" .dialogCloseCustom").length&&!jQuery(b.target).closest(".mbdialog.popupid"+a.popupId+
" .dialogClose").length&&mb_popup.clickInsideDialog(a,b)})},clickInsideAnotherHandler:function(a){if(jQuery(a).parents(".mbdialog").hasClass("mbdialog")){jQuery(".mbdialog").hide();jQuery(".dialogBg").hide();a=jQuery(a).parents(".mbdialog").attr("class").split(/\s+/);for(var b=0;b<a.length;b++)if(-1<a[b].indexOf("popupid"))var c=mb_popups[a[b].replace(/[^0-9]/g,"")];c.makeStats&&(mb_popup.setPopupIdsCookie("clickInside",c),mb_popup.gaTracking(c,"Clicked inside popup"),seconds=(new Date).getTime()-
c.startTime,jQuery.ajax({type:mbPopupParams.requestMethod,url:mb_popup.statsUrl,data:"time="+seconds+"&clickInside=1&popupId="+c.popupId}),c.makeStats=!1)}},dialogLocked:!1,clickInsideDialog:function(a,b){mb_popup.checkIfMobile()&&3==a.overlay&&!1==mb_popup.dialogLocked&&(jQuery(".mbdialog.popupid"+a.popupId).css("position","absolute"),jQuery(".mbdialog.popupid"+a.popupId).css("top",jQuery(".mbdialog").offset().top+jQuery(document).scrollTop()+"px"),jQuery(".mbdialog.popupid"+a.popupId).css("left",
jQuery(".mbdialog").offset().left+jQuery(document).scrollLeft()+"px"),jQuery(".mbdialog.popupid"+a.popupId).css("margin","0px"),mb_popup.dialogLocked=!0);a.completedAction||(a.completedAction=4);-1==window.location.href.indexOf("popup/index/preview/")&&-1==window.location.href.indexOf("popup/index/template/")&&mb_popup.setPopupIdsCookie("clickInside",a);a.makeStats&&(mb_popup.gaTracking(a,"Clicked inside popup"),seconds=(new Date).getTime()-a.startTime,jQuery.ajax({type:mbPopupParams.requestMethod,
url:mb_popup.statsUrl,data:"time="+seconds+"&clickInside=1&popupId="+a.popupId}),a.makeStats=!1)},prepareDialog:function(a){popupDialog='<div class="mbdialog popupid'+a.popupId+'" data-popupid="'+a.popupId+'">';5!=a.closeStyle&&(popupDialog+='<a href="javascript:void(0)" onclick="mb_popup.closeDialog(mb_popups['+a.popupId+'])" class="dialogClose style'+a.closeStyle+" overlay"+a.overlay+'"></a>');popupDialog+='<div class="dialogBody"></div></div>';mb_popup.hasOverlay(a)&&(popupDialog='<div class="dialogBg popupid'+
a.popupId+'">'+popupDialog+"</div>");4==a.verticalPosition?jQuery("body").prepend(popupDialog):jQuery("body").append(popupDialog);jQuery(".popupid"+a.popupId+" .dialogBody").append(a.content);mb_popup.checkIfMobile()&&mb_popup.hasOverlay(a)&&jQuery(".dialogBg.popupid"+a.popupId).css("position","absolute");3==a.verticalPosition&&""!=a.elementIdPosition&&jQuery(a.elementIdPosition).length&&(a.verticalPositionPx=parseInt(jQuery(a.elementIdPosition).offset().top)+parseInt(a.verticalPositionPx),jQuery(".mbdialog.popupid"+
a.popupId).css({top:a.verticalPositionPx+"px"}));6==a.horizontalPosition&&""!=a.elementIdPosition&&jQuery(a.elementIdPosition).length&&jQuery(".mbdialog.popupid"+a.popupId).css({left:parseInt(jQuery(a.elementIdPosition).offset().left)+parseInt(a.horizontalPositionPx)+"px"});mb_popup.showHandler(a)},rotateZoomShow:function(a){setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).addClass("transform-rotate-zoom1");jQuery(".mbdialog.popupid"+a.popupId).css({display:"block"})},100);setTimeout(function(){jQuery(".mbdialog.popupid"+
a.popupId).addClass("transform-rotate-zoom2")},500)},elasticShow:function(a){setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).css({display:"block"})},100);jQuery(".mbdialog.popupid"+a.popupId).addClass("transform-elastic1");setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).addClass("transform-elastic2")},300);setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).addClass("transform-elastic3")},640)},slidedownShow:function(a){4==a.verticalPosition?jQuery(".mbdialog.popupid"+
a.popupId).css("margin-top","-"+jQuery(".mbdialog.popupid"+a.popupId).outerHeight()+"px"):jQuery(".mbdialog.popupid"+a.popupId).css({top:"-"+jQuery(".mbdialog.popupid"+a.popupId).outerHeight()+"px"});mb_popup.cssTransitions()||mb_popup.checkIfMobile()?(setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).css({display:"block"})},100),4==a.verticalPosition?setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).animate({"margin-top":"0px"},700)},1):1==a.verticalPosition||3==a.verticalPosition?
setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).animate({top:a.verticalPositionPx+"px"},700)},1):setTimeout(function(){var b=jQuery(window).height()-jQuery(".popupid"+a.popupId+" .dialogBody").outerHeight()-a.verticalPositionPx;jQuery(".mbdialog.popupid"+a.popupId).animate({top:b+"px"},700)},120)):(setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).css({display:"block"})},100),setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).addClass("popuptransition")},110),4==a.verticalPosition?
setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).css({"margin-top":"0px"})},120):1==a.verticalPosition||1==a.verticalPosition?setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).css({top:a.verticalPositionPx+"px"})},120):setTimeout(function(){var b=jQuery(window).height()-jQuery(".popupid"+a.popupId+" .dialogBody").outerHeight()-a.verticalPositionPx;jQuery(".mbdialog.popupid"+a.popupId).animate({top:b+"px"},700)},120))},slideupShow:function(a){if(4==a.verticalPosition)setTimeout(function(){jQuery(".mbdialog.popupid"+
a.popupId).css({display:"block"})},50);else if(1==a.verticalPosition||3==a.verticalPosition)jQuery(".mbdialog.popupid"+a.popupId).css({top:jQuery(window).height()+"px"}),setTimeout(function(){jQuery(".mbdialog.popupid"+a.popupId).css({display:"block"})},100),jQuery(".mbdialog.popupid"+a.popupId).animate({top:a.verticalPositionPx+"px"},800);else{var b=jQuery(".mbdialog.popupid"+a.popupId).height();0==b&&(b=600);jQuery(".mbdialog.popupid"+a.popupId).css({bottom:"-"+b+"px"});setTimeout(function(){jQuery(".mbdialog.popupid"+
a.popupId).css({display:"block"});jQuery(".mbdialog.popupid"+a.popupId).animate({bottom:a.verticalPositionPx+"px"},450)},100)}},randomString:function(a,b){b=b?b:"";return a?rand(--a,"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkldpqzsjhiunbhfcjseepudpnuvwxyz0123456789".charAt(Math.floor(60*Math.random()))+b):b},checkIfMobile:function(){return/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)?!0:!1},cssTransitions:function(){return"WebkitTransition"in document.body.style||
"MozTransition"in document.body.style||"OTransition"in document.body.style||"transition"in document.body.style?!0:!1},gaTracking:function(a,b){mbPopupParams.doGaTracking&&("undefined"!=typeof ga?ga("send","event","Magebird Popup",b+" - Popup Id "+a.popupId,a.title,{nonInteraction:!0}):"undefined"!=typeof _gaq?_gaq.push(["_trackEvent","Magebird Popup",b+" - Popup Id "+a.popupId,a.title,1,!0]):"undefined"!=typeof dataLayer&&dataLayer.push({event:"gaEvent",gaEventCategory:"Magebird Popup",gaEventAction:b+
" - Popup Id "+a.popupId,gaEventLabel:a.title,nonInteraction:1}))},getCookie:function(a){a+="=";for(var b=document.cookie.split(";"),c=0;c<b.length;c++){for(var d=b[c];" "==d.charAt(0);)d=d.substring(1);if(-1!=d.indexOf(a))return decodeURIComponent(d.substring(a.length,d.length))}return""},setCookie:function(a,b,c,d){var e=new Date;e.setTime(e.getTime()+864E5*c);c="expires="+e.toUTCString();d&&(b=encodeURIComponent(b));document.cookie=a+"="+b+"; "+c+"; path=/"},getPopupCookie:function(a,b){a=mb_popup.getCookie("popupData").split(a+
":");if(a[1])value=a[1].split("|"),value=value[0];else if("lastSession"==a&&mb_popup.getCookie("lastPopupSession"))value=mb_popup.getCookie("lastPopupSession");else if("lastPageviewId"==a&&mb_popup.getCookie("lastPageviewId"))value=mb_popup.getCookie("lastPageviewId");else return"";if(!b){value=value.split("=");if(value[1]){expire=value[1];var c=parseInt((new Date).getTime()/1E3)+(mb_popup.serverTime-parseInt(mb_popup.clientTime/1E3));if(expire<c)return""}value=value[0]}return value},setPopupCookie:function(a,
b,c){if(c){var d=parseInt((new Date).getTime()/1E3)+(mb_popup.serverTime-parseInt(mb_popup.clientTime/1E3));c=parseInt(c)+parseInt(d);b+="="+c}mb_popup.getCookie("popupData")?(c=mb_popup.getCookie("popupData"),d=mb_popup.getPopupCookie(a,!0),c=-1!=c.indexOf(a)?c.replace(a+":"+d,a+":"+b):c+("|"+a+":"+b)):c=a+":"+b;mb_popup.setCookie("popupData",c,365,!0)},dontShowAgain:function(a){a=jQuery(a).closest(".mbdialog").attr("data-popupid");jQuery(".popupid"+a+" .rememberMe").is(":checked")?mb_popup.setPopupIdsCookie("setCookieManually",
mb_popups[a]):mb_popup.setPopupIdsCookie("setCookieManually",mb_popups[a],!0)},setPopupIdsCookie:function(a,b,c,d,e){if(-1==window.location.href.indexOf("magebird_popup/index/preview/")&&-1==window.location.href.indexOf("popup/index/template/")&&("goalCompleted"!=a||!b||6==b.showingFrequency)){if("closePopup"==a&&b){if(1!=b.showingFrequency&&5!=b.showingFrequency)return}else if("clickInside"==a&&b){if(4!=b.showingFrequency&&5!=b.showingFrequency)return}else if(b&&2!=b.showingFrequency&&"setCookieManually"!=
a&&"goalCompleted"!=a)return;d=d?d:b.cookieId;e=e?e:b.cookieTime;var f=[],g=!1;mb_popup.getCookie("popup_ids").split("|").forEach(function(a){a&&(explode=a.split("="),expire=explode[1],popupCookieId=explode[0],popupCookieId==d&&(expire=c?mb_popup.serverTime-1E3:mb_popup.serverTime+86400*e,g=!0),f.push(popupCookieId+"="+expire))});!1!=g||c||(expire=mb_popup.serverTime+86400*e,f.push(d+"="+expire));mb_popup.setCookie("popup_ids",f.join("|"),365);mb_popup.getPopupCookie("popupTimer")&&mb_popup.setPopupCookie("popupTimer",
mb_popup.getPopupCookie("popupTimer").replace(d,0))}},hasOverlay:function(a){return 3==a.overlay||4==a.overlay?!1:!0},correctHttps:function(a){"https:"==window.location.protocol?"https:"!==a.substring(0,6)&&(a="https:"+a.substring(5)):"http:"!==a.substring(0,5)&&(a="http:"+a.substring(6));return a},callbackRequest:function(){mb_popup.showPopupsUrl=decodeURIComponent(mbPopupParams.baseUrl)+"magebird_popup/index/show?switchRequestType=1&rand="+Math.floor(1E8*Math.random()+1);mb_popup.statsUrl=decodeURIComponent(mbPopupParams.baseUrl)+
"magebird_popup/index/stats/?rand="+Math.floor(1E8*Math.random()+1);jQuery.ajax({type:mbPopupParams.requestMethod,url:mb_popup.showPopupsUrl,data:"storeId="+mbPopupParams.storeId+"&previewId="+mbPopupParams.previewId+"&templateId="+mbPopupParams.templateId+"&nocache=1&popup_page_id="+mbPopupParams.popupPageId+"&filterId="+mbPopupParams.filterId+"&ref="+encodeURIComponent(document.referrer)+"&url="+encodeURIComponent(window.location.href)+"&baseUrl="+encodeURIComponent(mbPopupParams.rootUrl)+"&customParams="+
encodeURIComponent(mbPopupParams.customParams)+"&cEnabled="+navigator.cookieEnabled,success:function(a){-1===a.indexOf("magebird_popup/main.js")&&jQuery("body").append(a)},error:function(){console.log("Unknown error for url "+mb_popup.showPopupsUrl)}})},totalTime:function(){var a,b;setInterval(function(){b=parseInt((new Date).getTime()/1E3)+(mb_popup.serverTime-parseInt(mb_popup.clientTime/1E3));900<(new Date).getTime()-mb_popup.getPopupCookie("lastTimer")&&(mb_popup.setPopupCookie("lastTimer",parseInt((new Date).getTime())),
mb_popup.getPopupCookie("totalTime")?(a=mb_popup.getPopupCookie("totalTime",!0),mb_popup.setPopupCookie("totalTime",parseInt(a.split("=")[0])+1,a.split("=")[1]-parseInt(parseInt(b)))):mb_popup.setPopupCookie("totalTime",1,7200))},1E3)},cartStats:function(a){var b=mb_popup.getPopupCookie("lastPopups").split(","),c=!1;for(i=0;i<b.length;i++)a.popupId==b[i]&&(c=!0);!c&&mb_popup.getPopupCookie("cartAdded")&&(b=-1==mb_popup.showPopupsUrl.indexOf("magebirdpopup.php")?decodeURIComponent(mbPopupParams.baseUrl)+
"magebird_popup/index/popupCartsCount?rand="+Math.floor(1E8*Math.random()+1):decodeURIComponent(mbPopupParams.rootUrl)+"magebirdpopup.php?action=popupCartsCount&rand="+Math.floor(1E8*Math.random()+1),jQuery.ajax({type:mbPopupParams.requestMethod,url:b,data:"popupId="+a.popupId}))}};
3!=mbPopupParams.requestType?(mb_popup.showPopupsUrl=decodeURIComponent(mbPopupParams.rootUrl)+"magebirdpopup.php?rand="+Math.floor(1E8*Math.random()+1),mb_popup.statsUrl=decodeURIComponent(mbPopupParams.rootUrl)+"magebirdpopup.php?action=stats&rand="+Math.floor(1E8*Math.random()+1)):(mb_popup.showPopupsUrl=decodeURIComponent(mbPopupParams.baseUrl)+"magebird_popup/index/show?rand="+Math.floor(1E8*Math.random()+1),mb_popup.statsUrl=decodeURIComponent(mbPopupParams.baseUrl)+"magebird_popup/index/stats/?rand="+
Math.floor(1E8*Math.random()+1));mb_popup.totalTime();
if(1==mbPopupParams.isAjax){if("undefined"===typeof popupIntervalChecker)var popupIntervalChecker=!1;var popupJqueryListener=setInterval(function(){"undefined"!==typeof jQuery&&jQuery("body").length&&!popupIntervalChecker&&0!=mbPopupParams.page&&(clearInterval(popupJqueryListener),popupIntervalChecker=!0,jQuery.ajax({type:mbPopupParams.requestMethod,url:mb_popup.showPopupsUrl,data:"cc="+mbPopupParams.cc+"&bc="+mbPopupParams.bc+"&cs="+mbPopupParams.cs+"&cf="+mbPopupParams.cf+"&storeId="+mbPopupParams.storeId+
"&previewId="+mbPopupParams.previewId+"&templateId="+mbPopupParams.templateId+"&nocache=1&popup_page_id="+mbPopupParams.popupPageId+"&filterId="+mbPopupParams.filterId+"&ref="+encodeURIComponent(document.referrer)+"&url="+encodeURIComponent(window.location.href)+"&baseUrl="+encodeURIComponent(mbPopupParams.rootUrl)+"&customParams="+encodeURIComponent(mbPopupParams.customParams)+"&cEnabled="+navigator.cookieEnabled,success:function(a){-1==a.indexOf("mb_popups")||-1!==a.indexOf("magebird_popup/main.js")||
-1!==a.indexOf('"content":null')?mb_popup.callbackRequest():jQuery("body").append(a)},error:function(a){0!=a.readyState&&mb_popup.callbackRequest()}}))},10)};