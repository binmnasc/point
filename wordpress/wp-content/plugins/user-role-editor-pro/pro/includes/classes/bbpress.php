<?php
/**
 * Support for bbPress user roles and capabilities editing
 * 
 * Project: User Role Editor Pro WordPress plugin
 * Author: Vladimir Garagulya
 * Author email: support@role-editor.com
 * Author URI: https://www.role-editor.com
 * 
 **/

class URE_bbPress_Pro extends URE_bbPress {

    
    protected function __construct(Ure_Lib_Pro $lib) {
        
        parent::__construct($lib);
                
        add_action('plugins_loaded', array($this, 'do_not_reload_roles'), 9);
        add_filter('bbp_get_caps_for_role', array($this, 'get_caps_for_role'), 10, 2);
        add_action('wp_roles_init', array($this, 'add_forums_roles'), 10);
    }
    // end of __construct()
    
    /**
     * Exclude roles created by bbPress
     * 
     * @global array $wp_roles
     * @return array
     */
    public function get_roles() {
        
        global $wp_roles;                  
        
        return $wp_roles->roles;
    }
    // end of get_roles()
    
    /**
     * Replace bbPress bbp_add_forums_roles() in order to not overwrite bbPress roles loaded from the database
     * 
     * @param array $wp_roles
     * @return array
     */
    public function add_forums_roles($wp_roles = null) {
        
        // Attempt to get global roles if not passed in & not mid-initialization
	if ((null===$wp_roles) && !doing_action('wp_roles_init')) {
            $wp_roles = bbp_get_wp_roles();
	}
        
        $bbp_roles = bbp_get_dynamic_roles();
        // Loop through dynamic roles and add them (if needed) to the $wp_roles array
	foreach ($bbp_roles  as $role_id=>$details) {
            if (isset($wp_roles->roles[$role_id])) {
                continue;
            }
            $wp_roles->roles[$role_id] = $details;
            $wp_roles->role_objects[$role_id] = new WP_Role( $role_id, $details['capabilities']);
            $wp_roles->role_names[$role_id] = $details['name'];
	}
        
        return $wp_roles;
    }
    // end of add_forums_roles()
    
    
    /**
     * Returns true if role does not include any capability, false in other case
     * @param array $caps - list of capabilities: cap=>1 or cap=>0
     * @return boolean
     */
    private function is_role_without_caps($caps) {
        if (empty($caps)) {
            return true;
        }
        
        if (!is_array($caps) || count($caps)==0) {
            return true;
        }
        
        $nocaps = true;
        foreach($caps as $turned_on) {
            if ($turned_on) {
                $nocaps = false;
                break;
            }
        }
        
        return $nocaps;        
    }
    // end of is_role_without_caps()
    
    
    public function get_caps_for_role($caps, $role_id) {
    
        global $wp_roles;
            
        $bbp_roles = array(
            bbp_get_keymaster_role(),
            bbp_get_moderator_role(),
            bbp_get_participant_role(),
            bbp_get_spectator_role(),
            bbp_get_blocked_role()
            );
        if (!in_array($role_id, $bbp_roles)) {
            return $caps;
        }
        
        // to exclude endless recursion
        remove_filter('bbp_get_caps_for_role', array($this, 'get_caps_for_role'), 10);
        if (!isset($wp_roles)) {
            $wp_roles = new WP_Roles();
        }
        // restore it back
        add_filter('bbp_get_caps_for_role', array($this, 'get_caps_for_role'), 10, 2);
        
        if (!isset($wp_roles->roles[$role_id]) ||
            $this->is_role_without_caps($wp_roles->roles[$role_id]['capabilities'])) {
            return $caps;
        }
        
        $caps = $wp_roles->roles[$role_id]['capabilities'];
        
        return $caps;
    }
    // end of get_caps_for_role()
    
    
    public function do_not_reload_roles() {
        remove_action('bbp_loaded', 'bbp_filter_user_roles_option',  16);
        remove_action('bbp_roles_init', 'bbp_add_forums_roles', 1);
        remove_action('bbp_deactivation', 'bbp_remove_caps');
        register_uninstall_hook('bbpress/bbpress.php', 'bbp_remove_caps');
    }
    // end of do_not_reload_roles()
    
}
// end of URE_bbPress_Pro class