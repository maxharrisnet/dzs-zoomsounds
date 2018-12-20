<?php

function dzsap_preview_player(){

    global $dzsap;
	wp_enqueue_style('dzs.remove_wp_bar', $dzsap->base_url . 'tinymce/remove_wp_bar.css');
	wp_enqueue_script('dzsap_preview_player', $dzsap->base_url . 'shortcodegenerator/player_preview.js');
    ?>
<style>
    .wrap-for-player-preview{
        background-color: #9bbccc;
        position: relative;
    }
    html:not(.a){
        margin-top: 0!important;
        padding-top: 0!important;
        overflow: hidden;

        width: 800px;
        height: 300px;
    }
    html #wpcontent{
        margin: 0!important;
        padding: 0!important;
    }
    html #wpcontent .wrap{
        margin: 0!important;
        padding: 10px!important;
        height: 300px;
        padding: 10px;
    }
    html #wpcontent .audioplayer{
        position: absolute;
        top:50%;
        transform: translate3d(0,-50%,0);
    }
</style>
<div class="wrap wrap-for-player-preview">
    <?php


    $config = '';

    if(isset($_GET['config']) && $_GET['config']){
        $config = $_GET['config'];
    }
    $args = array(
            'source'=>'http://soundbible.com/mp3/Hummingbird-SoundBible.com-623295865.mp3',
            'config'=>$config,
            'artistname'=>'artist',
            'songname'=>'song',
            'thumb'=>'https://i.imgur.com/jCLdxjj.jpg',
    );

echo $dzsap->shortcode_player($args);
    ?>
</div><?php

}