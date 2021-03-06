<?php 	
		defined( 'ABSPATH' ) or die( "No script kiddies please!" );
		$options = get_option( APSL_SETTINGS ); 
		$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';

		$encoded_url = urlencode( $redirect_to );
?>

<div class='apsl-login-networks theme-<?php echo $options['apsl_icon_theme']; ?> clearfix'>
	<span class='apsl-login-new-text'><?php echo $options['apsl_title_text_field']; ?></span>	
	<?php if(isset($_SESSION['apsl_login_error_flag']) && $_SESSION['apsl_login_error_flag'] == '1'){ //if( isset($_REQUEST['error']) || isset($_REQUEST['denied']) ){ ?> 
	<div class='apsl-error'>
		<?php if(isset($options['apsl_login_error_message']) && $options['apsl_login_error_message'] !=''){ echo $options['apsl_login_error_message']; }else{ _e('You have Access Denied. Please authorize the app to login.', APSL_TEXT_DOMAIN ); } ?>
	</div>
	<?php
	unset($_SESSION['apsl_login_error_flag']);
	} ?>

	<div class='social-networks'>
	<?php foreach($options['network_ordering'] as $key=>$value): ?>
	<?php	if($options["apsl_{$value}_settings"]["apsl_{$value}_enable"]==='enable'){ ?>
	 <a href="<?php echo wp_login_url() ?>?apsl_login_id=<?php echo $value; ?>_login<?php
                if ( $encoded_url ) {
                    echo "&state=" . base64_encode( "redirect_to=$encoded_url" );
                }
                ?>" title='<?php if(isset($options['apsl_each_link_title_attribute']) && $options['apsl_each_link_title_attribute'] !=''){ echo $options['apsl_each_link_title_attribute']; }else{ _e('Login with', APSL_TEXT_DOMAIN ); } echo ' '.$value; ?>' >
	 <div class="apsl-icon-block apsl-icon-<?php echo $value; ?> <?php if($value=='buffer'){ echo "buffer"; } ?> clearfix">
		<i class="fa fa-<?php echo $value; ?>"></i>
		<span class="apsl-login-text"><?php if(isset($options['apsl_login_short_text']) && $options['apsl_login_short_text'] !=''){ echo $options['apsl_login_short_text']; }else{ _e('Login', APSL_TEXT_DOMAIN ); } ?></span>
		<span class="apsl-long-login-text"><?php if(isset($options['apsl_login_with_long_text']) && $options['apsl_login_with_long_text'] !=''){ echo $options['apsl_login_with_long_text']; }else{ _e('Login with', APSL_TEXT_DOMAIN ); } ?><?php echo ' '.$value; ?></span>
	 </div>
	 </a>
		<?php } ?>
	<?php endforeach; ?>
	</div>
</div>
