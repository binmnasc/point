<?php
/*
* This is the page users will see logged out.
* You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
* The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
	<div class="lwa lwa-default"><?php //class must be here, and if this is a template, class name should be that of template directory ?>
        <form class="lwa-form" action="<?php echo esc_attr(LoginWithAjax::$url_login); ?>" method="post" id="login-ajax">
        	<div>
        	<span class="lwa-status"></span>
            <table>
                <tr class="lwa-username2">
                    <td class="lwa-username-input2 lwa-input row" colspan="2">
												<div class="col-sm-12 form-group form-row" >
														<div class="input-group input-group-icon">
																	<span class="input-group-addon">
																							<span class="icon"><i class="fa fa-user"></i></span>
																	</span>
					                        <input class="form-control input-text" id="lwa_user_login" autocomplete='on' Required placeholder="<?php _e('Usuário ou E-mail',
'porto'); ?>" type="text" name="log" >
														</div>
												</div>
                    </td>
                </tr>
                <tr class="lwa-password2">
                    <td class="lwa-password-input2 lwa-input row" colspan="2">
											<div class="col-sm-12 form-group form-row" >
													<div class="input-group input-group-icon">
																<span class="input-group-addon">
																						<span class="icon"><i class="fa fa-lock"></i></span>
																</span>
																<input class="form-control input-text" id="lwa_user_pass" autocomplete='on' Required placeholder="<?php _e('Senha',
'porto'); ?>" type="password" name="pwd" />
													</div>
											</div>
                    </td>
                </tr>
                <tr><td colspan="2"><?php do_action('login_form'); ?></td></tr>
                <tr class="lwa-submit">

									<td class="lwa-submit-links">

										<label class="checkbox1 ios7-switch">
											<input id="rememberme" class="input-checkbox" type="checkbox" name="rememberme" value="forever" />
											<span></span>
											<div class="remember-text"><?php esc_html_e('Remember Me',
'login-with-ajax') ?></div>
										</label>



											<?php if (!empty($lwa_data['remember'])): ?>
											<a class="lwa-links-remember" href="<?php echo esc_attr(LoginWithAjax::
$url_remember); ?>" title="<?php esc_attr_e('Password Lost and Found',
'login-with-ajax') ?>"><?php esc_attr_e('Lost your password?',
'login-with-ajax') ?></a>
											<?php endif; ?>
																	<?php if (get_option('users_can_register') && !empty($lwa_data['registration'])): ?>
											<br />
											<a href="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" class="lwa-links-register lwa-links-modal"><?php esc_html_e('Register',
'login-with-ajax') ?></a>
											<?php endif; ?>
									</td>

                    <td class="lwa-submit-button">

												<div class="social" >
												<?php
//echo do_shortcode( '[apsl-login theme="1"]' );

?>
												</div>

                        <input type="submit" class='button' name="wp-submit" id="lwa_wp-submit" value="<?php esc_attr_e('Log In',
'login-with-ajax'); ?>" tabindex="100" />
                        <input type="hidden" name="lwa_profile_link" value="<?php echo
esc_attr($lwa_data['profile_link']); ?>" />
                        <input type="hidden" name="login-with-ajax" value="login" />
												<?php if (!empty($lwa_data['redirect'])): ?>
												<input type="hidden" name="redirect_to" value="<?php echo esc_url($lwa_data['redirect']); ?>" />
												<?php endif; ?>
                    </td>

                </tr>
            </table>
            </div>
        </form>
        <?php if (!empty($lwa_data['remember']) && $lwa_data['remember'] == 1): ?>
        <form class="lwa-remember" action="<?php echo esc_attr(LoginWithAjax::$url_remember) ?>" method="post" style="display:none;" id="lwa-remember-ajax">
        	<div>
        	<span class="lwa-status"></span>
            <table>
                <tr>
                    <td>
                        <strong><?php esc_html_e("Forgotten Password",
'login-with-ajax'); ?></strong>
                    </td>
                </tr>
                <tr>
                    <td class="lwa-remember-email">
                        <?php $msg = __("Enter username or email",
'login-with-ajax'); ?>
                        <input type="text" name="user_login" class="lwa-user-remember" value="<?php echo
esc_attr($msg); ?>" onfocus="if(this.value == '<?php echo
esc_attr($msg); ?>'){this.value = '';}" onblur="if(this.value == ''){this.value = '<?php echo
esc_attr($msg); ?>'}" />
                        <?php do_action('lostpassword_form'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="lwa-remember-buttons">
                        <input type="submit" class='button btn' value="<?php esc_attr_e("Get New Password",
'login-with-ajax'); ?>" class="lwa-button-remember" />
                        <a href="#" class="lwa-links-remember-cancel"><?php esc_html_e("Cancel",
'login-with-ajax'); ?></a>
                        <input type="hidden" name="login-with-ajax" value="remember" />
                    </td>
                </tr>
            </table>
            </div>
        </form>

        <?php endif; ?>
		<?php if (get_option('users_can_register') && !empty($lwa_data['registration']) &&
$lwa_data['registration'] == 1): ?>
		<div class="lwa-register lwa-register-default lwa-modal" style="display:none;">
			<h4><?php esc_html_e('Register For This Site', 'login-with-ajax') ?></h4>
			<p><em class="lwa-register-tip"><?php esc_html_e('A password will be e-mailed to you.',
'login-with-ajax') ?></em></p>
			<form class="lwa-register-form" action="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" method="post" id="lwa-register-form-ajax">
				<div>
				<span class="lwa-status"></span>
				<p class="lwa-username">
					<label><?php esc_html_e('Username', 'login-with-ajax') ?><br />
					<input type="text" name="user_login" id="user_login" class="input" size="20" tabindex="10" /></label>
				</p>
				<p class="lwa-email">
					<label><?php esc_html_e('E-mail', 'login-with-ajax') ?><br />
					<input type="text" name="user_email" id="user_email" class="input" size="25" tabindex="20" /></label>
				</p>
				<?php do_action('register_form'); ?>
				<?php do_action('lwa_register_form'); ?>
				<p class="submit">
					<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="<?php esc_attr_e('Register',
'login-with-ajax'); ?>" tabindex="100" />
				</p>
		        <input type="hidden" name="login-with-ajax" value="register" />
		        </div>
			</form>
		</div>
		<?php endif; ?>
	</div>
	<script>
	jQuery(document).ready(function($) {
		//jQuery('#login-ajax').validate();
		//jQuery('#lwa-remember-ajax').validate();
		//jQuery('#lwa-register-form-ajax').validate();
	});
	</script>
