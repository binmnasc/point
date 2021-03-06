<script type="text/javascript" src="<?php echo htmlspecialchars($_GET['vp'], ENT_QUOTES, 'UTF-8'); ?>js/marker_packs_view.view.js" data-cfasync="false"></script>
<div class="hero_views">
    <div class="hero_col_12">
    	<h1 class="hero_red size_18">
            Marker Packs<br />
            <strong class="size_11 hero_grey">View available location markers</strong>
        </h1>
        
        <div class="hero_section_holder hero_grey size_14"> 
            <div class="hero_col_12">
                <div class="hero_col_9">
                    <label for="marker_animation" style="cursor:default;">
                        <h2 class="size_14 hero_green">Marker Packs</h2>
                        <p class="size_12 hero_grey">Preview location markers in each pack</p>
                    </label>
                </div>
                <div class="hero_col_3">
                    <select data-size="lrg" id="marker_category" name="marker_category">
                    </select>
                </div>
            </div>
        </div>
        
        <div class="hero_section_holder hero_grey size_14"> 
            <div class="hero_col_12">
				<div class="hero_col_4">
                	<h2 class="size_14 hero_green">Color Scheme</h2>
                    <p class="size_12 hero_grey">Color options available in this marker pack</p>
                </div>
                <div class="hero_col_8">
                    <div class="hero_preset_holder"></div>
                </div>
        	</div>
        </div>
        
        <div id="marker_display_holder"></div>
        
        <div style="clear:both; width:100%; height:20px; border-bottom:1px solid #efefef;"></div>
        
        <div class="hero_section_holder hero_grey size_14"> 
            <div class="hero_col_12">
				<div class="hero_col_4">
                	<h2 class="size_14 hero_green">Download additional marker packs</h2>
                    <p class="size_12 hero_grey">Many markers and colors available</p>
                </div>
                <div class="hero_col_8">
                    <div style="float:right; margin-right:0;" onclick="window.open('http://heroplugins.com/downloads/');" class="hero_button_auto red_button rounded_3">Download Now</div>
                </div>
        	</div>
            <div class="hero_col_12">
            	<img id="additional_markers_img" width="100%">
                <script type="text/javascript" data-cfasync="false">
					jQuery(function(){
						jQuery('#additional_markers_img').attr('src', plugin_url +'assets/images/additional_markers.jpg');
					});
				</script>
            </div>	
        </div>
        
	</div>
</div>