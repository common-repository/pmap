<?php

class installerPmap { 
    
    static public $update_to_version_method = '';

    /**
     * plugin init function
     * @return [type] [description]
     */
    static public function init() {
    }

    static public function update() {
        
        global $wpdb;

        $curVersion = get_option($wpdb->prefix . PMAP_DB_PREF . 'version', 0);
        $installVersion = (int) get_option($wpdb->prefix . PMAP_DB_PREF . 'installed', 0);

        if(!$curVersion || version_compare(PMAP_VERSION, $curVersion, '>')) {
            self::init();
            update_option($wpdb->prefix . 'PMAPp_version', PMAP_VERSION);
        }

    }

}