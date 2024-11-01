<?php

    /* 
    Plugin Name: wpNoPin
    Description: Adds meta tag that prevents content to be pinned on pinterest.com
    Author: Stephan Gerlach
    Version: 1.0 
    Author URI: http://www.computersniffer.com
    */  
    
    // add admin menu
    add_action('admin_menu', 'wpNoPin_admin_menu');
    function wpNoPin_admin_menu() {
        add_menu_page('wpNoPin', 'wpNoPin', 'administrator', 'wpNoPin_code', 'wpNoPin_code');
    }
    

    // options page    
    function wpNoPin_code() {
        
        global $wpdb;
        
        // check permissions
        if (!current_user_can('manage_options'))  {
    	   	wp_die( __('You do not have sufficient permissions to access this page.') );
    	}
        
        // check if update is sent
        if (isset($_POST['wpnopin'])) {
            
            // try to get verification key
            $opt = get_option('wpnopin_settings');
            
            // if verification key exists update key
            if (!(!$opt) || $opt=='') {
                update_option('wpnopin_settings',$_POST['wpnopin']);
            }
            // if no key exists (first run) insert key
            else {
                add_option('wpnopin_settings',$_POST['wpnopin']);
            }
            
        }
        
        // load verification key
        $opt = get_option('wpnopin_settings');
        if (!$opt) {
            $opt = '';
        }
        
        // form output
    	echo '<div class="wrap">';
        echo '<h2>wpNoPin Options</h2>';
        
        echo '<form action="" method="post">';
        echo '<label for="csalexa">Block pinterest.com</label>: 
                <input type="radio" name="wpnopin" value="1" ';
                if ($opt == 1) { echo ' checked ';}
                echo '/> Yes &nbsp;&nbsp;<input type="radio" name="wpnopin" value="0" ';
                if ($opt == 0) { echo ' checked ';}
                echo '/> No ';
        echo '<br /><input type="submit" name="wpnopin_save" value="Save" />';
        echo '</form>';
        
    }
        
    
    // add action for inserting code in <head>
    add_action('wp_head', 'wpNoPin_add_code');
    
    function wpNoPin_add_code() {
        
        global $wpdb;
        $opt = get_option('wpnopin_settings');
        if (!(!$opt) && $opt==1) {
            echo '<meta name="pinterest" content="nopin" />'."\n";
        }
        
    }
    
    
    
?>