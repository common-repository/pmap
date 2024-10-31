<?php 
global $wpdb;

if (WPLANG == '') {
    define('PMAP_WPLANG', 'en_US');
} else {
    define('PMAP_WPLANG', WPLANG);
}

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

define('PMAP_PLUG_NAME', basename(dirname(__FILE__)));
define('PMAP_DIR', WP_PLUGIN_DIR. DS. PMAP_PLUG_NAME. DS);
define('PMAP_CLASSES_DIR', PMAP_DIR. 'classes'. DS);
define('PMAP_ASSETS_DIR', PMAP_DIR. 'assets'. DS);

define('PMAP_SITE_URL', get_bloginfo('wpurl'). '/'); 

define('PMAP_CODE', 'pmap');
define('PMAP_VERSION', '0.0.2');
define('PMAP_DB_PREF', 'PMAP_');

define('PMAP_ASSETS_URL', WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/assets/');