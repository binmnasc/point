<?php
/**
 * Example Extension
 *      Example extension just like below
 *      Use Extension Name Unique as Possible, because same Name Will Be [Override Able]
 *
 * @method init() as initialization after active
 */
namespace iTechFlare\WP\iTechFlareExtension;

use iTechFlare\WP\Plugin\Core\Abstracts\FlareExtension;
use iTechFlare\WP\Plugin\Core\Helper\LoaderOnce;

/**
 * Class Example
 * @package iTechFlare\WP\iTechFlareExtension
 */
class FtpITF extends FlareExtension
{
	/**
	 * @var string
	 */
	protected $extension_name = 'FTP Channel';

	/**
	 * @var string
	 */
	protected $extension_uri = 'https://itechflare.com'; // with or without http://

	/**
	 * @var string
	 */
	protected $extension_author = 'iTechFlare';

	/**
	 * @var string
	 */
	protected $setting_field_type = 'ftp_select';
	
	/**
	 * @var string
	 */
	protected $meta_field_type = 'ftp_folder_select';

	/**
	 * @var string
	 */
	protected $select_folder_id = 'ftp_folder';

	/**
	 * @var string
	 */
	protected $field_slug = 'ftp';

	/**
	 * @var string
	 */
	protected $field_name = 'FTP';

	/**
	 * @var string
	 */
	protected $ajax_action_ref = 'ftp_send_file';
	/**
	 * @var string
	 */
	protected $extension_author_uri = 'https://itechflare.com';

	/**
	 * @var string
	 */
	protected $extension_version = '1.2';

	/**
	 * @var string
	 */
	protected $extension_description = 'Activate to start transferring Filetrip Uploads to FTP. Go to Settings to complete your configuration.';
	/**
	 * @var string
	 *      fill with full URL to Extension icon
	 *      please use Square dimension :
	 *      128px square max 256px
	 *      Extension must be transparent png
	 */
	protected $extension_icon; // fill with icon url
	/**
	 * Initials
	 */
	public function init()
	{	
		// do module
		// ************************* Bootstrapping *************
		if (current_user_can('activate_plugins')) {
			// include
			LoaderOnce::load( __DIR__ .'/ftp/class-utilities.php');
			LoaderOnce::load( __DIR__ .'/ftp/class-filetrip-ftp.php');

			// Init
			define("FtpITF_ACTIVE", true);

			// Filters 
			add_filter( 'itf/filetrip/channel/selection_filter', array($this, 'add_meta_channel_configuration'));
			add_filter( 'itf/filetrip/settings/add/section_fields', array($this, 'add_setting_field'));

			// Register this channel to the core channel distributor [Filetrip_Distributor]
			add_filter( 'itf/filetrip/filter/register/channels', array($this, 'register_me_to_channels'));
			add_filter( 'itf/filetrip/filter/channels/media/dest', array($this, 'register_me_as_forwarder'), 10, 2);

			// FTP meta type to render folder selection widget
			add_action( 'wp_ajax_' . $this->ajax_action_ref, array($this, 'send_media_to_cloud'), 10 );
			add_action( 'wp_ajax_' . $this->ajax_action_ref . '_backup', array($this, 'send_file_to_cloud'), 10 );
			add_action( 'filetrip_cmb_render_' . $this->meta_field_type, array($this, 'folder_select_widget'), 10, 5 );
			add_action( 'itf/main_setting/add_field/'.$this->setting_field_type, array($this, 'add_settings_field_callback'), 10, 4);
			add_action( 'admin_init', array($this ,'init_admin_hooks') );

			// Deamon transfer / CLI Mode: Transfer files when they are been auto-approve
			add_action( 'itf/filetrip/upload/forware/me', array($this ,'CLI_forward_upload'), 10, 3);

			/**
			* Hook to admin footer to inject jQuery for Media action addition
			*/
			add_action('admin_footer-upload.php', array( $this, 'custom_bulk_admin_media_library_footer'));

			$this->ftp_settings = (array)get_option(\Filetrip_FTP_Setting_page::$ftp_settings_slug);

			//================ FTP Initiation and handling ============
			//==================================================================
			$ftp_obj = new \Filetrip_FTP($this->ftp_settings);

			// Check if authorization action was triggered
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'do_ftp_auth' && wp_verify_nonce( $_REQUEST['arfaly_nonce'], \Filetrip_Constants::NONCE) )
			{
				$ftp_obj->auth_start();
			}
		}
	}

	/**
	* Responsible of forwarding uploads with auto-approve setting set to ON
	*/
	function CLI_forward_upload($att_id, $title, $description)
	{
		$att_info = get_post($att_id);
		$selectedChannels = \Filetrip_Channel_Utility::get_channel_selected($att_info->post_parent);

		foreach($selectedChannels as $select)
		{
			if($select == $this->field_slug){
				// I am in his list of seleced channel. Let's send the file
				$file_path = get_attached_file( $att_id );
				$destination = $this->get_meta_destination_folder($att_info->post_parent);

				\Filetrip_FTP::resumable_file_upload($destination, $file_path, false);
			}
		}
	}

	function custom_bulk_admin_media_library_footer() {
 
		?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('<option>').val('<?php echo $this->field_slug; ?>').text('<?php _e('Filetrip to '.$this->field_name, 'filetrip-plugin'); ?>').appendTo("select[name='action']");
				jQuery('<option>').val('<?php echo $this->field_slug; ?>').text('<?php _e('Filetrip to '.$this->field_name, 'filetrip-plugin'); ?>').appendTo("select[name='action2']");
			});
			</script>
		<?php
	}

	/**
	 * @param string {folder_destination}:
	 * @param string {targetFileURLPath}:
	 * @param bool {subfolder_enabled}:
	 * @param string {username}:
	 *      
	 *	@return EventSource data message
	 */
	function send_file_to_cloud( )
	{
		header("Content-Type: text/event-stream");
		header("Cache-Control: no-cache");
		
		/**
		*  Parse {security}
		*/
		if( !isset($_GET['security']) || !wp_verify_nonce( $_GET['security'], $this->ajax_action_ref) ){
			/**
			*  Construct SSE message and echo it to the client
			*/
			\Filetrip_Channel_Utility::sse_send_message("", "Invalid security check", "error");
			die();
		}

		/**
		*  Parse {$file_path}
		*/
		if( !isset($_GET['file_path']) ){
			/**
			*  Construct SSE message and echo it to the client
			*/
			\Filetrip_Channel_Utility::sse_send_message("", "FTP: No filepath was attached", "error");
			die();
		}
		$file_path = base64_decode($_GET['file_path']);

		/**
		*  Parse {subfolder}
		*/
		$username = 'Guest';
		$subfolder_enabled = false;

		/**
		*  Parse {subfolder}
		*/
		$destination = (isset($_GET['target_folder']) && $_GET['target_folder']!="false")?base64_decode($_GET['target_folder']):'';

		\Filetrip_FTP::resumable_file_upload($destination, $file_path);

	}

	/**
	 * @param string {folder_destination}:
	 * @param string {targetFileURLPath}:
	 * @param bool {subfolder_enabled}:
	 * @param string {username}:
	 *      
	 *	@return EventSource data message
	 */
	function send_media_to_cloud( )
	{
		header("Content-Type: text/event-stream");
		header("Cache-Control: no-cache");

		/**
		*  Parse {mediaID}
		*/
		if( !isset($_GET['mediaID']) || intval($_GET['mediaID']) <= 0 ){
			/**
			*  Construct SSE message and echo it to the client
			*/
			\Filetrip_Channel_Utility::sse_send_message("", "Invalid attachment ID", "error");
			die();
		}
		$att_id = intval($_GET['mediaID']);

		/**
		*  Parse {security}
		*/
		if( !isset($_GET['security']) || !wp_verify_nonce( $_GET['security'], $this->ajax_action_ref) ){
			/**
			*  Construct SSE message and echo it to the client
			*/
			\Filetrip_Channel_Utility::sse_send_message("", "Invalid security check", "error");
			die();
		}

		/**
		*  Parse {subfolder}
		*/
		$username = 'Guest';
		$subfolder_enabled = false;
		$post = get_post($att_id);

		if(isset($_GET['subfolder']) && $_GET['subfolder']!='false' ){
			$subfolder_enabled = true;
			$userid = $post->post_author;

			if($userid == 0)
			{
				$username = 'Guest';
			}else{ 
				$user_info = get_userdata($userid);
				$username = $user_info->user_login;
			}
		}

		/**
		*  Parse {subfolder}
		*/
		$file_path = get_attached_file( $att_id );
		$destination = (isset($_GET['target_folder']) && $_GET['target_folder']!="false")?base64_decode($_GET['target_folder']):'';

		\Filetrip_FTP::resumable_file_upload($destination, $file_path);
	}

	/**
	 * @param array 
	 *      This function responsible of registering this channel to the core 
	 *		Filetrip channel distributor [Filetrip_Distributor]. Also it should pass
	 *		all the necessary information to the client js.
	 */
	function register_me_to_channels($channels)
	{
			/**
			** Channel array structure:
					array(
						'channel_key' => 'key',
						'channel_name' => 'name',
						'channel_icon' => 'path/img',
						'channel_action_url' => 'action_url'
					)
			**/

			$filetrip_settings = \Filetrip_Uploader::get_filetrip_main_settings();

			$destination = isset($filetrip_settings[$this->select_folder_id])?$filetrip_settings[$this->select_folder_id]:'';
			
			$channels[$this->field_slug] = array(
				'destination' => $destination,
				'channel_key' => $this->field_slug,
				'channel_name' => $this->field_name,
				'channel_icon' => $this->get_icon('', '', '20', ''),
				'channel_action_url' => $this->ajax_action_ref,
				'active' => \Filetrip_FTP::is_ftp_active(),
				'security' => wp_create_nonce( $this->ajax_action_ref )
			);

			return $channels;
	}

	/**
	 * @param (int) ($uploader_id) 
	 *      Gets attachment ID and find if this channel is selected as forwarding channel, and retrive destination.
	 */
	function register_me_as_forwarder($forwardingChannels, $uploader_id)
	{
		$post = get_post($uploader_id);
		$selectedChannels = \Filetrip_Channel_Utility::get_channel_selected($post->post_parent);

		/**
		* Check if this channel is been selected for the designated uploader
		*/
		foreach($selectedChannels as $select)
		{
			if($select == $this->field_slug){
				$forwardingChannels[] = array(
					'destination' => $this->get_meta_destination_folder($post->post_parent),
					'channel_key' => $this->field_slug,
					'channel_name' => $this->field_name,
					'channel_icon' => $this->get_icon('', '', '20', ''),
					'channel_action_url' => $this->ajax_action_ref,
					'active' => \Filetrip_FTP::is_ftp_active(),
					'security' => wp_create_nonce( $this->ajax_action_ref )
				);
			}
		}

		return $forwardingChannels;
	}

	function get_meta_destination_folder($post_id)
    {
        $meta = get_post_meta( $post_id );
        if(isset($meta[\Filetrip_Constants::METABOX_PREFIX . $this->select_folder_id]))
        {
          $folder = $meta[\Filetrip_Constants::METABOX_PREFIX . $this->select_folder_id][0];
          return $folder;
        }
        
        return false;
    }

	function init_admin_hooks() {
		// All hooks that need to be called at admin_init trigger should be placed hear 
		add_action( 'manage_media_custom_column', array($this, 'insert_channel_hyperlinked_icon'), 10, 2 );
	}


	function folder_select_widget($field_args, $escaped_value, $object_id, $object_type, $field_type_object ) {
		\Filetrip_FTP_Utility::build_select_folder_widget($field_args, $field_type_object, true);
	}

	function add_meta_channel_configuration($meta_config_array)
	{
		// Add checkbox field
		$meta_config_array['fields']['channels']['options'][$this->field_slug] = $this->get_icon();

		// Add folder selection field
		$meta_config_array['fields'][] = array(
              'name'    => 'FTP folder',
              'desc'    => 'Select the destination folder for your FTP channel',
              'id'      => \Filetrip_Constants::METABOX_PREFIX . $this->select_folder_id,
              'type'    => $this->meta_field_type,
              'attributes' => array('readonly'=>'')
           );

		return $meta_config_array;
	}

	function add_setting_field($setting_array)
	{
		$setting_array[\Filetrip_Constants::POST_TYPE.'_settings'][] = array(
					'name' => $this->select_folder_id,
					'label' => __( 'Select FTP Folder', 'filetrip-plugin' ),
					'desc' => __( 'Select default FTP folder as Media destination', 'filetrip-plugin' ),
					'type' => $this->setting_field_type,
					'default' => '',
				);

		return $setting_array;
	}

	function add_settings_field_callback( $args, $section, $option, $obj)
	{
		// Render my setting
		add_settings_field( $section . '[' . $option['name'] . ']', $option['label'], 
		function() use($args, $obj) {
			\Filetrip_FTP_Utility::build_select_folder_widget($args, $obj);
		}
		, $section, $section, $args );
	}

	function get_icon($link = '', $title = '', $size = '30', $label = 'FTP')
	{
		// If there is no link? Don't add href 
		$hrefStart = '<a target="_blank" href="'.$link.'">';
		$hrefEnd = '</a> ';

		if( $link == '' )
		{
			$hrefStart = '';
			$hrefEnd = '';
		}
		
		return $hrefStart.'<img title="FTP" src="'.$this->extensionGetDirectoryUrl().'/assets/img/ftp.png" width="'. $size .'" height="'. $size .'">'.$hrefEnd." ".$label;
	}

	function insert_channel_hyperlinked_icon($column_name, $id)
	{
		switch($column_name)
		{
			case \Filetrip_Constants::MEDIA_COLUMN_SLUG;
				$query_s = admin_url(\Filetrip_Constants::FILETRIP_DISTRIBUTOR_PAGE);
				$query_s = $query_s.'&media='.$id.'&source='.\Filetrip_Constants::TRANSFER_REQUEST_SOURCE['media'].'&';

				echo $this->get_icon($query_s.'channel='.$this->field_slug, 'Send file to Dropbox', '20', '');

				break;
			default:
				break;
		}
	}
}

