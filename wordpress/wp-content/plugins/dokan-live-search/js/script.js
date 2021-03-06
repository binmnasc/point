(function($){

    $(document).ready(function($){
        
        var xhr ,timeout;

        $('body').addClass('woocommerce');

        $('.ajaxsearchform').on('submit',function(e){
            e.preventDefault();
        })

        $('#page').on('keyup','.dokan-ajax-search-textfield', function(evt){

            evt.preventDefault();
          
            var self = $(this);
            var nurl = self.closest('form').attr('action');
            var textfield = self.val();
            var selectfield = self.closest('.ajaxsearchform').find('.dokan-ajax-search-category').val();

            var ordershort = $('.woocommerce-ordering .orderby').val();

            if(evt.type != 'change'){
                var charCode = (evt.which) ? evt.which : event.keyCode;
            } 
             
            if (charCode > 64 && charCode < 91 || charCode > 96 && charCode < 123 || charCode > 47 && charCode < 58 || charCode == 8 || charCode == 127 ) {   
                for_onkeyup_onchange(evt,self, nurl, textfield, selectfield, ordershort);
            }
        });

        $('#page').on('change', '.dokan-ajax-search-category', function(e) {
            e.preventDefault();

            var self = $(this);
            var nurl = self.closest('form').attr('action');
            var textfield = self.closest('.ajaxsearchform').find('.dokan-ajax-search-textfield').val();
            var selectfield = self.val();
            var ordershort = $('.woocommerce-ordering .orderby').val();

            for_onkeyup_onchange(e, self, nurl, textfield, selectfield, ordershort );
        });
        
        function for_onkeyup_onchange( evt, self, nurl, textfield, selectfield, ordershort ) {

            if(!ordershort){
                ordershort = '';
            }

            if(selectfield == 'All' && evt.type == 'change' && ordershort == 'menu_order'){
                 
                var url = nurl +'?s='+ textfield.replace(/\s/g,"+")+'&post_type=product';
                loading_get_request( url, textfield, selectfield );

            } else if(selectfield == 'All' && ordershort == 'menu_order') {

                var url = nurl +'?s='+ textfield.replace(/\s/g,"+")+'&post_type=product';
                loading_get_request( url, textfield, selectfield );

            } else if(selectfield == 'All' && ordershort != 'menu_order') {

                var url = nurl +'?s='+ textfield.replace(/\s/g,"+")+'&post_type=product&orderby='+ordershort;
                loading_get_request( url, textfield, selectfield );

            }else if(selectfield != 'All' && ordershort == 'menu_order'){

                var url = nurl +'?s='+ textfield.replace(/\s/g,"+")+'&post_type=product&product_cat='+ selectfield;
                loading_get_request( url, textfield, selectfield );

            } else {

                var url = nurl +'?s='+ textfield.replace(/\s/g,"+")+'&post_type=product&product_cat='+ selectfield + '&orderby=' + ordershort;
                loading_get_request( url, textfield, selectfield );

            }
        }

        function loading_get_request( url, textfield, selectfield ){

            $('#content').append('<div id="loading"><img src="' + dokanLiveSearch.loading_img + '" atr="Loding..."/></div>');
            $('#content').css({'opacity':0.3,'position':'relative'});
            $('#loading').show();

            clearTimeout(timeout);

            if(xhr) { 
            xhr.abort(); 
            }

            timeout = setTimeout(function(){
             xhr = get_ajax_request( url, textfield, selectfield );
            },150); 
        }

        function get_ajax_request( url, textfield, selectfield ){

            xhr = $.get(url, function(resp, status) {
                                  
                //window.history.pushState({"html":resp,"pageTitle":resp.pageTitle},"", url);  
                var dom = $(resp).find('#container');
            
                $('.dokan-ajax-search-textfield').val( textfield );
                $('.dokan-ajax-search-category').val( selectfield );
                $('#content').html(dom);
                

                $('#loading').hide();
                $('#content').css({'opacity':1,'position':'auto'});

                if(dokanLiveSearch.single){
                    console.log('single');
                }

                $('.woocommerce-ordering').on('change','.orderby',function(e){
                    e.preventDefault();
                    
                    var self = $(this);
                    var nurl = $('.ajaxsearchform').attr('action');
                    var textfield = $('.dokan-ajax-search-textfield').val();
                    var selectfield = $('.dokan-ajax-search-category').val();
                    var ordershort = self.val();
                        
                    for_onkeyup_onchange(e, self, nurl, textfield, selectfield, ordershort );
                    
                });  

            });

            return xhr;
        }
    });

})(jQuery)


