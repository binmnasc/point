﻿var $=jQuery;$.fn.simpleColorPicker=function(f){var e={colorsPerLine:8,colors:["#000000","#444444","#666666","#999999","#cccccc","#eeeeee","#f3f3f3","#ffffff","#ff0000","#ff9900","#ffff00","#00ff00","#00ffff","#0000ff","#9900ff","#ff00ff","#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc","#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd","#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0","#cc0000","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79","#990000","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47","#660000","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4C1130"],showEffect:"",hideEffect:"",onChangeColor:!1};void 0==$&&($=jQuery);var o=$.extend(e,f);return this.each(function(){function f(f){"fade"==o.hideEffect?f.fadeOut():"slide"==o.hideEffect?f.slideUp():f.hide()}function c(f){"fade"==o.showEffect?f.fadeIn():"slide"==o.showEffect?f.slideDown():f.show()}for(var i=$(this),t="",l=i.attr("id").replace(/-/g,"")+"_",d=0;d<o.colors.length;d++){var r=o.colors[d],n="";d%o.colorsPerLine==0&&(n="clear: both; "),d>0&&n&&$.browser&&$.browser.msie&&$.browser.version<=7&&(n="",t+='<li style="float: none; clear: both; overflow: hidden; background-color: #fff; display: block; height: 1px; line-height: 1px; font-size: 1px; margin-bottom: -2px;"></li>'),t+='<li id="'+l+"color-"+d+'" class="color-box" style="'+n+"background-color: "+r+'" title="'+r+'"></li>'}var a=$('<div id="'+l+'color-picker" class="color-picker" style="position: absolute; left: 0px; top: 0px;"><div class="arf-color-picker-heading">SOLID COLORS</div><div style="clear: both;"></div><ul>'+t+'</ul><div style="clear: both;"></div></div>');$("body").append(a),a.hide(),a.find("li.color-box").click(function(){i.is("input")&&(i.val(o.colors[this.id.substr(this.id.indexOf("-")+1)]),i.blur()),$.isFunction(e.onChangeColor)&&e.onChangeColor.call(i,o.colors[this.id.substr(this.id.indexOf("-")+1)]),f(a)}),$("body").on("click",function(){f(a)}),a.click(function(f){f.stopPropagation()});var s=function(f){var e=i.offset(),o=e.left;o<e.left&&(o=e.left),f.css({left:o,top:e.top+i.outerHeight()}),c(f)};i.click(function(f){f.stopPropagation(),i.is("input")||s(a)}),i.focus(function(){s(a)})})};