<script type="text/javascript" src="<?php echo htmlspecialchars($_GET['vp'], ENT_QUOTES, 'UTF-8'); ?>js/marker_custom_upload_view.view.js" data-cfasync="false"></script>
<div class="hero_views">
    <div class="hero_col_12">
    	<h1 class="hero_red size_18">
            Custom Markers<br />
            <strong class="size_11 hero_grey">Upload and manage custom location markers</strong>
        </h1>
        
        <div class="hero_section_holder hero_grey size_14"> 
            <div class="hero_col_12">
                <h3 class="hero_grey">Upload</h3>
                <p>
                	Click "Choose File" and select your custom marker image (.png). The location marker will be automatically installed and available in Maps.
                </p>
            </div>
            <div class="custom_marker_upload_holder"></div>
            <p>
            	<i class="size_12">Custom marker images must be in <b>PNG</b> format. Image width and height must be between <b>15px</b> and <b>150px</b>.</i>
            </p>
        </div>
        
        <div id="custom_marker_table_holder">
        </div>
        
	</div>
</div>