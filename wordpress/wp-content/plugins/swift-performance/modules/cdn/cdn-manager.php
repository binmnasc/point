<?php
class Swift_Performance_CDN_Manager{

	public $cdn = array();

	public $site_url = '';

	public function __construct(){
		//Use CDN only on frontend
		if (Swift_Performance::is_admin()){
			return false;
		}

		$this->site_url = preg_replace('~http(s)?://~','',home_url());

		// Set CDN hostnames
		$this->cdn['css']	= preg_replace('~http(s)?://~','',Swift_Performance::get_option('cdn-hostname-master'));
		$this->cdn['js']	= (Swift_Performance::check_option('cdn-hostname-slot-1','','!=') ? preg_replace('~http(s)?://~','',Swift_Performance::get_option('cdn-hostname-slot-1')) : $this->cdn['css']);
		$this->cdn['media']	= (Swift_Performance::check_option('cdn-hostname-slot-2','','!=') ? preg_replace('~http(s)?://~','',Swift_Performance::get_option('cdn-hostname-slot-2')) : $this->cdn['css']);

		if (isset($_SERVER['HTTPS'])){
			if (Swift_Performance::check_option('enable-cdn-ssl','enabled','!=')){
				return false;
			}

			$ssl_master = false;
			if (Swift_Performance::check_option('cdn-hostname-master-ssl','','!=')){
				$this->cdn['css'] = preg_replace('~http(s)?://~','',Swift_Performance::get_option('cdn-hostname-master-ssl'));
				$ssl_master = true;
			}

			$ssl_slot_1 = false;
			if (Swift_Performance::check_option('cdn-hostname-slot-1-ssl','','!=')){
				$this->cdn['js'] = preg_replace('~http(s)?://~','',Swift_Performance::get_option('cdn-hostname-slot-1-ssl'));
				$ssl_slot_1 = true;
			}
			else if($ssl_master){
				$this->cdn['js'] = $this->cdn['css'];
			}

			if (Swift_Performance::check_option('cdn-hostname-slot-2-ssl','','!=')){
				$this->cdn['media'] = preg_replace('~http(s)?://~','',Swift_Performance::get_option('cdn-hostname-slot-2-ssl'));
			}
			else if($ssl_slot_1){
				$this->cdn['media'] = $this->cdn['js'];
			}
			else if ($ssl_master){
				$this->cdn['media'] = $this->cdn['css'];
			}
		}

		if (empty($this->cdn['css'])){
			return false;
		}

		add_filter('script_loader_src', array($this, 'js'),0,2);
		add_filter('style_loader_src', array($this, 'css'),0,2);
		add_filter('swift_performance_media_host', array($this, 'media_host_filter'));
		add_action('init', array($this, 'media'));
	}

	/**
	 * Start output buffering for media files
	 */
	public function media(){
		ob_start(array($this, 'media_callback'));
	}

	/**
	 * Replace media files callback
	 */
	public function media_callback($buffer){
		return preg_replace('~'.$this->site_url.'([^"\'\s]*)\.(jpe?g|png|gif|swf|flv|mpeg|mpg|mpe|3gp|mov|avi|wav|flac|mp2|mp3|m4a|mp4|m4p|aac)~i', $this->cdn['media']."$1.$2", $buffer);
	}

	/**
	 * Replace media files host
	 */
	public function media_host_filter($url){
		return preg_replace('~'.$this->site_url.'~i', $this->cdn['media'], $url);
	}

	/**
	 * Change hostname for js files
	 */
	public function js($url, $handle = ''){
		return preg_replace('~^(http(s)?://)?' . preg_quote($this->site_url) . '~', "$1{$this->cdn['js']}", $url);
	}

	/**
	 * Change hostname for css files
	 */
	public function css($url, $handle = ''){
		return preg_replace('~^(http(s)?://)?' . preg_quote($this->site_url) . '~', "$1{$this->cdn['css']}", $url);
	}

	/**
	 * Purge CDN
	 * Currently MaxCDN supported only
	 */
	public static function purge_cdn(){
		require_once 'maxcdn.php';
		$admin_notices = array();
		if (Swift_Performance::check_option('enable-cdn', 1) && Swift_Performance::check_option('maxcdn-alias', '','!=') && Swift_Performance::check_option('maxcdn-key', '','!=') && Swift_Performance::check_option('maxcdn-secret', '','!=')){
			try {
				$maxcdn = new Swift_Performance_MaxCDN(Swift_Performance::get_option('maxcdn-alias'),Swift_Performance::get_option('maxcdn-key'),Swift_Performance::get_option('maxcdn-secret'));

				$response = json_decode($maxcdn->get('/zones.json'),true);
				if ($response['code'] == '200'){
					$zones = $response['data']['zones'];
				}

				foreach ((array)$zones as $zone){
					$response = json_decode($maxcdn->delete('/zones/pull.json/'.$zone['id'].'/cache'));
					if (isset($response->code) && $response->code == '200'){
						Swift_Performance::add_notice(sprintf(esc_html__('Purge Cache: Zone Purged [id: %s]', 'swift_performance'), $zone['id']), 'success');
					}
					else if (isset($response->error->message) && !empty($response->error->message)){
						Swift_Performance::add_notice($response->error->message, 'warning');
					}
					else{
						Swift_Performance::add_notice(sprintf(esc_html__('Purge Cache: Unknown error[id: %s]', 'swift_performance'), $zone['id']), 'error');
					}
				}
			}
			catch(Exception $e){
				Swift_Performance::add_notice($e->getMessage(), 'error');
			}
		}
	}

}

return new Swift_Performance_CDN_Manager();
?>
