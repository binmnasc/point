function FiletripBackendUploader(e,r,i){return void 0===e.ajax_url||void 0===r.channel_action_url?void console.log("FiletripBackendUploader: Bad input"):(this.actionName=r.channel_action_url,this.actionUrl=e.ajax_url+"?action="+this.actionName+"&security="+r.security+"&mediaID="+i.id,r.hasOwnProperty("is_subfolder")?this.actionUrl+="&subfolder="+r.is_subfolder:this.actionUrl+="&subfolder=false",0!=r.destination?this.actionUrl+="&target_folder="+window.btoa(r.destination):this.actionUrl+="&target_folder=false",0!=e.file_path&&(this.actionUrl+="&file_path="+e.file_path),r.actionUrl=this.actionUrl,void(this.sendFile=function(){return new Promise(function(e,t){var n=new EventSource(r.actionUrl);console.log(r.actionUrl);var a="#filetrip-upload-card-"+r.channel_key+i.id,s=function(e){var r=JSON.parse(e.data),t=jQuery(a).find(".filetrip-transfer-progress-fg"),n=jQuery(a).find(".filetrip-transfer-progress-label");t.width(r.percentage+"%"),n.text(r.percentage+"%"),jQuery(a).find("span.filetrip-transfer-progress-bytes").empty(),jQuery(a).find("span.filetrip-transfer-progress-bytes").append(bytesToSize(r.bytes)+" / "+i.file_size_friendly)},l=function(r){var t=(r.type,jQuery(a).find(".filetrip-transfer-progress-fg")),s=jQuery(a).find(".filetrip-transfer-progress-label");return t.width("100%"),s.text("100%"),jQuery(a).find("div.arfaly-drive-folder-loading").remove(),jQuery(a).find("span.filetrip-transfer-progress-bytes").empty(),jQuery(a).find("span.filetrip-transfer-progress-bytes").append(i.file_size_friendly+" / "+i.file_size_friendly),jQuery(a).find("span.filetrip-transfer-stage").append('<span class="stage succeed active filetrip-pull-left">Transferred</span>'),n.close(),e("success")},f=function(e){var i='<a href="#" class="help_tip filetrip-help-pointer--icon" data-title="Help information" data-tip="['+e.data+']">?</a>';return jQuery(a).find("div.arfaly-drive-folder-loading").remove(),jQuery(a).find("span.filetrip-transfer-stage").append('<span class="stage failed active">Failed</span>'),jQuery(a).find(".stage-channel-icon").remove(),jQuery(a).find(".filetrip-transfer-progress-label").empty(),jQuery(a).find(".filetrip-transfer-progress-label").append(r.channel_icon),jQuery(a).find(".filetrip-transfer-completes").empty(),jQuery(a).find(".filetrip-transfer-completes").append(i),prepare_help_tip(jQuery),n.close(),t("FiletripBackendUploader: Error occured during file transfer")};n.addEventListener("message",s,!1),n.addEventListener("error",f,!1),n.addEventListener("finished",l,!1)})}))}