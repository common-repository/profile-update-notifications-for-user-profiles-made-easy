<?php

class UPUNA_Settings{
    
    public function __construct(){        
        add_action('admin_menu', array(&$this, 'add_menu'), 9);
        add_action('admin_init', array($this,'save_settings_page') );       
    }
    
    public function add_menu(){
        add_menu_page(__('UPME Profile Notifications', "upuna"), __("UPME Profile Notifications", "upuna"),'manage_options','upuna-settings',array(&$this,'settings'));
    }

    public function settings(){
        global $upuna,$upuna_settings_data;
        
        add_settings_section( 'upuna_section_profile_notify', __('Profile Notification Settings','upuna'), array( &$this, 'section_general_desc' ), 'upuna-notifications' );
        
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'upuna_section_profile_notify';
        $upuna_settings_data['tab'] = $tab;
        
        $tabs = $this->plugin_options_tabs('profile_notify',$tab);
   
        $upuna_settings_data['tabs'] = $tabs;
        
        $tab_content = $this->plugin_options_tab_content($tab);
        $upuna_settings_data['tab_content'] = $tab_content;
        
        ob_start();
		$upuna->template_loader->get_template_part( 'menu-page-container');
		$display = ob_get_clean();
		echo $display;
        
    
    }

    public function plugin_options_tabs($type,$tab) {
        $current_tab = $tab;
        $this->plugin_settings_tabs = array();
        
        switch($type){

            case 'profile_notify':
                $this->plugin_settings_tabs['upuna_section_profile_notify']  = __('Profile Notifications','upuna');
                break;

        }
        
        ob_start();
        ?>

        <h2 class="nav-tab-wrapper">
        <?php 
            foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
            $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
            $page = isset($_GET['page']) ? $_GET['page'] : '';
        ?>
                <a class="nav-tab <?php echo $active; ?> " href="?page=<?php echo $page; ?>&tab=<?php echo $tab_key; ?>"><?php echo $tab_caption; ?></a>
            
        <?php } ?>
        </h2>

        <?php
                
        return ob_get_clean();
    }
    
    public function plugin_options_tab_content($tab,$params = array()){
        global $upuna,$upuna_settings_data;
        
        $upuna_options = get_option('upuna_options');
        
        ob_start();
        switch($tab){
            
            
            case 'upuna_section_profile_notify':                
	            $data = isset($upuna_options['upuna_notify']) ? $upuna_options['upuna_notify'] : array();
	            $upuna_settings_data['notification_fields_list'] = isset($data['notification_fields_list']) ? $data['notification_fields_list'] : array();
                
                $upuna_settings_data['tab'] = $tab;
            
                $upuna->template_loader->get_template_part('profile-notify-settings');            
                break;
            
        }
        
        $display = ob_get_clean();
        return $display;
        
    }

    public function save_upuna_section_profile_notify(){
        $this->settings[] = array();
        if(isset($_POST['upuna_notify'])){
        	
            foreach($_POST['upuna_notify'] as $k=>$v){
                $this->settings[$k] = $v;
            }
        }

        $upuna_options = get_option('upuna_options');
        $upuna_options['upuna_notify'] = $this->settings;
        update_option('upuna_options',$upuna_options);
        
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
    }

    public function save_settings_page(){
        
        $upuna_settings_pages = array('upuna-settings');
      
        if(isset($_POST['upuna_tab']) && isset($_GET['page']) && in_array($_GET['page'],$upuna_settings_pages)){
            $tab = '';
            if ( isset ( $_POST['upuna_tab'] ) )
               $tab = $_POST['upuna_tab']; 

            if($tab != ''){
                $func = 'save_'.$tab;
                $this->$func();
            }          
        }
    }

    public function admin_notices(){
        ?>
        <div class="updated">
          <p><?php esc_html_e( 'Settings saved successfully.', 'upuna' ); ?></p>
       </div>
        <?php
    }
}


