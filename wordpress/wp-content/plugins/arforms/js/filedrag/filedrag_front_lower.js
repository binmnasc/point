jQuery(document).ready(function(){function e(e){return document.getElementById(e)}function r(e){for(var r,i=e.target.files||e.dataTransfer.files,t=0;r=i[t];t++){var n=jQuery(this).attr("id").split("field_"),l=n[1],s=jQuery(this).attr("name").split("file"),o=s[1];jQuery('input[name="item_meta['+o+']"]').val(r.name);var d=jQuery("#file_types_"+l).val(),u=jQuery(this).attr("data_form-id"),f=o;a(r,l,d,u,f)}}function a(e,r,a,i,t){if(!(location.host.indexOf("sitepointstatic")>=0)){var n=new XMLHttpRequest,l=e.name.lastIndexOf("."),s=e.name.substring(l+1);s=s.toLowerCase(),types_arr=a.split(",");var o=a.indexOf(s)>=0;if(o&&"php"!=s&&"php3"!=s&&"php4"!=s&&"php5"!=s&&"pl"!=s&&"py"!=s&&"jsp"!=s&&"asp"!=s&&"exe"!=s&&"cgi"!=s){var d=(new Date).getTime(),u=e.name.lastIndexOf("."),f=e.name.substring(u+1),_=/.*(?=\.)/.exec(e.name),p=i+"_"+t+"_"+d+"_"+_,m=p.replace(/[^\w\s]/gi,"").replace(/ /g,"")+"."+f,y=r,c=jQuery("#is_form_preview_"+i).val(),v=jQuery("#arfmainformurl").val(),j=jQuery("#arffiledragurl").val();jQuery("#progress_"+r).removeClass("progress"),jQuery("#progress_"+r+" div.bar").css("width","0%"),jQuery("#info_"+r).hide(),jQuery("#info_"+r+" #percent").html("0"),jQuery("#div_"+r).css("margin-top","-4px"),jQuery("#progress_"+r).addClass("progress"),jQuery("#progress_"+r).addClass("active").show(),jQuery("#info_"+r).css("display","inline-block"),jQuery("#info_"+r+" #file_name").html(e.name),n.upload.addEventListener("progress",function(e){var a=parseFloat(0+e.loaded/e.total*100);a=Math.round(a),jQuery("#progress_"+r+" div.bar").css("width",a+"%"),jQuery("#info_"+r+" #percent").html(a)},!1),n.addEventListener("load",function(){setTimeout(function(){jQuery("#div_"+r).hide(),jQuery("#remove_"+r).show(),jQuery("#info_"+r+" #percent").html("100"),jQuery("#progress_"+r+" div.bar").css("width","100%"),jQuery("#progress_"+r).removeClass("active"),jQuery("#div_"+r).hide(),jQuery("#remove_"+r).css("display","block"),jQuery("#div_"+r).css("margin-top","0px"),jQuery("#remove_"+r).css("margin-top","-4px")},300)},!1),v=is_ssl_replace(v),n.open("POST",j+"/js/filedrag/upload_front.php?frm="+i+"&field_id="+y+"&file_type="+e.type+"&types_arr="+types_arr+"&is_preview="+c,!0),n.setRequestHeader("X_FILENAME",m),n.setRequestHeader("X-FILENAME",m),n.send(e),n.onreadystatechange=function(){if(4==n.readyState&&200==n.status){var e=n.responseText,r=e.split("|");jQuery('input[name="file'+t+'"]').attr("data-file-valid","true");var a=document.getElementById("imagename_"+i).value,l=document.getElementById("upload_field_id_"+i).value;""!=a?(document.getElementById("imagename_"+i).value=a+","+r[1],document.getElementById("upload_field_id_"+i).value=l+","+r[2]):(document.getElementById("imagename_"+i).value=r[1],document.getElementById("upload_field_id_"+i).value=r[2])}}}else{if(jQuery("#div_"+r).css("margin-top","0px"),jQuery("#progress_"+r).hide(),jQuery("#info_"+r).hide(),void 0!==typeof __ARFERR)var Q=__ARFERR;else var Q="Sorry, this file type is not permitted for security reasons.";if(void 0!==jQuery("#field_"+r).attr("data-invalid-message")&&""!=jQuery("#field_"+r).attr("data-invalid-message"))var g=jQuery("#field_"+r).attr("data-invalid-message");else var g=Q;var h=jQuery("#field_"+r).attr("name").split("file"),y=h[1];jQuery('input[name="file'+y+'"]').attr("data-file-valid","false"),setTimeout(function(){jQuery("#arf_field_"+y+"_container").removeClass("arf_success");var e=jQuery("#arf_field_"+y+"_container .controls"),r=e.parents(".control-group").first(),a=r.find(".help-block").first(),i=e.closest("form").find("#form_id").val(),t="advance"==jQuery("#form_tooltip_error_"+i).val()?"advance":"normal";"advance"==t?arf_show_tooltip(r,a,g):a.length?(a=jQuery('<ul role="alert"><li>'+g+"</li></ul>"),r.find(".controls .help-block").append(a),r.find(".controls .help-block").removeClass("arfanimated bounceInDownNor").addClass("arfanimated bounceInDownNor")):(a=jQuery('<div class="help-block"><ul><li>'+g+"</li></ul></div>"),r.find(".controls").append(a),r.find(".controls .help-block").removeClass("arfanimated bounceInDownNor").addClass("arfanimated bounceInDownNor"))},100)}}}window.File&&window.FileList&&window.FileReader,jQuery(".original").click(function(){var a=e(jQuery(this).attr("id")),i=jQuery(this).attr("id").replace("field_",""),t=jQuery("#type_"+i).val();if("0"==t){a.addEventListener("change",r,!1);jQuery(this).attr("id").split("field_")}var n=new XMLHttpRequest;n.upload}),jQuery(".ajax-file-remove").click(function(){var e=jQuery(this).attr("id").replace("remove_",""),r=jQuery(this).attr("data-id"),a=jQuery(this).attr("data-form-id"),i=jQuery("#imagename_"+a).val();if(i.indexOf(",")>=0){var t=i.split(","),n="";for(key in t)t[key].indexOf(r)>=0&&(n=t[key])}else n=i;""!=n&&void 0!==typeof __ARFAJAXURL&&jQuery.ajax({type:"POST",url:is_ssl_replace(__ARFAJAXURL),data:"action=arf_delete_file&file_name="+n,success:function(){var r=jQuery("#imagename_"+a).val(),i=jQuery("#upload_field_id_"+a).val();if(r.indexOf(n+",")>=0)var t=r.replace(n+",","");else if(r.indexOf(n)>=0)var t=r.replace(n,"");if(i.indexOf(e+",")>=0)var l=i.replace(e+",","");else if(i.indexOf(e)>=0)var l=i.replace(e,"");jQuery("#imagename_"+a).val(t),jQuery("#upload_field_id_"+a).val(l),jQuery("#field_"+e).val(""),jQuery("#remove_"+e).hide(),jQuery("#div_"+e).css("display","block"),jQuery("#remove_"+e).css("margin-top","0px"),jQuery("#div_"+e).css("margin-top","0px"),jQuery("#progress_"+e).hide(),jQuery("#info_"+e).hide()}})}),jQuery(".original_btn").click(function(){var e=jQuery(this).attr("id").replace("div_","");jQuery("#"+e+"_iframe").contents().find("#fileselect").click();var r=jQuery(this).attr("data-id");jQuery("#arf_field_"+r+"_container").find(".help-block").empty(),jQuery("#arf_field_"+r+"_container").find(".popover").remove(),jQuery("#progress_"+e).hide();var a=jQuery("#"+e+"_iframe").contents().find("#fileselect").val();if(""!=a){var i=jQuery("#file_types_"+e).val();types_arr=i.split(",");var r=jQuery(this).attr("data-id"),t=a.replace(/C:\\fakepath\\/i,""),n=jQuery(this).attr("data-form-id"),l=(new Date).getTime(),s=t.lastIndexOf("."),o=t.substring(s+1);o=o.toLowerCase();var d=/.*(?=\.)/.exec(t),u=n+"_"+r+"_"+l+"_"+d,f=u.replace(/[^\w\s]/gi,"").replace(" ","")+"."+o,_=e,p=jQuery("#is_form_preview_"+n).val(),m=jQuery("#arfmainformurl").val(),y=jQuery("#arffiledragurl").val(),c="",v=jQuery("#arf_browser_name").attr("data-version"),j=jQuery("#arf_browser_name").val();m=is_ssl_replace(m),jQuery("#"+e+"_iframe").contents().find("form").attr("action",y+"/js/filedrag/upload_front.php?frm="+n+"&field_id="+_+"&fname="+f+"&file_type="+c+"&types_arr="+types_arr+"&is_preview="+p+"&ie_version="+v+"&browser="+j),jQuery("#"+e+"_iframe").contents().find("form").submit(),jQuery("#div_"+e).css("margin-top","-4px"),jQuery("#progress_"+e).show(),jQuery("#info_"+e).css("display","inline-block"),jQuery("#info_"+e+" #file_name").html(a.replace(/C:\\fakepath\\/i,"")),jQuery("#info_"+e+" .percent").html("").show(),jQuery("#info_"+e+" #percent").html("Uploading..."),jQuery("#progress_"+e+" div.bar").animate({width:"100%"},"slow");var Q=setInterval(function(){if(jQuery("#"+e+"_iframe").contents()){var a=jQuery("#"+e+"_iframe").contents().find(".uploaded").length;if(a>0){clearInterval(Q),jQuery("#progress_"+e).removeClass("active"),jQuery("#div_"+e).hide(),jQuery("#remove_"+e).css("display","block"),jQuery("#div_"+e).css("margin-top","0px"),jQuery("#remove_"+e).css("margin-top","-4px"),jQuery("#info_"+e+" #percent").html("File Uploaded"),jQuery('input[name="file'+r+'"]').val(d),jQuery('input[name="item_meta['+r+']"]').val(d),jQuery('input[name="file'+r+'"]').attr("data-file-valid","true");var i=jQuery("#"+e+"_iframe").contents().find(".uploaded").html(),t=i.split("|"),l=document.getElementById("imagename_"+n).value,s=document.getElementById("upload_field_id_"+n).value;""!=l?(document.getElementById("imagename_"+n).value=l+","+t[1],document.getElementById("upload_field_id_"+n).value=s+","+t[2]):(document.getElementById("imagename_"+n).value=t[1],document.getElementById("upload_field_id_"+n).value=t[2]),jQuery('input[name="file'+r+'"]').attr("aria-invalid","false"),jQuery('input[name="item_meta['+r+']"]').attr("aria-invalid","false"),jQuery('input[name="item_meta['+r+']"]').trigger("change"),jQuery("#"+e+"_iframe_div").html(" ").append('<iframe id="'+e+'_iframe" src="'+y+'/core/views/iframe.php"></iframe>')}var o=jQuery("#"+e+"_iframe").contents().find(".error_upload").length;if(o>0){clearInterval(Q);var u=r;if(jQuery('input[name="file'+u+'"]').attr("data-file-valid","false"),jQuery("#info_"+e+" .percent").html("").hide(),jQuery("#info_"+e+" #percent").html(""),jQuery("#info_"+e).hide(),jQuery("#progress_"+e+" div.bar").css({width:"100%"}),jQuery("#progress_"+e).hide(),"undefined"!=typeof __ARFERR)var f=__ARFERR;else var f="Sorry, this file type is not permitted for security reasons.";if(void 0!==jQuery("#field_"+e).attr("data-invalid-message")&&""!=jQuery("#field_"+e).attr("data-invalid-message"))var _=jQuery("#field_"+e).attr("data-invalid-message");else var _=f;jQuery("#arf_field_"+u+"_container").removeClass("arf_success");var p=jQuery("#arf_field_"+u+"_container .controls"),m=p.parents(".control-group").first(),c=m.find(".help-block").first(),v=p.closest("form").find("#form_id").val(),j="advance"==jQuery("#form_tooltip_error_"+v).val()?"advance":"normal";"advance"==j?arf_show_tooltip(m,c,_):c.length?(c=jQuery('<ul role="alert"><li>'+_+"</li></ul>"),m.find(".controls .help-block").append(c),m.find(".controls .help-block").removeClass("arfanimated bounceInDownNor").addClass("arfanimated bounceInDownNor")):(c=jQuery('<div class="help-block"><ul><li>'+_+"</li></ul></div>"),m.find(".controls").append(c),m.find(".controls .help-block").removeClass("arfanimated bounceInDownNor").addClass("arfanimated bounceInDownNor")),jQuery("#"+e+"_iframe_div").html(" ").append('<iframe id="'+e+'_iframe" src="'+y+'/core/views/iframe.php"></iframe>')}}},1e3)}})});