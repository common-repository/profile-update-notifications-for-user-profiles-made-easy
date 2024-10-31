<?php
/*
  Plugin Name: Profile Update Notifications for User Profiles Made Easy
  Plugin URI: http://www.profileplugin.com/upme-profile-noitifications
  Description: Send email notifications when updating custom fields in UPME profile
  Version: 1.0
  Author: Rakhitha Nimesh
  Author URI: http://www.wpexpertdeveloper.com
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

function upuna_get_plugin_version() {
    $default_headers = array('Version' => 'Version');
    $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');
    return $plugin_data['Version'];
}

/* Validating existence of required plugins */
add_action( 'plugins_loaded', 'upuna_plugin_init' );

function upuna_plugin_init(){
    if(!class_exists('UPME')){
        add_action( 'admin_notices', 'upuna_plugin_admin_notice' );
    }else{
        
    }
}

function upuna_plugin_admin_notice() {
   $message = __('<strong>Profile Update Notifications for User Profiles Made Easy</strong> requires <strong>User Profiles Made Easy</strong> plugin to function properly','upmeinc');
   echo '<div class="error"><p>'.$message.'</p></div>';
}

if( !class_exists( 'UPME_Profile_Notifications' ) ) {
    
    class UPME_Profile_Notifications{
    
        private static $instance;

        public static function instance() {
            
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof UPME_Profile_Notifications ) ) {
                self::$instance = new UPME_Profile_Notifications();
                self::$instance->setup_constants();

                add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
                self::$instance->includes();
                
                add_action('admin_enqueue_scripts',array(self::$instance,'load_admin_scripts'),9);
                add_action('wp_enqueue_scripts',array(self::$instance,'load_scripts'),9);
                 
                self::$instance->template_loader    = new UPUNA_Template_Loader();
                self::$instance->settings           = new UPUNA_Settings();

                add_filter('upme_trigger_field_update',array(self::$instance,'trigger_field_update'),10,2);

            }
            return self::$instance;
        }

        public function setup_constants() { }
        
        public function load_scripts(){ 

        }
        
        public function load_admin_scripts(){
            
        }
        
        private function includes() {
            
            require_once UPUNA_PLUGIN_DIR . 'functions.php';
            require_once UPUNA_PLUGIN_DIR . 'classes/class-upuna-template-loader.php';      
            require_once UPUNA_PLUGIN_DIR . 'classes/class-upuna-settings.php'; 

            if ( is_admin() ) {
            }
        }

        public function load_textdomain() {
            
        }

        public function trigger_field_update($fields,$params){
            $upuna_options = get_option('upuna_options');
            $data = isset($upuna_options['upuna_notify']) ? $upuna_options['upuna_notify'] : array();
            $notification_fields_list = isset($data['notification_fields_list']) ? $data['notification_fields_list'] : array();
            
            add_action('upme_profile_field_update_triggered',array($this,'profile_field_update_triggered')); 
         
            $fields = $notification_fields_list;
            return $fields;
        }

        public function profile_field_update_triggered($params){
            global $upme_save;
            $upme_save->notify_field_update = true;
        }
        
    }
}

// Plugin version
if ( ! defined( 'UPUNA_VERSION' ) ) {
    define( 'UPUNA_VERSION', '1.0' );
}

// Plugin Folder Path
if ( ! defined( 'UPUNA_PLUGIN_DIR' ) ) {
    define( 'UPUNA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL
if ( ! defined( 'UPUNA_PLUGIN_URL' ) ) {
    define( 'UPUNA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}




function UPME_Profile_Notifications() {
    global $upuna;
    $upuna = UPME_Profile_Notifications::instance();
}

UPME_Profile_Notifications();





