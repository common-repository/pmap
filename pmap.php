<?php 
/*
Plugin Name: pmap
Plugin URI: https://github.com/edtoken/pmap
Description: Adds field google MAPS in wordpress admin, and view map in post. Use shortcode [pmap] and attributes width, height to change pam options [pmap width= height=]
Version: 0.0.2 
Author: Ed
Author URI: https://vk.com/etoed
*/

 
/*  Copyright 2014 Edward (email: editied@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR. 'functions.php');

LoadClassPmap('installerPmap');
LoadClassPmap('corePmap');

installerPmap::update();
corePmap::init();


/**
 * 
 */

// class pMap 
// {
//     private $lang = 'en';
//     private $debug = true;
//     private $screens = array(
//         'page',
//         'post',
//         );

//     function __construct()
//     {

//         add_action( 'add_meta_boxes', array(&$this, 'custom_box') );
//         add_action( 'admin_enqueue_scripts', array(&$this, 'admin_scripts') );
//         add_action( 'wp_enqueue_scripts', array(&$this, 'user_scripts') );
//         add_action( 'save_post', array(&$this, 'pm_save_post') );
//         add_shortcode('pmap', array(&$this, 'pm_shortcode')); 

//     }

//     function pm_shortcode($attrs)
//     {
//         global $post;

//         $map_data = array(
//             'center'=>'marker',
//             'zoom'=>8,
//             'latitude'=>get_post_meta($post->ID, 'latitude', true),
//             'longitude'=>get_post_meta($post->ID, 'longitude', true),
//             'title'=>$post->post_title,
//             );

//         $width = 300;
//         $height = 200;


//         if($attrs['center']) $map_data['center'] = $attrs['center'];
//         if($attrs['zoom']) $map_data['zoom'] = $attrs['zoom'];

//         if($attrs['width']) $width = $attrs['width'];
//         if($attrs['height']) $height = $attrs['height'];


//         $map_data = json_encode($map_data);


//         $html = '

//             <textarea id="d_pmap_'.$post->ID.'" style="display:none;">
//                 '. $map_data . '
//             </textarea>

//             <div class="pmap" data-map_id="'.$post->ID.'" id="pmap_' . $post->ID . '" style="width:'.$width.'px;height:'.$height.'px;">
            
//             </div>
//             ';
        
//         return $html;
//     }

//     function pm_save_post($post_id)
//     {

//          if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
//       return;

//       // Check permissions
//       if ( 'page' == $_POST['post_type'] ) 
//       {
//         if ( !current_user_can( 'edit_page', $post_id ) )
//             return;
//       }
//       else
//       {
//         if ( !current_user_can( 'edit_post', $post_id ) )
//             return;
//       } 

//       if(in_array(get_post_type($post_id), $this->screens))
//       {

//         if($_POST['latitude']) update_post_meta($post_id, 'latitude', $_POST['latitude']);
//         if($_POST['longitude']) update_post_meta($post_id, 'longitude', $_POST['longitude']);

//       }

//     }

//     function user_scripts()
//     {
//         wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language='.$this->lang.'', false, '3');
//         wp_enqueue_script('googlemaps');

//         if($this->debug){
//             wp_register_script( 'pmields_user_js', plugins_url( 'assets/js/main-user.js', __FILE__ ), false, '1.0.0' );
//         }else{
//             wp_register_script( 'pmields_user_js', plugins_url( 'assets/js/main-user.min.js', __FILE__ ), false, '1.0.0' );
//         }
//          wp_enqueue_script('backbone');
//          wp_enqueue_script( 'pmields_user_js', '');

//     }

//     function admin_scripts()
//     {

//         wp_register_style( 'pmields_css', plugins_url( 'assets/styles/main.min.css', __FILE__ ), false, '1.0.0' );
//         wp_enqueue_style( 'pmields_css' );

//         wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language='.$this->lang.'', false, '3');
//         wp_enqueue_script('googlemaps');

//         if($this->debug){
//             wp_register_script( 'pmields_adm_js', plugins_url( 'assets/js/main-admin.js', __FILE__ ), false, '1.0.0' );
//         }else{
//             wp_register_script( 'pmields_adm_js', plugins_url( 'assets/js/main-admin.min.js', __FILE__ ), false, '1.0.0' );
//         }
//         wp_enqueue_script( 'pmields_adm_js', '', array( 'jquery', 'backbone' ));

        

//     }

//     function custom_box()
//     {
//         foreach ( $this->screens as $screen ) {

//             add_meta_box(
//                 'pmapid',
//                 __( 'pmap custom box', 'pmap' ),
//                 array(&$this, 'inner_custom_box'),
//                 $screen
//             );
//         }
//     }

//     function inner_custom_box()
//     {

//         global $post;

//         $file = plugin_dir_path(__FILE__) .  'assets/templates/inner_custom_box.tpl';

//         if(!$file)
//         {
//             echo 'error to load custom box tpl';
//             return;
//         }

//         $content = file_get_contents($file);
        
//         $str = array('<%= latitude %>', '<%= longitude %>');
        
//         $replace   = array(
//             get_post_meta($post->ID, 'latitude', true), 
//             get_post_meta($post->ID, 'longitude', true), 
//         );

//         $content = str_replace($str, $replace, $content);

//         echo $content;

//         unset($content);

//     }
// }

// new pMap();