<?php
/*
  Plugin Name: DZS ZoomSounds
  Plugin URI: http://digitalzoomstudio.net/
  Description: Creates and manages cool audio players with optional playlists.
  Version: 4.64
  Author: Digital Zoom Studio
  Author URI: http://digitalzoomstudio.net/ 
 */



include_once(dirname(__FILE__).'/dzs_functions.php');
if(!class_exists('DZSAudioPlayer')){
    include_once(dirname(__FILE__).'/class-dzsap.php');
}


define("DZSAP_VERSION", "4.64");
$dzsap = new DZSAudioPlayer();




if(class_exists('Cornerstone_Plugin')){


//    error_log('cornerstone - ok');


    add_action( 'wp_enqueue_scripts', array($dzsap,'cs_enqueue') );
    add_action( 'cornerstone_register_elements', array($dzsap,'cs_register_elements') );
    add_filter( 'cornerstone_icon_map', array($dzsap,'cs_icon_map') );
    add_action( '_cornerstone_home_before' ,  array($dzsap,'cs__home_before'));
    add_action( 'cornerstone_before_wp_editor' , array($dzsap,'cs_home_before'));
    add_action( 'cornerstone_load_builder' , array($dzsap,'cs_home_before'));


}




