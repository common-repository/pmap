<?php 

class corePmap {

    public static $debug = false;
    public static $lang = 'en';
    public static $screens = array('page', 'post');

    public static $fields = array(

        'latitude'=>array(
            'name'=>'latitude',
            'id'=>'pm_latitude',
            'value'=>''
            ),

        'longitude'=>array(
            'name'=>'longitude',
            'id'=>'pm_longitude',
            'value'=>''
            ),

        );

    public static function init()
    {

        add_action( 'add_meta_boxes', array('corePmap', 'custom_box') );
        add_action( 'admin_enqueue_scripts', array('corePmap', 'admin_scripts') );
        add_action( 'wp_enqueue_scripts', array('corePmap', 'user_scripts') );
        add_action( 'save_post', array('corePmap', 'pm_save_post') );
        add_shortcode('pmap', array('corePmap', 'pm_shortcode')); 

    }

    public static function custom_box(){

        foreach ( self::$screens as $screen ) {

            add_meta_box(
                'pmapid',
                __( 'pmap custom box', 'pmap' ),
                array('corePmap', 'inner_custom_box'),
                $screen
            );
        }

    }

    public static function inner_custom_box()
    {

         global $post;

        $file = PMAP_ASSETS_DIR . '/templates/inner_custom_box.tpl';


        if(!file_exists($file))
        {
            echo 'error to load custom box tpl';
            return;
        }

        $content = file_get_contents($file);

        $str = array();
        $replace = array();

        foreach (self::$fields as $field => $field_data) {

            foreach ($field_data as $f_k => $f_v) {

                switch($f_k)
                {

                    case 'value':

                        $val = (!$f_v) ? get_post_meta($post->ID, $field, true) : $f_v;

                        break;

                    default:

                        $val = $f_v;

                        break;
                }


                $field_name_str = $field . '_' . $f_k;

                $str[] = '<%= ' . $field_name_str . ' %>';
                $replace[] = $val; 

                unset($val);


            }

        }


        $content = str_replace($str, $replace, $content);

        echo '
        <script>
            var pmapd = function()
            {
                return ' . json_encode(self::$fields) . ';
            }
        </script>
        ';
    
        echo $content;

        unset($content);

    }

    public static function admin_scripts(){

        global $post;

        wp_register_style( 'pmields_css', PMAP_ASSETS_URL . 'styles/main.min.css', false, '1.0.0' );
        wp_enqueue_style( 'pmields_css' );

        wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language='.self::$lang.'', false, '3');
        wp_enqueue_script('googlemaps');

        if(!array_search($post->post_type, self::$screens)) return;

        if(self::$debug){
            wp_register_script( 'pmields_adm_js', PMAP_ASSETS_URL . 'js/main-admin.js', false, '1.0.0' );
        }else{
            //wp_register_script( 'pmields_adm_js', PMAP_ASSETS_URL . 'js/main-admin.min.js', false, '1.0.0' );
            wp_register_script( 'pmields_adm_js', PMAP_ASSETS_URL . 'js/main-admin.js', false, '1.0.0' );            
        }
        wp_enqueue_script( 'pmields_adm_js', '', array( 'jquery', 'backbone' ));


    }
    public static function user_scripts(){

         wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language='.self::$lang.'', false, '3');
        wp_enqueue_script('googlemaps');

        if(self::$debug){
            wp_register_script( 'pmields_user_js', PMAP_ASSETS_URL . 'js/main-user.js', false, '1.0.0' );
        }else{
            wp_register_script( 'pmields_user_js', PMAP_ASSETS_URL . 'js/main-user.min.js', false, '1.0.0' );
        }
         wp_enqueue_script('backbone');
         wp_enqueue_script( 'pmields_user_js', '');

    }
    public static function pm_save_post($post_id){


        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

        // Check permissions
        if ( 'page' == $_POST['post_type'] ) 
        {
        if ( !current_user_can( 'edit_page', $post_id ) )
        return;
        }
        else
        {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return;
        } 

        if(in_array(get_post_type($post_id), self::$screens))
        {


            foreach (self::$fields as $field => $field_data) {

                if($_POST[$field_data['name']]){

                    update_post_meta($post_id, $field_data['name'], $_POST[$field_data['name']]);

                }
            
            }

        }

    }

    public  static function pm_shortcode($attrs){
        
      global $post;

        $map_data = array(
            'center'=>'marker',
            'zoom'=>14,
            'latitude'=>get_post_meta($post->ID, self::$fields['latitude']['name'], true),
            'longitude'=>get_post_meta($post->ID, self::$fields['longitude']['name'], true),
            'title'=>$post->post_title,
            );



        if($attrs['center']) $map_data['center'] = $attrs['center'];
        if($attrs['zoom']) $map_data['zoom'] = $attrs['zoom'];

        $width = (!$attrs['width']) ? 500 : $attrs['width'];
        $height = (!$attrs['height']) ? 400 : $attrs['height'];


        $map_data = json_encode($map_data);


        $html = '

            <textarea id="d_pmap_'.$post->ID.'" style="display:none;">
                '. $map_data . '
            </textarea>

            <div class="pmap" data-map_id="'.$post->ID.'" id="pmap_' . $post->ID . '" style="width:'.$width.'px;height:'.$height.'px;">
            
            </div>
            ';
        
        return $html;

    }

}