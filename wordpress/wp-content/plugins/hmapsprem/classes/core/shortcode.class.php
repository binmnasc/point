<?php
	
	#PLUGIN SHORTCODE MANAGEMENT
	class hmapsprem_shortcodes{
		
		#CLASS VARS
		private $shortcode; //plugin shortcode is the same as the plugin name
		private $plugin_prefix;
		private $plugin_dir;
		private $plugin_url;
		private $display;
		private $frontend;
		
		#CONSTRUCT
		public function __construct($plugin_prefix,$plugin_name,$plugin_dir,$plugin_url){
			$this->plugin_prefix = $plugin_prefix;
			$this->shortcode = $plugin_name;
			$this->plugin_dir = $plugin_dir;
			$this->plugin_url = $plugin_url;
			$this->display = new hmapsprem_display($this->plugin_dir);
			$this->frontend = new hmapsprem_frontend();
		}
		
		#INITIALISE SHORTCODE LISTENER
		public function initialise_shortcode_listener(){
			//remove shortcode listener
			remove_shortcode($this->shortcode);
			//add shortcode listener
			add_shortcode($this->shortcode, array(&$this,'use_shortcode'));
		}
		
		#USE SHORTCODE
		public function use_shortcode($atts){ //all front-end code can be initialised here...

			//load front-end css
			$this->load_frontend_css();
			//load front-end scripts
			$this->load_frontend_javascript();
			//output front-end JS references
			echo '
				<script type="text/javascript" data-cfasync="false">
					var ajax_url = "'. admin_url('admin-ajax.php') .'";
					var '. $this->plugin_prefix .'url = "'. $this->plugin_url .'";
				</script>
			';
			//define content
			$content = $this->frontend->get_shortcode_content($atts);
			//display content on front-end
			return $this->display->output_frontend($content); //this ensure output buffering takes place
		}
		
		#IMPLEMENT FRONT-END JS
		private function load_frontend_javascript(){
			//front-end javascript
			wp_register_script($this->plugin_prefix .'cluster', $this->plugin_url .'assets/js/markerclusterer.js');
			wp_enqueue_script($this->plugin_prefix .'cluster');
			wp_register_script($this->plugin_prefix .'user', $this->plugin_url .'assets/js/frontend_script.js');
			wp_enqueue_script($this->plugin_prefix .'user');
		}		
		
		#IMPLEMENT FRONT-END CSS
		private function load_frontend_css(){
			//front-end css
			wp_register_style($this->plugin_prefix .'userstyles', $this->plugin_url .'assets/css/frontend_styles.css');
			wp_enqueue_style($this->plugin_prefix .'userstyles');
		}
		
	}